<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__, 2) . '/includes/admin_api_guard.php';
require_once dirname(__DIR__, 2) . '/includes/AdminPermissions.php';
require_once dirname(__DIR__, 2) . '/includes/PageContentService.php';

AdminPermissions::require(AdminPermissions::PERM_CONTENT);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        $page = (string) ($_GET['page'] ?? 'all');
        if ($page === 'all') {
            jsonOk(['content' => PageContentService::get()]);
        }
        jsonOk(['page' => $page, 'content' => PageContentService::getPage($page)]);
    }

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input') ?: '', true) ?? $_POST;
        $page = (string) ($body['page'] ?? '');
        $content = $body['content'] ?? null;
        if ($page === '' || !is_array($content)) {
            jsonError('Page slug and content are required.');
        }
        PageContentService::savePage($page, $content);
        jsonOk(['message' => ucfirst($page) . ' page saved.', 'content' => PageContentService::getPage($page)]);
    }

    jsonError('Method not allowed.', 405);
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
