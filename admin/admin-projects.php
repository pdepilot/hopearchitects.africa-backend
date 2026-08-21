<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>Hope Architects | Projects Manager</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <?php require dirname(__DIR__) . '/includes/admin_assets.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin-projects.css">
</head>
<body>
<div class="bg-aura"></div>
<?php $activeNav = 'properties'; ?>
<div class="app dashboard">
  <?php require dirname(__DIR__) . '/includes/admin_sidebar.php'; ?>

  <div class="main">
    <div class="top-bar">
      <div class="admin-header-actions--lg">
        <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
        <h1 class="page-title"><i class="fas fa-diagram-project admin-page-title-icon"></i> Projects Manager</h1>
      </div>
      <div class="action-group">
        <div class="search-bar">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" placeholder="Search by title or location...">
        </div>
        <button class="btn-primary" id="addProjectBtn"><i class="fas fa-plus-circle"></i> Add New Project</button>
      </div>
    </div>

    <div id="projectsContainer" class="projects-grid">
      <div class="loader"><i class="fas fa-spinner fa-pulse"></i> Loading projects...</div>
    </div>
    <div id="projectsPagination" class="admin-pagination"></div>
  </div>
</div>

<div id="projectModal" class="modal-overlay" aria-hidden="true">
  <div class="modal-container" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-header">
      <h3 id="modalTitle">Add / Edit Project</h3>
      <button type="button" class="modal-close" id="closeModalBtn" aria-label="Close dialog">&times;</button>
    </div>
    <form id="projectForm" class="modal-form" enctype="multipart/form-data">
      <input type="hidden" id="projectId">
      <div class="modal-body">
        <input type="text" id="title" placeholder="Project Title" required>
        <select id="type">
          <option value="sale">Architecture</option>
          <option value="rent">Interior Design</option>
        </select>
        <input type="text" id="location" placeholder="Location (e.g., Lekki, Lagos)" required>
        <div class="admin-project-grid-form">
          <input type="number" id="bedrooms" placeholder="Spaces" min="0" value="2">
          <input type="number" id="bathrooms" placeholder="Zones" min="0" value="2">
          <input type="number" id="area" placeholder="Area (sq ft)" min="0" value="0">
        </div>
        <div class="project-upload-field">
          <label for="projectImage">Project image</label>
          <input type="file" id="projectImage" accept="image/*">
          <div class="project-upload-meta" id="projectImageMeta">Choose an image to upload.</div>
          <div class="project-upload-preview" id="projectImagePreview" hidden>
            <img id="projectImagePreviewImg" src="" alt="Project preview">
            <button type="button" class="btn-outline btn-outline--small" id="removeProjectImageBtn">Remove selected image</button>
          </div>
        </div>
        <textarea id="description" placeholder="Project description..."></textarea>
        <select id="approvalStatus">
          <option value="approved">Approved (visible on website)</option>
          <option value="pending">Pending (hidden from website)</option>
          <option value="rejected">Rejected (hidden from website)</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline" id="cancelModalBtn">Cancel</button>
        <button type="submit" class="btn-primary">Save Project</button>
      </div>
    </form>
  </div>
</div>

<script>
  const API_URL = 'api/properties.php';
  const ITEMS_PER_PAGE = 6;
  let projects = [];
  let currentPage = 1;
  let selectedImageFile = null;
  let existingImageUrl = '';

  function buildApiUrl(path, method = 'GET') {
    const match = path.match(/^\/properties(?:\/([^/?]+))?(?:\?(.*))?$/);
    if (!match) return API_URL;

    const id = match[1];
    const query = match[2];
    if (id) return `${API_URL}?id=${encodeURIComponent(id)}`;
    if (query) return `${API_URL}?${query}`;
    return method === 'GET' ? `${API_URL}?limit=200` : API_URL;
  }

  async function apiFetch(path, options = {}) {
    const method = (options.method || 'GET').toUpperCase();
    const res = await fetch(buildApiUrl(path, method), {
      method,
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', ...options.headers },
      body: options.body
    });
    const data = await res.json().catch(() => ({}));
    if (res.status === 401) throw new Error('Unauthorized');
    if (!res.ok || data.success === false) {
      throw new Error(data.message || `HTTP ${res.status}`);
    }
    return data;
  }

  async function loadProjects() {
    try {
      const data = await apiFetch('/properties?limit=200');
      projects = data.properties || [];
      currentPage = 1;
      renderProjects();
    } catch (err) {
      console.error(err);
      showToast(err.message || 'Could not load projects', true);
    }
  }

  function renderProjects() {
    const container = document.getElementById('projectsContainer');
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const filtered = projects.filter(p =>
      p.title?.toLowerCase().includes(searchTerm) ||
      p.location?.toLowerCase().includes(searchTerm)
    );
    const pagination = document.getElementById('projectsPagination');

    if (filtered.length === 0) {
      if (pagination) pagination.innerHTML = '';
      container.innerHTML = '<div class="admin-empty-wide">No projects found. Add one now.</div>';
      return;
    }

    const totalPages = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const paged = filtered.slice(start, start + ITEMS_PER_PAGE);

    container.innerHTML = paged.map(project => {
      const status = project.approvalStatus || 'pending';
      const statusClass = status === 'approved' ? 'approved' : (status === 'rejected' ? 'rejected' : 'pending');
      return `
      <div class="project-card" data-id="${project._id}">
        <div class="card-img admin-card-img" style="--card-img:url('${escapeHtml(project.imageUrl || './assets/images/Scene 10_1.png')}')">
          <span class="category-tag">${project.type === 'rent' ? 'Interior Design' : 'Architecture'}</span>
          <span class="status-badge status-${statusClass}">${status}</span>
        </div>
        <div class="card-content">
          <div class="card-title">${escapeHtml(project.title)}</div>
          <div class="location"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(project.location)}</div>
          <div class="meta-row">
            <span>${project.bedrooms ?? 2} spaces</span>
            <span>${project.bathrooms ?? 2} zones</span>
            ${project.area ? `<span>${project.area} sq ft</span>` : ''}
          </div>
          <div class="admin-project-meta">${project.type === 'sale' ? 'Architecture' : 'Interior Design'}</div>
          <div class="card-actions">
            <button class="icon-btn edit" data-id="${project._id}"><i class="fas fa-edit"></i> Edit</button>
            <button class="icon-btn delete" data-id="${project._id}"><i class="fas fa-trash-alt"></i> Delete</button>
          </div>
        </div>
      </div>`;
    }).join('');

    container.querySelectorAll('.edit').forEach(btn => btn.addEventListener('click', (e) => {
      e.stopPropagation();
      openEditModal(btn.getAttribute('data-id'));
    }));
    container.querySelectorAll('.delete').forEach(btn => btn.addEventListener('click', async (e) => {
      e.stopPropagation();
      const id = btn.getAttribute('data-id');
      if (confirm('Permanently delete this project?')) {
        await deleteProject(id);
      }
    }));
    renderPagination(totalPages, filtered.length);
  }

  function renderPagination(totalPages, totalItems) {
    const pagination = document.getElementById('projectsPagination');
    if (!pagination) return;
    if (totalPages <= 1) {
      pagination.innerHTML = `<span class="admin-pagination__summary">${totalItems} projects</span>`;
      return;
    }
    let html = `<span class="admin-pagination__summary">${totalItems} projects</span>`;
    html += '<div class="admin-pagination__controls">';
    html += `<button type="button" class="admin-pagination__btn" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>`;
    for (let page = 1; page <= totalPages; page++) {
      html += `<button type="button" class="admin-pagination__btn ${page === currentPage ? 'is-active' : ''}" data-page="${page}">${page}</button>`;
    }
    html += `<button type="button" class="admin-pagination__btn" data-page="${currentPage + 1}" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>`;
    html += '</div>';
    pagination.innerHTML = html;
    pagination.querySelectorAll('[data-page]').forEach(btn => {
      btn.addEventListener('click', () => {
        const page = Number(btn.getAttribute('data-page'));
        if (!page || page === currentPage) return;
        currentPage = page;
        renderProjects();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    });
  }

  function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str).replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
  }

  async function deleteProject(id) {
    try {
      await apiFetch(`/properties/${id}`, { method: 'DELETE' });
      showToast('Project deleted successfully');
      loadProjects();
    } catch (err) {
      showToast('Deletion failed', true);
    }
  }

  function openEditModal(id = null) {
    const modal = document.getElementById('projectModal');
    const form = document.getElementById('projectForm');
    form.reset();
    resetProjectImageField();
    document.getElementById('projectId').value = '';
    document.getElementById('bedrooms').value = 2;
    document.getElementById('bathrooms').value = 2;
    document.getElementById('area').value = 0;
    document.getElementById('approvalStatus').value = 'approved';
    document.getElementById('modalTitle').innerText = id ? 'Edit Project' : 'Add New Project';

    if (id) {
      const project = projects.find(p => p._id === id);
      if (project) {
        document.getElementById('projectId').value = project._id;
        document.getElementById('title').value = project.title || '';
        document.getElementById('type').value = project.type || 'sale';
        document.getElementById('location').value = project.location || '';
        document.getElementById('bedrooms').value = project.bedrooms ?? 2;
        document.getElementById('bathrooms').value = project.bathrooms ?? 2;
        document.getElementById('area').value = project.area ?? 0;
        document.getElementById('description').value = project.description || '';
        document.getElementById('approvalStatus').value = project.approvalStatus || 'approved';
        existingImageUrl = project.imageUrl || '';
        showExistingImagePreview(existingImageUrl);
      }
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
    modal.querySelector('.modal-body')?.scrollTo(0, 0);
  }

  function closeModal() {
    const modal = document.getElementById('projectModal');
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
  }

  function showExistingImagePreview(url) {
    if (!url) return;
    const preview = document.getElementById('projectImagePreview');
    const img = document.getElementById('projectImagePreviewImg');
    const meta = document.getElementById('projectImageMeta');
    if (preview && img && meta) {
      img.src = url;
      preview.hidden = false;
      meta.textContent = 'Current project image.';
    }
  }

  function resetProjectImageField() {
    selectedImageFile = null;
    existingImageUrl = '';
    const input = document.getElementById('projectImage');
    const preview = document.getElementById('projectImagePreview');
    const img = document.getElementById('projectImagePreviewImg');
    const meta = document.getElementById('projectImageMeta');
    if (input) input.value = '';
    if (img) img.src = '';
    if (preview) preview.hidden = true;
    if (meta) meta.textContent = 'Choose an image to upload.';
  }

  function handleProjectImageChange(event) {
    const file = event.target.files?.[0] || null;
    const preview = document.getElementById('projectImagePreview');
    const img = document.getElementById('projectImagePreviewImg');
    const meta = document.getElementById('projectImageMeta');
    selectedImageFile = file;
    if (!file) {
      if (existingImageUrl) showExistingImagePreview(existingImageUrl);
      return;
    }
    if (img) img.src = URL.createObjectURL(file);
    if (preview) preview.hidden = false;
    if (meta) meta.textContent = `Selected: ${file.name}`;
  }

  async function saveProject(event) {
    event.preventDefault();
    const id = document.getElementById('projectId').value;
    if (!id && !selectedImageFile) {
      showToast('Please upload a project image', true);
      return;
    }

    const formData = new FormData();
    formData.append('action', id ? 'update' : 'create');
    if (id) formData.append('id', id);
    formData.append('title', document.getElementById('title').value);
    formData.append('price', '0');
    formData.append('type', document.getElementById('type').value);
    formData.append('location', document.getElementById('location').value);
    formData.append('bedrooms', String(Number(document.getElementById('bedrooms').value)));
    formData.append('bathrooms', String(Number(document.getElementById('bathrooms').value)));
    formData.append('area', String(Number(document.getElementById('area').value)));
    formData.append('description', document.getElementById('description').value);
    formData.append('approvalStatus', document.getElementById('approvalStatus').value);
    if (selectedImageFile) {
      formData.append('propertyImages[]', selectedImageFile);
    }

    try {
      const res = await fetch(API_URL, { method: 'POST', credentials: 'same-origin', body: formData });
      const data = await res.json().catch(() => ({}));
      if (!res.ok || data.success === false) throw new Error(data.message || 'Save failed');
      showToast(id ? 'Project updated' : 'Project created');
      closeModal();
      loadProjects();
    } catch (err) {
      showToast(err.message || 'Error saving project', true);
    }
  }

  function showToast(msg, isError = false) {
    let toast = document.querySelector('.toast');
    if (toast) toast.remove();
    toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<i class="fas ${isError ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i> ${msg}`;
    toast.style.borderLeftColor = isError ? '#e74c3c' : '#feee90';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  document.getElementById('logoutBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    window.location.href = 'logout.php';
  });

  document.getElementById('searchInput')?.addEventListener('input', () => {
    currentPage = 1;
    renderProjects();
  });

  document.getElementById('addProjectBtn')?.addEventListener('click', () => openEditModal());
  document.getElementById('projectImage')?.addEventListener('change', handleProjectImageChange);
  document.getElementById('removeProjectImageBtn')?.addEventListener('click', resetProjectImageField);
  document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
  document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
  document.getElementById('projectModal')?.addEventListener('click', (e) => {
    if (e.target === document.getElementById('projectModal')) closeModal();
  });
  document.getElementById('projectForm')?.addEventListener('submit', saveProject);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && document.getElementById('projectModal')?.classList.contains('is-open')) {
      closeModal();
    }
  });

  loadProjects();
</script>
</body>
</html>
