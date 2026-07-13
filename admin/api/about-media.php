<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__, 2) . '/includes/admin_api_guard.php';
require_once dirname(__DIR__, 2) . '/includes/AdminPermissions.php';
require_once dirname(__DIR__, 2) . '/includes/AboutPageUploadService.php';

AdminPermissions::require(AdminPermissions::PERM_CONTENT);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method !== 'POST') {
        jsonError('Method not allowed.', 405);
    }

    $action = (string) ($_POST['action'] ?? 'upload');
    if ($action !== 'upload') {
        jsonError('Unknown action.');
    }

    if (empty($_FILES['image']) || !is_array($_FILES['image'])) {
        jsonError('No image file uploaded.');
    }

    $group = (string) ($_POST['group'] ?? 'general');
    $path = AboutPageUploadService::upload($_FILES['image'], $group);

    jsonOk([
        'message'     => 'Image uploaded.',
        'path'        => $path,
        'previewUrl'  => AboutPageUploadService::adminPreviewHref($path),
        'publicUrl'   => AboutPageUploadService::publicPreviewUrl($path),
    ]);
} catch (Throwable $e) {
    jsonError($e->getMessage(), 400);
}

/** @param array<string, mixed> $data */
function jsonOk(array $data): void
{
    echo json_encode(['success' => true] + $data);
    exit;
}

function jsonError(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}
