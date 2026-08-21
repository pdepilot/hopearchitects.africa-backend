<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php
require_once __DIR__ . '/includes/PageContentService.php';
require_once __DIR__ . '/includes/site_helpers.php';
require_once __DIR__ . '/includes/portfolio_helpers.php';
$aboutPage = PageContentService::getPage('about');
$aboutHero = $aboutPage['hero'] ?? [];
$aboutNarrative = $aboutPage['narrative'] ?? [];
$aboutValues = $aboutPage['values'] ?? [];
$aboutTeam = $aboutPage['team'] ?? [];
$aboutCta = $aboutPage['cta'] ?? [];

$aboutImageSrc = static function (string $path, int $fallbackIndex = 0): string {
    return portfolioResolveMediaUrl($path, $fallbackIndex);
};

/** @return list<array{key: string, url: string, icon: string, label: string, active: bool}> */
$aboutTeamSocialPlatforms = static function (array $social): array {
    $platforms = [
        ['key' => 'facebook',  'icon' => 'logo-facebook',  'label' => 'Facebook'],
        ['key' => 'instagram', 'icon' => 'logo-instagram', 'label' => 'Instagram'],
        ['key' => 'twitter',   'icon' => 'logo-twitter',   'label' => 'X'],
    ];
    $out = [];
    foreach ($platforms as $platform) {
        $url = trim((string) ($social[$platform['key']] ?? ''));
        $out[] = [
            'key'    => $platform['key'],
            'url'    => $url,
            'icon'   => $platform['icon'],
            'label'  => $platform['label'],
            'active' => $url !== '',
        ];
    }

    return $out;
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Hope Architects — the story behind our architecture and construction practice in Nigeria. Integrity, vision, and design excellence from Lagos to landmark builds nationwide.">
  <meta name="keywords" content="about Hope Architects, architecture Nigeria, construction company Lagos, building design, landmark projects">
  <meta name="author" content="Hope Architects">
  <title>About Us | Hope Architects — Architecture & Construction</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/about.css">
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
</head>
<body>

  <?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

  <main id="main-content">
    <!-- =============================================
         HERO SECTION - Cinematic Intro (preserved)
    ============================================= -->
    <section class="about-hero">
      <div class="hero-bg-pattern"></div>
      <div class="hero-glow"></div>
      <div class="container">
        <div class="hero-subtitle"><?= siteEscape((string) ($aboutHero['subtitle'] ?? 'EST. 2015')) ?></div>
        <h1 class="hero-title"><?= $aboutHero['title'] ?? 'Architects of <span class="gold-accent">Dreams</span>,<br>Builders of Trust' ?></h1>
        <p class="hero-description"><?= siteEscape((string) ($aboutHero['description'] ?? '')) ?></p>
        <div class="stats-grid">
          <?php foreach (($aboutHero['stats'] ?? []) as $stat): ?>
          <div class="stat-card"><div class="stat-number"><?= siteEscape((string) ($stat['num'] ?? '')) ?></div><div class="stat-label"><?= siteEscape((string) ($stat['label'] ?? '')) ?></div></div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- =============================================
         NARRATIVE - The Untold Story
    ============================================= -->
    <section class="narrative">
      <div class="container">
        <div class="narrative-grid">
          <div class="narrative-content reveal reveal-left">
            <div class="narrative-badge"><div class="line"></div><span><?= siteEscape((string) ($aboutNarrative['badge'] ?? 'The Untold Story')) ?></span></div>
            <h2 class="narrative-title"><?= siteEscape((string) ($aboutNarrative['title'] ?? '')) ?></h2>
            <p class="narrative-text"><?= siteEscape((string) ($aboutNarrative['paragraph1'] ?? '')) ?></p>
            <p class="narrative-text"><?= siteEscape((string) ($aboutNarrative['paragraph2'] ?? '')) ?></p>
            <div class="narrative-quote"><?= siteEscape((string) ($aboutNarrative['quote'] ?? '')) ?></div>
            <div class="signature"><?= siteEscape((string) ($aboutNarrative['signature'] ?? '')) ?></div>
          </div>
          <div class="visual-story reveal reveal-right visible">
            <img src="<?= siteEscape($aboutImageSrc((string) ($aboutNarrative['mainImage'] ?? 'assets/images/Scene 10_1.png'), 0)) ?>" alt="Founder at work" class="main-image" loading="lazy" width="800" height="1000">
            <img src="<?= siteEscape($aboutImageSrc((string) ($aboutNarrative['floatingImage'] ?? 'assets/images/IMG-20260720-WA0010.jpg'), 1)) ?>" alt="Early days" class="floating-image" loading="lazy" width="480" height="288">
            <div class="image-caption"><?= siteEscape((string) ($aboutNarrative['caption'] ?? '')) ?></div>
          </div>
        </div> 
      </div>
    </section>

    <!-- =============================================
         VALUES - Core Values Deep Dive
    ============================================= -->
    <section class="values">
      <div class="container">
        <div class="values-grid">
          <div class="values-list">
            <?php foreach (($aboutValues['items'] ?? []) as $i => $value): ?>
            <?php $delay = $i > 0 ? ' reveal-delay-' . min($i, 3) : ''; ?>
            <div class="value-item reveal reveal-left<?= $delay ?>">
              <div class="value-icon"><ion-icon name="<?= siteEscape((string) ($value['icon'] ?? 'star-outline')) ?>"></ion-icon></div>
              <div class="value-text">
                <h4><?= siteEscape((string) ($value['title'] ?? '')) ?></h4>
                <p><?= siteEscape((string) ($value['text'] ?? '')) ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <div class="values-image reveal reveal-right">
            <img src="<?= siteEscape($aboutImageSrc((string) ($aboutValues['image'] ?? ''), 2)) ?>" alt="Building community" class="values-main-img" loading="lazy">
          </div>
        </div>
      </div>
    </section>

    <!-- =============================================
         TEAM PREVIEW
    ============================================= -->
    <section class="team-preview">
      <div class="container">
        <div class="section-eyebrow reveal"><div class="line"></div><span><?= siteEscape((string) ($aboutTeam['eyebrow'] ?? '')) ?></span><div class="line"></div></div>
        <h2 class="section-title reveal reveal-delay-1"><?= siteEscape((string) ($aboutTeam['title'] ?? '')) ?></h2>
        <div class="team-grid">
          <?php foreach (($aboutTeam['members'] ?? []) as $i => $member): ?>
          <?php
            $delay = $i > 0 ? ' reveal-delay-' . min($i, 3) : '';
            $role = (string) ($member['role'] ?? '');
            $showSocial = (bool) preg_match('/founder|\bceo\b/i', $role);
            $platforms = $showSocial
              ? $aboutTeamSocialPlatforms(is_array($member['social'] ?? null) ? $member['social'] : [])
              : [];
            $hasActiveSocial = false;
            foreach ($platforms as $platform) {
              if (!empty($platform['active'])) {
                $hasActiveSocial = true;
                break;
              }
            }
          ?>
          <article class="team-card<?= $hasActiveSocial ? ' team-card--has-social' : '' ?> reveal<?= $delay ?>" tabindex="0">
            <div class="team-card__media">
              <img src="<?= siteEscape($aboutImageSrc((string) ($member['image'] ?? ''), 10 + (int) $i)) ?>" alt="<?= siteEscape((string) ($member['name'] ?? 'Team member')) ?>" class="team-image" loading="lazy">
              <?php if ($hasActiveSocial): ?>
              <div class="team-card__overlay">
                <p class="team-card__social-label">Follow</p>
                <div class="team-card__social" role="list" aria-label="<?= siteEscape((string) ($member['name'] ?? 'Team member') . ' social profiles') ?>">
                  <?php foreach ($platforms as $j => $platform): ?>
                  <?php if (empty($platform['active'])) continue; ?>
                  <a href="<?= siteEscape($platform['url']) ?>" class="team-card__social-link team-card__social-link--<?= siteEscape($platform['key']) ?>" style="--social-i: <?= (int) $j ?>" target="_blank" rel="noopener noreferrer" role="listitem" aria-label="<?= siteEscape((string) ($member['name'] ?? 'Team member') . ' on ' . $platform['label']) ?>">
                    <span class="team-card__social-ring" aria-hidden="true"></span>
                    <ion-icon name="<?= siteEscape($platform['icon']) ?>" aria-hidden="true"></ion-icon>
                  </a>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
            <div class="team-card__body">
              <h3 class="team-name"><?= siteEscape((string) ($member['name'] ?? '')) ?></h3>
              <p class="team-role"><?= siteEscape($role) ?></p>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- =============================================
         CTA SECTION
    ============================================= -->
    <section class="about-cta">
      <div class="container">
        <h2 class="reveal"><?= siteEscape((string) ($aboutCta['title'] ?? '')) ?></h2>
        <p class="reveal reveal-delay-1"><?= siteEscape((string) ($aboutCta['text'] ?? '')) ?></p>
        <a href="<?= pageHref((string) ($aboutCta['link'] ?? 'contact')) ?>" class="cta-btn reveal reveal-delay-2"><ion-icon name="chatbubble-outline"></ion-icon><?= siteEscape((string) ($aboutCta['label'] ?? 'Contact Us')) ?></a>
      </div>
    </section>
  </main>

  <?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

<!-- Scripts -->
    <script src="./assets/js/site-header.js" defer></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <script>
    'use strict';
    // Scroll Reveal Observer
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('visible'); revealObserver.unobserve(entry.target); } });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    // Stagger delays
    const delays = { 'reveal-delay-1': 100, 'reveal-delay-2': 200, 'reveal-delay-3': 300, 'reveal-delay-4': 400 };
    Object.keys(delays).forEach(cls => {
      document.querySelectorAll(`.${cls}`).forEach((el, i) => el.style.transitionDelay = `${delays[cls] + i * 50}ms`);
    });

    // Counter animation for stats
    function animateStats() {
      document.querySelectorAll('.stat-number').forEach(el => {
        const target = parseInt(el.innerText.replace(/[^0-9]/g, ''));
        if (!target) return;
        let current = 0;
        const increment = target / (2000 / 16);
        const update = () => {
          current += increment;
          if (current < target) { el.innerText = Math.floor(current).toLocaleString() + (el.innerText.includes('+') ? '+' : ''); requestAnimationFrame(update); }
          else el.innerText = el.innerText;
        };
        const obs = new IntersectionObserver((entries) => { if (entries[0].isIntersecting) { update(); obs.disconnect(); } }, { threshold: 0.5 });
        obs.observe(el);
      });
    }
    animateStats();

    // Team cards — reveal social overlay on tap (touch devices)
    const teamCards = document.querySelectorAll('.team-card--has-social');
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    teamCards.forEach((card) => {
      card.addEventListener('click', (e) => {
        if (canHover || e.target.closest('.team-card__social-link')) return;
        const isActive = card.classList.contains('is-active');
        teamCards.forEach((c) => c.classList.remove('is-active'));
        if (!isActive) card.classList.add('is-active');
      });

      card.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter' && e.key !== ' ') return;
        if (canHover) return;
        e.preventDefault();
        const isActive = card.classList.contains('is-active');
        teamCards.forEach((c) => c.classList.remove('is-active'));
        if (!isActive) card.classList.add('is-active');
      });
    });

    document.addEventListener('click', (e) => {
      if (canHover || e.target.closest('.team-card--has-social')) return;
      teamCards.forEach((c) => c.classList.remove('is-active'));
    });

    function resetHeroParallax() {
      const hero = document.querySelector('.about-hero');
      if (!hero) return;
      if (window.innerWidth < 992) {
        hero.style.transform = '';
        hero.style.opacity = '';
      }
    }
    window.addEventListener('resize', resetHeroParallax, { passive: true });
    resetHeroParallax();

    window.addEventListener('scroll', () => {
      if (window.innerWidth < 992) return;
      const hero = document.querySelector('.about-hero');
      if (hero) {
        hero.style.transform = `translateY(${window.scrollY * 0.3}px)`;
        hero.style.opacity = String(1 - window.scrollY / 800);
      }
    }, { passive: true });
  </script>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
</body>
</html>