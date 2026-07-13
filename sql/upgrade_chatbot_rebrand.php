<?php
/**
 * Replace legacy Biver Royalty Homes copy in chatbot tables and stored chat messages.
 * Run once: php sql/upgrade_chatbot_rebrand.php
 * Or browser: http://localhost/hope_architects/sql/upgrade_chatbot_rebrand.php
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/chatbot/chatbot-config.php';
require_once dirname(__DIR__) . '/chatbot/includes/ChatbotRepository.php';

$isCli = PHP_SAPI === 'cli';

function rebrandOut(string $line): void
{
    global $isCli;
    if ($isCli) {
        echo $line . PHP_EOL;
        return;
    }
    echo htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . "<br>\n";
}

/** @return list<string> */
function rebrandColumns(PDO $pdo, string $table): array
{
    $cols = [];
    $stmt = $pdo->query('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`');
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        $name = (string) ($col['Field'] ?? '');
        if (preg_match('/(text|content|message|title|answer|question|response)/i', $name)) {
            $cols[] = $name;
        }
    }

    return $cols;
}

try {
    $pdo = getDatabaseConnection();
    $siteName = (string) (chatbotSiteConfig()['siteName'] ?? 'Hope Architects');

    $searchReplace = [
        'Biver Royalty Homes Ltd' => $siteName,
        'Biver Royalty Homes'   => $siteName,
        'BiverRoyalty Homes'      => $siteName,
        'Biver Royalty'           => $siteName,
    ];

    $tables = [
        'chatbot_responses',
        'chatbot_knowledgebase',
        'chatbot_faqs',
        'chat_messages',
    ];

    $totalUpdated = 0;

    foreach ($tables as $table) {
        try {
            $pdo->query('SELECT 1 FROM `' . $table . '` LIMIT 1');
        } catch (Throwable) {
            rebrandOut("Skip missing table: {$table}");
            continue;
        }

        $columns = rebrandColumns($pdo, $table);
        if ($columns === []) {
            rebrandOut("No text columns in {$table}");
            continue;
        }

        $tableUpdated = 0;

        foreach ($columns as $column) {
            foreach ($searchReplace as $search => $replace) {
                $sql = "UPDATE `{$table}` SET `{$column}` = REPLACE(`{$column}`, :search, :replace)
                        WHERE `{$column}` LIKE :like";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'search'  => $search,
                    'replace' => $replace,
                    'like'    => '%' . $search . '%',
                ]);
                $tableUpdated += $stmt->rowCount();
            }
        }

        $totalUpdated += $tableUpdated;
        rebrandOut("Updated {$tableUpdated} row(s) in {$table}");
    }

    // Normalize stored session welcome messages to current branding.
    $welcome = chatbotWelcomeMessage();
    $stmt = $pdo->prepare(
        "UPDATE chat_messages
         SET message = :welcome
         WHERE sender_type = 'bot'
           AND message_type = 'system'
           AND (
             message LIKE '%Biver%'
             OR message LIKE '%Welcome to%virtual property assistant%'
           )"
    );
    $stmt->execute(['welcome' => $welcome]);
    $welcomeUpdated = $stmt->rowCount();
    $totalUpdated += $welcomeUpdated;
    rebrandOut("Refreshed {$welcomeUpdated} stored welcome message(s) in chat_messages");

    ChatbotRepository::invalidateContentCache();

    rebrandOut("Done. Total row updates: {$totalUpdated}");
} catch (Throwable $e) {
    $msg = 'Upgrade failed: ' . $e->getMessage();
    if ($isCli) {
        fwrite(STDERR, $msg . PHP_EOL);
    } else {
        echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    }
    exit(1);
}
