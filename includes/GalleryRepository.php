<?php
/**
 * Standalone site gallery images (not property listings).
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
require_once __DIR__ . '/site_paths.php';

final class GalleryRepository
{
    public static function ensureSchema(): void
    {
        static $done = false;
        if ($done) {
            return;
        }

        $pdo = getDatabaseConnection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS gallery_images (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                title VARCHAR(160) DEFAULT NULL,
                caption VARCHAR(255) DEFAULT NULL,
                category VARCHAR(80) DEFAULT NULL,
                image_path VARCHAR(500) NOT NULL,
                sort_order INT NOT NULL DEFAULT 0,
                is_published TINYINT(1) NOT NULL DEFAULT 1,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_gallery_published_sort (is_published, sort_order, id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        $done = true;
    }

    /** @return list<array<string, mixed>> */
    public static function getAll(bool $publishedOnly = false): array
    {
        self::ensureSchema();
        $pdo = getDatabaseConnection();
        $sql = 'SELECT * FROM gallery_images';
        if ($publishedOnly) {
            $sql .= ' WHERE is_published = 1';
        }
        $sql .= ' ORDER BY sort_order ASC, id DESC';

        return array_map([self::class, 'format'], $pdo->query($sql)->fetchAll() ?: []);
    }

    public static function getById(int $id): ?array
    {
        self::ensureSchema();
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('SELECT * FROM gallery_images WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? self::format($row) : null;
    }

    /** @param array<string, mixed> $input */
    public static function create(array $input): int
    {
        self::ensureSchema();
        $pdo = getDatabaseConnection();
        $data = self::sanitize($input);

        $stmt = $pdo->prepare(
            'INSERT INTO gallery_images
             (title, caption, category, image_path, sort_order, is_published)
             VALUES
             (:title, :caption, :category, :image_path, :sort_order, :is_published)'
        );
        $stmt->execute($data);

        return (int) $pdo->lastInsertId();
    }

    /** @param array<string, mixed> $input */
    public static function update(int $id, array $input): bool
    {
        self::ensureSchema();
        $existing = self::getById($id);
        if ($existing === null) {
            return false;
        }

        $pdo = getDatabaseConnection();
        $merged = [
            'title'       => array_key_exists('title', $input) ? $input['title'] : ($existing['title'] ?? ''),
            'caption'     => array_key_exists('caption', $input) ? $input['caption'] : ($existing['caption'] ?? ''),
            'category'    => array_key_exists('category', $input) ? $input['category'] : ($existing['category'] ?? ''),
            'imagePath'   => trim((string) ($input['imagePath'] ?? $input['image_path'] ?? $existing['imagePath'] ?? '')),
            'sortOrder'   => array_key_exists('sortOrder', $input) || array_key_exists('sort_order', $input)
                ? ($input['sortOrder'] ?? $input['sort_order'])
                : ($existing['sortOrder'] ?? 0),
            'isPublished' => array_key_exists('isPublished', $input) || array_key_exists('is_published', $input)
                ? ($input['isPublished'] ?? $input['is_published'])
                : ($existing['isPublished'] ?? true),
        ];

        $data = self::sanitize($merged);
        $data['id'] = $id;

        $stmt = $pdo->prepare(
            'UPDATE gallery_images SET
                title = :title,
                caption = :caption,
                category = :category,
                image_path = :image_path,
                sort_order = :sort_order,
                is_published = :is_published
             WHERE id = :id'
        );

        return $stmt->execute($data);
    }

    public static function delete(int $id): bool
    {
        self::ensureSchema();
        $item = self::getById($id);
        if ($item === null) {
            return false;
        }

        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('DELETE FROM gallery_images WHERE id = :id');
        $ok = $stmt->execute(['id' => $id]);

        if ($ok && !empty($item['imagePath'])) {
            require_once __DIR__ . '/GalleryUploadService.php';
            GalleryUploadService::deleteStoredPath((string) $item['imagePath']);
        }

        return $ok;
    }

    /** @return array<string, int> */
    public static function getStats(): array
    {
        self::ensureSchema();
        $pdo = getDatabaseConnection();
        $row = $pdo->query(
            'SELECT COUNT(*) AS total, SUM(is_published = 1) AS published FROM gallery_images'
        )->fetch() ?: [];

        return [
            'total'     => (int) ($row['total'] ?? 0),
            'published' => (int) ($row['published'] ?? 0),
        ];
    }

    /** @return list<string> */
    public static function getCategories(bool $publishedOnly = true): array
    {
        self::ensureSchema();
        $pdo = getDatabaseConnection();
        $sql = 'SELECT DISTINCT category FROM gallery_images WHERE category IS NOT NULL AND category <> \'\'';
        if ($publishedOnly) {
            $sql .= ' AND is_published = 1';
        }
        $sql .= ' ORDER BY category ASC';
        $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN) ?: [];

        return array_values(array_filter(array_map('strval', $rows)));
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private static function sanitize(array $input): array
    {
        $title = trim((string) ($input['title'] ?? ''));
        $caption = trim((string) ($input['caption'] ?? ''));
        $category = trim((string) ($input['category'] ?? ''));
        $imagePath = trim((string) ($input['imagePath'] ?? $input['image_path'] ?? ''));
        $sortOrder = (int) ($input['sortOrder'] ?? $input['sort_order'] ?? 0);
        $isPublished = filter_var($input['isPublished'] ?? $input['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

        if ($imagePath === '') {
            throw new InvalidArgumentException('Image path is required.');
        }

        return [
            'title'        => $title !== '' ? mb_substr($title, 0, 160) : null,
            'caption'      => $caption !== '' ? mb_substr($caption, 0, 255) : null,
            'category'     => $category !== '' ? mb_substr($category, 0, 80) : null,
            'image_path'   => mb_substr($imagePath, 0, 500),
            'sort_order'   => max(0, $sortOrder),
            'is_published' => $isPublished ? 1 : 0,
        ];
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function format(array $row): array
    {
        $path = (string) ($row['image_path'] ?? '');
        $url = publicAssetUrl($path) ?? ('/' . ltrim($path, '/'));

        return [
            'id'          => (int) $row['id'],
            '_id'         => (string) $row['id'],
            'title'       => $row['title'] ?? '',
            'caption'     => $row['caption'] ?? '',
            'category'    => $row['category'] ?? '',
            'imagePath'   => $path,
            'imageUrl'    => $url,
            'sortOrder'   => (int) ($row['sort_order'] ?? 0),
            'isPublished' => (bool) ($row['is_published'] ?? 0),
            'createdAt'   => $row['created_at'] ?? null,
            'updatedAt'   => $row['updated_at'] ?? null,
        ];
    }
}
