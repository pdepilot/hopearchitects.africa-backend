<?php
declare(strict_types=1);

require_once __DIR__ . '/site_paths.php';

final class HomepageUploadService
{
    private const UPLOAD_DIR = 'assets/uploads/homepage';
    private const MAX_IMAGE_BYTES = 8 * 1024 * 1024;
    private const MAX_VIDEO_BYTES = 80 * 1024 * 1024;

    /** @var list<string> */
    private const ALLOWED_IMAGE_EXT = ['jpg', 'jpeg', 'png', 'webp'];

    /** @var list<string> */
    private const ALLOWED_VIDEO_EXT = ['mp4', 'webm', 'mov'];

    /** @var list<string> */
    private const ALLOWED_GROUPS = [
        'preloader', 'slide', 'about', 'why', 'deliver', 'general',
    ];

    /** @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file */
    public static function uploadImage(array $file, string $group = 'general'): string
    {
        return self::store($file, $group, 'image');
    }

    /** @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file */
    public static function uploadVideo(array $file, string $group = 'preloader'): string
    {
        return self::store($file, $group, 'video');
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

    /**
     * @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file
     */
    private static function store(array $file, string $group, string $kind): string
    {
        $group = self::sanitizeGroup($group);
        $isVideo = $kind === 'video';
        $maxBytes = $isVideo ? self::MAX_VIDEO_BYTES : self::MAX_IMAGE_BYTES;
        $allowedExt = $isVideo ? self::ALLOWED_VIDEO_EXT : self::ALLOWED_IMAGE_EXT;
        $allowedMimes = $isVideo
            ? ['video/mp4', 'video/webm', 'video/quicktime']
            : ['image/jpeg', 'image/png', 'image/webp'];

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException(self::uploadErrorMessage((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE), $isVideo));
        }

        if (($file['size'] ?? 0) > $maxBytes) {
            throw new RuntimeException($isVideo
                ? 'Video must be 80 MB or smaller.'
                : 'Image must be 8 MB or smaller.');
        }

        $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            throw new RuntimeException($isVideo
                ? 'Use MP4, WebM, or MOV videos only.'
                : 'Use JPG, PNG, or WebP images only.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, (string) $file['tmp_name']) : null;
        if ($finfo) {
            finfo_close($finfo);
        }
        if ($mime !== null && !in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException($isVideo ? 'Invalid video file type.' : 'Invalid image file type.');
        }

        self::ensureUploadDir();

        $safeExt = $ext === 'jpeg' ? 'jpg' : $ext;
        // Avoid names like video.mp4.mp4 when the original already ends with the extension.
        $baseName = pathinfo((string) ($file['name'] ?? 'upload'), PATHINFO_FILENAME);
        $baseName = strtolower(preg_replace('/[^a-z0-9\-]+/i', '-', $baseName) ?? 'upload');
        $baseName = trim($baseName, '-') ?: 'upload';
        if (str_ends_with($baseName, '.' . $safeExt)) {
            $baseName = substr($baseName, 0, -(strlen($safeExt) + 1));
        }
        $filename = $group . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $safeExt;
        $target = self::uploadDirAbsolute() . '/' . $filename;

        if (!move_uploaded_file((string) $file['tmp_name'], $target)) {
            throw new RuntimeException('Could not save uploaded file.');
        }

        return self::UPLOAD_DIR . '/' . $filename;
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
            throw new RuntimeException('Unable to create homepage upload directory.');
        }
        $htaccess = $dir . '/.htaccess';
        if (!is_file($htaccess)) {
            @file_put_contents($htaccess, "Options -Indexes\n");
        }
    }

    private static function uploadErrorMessage(int $code, bool $isVideo): string
    {
        $label = $isVideo ? 'Video' : 'Image';

        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => $label . ' is too large for the server upload limit.',
            UPLOAD_ERR_PARTIAL => 'Upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'No file was selected.',
            default => $label . ' upload failed. Please try again.',
        };
    }
}
