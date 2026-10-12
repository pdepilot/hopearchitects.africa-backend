<?php
/**
 * Public API: published gallery photos.
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once dirname(__DIR__) . '/includes/GalleryRepository.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

try {
    $category = trim((string) ($_GET['category'] ?? ''));
    $items = GalleryRepository::getAll(true);
    if ($category !== '') {
        $items = array_values(array_filter(
            $items,
            static fn (array $item): bool => strcasecmp((string) ($item['category'] ?? ''), $category) === 0
        ));
    }

    echo json_encode([
        'success'    => true,
        'items'      => $items,
        'categories' => GalleryRepository::getCategories(true),
        'count'      => count($items),
    ]);
} catch (Throwable $e) {
    error_log('Public gallery API: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unable to load gallery.']);
}
