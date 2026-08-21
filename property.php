<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php require_once __DIR__ . '/includes/site_helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Browse completed and in-progress architecture and construction projects by Hope Architects in Lagos and across Nigeria. Explore our portfolio of landmark builds.">
  <meta name="keywords" content="architecture projects Nigeria, construction portfolio, Hope Architects projects, Lagos builds, building design">
  <meta name="author" content="Hope Architects">
  <title>Projects | Hope Architects — Architecture & Construction Portfolio</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/property.css">
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
</head>
<body>

<?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

  <main id="main-content">
    <section class="properties-hero">
      <div class="container">
        <h1>Explore Our <span class="gold-accent">Projects</span></h1>
        <p>Discover our portfolio of residential, commercial, and institutional builds across Lagos</p>
      </div>
    </section>

    <div class="filter-bar">
      <div class="container">
        <div class="filter-form">
          <div class="filter-group"><input type="text" id="propertyFilterInput" placeholder="Search by location or title..."></div>
          <div class="filter-group"><select id="typeFilter"><option value="">All Disciplines</option><option value="sale">Architecture</option><option value="rent">Interior Design</option></select></div>
          <div class="filter-group"><select id="sortFilter"><option value="newest">Newest First</option><option value="title">Title A–Z</option></select></div>
          <button class="search-btn" id="searchBtn"><ion-icon name="search-outline"></ion-icon>Search Projects</button>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="properties-toolbar">
        <div class="view-toggle">
          <button class="view-btn active" data-view="grid"><ion-icon name="grid-outline"></ion-icon> Grid</button>
          <button class="view-btn" data-view="list"><ion-icon name="list-outline"></ion-icon> List</button>
        </div>
        <div><span id="resultsCount">Loading projects...</span></div>
      </div>

      <div id="propertiesContainer">
        <div class="skeleton-grid" id="skeletonLoader">
          <div class="skeleton-card"><div class="skeleton-image"></div><div class="skeleton-content"><div class="skeleton-line"></div><div class="skeleton-line skeleton-line-short"></div><div class="skeleton-line"></div></div></div>
          <div class="skeleton-card"><div class="skeleton-image"></div><div class="skeleton-content"><div class="skeleton-line"></div><div class="skeleton-line skeleton-line-short"></div><div class="skeleton-line"></div></div></div>
          <div class="skeleton-card"><div class="skeleton-image"></div><div class="skeleton-content"><div class="skeleton-line"></div><div class="skeleton-line skeleton-line-short"></div><div class="skeleton-line"></div></div></div>
          <div class="skeleton-card"><div class="skeleton-image"></div><div class="skeleton-content"><div class="skeleton-line"></div><div class="skeleton-line skeleton-line-short"></div><div class="skeleton-line"></div></div></div>
          <div class="skeleton-card"><div class="skeleton-image"></div><div class="skeleton-content"><div class="skeleton-line"></div><div class="skeleton-line skeleton-line-short"></div><div class="skeleton-line"></div></div></div>
          <div class="skeleton-card"><div class="skeleton-image"></div><div class="skeleton-content"><div class="skeleton-line"></div><div class="skeleton-line skeleton-line-short"></div><div class="skeleton-line"></div></div></div>
        </div>
        <div id="propertiesGrid" class="properties-grid u-hidden"></div>
        <div id="errorState" class="error-state u-hidden">
          <ion-icon name="alert-circle-outline"></ion-icon>
          <h3>Unable to Load Projects</h3>
          <p>We're having trouble connecting to our project database. Please check your internet connection and try again.</p>
          <button class="retry-btn" onclick="location.reload()">Try Again</button>
        </div>
      </div>

      <div class="pagination" id="pagination"></div>
    </div>
  </main>

  <?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

<script src="./assets/js/site-header.js" defer></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    const PROPERTIES_API = window.BIVER_SITE?.propertiesApi || 'api/properties.php';
    let allProperties = [];
    let propertiesLoadFailed = false;
    let currentPage = 1;
    let itemsPerPage = 3;
    let currentView = 'grid';
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');

    function showToast(message) {
      const existing = document.querySelector('.toast-notification');
      if (existing) existing.remove();
      const toast = document.createElement('div');
      toast.className = 'toast-notification';
      toast.innerHTML = `<ion-icon name="checkmark-circle-outline"></ion-icon><span>${message}</span>`;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }

    function toggleFavorite(propertyId, btnElement) {
      const index = favorites.indexOf(propertyId);
      if (index === -1) { favorites.push(propertyId); showToast('Added to favorites!'); }
      else { favorites.splice(index, 1); showToast('Removed from favorites'); }
      localStorage.setItem('favorites', JSON.stringify(favorites));
      if (btnElement) { btnElement.classList.toggle('active'); btnElement.querySelector('ion-icon').name = favorites.includes(propertyId) ? 'heart' : 'heart-outline'; }
      renderProperties();
    }

    function getPropertyImage(property, index) {
      const portfolio = window.HOPE_PORTFOLIO || {};
      if (portfolio.propertyImage) {
        return portfolio.propertyImage(property, typeof index === 'number' ? index : 0);
      }
      if (property.imageUrl) return resolveMediaUrl(property.imageUrl);
      if (property.images && property.images[0]) return resolveMediaUrl(property.images[0]);
      return portfolio.imageAt ? portfolio.imageAt(0) : './assets/images/Scene 10_1.png';
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

    function escapeHtml(str) {
      if (!str) return '';
      return String(str).replace(/[&<>"']/g, (m) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
      }[m]));
    }

    function createPropertyCard(property, cardIndex) {
      const isFavorite = favorites.includes(property._id);
      const imageUrl = getPropertyImage(property, cardIndex || 0);
      const badge = (window.HOPE_PORTFOLIO && window.HOPE_PORTFOLIO.categoryBadge)
        ? window.HOPE_PORTFOLIO.categoryBadge(property.type)
        : { label: property.type === 'rent' ? 'Interior Design' : 'Architecture', className: property.type === 'rent' ? 'interior' : 'architecture' };
      const badgeClass = badge.className;
      const badgeText = badge.label;
      const detailUrl = window.BIVER_SITE?.propertyDetail
        ? window.BIVER_SITE.propertyDetail(property._id)
        : 'property-detail?id=' + encodeURIComponent(property._id);
      return `
        <div class="property-card">
          <div class="card-banner">
            <img src="${imageUrl}" alt="${escapeHtml(property.title)}" loading="lazy" data-portfolio-fallback="${cardIndex || 0}">
            <div class="card-badge ${badgeClass}">${badgeText}</div>
            <button class="favorite-btn ${isFavorite ? 'active' : ''}" onclick="event.stopPropagation(); toggleFavorite('${property._id}', this)">
              <ion-icon name="${isFavorite ? 'heart' : 'heart-outline'}"></ion-icon>
            </button>
          </div>
          <div class="card-content">
            <span class="card-category">${badgeText}</span>
            <h3 class="card-title">${escapeHtml(property.title)}</h3>
            <div class="card-location"><ion-icon name="location-outline"></ion-icon> ${escapeHtml(property.location || 'Lagos, Nigeria')}</div>
            <div class="card-features">
              <div class="feature"><ion-icon name="bed-outline"></ion-icon> ${property.bedrooms || 2} Beds</div>
              <div class="feature"><ion-icon name="water-outline"></ion-icon> ${property.bathrooms || 2} Baths</div>
              ${property.area ? `<div class="feature"><ion-icon name="square-outline"></ion-icon> ${property.area} sq ft</div>` : ''}
            </div>
          </div>
          <div class="card-footer">
            <div class="agent-info">
              <div class="agent-avatar"><ion-icon name="person-outline"></ion-icon></div>
              <span class="agent-name">Hope Architects</span>
            </div>
            <a href="${detailUrl}" class="view-property">View Details <ion-icon name="arrow-forward-outline"></ion-icon></a>
          </div>
        </div>
      `;
    }

    function hideEl(el) { if (el) el.classList.add('u-hidden'); }
    function showEl(el) { if (el) el.classList.remove('u-hidden'); }

    function renderProperties() {
      const grid = document.getElementById('propertiesGrid');
      const skeleton = document.getElementById('skeletonLoader');
      const errorState = document.getElementById('errorState');
      const resultsCount = document.getElementById('resultsCount');
      
      if (propertiesLoadFailed) {
        hideEl(skeleton);
        hideEl(grid);
        showEl(errorState);
        errorState.innerHTML = '<ion-icon name="alert-circle-outline"></ion-icon><h3>Unable to Load Projects</h3><p>We\'re having trouble connecting to our project database. Please check your connection and try again.</p><button class="retry-btn" onclick="location.reload()">Try Again</button>';
        resultsCount.textContent = '0 projects';
        document.getElementById('pagination').innerHTML = '';
        return;
      }

      if (!allProperties.length) {
        hideEl(skeleton);
        hideEl(grid);
        showEl(errorState);
        errorState.innerHTML = '<ion-icon name="home-outline"></ion-icon><h3>No Projects Available</h3><p>There are currently no approved projects in our portfolio. Please check back soon or contact us for assistance.</p><a href="' + (window.BIVER_SITE?.page ? window.BIVER_SITE.page('contact') : 'contact') + '" class="retry-btn">Contact Us</a>';
        resultsCount.textContent = '0 projects';
        document.getElementById('pagination').innerHTML = '';
        return;
      }
      
      const filtered = allProperties;
      const typeFilter = document.getElementById('typeFilter').value;
      const searchTerm = document.getElementById('propertyFilterInput').value.toLowerCase();
      const sortBy = document.getElementById('sortFilter').value;
      
      let filteredProps = filtered.filter(p => {
        if (typeFilter && p.type !== typeFilter) return false;
        if (searchTerm && !p.title.toLowerCase().includes(searchTerm) && !(p.location || '').toLowerCase().includes(searchTerm)) return false;
        return true;
      });
      
      if (sortBy === 'title') filteredProps.sort((a, b) => (a.title || '').localeCompare(b.title || ''));
      else filteredProps.sort((a,b) => new Date(b.createdAt) - new Date(a.createdAt));

      const totalPages = Math.max(1, Math.ceil(filteredProps.length / itemsPerPage));
      if (currentPage > totalPages) currentPage = totalPages;
      const start = (currentPage - 1) * itemsPerPage;
      const paginated = filteredProps.slice(start, start + itemsPerPage);

      resultsCount.textContent = filteredProps.length
        ? `Showing ${paginated.length} of ${filteredProps.length} projects (page ${currentPage} of ${totalPages})`
        : '0 projects';
      
      if (paginated.length === 0) {
        hideEl(grid);
        hideEl(skeleton);
        showEl(errorState);
        errorState.innerHTML = '<ion-icon name="home-outline"></ion-icon><h3>No projects found</h3><p>Try adjusting your search or filters</p><button class="retry-btn" onclick="document.getElementById(\'propertyFilterInput\').value=\'\'; document.getElementById(\'typeFilter\').value=\'\'; renderProperties()">Clear Filters</button>';
        document.getElementById('pagination').innerHTML = '';
        return;
      }
      
      hideEl(errorState);
      hideEl(skeleton);
      showEl(grid);
      grid.className = `properties-grid ${currentView === 'list' ? 'list-view' : ''}`;
      grid.innerHTML = paginated.map((p, i) => createPropertyCard(p, start + i)).join('');
      if (window.HOPE_PORTFOLIO && window.HOPE_PORTFOLIO.bindImageFallback) {
        window.HOPE_PORTFOLIO.bindImageFallback(grid);
      }

      const paginationDiv = document.getElementById('pagination');
      if (totalPages <= 1) { paginationDiv.innerHTML = ''; return; }

      let pagesHtml = '';
      if (currentPage > 1) {
        pagesHtml += `<button class="page-btn" data-page="${currentPage - 1}" aria-label="Previous page">&lsaquo;</button>`;
      }
      for (let i = 1; i <= totalPages; i++) {
        pagesHtml += `<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
      }
      if (currentPage < totalPages) {
        pagesHtml += `<button class="page-btn" data-page="${currentPage + 1}" aria-label="Next page">&rsaquo;</button>`;
      }
      paginationDiv.innerHTML = pagesHtml;
      document.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', () => { currentPage = parseInt(btn.dataset.page); renderProperties(); window.scrollTo({ top: 400, behavior: 'smooth' }); });
      });
    }

    async function loadProperties() {
      const skeleton = document.getElementById('skeletonLoader');
      const errorState = document.getElementById('errorState');
      showEl(skeleton);
      hideEl(errorState);

      try {
        const response = await fetch(`${PROPERTIES_API}?limit=100`);
        const data = await response.json();

        if (!response.ok || data.success === false) {
          throw new Error(data.message || 'Failed to load properties');
        }

        allProperties = data.properties || [];
        propertiesLoadFailed = false;
      } catch (error) {
        console.error('Property load failed:', error.message);
        allProperties = [];
        propertiesLoadFailed = true;
      }

      currentPage = 1;
      renderProperties();
    }
    
    document.querySelectorAll('.view-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentView = btn.dataset.view;
        renderProperties();
      });
    });
    
    document.getElementById('searchBtn').addEventListener('click', () => { currentPage = 1; renderProperties(); });
    document.getElementById('propertyFilterInput').addEventListener('keypress', (e) => { if (e.key === 'Enter') { currentPage = 1; renderProperties(); } });
    document.getElementById('typeFilter').addEventListener('change', () => { currentPage = 1; renderProperties(); });
    document.getElementById('sortFilter').addEventListener('change', () => { renderProperties(); });
    
    loadProperties();
  </script>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
</body>
</html>