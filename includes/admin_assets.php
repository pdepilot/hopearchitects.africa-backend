<?php
/** Shared CSS/JS for admin pages using the unified sidebar. */
declare(strict_types=1);

require_once __DIR__ . '/site_helpers.php';
?>
<link rel="shortcut icon" href="<?= htmlspecialchars(adminAssetHref(siteLogoPath()), ENT_QUOTES, 'UTF-8') ?>" type="image/png">
<link rel="stylesheet" href="../assets/css/admin-common.css">
<?php if (!empty($pageStylesheet)): ?>
<link rel="stylesheet" href="<?= htmlspecialchars((string) $pageStylesheet, ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
