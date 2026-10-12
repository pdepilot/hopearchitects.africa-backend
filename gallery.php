<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php
require_once __DIR__ . '/includes/site_helpers.php';
require_once __DIR__ . '/includes/GalleryRepository.php';

try {
    $galleryItems = GalleryRepository::getAll(true);
    $galleryCategories = GalleryRepository::getCategories(true);
} catch (Throwable $e) {
    error_log('Gallery page load failed: ' . $e->getMessage());
    $galleryItems = [];
    $galleryCategories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Hope Architects gallery — architecture, interiors, and construction photography from our Lagos studio.">
  <meta name="author" content="Hope Architects">
  <title>Gallery | Hope Architects — Architecture & Construction</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/gallery.css">
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
</head>
<body class="gallery-page">

  <?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

  <main id="main-content">
    <section class="gallery-hero">
      <div class="container">
        <div class="gallery-hero__eyebrow"><span class="line"></span><span>Studio Gallery</span></div>
        <h1>Spaces Shaped with <em>Intention</em></h1>
        <p>A curated look at Hope Architects projects — architecture, interiors, and construction moments from concept to completion.</p>
      </div>
    </section>

    <div class="gallery-toolbar" id="galleryToolbar" role="toolbar" aria-label="Filter gallery">
      <button type="button" class="gallery-filter is-active" data-filter="all">All</button>
      <?php foreach ($galleryCategories as $category): ?>
      <button type="button" class="gallery-filter" data-filter="<?= siteEscape(mb_strtolower($category)) ?>"><?= siteEscape($category) ?></button>
      <?php endforeach; ?>
    </div>

    <section class="gallery-grid-wrap" aria-label="Photo gallery">
      <?php if ($galleryItems === []): ?>
      <div class="gallery-empty">
        <h2>Gallery coming soon</h2>
        <p>New project photography will appear here shortly.</p>
      </div>
      <?php else: ?>
      <div class="gallery-grid" id="galleryGrid">
        <?php foreach ($galleryItems as $index => $item): ?>
        <?php
          $title = (string) ($item['title'] ?? '');
          $category = (string) ($item['category'] ?? '');
          $caption = (string) ($item['caption'] ?? '');
          $filterKey = mb_strtolower($category !== '' ? $category : 'uncategorised');
        ?>
        <button
          type="button"
          class="gallery-card"
          data-category="<?= siteEscape($filterKey) ?>"
          data-index="<?= (int) $index ?>"
          aria-label="<?= siteEscape($title !== '' ? $title : 'Open gallery photo') ?>"
        >
          <img src="<?= siteEscape((string) $item['imageUrl']) ?>" alt="<?= siteEscape($title !== '' ? $title : 'Hope Architects gallery photo') ?>" loading="lazy">
          <?php if ($title !== '' || $category !== ''): ?>
          <div class="gallery-card__meta">
            <?php if ($title !== ''): ?><strong><?= siteEscape($title) ?></strong><?php endif; ?>
            <?php if ($category !== ''): ?><span><?= siteEscape($category) ?></span><?php endif; ?>
          </div>
          <?php endif; ?>
        </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </section>
  </main>

  <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true" role="dialog" aria-label="Gallery viewer">
    <button type="button" class="gallery-lightbox__close" id="lightboxClose" aria-label="Close">&times;</button>
    <button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--prev" id="lightboxPrev" aria-label="Previous">&#10094;</button>
    <img src="" alt="" id="lightboxImage">
    <button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--next" id="lightboxNext" aria-label="Next">&#10095;</button>
    <p class="gallery-lightbox__caption" id="lightboxCaption"></p>
  </div>

  <?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

  <script src="./assets/js/site-header.js" defer></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    'use strict';
    const galleryItems = <?= json_encode(array_map(static function (array $item): array {
        return [
            'imageUrl' => $item['imageUrl'] ?? '',
            'title'    => $item['title'] ?? '',
            'caption'  => $item['caption'] ?? '',
            'category' => $item['category'] ?? '',
        ];
    }, $galleryItems), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

    const cards = Array.from(document.querySelectorAll('.gallery-card'));
    const filters = Array.from(document.querySelectorAll('.gallery-filter'));
    const lightbox = document.getElementById('galleryLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    let currentIndex = 0;
    let visibleIndexes = cards.map((_, i) => i);

    function applyFilter(key) {
      filters.forEach((btn) => btn.classList.toggle('is-active', btn.dataset.filter === key));
      visibleIndexes = [];
      cards.forEach((card, index) => {
        const match = key === 'all' || card.dataset.category === key;
        card.hidden = !match;
        if (match) visibleIndexes.push(index);
      });
    }

    function openLightbox(index) {
      currentIndex = index;
      const item = galleryItems[index];
      if (!item) return;
      lightboxImage.src = item.imageUrl;
      lightboxImage.alt = item.title || 'Gallery photo';
      lightboxCaption.textContent = [item.title, item.caption].filter(Boolean).join(' — ');
      lightbox.classList.add('is-open');
      lightbox.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
      lightbox.classList.remove('is-open');
      lightbox.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }

    function stepLightbox(delta) {
      if (!visibleIndexes.length) return;
      const pos = visibleIndexes.indexOf(currentIndex);
      const nextPos = (pos + delta + visibleIndexes.length) % visibleIndexes.length;
      openLightbox(visibleIndexes[nextPos]);
    }

    filters.forEach((btn) => btn.addEventListener('click', () => applyFilter(btn.dataset.filter || 'all')));
    cards.forEach((card) => {
      card.addEventListener('click', () => openLightbox(Number(card.dataset.index)));
    });
    document.getElementById('lightboxClose')?.addEventListener('click', closeLightbox);
    document.getElementById('lightboxPrev')?.addEventListener('click', () => stepLightbox(-1));
    document.getElementById('lightboxNext')?.addEventListener('click', () => stepLightbox(1));
    lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', (e) => {
      if (!lightbox?.classList.contains('is-open')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') stepLightbox(-1);
      if (e.key === 'ArrowRight') stepLightbox(1);
    });
  </script>
</body>
</html>
