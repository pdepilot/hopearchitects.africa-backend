<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/site_paths.php';
require_once dirname(__DIR__, 2) . '/includes/site_helpers.php';
?>
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <div class="topbar" role="banner">
    <div class="topbar-inner">
      <div class="topbar-left">
        <a href="<?= siteMailto() ?>" class="topbar-link" aria-label="Email us">
          <ion-icon name="mail-outline"></ion-icon>
          <span><?= siteEscape(siteContactEmail()) ?></span>
        </a>
        <div class="topbar-divider" aria-hidden="true"></div>
        <a href="#" class="topbar-link" aria-label="Our location">
          <ion-icon name="location-outline"></ion-icon>
          <address><?= siteEscape(siteAddress()) ?></address>
        </a>
        <div class="topbar-divider" aria-hidden="true"></div>
        <a href="<?= siteTelHref() ?>" class="topbar-link" aria-label="Call us">
          <ion-icon name="call-outline"></ion-icon>
          <span><?= siteEscape(siteContactPhone()) ?></span>
        </a>
      </div>
      <div class="topbar-right">
        <div class="topbar-socials" aria-label="Social media links">
          <?php if (siteSocial('facebook') !== ''): ?>
          <a href="<?= siteEscape(siteSocial('facebook')) ?>" class="topbar-social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <ion-icon name="logo-facebook"></ion-icon>
          </a>
          <?php endif; ?>
          <?php if (siteSocial('instagram') !== ''): ?>
          <a href="<?= siteEscape(siteSocial('instagram')) ?>" class="topbar-social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
            <ion-icon name="logo-instagram"></ion-icon>
          </a>
          <?php endif; ?>
          <?php if (siteSocial('tiktok') !== ''): ?>
          <a href="<?= siteEscape(siteSocial('tiktok')) ?>" class="topbar-social-link" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
            <ion-icon name="logo-youtube"></ion-icon>
          </a>
          <?php endif; ?>
        </div>
        <a href="<?= siteEscape(pageUrl('start-a-project')) ?>" class="topbar-cta">Start a Project</a>
      </div>
    </div>
  </div>

  <div id="searchModal" role="dialog" aria-modal="true" aria-label="Search projects">
    <button class="search-modal-close" id="searchModalClose" aria-label="Close search">
      <ion-icon name="close-outline"></ion-icon>
    </button>
    <p class="search-modal-eyebrow">Project Search</p>
    <h2 class="search-modal-heading">Find Your <em>Next</em> Build</h2>
    <div class="search-input-wrap">
      <input type="search" id="searchInput"
        placeholder="Search by location, type, or project..."
        autocomplete="off" spellcheck="false"
        aria-label="Search projects">
      <ion-icon name="search-outline" class="search-input-icon" aria-hidden="true"></ion-icon>
    </div>
    <div class="search-filters" role="group" aria-label="Filter by type">
      <button class="search-filter-chip active" data-filter="all">All</button>
      <button class="search-filter-chip" data-filter="sale">Architecture</button>
      <button class="search-filter-chip" data-filter="rent">Interior Design</button>
    </div>
    <div class="search-results-wrap" id="searchResults" aria-live="polite" aria-label="Search results"></div>
  </div>

  <header class="header" id="header" data-header>
    <div class="header-bottom">
      <div class="container">
        <a href="<?= siteEscape(pageUrl('index')) ?>" class="logo" aria-label="<?= siteEscape(siteName()) ?> Home">
          <?php siteLogoVideo(50); ?>
        </a>

        <nav class="navbar" data-navbar aria-label="Main navigation">
          <div class="navbar-inner-wrap">
            <div class="navbar-top">
              <a href="<?= siteEscape(pageUrl('index')) ?>" class="logo">
                <?php siteLogoVideo(50, 140); ?>
              </a>
              <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
                <ion-icon name="close-outline"></ion-icon>
              </button>
            </div>

            <div class="navbar-bottom">
              <ul class="navbar-list" role="menubar">
                <li role="none"><a href="<?= siteEscape(pageUrl('index')) ?>" class="navbar-link" data-nav-link role="menuitem">Home</a></li>
                <li role="none"><a href="<?= siteEscape(pageUrl('about')) ?>" class="navbar-link" data-nav-link role="menuitem">About</a></li>
                <li role="none"><a href="<?= siteEscape(pageUrl('services')) ?>" class="navbar-link" data-nav-link role="menuitem">Services</a></li>
                <li role="none"><a href="<?= siteEscape(pageUrl('property')) ?>" class="navbar-link" data-nav-link role="menuitem">Projects</a></li>
                <li role="none"><a href="<?= siteEscape(pageUrl('contact')) ?>" class="navbar-link" data-nav-link role="menuitem">Contact</a></li>
              </ul>
            </div>

            <div class="navbar-footer">
              <p class="navbar-footer-title">Get in Touch</p>
              <a href="<?= siteTelHref() ?>" class="navbar-footer-link">
                <ion-icon name="call-outline"></ion-icon>
                <span><?= siteEscape(siteContactPhone()) ?></span>
              </a>
              <a href="<?= siteMailto() ?>" class="navbar-footer-link">
                <ion-icon name="mail-outline"></ion-icon>
                <span><?= siteEscape(siteContactEmail()) ?></span>
              </a>
              <a href="<?= siteEscape(pageUrl('start-a-project')) ?>" class="navbar-cta">Start a Project</a>
            </div>
          </div>
        </nav>

        <div class="header-bottom-actions">
          <button class="header-bottom-actions-btn" data-search-open aria-label="Search projects" aria-expanded="false" aria-controls="searchModal">
            <ion-icon name="search-outline"></ion-icon>
            <span>Search</span>
          </button>
          <button class="header-bottom-actions-btn" data-nav-open-btn aria-label="Open Menu" aria-expanded="false">
            <ion-icon name="menu-outline"></ion-icon>
            <span>Menu</span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <div class="overlay" data-overlay aria-hidden="true"></div>
