<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_guard.php';
require_once dirname(__DIR__) . '/includes/AdminPermissions.php';
AdminPermissions::require(AdminPermissions::PERM_CONTENT);

$activeNav = 'pages';
$pageTitle = 'Services Page | Hope Architects Admin';
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
      <h1 class="page-title">Services Page</h1>
      <span class="admin-badge">Signed in as <?= $adminName ?></span>
    </header>
    <div class="admin-content-pad">
      <p class="hint">Edit the public <a href="../services.php" target="_blank" rel="noopener">Services</a> page. About page content is managed under <a href="admin-about-us.php">About Page</a>.</p>
      <div class="panel">
        <form id="pageForm" class="content-form-grid"></form>
        <button type="button" class="btn-gold" id="savePageBtn">Save Page Content</button>
      </div>
    </div>
  </div>
</div>

<script>
const API = 'api/pages.php';
const currentPage = 'services';
let pageContent = {};

function showToast(msg, err = false) {
  document.querySelector('.toast')?.remove();
  const t = document.createElement('div');
  t.className = 'toast' + (err ? ' error' : '');
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

function esc(s) { return String(s ?? '').replace(/"/g, '&quot;').replace(/</g, '&lt;'); }

function field(label, path, value, rows = 1) {
  const id = path.replace(/\./g, '_');
  if (rows > 1) {
    return `<div class="form-field"><label for="${id}">${label}</label><textarea id="${id}" data-path="${path}" rows="${rows}">${esc(value)}</textarea></div>`;
  }
  return `<div class="form-field"><label for="${id}">${label}</label><input type="text" id="${id}" data-path="${path}" value="${esc(value)}"></div>`;
}

function renderForm() {
  const c = pageContent;
  const form = document.getElementById('pageForm');
  const h = c.hero || {};
  const sh = c.showcase || {};
  const cta = c.cta || {};
  form.innerHTML = `
    <h3>Hero</h3>
    ${field('Badge', 'hero.badge', h.badge)}
    ${field('Title (HTML ok)', 'hero.title', h.title)}
    ${field('Description', 'hero.description', h.description, 3)}
    <h3>Showcase section</h3>
    ${field('Eyebrow', 'showcase.eyebrow', sh.eyebrow)}
    ${field('Title', 'showcase.title', sh.title)}
    <h3>CTA</h3>
    ${field('Title', 'cta.title', cta.title)}
    ${field('Text', 'cta.text', cta.text, 2)}
    ${field('Button label', 'cta.label', cta.label)}
    <h3>Service cards</h3>
    <p class="hint">Edit service cards in the JSON below (icon, title, description, features array, linkLabel, linkPage).</p>
    <textarea id="cardsJson" rows="12" style="font-family:monospace;font-size:12px;">${esc(JSON.stringify(c.cards || [], null, 2))}</textarea>
  `;
}

function setPath(obj, path, value) {
  const parts = path.split('.');
  let cur = obj;
  for (let i = 0; i < parts.length - 1; i++) {
    if (!cur[parts[i]]) cur[parts[i]] = {};
    cur = cur[parts[i]];
  }
  cur[parts[parts.length - 1]] = value;
}

function collectForm() {
  const out = JSON.parse(JSON.stringify(pageContent));
  document.querySelectorAll('#pageForm [data-path]').forEach(el => {
    setPath(out, el.dataset.path, el.value);
  });
  try { out.cards = JSON.parse(document.getElementById('cardsJson').value); }
  catch { throw new Error('Invalid service cards JSON'); }
  return out;
}

async function loadPage() {
  const res = await fetch(`${API}?page=${currentPage}`, { credentials: 'same-origin' });
  const data = await res.json();
  if (!data.success) throw new Error(data.message);
  pageContent = data.content || {};
  renderForm();
}

document.getElementById('savePageBtn').onclick = async () => {
  try {
    const content = collectForm();
    const res = await fetch(API, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify({ page: currentPage, content })
    });
    const data = await res.json();
    if (!data.success) throw new Error(data.message);
    pageContent = data.content;
    showToast('Page saved');
  } catch (e) { showToast(e.message, true); }
};

loadPage().catch(e => showToast(e.message, true));
</script>
</body>
</html>
