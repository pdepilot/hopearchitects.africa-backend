<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_guard.php';
require_once dirname(__DIR__) . '/includes/AdminPermissions.php';
AdminPermissions::require(AdminPermissions::PERM_CONTENT);

$activeNav = 'about';
$pageTitle = 'About Page | Hope Architects Admin';
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
<body class="admin-app admin-about-editor">
<div class="dashboard admin-dashboard">
  <?php require dirname(__DIR__) . '/includes/admin_sidebar.php'; ?>
  <div class="main-content">
    <header class="topbar">
      <button type="button" class="menu-toggle" id="menuToggle" aria-label="Open menu"><ion-icon name="menu-outline"></ion-icon></button>
      <h1 class="page-title">About Page</h1>
      <span class="admin-badge">Signed in as <?= $adminName ?></span>
    </header>

    <div class="admin-content-pad">
      <p class="hint">Manage every section on the public <a href="../about.php" target="_blank" rel="noopener">About Us</a> page. Upload images directly (JPG, PNG, WebP). HTML is allowed in the hero title (e.g. <code>&lt;span class="gold-accent"&gt;</code>).</p>

      <div class="content-tabs" role="tablist">
        <button type="button" class="content-tab active" data-tab="hero">Hero</button>
        <button type="button" class="content-tab" data-tab="story">Untold Story</button>
        <button type="button" class="content-tab" data-tab="journey">Timeline</button>
        <button type="button" class="content-tab" data-tab="values">Values</button>
        <button type="button" class="content-tab" data-tab="team">Team</button>
        <button type="button" class="content-tab" data-tab="cta">CTA</button>
      </div>

      <div class="panel content-panel active" id="panel-hero">
        <h2>Hero Section</h2>
        <p class="hint">Top banner — subtitle, headline, intro text, and stat counters.</p>
        <form id="heroForm" class="content-form-grid"></form>
        <h3 class="panel-subtitle">Hero statistics</h3>
        <div id="heroStatsEditor" class="repeater-list"></div>
        <div class="content-actions">
          <button type="button" class="btn-outline" id="addHeroStatBtn">+ Add Stat</button>
          <button type="button" class="btn-gold" id="saveHeroBtn">Save Hero</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-story">
        <h2>The Untold Story</h2>
        <p class="hint">Narrative copy and side-by-side images — upload photos below.</p>
        <form id="storyForm" class="content-form-grid"></form>
        <div class="content-actions">
          <button type="button" class="btn-gold" id="saveStoryBtn">Save Story Section</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-journey">
        <h2>Timeline / Journey</h2>
        <p class="hint">Company milestones shown in the vertical timeline.</p>
        <form id="journeyMetaForm" class="content-form-grid"></form>
        <h3 class="panel-subtitle">Milestones</h3>
        <div id="journeyItemsEditor" class="repeater-list"></div>
        <div class="content-actions">
          <button type="button" class="btn-outline" id="addJourneyItemBtn">+ Add Milestone</button>
          <button type="button" class="btn-gold" id="saveJourneyBtn">Save Timeline</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-values">
        <h2>Core Values</h2>
        <p class="hint">Value cards with icons — upload the large side image.</p>
        <form id="valuesMetaForm" class="content-form-grid"></form>
        <h3 class="panel-subtitle">Value cards</h3>
        <div id="valuesItemsEditor" class="repeater-list"></div>
        <div class="content-actions">
          <button type="button" class="btn-outline" id="addValueItemBtn">+ Add Value</button>
          <button type="button" class="btn-gold" id="saveValuesBtn">Save Values</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-team">
        <h2>Team Preview</h2>
        <p class="hint">Team member cards — upload a photo for each person.</p>
        <form id="teamMetaForm" class="content-form-grid"></form>
        <h3 class="panel-subtitle">Team members</h3>
        <div id="teamMembersEditor" class="repeater-list"></div>
        <div class="content-actions">
          <button type="button" class="btn-outline" id="addTeamMemberBtn">+ Add Team Member</button>
          <button type="button" class="btn-gold" id="saveTeamBtn">Save Team</button>
        </div>
      </div>

      <div class="panel content-panel" id="panel-cta">
        <h2>Call to Action</h2>
        <p class="hint">Closing section at the bottom of the About page.</p>
        <form id="ctaForm" class="content-form-grid"></form>
        <div class="content-actions">
          <button type="button" class="btn-gold" id="saveCtaBtn">Save CTA</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const API = 'api/pages.php?page=about';
let content = {};

function showToast(msg, err = false) {
  document.querySelector('.toast')?.remove();
  const t = document.createElement('div');
  t.className = 'toast' + (err ? ' error' : '');
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

function esc(s) {
  return String(s ?? '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}

function field(label, key, value, rows = 1, hint = '') {
  const id = 'f_' + key.replace(/\./g, '_');
  const hintHtml = hint ? `<small class="hint">${hint}</small>` : '';
  if (rows > 1) {
    return `<div class="form-field"><label for="${id}">${label}</label><textarea id="${id}" data-key="${key}" rows="${rows}">${esc(value)}</textarea>${hintHtml}</div>`;
  }
  return `<div class="form-field"><label for="${id}">${label}</label><input type="text" id="${id}" data-key="${key}" value="${esc(value)}">${hintHtml}</div>`;
}

const MEDIA_API = 'api/about-media.php';

function previewHref(path) {
  if (!path) return '';
  if (/^https?:\/\//i.test(path)) return path;
  return '../' + path.replace(/^\.\//, '');
}

function imageUploadHtml({ id, label, hint, group, pathKey, currentPath }) {
  const previewSrc = previewHref(currentPath);
  const hasImage = !!currentPath;
  return `<div class="image-upload" data-upload-id="${id}">
    <span class="image-upload__label">${label}</span>
    <div class="image-upload__card">
      <img class="image-upload__preview${hasImage ? ' visible' : ''}" id="${id}Preview" alt="" ${hasImage ? `src="${esc(previewSrc)}"` : ''}>
      <p class="image-upload__meta" id="${id}Meta">${hasImage ? esc(currentPath) : 'No image uploaded yet'}</p>
      <input type="file" class="image-upload__file" id="${id}File" accept="image/jpeg,image/png,image/webp">
      <input type="hidden" data-key="${pathKey}" id="${id}Path" value="${esc(currentPath)}">
      <div class="image-upload__actions">
        <button type="button" class="btn-outline" data-pick="${id}">Choose image</button>
        <button type="button" class="btn-gold" data-upload="${id}" data-group="${group}">Upload</button>
        <button type="button" class="btn-outline" data-clear="${id}">Remove</button>
      </div>
      ${hint ? `<small class="hint">${hint}</small>` : ''}
    </div>
  </div>`;
}

function setImageUploadState(id, path) {
  const preview = document.getElementById(id + 'Preview');
  const meta = document.getElementById(id + 'Meta');
  const hidden = document.getElementById(id + 'Path');
  if (!preview || !meta || !hidden) return;
  hidden.value = path || '';
  if (path) {
    preview.src = previewHref(path) + '?t=' + Date.now();
    preview.classList.add('visible');
    meta.textContent = path;
  } else {
    preview.removeAttribute('src');
    preview.classList.remove('visible');
    meta.textContent = 'No image uploaded yet';
  }
}

async function uploadAboutImage(id, group) {
  const input = document.getElementById(id + 'File');
  if (!input?.files?.[0]) {
    showToast('Choose an image first', true);
    return;
  }
  const form = new FormData();
  form.append('action', 'upload');
  form.append('group', group);
  form.append('image', input.files[0]);
  const res = await fetch(MEDIA_API, { method: 'POST', body: form, credentials: 'same-origin' });
  const data = await res.json();
  if (!data.success) throw new Error(data.message || 'Upload failed');
  input.value = '';
  setImageUploadState(id, data.path);
  return data.path;
}

function bindImageUploads(root = document) {
  root.querySelectorAll('[data-pick]').forEach(btn => {
    btn.onclick = () => document.getElementById(btn.dataset.pick + 'File')?.click();
  });
  root.querySelectorAll('[data-upload]').forEach(btn => {
    btn.onclick = () => {
      uploadAboutImage(btn.dataset.upload, btn.dataset.group || 'general')
        .then(() => showToast('Image uploaded'))
        .catch(e => showToast(e.message, true));
    };
  });
  root.querySelectorAll('[data-clear]').forEach(btn => {
    btn.onclick = () => {
      if (!confirm('Remove this image from the section?')) return;
      setImageUploadState(btn.dataset.clear, '');
    };
  });
  root.querySelectorAll('.image-upload__file').forEach(input => {
    input.addEventListener('change', () => {
      const id = input.id.replace(/File$/, '');
      const meta = document.getElementById(id + 'Meta');
      if (meta && input.files?.[0]) {
        meta.textContent = 'Selected: ' + input.files[0].name + ' — click Upload';
      }
    });
  });
}

function collectKeyedForm(formId) {
  const out = {};
  document.querySelectorAll(`#${formId} [data-key]`).forEach(el => {
  const parts = el.dataset.key.split('.');
  let cur = out;
  for (let i = 0; i < parts.length - 1; i++) {
    if (!cur[parts[i]]) cur[parts[i]] = {};
    cur = cur[parts[i]];
  }
  cur[parts[parts.length - 1]] = el.value;
  });
  return out;
}

function setNested(obj, path, value) {
  const parts = path.split('.');
  let cur = obj;
  for (let i = 0; i < parts.length - 1; i++) {
    if (!cur[parts[i]]) cur[parts[i]] = {};
    cur = cur[parts[i]];
  }
  cur[parts[parts.length - 1]] = value;
}

function renderHero() {
  const h = content.hero || {};
  document.getElementById('heroForm').innerHTML = [
    field('Subtitle', 'hero.subtitle', h.subtitle),
    field('Title (HTML allowed)', 'hero.title', h.title, 1, 'Use &lt;span class="gold-accent"&gt; for gold highlights.'),
    field('Description', 'hero.description', h.description, 3),
  ].join('');
  renderHeroStats();
}

function heroStatHtml(stat, i) {
  return `<div class="stat-editor" data-index="${i}">
    <span class="repeater-index">Stat ${i + 1}</span>
    <div class="form-row-2">
      <div class="form-field"><label>Number</label><input type="text" class="hero-stat-num" value="${esc(stat.num)}"></div>
      <div class="form-field"><label>Label</label><input type="text" class="hero-stat-label" value="${esc(stat.label)}"></div>
    </div>
    <button type="button" class="btn-outline remove-hero-stat">Remove</button>
  </div>`;
}

function renderHeroStats() {
  const el = document.getElementById('heroStatsEditor');
  const stats = content.hero?.stats || [];
  el.innerHTML = stats.map((s, i) => heroStatHtml(s, i)).join('');
  el.querySelectorAll('.remove-hero-stat').forEach(btn => {
    btn.onclick = (e) => {
      const idx = parseInt(e.target.closest('.stat-editor').dataset.index, 10);
      content.hero.stats.splice(idx, 1);
      renderHeroStats();
    };
  });
}

function collectHeroStats() {
  return [...document.querySelectorAll('#heroStatsEditor .stat-editor')].map(el => ({
    num: el.querySelector('.hero-stat-num').value.trim(),
    label: el.querySelector('.hero-stat-label').value.trim(),
  })).filter(s => s.num || s.label);
}

function renderStory() {
  const n = content.narrative || {};
  document.getElementById('storyForm').innerHTML = [
    field('Badge', 'narrative.badge', n.badge),
    field('Title', 'narrative.title', n.title),
    field('Paragraph 1', 'narrative.paragraph1', n.paragraph1, 4),
    field('Paragraph 2', 'narrative.paragraph2', n.paragraph2, 4),
    field('Quote', 'narrative.quote', n.quote, 2),
    field('Signature', 'narrative.signature', n.signature),
    field('Image caption', 'narrative.caption', n.caption),
    '<div class="image-upload-grid image-upload-grid--2">',
    imageUploadHtml({ id: 'storyMain', label: 'Main image', group: 'narrative', pathKey: 'narrative.mainImage', currentPath: n.mainImage || '', hint: 'JPG, PNG or WebP — max 8 MB' }),
    imageUploadHtml({ id: 'storyFloat', label: 'Floating image', group: 'narrative', pathKey: 'narrative.floatingImage', currentPath: n.floatingImage || '', hint: 'Smaller overlay image beside the main photo' }),
    '</div>',
  ].join('');
  bindImageUploads(document.getElementById('storyForm'));
}

function journeyItemHtml(item, i) {
  return `<div class="slide-editor" data-index="${i}">
    <span class="repeater-index">Milestone ${i + 1}</span>
    <div class="form-row-2">
      <div class="form-field"><label>Year</label><input type="text" class="journey-year" value="${esc(item.year)}"></div>
      <div class="form-field"><label>Title</label><input type="text" class="journey-title" value="${esc(item.title)}"></div>
    </div>
    <div class="form-field"><label>Description</label><textarea class="journey-text" rows="2">${esc(item.text)}</textarea></div>
    <button type="button" class="btn-outline remove-journey-item">Remove</button>
  </div>`;
}

function renderJourney() {
  const j = content.journey || {};
  document.getElementById('journeyMetaForm').innerHTML = [
    field('Eyebrow', 'journey.eyebrow', j.eyebrow),
    field('Section title', 'journey.title', j.title),
  ].join('');
  const el = document.getElementById('journeyItemsEditor');
  const items = j.items || [];
  el.innerHTML = items.map((item, i) => journeyItemHtml(item, i)).join('');
  el.querySelectorAll('.remove-journey-item').forEach(btn => {
    btn.onclick = (e) => {
      const idx = parseInt(e.target.closest('.slide-editor').dataset.index, 10);
      content.journey.items.splice(idx, 1);
      renderJourney();
    };
  });
}

function valueItemHtml(item, i) {
  return `<div class="slide-editor" data-index="${i}">
    <span class="repeater-index">Value ${i + 1}</span>
    <div class="form-field"><label>Icon (ion-icon name)</label><input type="text" class="value-icon" value="${esc(item.icon)}"></div>
    <div class="form-field"><label>Title</label><input type="text" class="value-title" value="${esc(item.title)}"></div>
    <div class="form-field"><label>Description</label><textarea class="value-text" rows="2">${esc(item.text)}</textarea></div>
    <button type="button" class="btn-outline remove-value-item">Remove</button>
  </div>`;
}

function renderValues() {
  const v = content.values || {};
  document.getElementById('valuesMetaForm').innerHTML = [
    imageUploadHtml({ id: 'valuesSide', label: 'Side image', group: 'values', pathKey: 'values.image', currentPath: v.image || '', hint: 'Large image shown beside the value cards' }),
  ].join('');
  bindImageUploads(document.getElementById('valuesMetaForm'));
  const el = document.getElementById('valuesItemsEditor');
  const items = v.items || [];
  el.innerHTML = items.map((item, i) => valueItemHtml(item, i)).join('');
  el.querySelectorAll('.remove-value-item').forEach(btn => {
    btn.onclick = (e) => {
      const idx = parseInt(e.target.closest('.slide-editor').dataset.index, 10);
      content.values.items.splice(idx, 1);
      renderValues();
    };
  });
}

function teamMemberHtml(member, i) {
  const uploadId = 'teamMember' + i;
  const path = member.image || '';
  const previewSrc = previewHref(path);
  const hasImage = !!path;
  const social = member.social || {};
  const socialField = (key, label) =>
    `<div class="form-field"><label>${label}</label><input type="url" class="team-social-${key}" value="${esc(social[key] || '')}" placeholder="https://"></div>`;
  return `<div class="slide-editor" data-index="${i}">
    <span class="repeater-index">Member ${i + 1}</span>
    <div class="form-row-2">
      <div class="form-field"><label>Name</label><input type="text" class="team-name" value="${esc(member.name)}"></div>
      <div class="form-field"><label>Role</label><input type="text" class="team-role" value="${esc(member.role)}"></div>
    </div>
    <div class="image-upload" data-upload-id="${uploadId}">
      <span class="image-upload__label">Photo</span>
      <div class="image-upload__card">
        <img class="image-upload__preview${hasImage ? ' visible' : ''}" id="${uploadId}Preview" alt="" ${hasImage ? `src="${esc(previewSrc)}"` : ''}>
        <p class="image-upload__meta" id="${uploadId}Meta">${hasImage ? esc(path) : 'No photo uploaded yet'}</p>
        <input type="file" class="image-upload__file" id="${uploadId}File" accept="image/jpeg,image/png,image/webp">
        <input type="hidden" class="team-image-path" id="${uploadId}Path" value="${esc(path)}">
        <div class="image-upload__actions">
          <button type="button" class="btn-outline" data-pick="${uploadId}">Choose photo</button>
          <button type="button" class="btn-gold" data-upload="${uploadId}" data-group="team">Upload</button>
          <button type="button" class="btn-outline" data-clear="${uploadId}">Remove</button>
        </div>
      </div>
    </div>
    <h4 class="panel-subtitle team-member-social-heading">Social handles</h4>
    <p class="hint">Facebook, Instagram &amp; X profile URLs. All three icons appear on the card; empty fields show as inactive.</p>
    <div class="form-row-2">
      ${socialField('facebook', 'Facebook')}
      ${socialField('instagram', 'Instagram')}
    </div>
    ${socialField('twitter', 'X (Twitter)')}
    <button type="button" class="btn-outline remove-team-member">Remove member</button>
  </div>`;
}

function renderTeam() {
  const t = content.team || {};
  document.getElementById('teamMetaForm').innerHTML = [
    field('Eyebrow', 'team.eyebrow', t.eyebrow),
    field('Section title', 'team.title', t.title),
  ].join('');
  const el = document.getElementById('teamMembersEditor');
  const members = t.members || [];
  el.innerHTML = members.map((m, i) => teamMemberHtml(m, i)).join('');
  bindImageUploads(el);
  el.querySelectorAll('.remove-team-member').forEach(btn => {
    btn.onclick = (e) => {
      const idx = parseInt(e.target.closest('.slide-editor').dataset.index, 10);
      content.team.members.splice(idx, 1);
      renderTeam();
    };
  });
}

function renderCta() {
  const c = content.cta || {};
  document.getElementById('ctaForm').innerHTML = [
    field('Heading', 'cta.title', c.title),
    field('Supporting text', 'cta.text', c.text, 2),
    field('Button label', 'cta.label', c.label),
    field('Link page slug', 'cta.link', c.link, 1, 'e.g. contact — no .php extension'),
  ].join('');
}

function renderAll() {
  renderHero();
  renderStory();
  renderJourney();
  renderValues();
  renderTeam();
  renderCta();
}

function mergeKeyedForms(...forms) {
  const merged = {};
  forms.forEach(formId => {
    const data = collectKeyedForm(formId);
    Object.keys(data).forEach(section => {
      merged[section] = { ...(merged[section] || {}), ...data[section] };
    });
  });
  return merged;
}

function collectJourneyItems() {
  return [...document.querySelectorAll('#journeyItemsEditor .slide-editor')].map(el => ({
    year: el.querySelector('.journey-year').value.trim(),
    title: el.querySelector('.journey-title').value.trim(),
    text: el.querySelector('.journey-text').value.trim(),
  })).filter(i => i.year || i.title || i.text);
}

function collectValueItems() {
  return [...document.querySelectorAll('#valuesItemsEditor .slide-editor')].map(el => ({
    icon: el.querySelector('.value-icon').value.trim() || 'star-outline',
    title: el.querySelector('.value-title').value.trim(),
    text: el.querySelector('.value-text').value.trim(),
  })).filter(i => i.title || i.text);
}

function collectTeamMembers() {
  const socialKeys = ['facebook', 'instagram', 'twitter'];
  return [...document.querySelectorAll('#teamMembersEditor .slide-editor')].map(el => {
    const social = {};
    socialKeys.forEach(key => {
      social[key] = el.querySelector('.team-social-' + key)?.value.trim() || '';
    });
    return {
      name: el.querySelector('.team-name').value.trim(),
      role: el.querySelector('.team-role').value.trim(),
      image: el.querySelector('.team-image-path')?.value.trim() || '',
      social,
    };
  }).filter(m => m.name || m.role);
}

async function saveAbout(partial) {
  const payload = {
    page: 'about',
    content: {
      hero: partial.hero ?? content.hero,
      narrative: partial.narrative ?? content.narrative,
      journey: partial.journey ?? content.journey,
      values: partial.values ?? content.values,
      team: partial.team ?? content.team,
      cta: partial.cta ?? content.cta,
    }
  };
  const res = await fetch('api/pages.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'same-origin',
    body: JSON.stringify(payload)
  });
  const data = await res.json();
  if (!data.success) throw new Error(data.message || 'Save failed');
  content = data.content || payload.content;
  renderAll();
  showToast('About page saved');
}

document.querySelectorAll('.content-tab').forEach(btn => {
  btn.onclick = () => {
    document.querySelectorAll('.content-tab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.content-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + btn.dataset.tab).classList.add('active');
  };
});

document.getElementById('addHeroStatBtn').onclick = () => {
  if (!content.hero) content.hero = {};
  if (!content.hero.stats) content.hero.stats = [];
  content.hero.stats.push({ num: '', label: '' });
  renderHeroStats();
};

document.getElementById('saveHeroBtn').onclick = () => {
  const heroData = mergeKeyedForms('heroForm').hero || {};
  heroData.stats = collectHeroStats();
  saveAbout({ hero: { ...content.hero, ...heroData } }).catch(e => showToast(e.message, true));
};

document.getElementById('saveStoryBtn').onclick = () => {
  const narrative = mergeKeyedForms('storyForm').narrative || {};
  saveAbout({ narrative: { ...content.narrative, ...narrative } }).catch(e => showToast(e.message, true));
};

document.getElementById('addJourneyItemBtn').onclick = () => {
  if (!content.journey) content.journey = { items: [] };
  if (!content.journey.items) content.journey.items = [];
  content.journey.items.push({ year: '', title: '', text: '' });
  renderJourney();
};

document.getElementById('saveJourneyBtn').onclick = () => {
  const journeyMeta = mergeKeyedForms('journeyMetaForm').journey || {};
  saveAbout({
    journey: { ...content.journey, ...journeyMeta, items: collectJourneyItems() }
  }).catch(e => showToast(e.message, true));
};

document.getElementById('addValueItemBtn').onclick = () => {
  if (!content.values) content.values = { items: [] };
  if (!content.values.items) content.values.items = [];
  content.values.items.push({ icon: 'star-outline', title: '', text: '' });
  renderValues();
};

document.getElementById('saveValuesBtn').onclick = () => {
  const valuesMeta = mergeKeyedForms('valuesMetaForm').values || {};
  saveAbout({
    values: { ...content.values, ...valuesMeta, items: collectValueItems() }
  }).catch(e => showToast(e.message, true));
};

document.getElementById('addTeamMemberBtn').onclick = () => {
  if (!content.team) content.team = { members: [] };
  if (!content.team.members) content.team.members = [];
  content.team.members.push({ name: '', role: '', image: '', social: { facebook: '', instagram: '', twitter: '' } });
  renderTeam();
};

document.getElementById('saveTeamBtn').onclick = () => {
  const teamMeta = mergeKeyedForms('teamMetaForm').team || {};
  saveAbout({
    team: { ...content.team, ...teamMeta, members: collectTeamMembers() }
  }).catch(e => showToast(e.message, true));
};

document.getElementById('saveCtaBtn').onclick = () => {
  const cta = mergeKeyedForms('ctaForm').cta || {};
  saveAbout({ cta: { ...content.cta, ...cta } }).catch(e => showToast(e.message, true));
};

fetch(API, { credentials: 'same-origin' })
  .then(r => r.json())
  .then(data => {
    if (!data.success) throw new Error(data.message);
    content = data.content || {};
    renderAll();
  })
  .catch(e => showToast(e.message, true));
</script>
</body>
</html>
