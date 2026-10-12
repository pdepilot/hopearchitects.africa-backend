<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php require_once __DIR__ . '/includes/site_helpers.php'; ?>
<!DOCTYPE html>
<?php require_once __DIR__ . '/includes/site_paths.php'; ?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="View full project details, photos, video tour, and specifications with Hope Architects.">
  <title>Project Details | Hope Architects</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/property-detail.css">
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  </head>
<body>

<?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

<main id="main-content">
  <div class="page-container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= siteEscape(pageUrl('index')) ?>">Home</a>
      <ion-icon name="chevron-forward-outline"></ion-icon>
      <a href="<?= siteEscape(pageUrl('property')) ?>">Projects</a>
      <ion-icon name="chevron-forward-outline"></ion-icon>
      <span id="breadcrumbTitle">Project Details</span>
    </nav>

    <div id="detailContent">
      <div class="state-box">
        <p>Loading property details...</p>
      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

<div class="lightbox" id="lightbox" aria-hidden="true" role="dialog" aria-label="Image gallery">
  <button type="button" class="lightbox-close" id="lightboxClose" aria-label="Close gallery">&times;</button>
  <button type="button" class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Previous image">&#8249;</button>
  <img id="lightboxImage" src="" alt="">
  <button type="button" class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Next image">&#8250;</button>
  <span class="lightbox-counter" id="lightboxCounter"></span>
</div>

<script src="./assets/js/site-header.js" defer></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script>
(function() {
  'use strict';

  const API = window.BIVER_SITE?.propertiesApi || 'api/properties.php';
  let galleryImages = [];
  let lightboxIndex = 0;
  let currentProperty = null;

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, (m) => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[m]));
  }

  function resolveMediaUrl(url, fallbackIndex) {
    const portfolio = window.HOPE_PORTFOLIO || {};
    if (portfolio.resolveMediaUrl) {
      return portfolio.resolveMediaUrl(url, typeof fallbackIndex === 'number' ? fallbackIndex : 0);
    }
    if (!url) return portfolio.imageAt ? portfolio.imageAt(0) : '';
    if (url.startsWith('http') || url.startsWith('/')) return url;
    if (url.startsWith('assets/')) return './' + url;
    return url;
  }

  function listingLabel(property) {
    if (property.listingPurpose) {
      return String(property.listingPurpose).replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
    }
    if (window.HOPE_PORTFOLIO && window.HOPE_PORTFOLIO.categoryBadge) {
      return window.HOPE_PORTFOLIO.categoryBadge(property.type).label;
    }
    return property.type === 'rent' ? 'Interior Design' : 'Architecture';
  }

  function getAllImages(property) {
    const portfolio = window.HOPE_PORTFOLIO || {};
    if (Array.isArray(property.images) && property.images.length) {
      return property.images.map((url, i) => resolveMediaUrl(url, i));
    }
    const urls = [];
    if (property.imageUrl) urls.push(resolveMediaUrl(property.imageUrl, 0));
    if (Array.isArray(property.galleryUrls)) {
      property.galleryUrls.forEach((url, i) => urls.push(resolveMediaUrl(url, i + 1)));
    }
    if (!urls.length) {
      urls.push(portfolio.imageAt ? portfolio.imageAt(property.id || 0) : './assets/images/Scene 10_1.png');
    }
    return urls;
  }

  function parseFeatures(raw) {
    if (!raw) return [];
    return String(raw).split(/[,;|•\n]+/).map((item) => item.trim()).filter(Boolean);
  }

  function openLightbox(index) {
    lightboxIndex = index;
    updateLightbox();
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
  }

  function updateLightbox() {
    const img = document.getElementById('lightboxImage');
    img.src = galleryImages[lightboxIndex];
    img.alt = `${currentProperty?.title || 'Property'} photo ${lightboxIndex + 1}`;
    document.getElementById('lightboxCounter').textContent = `${lightboxIndex + 1} / ${galleryImages.length}`;
  }

  document.getElementById('lightboxClose').addEventListener('click', closeLightbox);
  document.getElementById('lightbox').addEventListener('click', (e) => { if (e.target.id === 'lightbox') closeLightbox(); });
  document.getElementById('lightboxPrev').addEventListener('click', () => {
    lightboxIndex = (lightboxIndex - 1 + galleryImages.length) % galleryImages.length;
    updateLightbox();
  });
  document.getElementById('lightboxNext').addEventListener('click', () => {
    lightboxIndex = (lightboxIndex + 1) % galleryImages.length;
    updateLightbox();
  });
  document.addEventListener('keydown', (e) => {
    if (!document.getElementById('lightbox').classList.contains('open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') document.getElementById('lightboxPrev').click();
    if (e.key === 'ArrowRight') document.getElementById('lightboxNext').click();
  });

  function renderGallery(images, property) {
    const badgeMeta = window.HOPE_PORTFOLIO && window.HOPE_PORTFOLIO.categoryBadge
      ? window.HOPE_PORTFOLIO.categoryBadge(property.type)
      : { className: property.type === 'rent' ? 'interior' : 'architecture' };
    const badgeClass = badgeMeta.className;
    const singleClass = images.length <= 1 ? 'gallery-single' : '';
    const side = images.slice(1, 3);
    const moreCount = Math.max(0, images.length - 3);

    let sideHtml = side.map((url, i) => `
      <div class="gallery-side-item" data-index="${i + 1}" role="button" tabindex="0" aria-label="View photo ${i + 2}">
        <img src="${escapeHtml(url)}" alt="">
        ${i === 1 && moreCount > 0 ? `<div class="gallery-more">+${moreCount} more</div>` : ''}
      </div>
    `).join('');

    if (images.length === 2) {
      sideHtml = `
        <div class="gallery-side-item" data-index="1" role="button" tabindex="0">
          <img src="${escapeHtml(images[1])}" alt="">
        </div>
        <div class="gallery-side-item gallery-side-placeholder">More views below</div>`;
    }

    const thumbs = images.map((url, i) => `
      <button type="button" class="${i === 0 ? 'active' : ''}" data-thumb="${i}" aria-label="Show photo ${i + 1}">
        <img src="${escapeHtml(url)}" alt="">
      </button>
    `).join('');

    return `
      <div class="gallery-hero ${singleClass}">
        <div class="gallery-main" data-index="0" role="button" tabindex="0" aria-label="View main photo">
          <img id="heroMainImage" src="${escapeHtml(images[0])}" alt="${escapeHtml(property.title)}">
          <span class="gallery-badge ${badgeClass}">${escapeHtml(listingLabel(property))}</span>
          <span class="gallery-count"><ion-icon name="images-outline"></ion-icon> ${images.length} Photos</span>
        </div>
        ${images.length > 1 ? `<div class="gallery-side">${sideHtml}</div>` : ''}
      </div>
      ${images.length > 1 ? `<div class="thumb-strip" id="thumbStrip">${thumbs}</div>` : ''}
    `;
  }

  function bindGalleryEvents() {
    document.querySelectorAll('[data-index]').forEach((el) => {
      const open = () => openLightbox(Number(el.dataset.index));
      el.addEventListener('click', open);
      el.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); } });
    });

    document.querySelectorAll('[data-thumb]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const index = Number(btn.dataset.thumb);
        const hero = document.getElementById('heroMainImage');
        if (hero) hero.src = galleryImages[index];
        document.querySelectorAll('[data-thumb]').forEach((b) => b.classList.toggle('active', b === btn));
        document.querySelector('.gallery-main')?.setAttribute('data-index', String(index));
      });
    });
  }

  function renderInfoRows(property) {
    const rows = [
      ['Property type', property.propertyCategory || 'Residential'],
      ['Listing purpose', listingLabel(property)],
      ['Location', property.location],
      ['Full address', property.propertyAddress],
      ['Plot / size', property.propertySize || (property.area ? property.area + ' sqm' : null)],
      ['Ownership', property.ownershipStatus],
      ['Reference ID', 'HA-' + property.id]
    ].filter(([, value]) => value);

    return rows.map(([label, value]) => `
      <div class="info-row"><dt>${escapeHtml(label)}</dt><dd>${escapeHtml(String(value))}</dd></div>
    `).join('');
  }

  function renderProperty(property) {
    currentProperty = property;
    galleryImages = getAllImages(property);
    const video = property.videoUrl ? resolveMediaUrl(property.videoUrl) : '';
    const features = parseFeatures(property.propertyFeatures);
    const contactUrl = (window.BIVER_SITE?.page || ((n, p) => n + (p ? '?' + new URLSearchParams(p) : '')))('contact', { property: property.title });
    const whatsappDigits = window.HOPE_SITE?.whatsappDigits || window.BIVER_SITE?.whatsappDigits || '2348063896199';
    const contactTel = window.HOPE_SITE?.contactTel || window.BIVER_SITE?.contactTel || '+2348084309701';
    const contactPhone = window.HOPE_SITE?.contactPhone || window.BIVER_SITE?.contactPhone || '+234 808 430 9701';
    const whatsappText = encodeURIComponent(`Hello Hope Architects, I am interested in: ${property.title} (Ref HA-${property.id})`);
    const whatsappUrl = 'https://wa.me/' + whatsappDigits + '?text=' + whatsappText;

    document.title = `${property.title} | Hope Architects`;
    document.getElementById('breadcrumbTitle').textContent = property.title;

    document.getElementById('detailContent').innerHTML = `
      ${renderGallery(galleryImages, property)}

      <div class="detail-layout">
        <div class="detail-main">
          <div class="title-block">
            <h1>${escapeHtml(property.title)}</h1>
            <div class="title-meta">
              <span><ion-icon name="location-outline"></ion-icon>${escapeHtml(property.location)}</span>
              ${property.propertyAddress ? `<span><ion-icon name="navigate-outline"></ion-icon>${escapeHtml(property.propertyAddress)}</span>` : ''}
            </div>
          </div>

          <div class="spec-grid">
            <div class="spec-card"><ion-icon name="bed-outline"></ion-icon><strong>${property.bedrooms || 0}</strong><span>Bedrooms</span></div>
            <div class="spec-card"><ion-icon name="water-outline"></ion-icon><strong>${property.bathrooms || 0}</strong><span>Bathrooms</span></div>
            <div class="spec-card"><ion-icon name="resize-outline"></ion-icon><strong>${property.area || property.propertySize || '—'}</strong><span>${property.area ? 'Sqm' : 'Size'}</span></div>
            <div class="spec-card"><ion-icon name="home-outline"></ion-icon><strong>${escapeHtml(property.propertyCategory || 'Home')}</strong><span>Category</span></div>
          </div>

          <div class="section-card">
            <h2 class="section-heading">About this property</h2>
            <div class="description-text">${escapeHtml(property.description || 'Contact our team for a full briefing on this property, title documents, and inspection availability.')}</div>
          </div>

          ${features.length ? `
          <div class="section-card">
            <h2 class="section-heading">Features &amp; amenities</h2>
            <div class="feature-chips">
              ${features.map((feature) => `<span class="feature-chip"><ion-icon name="checkmark-circle-outline"></ion-icon>${escapeHtml(feature)}</span>`).join('')}
            </div>
          </div>` : ''}

          <div class="section-card">
            <h2 class="section-heading">Property information</h2>
            <dl class="info-table">${renderInfoRows(property)}</dl>
          </div>

          <div class="section-card">
            <h2 class="section-heading">${video ? 'Video tour' : 'Media'}</h2>
            ${video
              ? `<video src="${escapeHtml(video)}" controls playsinline preload="metadata" poster="${escapeHtml(galleryImages[0])}">Your browser does not support video playback.</video>`
              : `<div class="video-placeholder"><span><ion-icon name="videocam-outline"></ion-icon>Video tour available on request</span></div>`
            }
          </div>
        </div>

        <aside class="detail-sidebar">
          <div class="project-card-sidebar">
            <p class="project-card-sidebar__label">${escapeHtml(listingLabel(property))}</p>
            <p class="project-card-sidebar__title">${escapeHtml(property.title)}</p>
            <p class="project-card-sidebar__note">Verified project by Hope Architects</p>
            <div class="project-card-sidebar__stats">
              <div class="project-card-sidebar__stat"><strong>${property.bedrooms || 0}</strong><span>Spaces</span></div>
              <div class="project-card-sidebar__stat"><strong>${property.bathrooms || 0}</strong><span>Zones</span></div>
              <div class="project-card-sidebar__stat"><strong>${property.area || '—'}</strong><span>${property.area ? 'Sqm' : 'Scale'}</span></div>
            </div>
            <div class="cta-stack">
              <a href="${contactUrl}" class="cta-btn cta-btn-primary"><ion-icon name="calendar-outline"></ion-icon>Schedule Viewing</a>
              <a href="${whatsappUrl}" class="cta-btn cta-btn-outline" target="_blank" rel="noopener noreferrer"><ion-icon name="logo-whatsapp"></ion-icon>Chat on WhatsApp</a>
              <a href="tel:${contactTel.replace(/[^\d+]/g, '')}" class="cta-btn cta-btn-ghost"><ion-icon name="call-outline"></ion-icon>${escapeHtml(contactPhone)}</a>
              <button type="button" class="cta-btn cta-btn-ghost" id="shareBtn"><ion-icon name="share-social-outline"></ion-icon>Share Listing</button>
            </div>
            <div class="trust-row">
              <div class="trust-item"><ion-icon name="shield-checkmark-outline"></ion-icon>Verified listing</div>
              <div class="trust-item"><ion-icon name="people-outline"></ion-icon>Expert agents</div>
              <div class="trust-item"><ion-icon name="document-text-outline"></ion-icon>Title support</div>
            </div>
          </div>
        </aside>
      </div>

      <section class="related-section" id="relatedSection" hidden>
        <h2>You may also like</h2>
        <div class="related-grid" id="relatedGrid"></div>
      </section>
    `;

    bindGalleryEvents();

    document.getElementById('shareBtn')?.addEventListener('click', async () => {
      const shareData = { title: property.title, text: `Check out this property: ${property.title}`, url: window.location.href };
      try {
        if (navigator.share) await navigator.share(shareData);
        else {
          await navigator.clipboard.writeText(window.location.href);
          alert('Link copied to clipboard.');
        }
      } catch (_) { /* user cancelled */ }
    });

    loadRelated(property.id);
  }

  async function loadRelated(currentId) {
    try {
      const res = await fetch(`${API}?limit=4`);
      const data = await res.json();
      const list = (data.properties || []).filter((p) => String(p.id) !== String(currentId)).slice(0, 3);
      if (!list.length) return;

      document.getElementById('relatedSection').hidden = false;
      document.getElementById('relatedGrid').innerHTML = list.map((p) => {
        const img = getAllImages(p)[0];
        const detailUrl = window.BIVER_SITE?.propertyDetail
          ? window.BIVER_SITE.propertyDetail(p._id || p.id)
          : 'property-detail?id=' + encodeURIComponent(p._id || p.id);
        return `
          <a href="${detailUrl}" class="related-card">
            <img src="${escapeHtml(img)}" alt="${escapeHtml(p.title)}" loading="lazy">
            <div class="related-card-body">
              <h3>${escapeHtml(p.title)}</h3>
              <p>${escapeHtml(p.location || '')}</p>
              <span class="related-category">${escapeHtml(listingLabel(p))}</span>
            </div>
          </a>`;
      }).join('');
    } catch (_) { /* optional section */ }
  }

  function renderError(message) {
    document.getElementById('detailContent').innerHTML = `
      <div class="state-box">
        <h2>Property Not Available</h2>
        <p>${escapeHtml(message)}</p>
        <a href="<?= siteEscape(pageUrl('property')) ?>" class="btn btn-primary">Browse All Projects</a>
      </div>`;
  }

  async function loadProperty() {
    const id = new URLSearchParams(window.location.search).get('id');
    if (!id) {
      renderError('No property was selected.');
      return;
    }

    try {
      const res = await fetch(`${API}?id=${encodeURIComponent(id)}`);
      const data = await res.json();
      if (!res.ok || data.success === false || !data.property) {
        throw new Error(data.message || 'Property not found.');
      }
      renderProperty(data.property);
    } catch (err) {
      renderError(err.message || 'Unable to load this property.');
    }
  }

  loadProperty();
})();
</script>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
</body>
</html>
