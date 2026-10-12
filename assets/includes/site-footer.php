<?php
/**
 * Hope Architects — Monolith footer (shared across public pages)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/site_paths.php';
require_once dirname(__DIR__, 2) . '/includes/site_helpers.php';

$footerJs = siteUrl('assets/js/site-footer.js');
?>
<footer class="footer footer--monolith" role="contentinfo">
  <div class="footer-monolith__blueprint" aria-hidden="true"></div>
  <div class="footer-monolith__watermark" aria-hidden="true">
    <img src="<?= siteEscape(publicAssetUrl('assets/images/hope_archites.png') ?? siteUrl('assets/images/hope_archites.png')) ?>" alt="">
  </div>
  <div class="footer-monolith__glow footer-monolith__glow--1" aria-hidden="true"></div>
  <div class="footer-monolith__glow footer-monolith__glow--2" aria-hidden="true"></div>
  <div class="footer-monolith__apex" aria-hidden="true"></div>

  <section class="footer-monolith__hero">
    <p class="footer-monolith__eyebrow"><?= siteEscape(siteName()) ?></p>
    <h2 class="footer-monolith__headline">Let's Build<br><em>Tomorrow</em></h2>
    <p class="footer-monolith__tagline">Architecture that outlasts trends. Construction that earns trust.</p>
    <div class="footer-monolith__hero-actions">
      <a href="<?= pageHref('contact') ?>" class="footer-monolith__cta footer-monolith__cta--primary">
        <span>Start a Project</span>
        <ion-icon name="arrow-forward-outline"></ion-icon>
      </a>
      <a href="<?= siteTelHref() ?>" class="footer-monolith__cta footer-monolith__cta--ghost">
        <ion-icon name="call-outline"></ion-icon>
        <span><?= siteEscape(siteContactPhone()) ?></span>
      </a>
    </div>
  </section>

  <div class="footer-monolith__marquee" aria-hidden="true">
    <div class="footer-monolith__marquee-track">
      <span>Architecture</span><span class="footer-monolith__dot">◆</span>
      <span>Construction</span><span class="footer-monolith__dot">◆</span>
      <span>Design Excellence</span><span class="footer-monolith__dot">◆</span>
      <span>Hope Architects</span><span class="footer-monolith__dot">◆</span>
      <span>Architecture</span><span class="footer-monolith__dot">◆</span>
      <span>Construction</span><span class="footer-monolith__dot">◆</span>
      <span>Design Excellence</span><span class="footer-monolith__dot">◆</span>
      <span>Hope Architects</span><span class="footer-monolith__dot">◆</span>
    </div>
  </div>

  <div class="footer-monolith__core">
    <div class="footer-brand footer-monolith__brand">
      <a href="<?= pageHref('index') ?>" class="logo footer-monolith__logo">
        <?php siteLogoVideo(50, 150); ?>
      </a>
      <p class="section-text footer-monolith__desc">
        <?= siteEscape(siteAboutText()) ?>
      </p>
      <ul class="contact-list footer-monolith__contact">
        <li>
          <a href="<?= pageHref('contact') ?>" class="contact-link">
            <ion-icon name="location-outline"></ion-icon>
            <address><?= siteEscape(siteAddress()) ?></address>
          </a>
        </li>
        <li>
          <a href="<?= siteMailto() ?>" class="contact-link">
            <ion-icon name="mail-outline"></ion-icon>
            <span><?= siteEscape(siteContactEmail()) ?></span>
          </a>
        </li>
      </ul>
      <ul class="social-list footer-monolith__social">
        <?php if (siteSocial('facebook') !== ''): ?>
        <li><a href="<?= siteEscape(siteSocial('facebook')) ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><ion-icon name="logo-facebook"></ion-icon></a></li>
        <?php endif; ?>
        <?php if (siteSocial('instagram') !== ''): ?>
        <li><a href="<?= siteEscape(siteSocial('instagram')) ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><ion-icon name="logo-instagram"></ion-icon></a></li>
        <?php endif; ?>
        <?php if (siteSocial('tiktok') !== ''): ?>
        <li><a href="<?= siteEscape(siteSocial('tiktok')) ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><ion-icon name="logo-youtube"></ion-icon></a></li>
        <?php endif; ?>
      </ul>
      <div class="footer-monolith__live">
        <span class="footer-monolith__live-dot"></span>
        <span id="footerLocalTime">—</span>
      </div>
    </div>

    <div class="footer-link-box footer-monolith__nav">
      <ul class="footer-list">
        <li><p class="footer-list-title">Company</p></li>
        <li><a href="<?= pageHref('about') ?>" class="footer-link">About Us</a></li>
        <li><a href="https://hopearchitects.ng/blog/" class="footer-link" target="_blank" rel="noopener noreferrer">Blog</a></li>
        <li><a href="<?= pageHref('property') ?>" class="footer-link">All Projects</a></li>
        <li><a href="<?= pageHref('gallery') ?>" class="footer-link">Gallery</a></li>
        <li><a href="<?= pageHref('contact') ?>" class="footer-link">Contact Us</a></li>
      </ul>
      <ul class="footer-list">
        <li><p class="footer-list-title">Services</p></li>
        <li><a href="<?= pageHref('services') ?>" class="footer-link">Architectural Design</a></li>
        <li><a href="<?= pageHref('services') ?>" class="footer-link">Construction</a></li>
        <li><a href="<?= pageHref('services') ?>" class="footer-link">Project Management</a></li>
        <li><a href="<?= pageHref('favorites') ?>" class="footer-link">Saved Projects</a></li>
      </ul>
      <ul class="footer-list">
        <li><p class="footer-list-title">Portal</p></li>
        <li><a href="<?= pageHref('login') ?>" class="footer-link">Login</a></li>
        <li><a href="<?= pageHref('userDashboard') ?>" class="footer-link">My Account</a></li>
        <li><a href="<?= pageHref('addCart') ?>" class="footer-link">Order Tracking</a></li>
        <li><a href="<?= pageHref('contact') ?>" class="footer-link">Support</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom footer-monolith__bottom">
    <div class="container footer-monolith__bottom-inner">
      <p class="copyright">
        &copy; <span id="footerYear"><?= date('Y') ?></span> <a href="<?= pageHref('index') ?>"><?= siteEscape(siteName()) ?></a>. All Rights Reserved
        <?php if (siteDesignerName() !== ''): ?>
        <span class="footer-monolith__designer"> · Designed by <span class="footer-monolith__designer-name"><?= siteEscape(siteDesignerName()) ?></span></span>
        <?php endif; ?>
      </p>
      <div class="footer-monolith__legal">
        <a href="<?= pageHref('login') ?>" class="footer-link">Client Portal</a>
        <a href="<?= pageHref('favorites') ?>" class="footer-link">Saved Projects</a>
      </div>
    </div>
  </div>
</footer>

<button id="scrollToTop" aria-label="Scroll to top">
  <ion-icon name="chevron-up-outline"></ion-icon>
</button>
<script src="<?= siteEscape($footerJs) ?>" defer></script>
