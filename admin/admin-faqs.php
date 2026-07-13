<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_guard.php';
require_once dirname(__DIR__) . '/includes/AdminPermissions.php';
AdminPermissions::require(AdminPermissions::PERM_FAQS);

$activeNav = 'faqs';
$pageTitle = 'FAQs | Biver Royalty Homes Admin';
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
      <button type="button" class="menu-toggle" id="menuToggle" aria-label="Open menu"><ion-icon name="menu-outline"></ion-icon></button>
      <h1 class="page-title">FAQs</h1>
      <span class="admin-badge">Signed in as <?= $adminName ?></span>
    </header>
    <div class="admin-content-pad">
      <div class="panel">
        <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
          <div>
            <h2>Frequently Asked Questions</h2>
            <p class="hint">Shown on the Contact page and used by the AI chatbot. Higher priority appears first.</p>
            <p class="status-text" id="statusText">Loading...</p>
          </div>
          <button type="button" class="btn-gold" id="addFaqBtn"><ion-icon name="add-outline"></ion-icon> Add FAQ</button>
        </div>
        <div id="faqList" class="content-list"></div>
      </div>
    </div>
  </div>
</div>

<div id="faqModal" class="modal" aria-hidden="true">
  <div class="modal-card">
    <h3 id="modalTitle">Add FAQ</h3>
    <form id="faqForm" class="content-form-grid">
      <input type="hidden" id="faqId">
      <div class="form-field"><label for="question">Question</label><input type="text" id="question" required maxlength="500"></div>
      <div class="form-field"><label for="answer">Answer</label><textarea id="answer" required rows="4"></textarea></div>
      <div class="form-row-2">
        <div class="form-field"><label for="category">Category</label><input type="text" id="category" value="general" maxlength="80"></div>
        <div class="form-field"><label for="priority">Priority (0–100)</label><input type="number" id="priority" value="50" min="0" max="100"></div>
      </div>
      <div class="form-field"><label for="keywords">Keywords (comma-separated)</label><input type="text" id="keywords" placeholder="viewing, schedule, appointment"></div>
      <label class="checkbox-inline"><input type="checkbox" id="isActive" checked> Active / published</label>
      <div class="modal-actions">
        <button type="button" class="btn-outline" id="closeModalBtn">Cancel</button>
        <button type="submit" class="btn-gold">Save FAQ</button>
      </div>
    </form>
  </div>
</div>

<script>
const API = 'api/faqs.php';
let faqs = [];

function showToast(msg, err = false) {
  document.querySelector('.toast')?.remove();
  const t = document.createElement('div');
  t.className = 'toast' + (err ? ' error' : '');
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

function esc(s) {
  return String(s ?? '').replace(/[&<>"]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[m]));
}

async function apiPost(payload) {
  const res = await fetch(API, { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify(payload) });
  const data = await res.json();
  if (!data.success) throw new Error(data.message || 'Request failed');
  return data;
}

async function loadFaqs() {
  const res = await fetch(API, { credentials: 'same-origin' });
  const data = await res.json();
  if (!data.success) throw new Error(data.message || 'Failed to load');
  faqs = data.faqs || [];
  document.getElementById('statusText').textContent = `${faqs.length} total · ${faqs.filter(f => f.isActive).length} active`;
  renderFaqs();
}

function renderFaqs() {
  const el = document.getElementById('faqList');
  if (!faqs.length) {
    el.innerHTML = '<p class="hint">No FAQs yet. Add your first question.</p>';
    return;
  }
  el.innerHTML = faqs.map(f => `
    <article class="content-item">
      <h4>${esc(f.question)} ${f.isActive ? '' : '<span class="badge-inactive">Hidden</span>'}</h4>
      <p>${esc(f.answer)}</p>
      <small class="hint">Category: ${esc(f.category)} · Priority: ${f.priority}</small>
      <div class="content-item-actions">
        <button type="button" class="btn-outline" onclick="editFaq(${f.id})">Edit</button>
        <button type="button" class="btn-outline" onclick="deleteFaq(${f.id})">Delete</button>
      </div>
    </article>`).join('');
}

function openModal(faq = null) {
  document.getElementById('modalTitle').textContent = faq ? 'Edit FAQ' : 'Add FAQ';
  document.getElementById('faqId').value = faq?.id || '';
  document.getElementById('question').value = faq?.question || '';
  document.getElementById('answer').value = faq?.answer || '';
  document.getElementById('category').value = faq?.category || 'general';
  document.getElementById('priority').value = faq?.priority ?? 50;
  document.getElementById('keywords').value = (faq?.keywords || []).join(', ');
  document.getElementById('isActive').checked = faq ? !!faq.isActive : true;
  document.getElementById('faqModal').classList.add('open');
}

function closeModal() { document.getElementById('faqModal').classList.remove('open'); }

function editFaq(id) {
  const faq = faqs.find(f => f.id === id);
  if (faq) openModal(faq);
}

async function deleteFaq(id) {
  if (!confirm('Delete this FAQ?')) return;
  try {
    await apiPost({ action: 'delete', id });
    showToast('FAQ deleted');
    loadFaqs();
  } catch (e) { showToast(e.message, true); }
}

document.getElementById('addFaqBtn').onclick = () => openModal();
document.getElementById('closeModalBtn').onclick = closeModal;
document.getElementById('faqForm').onsubmit = async (e) => {
  e.preventDefault();
  try {
    const id = parseInt(document.getElementById('faqId').value, 10) || 0;
    await apiPost({
      id,
      question: document.getElementById('question').value,
      answer: document.getElementById('answer').value,
      category: document.getElementById('category').value,
      priority: parseInt(document.getElementById('priority').value, 10) || 50,
      keywords: document.getElementById('keywords').value,
      isActive: document.getElementById('isActive').checked
    });
    showToast('FAQ saved');
    closeModal();
    loadFaqs();
  } catch (err) { showToast(err.message, true); }
};

loadFaqs().catch(e => showToast(e.message, true));
</script>
</body>
</html>
