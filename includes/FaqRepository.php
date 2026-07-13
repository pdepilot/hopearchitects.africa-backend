<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';

final class FaqRepository
{
    /** @return list<array<string, mixed>> */
    public static function getAll(bool $activeOnly = false): array
    {
        $pdo = getDatabaseConnection();
        $sql = 'SELECT * FROM chatbot_faqs';
        if ($activeOnly) {
            $sql .= ' WHERE is_active = 1';
        }
        $sql .= ' ORDER BY priority DESC, id ASC';

        return array_map([self::class, 'format'], $pdo->query($sql)->fetchAll());
    }

    /** @return list<array<string, mixed>> */
    public static function getPublic(): array
    {
        return self::getAll(true);
    }

    public static function getById(int $id): ?array
    {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('SELECT * FROM chatbot_faqs WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? self::format($row) : null;
    }

    /** @param array<string, mixed> $input */
    public static function create(array $input): int
    {
        $pdo = getDatabaseConnection();
        $data = self::sanitize($input);

        $stmt = $pdo->prepare(
            'INSERT INTO chatbot_faqs
             (question, answer, keywords, category, priority, match_score_threshold, is_active)
             VALUES
             (:question, :answer, :keywords, :category, :priority, :match_score_threshold, :is_active)'
        );
        $stmt->execute($data);
        self::invalidateChatbotCache();

        return (int) $pdo->lastInsertId();
    }

    /** @param array<string, mixed> $input */
    public static function update(int $id, array $input): bool
    {
        $pdo = getDatabaseConnection();
        $data = self::sanitize($input);
        $data['id'] = $id;

        $stmt = $pdo->prepare(
            'UPDATE chatbot_faqs SET
                question = :question,
                answer = :answer,
                keywords = :keywords,
                category = :category,
                priority = :priority,
                match_score_threshold = :match_score_threshold,
                is_active = :is_active
             WHERE id = :id'
        );
        $ok = $stmt->execute($data);
        self::invalidateChatbotCache();

        return $ok;
    }

    public static function delete(int $id): bool
    {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('DELETE FROM chatbot_faqs WHERE id = :id');
        $ok = $stmt->execute(['id' => $id]);
        self::invalidateChatbotCache();

        return $ok;
    }

    /** @return array<string, int> */
    public static function getStats(): array
    {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->query(
            'SELECT COUNT(*) AS total, SUM(is_active = 1) AS active FROM chatbot_faqs'
        );
        $row = $stmt->fetch() ?: [];

        return [
            'total'  => (int) ($row['total'] ?? 0),
            'active' => (int) ($row['active'] ?? 0),
        ];
    }

    /** @param array<string, mixed> $row */
    private static function format(array $row): array
    {
        $keywords = $row['keywords'] ?? '[]';
        if (is_string($keywords)) {
            $keywords = json_decode($keywords, true) ?: [];
        }

        return [
            'id'                  => (int) $row['id'],
            'question'            => (string) $row['question'],
            'answer'              => (string) $row['answer'],
            'keywords'            => is_array($keywords) ? $keywords : [],
            'category'            => (string) ($row['category'] ?? 'general'),
            'priority'            => (int) ($row['priority'] ?? 50),
            'matchScoreThreshold' => (float) ($row['match_score_threshold'] ?? 0.4),
            'isActive'            => (bool) ($row['is_active'] ?? true),
            'createdAt'           => (string) ($row['created_at'] ?? ''),
            'updatedAt'           => (string) ($row['updated_at'] ?? ''),
        ];
    }

    /** @param array<string, mixed> $input @return array<string, mixed> */
    private static function sanitize(array $input): array
    {
        $keywords = $input['keywords'] ?? [];
        if (is_string($keywords)) {
            $keywords = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $keywords) ?: [])));
        }

        return [
            'question'              => self::clip((string) ($input['question'] ?? ''), 500),
            'answer'                => self::clip((string) ($input['answer'] ?? ''), 10000),
            'keywords'              => json_encode(is_array($keywords) ? $keywords : [], JSON_UNESCAPED_UNICODE),
            'category'              => self::clip((string) ($input['category'] ?? 'general'), 80),
            'priority'              => max(0, min(100, (int) ($input['priority'] ?? 50))),
            'match_score_threshold' => max(0.1, min(1.0, (float) ($input['matchScoreThreshold'] ?? $input['match_score_threshold'] ?? 0.4))),
            'is_active'             => filter_var($input['isActive'] ?? $input['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
        ];
    }

    private static function invalidateChatbotCache(): void
    {
        $repoFile = dirname(__DIR__) . '/chatbot/includes/ChatbotRepository.php';
        if (is_file($repoFile)) {
            require_once $repoFile;
            ChatbotRepository::invalidateContentCache();
        }
    }

    private static function clip(string $value, int $max): string
    {
        $value = trim($value);
        return strlen($value) <= $max ? $value : substr($value, 0, $max);
    }
}
