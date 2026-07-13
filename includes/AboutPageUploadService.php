<?php
declare(strict_types=1);

require_once __DIR__ . '/site_paths.php';

final class AboutPageUploadService
{
    private const UPLOAD_DIR = 'assets/uploads/about';
    private const MAX_BYTES = 8 * 1024 * 1024;

    /** @var list<string> */
    private const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'webp'];

    /** @var list<string> */
    private const ALLOWED_GROUPS = ['narrative', 'values', 'team', 'general'];

    /** @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file */
    public static function upload(array $file, string $group = 'general'): string
    {
        $group = self::sanitizeGroup($group);

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException(self::uploadErrorMessage((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE)));
        }

        if (($file['size'] ?? 0) > self::MAX_BYTES) {
            throw new RuntimeException('Image must be 8 MB or smaller.');
        }

        $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_EXT, true)) {
            throw new RuntimeException('Use JPG, PNG, or WebP images only.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = $finfo ? finfo_file($finfo, (string) $file['tmp_name']) : null;
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if ($mime !== null && !in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException('Invalid image file type.');
        }

        self::ensureUploadDir();

        $safeExt = $ext === 'jpeg' ? 'jpg' : $ext;
        $filename = $group . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $safeExt;
        $target = self::uploadDirAbsolute() . '/' . $filename;

        if (!move_uploaded_file((string) $file['tmp_name'], $target)) {
            throw new RuntimeException('Could not save uploaded image.');
        }

        return self::UPLOAD_DIR . '/' . $filename;
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

    public static function publicPreviewUrl(string $relativePath): ?string
    {
        $relativePath = trim($relativePath);
        if ($relativePath === '') {
            return null;
        }

        return publicAssetUrl($relativePath);
    }

    private static function sanitizeGroup(string $group): string
    {
        $group = strtolower(preg_replace('/[^a-z0-9\-]/', '', $group) ?? 'general');

        return in_array($group, self::ALLOWED_GROUPS, true) ? $group : 'general';
    }

    private static function uploadDirAbsolute(): string
    {
        return dirname(__DIR__) . '/' . self::UPLOAD_DIR;
    }

    private static function ensureUploadDir(): void
    {
        $dir = self::uploadDirAbsolute();
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException('Unable to create about page upload directory.');
        }
    }

    private static function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Image is too large for the server upload limit.',
            UPLOAD_ERR_PARTIAL => 'Upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'No image file was selected.',
            default => 'Image upload failed. Please try again.',
        };
    }
}
