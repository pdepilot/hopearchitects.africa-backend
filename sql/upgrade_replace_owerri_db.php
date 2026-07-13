<?php
/**
 * Replace Owerri / Imo references in database content with Lagos / Abuja.
 * Run once: php sql/upgrade_replace_owerri_db.php
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';

$replacements = [
    'New Owerri, Imo State' => 'Lekki Phase 1, Lagos',
    'New Owerri' => 'Lekki, Lagos',
    'Owerri, Imo State' => 'Lagos, Nigeria',
    'Owerri, Imo' => 'Lagos, Nigeria',
    'Aladinma, Owerri' => 'Victoria Island, Lagos',
    'Wetheral Road, Owerri' => 'Admiralty Way, Lagos',
    'Owerri' => 'Lagos',
    'Imo State' => 'Lagos & Abuja',
    'Imo' => 'Lagos',
];

try {
    $pdo = getDatabaseConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = [
        'properties' => ['title', 'location', 'description'],
        'service_areas' => ['title', 'tag', 'description', 'meta1_text', 'meta2_text'],
        'testimonials' => ['name', 'message', 'role_label'],
        'chatbot_faqs' => ['question', 'answer', 'keywords'],
        'chatbot_knowledge' => ['title', 'content', 'keywords'],
    ];

    foreach ($tables as $table => $columns) {
        foreach ($columns as $column) {
            foreach ($replacements as $from => $to) {
                try {
                    $stmt = $pdo->prepare("UPDATE `{$table}` SET `{$column}` = REPLACE(`{$column}`, :from_val, :to_val) WHERE `{$column}` LIKE :like_val");
                    $stmt->execute([
                        'from_val' => $from,
                        'to_val'   => $to,
                        'like_val' => '%' . $from . '%',
                    ]);
                    if ($stmt->rowCount() > 0) {
                        echo "Updated {$stmt->rowCount()} row(s) in {$table}.{$column}: '{$from}' -> '{$to}'\n";
                    }
                } catch (Throwable $e) {
                    // Table or column may not exist in this install — skip silently
                }
            }
        }
    }

    echo "Database Owerri/Imo replacement complete.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Upgrade failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
