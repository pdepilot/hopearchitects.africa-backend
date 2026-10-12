<?php
/**
 * Admin API: manage site gallery photos (max 5MB each).
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__, 2) . '/includes/admin_api_guard.php';
require_once dirname(__DIR__, 2) . '/includes/GalleryRepository.php';
require_once dirname(__DIR__, 2) . '/includes/GalleryUploadService.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        jsonOk([
            'items'      => GalleryRepository::getAll(false),
            'stats'      => GalleryRepository::getStats(),
            'categories' => GalleryRepository::getCategories(false),
            'limits'     => [
                'maxBytes' => GalleryUploadService::maxBytes(),
                'maxMb'    => GalleryUploadService::maxMb(),
            ],
        ]);
    }

    if ($method === 'POST') {
        $contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '');
        $isMultipart = stripos($contentType, 'multipart/form-data') !== false
            || !empty($_FILES);

        if ($isMultipart) {
            handleMultipart();
        }

        $body = json_decode(file_get_contents('php://input') ?: '', true) ?? [];
        $action = (string) ($body['action'] ?? 'save');

        if ($action === 'delete') {
            $id = (int) ($body['id'] ?? 0);
            if ($id <= 0 || !GalleryRepository::delete($id)) {
                jsonError('Gallery item not found.', 404);
            }
            jsonOk(['message' => 'Gallery photo deleted.']);
        }

        if ($action === 'toggle') {
            $id = (int) ($body['id'] ?? 0);
            $item = GalleryRepository::getById($id);
            if ($item === null) {
                jsonError('Gallery item not found.', 404);
            }
            GalleryRepository::update($id, [
                'isPublished' => empty($item['isPublished']),
            ]);
            jsonOk([
                'message' => 'Visibility updated.',
                'item'    => GalleryRepository::getById($id),
            ]);
        }

        $id = (int) ($body['id'] ?? 0);
        if ($id <= 0) {
            jsonError('Invalid gallery item.');
        }

        $payload = [
            'title'       => $body['title'] ?? '',
            'caption'     => $body['caption'] ?? '',
            'category'    => $body['category'] ?? '',
            'sortOrder'   => $body['sortOrder'] ?? 0,
            'isPublished' => $body['isPublished'] ?? true,
        ];
        if (!empty($body['imagePath'])) {
            $payload['imagePath'] = $body['imagePath'];
        }
        GalleryRepository::update($id, $payload);

        jsonOk([
            'message' => 'Gallery photo updated.',
            'item'    => GalleryRepository::getById($id),
        ]);
    }

    jsonError('Method not allowed.', 405);
} catch (InvalidArgumentException $e) {
    jsonError($e->getMessage(), 422);
} catch (Throwable $e) {
    error_log('Admin gallery API: ' . $e->getMessage());
    jsonError($e->getMessage() !== '' ? $e->getMessage() : 'Gallery request failed.', 400);
}

/** @return never */
function handleMultipart(): void
{
    $action = (string) ($_POST['action'] ?? 'upload');
    if ($action !== 'upload' && $action !== 'create') {
        jsonError('Unknown upload action.');
    }

    if (empty($_FILES['image']) && empty($_FILES['file'])) {
        jsonError('Please choose a photo to upload (max 5MB).');
    }

    $file = !empty($_FILES['image']) ? $_FILES['image'] : $_FILES['file'];
    $path = GalleryUploadService::upload($file);

    $id = GalleryRepository::create([
        'title'       => $_POST['title'] ?? '',
        'caption'     => $_POST['caption'] ?? '',
        'category'    => $_POST['category'] ?? '',
        'imagePath'   => $path,
        'sortOrder'   => $_POST['sortOrder'] ?? 0,
        'isPublished' => $_POST['isPublished'] ?? '1',
    ]);

    jsonOk([
        'message' => 'Gallery photo uploaded.',
        'item'    => GalleryRepository::getById($id),
    ]);
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
