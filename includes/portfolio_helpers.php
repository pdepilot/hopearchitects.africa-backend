<?php
declare(strict_types=1);

require_once __DIR__ . '/site_paths.php';

/** @return list<string> Relative paths under assets/images (excludes promo subfolder). */
function portfolioImageCatalog(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $dir = dirname(__DIR__) . '/assets/images';
    if (!is_dir($dir)) {
        return $cache = ['assets/images/Scene 10_1.png'];
    }

    $files = [];
    foreach (scandir($dir) ?: [] as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (!is_file($path)) {
            continue;
        }
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            continue;
        }
        // Prefer project photos over logos/brand marks and people portraits as gallery fallbacks.
        if (preg_match('/^(hope_archites|biver-logo)/i', $file)) {
            continue;
        }
        if (preg_match('/^IMG-20260702-WA0012|^IMG-20260706-WA004[35]/i', $file)) {
            continue;
        }
        $files[] = 'assets/images/' . str_replace('\\', '/', $file);
    }

    sort($files, SORT_NATURAL);

    if ($files === []) {
        $files = ['assets/images/Scene 10_1.png'];
    }

    return $cache = $files;
}

/** Strip site base / leading dots so we can resolve a file on disk. */
function portfolioNormalizeRelativePath(string $path): string
{
    $path = trim(str_replace('\\', '/', $path));
    $path = preg_replace('#^https?://[^/]+/#i', '', $path) ?? $path;
    $path = ltrim($path, '/');
    $path = preg_replace('#^\./+#', '', $path) ?? $path;

    $base = ltrim(str_replace('\\', '/', siteRootPath()), '/');
    if ($base !== '' && str_starts_with($path, $base . '/')) {
        $path = substr($path, strlen($base) + 1);
    }

    // Decode any %20 so disk checks work.
    $segments = explode('/', $path);
    $decoded = array_map(static fn (string $s): string => rawurldecode($s), $segments);

    return implode('/', $decoded);
}

function portfolioLocalFileExists(?string $relativePath): bool
{
    $relativePath = trim((string) $relativePath);
    if ($relativePath === '' || preg_match('#^https?://#i', $relativePath)) {
        return false;
    }

    $normalized = portfolioNormalizeRelativePath($relativePath);
    if ($normalized === '' || str_contains($normalized, '..')) {
        return false;
    }

    return is_file(dirname(__DIR__) . '/' . $normalized);
}

function portfolioImageAt(int $index): string
{
    $catalog = portfolioImageCatalog();
    $index = (($index % count($catalog)) + count($catalog)) % count($catalog);

    return $catalog[$index];
}

function portfolioImagePublicUrl(int $index): string
{
    return publicAssetUrl(portfolioImageAt($index)) ?? encodeAssetUrlPath('./' . portfolioImageAt($index));
}

function portfolioResolveMediaUrl(?string $url, int $fallbackIndex = 0): string
{
    $url = trim((string) $url);
    if ($url !== '') {
        if (preg_match('#^https?://#i', $url)) {
            if (str_contains(strtolower($url), 'unsplash.com') || str_contains(strtolower($url), 'placehold.co')) {
                return portfolioImagePublicUrl($fallbackIndex);
            }
            return $url;
        }

        if (portfolioLocalFileExists($url)) {
            $normalized = portfolioNormalizeRelativePath($url);
            return publicAssetUrl($normalized) ?? encodeAssetUrlPath('./' . $normalized);
        }
    }

    return portfolioImagePublicUrl($fallbackIndex);
}

/** @return array{label: string, class: string} */
function portfolioCategoryMeta(?string $type): array
{
    return ($type === 'rent')
        ? ['label' => 'Interior Design', 'class' => 'interior']
        : ['label' => 'Architecture', 'class' => 'architecture'];
}

function portfolioCategoryLabel(?string $type): string
{
    return portfolioCategoryMeta($type)['label'];
}

/** Pick primary image for a property record. */
function portfolioPropertyImage(array $property, int $index = 0): string
{
    $candidates = [];
    if (!empty($property['imageUrl'])) {
        $candidates[] = (string) $property['imageUrl'];
    }
    if (!empty($property['images']) && is_array($property['images'])) {
        foreach ($property['images'] as $img) {
            if (is_string($img) && $img !== '') {
                $candidates[] = $img;
            }
        }
    }
    if (!empty($property['galleryUrls']) && is_array($property['galleryUrls'])) {
        foreach ($property['galleryUrls'] as $img) {
            if (is_string($img) && $img !== '') {
                $candidates[] = $img;
            }
        }
    }

    foreach ($candidates as $i => $candidate) {
        $lower = strtolower($candidate);
        if (str_contains($lower, 'unsplash.com') || str_contains($lower, 'placehold.co')) {
            continue;
        }
        if (preg_match('#^https?://#i', $candidate)) {
            return $candidate;
        }
        if (portfolioLocalFileExists($candidate)) {
            return portfolioResolveMediaUrl($candidate, $index + $i);
        }
    }

    return portfolioImagePublicUrl($index);
}
