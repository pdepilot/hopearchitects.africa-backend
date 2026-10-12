<?php
/**
 * Gallery photo uploads — max 5MB per image.
 */
declare(strict_types=1);

require_once __DIR__ . '/site_paths.php';

final class GalleryUploadService
{
    private const UPLOAD_DIR = 'assets/uploads/gallery';
    private const MAX_BYTES = 5 * 1024 * 1024;

    /** @var list<string> */
    private const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    public static function maxBytes(): int
    {
        return self::MAX_BYTES;
    }

    public static function maxMb(): int
    {
        return 5;
    }

    /**
     * @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file
     */
    public static function upload(array $file): string
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException(self::uploadErrorMessage($error));
        }

        if (($file['size'] ?? 0) > self::MAX_BYTES) {
            $name = basename((string) ($file['name'] ?? 'photo'));
            throw new RuntimeException(
                '"' . $name . '" is too large. Each gallery photo must be '
                . self::maxMb() . 'MB or less.'
            );
        }

        $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_EXT, true)) {
            throw new RuntimeException('Use JPG, PNG, WEBP, or GIF images only.');
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        $mime = self::detectMime($tmp, (string) ($file['type'] ?? ''));
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/webp', 'image/gif'];
        if ($mime !== '' && !in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException('Invalid image file type.');
        }

        self::ensureUploadDir();

        $safeExt = $ext === 'jpeg' ? 'jpg' : $ext;
        $filename = 'gallery-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $safeExt;
        $target = self::uploadDirAbsolute() . '/' . $filename;

        if (!move_uploaded_file($tmp, $target)) {
            throw new RuntimeException('Could not save uploaded gallery photo.');
        }

        return self::UPLOAD_DIR . '/' . $filename;
    }

    public static function deleteStoredPath(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }

        $normalized = ltrim(str_replace('\\', '/', $relativePath), '/');
        if (!str_starts_with($normalized, self::UPLOAD_DIR . '/')) {
            return;
        }

        $full = dirname(__DIR__) . '/' . $normalized;
        if (is_file($full)) {
            unlink($full);
        }
    }

    public static function adminPreviewHref(string $relativePath): string
    {
        $relativePath = trim($relativePath);
        if ($relativePath === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $relativePath)) {
            return $relativePath;
        }

        return '../' . ltrim(str_replace('\\', '/', $relativePath), './');
    }

    private static function detectMime(string $tmpPath, string $clientType): string
    {
        if ($tmpPath !== '' && is_file($tmpPath) && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $detected = finfo_file($finfo, $tmpPath) ?: '';
                finfo_close($finfo);
                if ($detected !== '') {
                    return strtolower($detected);
                }
            }
        }

        return strtolower(trim($clientType));
    }

    private static function uploadDirAbsolute(): string
    {
        return dirname(__DIR__) . '/' . self::UPLOAD_DIR;
    }

    private static function ensureUploadDir(): void
    {
        $dir = self::uploadDirAbsolute();
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException('Unable to create gallery upload directory.');
        }

        $htaccess = $dir . '/.htaccess';
        if (!is_file($htaccess)) {
            file_put_contents($htaccess, "Options -Indexes\n");
        }
    }

    private static function uploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE =>
                'File is too large for the server upload limit. Each photo must be 5MB or less.',
            UPLOAD_ERR_PARTIAL => 'Upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'No photo selected.',
            default => 'Photo upload failed.',
        };
    }
}
