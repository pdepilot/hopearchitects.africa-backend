<?php
/**
 * One-time installer for gallery_images table.
 * Run: http://localhost/hope_architects/sql/install_gallery.php
 */
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

require_once dirname(__DIR__) . '/includes/GalleryRepository.php';

try {
    GalleryRepository::ensureSchema();
    $stats = GalleryRepository::getStats();
    echo "Gallery table ready.\n";
    echo "Total photos: {$stats['total']}\n";
    echo "Published: {$stats['published']}\n";
    echo "Upload photos via Admin → Gallery (max 5MB each).\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Install failed: ' . $e->getMessage() . "\n";
    exit(1);
}
