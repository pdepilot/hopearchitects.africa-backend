<?php
/**
 * Public API: FAQs for contact page and site.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=120');

require_once dirname(__DIR__) . '/includes/FaqRepository.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

try {
    $faqs = FaqRepository::getPublic();
    echo json_encode([
        'success' => true,
        'faqs'    => $faqs,
        'count'   => count($faqs),
    ]);
} catch (Throwable $e) {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'FAQs unavailable.', 'faqs' => []]);
}
