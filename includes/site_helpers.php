<?php
declare(strict_types=1);

require_once __DIR__ . '/SiteSettingsService.php';
require_once __DIR__ . '/site_paths.php';

/** @return array<string, mixed> */
function siteSettings(): array
{
    static $cache = null;
    if ($cache === null) {
        $cache = SiteSettingsService::get();
    }

    return $cache;
}

function siteName(): string
{
    return (string) (siteSettings()['siteName'] ?? 'Hope Architects');
}

function siteLogoPath(): string
{
    return './assets/images/hope_archites.png';
}

function siteLogoVideoPath(): string
{
    return './assets/videos/VID-20260702-WA0010.mp4';
}

function siteLogoAlt(): string
{
    return siteName();
}

/** Echo a muted looping video logo (header / footer). */
function siteLogoVideo(int $height = 50, ?int $width = null): void
{
    $height = max(32, $height);
    $widthAttr = $width !== null ? ' width="' . (int) $width . '"' : '';
    echo '<video class="site-logo-video" src="', siteEscape(siteLogoVideoPath()), '"',
        ' height="', (int) $height, '"', $widthAttr,
        ' autoplay muted loop playsinline preload="metadata"',
        ' aria-label="', siteEscape(siteLogoAlt()), '"></video>';
}

/** Relative asset URL from /admin pages. */
function adminAssetHref(string $path): string
{
    $normalized = ltrim(str_replace('\\', '/', $path), './');

    return '../' . $normalized;
}

/** Video logo markup for admin shell (sidebar, login). */
function adminSiteLogoVideo(int $height = 48, ?int $width = 150): void
{
    $height = max(32, $height);
    $widthAttr = $width !== null ? ' width="' . (int) $width . '"' : '';
    $src = adminAssetHref(siteLogoVideoPath());
    echo '<video class="site-logo-video admin-logo-video" src="', siteEscape($src), '"',
        ' height="', (int) $height, '"', $widthAttr,
        ' autoplay muted loop playsinline preload="metadata"',
        ' aria-label="', siteEscape(siteLogoAlt()), '"></video>';
}

function siteContactEmail(): string
{
    return (string) (siteSettings()['contactEmail'] ?? 'info@hopearchitects.ng');
}

function siteContactPhone(): string
{
    return (string) (siteSettings()['contactPhone'] ?? '+234 808 430 9701');
}

function siteContactPhoneTel(): string
{
    $phone = siteContactPhone();
    $digits = preg_replace('/[^\d+]/', '', $phone) ?? '';

    return $digits !== '' ? $digits : '+2348084309701';
}

function siteAddress(): string
{
    return (string) (siteSettings()['address'] ?? '12 Admiralty Way, Lekki Phase 1, Lagos, Nigeria');
}

function siteAboutText(): string
{
    return (string) (siteSettings()['aboutText'] ?? '');
}

function siteSocial(string $network): string
{
    $key = match (strtolower($network)) {
        'facebook'  => 'socialFacebook',
        'instagram' => 'socialInstagram',
        'tiktok'    => 'socialTiktok',
        'twitter'   => 'socialTwitter',
        default     => '',
    };

    return $key !== '' ? (string) (siteSettings()[$key] ?? '') : '';
}

function siteMailto(): string
{
    return 'mailto:' . rawurlencode(siteContactEmail());
}

function siteTelHref(): string
{
    return 'tel:' . siteContactPhoneTel();
}

/** WhatsApp number digits only (no +), for wa.me links. */
function siteWhatsAppPhone(): string
{
    return (string) (siteSettings()['whatsapp'] ?? '+234 806 389 6199');
}

function siteWhatsAppDigits(): string
{
    $raw = siteWhatsAppPhone();
    $digits = preg_replace('/\D/', '', $raw) ?? '';

    if ($digits === '') {
        return '2348063896199';
    }

    if (str_starts_with($digits, '0')) {
        return '234' . substr($digits, 1);
    }

    return $digits;
}

function siteWhatsAppUrl(?string $prefillMessage = null): string
{
    $url = 'https://wa.me/' . siteWhatsAppDigits();

    if ($prefillMessage !== null && $prefillMessage !== '') {
        $url .= '?text=' . rawurlencode($prefillMessage);
    }

    return $url;
}

function siteBusinessHours(): string
{
    return (string) (siteSettings()['businessHours'] ?? 'Mon – Sat: 9:00 AM – 6:00 PM');
}

function siteDesignerName(): string
{
    return (string) (siteSettings()['siteDesigner'] ?? 'blinkzcode');
}
