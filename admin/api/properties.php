<?php
/**
 * Admin API: manage property listings (list, create, update, delete).
 * Requires active PHP admin session.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__, 2) . '/includes/admin_api_guard.php';
require_once dirname(__DIR__, 2) . '/includes/PropertyRepository.php';
require_once dirname(__DIR__, 2) . '/includes/PropertyUploadService.php';
require_once dirname(__DIR__, 2) . '/includes/AutomatedEmailService.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        if (!empty($_GET['id'])) {
            $property = PropertyRepository::getById((int) $_GET['id']);
            if ($property === null) {
                jsonError('Property not found.', 404);
            }
            jsonOk(['property' => $property]);
        }

        $limit  = isset($_GET['limit']) ? (int) $_GET['limit'] : 100;
        $type   = isset($_GET['type']) ? (string) $_GET['type'] : null;
        $search = isset($_GET['search']) ? trim((string) $_GET['search']) : null;

        jsonOk(['properties' => PropertyRepository::getAll($limit, $type, $search)]);
    }

    if ($method === 'POST') {
        if (isMultipartRequest()) {
            handleMultipartPost();
        }

        $body = parseJsonBody();
        $title = trim((string) ($body['title'] ?? ''));
        $location = trim((string) ($body['location'] ?? ''));

        if ($title === '' || $location === '') {
            jsonError('Title and location are required.');
        }

        $property = PropertyRepository::create(normalizePayload($body, $title, $location));
        jsonOk(['property' => $property, 'message' => 'Property created.']);
    }

    if ($method === 'PUT') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            jsonError('Invalid property ID.');
        }

        $body = parseJsonBody();
        $updates = [];

        if (isset($body['title'])) {
            $updates['title'] = trim((string) $body['title']);
        }
        if (isset($body['location'])) {
            $updates['location'] = trim((string) $body['location']);
        }
        if (isset($body['price'])) {
            $updates['price'] = (int) $body['price'];
        }
        if (isset($body['type'])) {
            $updates['type'] = (string) $body['type'];
        }
        if (array_key_exists('imageUrl', $body)) {
            $updates['imageUrl'] = trim((string) $body['imageUrl']) ?: null;
        }
        if (array_key_exists('description', $body)) {
            $updates['description'] = trim((string) $body['description']) ?: null;
        }
        $oldProperty = PropertyRepository::getById($id);
        $oldStatus = (string) ($oldProperty['approvalStatus'] ?? '');

        if (isset($body['approvalStatus'])) {
            $updates['approvalStatus'] = (string) $body['approvalStatus'];
        }
        if (isset($body['bedrooms'])) {
            $updates['bedrooms'] = (int) $body['bedrooms'];
        }
        if (isset($body['bathrooms'])) {
            $updates['bathrooms'] = (int) $body['bathrooms'];
        }
        if (isset($body['area'])) {
            $updates['area'] = (int) $body['area'];
        }

        $property = PropertyRepository::update($id, $updates);
        if ($property === null) {
            jsonError('Property not found.', 404);
        }

        if (isset($updates['approvalStatus']) && $oldProperty !== null) {
            $newStatus = (string) $updates['approvalStatus'];
            if ($newStatus !== $oldStatus) {
                AutomatedEmailService::onPropertyStatusChange(
                    $property,
                    $oldStatus,
                    $newStatus,
                    (int) ($_SESSION['admin_id'] ?? 0),
                    (string) ($property['adminNotes'] ?? $body['adminNotes'] ?? '')
                );
            }
        }

        jsonOk(['property' => $property, 'message' => 'Property updated.']);
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0 || !PropertyRepository::delete($id)) {
            jsonError('Property not found.', 404);
        }

        jsonOk(['message' => 'Property deleted.']);
    }

    jsonError('Method not allowed.', 405);
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'properties') || str_contains($e->getMessage(), 'Unknown column')) {
        jsonError('Properties table needs migration. Run sql/install_properties.php or sql/property_migrate_v2.sql.', 503);
    }
    jsonError('Database error.', 500);
} catch (Throwable $e) {
    jsonError($e->getMessage() ?: 'Request failed.', 400);
}

/**
 * @return never
 */
function handleMultipartPost(): void
{
    $action = (string) ($_POST['action'] ?? 'create');
    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            jsonError('Invalid project ID.');
        }

        $oldProject = PropertyRepository::getById($id);
        if ($oldProject === null) {
            jsonError('Project not found.', 404);
        }

        $updates = normalizeMultipartPayload($_POST);

        $keepImages = $_POST['keepImages'] ?? [];
        if (!is_array($keepImages)) {
            $keepImages = $keepImages !== '' ? [(string) $keepImages] : [];
        }
        $keepImages = array_values(array_filter(array_map(
            static fn ($path): string => trim((string) $path),
            $keepImages
        )));

        $hasNewImages = !empty($_FILES['propertyImages']) && hasUploadedFiles($_FILES['propertyImages']);
        $mediaTouched = $hasNewImages || array_key_exists('keepImages', $_POST);

        if ($mediaTouched) {
            // If keepImages omitted, preserve existing stored gallery when only adding files.
            if (!array_key_exists('keepImages', $_POST)) {
                $keepImages = is_array($oldProject['storedImages'] ?? null)
                    ? array_values(array_filter($oldProject['storedImages']))
                    : [];
            }

            $media = PropertyUploadService::updateSubmissionMedia(
                $id,
                $keepImages,
                $hasNewImages ? $_FILES : [],
                false,
                is_string($oldProject['storedVideo'] ?? null) ? $oldProject['storedVideo'] : null
            );
            $updates['imageUrl'] = $media['imageUrl'];
            $updates['galleryUrls'] = $media['galleryUrls'];
            $updates['videoUrl'] = $media['videoUrl'];
        }

        $project = PropertyRepository::update($id, $updates);
        if ($project === null) {
            jsonError('Project not found.', 404);
        }

        if (isset($updates['approvalStatus'])) {
            $oldStatus = (string) ($oldProject['approvalStatus'] ?? '');
            $newStatus = (string) $updates['approvalStatus'];
            if ($newStatus !== $oldStatus) {
                AutomatedEmailService::onPropertyStatusChange(
                    $project,
                    $oldStatus,
                    $newStatus,
                    (int) ($_SESSION['admin_id'] ?? 0),
                    (string) ($project['adminNotes'] ?? '')
                );
            }
        }

        jsonOk(['property' => $project, 'message' => 'Project updated.']);
    }

    $title = trim((string) ($_POST['title'] ?? ''));
    $location = trim((string) ($_POST['location'] ?? ''));
    if ($title === '' || $location === '') {
        jsonError('Title and location are required.');
    }
    if (empty($_FILES['propertyImages']) || !hasUploadedFiles($_FILES['propertyImages'])) {
        jsonError('Please upload a project image.');
    }

    $project = PropertyRepository::create(normalizeMultipartPayload($_POST));
    $projectId = (int) ($project['id'] ?? 0);
    if ($projectId <= 0) {
        jsonError('Could not create project.');
    }

    $media = PropertyUploadService::storeSubmissionMedia($projectId, $_FILES);
    $project = PropertyRepository::update($projectId, [
        'imageUrl' => $media['imageUrl'],
        'galleryUrls' => $media['extraImages'],
        'videoUrl' => $media['videoUrl'],
    ]) ?? $project;

    jsonOk(['property' => $project, 'message' => 'Project created.']);
}

/**
 * @return array<string, mixed>
 */
function parseJsonBody(): array
{
    $body = json_decode(file_get_contents('php://input') ?: '', true);
    return is_array($body) ? $body : [];
}

/**
 * @param array<string, mixed> $body
 * @return array<string, mixed>
 */
function normalizePayload(array $body, string $title, string $location): array
{
    return [
        'title'          => $title,
        'location'       => $location,
        'price'          => (int) ($body['price'] ?? 0),
        'type'           => (string) ($body['type'] ?? 'sale'),
        'imageUrl'       => trim((string) ($body['imageUrl'] ?? '')) ?: null,
        'description'    => trim((string) ($body['description'] ?? '')) ?: null,
        'approvalStatus' => (string) ($body['approvalStatus'] ?? 'approved'),
        'bedrooms'       => (int) ($body['bedrooms'] ?? 2),
        'bathrooms'      => (int) ($body['bathrooms'] ?? 2),
        'area'           => (int) ($body['area'] ?? 0),
    ];
}

/**
 * @param array<string, mixed> $body
 * @return array<string, mixed>
 */
function normalizeMultipartPayload(array $body): array
{
    $title = trim((string) ($body['title'] ?? ''));
    $location = trim((string) ($body['location'] ?? ''));
    $payload = [
        'title'          => $title,
        'location'       => $location,
        'price'          => (int) ($body['price'] ?? 0),
        'type'           => (string) ($body['type'] ?? 'sale'),
        'description'    => trim((string) ($body['description'] ?? '')) ?: null,
        'approvalStatus' => (string) ($body['approvalStatus'] ?? 'approved'),
        'bedrooms'       => (int) ($body['bedrooms'] ?? 2),
        'bathrooms'      => (int) ($body['bathrooms'] ?? 2),
        'area'           => (int) ($body['area'] ?? 0),
    ];

    if (array_key_exists('imageUrl', $body)) {
        $payload['imageUrl'] = trim((string) $body['imageUrl']) ?: null;
    }

    return $payload;
}

function isMultipartRequest(): bool
{
    $contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '');
    return str_contains(strtolower($contentType), 'multipart/form-data');
}

/**
 * @param array<string, mixed> $file
 */
function hasUploadedFiles(array $file): bool
{
    if (is_array($file['name'] ?? null)) {
        foreach ($file['name'] as $idx => $name) {
            if (($file['error'][$idx] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE && $name !== '') {
                return true;
            }
        }
        return false;
    }

    return (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) && (($file['name'] ?? '') !== '');
}

function jsonOk(array $data): void
{
    echo json_encode(array_merge(['success' => true], $data));
    exit;
}

function jsonError(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}
