<?php
declare(strict_types=1);

require_once __DIR__ . '/site_paths.php';
require_once __DIR__ . '/site_helpers.php';

$siteBase = siteRootPath();
$propertiesApi = siteUrl('api/properties.php');
?>
<script>
window.HOPE_SITE = {
  base: <?= json_encode($siteBase, JSON_UNESCAPED_SLASHES) ?>,
  propertiesApi: <?= json_encode($propertiesApi, JSON_UNESCAPED_SLASHES) ?>,
  testimonialsApi: <?= json_encode(siteUrl('api/testimonials.php'), JSON_UNESCAPED_SLASHES) ?>,
  locationsApi: <?= json_encode(siteUrl('api/locations.php'), JSON_UNESCAPED_SLASHES) ?>,
  contactPhone: <?= json_encode(siteContactPhone(), JSON_UNESCAPED_UNICODE) ?>,
  contactTel: <?= json_encode(siteContactPhoneTel(), JSON_UNESCAPED_UNICODE) ?>,
  whatsappDigits: <?= json_encode(siteWhatsAppDigits(), JSON_UNESCAPED_UNICODE) ?>,
  whatsappPhone: <?= json_encode(siteWhatsAppPhone(), JSON_UNESCAPED_UNICODE) ?>,
  page(name, params) {
    const base = this.base || '';
    let slug = String(name).replace(/^\//, '').replace(/\.php$/i, '');
    let url = (base ? base : '') + '/' + slug;
    if (params && typeof params === 'object') {
      const qs = new URLSearchParams();
      Object.entries(params).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') qs.set(key, String(value));
      });
      const query = qs.toString();
      if (query) url += '?' + query;
    }
    return url || '/';
  },
  propertyDetail(id) {
    return this.page('property-detail', { id: id });
  }
};
window.BIVER_SITE = window.HOPE_SITE;
</script>
