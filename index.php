<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php
require_once __DIR__ . '/includes/HomepageContentService.php';
require_once __DIR__ . '/includes/site_helpers.php';
require_once __DIR__ . '/includes/portfolio_helpers.php';
$homepageContent = HomepageContentService::get();
$heroSlidesData = $homepageContent['slides'] ?? [];
$heroStatsData = $homepageContent['stats'] ?? [];
$homeSections = $homepageContent['sections'] ?? [];
$homePreloader = $homepageContent['preloader'] ?? [];
$homeHero = $homepageContent['hero'] ?? [];
$homeAbout = $homepageContent['about'] ?? [];
$homeWhyUs = $homepageContent['whyUs'] ?? [];
$homeProcess = $homepageContent['process'] ?? [];
$homeDeliver = $homepageContent['deliver'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Hope Architects — world-class architecture and construction in Nigeria. Visionary design, precision engineering, and landmark builds in Lagos and beyond.">
  <meta name="keywords" content="architecture, construction, Nigeria, Lagos, building design, Hope Architects, civil construction">
  <meta name="author" content="Hope Architects">
  <meta name="robots" content="index, follow">
  <meta property="og:title" content="Hope Architects — Architecture & Construction">
  <meta property="og:description" content="World-class architectural design and construction — shaping iconic spaces across Nigeria.">
  <meta property="og:image" content="<?= siteEscape(siteLogoPath()) ?>">
  <meta property="og:url" content="https://hopearchitects.ng">
  <meta property="og:type" content="website">
  <title>Hope Architects | World-Class Architecture & Construction Nigeria</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/index.css">
  <link rel="stylesheet" href="./assets/css/index-sections.css">
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-banners.css">
</head>

<body>

  <!-- =============================================
       PRELOADER — Full-screen intro video
  ============================================= -->
  <?php
  $preloaderEnabled = !isset($homePreloader['enabled']) || !empty($homePreloader['enabled']);
  $preloaderVideoPath = '';
  $preloaderVideoMime = 'video/mp4';
  $configuredPreloader = trim((string) ($homePreloader['video'] ?? ''));

  // Prefer configured path when the file still exists on disk.
  if ($configuredPreloader !== '' && portfolioLocalFileExists($configuredPreloader)) {
    $preloaderVideoPath = $configuredPreloader;
  } else {
    $candidateDirs = [
      __DIR__ . '/assets/uploads/homepage',
      __DIR__ . '/assets/videos',
    ];
    $found = [];
    foreach ($candidateDirs as $dir) {
      if (!is_dir($dir)) {
        continue;
      }
      foreach (['*.mp4', '*.webm', '*.mov'] as $pattern) {
        foreach (glob($dir . '/' . $pattern) ?: [] as $file) {
          $found[] = $file;
        }
      }
    }
    if ($found !== []) {
      usort($found, static function (string $a, string $b): int {
        $score = static function (string $path): int {
          $base = strtolower(basename($path));
          if (str_contains($base, 'preloader')) {
            return 0;
          }
          // Prefer clean .mp4 over double extensions like video.mp4.mp4
          if (preg_match('/\.mp4$/i', $base) && !preg_match('/\.mp4\.mp4$/i', $base)) {
            return 1;
          }
          return 2;
        };
        $sa = $score($a);
        $sb = $score($b);
        if ($sa !== $sb) {
          return $sa <=> $sb;
        }
        return filemtime($b) <=> filemtime($a);
      });
      $preloaderVideoPath = str_replace('\\', '/', substr($found[0], strlen(__DIR__) + 1));
    } elseif (is_file(__DIR__ . '/assets/video.mp4')) {
      $preloaderVideoPath = 'assets/video.mp4';
    }
  }

  $preloaderVideoUrl = '';
  if ($preloaderVideoPath !== '') {
    $absVideo = __DIR__ . '/' . portfolioNormalizeRelativePath($preloaderVideoPath);
    $bust = is_file($absVideo) ? (string) filemtime($absVideo) : (string) time();
    $preloaderVideoUrl = portfolioResolveMediaUrl($preloaderVideoPath, 0);
    $preloaderVideoUrl .= (str_contains($preloaderVideoUrl, '?') ? '&' : '?') . 'v=' . rawurlencode($bust);
    $ext = strtolower(pathinfo(portfolioNormalizeRelativePath($preloaderVideoPath), PATHINFO_EXTENSION));
    $preloaderVideoMime = match ($ext) {
      'webm' => 'video/webm',
      'mov' => 'video/quicktime',
      default => 'video/mp4',
    };
  }
  $preloaderAria = (string) ($homePreloader['ariaLabel'] ?? 'Loading Hope Architects');
  ?>
  <?php if ($preloaderEnabled): ?>
  <div id="preloader" role="dialog" aria-modal="true" aria-label="<?= siteEscape($preloaderAria) ?>" aria-busy="true">
    <?php if ($preloaderVideoUrl !== ''): ?>
    <video
      class="preloader-video"
      id="preloaderVideo"
      src="<?= siteEscape($preloaderVideoUrl) ?>"
      autoplay
      muted
      playsinline
      preload="auto"
      aria-hidden="true"
    >
      <source src="<?= siteEscape($preloaderVideoUrl) ?>" type="<?= siteEscape($preloaderVideoMime) ?>">
    </video>
    <?php endif; ?>
    <div class="preloader-video-overlay" aria-hidden="true"></div>
    <div class="preloader-curtain" aria-hidden="true"></div>
    <button type="button" class="preloader-skip" id="preloaderSkip" aria-label="Skip intro">
      <span>Skip</span>
      <span class="preloader-skip__arrow" aria-hidden="true"></span>
    </button>
  </div>
  <?php endif; ?>

  <?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

  <main id="main-content" class="home-page">
    <article>

      <?php require __DIR__ . '/assets/includes/promo-banner.php'; ?>

      <!-- =============================================
           HERO — Premium luxury full-screen
      ============================================= -->
      <section class="hero hero--luxury" id="home" aria-label="Hero section">
        <div class="hero-media" id="heroMedia" aria-hidden="true">
          <div class="hero-bg-slideshow" id="heroBgSlideshow">
            <div class="bg-slide active bg-slide--1"<?php if (!empty($heroSlidesData[0]['bgImage'])): ?> style="background-image:url('<?= siteEscape(portfolioResolveMediaUrl((string) $heroSlidesData[0]['bgImage'], 0)) ?>')"<?php endif; ?>></div>
            <?php foreach (array_slice($heroSlidesData, 1) as $i => $slide): ?>
            <div class="bg-slide" data-bg="<?= siteEscape(portfolioResolveMediaUrl((string) ($slide['bgImage'] ?? ''), $i + 1)) ?>"<?php if (!empty($slide['video'])): ?> data-video="<?= siteEscape($slide['video']) ?>"<?php endif; ?>></div>
            <?php endforeach; ?>
          </div>
          <video class="hero-bg-video" id="heroBgVideo" muted loop playsinline preload="none" aria-hidden="true"></video>
          <div class="bg-overlay"></div>
          <div class="hero-bg-pattern"></div>
          <div class="hero-glow"></div>
          <div class="hero-accent-line" aria-hidden="true"></div>
        </div>

        <div class="container hero-container">
          <div class="hero-content" id="heroContent">
            <div class="hero-location" id="heroLocation">
              <ion-icon name="location-outline" aria-hidden="true"></ion-icon>
              <span id="heroLocationText"><?= siteEscape((string) ($homeHero['location'] ?? 'Lagos, Nigeria')) ?></span>
            </div>

            <p class="hero-eyebrow" id="heroEyebrow">
              <span class="line"></span>
              <span id="heroEyebrowText"><?= siteEscape($heroSlidesData[0]['eyebrow'] ?? 'Premium Real Estate Agency') ?></span>
            </p>

            <h1 class="hero-title" id="heroTitle">
              <span class="hero-title-lines" id="heroTitleLines">
                <span id="heroTitleText"><?= $heroSlidesData[0]['title'] ?? 'Hope <span class="accent">Architects</span>' ?></span>
              </span>
            </h1>

            <p class="hero-tagline" id="heroTagline"><?= siteEscape($heroSlidesData[0]['tagline'] ?? 'World-class architectural design and construction — shaping iconic spaces across Lagos and Nigeria.') ?></p>

            <div class="hero-actions" id="heroActions">
              <a href="<?= pageHref((string) ($homeHero['primaryCtaHref'] ?? 'property')) ?>" class="hero-btn-primary">
                <span class="hero-btn-shine" aria-hidden="true"></span>
                <ion-icon name="home-outline"></ion-icon>
                <span><?= siteEscape((string) ($homeHero['primaryCtaLabel'] ?? 'Explore Projects')) ?></span>
              </a>
              <a href="<?= pageHref((string) ($homeHero['secondaryCtaHref'] ?? 'contact')) ?>" class="hero-btn-secondary">
                <ion-icon name="call-outline"></ion-icon>
                <span><?= siteEscape((string) ($homeHero['secondaryCtaLabel'] ?? 'Request Consultation')) ?></span>
              </a>
            </div>
          </div>
        </div>

        <div class="hero-slide-counter" aria-hidden="true">
          <span class="hero-slide-current" id="heroSlideCurrent">01</span>
          <span class="hero-slide-sep">/</span>
          <span class="hero-slide-total" id="heroSlideTotal"><?= str_pad((string) max(1, count($heroSlidesData)), 2, '0', STR_PAD_LEFT) ?></span>
        </div>

        <div class="hero-stats" id="heroStats" aria-label="Company statistics">
          <div class="container">
            <?php foreach ($heroStatsData as $stat): ?>
            <div class="stat-item">
              <div class="stat-icon"><ion-icon name="<?= siteEscape((string) ($stat['icon'] ?? 'home-outline')) ?>"></ion-icon></div>
              <div class="stat-info">
                <div class="num"><?= siteEscape((string) ($stat['num'] ?? '')) ?></div>
                <div class="label"><?= siteEscape((string) ($stat['label'] ?? '')) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="slide-dots" role="tablist" aria-label="Slideshow navigation">
          <?php foreach ($heroSlidesData as $i => $slide): ?>
          <button type="button" class="dot<?= $i === 0 ? ' active' : '' ?>" data-slide="<?= (int) $i ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= (int) $i + 1 ?>"></button>
          <?php endforeach; ?>
        </div>

        <a href="#about" class="hero-scroll-indicator" id="heroScrollIndicator" aria-label="Scroll to explore">
          <span class="hero-scroll-text">Discover</span>
          <span class="hero-scroll-mouse" aria-hidden="true">
            <span class="hero-scroll-wheel"></span>
          </span>
        </a>
      </section>

      <!-- =============================================
           ABOUT — Studio editorial
      ============================================= -->
      <section class="about about--studio" id="about" aria-label="About us">
        <div class="about-studio">
          <div class="about-studio__intro reveal">
            <span class="section-eyebrow">01 — Studio</span>
            <p class="section-subtitle"><?= siteEscape((string) ($homeAbout['subtitle'] ?? 'About Us')) ?></p>
            <h2 class="h2 section-title about-studio__title"><?= siteEscape((string) ($homeAbout['title'] ?? 'Architecture Built on Vision & Precision')) ?></h2>
            <p class="about-text about-studio__lead"><?= siteEscape((string) ($homeAbout['text'] ?? '')) ?></p>
            <ul class="about-studio__pillars">
              <?php foreach (($homeAbout['pillars'] ?? []) as $pillar): ?>
              <li><ion-icon name="<?= siteEscape((string) ($pillar['icon'] ?? 'home-outline')) ?>"></ion-icon> <?= siteEscape((string) ($pillar['label'] ?? '')) ?></li>
              <?php endforeach; ?>
            </ul>
            <blockquote class="about-studio__quote reveal reveal-delay-2">
              <?= siteEscape((string) ($homeAbout['quote'] ?? '')) ?>
            </blockquote>
            <a href="<?= pageHref('about') ?>" class="btn about-studio__cta reveal reveal-delay-3"><?= siteEscape((string) ($homeAbout['ctaLabel'] ?? 'Meet Our Team')) ?></a>
          </div>
          <div class="about-studio__visual reveal reveal-left">
            <figure class="about-studio__frame about-studio__frame--main">
              <img src="<?= siteEscape(portfolioResolveMediaUrl((string) ($homeAbout['imageMain'] ?? ''), 9)) ?>" alt="Modern architectural interior" loading="lazy" width="800" height="1000" data-portfolio-fallback="9">
            </figure>
            <figure class="about-studio__frame about-studio__frame--float">
              <img src="<?= siteEscape(portfolioResolveMediaUrl((string) ($homeAbout['imageFloat'] ?? ''), 10)) ?>" alt="Luxury building design" loading="lazy" width="600" height="600" data-portfolio-fallback="10">
            </figure>
            <div class="about-studio__stat-ring" aria-hidden="true">
              <span class="about-studio__stat-num"><?= siteEscape((string) ($homeAbout['ringNum'] ?? '15+')) ?></span>
              <span class="about-studio__stat-label"><?= siteEscape((string) ($homeAbout['ringLabel'] ?? 'Years Building')) ?></span>
            </div>
          </div>
          <aside class="about-studio__metrics reveal reveal-delay-2" aria-label="Studio highlights">
            <?php foreach (($homeAbout['metrics'] ?? []) as $metric): ?>
            <div class="about-studio__metric"><strong><?= siteEscape((string) ($metric['num'] ?? '')) ?></strong><span><?= siteEscape((string) ($metric['label'] ?? '')) ?></span></div>
            <?php endforeach; ?>
          </aside>
        </div>
      </section>

      <!-- =============================================
           SERVICE — Bento grid
      ============================================= -->
      <section class="service service--bento" id="service" aria-label="Our services">
        <div class="service-bento">
          <header class="service-bento__header reveal">
            <span class="section-eyebrow section-eyebrow--light">02 — Expertise</span>
            <p class="section-subtitle"><?= siteEscape((string) ($homeSections['services']['subtitle'] ?? 'Our Expertise')) ?></p>
            <h2 class="h2 section-title"><?= siteEscape((string) ($homeSections['services']['title'] ?? 'What We Build')) ?></h2>
          </header>
          <ul class="service-bento__grid">
            <li class="service-bento__cell service-bento__cell--featured reveal reveal-delay-1">
              <div class="service-card service-card--bento">
                <span class="service-bento__index">01</span>
                <div class="card-icon"><ion-icon name="color-palette-outline"></ion-icon></div>
                <h3 class="h3 card-title"><a href="<?= pageHref('services') ?>">Architectural Design</a></h3>
                <p class="card-text">Concept development, 3D visualization, and detailed drawings for residential and commercial projects.</p>
                <a href="<?= pageHref('services') ?>" class="card-link"><span>Our Design Process</span><ion-icon name="arrow-forward-outline"></ion-icon></a>
              </div>
            </li>
            <li class="service-bento__cell reveal reveal-delay-2">
              <div class="service-card service-card--bento">
                <span class="service-bento__index">02</span>
                <div class="card-icon"><ion-icon name="construct-outline"></ion-icon></div>
                <h3 class="h3 card-title"><a href="<?= pageHref('services') ?>">Construction</a></h3>
                <p class="card-text">End-to-end construction with rigorous quality control and transparent project reporting.</p>
                <a href="<?= pageHref('services') ?>" class="card-link"><span>Build With Us</span><ion-icon name="arrow-forward-outline"></ion-icon></a>
              </div>
            </li>
            <li class="service-bento__cell reveal reveal-delay-3">
              <div class="service-card service-card--bento">
                <span class="service-bento__index">03</span>
                <div class="card-icon"><ion-icon name="business-outline"></ion-icon></div>
                <h3 class="h3 card-title"><a href="<?= pageHref('contact') ?>">Project Management</a></h3>
                <p class="card-text">From feasibility to handover — budgets, timelines, approvals, and coordination.</p>
                <a href="<?= pageHref('contact') ?>" class="card-link"><span>Start a Project</span><ion-icon name="arrow-forward-outline"></ion-icon></a>
              </div>
            </li>
            <li class="service-bento__cell service-bento__cell--cta reveal reveal-delay-4">
              <div class="service-bento__cta-panel">
                <h3>Ready to break ground?</h3>
                <p>One team from first sketch to final handover.</p>
                <a href="<?= pageHref('contact') ?>" class="btn">Get in Touch</a>
              </div>
            </li>
          </ul>
        </div>
      </section>

      <!-- =============================================
           FEATURED PROJECTS — Portfolio Gallery
      ============================================= -->
      <section class="property property--gallery" id="property" aria-label="Featured projects">
        <div class="project-gallery">
          <div class="project-gallery__bg" aria-hidden="true"></div>

          <header class="project-gallery__header reveal">
            <div class="project-gallery__header-copy">
              <span class="section-eyebrow section-eyebrow--light">03 — Portfolio</span>
              <p class="section-subtitle"><?= siteEscape((string) ($homeSections['featured']['subtitle'] ?? 'Featured Projects')) ?></p>
              <h2 class="h2 section-title project-gallery__title"><?= siteEscape((string) ($homeSections['featured']['title'] ?? 'Signature Works')) ?></h2>
              <p class="project-gallery__desc">Landmark builds and in-progress designs — scroll the gallery or use the arrows to explore.</p>
            </div>
            <div class="project-gallery__header-actions">
              <div class="project-gallery__scroll-nav" id="projectGalleryNav" hidden>
                <button type="button" class="project-gallery__scroll-btn" id="projectGalleryPrev" aria-label="Scroll projects left">
                  <ion-icon name="arrow-back-outline"></ion-icon>
                </button>
                <button type="button" class="project-gallery__scroll-btn" id="projectGalleryNext" aria-label="Scroll projects right">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
              </div>
              <a href="<?= pageHref('property') ?>" class="project-gallery__view-all">
                View All Projects
                <ion-icon name="arrow-forward-outline"></ion-icon>
              </a>
            </div>
          </header>

          <div id="propertiesSkeleton" class="properties-skeleton project-gallery__skeleton" aria-hidden="true">
            <div class="project-gallery__skeleton-track">
              <div class="project-gallery__skeleton-card"></div>
              <div class="project-gallery__skeleton-card"></div>
              <div class="project-gallery__skeleton-card"></div>
            </div>
          </div>

          <div id="propertiesError" class="properties-error project-gallery__state u-hidden" role="alert">
            <ion-icon name="alert-circle-outline"></ion-icon>
            <h3>Failed to load projects</h3>
            <p>Unable to fetch featured projects. Please check your connection and try again.</p>
            <button onclick="loadFeaturedProperties()" class="btn u-mt-20">Try Again</button>
          </div>
          <div id="propertiesEmpty" class="properties-empty project-gallery__state u-hidden"></div>

          <div class="project-gallery__rail-wrap">
            <ul id="propertiesList" class="property-list has-scrollbar u-hidden project-gallery__rail" aria-label="Featured project gallery"></ul>
            <div class="project-gallery__progress" id="projectGalleryProgress" hidden aria-hidden="true">
              <span class="project-gallery__progress-bar" id="projectGalleryProgressBar"></span>
            </div>
          </div>
        </div>
      </section>

      <!-- =============================================
           WHY CHOOSE US — Asymmetric
      ============================================= -->
      <section class="why-us why-us--asymmetric" id="why-us" aria-label="Why choose us">
        <div class="why-asymmetric">
          <div class="why-asymmetric__visual reveal reveal-left">
            <figure class="why-asymmetric__portrait">
              <img src="<?= siteEscape(portfolioResolveMediaUrl((string) ($homeWhyUs['image'] ?? ''), 8)) ?>" alt="Hope Architects interior design" class="why-us-img-main why-asymmetric__photo" loading="lazy" data-portfolio-fallback="8">
              <span class="why-asymmetric__portrait-accent" aria-hidden="true"></span>
            </figure>
            <div class="why-us-badge why-asymmetric__badge">
              <div class="num"><?= siteEscape((string) ($homeWhyUs['badgeNum'] ?? '10+')) ?></div>
              <div class="txt"><?= siteEscape((string) ($homeWhyUs['badgeTxt'] ?? 'Years of Excellence')) ?></div>
            </div>
            <div class="why-asymmetric__float why-asymmetric__float--1">
              <ion-icon name="shield-checkmark-outline"></ion-icon>
              <span>ISO Quality</span>
            </div>
            <div class="why-asymmetric__float why-asymmetric__float--2">
              <ion-icon name="time-outline"></ion-icon>
              <span>On-Time Delivery</span>
            </div>
          </div>
          <div class="why-asymmetric__content">
            <span class="section-eyebrow reveal">04 — Why Hope</span>
            <p class="section-subtitle reveal section-subtitle--left"><?= siteEscape((string) ($homeWhyUs['subtitle'] ?? 'Why Choose Us')) ?></p>
            <h2 class="h2 section-title reveal reveal-delay-1 section-title--left"><?= $homeWhyUs['title'] ?? 'We Turn Vision Into<br><em>Landmark Architecture</em>' ?></h2>
            <p class="why-us-text reveal reveal-delay-2"><?= siteEscape((string) ($homeWhyUs['text'] ?? '')) ?></p>
            <div class="why-grid why-asymmetric__grid reveal reveal-delay-3">
              <?php foreach (($homeWhyUs['items'] ?? []) as $whyItem): ?>
              <div class="why-item">
                <div class="why-item-icon"><ion-icon name="<?= siteEscape((string) ($whyItem['icon'] ?? 'home-outline')) ?>"></ion-icon></div>
                <div>
                  <p class="why-item-title"><?= siteEscape((string) ($whyItem['title'] ?? '')) ?></p>
                  <p class="why-item-text"><?= siteEscape((string) ($whyItem['text'] ?? '')) ?></p>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <a href="<?= pageHref('about') ?>" class="btn reveal reveal-delay-4"><?= siteEscape((string) ($homeWhyUs['ctaLabel'] ?? 'Learn More About Us')) ?></a>
          </div>
        </div>
      </section>

      <!-- =============================================
           PROCESS — Timeline
      ============================================= -->
      <section class="process process--timeline" id="process" aria-label="How it works">
        <div class="process-timeline">
          <header class="process-timeline__header reveal">
            <span class="section-eyebrow section-eyebrow--light">05 — Method</span>
            <p class="section-subtitle"><?= siteEscape((string) ($homeProcess['subtitle'] ?? 'Our Process')) ?></p>
            <h2 class="h2 section-title"><?= siteEscape((string) ($homeProcess['title'] ?? 'Your Project in 4 Clear Phases')) ?></h2>
          </header>
          <ol class="process-timeline__track">
            <?php foreach (($homeProcess['steps'] ?? []) as $stepIndex => $step): ?>
            <li class="process-item process-timeline__step reveal reveal-delay-<?= min(4, (int) $stepIndex + 1) ?>">
              <span class="process-timeline__num"><?= str_pad((string) ((int) $stepIndex + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <div class="process-icon"><ion-icon name="<?= siteEscape((string) ($step['icon'] ?? 'home-outline')) ?>"></ion-icon></div>
              <h3 class="process-title"><?= siteEscape((string) ($step['title'] ?? '')) ?></h3>
              <p class="process-text"><?= siteEscape((string) ($step['text'] ?? '')) ?></p>
            </li>
            <?php endforeach; ?>
          </ol>
        </div>
      </section>

      <!-- =============================================
           WHAT WE DELIVER
      ============================================= -->
      <section class="features features--deliver" id="features" aria-label="What we deliver">
        <div class="deliver-showcase">
          <div class="deliver-showcase__bg" aria-hidden="true"></div>
          <div class="deliver-showcase__layout">
            <aside class="deliver-showcase__intro reveal">
              <span class="section-eyebrow">06 — Capabilities</span>
              <p class="section-subtitle"><?= siteEscape((string) ($homeDeliver['subtitle'] ?? 'What We Deliver')) ?></p>
              <h2 class="h2 section-title deliver-showcase__title"><?= $homeDeliver['title'] ?? 'Built for<br>Every Scale' ?></h2>
              <p class="deliver-showcase__lead"><?= siteEscape((string) ($homeDeliver['lead'] ?? '')) ?></p>
              <a href="<?= pageHref('services') ?>" class="btn deliver-showcase__cta"><?= siteEscape((string) ($homeDeliver['ctaLabel'] ?? 'Explore All Services')) ?></a>
              <div class="deliver-showcase__stat" aria-hidden="true">
                <strong><?= siteEscape((string) ($homeDeliver['statNum'] ?? '8+')) ?></strong>
                <span><?= siteEscape((string) ($homeDeliver['statLabel'] ?? 'Core disciplines')) ?></span>
              </div>
            </aside>

            <ul class="deliver-showcase__grid">
              <?php foreach (($homeDeliver['tiles'] ?? []) as $tileIndex => $tile): ?>
              <li class="reveal reveal-delay-<?= min(4, ((int) $tileIndex % 4) + 1) ?>">
                <a href="<?= pageHref(!empty($tile['isCta']) ? 'contact' : 'services') ?>" class="deliver-tile<?= !empty($tile['isCta']) ? ' deliver-tile--cta' : '' ?>">
                  <span class="deliver-tile__num"><?= str_pad((string) ((int) $tileIndex + 1), 2, '0', STR_PAD_LEFT) ?></span>
                  <span class="deliver-tile__icon"><ion-icon name="<?= siteEscape((string) ($tile['icon'] ?? 'home-outline')) ?>"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title"><?= siteEscape((string) ($tile['title'] ?? '')) ?></h3>
                    <p class="deliver-tile__desc"><?= siteEscape((string) ($tile['text'] ?? '')) ?></p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </section>

      <!-- =============================================
           LOCAL EXPERTISE — Lagos
      ============================================= -->
      <section class="areas areas--cities" id="areas" aria-label="Areas we serve">
        <div class="cities-showcase">
          <div class="cities-showcase__skyline" aria-hidden="true"></div>
          <div class="cities-showcase__orb cities-showcase__orb--lagos" aria-hidden="true"></div>
          <div class="cities-showcase__watermark cities-showcase__watermark--lagos" aria-hidden="true">LAGOS</div>

          <header class="cities-showcase__header reveal">
            <div class="cities-showcase__header-copy">
              <span class="section-eyebrow section-eyebrow--light">08 — Reach</span>
              <p class="section-subtitle"><?= siteEscape((string) ($homeSections['areas']['subtitle'] ?? 'Local Expertise')) ?></p>
              <h2 class="h2 section-title cities-showcase__title"><?= $homeSections['areas']['title'] ?? 'Building Across<br><em>Lagos</em>' ?></h2>
              <p class="areas-intro cities-showcase__intro reveal reveal-delay-1" id="areasIntro">From Victoria Island skylines to Lekki estates — we design and deliver landmark architecture across Nigeria's most dynamic city.</p>
            </div>
            <div class="cities-showcase__hubs reveal reveal-delay-2">
              <div class="cities-showcase__hub cities-showcase__hub--lagos">
                <ion-icon name="business-outline"></ion-icon>
                <span>Lagos Hub</span>
                <small>Island · Mainland · Lekki</small>
              </div>
            </div>
          </header>

          <div class="cities-showcase__metrics reveal reveal-delay-2" aria-label="Service coverage">
            <div class="cities-showcase__metric"><strong>1</strong><span>City</span></div>
            <div class="cities-showcase__metric"><strong>12+</strong><span>Districts</span></div>
            <div class="cities-showcase__metric"><strong>100+</strong><span>Active Sites</span></div>
          </div>

          <div id="areasSkeleton" class="cities-showcase__skeleton" aria-hidden="true">
            <div class="cities-showcase__skeleton-track">
              <div class="cities-showcase__skeleton-card"></div>
              <div class="cities-showcase__skeleton-card"></div>
              <div class="cities-showcase__skeleton-card"></div>
            </div>
          </div>

          <div class="cities-showcase__rail-wrap">
            <div class="cities-showcase__rail-nav" id="citiesGalleryNav" hidden>
              <button type="button" class="cities-showcase__rail-btn" id="citiesGalleryPrev" aria-label="Scroll areas left">
                <ion-icon name="arrow-back-outline"></ion-icon>
              </button>
              <button type="button" class="cities-showcase__rail-btn" id="citiesGalleryNext" aria-label="Scroll areas right">
                <ion-icon name="arrow-forward-outline"></ion-icon>
              </button>
            </div>
            <ul class="areas-grid has-scrollbar cities-showcase__rail" id="areasList" aria-label="Lagos districts we serve">
              <li class="reveal"><article class="city-card city-card--loading"><div class="city-card__body"><p class="city-card__text">Loading districts...</p></div></article></li>
            </ul>
            <div class="cities-showcase__progress" id="citiesGalleryProgress" hidden aria-hidden="true">
              <span class="cities-showcase__progress-bar" id="citiesGalleryProgressBar"></span>
            </div>
          </div>

          <div class="areas-cta cities-showcase__cta reveal" id="areasCtaWrap">
            <div class="cities-showcase__cta-inner">
              <p id="areasCtaText">Don't see your district? Our teams cover all of Lagos.</p>
              <a href="<?= pageHref('contact') ?>" class="btn cities-showcase__cta-btn" id="areasCtaBtn">Book a Site Consultation</a>
            </div>
          </div>
        </div>
      </section>

      <!-- =============================================
           CTA — Immersive
      ============================================= -->
      <section class="cta cta--immersive" id="cta" aria-label="Call to action">
        <div class="cta-immersive">
          <div class="cta-immersive__bg" aria-hidden="true"></div>
          <div class="cta-card cta-immersive__panel">
            <div class="cta-inner">
              <div class="cta-content reveal reveal-left">
                <span class="section-eyebrow section-eyebrow--light">09 — Begin</span>
                <p class="eyebrow">Ready to Build?</p>
                <h2 class="card-title">Ready to Create Something Iconic?</h2>
                <p class="card-text">From first sketch to final handover — our architects and builders are ready to bring your vision to life.</p>
              </div>
              <div class="cta-actions reveal reveal-right">
                <a href="<?= pageHref('property') ?>" class="cta-btn-primary">
                  <ion-icon name="business-outline"></ion-icon> View Projects
                </a>
                <a href="<?= pageHref('contact') ?>" class="cta-btn-outline">
                  <ion-icon name="call-outline"></ion-icon> Book Consultation
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

    </article>
  </main>

  <?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

<!-- Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "Hope Architects",
    "description": "World-class architecture and construction firm in Nigeria specializing in design, engineering, and landmark builds.",
    "url": "https://hopearchitects.ng",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "12 Admiralty Way, Lekki Phase 1",
      "addressLocality": "Lagos",
      "addressRegion": "Lagos State",
      "addressCountry": "NG"
    },
    "telephone": "<?= siteEscape(siteContactPhoneTel()) ?>",
    "email": "<?= siteEscape(siteContactEmail()) ?>"
  }
  </script>

  <!-- Ionicons -->
    <script src="./assets/js/site-header.js" defer></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
  <script>
    window.HOPE_HERO = {
      slides: <?= json_encode($heroSlidesData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    };
  </script>
  <script src="./assets/js/index.js" defer></script>
  <script src="./assets/js/index-sections.js" defer></script>

  <script>
    'use strict';

    /* =============================================
       PRELOADER LOGIC — Full-screen intro video
       Dismisses when the video ends, on error, or after a safe timeout.
       Respects prefers-reduced-motion.
    ============================================= */
    (function initPreloader() {
      const preloader = document.getElementById('preloader');
      const video     = document.getElementById('preloaderVideo');

      function notifySiteReady() {
        window.dispatchEvent(new CustomEvent('hope:preloader-done'));
        window.dispatchEvent(new CustomEvent('biver:preloader-done'));
      }

      if (!preloader) {
        notifySiteReady();
        return;
      }

      let fallbackTimer = null;
      let dismissed = false;

      function scheduleFallback(ms) {
        clearTimeout(fallbackTimer);
        fallbackTimer = setTimeout(dismissPreloader, ms);
      }

      function dismissPreloader() {
        if (dismissed || preloader.classList.contains('hidden')) return;
        dismissed = true;
        if (fallbackTimer) clearTimeout(fallbackTimer);
        preloader.classList.add('exiting');
        setTimeout(() => {
          preloader.classList.add('hidden');
          document.body.style.overflow = '';
          if (video) {
            try {
              video.pause();
              video.removeAttribute('src');
              video.querySelectorAll('source').forEach((s) => s.removeAttribute('src'));
              video.load();
            } catch (_) { /* ignore */ }
          }
          notifySiteReady();
        }, 700);
      }

      document.body.style.overflow = 'hidden';

      const skipBtn = document.getElementById('preloaderSkip');
      if (skipBtn) {
        skipBtn.addEventListener('click', (e) => {
          e.preventDefault();
          dismissPreloader();
        });
      }
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') dismissPreloader();
      }, { once: true });

      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (prefersReduced || !video) {
        scheduleFallback(prefersReduced ? 300 : 1200);
        return;
      }

      // Hard cap so a broken video never blocks the site.
      scheduleFallback(20000);

      video.muted = true;
      video.playsInline = true;
      video.setAttribute('muted', '');
      video.setAttribute('playsinline', '');

      video.addEventListener('ended', dismissPreloader, { once: true });
      video.addEventListener('error', () => scheduleFallback(600), { once: true });
      video.addEventListener('loadedmetadata', () => {
        const durationMs = Number.isFinite(video.duration) && video.duration > 0
          ? (video.duration * 1000) + 400
          : 8000;
        scheduleFallback(durationMs);
      }, { once: true });
      video.addEventListener('playing', () => {
        // Keep playing; duration-based dismiss handles the rest.
      }, { once: true });

      const tryPlay = () => {
        const playPromise = video.play();
        if (playPromise && typeof playPromise.catch === 'function') {
          // Do not dismiss immediately — browser may allow play after load.
          playPromise.catch(() => {
            setTimeout(() => {
              video.play().catch(() => { /* keep waiting for timeout/error */ });
            }, 250);
          });
        }
      };

      tryPlay();
      window.addEventListener('load', () => {
        if (!dismissed && video.paused) tryPlay();
      }, { once: true });
    })();


    /* =============================================
       API CONFIG
    ============================================= */
    const API_BASE_URL = (window.HOPE_SITE || window.BIVER_SITE)?.apiBase || '';
    const PROPERTIES_API = window.HOPE_SITE?.propertiesApi || window.BIVER_SITE?.propertiesApi || "api/properties.php";
    const LOCATIONS_API = window.HOPE_SITE?.locationsApi || window.BIVER_SITE?.locationsApi || "api/locations.php";

    function fetchWithTimeout(url, options = {}, timeoutMs = 8000) {
      const controller = new AbortController();
      const timer = setTimeout(() => controller.abort(), timeoutMs);
      return fetch(url, { ...options, signal: controller.signal }).finally(() => clearTimeout(timer));
    }

    const api = {
      getHeaders() {
        const headers = { "Content-Type": "application/json" };
        const token = localStorage.getItem('token');
        if (token) headers["Authorization"] = `Bearer ${token}`;
        return headers;
      },
      async get(endpoint) {
        const response = await fetchWithTimeout(`${API_BASE_URL}${endpoint}`, { headers: this.getHeaders() });
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
      },
      async post(endpoint, data = {}) {
        const response = await fetchWithTimeout(`${API_BASE_URL}${endpoint}`, { method: 'POST', headers: this.getHeaders(), body: JSON.stringify(data) });
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
      },
      async delete(endpoint) {
        const response = await fetchWithTimeout(`${API_BASE_URL}${endpoint}`, { method: 'DELETE', headers: this.getHeaders() });
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
      },
      async getCurrentUser() { return await this.get('/users/me'); },
      async getProperties(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const response = await fetchWithTimeout(`${PROPERTIES_API}${queryString ? '?' + queryString : ''}`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        if (data.success === false) throw new Error(data.message || 'Failed to load properties');
        return data;
      },
      async getFavorites() { return await this.get('/favorites'); },
      async addToFavorites(propertyId) { return await this.post(`/favorites/${propertyId}`); },
      async removeFromFavorites(propertyId) { return await this.delete(`/favorites/${propertyId}`); },
      async getCart() { return await this.get('/cart'); },
      async addToCart(propertyId) { return await this.post(`/cart/${propertyId}`); },
      async removeFromCart(propertyId) { return await this.delete(`/cart/${propertyId}`); },
    };

    const utils = {
      formatNumber(num) {
        if (!num) return '0';
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      },
      showToast(message, type = 'success') {
        const existing = document.querySelector('.toast-notification');
        if (existing) existing.remove();
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type === 'error' ? 'toast-error' : ''}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
          <div class="toast-content">
            <ion-icon name="${type === 'success' ? 'checkmark-circle' : 'alert-circle'}-outline"></ion-icon>
            <span>${message}</span>
          </div>
          <button class="toast-close" onclick="this.parentElement.remove()" aria-label="Close">
            <ion-icon name="close-outline"></ion-icon>
          </button>`;
        document.body.appendChild(toast);
        setTimeout(() => { if (toast.parentElement) toast.remove(); }, 3500);
      },
      isAuthenticated() { return !!localStorage.getItem('token'); },
      redirectToLogin() { window.location.href = (window.HOPE_SITE || window.BIVER_SITE)?.page('login') || 'login'; },
      truncate(text, length = 90) {
        if (!text) return '';
        return text.length > length ? text.substring(0, length) + '...' : text;
      },
      formatDate(dateString) {
        if (!dateString) return 'Recently';
        return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      },
      getInitials(name) {
        return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
      }
    };

    /* =============================================
       SCROLL REVEAL
    ============================================= */
    function initScrollReveal() {
      const revealEls = document.querySelectorAll('.reveal');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
      revealEls.forEach(el => observer.observe(el));
    }

    /* =============================================
       PRELOADER — prefers-reduced-motion awareness
       If user has reduced motion OS setting, skip the
       cinematic delay and dismiss immediately.
       (CSS already disables animations via media query.)
    ============================================= */
    function checkReducedMotionForPreloader() {
      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (prefersReduced) {
        // Skip the cinematic delay entirely — just fade out fast
        const preloader = document.getElementById('preloader');
        if (preloader) {
          setTimeout(() => {
            preloader.classList.add('hidden');
            document.body.style.overflow = '';
          }, 400);
        }
      }
    }

    /* =============================================
       PROPERTIES MODULE
    ============================================= */
    /* =============================================
       PROPERTIES MODULE
    ============================================= */
    let featuredProperties = [];
    let userFavorites = [];
    let userCart = [];
    let currentUser = null;

    const propertiesSkeleton = document.getElementById('propertiesSkeleton');
    const propertiesList     = document.getElementById('propertiesList');
    const propertiesError    = document.getElementById('propertiesError');
    const propertiesEmpty    = document.getElementById('propertiesEmpty');
    const headerCartCount    = document.getElementById('headerCartCount');

    function hideEl(el) { if (el) el.classList.add('u-hidden'); }
    function showEl(el) { if (el) el.classList.remove('u-hidden'); }

    async function checkUserAuthentication() {
      if (!utils.isAuthenticated()) return;
      try {
        const data = await api.getCurrentUser();
        currentUser = data.user || data;
        await Promise.all([loadUserFavorites(), loadUserCart()]);
      } catch (error) {
        localStorage.removeItem('token');
      }
    }

    async function loadUserFavorites() {
      if (!currentUser) return;
      try {
        const data = await api.getFavorites();
        userFavorites = data.favorites || data || [];
      } catch (e) {}
    }

    async function loadUserCart() {
      if (!currentUser) return;
      try {
        const data = await api.getCart();
        userCart = data.cart || data || [];
        updateCartCount();
      } catch (e) {}
    }

    function updateCartCount() {
      if (!headerCartCount) return;
      headerCartCount.textContent = userCart.length;
      if (userCart.length > 0) showEl(headerCartCount); else hideEl(headerCartCount);
    }

    function isPropertyFavorite(id) {
      return userFavorites.some(f => f._id === id || f.property === id || f.property?._id === id || f.propertyId === id);
    }

    function isPropertyInCart(id) {
      return userCart.some(i => i._id === id || i.property === id || i.property?._id === id || i.propertyId === id);
    }

    async function loadFeaturedProperties() {
      try {
        showEl(propertiesSkeleton);
        hideEl(propertiesList);
        hideEl(propertiesError);
        hideEl(propertiesEmpty);

        const data = await api.getProperties({ limit: 20 });
        if (data.properties && data.properties.length > 0) {
          featuredProperties = data.properties;
          displayProperties(featuredProperties);
        } else {
          showPropertiesEmpty('No Properties Available', 'There are currently no approved properties. Check back later.', false);
        }
      } catch (error) {
        console.error('Failed to load properties:', error.message);
        hideEl(propertiesSkeleton);
        hideEl(propertiesList);
        hideEl(propertiesEmpty);
        showEl(propertiesError);
      }
    }

    function displayProperties(properties) {
      hideEl(propertiesSkeleton);
      hideEl(propertiesError);
      hideEl(propertiesEmpty);
      propertiesList.innerHTML = '';
      properties.forEach(function (p, i) {
        propertiesList.appendChild(createPropertyCard(p, i));
      });
      showEl(propertiesList);
      if (typeof window.initProjectGallery === 'function') {
        window.initProjectGallery();
      }
    }

    function createPropertyCard(property, index) {
      const li = document.createElement('li');
      const cardIndex = String((index || 0) + 1).padStart(2, '0');
      const portfolio = window.HOPE_PORTFOLIO || {};
      const imageUrl = portfolio.propertyImage
        ? portfolio.propertyImage(property, index || 0)
        : (property.imageUrl || (property.images && property.images.length > 0 ? property.images[0] : ''));
      const badge = portfolio.categoryBadge
        ? portfolio.categoryBadge(property.type)
        : { label: property.type === 'rent' ? 'Interior Design' : 'Architecture', className: property.type === 'rent' ? 'interior' : 'architecture' };
      const badgeClass = badge.className === 'interior' ? 'green' : 'orange';
      const badgeText = badge.label;
      const hasVideo  = property.videoUrl || (property.videos && property.videos.length > 0);
      const videoUrl  = property.videoUrl || (hasVideo ? property.videos[0] : null);
      const isNew      = property.createdAt && (new Date() - new Date(property.createdAt)) < 7 * 24 * 60 * 60 * 1000;
      const isFavorite = isPropertyFavorite(property._id);
      const isInCart   = isPropertyInCart(property._id);

      const resolveMediaUrl = (url) => {
        if (portfolio.resolveMediaUrl) return portfolio.resolveMediaUrl(url, index || 0);
        if (!url) return '';
        if (url.startsWith('http') || url.startsWith('/')) return url;
        if (url.startsWith('assets/')) return './' + url;
        return url;
      };

      const finalImg = resolveMediaUrl(imageUrl);
      const finalVideo = videoUrl ? resolveMediaUrl(videoUrl) : null;

      let mediaHtml = hasVideo
        ? `<video src="${finalVideo}" poster="${finalImg}" controls preload="metadata" playsinline aria-label="Video tour of ${property.title}"></video>
           <div class="video-badge"><ion-icon name="videocam"></ion-icon><span>Video Tour</span></div>`
        : `<img src="${finalImg}" alt="${property.title}" class="w-100" loading="lazy" data-portfolio-fallback="${index || 0}">`;

      const siteCfg = window.HOPE_SITE || window.BIVER_SITE || {};
      const detailUrl = siteCfg.propertyDetail
        ? siteCfg.propertyDetail(property._id)
        : 'property-detail?id=' + encodeURIComponent(property._id);

      li.innerHTML = `
        <article class="project-card">
          <figure class="project-card__media card-banner">
            <a href="${detailUrl}" class="project-card__media-link">${mediaHtml}</a>
            <span class="project-card__index" aria-hidden="true">${cardIndex}</span>
            <div class="project-card__badges">
              ${isNew ? '<span class="card-badge new-badge project-card__badge">New</span>' : ''}
              <span class="card-badge ${badgeClass} project-card__badge">${badgeText}</span>
            </div>
            <figcaption class="project-card__overlay">
              <div class="project-card__top">
                <span class="project-card__location">
                  <ion-icon name="location-outline"></ion-icon>
                  ${property.location || 'Location TBD'}
                </span>
                ${property.images?.length ? `<span class="project-card__photos"><ion-icon name="camera-outline"></ion-icon>${property.images.length}</span>` : ''}
              </div>
              <h3 class="project-card__title h3 card-title"><a href="${detailUrl}">${property.title}</a></h3>
              <p class="project-card__excerpt card-text">${utils.truncate(property.description, 120)}</p>
              <ul class="project-card__specs card-list">
                <li class="card-item"><ion-icon name="bed-outline"></ion-icon><strong>${property.bedrooms || 1}</strong><span>Beds</span></li>
                <li class="card-item"><ion-icon name="water-outline"></ion-icon><strong>${property.bathrooms || 1}</strong><span>Baths</span></li>
                ${property.area ? `<li class="card-item"><ion-icon name="resize-outline"></ion-icon><strong>${utils.formatNumber(property.area)}</strong><span>ft²</span></li>` : ''}
              </ul>
              <div class="project-card__bar card-footer">
                <span class="project-card__category">${badgeText}</span>
                <a href="${detailUrl}" class="project-card__cta">View Project <ion-icon name="arrow-forward-outline"></ion-icon></a>
                <div class="project-card__actions card-footer-actions">
                  <button class="card-footer-actions-btn ${isFavorite ? 'favorited' : ''}"
                          onclick="toggleFavorite('${property._id}', this)"
                          aria-label="${isFavorite ? 'Remove from favorites' : 'Save project'}">
                    <ion-icon name="${isFavorite ? 'heart' : 'heart-outline'}"></ion-icon>
                  </button>
                  <button class="card-footer-actions-btn ${isInCart ? 'in-cart' : ''}"
                          onclick="addToCart('${property._id}', this)"
                          aria-label="${isInCart ? 'Remove from cart' : 'Add to cart'}">
                    <ion-icon name="${isInCart ? 'checkmark-circle' : 'add-circle-outline'}"></ion-icon>
                  </button>
                </div>
              </div>
            </figcaption>
          </figure>
        </article>`;
      if (portfolio.bindImageFallback) {
        portfolio.bindImageFallback(li, index || 0);
      }
      return li;
    }

    async function toggleFavorite(propertyId, button) {
      if (!utils.isAuthenticated()) {
        utils.showToast('Please login to save favorites', 'error');
        setTimeout(() => utils.redirectToLogin(), 1500);
        return;
      }
      try {
        const isFav = isPropertyFavorite(propertyId);
        if (isFav) {
          await api.removeFromFavorites(propertyId);
          button.querySelector('ion-icon').name = 'heart-outline';
          button.classList.remove('favorited');
          userFavorites = userFavorites.filter(f => f._id !== propertyId && f.property !== propertyId && f.propertyId !== propertyId);
          utils.showToast('Removed from favorites');
        } else {
          await api.addToFavorites(propertyId);
          button.querySelector('ion-icon').name = 'heart';
          button.classList.add('favorited');
          userFavorites.push({ propertyId });
          utils.showToast('Added to favorites');
        }
      } catch (e) { utils.showToast('Error updating favorites', 'error'); }
    }

    async function addToCart(propertyId, button) {
      if (!utils.isAuthenticated()) {
        utils.showToast('Please login to add to cart', 'error');
        setTimeout(() => utils.redirectToLogin(), 1500);
        return;
      }
      try {
        const inCart = isPropertyInCart(propertyId);
        if (inCart) {
          await api.removeFromCart(propertyId);
          button.querySelector('ion-icon').name = 'add-circle-outline';
          button.classList.remove('in-cart');
          userCart = userCart.filter(i => i._id !== propertyId && i.property !== propertyId && i.propertyId !== propertyId);
          updateCartCount();
          utils.showToast('Removed from cart');
        } else {
          await api.addToCart(propertyId);
          button.querySelector('ion-icon').name = 'checkmark-circle';
          button.classList.add('in-cart');
          userCart.push({ propertyId });
          updateCartCount();
          utils.showToast('Added to cart!');
        }
      } catch (e) { utils.showToast('Error updating cart', 'error'); }
    }

    function showPropertiesEmpty(title, message, isApproved = false) {
      hideEl(propertiesSkeleton);
      hideEl(propertiesError);
      showEl(propertiesEmpty);
      hideEl(propertiesList);
      propertiesEmpty.innerHTML = `
        <ion-icon name="${isApproved ? 'time-outline' : 'home-outline'}"></ion-icon>
        <h3>${title}</h3><p>${message}</p>
        <a href="${isApproved ? ((window.HOPE_SITE || window.BIVER_SITE)?.page('property') || 'property') : ((window.HOPE_SITE || window.BIVER_SITE)?.page('contact') || 'contact')}" class="btn u-mt-20">
          ${isApproved ? 'Browse All Projects' : 'Contact Us'}
        </a>`;
    }

    /* =============================================
       SERVICE AREAS MODULE
    ============================================= */
    async function loadServiceAreas() {
      const listEl = document.getElementById('areasList');
      const skeleton = document.getElementById('areasSkeleton');
      if (!listEl) return;

      function detectCity() {
        return 'lagos';
      }

      function hideSkeleton() {
        if (skeleton) skeleton.hidden = true;
      }

      try {
        if (skeleton) skeleton.hidden = false;
        const response = await fetchWithTimeout(LOCATIONS_API);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();
        const areas = data.areas || [];
        const section = data.section || {};

        if (section.intro) {
          const introEl = document.getElementById('areasIntro');
          if (introEl) introEl.textContent = section.intro;
        }
        if (section.ctaText) {
          const ctaTextEl = document.getElementById('areasCtaText');
          if (ctaTextEl) ctaTextEl.textContent = section.ctaText;
        }
        const ctaBtn = document.getElementById('areasCtaBtn');
        if (ctaBtn) {
          if (section.ctaLabel) ctaBtn.textContent = section.ctaLabel;
          if (section.ctaLink) ctaBtn.href = section.ctaLink;
        }

        hideSkeleton();

        if (!areas.length) {
          listEl.innerHTML = '<li><article class="city-card city-card--loading"><div class="city-card__body"><p class="city-card__text">Lagos districts coming soon.</p></div></article></li>';
          return;
        }

        listEl.innerHTML = areas.map((area, index) => {
          const delay = (index % 3) + 1;
          const city = detectCity();
          const cityLabel = 'Lagos';
          const meta1 = area.meta1Text ? `<span><ion-icon name="${area.meta1Icon || 'home-outline'}"></ion-icon>${area.meta1Text}</span>` : '';
          const meta2 = area.meta2Text ? `<span><ion-icon name="${area.meta2Icon || 'star-outline'}"></ion-icon>${area.meta2Text}</span>` : '';
          const cardNum = String(index + 1).padStart(2, '0');
          const link = area.linkUrl || ((window.HOPE_SITE || window.BIVER_SITE)?.page('property') || 'property');
          return `
            <li class="reveal reveal-delay-${delay}">
              <article class="city-card city-card--${city}">
                <div class="city-card__media">
                  <img src="${(window.HOPE_PORTFOLIO && window.HOPE_PORTFOLIO.resolveMediaUrl)
                    ? window.HOPE_PORTFOLIO.resolveMediaUrl(area.imageUrl, index + 20)
                    : (area.imageUrl || '')}" alt="${area.title} — ${cityLabel}" width="800" height="560" loading="lazy">
                  <div class="city-card__media-shade" aria-hidden="true"></div>
                  <span class="city-card__city">${cityLabel}</span>
                  ${area.tag ? `<span class="city-card__tag">${area.tag}</span>` : ''}
                  <span class="city-card__index" aria-hidden="true">${cardNum}</span>
                </div>
                <div class="city-card__body">
                  <h3 class="city-card__title">${area.title}</h3>
                  <p class="city-card__text">${area.description}</p>
                  <div class="city-card__meta">${meta1}${meta2}</div>
                  <a href="${link}" class="city-card__link">Explore district <ion-icon name="arrow-forward-outline"></ion-icon></a>
                </div>
              </article>
            </li>`;
        }).join('');
        initScrollReveal();
        if (typeof window.initCitiesGallery === 'function') {
          window.initCitiesGallery();
        }
      } catch (e) {
        console.warn('Service areas load failed:', e.message);
        hideSkeleton();
        listEl.innerHTML = '<li><article class="city-card city-card--loading"><div class="city-card__body"><p class="city-card__text">Unable to load districts right now.</p></div></article></li>';
      }
    }

    /* =============================================
       INIT — All features wired up here
    ============================================= */
    document.addEventListener('DOMContentLoaded', async () => {

      // Feature #5: Check reduced-motion FIRST — may fast-dismiss preloader
      checkReducedMotionForPreloader();

      initScrollReveal();

      if (window.HOPE_PORTFOLIO && window.HOPE_PORTFOLIO.bindImageFallback) {
        window.HOPE_PORTFOLIO.bindImageFallback(document);
      }

      // Data loading (do not block page render on slow external auth API)
      checkUserAuthentication();
      await Promise.all([
        loadFeaturedProperties(),
        loadServiceAreas()
      ]);
    });
  </script>
  <?php require __DIR__ . '/assets/includes/cookie-banner.php'; ?>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
  <script src="./assets/js/site-banners.js" defer></script>
</body>
</html>