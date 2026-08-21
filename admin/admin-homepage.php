<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_guard.php';
require_once dirname(__DIR__) . '/includes/AdminPermissions.php';
AdminPermissions::require(AdminPermissions::PERM_CONTENT);

$activeNav = 'homepage';
$pageTitle = 'Homepage Content | Hope Architects Admin';
$adminName = htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator', ENT_QUOTES, 'UTF-8');
$pageStylesheet = '../assets/css/admin-content.css';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <?php require dirname(__DIR__) . '/includes/admin_assets.php'; ?>
</head>
<body>
<div class="dashboard">
  <?php require dirname(__DIR__) . '/includes/admin_sidebar.php'; ?>
  <div class="main-content">
    <header class="topbar">
      <button type="button" class="menu-toggle" id="menuToggle"><ion-icon name="menu-outline"></ion-icon></button>
      <h1 class="page-title">Homepage Content</h1>
      <span class="admin-badge">Signed in as <?= $adminName ?></span>
    </header>
    <div class="admin-content-pad">
      <p class="hint">Manage the full homepage: preloader, hero, about, why us, process, deliver section, and headings. Upload images/videos instead of pasting URLs. Featured projects, testimonials, and service areas still use their own admin pages.</p>

      <div class="content-tabs">
        <button type="button" class="content-tab active" data-tab="preloader">Preloader</button>
        <button type="button" class="content-tab" data-tab="slides">Hero Slides</button>
        <button type="button" class="content-tab" data-tab="hero">Hero CTAs</button>
        <button type="button" class="content-tab" data-tab="stats">Hero Stats</button>
        <button type="button" class="content-tab" data-tab="about">About</button>
        <button type="button" class="content-tab" data-tab="why">Why Us</button>
        <button type="button" class="content-tab" data-tab="process">Process</button>
        <button type="button" class="content-tab" data-tab="deliver">Deliver</button>
        <button type="button" class="content-tab" data-tab="sections">Other Headings</button>
      </div>

      <div class="panel content-panel active" id="panel-preloader">
        <h2>Preloader</h2>
        <p class="hint">Full-screen intro video shown before the homepage. MP4 / WebM / MOV up to 80 MB. Leave empty to use the first video in <code>assets/videos</code>.</p>
        <form id="preloaderForm" class="content-form-grid"></form>
        <button type="button" class="btn-gold" id="savePreloaderBtn">Save Preloader</button>
      </div>

      <div class="panel content-panel" id="panel-slides">
        <h2>Hero Slideshow</h2>
        <p class="hint">Upload a background image per slide (JPG, PNG, WebP — max 8 MB). Title may include <code>&lt;span class="accent"&gt;</code>.</p>
        <div id="slidesEditor"></div>
        <button type="button" class="btn-outline" id="addSlideBtn">+ Add Slide</button>
        <button type="button" class="btn-gold" id="saveSlidesBtn" style="margin-left:8px;">Save Slides</button>
      </div>

      <div class="panel content-panel" id="panel-hero">
        <h2>Hero CTAs &amp; Location</h2>
        <form id="heroForm" class="content-form-grid"></form>
        <button type="button" class="btn-gold" id="saveHeroBtn">Save Hero Settings</button>
      </div>

      <div class="panel content-panel" id="panel-stats">
        <h2>Hero Statistics Bar</h2>
        <div id="statsEditor"></div>
        <button type="button" class="btn-outline" id="addStatBtn">+ Add Stat</button>
        <button type="button" class="btn-gold" id="saveStatsBtn" style="margin-left:8px;">Save Stats</button>
      </div>

      <div class="panel content-panel" id="panel-about">
        <h2>About / Studio</h2>
        <form id="aboutForm" class="content-form-grid"></form>
        <h3 style="margin-top:24px;">Pillars</h3>
        <div id="aboutPillarsEditor"></div>
        <button type="button" class="btn-outline" id="addPillarBtn">+ Add Pillar</button>
        <h3 style="margin-top:24px;">Metrics</h3>
        <div id="aboutMetricsEditor"></div>
        <button type="button" class="btn-outline" id="addMetricBtn">+ Add Metric</button>
        <div style="margin-top:20px;">
          <button type="button" class="btn-gold" id="saveAboutBtn">Save About Section</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-why">
        <h2>Why Choose Us</h2>
        <form id="whyForm" class="content-form-grid"></form>
        <h3 style="margin-top:24px;">Reasons</h3>
        <div id="whyItemsEditor"></div>
        <button type="button" class="btn-outline" id="addWhyItemBtn">+ Add Reason</button>
        <div style="margin-top:20px;">
          <button type="button" class="btn-gold" id="saveWhyBtn">Save Why Us</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-process">
        <h2>Process Timeline</h2>
        <form id="processForm" class="content-form-grid"></form>
        <h3 style="margin-top:24px;">Steps</h3>
        <div id="processStepsEditor"></div>
        <button type="button" class="btn-outline" id="addProcessStepBtn">+ Add Step</button>
        <div style="margin-top:20px;">
          <button type="button" class="btn-gold" id="saveProcessBtn">Save Process</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-deliver">
        <h2>What We Deliver</h2>
        <form id="deliverForm" class="content-form-grid"></form>
        <h3 style="margin-top:24px;">Capability Tiles</h3>
        <div id="deliverTilesEditor"></div>
        <button type="button" class="btn-outline" id="addDeliverTileBtn">+ Add Tile</button>
        <div style="margin-top:20px;">
          <button type="button" class="btn-gold" id="saveDeliverBtn">Save Deliver Section</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-sections">
        <h2>Other Section Headings</h2>
        <p class="hint">Services cards, featured projects, testimonials, and areas load from their own admin modules — edit only the headings here.</p>
        <form id="sectionsForm" class="content-form-grid"></form>
        <button type="button" class="btn-gold" id="saveSectionsBtn">Save Headings</button>
      </div>
    </div>
  </div>
</div>

<script>
const API = 'api/homepage.php';
let content = {};
let previews = {};

function showToast(msg, err = false) {
  document.querySelector('.toast')?.remove();
  const t = document.createElement('div');
  t.className = 'toast' + (err ? ' error' : '');
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

function esc(s) {
  return String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

function previewHref(path) {
  if (!path) return '';
  if (/^https?:\/\//i.test(path)) return path;
  return '../' + String(path).replace(/^\.\//, '');
}

document.querySelectorAll('.content-tab').forEach(btn => {
  btn.onclick = () => {
    document.querySelectorAll('.content-tab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.content-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + btn.dataset.tab).classList.add('active');
  };
});

function field(label, key, value, rows = 1, hint = '') {
  const id = 'f_' + key.replace(/\W/g, '_');
  const hintHtml = hint ? `<small class="hint">${hint}</small>` : '';
  if (rows > 1) {
    return `<div class="form-field"><label for="${id}">${label}</label><textarea id="${id}" data-key="${key}" rows="${rows}">${esc(value)}</textarea>${hintHtml}</div>`;
  }
  return `<div class="form-field"><label for="${id}">${label}</label><input type="text" id="${id}" data-key="${key}" value="${esc(value)}">${hintHtml}</div>`;
}

function imageUploadHtml({ id, label, group, path, hint = 'JPG, PNG or WebP — max 8 MB' }) {
  const has = !!path;
  const src = previewHref(path);
  return `<div class="image-upload" data-upload-id="${id}">
    <span class="image-upload__label">${label}</span>
    <div class="image-upload__card">
      <img class="image-upload__preview${has ? ' visible' : ''}" id="${id}Preview" alt="" ${has ? `src="${esc(src)}"` : ''}>
      <p class="image-upload__meta" id="${id}Meta">${has ? esc(path) : 'No image uploaded yet'}</p>
      <input type="file" class="image-upload__file" id="${id}File" accept="image/jpeg,image/png,image/webp">
      <input type="hidden" class="upload-path" id="${id}Path" value="${esc(path || '')}">
      <div class="image-upload__actions">
        <button type="button" class="btn-outline" data-pick="${id}">Choose image</button>
        <button type="button" class="btn-gold" data-upload="${id}" data-group="${group}" data-kind="image">Upload</button>
        <button type="button" class="btn-outline" data-clear="${id}">Remove</button>
      </div>
      <small class="hint">${hint}</small>
    </div>
  </div>`;
}

function videoUploadHtml({ id, label, group, path }) {
  const has = !!path;
  return `<div class="image-upload" data-upload-id="${id}">
    <span class="image-upload__label">${label}</span>
    <div class="image-upload__card">
      ${has ? `<video class="image-upload__preview visible" id="${id}Preview" src="${esc(previewHref(path))}" muted playsinline controls style="max-height:180px;width:100%;object-fit:cover;"></video>` : `<div class="image-upload__preview" id="${id}Preview"></div>`}
      <p class="image-upload__meta" id="${id}Meta">${has ? esc(path) : 'No video uploaded — falls back to assets/videos'}</p>
      <input type="file" class="image-upload__file" id="${id}File" accept="video/mp4,video/webm,video/quicktime">
      <input type="hidden" class="upload-path" id="${id}Path" value="${esc(path || '')}">
      <div class="image-upload__actions">
        <button type="button" class="btn-outline" data-pick="${id}">Choose video</button>
        <button type="button" class="btn-gold" data-upload="${id}" data-group="${group}" data-kind="video">Upload</button>
        <button type="button" class="btn-outline" data-clear="${id}">Remove</button>
      </div>
      <small class="hint">MP4, WebM or MOV — max 80 MB</small>
    </div>
  </div>`;
}

function setUploadState(id, path, kind = 'image') {
  const meta = document.getElementById(id + 'Meta');
  const hidden = document.getElementById(id + 'Path');
  const preview = document.getElementById(id + 'Preview');
  if (!meta || !hidden) return;
  hidden.value = path || '';
  if (path) {
    meta.textContent = path;
    const src = previewHref(path) + '?t=' + Date.now();
    if (kind === 'video') {
      if (preview && preview.tagName === 'VIDEO') {
        preview.src = src;
        preview.classList.add('visible');
      } else if (preview?.parentElement) {
        preview.outerHTML = `<video class="image-upload__preview visible" id="${id}Preview" src="${esc(src)}" muted playsinline controls style="max-height:180px;width:100%;object-fit:cover;"></video>`;
      }
    } else if (preview) {
      preview.src = src;
      preview.classList.add('visible');
    }
  } else {
    meta.textContent = kind === 'video' ? 'No video uploaded — falls back to assets/videos' : 'No image uploaded yet';
    if (preview) {
      if (preview.tagName === 'VIDEO') {
        preview.removeAttribute('src');
        preview.classList.remove('visible');
      } else {
        preview.removeAttribute('src');
        preview.classList.remove('visible');
      }
    }
  }
}

async function uploadFile(id, group, kind) {
  const input = document.getElementById(id + 'File');
  if (!input?.files?.[0]) {
    showToast(kind === 'video' ? 'Choose a video first' : 'Choose an image first', true);
    return null;
  }
  const form = new FormData();
  form.append('action', 'upload');
  form.append('kind', kind);
  form.append('group', group);
  form.append('file', input.files[0]);
  const res = await fetch(API, { method: 'POST', body: form, credentials: 'same-origin' });
  const data = await res.json().catch(() => ({}));
  if (!res.ok || !data.success) throw new Error(data.message || ('Upload failed (' + res.status + ')'));
  input.value = '';
  setUploadState(id, data.path, kind);
  return data.path;
}

function bindUploads(root = document) {
  root.querySelectorAll('[data-pick]').forEach(btn => {
    btn.onclick = () => document.getElementById(btn.dataset.pick + 'File')?.click();
  });
  root.querySelectorAll('[data-upload]').forEach(btn => {
    btn.onclick = () => {
      uploadFile(btn.dataset.upload, btn.dataset.group || 'general', btn.dataset.kind || 'image')
        .then(() => showToast('Uploaded'))
        .catch(e => showToast(e.message, true));
    };
  });
  root.querySelectorAll('[data-clear]').forEach(btn => {
    btn.onclick = () => {
      if (!confirm('Remove this file from the field?')) return;
      const kind = btn.closest('.image-upload')?.querySelector('[data-kind]')?.dataset.kind || 'image';
      setUploadState(btn.dataset.clear, '', kind);
    };
  });
  root.querySelectorAll('.image-upload__file').forEach(input => {
    input.addEventListener('change', () => {
      const id = input.id.replace(/File$/, '');
      const meta = document.getElementById(id + 'Meta');
      if (meta && input.files?.[0]) meta.textContent = 'Selected: ' + input.files[0].name + ' — click Upload';
    });
  });
}

function collectKeyed(formId) {
  const out = {};
  document.querySelectorAll(`#${formId} [data-key]`).forEach(el => {
    const parts = el.dataset.key.split('.');
    let cur = out;
    for (let i = 0; i < parts.length - 1; i++) {
      if (!cur[parts[i]]) cur[parts[i]] = {};
      cur = cur[parts[i]];
    }
    if (el.type === 'checkbox') cur[parts[parts.length - 1]] = el.checked;
    else cur[parts[parts.length - 1]] = el.value;
  });
  return out;
}

async function save(partial) {
  const body = {
    action: 'save',
    preloader: partial.preloader ?? content.preloader,
    hero: partial.hero ?? content.hero,
    slides: partial.slides ?? content.slides,
    stats: partial.stats ?? content.stats,
    about: partial.about ?? content.about,
    whyUs: partial.whyUs ?? content.whyUs,
    process: partial.process ?? content.process,
    deliver: partial.deliver ?? content.deliver,
    sections: partial.sections ?? content.sections,
  };
  const res = await fetch(API, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'same-origin',
    body: JSON.stringify(body),
  });
  const data = await res.json().catch(() => ({}));
  if (!res.ok || !data.success) throw new Error(data.message || ('Save failed (' + res.status + ')'));
  content = data.content;
  previews = data.previews || {};
  renderAll();
  showToast('Saved');
}

/* ── Renderers ── */
function renderPreloader() {
  const p = content.preloader || {};
  document.getElementById('preloaderForm').innerHTML = [
    `<div class="form-field"><label><input type="checkbox" data-key="preloader.enabled" ${p.enabled ? 'checked' : ''}> Show preloader on homepage</label></div>`,
    field('Aria label', 'preloader.ariaLabel', p.ariaLabel || ''),
    videoUploadHtml({ id: 'preloaderVideo', label: 'Intro video', group: 'preloader', path: p.video || '' }),
  ].join('');
  bindUploads(document.getElementById('preloaderForm'));
}

function slideHtml(slide, i) {
  return `<div class="slide-editor" data-index="${i}">
    <span class="repeater-index">Slide ${i + 1}</span>
    <div class="form-field"><label>Eyebrow</label><input type="text" class="slide-eyebrow" value="${esc(slide.eyebrow)}"></div>
    <div class="form-field"><label>Title (HTML ok)</label><input type="text" class="slide-title" value="${esc(slide.title)}"></div>
    <div class="form-field"><label>Tagline</label><textarea class="slide-tagline" rows="2">${esc(slide.tagline)}</textarea></div>
    ${imageUploadHtml({ id: 'slideBg' + i, label: 'Background image', group: 'slide', path: slide.bgImage || '' })}
    <button type="button" class="btn-outline remove-slide">Remove</button>
  </div>`;
}

function renderSlides() {
  const el = document.getElementById('slidesEditor');
  el.innerHTML = (content.slides || []).map((s, i) => slideHtml(s, i)).join('');
  el.querySelectorAll('.remove-slide').forEach(btn => {
    btn.onclick = (e) => {
      const idx = parseInt(e.target.closest('.slide-editor').dataset.index, 10);
      content.slides.splice(idx, 1);
      renderSlides();
    };
  });
  bindUploads(el);
}

function collectSlides() {
  return [...document.querySelectorAll('#slidesEditor .slide-editor')].map(el => ({
    eyebrow: el.querySelector('.slide-eyebrow').value,
    title: el.querySelector('.slide-title').value,
    tagline: el.querySelector('.slide-tagline').value,
    bgImage: el.querySelector('.upload-path')?.value || '',
  }));
}

function renderHero() {
  const h = content.hero || {};
  document.getElementById('heroForm').innerHTML = [
    field('Location text', 'hero.location', h.location),
    field('Primary CTA label', 'hero.primaryCtaLabel', h.primaryCtaLabel),
    field('Primary CTA page key', 'hero.primaryCtaHref', h.primaryCtaHref, 1, 'e.g. property, contact, about'),
    field('Secondary CTA label', 'hero.secondaryCtaLabel', h.secondaryCtaLabel),
    field('Secondary CTA page key', 'hero.secondaryCtaHref', h.secondaryCtaHref),
  ].join('');
}

function renderStats() {
  const el = document.getElementById('statsEditor');
  el.innerHTML = (content.stats || []).map((stat, i) => `
    <div class="stat-editor" data-index="${i}">
      <div class="form-row-2">
        <div class="form-field"><label>Icon (ion-icon)</label><input type="text" class="stat-icon" value="${esc(stat.icon)}"></div>
        <div class="form-field"><label>Number</label><input type="text" class="stat-num" value="${esc(stat.num)}"></div>
      </div>
      <div class="form-field"><label>Label</label><input type="text" class="stat-label" value="${esc(stat.label)}"></div>
      <button type="button" class="btn-outline remove-stat">Remove</button>
    </div>`).join('');
  el.querySelectorAll('.remove-stat').forEach(btn => {
    btn.onclick = (e) => {
      content.stats.splice(parseInt(e.target.closest('.stat-editor').dataset.index, 10), 1);
      renderStats();
    };
  });
}

function collectStats() {
  return [...document.querySelectorAll('#statsEditor .stat-editor')].map(el => ({
    icon: el.querySelector('.stat-icon').value,
    num: el.querySelector('.stat-num').value,
    label: el.querySelector('.stat-label').value,
  }));
}

function renderAbout() {
  const a = content.about || {};
  document.getElementById('aboutForm').innerHTML = [
    field('Subtitle', 'about.subtitle', a.subtitle),
    field('Title', 'about.title', a.title),
    field('Lead text', 'about.text', a.text, 4),
    field('Quote', 'about.quote', a.quote, 2),
    field('CTA label', 'about.ctaLabel', a.ctaLabel),
    field('Ring number', 'about.ringNum', a.ringNum),
    field('Ring label', 'about.ringLabel', a.ringLabel),
    '<div class="image-upload-grid image-upload-grid--2">',
    imageUploadHtml({ id: 'aboutMain', label: 'Main image', group: 'about', path: a.imageMain || '' }),
    imageUploadHtml({ id: 'aboutFloat', label: 'Floating image', group: 'about', path: a.imageFloat || '' }),
    '</div>',
  ].join('');
  bindUploads(document.getElementById('aboutForm'));

  document.getElementById('aboutPillarsEditor').innerHTML = (a.pillars || []).map((item, i) => `
    <div class="stat-editor" data-index="${i}">
      <div class="form-row-2">
        <div class="form-field"><label>Icon</label><input type="text" class="pillar-icon" value="${esc(item.icon)}"></div>
        <div class="form-field"><label>Label</label><input type="text" class="pillar-label" value="${esc(item.label)}"></div>
      </div>
      <button type="button" class="btn-outline remove-pillar">Remove</button>
    </div>`).join('');
  document.querySelectorAll('.remove-pillar').forEach(btn => {
    btn.onclick = (e) => {
      content.about.pillars.splice(+e.target.closest('.stat-editor').dataset.index, 1);
      renderAbout();
    };
  });

  document.getElementById('aboutMetricsEditor').innerHTML = (a.metrics || []).map((item, i) => `
    <div class="stat-editor" data-index="${i}">
      <div class="form-row-2">
        <div class="form-field"><label>Number</label><input type="text" class="metric-num" value="${esc(item.num)}"></div>
        <div class="form-field"><label>Label</label><input type="text" class="metric-label" value="${esc(item.label)}"></div>
      </div>
      <button type="button" class="btn-outline remove-metric">Remove</button>
    </div>`).join('');
  document.querySelectorAll('.remove-metric').forEach(btn => {
    btn.onclick = (e) => {
      content.about.metrics.splice(+e.target.closest('.stat-editor').dataset.index, 1);
      renderAbout();
    };
  });
}

function collectAbout() {
  const base = collectKeyed('aboutForm').about || {};
  base.imageMain = document.getElementById('aboutMainPath')?.value || '';
  base.imageFloat = document.getElementById('aboutFloatPath')?.value || '';
  base.pillars = [...document.querySelectorAll('#aboutPillarsEditor .stat-editor')].map(el => ({
    icon: el.querySelector('.pillar-icon').value,
    label: el.querySelector('.pillar-label').value,
  }));
  base.metrics = [...document.querySelectorAll('#aboutMetricsEditor .stat-editor')].map(el => ({
    num: el.querySelector('.metric-num').value,
    label: el.querySelector('.metric-label').value,
  }));
  return base;
}

function renderWhy() {
  const w = content.whyUs || {};
  document.getElementById('whyForm').innerHTML = [
    field('Subtitle', 'whyUs.subtitle', w.subtitle),
    field('Title (HTML ok)', 'whyUs.title', w.title, 2),
    field('Body text', 'whyUs.text', w.text, 4),
    field('Badge number', 'whyUs.badgeNum', w.badgeNum),
    field('Badge text', 'whyUs.badgeTxt', w.badgeTxt),
    field('CTA label', 'whyUs.ctaLabel', w.ctaLabel),
    imageUploadHtml({ id: 'whyImage', label: 'Portrait / feature image', group: 'why', path: w.image || '' }),
  ].join('');
  bindUploads(document.getElementById('whyForm'));

  document.getElementById('whyItemsEditor').innerHTML = (w.items || []).map((item, i) => `
    <div class="slide-editor" data-index="${i}">
      <div class="form-field"><label>Icon</label><input type="text" class="why-icon" value="${esc(item.icon)}"></div>
      <div class="form-field"><label>Title</label><input type="text" class="why-title" value="${esc(item.title)}"></div>
      <div class="form-field"><label>Text</label><textarea class="why-text" rows="2">${esc(item.text)}</textarea></div>
      <button type="button" class="btn-outline remove-why-item">Remove</button>
    </div>`).join('');
  document.querySelectorAll('.remove-why-item').forEach(btn => {
    btn.onclick = (e) => {
      content.whyUs.items.splice(+e.target.closest('.slide-editor').dataset.index, 1);
      renderWhy();
    };
  });
}

function collectWhy() {
  const base = collectKeyed('whyForm').whyUs || {};
  base.image = document.getElementById('whyImagePath')?.value || '';
  base.items = [...document.querySelectorAll('#whyItemsEditor .slide-editor')].map(el => ({
    icon: el.querySelector('.why-icon').value,
    title: el.querySelector('.why-title').value,
    text: el.querySelector('.why-text').value,
  }));
  return base;
}

function renderProcess() {
  const p = content.process || {};
  document.getElementById('processForm').innerHTML = [
    field('Subtitle', 'process.subtitle', p.subtitle),
    field('Title', 'process.title', p.title),
  ].join('');
  document.getElementById('processStepsEditor').innerHTML = (p.steps || []).map((step, i) => `
    <div class="slide-editor" data-index="${i}">
      <span class="repeater-index">Step ${String(i + 1).padStart(2, '0')}</span>
      <div class="form-field"><label>Icon</label><input type="text" class="step-icon" value="${esc(step.icon)}"></div>
      <div class="form-field"><label>Title</label><input type="text" class="step-title" value="${esc(step.title)}"></div>
      <div class="form-field"><label>Text</label><textarea class="step-text" rows="2">${esc(step.text)}</textarea></div>
      <button type="button" class="btn-outline remove-step">Remove</button>
    </div>`).join('');
  document.querySelectorAll('.remove-step').forEach(btn => {
    btn.onclick = (e) => {
      content.process.steps.splice(+e.target.closest('.slide-editor').dataset.index, 1);
      renderProcess();
    };
  });
}

function collectProcess() {
  const base = collectKeyed('processForm').process || {};
  base.steps = [...document.querySelectorAll('#processStepsEditor .slide-editor')].map(el => ({
    icon: el.querySelector('.step-icon').value,
    title: el.querySelector('.step-title').value,
    text: el.querySelector('.step-text').value,
  }));
  return base;
}

function renderDeliver() {
  const d = content.deliver || {};
  document.getElementById('deliverForm').innerHTML = [
    field('Subtitle', 'deliver.subtitle', d.subtitle),
    field('Title (HTML ok)', 'deliver.title', d.title, 2),
    field('Lead', 'deliver.lead', d.lead, 3),
    field('CTA label', 'deliver.ctaLabel', d.ctaLabel),
    field('Stat number', 'deliver.statNum', d.statNum),
    field('Stat label', 'deliver.statLabel', d.statLabel),
  ].join('');
  document.getElementById('deliverTilesEditor').innerHTML = (d.tiles || []).map((tile, i) => `
    <div class="slide-editor" data-index="${i}">
      <span class="repeater-index">Tile ${String(i + 1).padStart(2, '0')}</span>
      <div class="form-field"><label>Icon</label><input type="text" class="tile-icon" value="${esc(tile.icon)}"></div>
      <div class="form-field"><label>Title</label><input type="text" class="tile-title" value="${esc(tile.title)}"></div>
      <div class="form-field"><label>Text</label><textarea class="tile-text" rows="2">${esc(tile.text)}</textarea></div>
      <label class="checkbox-inline"><input type="checkbox" class="tile-cta" ${tile.isCta ? 'checked' : ''}> Highlight as CTA tile</label>
      <button type="button" class="btn-outline remove-tile">Remove</button>
    </div>`).join('');
  document.querySelectorAll('.remove-tile').forEach(btn => {
    btn.onclick = (e) => {
      content.deliver.tiles.splice(+e.target.closest('.slide-editor').dataset.index, 1);
      renderDeliver();
    };
  });
}

function collectDeliver() {
  const base = collectKeyed('deliverForm').deliver || {};
  base.tiles = [...document.querySelectorAll('#deliverTilesEditor .slide-editor')].map(el => ({
    icon: el.querySelector('.tile-icon').value,
    title: el.querySelector('.tile-title').value,
    text: el.querySelector('.tile-text').value,
    isCta: el.querySelector('.tile-cta').checked,
  }));
  return base;
}

function renderSections() {
  const form = document.getElementById('sectionsForm');
  const sections = content.sections || {};
  form.innerHTML = Object.entries(sections).map(([key, fields]) => `
    <fieldset style="border:1px solid #eee;padding:12px;border-radius:8px;">
      <legend style="font-weight:600;text-transform:capitalize;">${esc(key)}</legend>
      ${Object.entries(fields).map(([field, val]) => `
        <div class="form-field">
          <label>${esc(field)}</label>
          <input type="text" data-section="${esc(key)}" data-field="${esc(field)}" value="${esc(val)}">
        </div>`).join('')}
    </fieldset>`).join('');
}

function collectSections() {
  const sections = JSON.parse(JSON.stringify(content.sections || {}));
  document.querySelectorAll('#sectionsForm input[data-section]').forEach(inp => {
    if (!sections[inp.dataset.section]) sections[inp.dataset.section] = {};
    sections[inp.dataset.section][inp.dataset.field] = inp.value;
  });
  return sections;
}

function collectPreloader() {
  const base = collectKeyed('preloaderForm').preloader || {};
  base.video = document.getElementById('preloaderVideoPath')?.value || '';
  const enabled = document.querySelector('#preloaderForm [data-key="preloader.enabled"]');
  base.enabled = !!enabled?.checked;
  return base;
}

function renderAll() {
  renderPreloader();
  renderSlides();
  renderHero();
  renderStats();
  renderAbout();
  renderWhy();
  renderProcess();
  renderDeliver();
  renderSections();
}

/* ── Actions ── */
document.getElementById('addSlideBtn').onclick = () => {
  content.slides = content.slides || [];
  content.slides.push({ eyebrow: '', title: '', tagline: '', bgImage: '' });
  renderSlides();
};
document.getElementById('addStatBtn').onclick = () => {
  content.stats = content.stats || [];
  content.stats.push({ icon: 'home-outline', num: '', label: '' });
  renderStats();
};
document.getElementById('addPillarBtn').onclick = () => {
  content.about.pillars = content.about.pillars || [];
  content.about.pillars.push({ icon: 'home-outline', label: '' });
  renderAbout();
};
document.getElementById('addMetricBtn').onclick = () => {
  content.about.metrics = content.about.metrics || [];
  content.about.metrics.push({ num: '', label: '' });
  renderAbout();
};
document.getElementById('addWhyItemBtn').onclick = () => {
  content.whyUs.items = content.whyUs.items || [];
  content.whyUs.items.push({ icon: 'home-outline', title: '', text: '' });
  renderWhy();
};
document.getElementById('addProcessStepBtn').onclick = () => {
  content.process.steps = content.process.steps || [];
  content.process.steps.push({ icon: 'home-outline', title: '', text: '' });
  renderProcess();
};
document.getElementById('addDeliverTileBtn').onclick = () => {
  content.deliver.tiles = content.deliver.tiles || [];
  content.deliver.tiles.push({ icon: 'home-outline', title: '', text: '', isCta: false });
  renderDeliver();
};

document.getElementById('savePreloaderBtn').onclick = () => save({ preloader: collectPreloader() }).catch(e => showToast(e.message, true));
document.getElementById('saveSlidesBtn').onclick = () => save({ slides: collectSlides() }).catch(e => showToast(e.message, true));
document.getElementById('saveHeroBtn').onclick = () => save({ hero: collectKeyed('heroForm').hero }).catch(e => showToast(e.message, true));
document.getElementById('saveStatsBtn').onclick = () => save({ stats: collectStats() }).catch(e => showToast(e.message, true));
document.getElementById('saveAboutBtn').onclick = () => save({ about: collectAbout() }).catch(e => showToast(e.message, true));
document.getElementById('saveWhyBtn').onclick = () => save({ whyUs: collectWhy() }).catch(e => showToast(e.message, true));
document.getElementById('saveProcessBtn').onclick = () => save({ process: collectProcess() }).catch(e => showToast(e.message, true));
document.getElementById('saveDeliverBtn').onclick = () => save({ deliver: collectDeliver() }).catch(e => showToast(e.message, true));
document.getElementById('saveSectionsBtn').onclick = () => save({ sections: collectSections() }).catch(e => showToast(e.message, true));

fetch(API, { credentials: 'same-origin' })
  .then(async (r) => {
    const data = await r.json().catch(() => ({}));
    if (!r.ok || !data.success) throw new Error(data.message || ('Failed to load homepage content (' + r.status + ')'));
    content = data.content;
    previews = data.previews || {};
    renderAll();
  })
  .catch(e => showToast(e.message, true));
</script>
</body>
</html>
