<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>Hope Architects | Gallery Manager</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <?php require dirname(__DIR__) . '/includes/admin_assets.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin-projects.css">
  <link rel="stylesheet" href="../assets/css/admin-gallery.css">
</head>
<body>
<?php $activeNav = 'gallery'; ?>
<div class="app dashboard">
  <?php require dirname(__DIR__) . '/includes/admin_sidebar.php'; ?>

  <div class="main">
    <div class="top-bar">
      <div class="admin-header-actions--lg">
        <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
        <h1 class="page-title"><i class="fas fa-images admin-page-title-icon"></i> Gallery Manager</h1>
      </div>
      <div class="action-group">
        <div class="search-bar">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" placeholder="Search title or category...">
        </div>
        <button class="btn-primary" id="addPhotoBtn"><i class="fas fa-plus-circle"></i> Add Photo</button>
      </div>
    </div>

    <div class="gallery-stats" id="galleryStats">
      <div class="gallery-stat"><span>Total</span><strong id="statTotal">0</strong></div>
      <div class="gallery-stat"><span>Published</span><strong id="statPublished">0</strong></div>
      <div class="gallery-stat"><span>Max size</span><strong>5MB</strong></div>
    </div>

    <div id="galleryContainer" class="gallery-admin-grid">
      <div class="loader"><i class="fas fa-spinner fa-pulse"></i> Loading gallery...</div>
    </div>
  </div>
</div>

<div id="galleryModal" class="modal-overlay" aria-hidden="true">
  <div class="modal-container" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-header">
      <h3 id="modalTitle">Add Gallery Photo</h3>
      <button type="button" class="modal-close" id="closeModalBtn" aria-label="Close">&times;</button>
    </div>
    <form id="galleryForm" class="modal-form" enctype="multipart/form-data">
      <input type="hidden" id="galleryId">
      <div class="modal-body">
        <p class="gallery-upload-hint">Photos only · JPG / PNG / WEBP / GIF · max <strong>5MB</strong> per photo.</p>
        <div class="gallery-upload-field">
          <label for="galleryFile">Photo file</label>
          <input type="file" id="galleryFile" accept="image/jpeg,image/png,image/webp,image/gif">
          <div class="gallery-upload-meta" id="galleryFileMeta">Choose a photo to upload.</div>
          <div class="gallery-upload-preview" id="galleryPreview" hidden>
            <img id="galleryPreviewImg" src="" alt="Preview">
          </div>
        </div>
        <input type="text" id="galleryTitle" placeholder="Title (optional)">
        <input type="text" id="galleryCategory" placeholder="Category (e.g. Interiors, Exterior, Construction)" list="categoryList">
        <datalist id="categoryList"></datalist>
        <textarea id="galleryCaption" rows="3" placeholder="Caption (optional)"></textarea>
        <input type="number" id="gallerySort" placeholder="Sort order" value="0" min="0">
        <label class="checkbox-inline"><input type="checkbox" id="galleryPublished" checked> Published on Gallery page</label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline" id="cancelModalBtn">Cancel</button>
        <button type="submit" class="btn-primary">Save Photo</button>
      </div>
    </form>
  </div>
</div>

<script>
  const API = 'api/gallery.php';
  const MAX_BYTES = 5 * 1024 * 1024;
  let items = [];
  let categories = [];
  let selectedFile = null;

  function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (m) => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[m]));
  }

  function formatBytes(bytes) {
    if (!bytes) return '0 B';
    const units = ['B', 'KB', 'MB'];
    const i = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
    return `${(bytes / Math.pow(1024, i)).toFixed(i === 0 ? 0 : 1)} ${units[i]}`;
  }

  function showToast(msg, isError = false) {
    let toast = document.querySelector('.toast');
    if (toast) toast.remove();
    toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<i class="fas ${isError ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i> ${escapeHtml(msg)}`;
    toast.style.borderLeftColor = isError ? '#e74c3c' : '#feee90';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3200);
  }

  async function loadGallery() {
    try {
      const res = await fetch(API, { credentials: 'same-origin' });
      const data = await res.json();
      if (!data.success) throw new Error(data.message || 'Failed to load');
      items = data.items || [];
      categories = data.categories || [];
      document.getElementById('statTotal').textContent = data.stats?.total ?? items.length;
      document.getElementById('statPublished').textContent = data.stats?.published ?? 0;
      const list = document.getElementById('categoryList');
      list.innerHTML = categories.map((c) => `<option value="${escapeHtml(c)}"></option>`).join('');
      renderGallery();
    } catch (err) {
      document.getElementById('galleryContainer').innerHTML =
        '<div class="admin-empty-wide">Could not load gallery. Check database connection.</div>';
      showToast(err.message || 'Load failed', true);
    }
  }

  function renderGallery() {
    const container = document.getElementById('galleryContainer');
    const q = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
    const filtered = items.filter((item) => {
      const hay = `${item.title || ''} ${item.category || ''} ${item.caption || ''}`.toLowerCase();
      return !q || hay.includes(q);
    });

    if (!filtered.length) {
      container.innerHTML = '<div class="admin-empty-wide"><i class="fas fa-images"></i> No gallery photos yet. Click Add Photo (max 5MB each).</div>';
      return;
    }

    container.innerHTML = filtered.map((item) => `
      <article class="gallery-admin-card ${item.isPublished ? '' : 'is-hidden'}">
        <div class="gallery-admin-card__media">
          <img src="${escapeHtml(item.imageUrl)}" alt="${escapeHtml(item.title || 'Gallery photo')}" loading="lazy">
          <span class="gallery-admin-badge">${item.isPublished ? 'Published' : 'Hidden'}</span>
        </div>
        <div class="gallery-admin-card__body">
          <h3>${escapeHtml(item.title || 'Untitled photo')}</h3>
          <p>${escapeHtml(item.category || 'Uncategorised')}</p>
          <div class="gallery-admin-card__actions">
            <button type="button" class="btn-outline btn-outline--small" data-edit="${item.id}">Edit</button>
            <button type="button" class="btn-outline btn-outline--small" data-toggle="${item.id}">${item.isPublished ? 'Hide' : 'Publish'}</button>
            <button type="button" class="btn-outline btn-outline--small danger" data-delete="${item.id}">Delete</button>
          </div>
        </div>
      </article>
    `).join('');

    container.querySelectorAll('[data-edit]').forEach((btn) => {
      btn.addEventListener('click', () => openModal(Number(btn.dataset.edit)));
    });
    container.querySelectorAll('[data-toggle]').forEach((btn) => {
      btn.addEventListener('click', () => toggleItem(Number(btn.dataset.toggle)));
    });
    container.querySelectorAll('[data-delete]').forEach((btn) => {
      btn.addEventListener('click', () => deleteItem(Number(btn.dataset.delete)));
    });
  }

  function openModal(id = null) {
    const modal = document.getElementById('galleryModal');
    const form = document.getElementById('galleryForm');
    form.reset();
    selectedFile = null;
    document.getElementById('galleryId').value = '';
    document.getElementById('galleryPublished').checked = true;
    document.getElementById('gallerySort').value = '0';
    document.getElementById('galleryFileMeta').textContent = 'Choose a photo to upload.';
    document.getElementById('galleryPreview').hidden = true;
    document.getElementById('galleryFile').required = !id;
    document.getElementById('modalTitle').textContent = id ? 'Edit Gallery Photo' : 'Add Gallery Photo';

    if (id) {
      const item = items.find((row) => Number(row.id) === id);
      if (item) {
        document.getElementById('galleryId').value = item.id;
        document.getElementById('galleryTitle').value = item.title || '';
        document.getElementById('galleryCategory').value = item.category || '';
        document.getElementById('galleryCaption').value = item.caption || '';
        document.getElementById('gallerySort').value = item.sortOrder ?? 0;
        document.getElementById('galleryPublished').checked = !!item.isPublished;
        document.getElementById('galleryPreviewImg').src = item.imageUrl;
        document.getElementById('galleryPreview').hidden = false;
        document.getElementById('galleryFileMeta').textContent = 'Current photo kept unless you choose a new file.';
      }
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
  }

  function closeModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
  }

  function handleFileChange(event) {
    const file = event.target.files?.[0] || null;
    selectedFile = null;
    if (!file) return;
    if (!file.type.startsWith('image/')) {
      showToast('Please choose an image file.', true);
      event.target.value = '';
      return;
    }
    if (file.size > MAX_BYTES) {
      showToast(`"${file.name}" is ${formatBytes(file.size)}. Max 5MB per photo.`, true);
      event.target.value = '';
      return;
    }
    selectedFile = file;
    document.getElementById('galleryFileMeta').textContent = `Selected: ${file.name} (${formatBytes(file.size)})`;
    document.getElementById('galleryPreviewImg').src = URL.createObjectURL(file);
    document.getElementById('galleryPreview').hidden = false;
  }

  async function saveGallery(event) {
    event.preventDefault();
    const id = document.getElementById('galleryId').value;

    try {
      if (!id) {
        if (!selectedFile) {
          showToast('Please choose a photo (max 5MB).', true);
          return;
        }
        const formData = new FormData();
        formData.append('action', 'upload');
        formData.append('image', selectedFile);
        formData.append('title', document.getElementById('galleryTitle').value);
        formData.append('caption', document.getElementById('galleryCaption').value);
        formData.append('category', document.getElementById('galleryCategory').value);
        formData.append('sortOrder', document.getElementById('gallerySort').value || '0');
        formData.append('isPublished', document.getElementById('galleryPublished').checked ? '1' : '0');

        const res = await fetch(API, { method: 'POST', credentials: 'same-origin', body: formData });
        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || 'Upload failed');
        showToast('Photo uploaded');
      } else if (selectedFile) {
        // Replace image: upload new, then delete old via update path — create then delete old record simpler:
        // Upload as new image attached to update by deleting old after create... Instead: delete+recreate keeping meta.
        const oldId = Number(id);
        const formData = new FormData();
        formData.append('action', 'upload');
        formData.append('image', selectedFile);
        formData.append('title', document.getElementById('galleryTitle').value);
        formData.append('caption', document.getElementById('galleryCaption').value);
        formData.append('category', document.getElementById('galleryCategory').value);
        formData.append('sortOrder', document.getElementById('gallerySort').value || '0');
        formData.append('isPublished', document.getElementById('galleryPublished').checked ? '1' : '0');
        const res = await fetch(API, { method: 'POST', credentials: 'same-origin', body: formData });
        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || 'Upload failed');
        await fetch(API, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', id: oldId }),
        });
        showToast('Photo replaced');
      } else {
        const res = await fetch(API, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'save',
            id: Number(id),
            title: document.getElementById('galleryTitle').value,
            caption: document.getElementById('galleryCaption').value,
            category: document.getElementById('galleryCategory').value,
            sortOrder: Number(document.getElementById('gallerySort').value || 0),
            isPublished: document.getElementById('galleryPublished').checked,
          }),
        });
        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || 'Save failed');
        showToast('Photo updated');
      }
      closeModal();
      loadGallery();
    } catch (err) {
      showToast(err.message || 'Save failed', true);
    }
  }

  async function toggleItem(id) {
    try {
      const res = await fetch(API, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'toggle', id }),
      });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Toggle failed');
      loadGallery();
    } catch (err) {
      showToast(err.message || 'Could not update visibility', true);
    }
  }

  async function deleteItem(id) {
    if (!confirm('Delete this gallery photo?')) return;
    try {
      const res = await fetch(API, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id }),
      });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Delete failed');
      showToast('Photo deleted');
      loadGallery();
    } catch (err) {
      showToast(err.message || 'Delete failed', true);
    }
  }

  document.getElementById('addPhotoBtn')?.addEventListener('click', () => openModal());
  document.getElementById('galleryFile')?.addEventListener('change', handleFileChange);
  document.getElementById('galleryForm')?.addEventListener('submit', saveGallery);
  document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
  document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
  document.getElementById('galleryModal')?.addEventListener('click', (e) => {
    if (e.target === document.getElementById('galleryModal')) closeModal();
  });
  document.getElementById('searchInput')?.addEventListener('input', renderGallery);
  document.getElementById('logoutBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    window.location.href = 'logout.php';
  });

  loadGallery();
</script>
</body>
</html>
