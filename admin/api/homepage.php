<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__, 2) . '/includes/admin_api_guard.php';
require_once dirname(__DIR__, 2) . '/includes/AdminPermissions.php';
require_once dirname(__DIR__, 2) . '/includes/HomepageContentService.php';
require_once dirname(__DIR__, 2) . '/includes/HomepageUploadService.php';

AdminPermissions::require(AdminPermissions::PERM_CONTENT);

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

try {
    if ($method === 'GET') {
        $content = HomepageContentService::get();
        jsonOk([
            'content'  => $content,
            'previews' => buildPreviews($content),
        ]);
    }

    if ($method === 'POST') {
        [$action, $body] = parseHomepageRequest();

        if ($action === 'upload') {
            $kind = (string) ($body['kind'] ?? $_POST['kind'] ?? 'image');
            $group = (string) ($body['group'] ?? $_POST['group'] ?? 'general');

            if ($kind === 'video') {
                if (empty($_FILES['file']) || !is_array($_FILES['file'])) {
                    jsonError('No video file uploaded. Choose a file, then click Upload.');
                }
                $path = HomepageUploadService::uploadVideo($_FILES['file'], $group);
            } else {
                $file = null;
                if (!empty($_FILES['file']) && is_array($_FILES['file'])) {
                    $file = $_FILES['file'];
                } elseif (!empty($_FILES['image']) && is_array($_FILES['image'])) {
                    $file = $_FILES['image'];
                }
                if ($file === null) {
                    jsonError('No image file uploaded. Choose a file, then click Upload.');
                }
                $path = HomepageUploadService::uploadImage($file, $group);
            }

            jsonOk([
                'message'    => 'Upload successful.',
                'path'       => $path,
                'previewUrl' => HomepageUploadService::adminPreviewHref($path),
                'publicUrl'  => HomepageUploadService::publicPreviewUrl($path),
            ]);
        }

        if ($action === 'save') {
            $saved = HomepageContentService::save([
                'preloader' => $body['preloader'] ?? null,
                'hero'      => $body['hero'] ?? null,
                'slides'    => $body['slides'] ?? null,
                'stats'     => $body['stats'] ?? null,
                'about'     => $body['about'] ?? null,
                'whyUs'     => $body['whyUs'] ?? null,
                'process'   => $body['process'] ?? null,
                'deliver'   => $body['deliver'] ?? null,
                'sections'  => $body['sections'] ?? null,
            ]);
            if (!$saved) {
                jsonError('Could not write homepage config. Check that the config folder is writable.');
            }
            $content = HomepageContentService::get();
            jsonOk([
                'message'  => 'Homepage content saved.',
                'content'  => $content,
                'previews' => buildPreviews($content),
            ]);
        }

        jsonError('Unknown action. Use save or upload.');
    }

    jsonError('Method not allowed.', 405);
} catch (Throwable $e) {
    jsonError($e->getMessage(), 400);
}

/**
 * @return array{0:string,1:array<string,mixed>}
 */
function parseHomepageRequest(): array
{
    $contentType = strtolower((string) (
        $_SERVER['CONTENT_TYPE']
        ?? $_SERVER['HTTP_CONTENT_TYPE']
        ?? ''
    ));

    $raw = file_get_contents('php://input') ?: '';
    $body = [];
    $action = '';

    if (str_contains($contentType, 'application/json') || (str_starts_with(ltrim($raw), '{') && $_POST === [])) {
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            jsonError('Invalid JSON body.');
        }
        $body = $decoded;
        $action = (string) ($body['action'] ?? 'save');
        return [$action, $body];
    }

    $body = $_POST;
    if (isset($_POST['payload']) && is_string($_POST['payload'])) {
        $decoded = json_decode($_POST['payload'], true);
        if (is_array($decoded)) {
            $body = $decoded;
        }
    }
    $action = (string) ($body['action'] ?? $_POST['action'] ?? 'save');

    return [$action, $body];
}

/** @param array<string, mixed> $content @return array<string, string> */
function buildPreviews(array $content): array
{
    $paths = [
        'preloader.video'  => (string) (($content['preloader']['video'] ?? '')),
        'about.imageMain'  => (string) (($content['about']['imageMain'] ?? '')),
        'about.imageFloat' => (string) (($content['about']['imageFloat'] ?? '')),
        'whyUs.image'      => (string) (($content['whyUs']['image'] ?? '')),
    ];
    foreach (($content['slides'] ?? []) as $i => $slide) {
        if (is_array($slide)) {
            $paths['slides.' . $i . '.bgImage'] = (string) ($slide['bgImage'] ?? '');
        }
    }

    $out = [];
    foreach ($paths as $key => $path) {
        $out[$key] = $path !== '' ? HomepageUploadService::adminPreviewHref($path) : '';
    }

    return $out;
}

/** @param array<string, mixed> $data */
function jsonOk(array $data): void
{
    $payload = ['success' => true] + $data;
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        http_response_code(500);
        echo '{"success":false,"message":"Failed to encode response JSON."}';
        exit;
    }
    echo $json;
    exit;
}

function jsonError(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $message !== '' ? $message : 'Request failed.',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
