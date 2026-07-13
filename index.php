<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php
require_once __DIR__ . '/includes/HomepageContentService.php';
require_once __DIR__ . '/includes/site_helpers.php';
$homepageContent = HomepageContentService::get();
$heroSlidesData = $homepageContent['slides'] ?? [];
$heroStatsData = $homepageContent['stats'] ?? [];
$homeSections = $homepageContent['sections'] ?? [];
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
  $preloaderVideoPath = '';
  $preloaderVideoDir = __DIR__ . '/assets/videos';
  if (is_dir($preloaderVideoDir)) {
    $preloaderVideoFiles = glob($preloaderVideoDir . '/*.{mp4,webm,mov}', GLOB_BRACE) ?: [];
    if (!empty($preloaderVideoFiles)) {
      $preloaderVideoPath = './assets/videos/' . basename($preloaderVideoFiles[0]);
    }
  }
  ?>
  <div id="preloader" role="dialog" aria-modal="true" aria-label="Loading Hope Architects" aria-busy="true">
    <?php if ($preloaderVideoPath !== ''): ?>
    <video class="preloader-video" id="preloaderVideo" autoplay muted playsinline preload="auto" aria-hidden="true">
      <source src="<?= siteEscape($preloaderVideoPath) ?>" type="video/mp4">
    </video>
    <?php endif; ?>
    <div class="preloader-video-overlay" aria-hidden="true"></div>
    <div class="preloader-curtain" aria-hidden="true"></div>
  </div>

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
            <div class="bg-slide active bg-slide--1"<?php if (!empty($heroSlidesData[0]['bgImage'])): ?> style="background-image:url('<?= siteEscape($heroSlidesData[0]['bgImage']) ?>')"<?php endif; ?>></div>
            <?php foreach (array_slice($heroSlidesData, 1) as $slide): ?>
            <div class="bg-slide" data-bg="<?= siteEscape($slide['bgImage'] ?? '') ?>"<?php if (!empty($slide['video'])): ?> data-video="<?= siteEscape($slide['video']) ?>"<?php endif; ?>></div>
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
              <span id="heroLocationText">Lagos, Nigeria</span>
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
              <a href="<?= pageHref('property') ?>" class="hero-btn-primary">
                <span class="hero-btn-shine" aria-hidden="true"></span>
                <ion-icon name="home-outline"></ion-icon>
                <span>Explore Projects</span>
              </a>
              <a href="<?= pageHref('contact') ?>" class="hero-btn-secondary">
                <ion-icon name="call-outline"></ion-icon>
                <span>Request Consultation</span>
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
            <p class="section-subtitle"><?= siteEscape((string) ($homeSections['about']['subtitle'] ?? 'About Us')) ?></p>
            <h2 class="h2 section-title about-studio__title"><?= siteEscape((string) ($homeSections['about']['title'] ?? 'Architecture Built on Vision & Precision')) ?></h2>
            <p class="about-text about-studio__lead"><?= siteEscape((string) ($homeSections['about']['text'] ?? '')) ?></p>
            <ul class="about-studio__pillars">
              <li><ion-icon name="color-palette-outline"></ion-icon> Design &amp; Master Planning</li>
              <li><ion-icon name="construct-outline"></ion-icon> Construction Management</li>
              <li><ion-icon name="layers-outline"></ion-icon> Structural Engineering</li>
              <li><ion-icon name="shield-checkmark-outline"></ion-icon> Quality Delivery</li>
            </ul>
            <blockquote class="about-studio__quote reveal reveal-delay-2">
              Where vision meets precision — turning ambitious concepts into landmark buildings within your scope and budget.
            </blockquote>
            <a href="<?= pageHref('about') ?>" class="btn about-studio__cta reveal reveal-delay-3">Meet Our Team</a>
          </div>
          <div class="about-studio__visual reveal reveal-left">
            <figure class="about-studio__frame about-studio__frame--main">
              <img src="<?= siteEscape('./assets/images/picture1.jpg') ?>" alt="Modern architectural interior" loading="lazy" width="800" height="1000">
            </figure>
            <figure class="about-studio__frame about-studio__frame--float">
              <img src="<?= siteEscape('./assets/images/download.jpg') ?>" alt="Luxury building design" loading="lazy" width="600" height="600">
            </figure>
            <div class="about-studio__stat-ring" aria-hidden="true">
              <span class="about-studio__stat-num">15+</span>
              <span class="about-studio__stat-label">Years Building</span>
            </div>
          </div>
          <aside class="about-studio__metrics reveal reveal-delay-2" aria-label="Studio highlights">
            <div class="about-studio__metric"><strong>200+</strong><span>Projects</span></div>
            <div class="about-studio__metric"><strong>50+</strong><span>Experts</span></div>
            <div class="about-studio__metric"><strong>100%</strong><span>Commitment</span></div>
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
              <img src="./assets/images/why-us-leadership.png" alt="Hope Architects leadership — professional team representative" class="why-us-img-main why-asymmetric__photo" loading="lazy">
              <span class="why-asymmetric__portrait-accent" aria-hidden="true"></span>
            </figure>
            <div class="why-us-badge why-asymmetric__badge">
              <div class="num">10+</div>
              <div class="txt">Years of Excellence</div>
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
            <p class="section-subtitle reveal section-subtitle--left">Why Choose Us</p>
            <h2 class="h2 section-title reveal reveal-delay-1 section-title--left"><?= $homeSections['whyUs']['title'] ?? 'We Turn Vision Into<br><em>Landmark Architecture</em>' ?></h2>
            <p class="why-us-text reveal reveal-delay-2"><?= siteEscape((string) ($homeSections['whyUs']['text'] ?? 'With over a decade of experience across Nigeria, Hope Architects stands apart through design excellence, structural integrity, and a relentless commitment to delivering on promise.')) ?></p>
            <div class="why-grid why-asymmetric__grid reveal reveal-delay-3">
              <div class="why-item">
                <div class="why-item-icon"><ion-icon name="shield-checkmark-outline"></ion-icon></div>
                <div>
                  <p class="why-item-title">Structural Integrity</p>
                  <p class="why-item-text">Every build meets rigorous engineering and safety standards.</p>
                </div>
              </div>
              <div class="why-item">
                <div class="why-item-icon"><ion-icon name="ribbon-outline"></ion-icon></div>
                <div>
                  <p class="why-item-title">Award-Winning Design</p>
                  <p class="why-item-text">Recognized excellence in architecture and construction.</p>
                </div>
              </div>
              <div class="why-item">
                <div class="why-item-icon"><ion-icon name="people-outline"></ion-icon></div>
                <div>
                  <p class="why-item-title">Expert Team</p>
                  <p class="why-item-text">Architects, engineers, and builders under one roof.</p>
                </div>
              </div>
              <div class="why-item">
                <div class="why-item-icon"><ion-icon name="headset-outline"></ion-icon></div>
                <div>
                  <p class="why-item-title">Dedicated Support</p>
                  <p class="why-item-text">Transparent communication from concept to handover.</p>
                </div>
              </div>
            </div>
            <a href="<?= pageHref('about') ?>" class="btn reveal reveal-delay-4">Learn More About Us</a>
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
            <p class="section-subtitle"><?= siteEscape((string) ($homeSections['process']['subtitle'] ?? 'Our Process')) ?></p>
            <h2 class="h2 section-title"><?= siteEscape((string) ($homeSections['process']['title'] ?? 'Your Project in 4 Clear Phases')) ?></h2>
          </header>
          <ol class="process-timeline__track">
            <li class="process-item process-timeline__step reveal reveal-delay-1">
              <span class="process-timeline__num">01</span>
              <div class="process-icon"><ion-icon name="chatbubbles-outline"></ion-icon></div>
              <h3 class="process-title">Consultation</h3>
              <p class="process-text">We listen to your vision, assess the site, and define scope, budget, and timeline.</p>
            </li>
            <li class="process-item process-timeline__step reveal reveal-delay-2">
              <span class="process-timeline__num">02</span>
              <div class="process-icon"><ion-icon name="color-palette-outline"></ion-icon></div>
              <h3 class="process-title">Design</h3>
              <p class="process-text">Concept sketches evolve into detailed architectural plans and 3D visualizations.</p>
            </li>
            <li class="process-item process-timeline__step reveal reveal-delay-3">
              <span class="process-timeline__num">03</span>
              <div class="process-icon"><ion-icon name="document-text-outline"></ion-icon></div>
              <h3 class="process-title">Approvals</h3>
              <p class="process-text">We coordinate permits, structural reviews, and regulatory compliance.</p>
            </li>
            <li class="process-item process-timeline__step reveal reveal-delay-4">
              <span class="process-timeline__num">04</span>
              <div class="process-icon"><ion-icon name="construct-outline"></ion-icon></div>
              <h3 class="process-title">Build &amp; Handover</h3>
              <p class="process-text">Construction managed to the highest standard — delivered on time, ready to occupy.</p>
            </li>
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
              <p class="section-subtitle">What We Deliver</p>
              <h2 class="h2 section-title deliver-showcase__title">Built for<br>Every Scale</h2>
              <p class="deliver-showcase__lead">From intimate renovations to landmark developments — full-spectrum architecture and construction under one roof.</p>
              <a href="<?= pageHref('services') ?>" class="btn deliver-showcase__cta">Explore All Services</a>
              <div class="deliver-showcase__stat" aria-hidden="true">
                <strong>8+</strong>
                <span>Core disciplines</span>
              </div>
            </aside>

            <ul class="deliver-showcase__grid">
              <li class="reveal reveal-delay-1">
                <a href="<?= pageHref('services') ?>" class="deliver-tile">
                  <span class="deliver-tile__num">01</span>
                  <span class="deliver-tile__icon"><ion-icon name="home-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">Residential</h3>
                    <p class="deliver-tile__desc">Custom homes and estates designed for how you live.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <li class="reveal reveal-delay-2">
                <a href="<?= pageHref('services') ?>" class="deliver-tile">
                  <span class="deliver-tile__num">02</span>
                  <span class="deliver-tile__icon"><ion-icon name="business-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">Commercial</h3>
                    <p class="deliver-tile__desc">Offices and mixed-use spaces that inspire teams.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <li class="reveal reveal-delay-3">
                <a href="<?= pageHref('services') ?>" class="deliver-tile">
                  <span class="deliver-tile__num">03</span>
                  <span class="deliver-tile__icon"><ion-icon name="leaf-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">Sustainable Design</h3>
                    <p class="deliver-tile__desc">Green building and energy-efficient materials.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <li class="reveal reveal-delay-4">
                <a href="<?= pageHref('services') ?>" class="deliver-tile">
                  <span class="deliver-tile__num">04</span>
                  <span class="deliver-tile__icon"><ion-icon name="cube-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">3D Visualization</h3>
                    <p class="deliver-tile__desc">Photorealistic renders before ground is broken.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <li class="reveal reveal-delay-1">
                <a href="<?= pageHref('services') ?>" class="deliver-tile">
                  <span class="deliver-tile__num">05</span>
                  <span class="deliver-tile__icon"><ion-icon name="git-network-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">Structural Engineering</h3>
                    <p class="deliver-tile__desc">Safe, durable, code-compliant structures.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <li class="reveal reveal-delay-2">
                <a href="<?= pageHref('services') ?>" class="deliver-tile">
                  <span class="deliver-tile__num">06</span>
                  <span class="deliver-tile__icon"><ion-icon name="map-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">Site Planning</h3>
                    <p class="deliver-tile__desc">Land use, zoning, and master plan development.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <li class="reveal reveal-delay-3">
                <a href="<?= pageHref('services') ?>" class="deliver-tile">
                  <span class="deliver-tile__num">07</span>
                  <span class="deliver-tile__icon"><ion-icon name="hammer-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">Renovation</h3>
                    <p class="deliver-tile__desc">Transform existing structures with modern upgrades.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
              <li class="reveal reveal-delay-4">
                <a href="<?= pageHref('contact') ?>" class="deliver-tile deliver-tile--cta">
                  <span class="deliver-tile__num">08</span>
                  <span class="deliver-tile__icon"><ion-icon name="call-outline"></ion-icon></span>
                  <div class="deliver-tile__body">
                    <h3 class="deliver-tile__title">Start a Project</h3>
                    <p class="deliver-tile__desc">Let's discuss your vision — book a consultation today.</p>
                  </div>
                  <span class="deliver-tile__arrow"><ion-icon name="arrow-forward-outline"></ion-icon></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- =============================================
           TESTIMONIALS — Editorial
      ============================================= -->
      <section class="testimonial testimonial--editorial" id="testimonial" aria-label="Client testimonials">
        <div class="testimonials-editorial">
          <header class="testimonials-editorial__header reveal">
            <span class="section-eyebrow">07 — Voices</span>
            <p class="section-subtitle">Testimonials</p>
            <h2 class="h2 section-title">What Our Clients Say</h2>
          </header>

          <div id="testimonialsSkeleton" class="testimonials-skeleton">
            <div class="skeleton-grid">
              <div class="skeleton-testimonial"></div>
              <div class="skeleton-testimonial"></div>
              <div class="skeleton-testimonial"></div>
            </div>
          </div>
          <div id="testimonialsError" class="testimonials-error u-hidden" role="alert">
            <ion-icon name="alert-circle-outline"></ion-icon>
            <h3>Failed to load testimonials</h3>
            <p>Unable to fetch client testimonials. Please try again.</p>
            <button onclick="loadTestimonials()" class="btn u-mt-20">Try Again</button>
          </div>
          <div id="testimonialsEmpty" class="testimonials-empty u-hidden">
            <ion-icon name="chatbubbles-outline"></ion-icon>
            <h3>No Testimonials Yet</h3>
            <p>Check back soon for client experiences!</p>
          </div>

          <div id="testimonialsSlider" class="testimonial-slider testimonials-editorial__slider u-hidden">
            <div class="testimonial-track">
              <ul id="testimonialsList" class="testimonial-list"></ul>
            </div>
            <div class="slider-nav testimonials-editorial__nav">
              <button class="slider-prev" id="testimonialPrev" disabled aria-label="Previous">
                <ion-icon name="chevron-back-outline"></ion-icon>
              </button>
              <button class="slider-next" id="testimonialNext" aria-label="Next">
                <ion-icon name="chevron-forward-outline"></ion-icon>
              </button>
            </div>
            <div class="slider-dots testimonials-editorial__dots" id="testimonialDots" role="tablist"></div>
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
              <p class="section-subtitle">Local Expertise</p>
              <h2 class="h2 section-title cities-showcase__title">Building Across<br><em>Lagos</em></h2>
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

      if (!preloader) return;

      function notifySiteReady() {
        window.dispatchEvent(new CustomEvent('hope:preloader-done'));
        window.dispatchEvent(new CustomEvent('biver:preloader-done'));
      }

      let fallbackTimer = null;

      function scheduleFallback() {
        clearTimeout(fallbackTimer);
        const durationMs = Number.isFinite(video.duration) && video.duration > 0
          ? (video.duration * 1000) + 400
          : 8000;
        fallbackTimer = setTimeout(dismissPreloader, durationMs);
      }

      function dismissPreloader() {
        if (preloader.classList.contains('hidden')) return;
        if (fallbackTimer) clearTimeout(fallbackTimer);
        preloader.classList.add('exiting');
        setTimeout(() => {
          preloader.classList.add('hidden');
          document.body.style.overflow = '';
          if (video) {
            video.pause();
            video.removeAttribute('src');
            video.load();
          }
          notifySiteReady();
        }, 700);
      }

      document.body.style.overflow = 'hidden';

      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (prefersReduced || !video) {
        setTimeout(dismissPreloader, prefersReduced ? 300 : 800);
        return;
      }

      fallbackTimer = setTimeout(dismissPreloader, 10000);

      video.addEventListener('ended', dismissPreloader, { once: true });
      video.addEventListener('error', dismissPreloader, { once: true });
      video.addEventListener('loadedmetadata', scheduleFallback, { once: true });

      const playPromise = video.play();
      if (playPromise && typeof playPromise.catch === 'function') {
        playPromise.catch(() => dismissPreloader());
      }

      window.addEventListener('load', () => {
        if (video.paused) {
          video.play().catch(() => dismissPreloader());
        }
      }, { once: true });
    })();


    /* =============================================
       API CONFIG
    ============================================= */
    const API_BASE_URL = (window.HOPE_SITE || window.BIVER_SITE)?.apiBase || '';
    const PROPERTIES_API = window.HOPE_SITE?.propertiesApi || window.BIVER_SITE?.propertiesApi || "api/properties.php";
    const TESTIMONIALS_API = window.HOPE_SITE?.testimonialsApi || window.BIVER_SITE?.testimonialsApi || "api/testimonials.php";
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
      async getTestimonials() {
        const response = await fetchWithTimeout(TESTIMONIALS_API);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        if (data.success === false) throw new Error(data.message || 'Failed to load testimonials');
        return data;
      },
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
      const priceDisplay = property.type === 'rent'
        ? `₦${utils.formatNumber(property.price)}/Month`
        : `₦${utils.formatNumber(property.price)}`;
      const imageUrl = property.imageUrl || (property.images && property.images.length > 0
        ? property.images[0]
        : 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop');
      const hasVideo  = property.videoUrl || (property.videos && property.videos.length > 0);
      const videoUrl  = property.videoUrl || (hasVideo ? property.videos[0] : null);
      const badgeClass = property.type === 'rent' ? 'green' : 'orange';
      const badgeText  = property.type === 'rent' ? 'For Rent' : 'For Sale';
      const isNew      = property.createdAt && (new Date() - new Date(property.createdAt)) < 7 * 24 * 60 * 60 * 1000;
      const isFavorite = isPropertyFavorite(property._id);
      const isInCart   = isPropertyInCart(property._id);

      const resolveMediaUrl = (url) => {
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
        : `<img src="${finalImg}" alt="${property.title}" class="w-100" loading="lazy">`;

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
                <div class="project-card__price card-price"><strong>${priceDisplay}</strong></div>
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
       TESTIMONIALS MODULE
    ============================================= */
    let testimonials = [];
    let currentTestimonialSlide = 0;
    let testimonialsPerSlide = 3;

    async function loadTestimonials() {
      const skeleton = document.getElementById('testimonialsSkeleton');
      const error    = document.getElementById('testimonialsError');
      const empty    = document.getElementById('testimonialsEmpty');
      const slider   = document.getElementById('testimonialsSlider');
      try {
        showEl(skeleton);
        hideEl(error);
        hideEl(empty);
        hideEl(slider);
        const data = await api.getTestimonials();
        const items = data.data || [];
        if (items.length > 0) {
          testimonials = items;
          displayTestimonials(testimonials);
        } else {
          hideEl(skeleton);
          showEl(empty);
        }
      } catch (e) {
        console.warn('Testimonials load failed:', e.message);
        hideEl(skeleton);
        showEl(error);
      }
    }

    function displayTestimonials(testimonials) {
      const skeleton = document.getElementById('testimonialsSkeleton');
      const slider   = document.getElementById('testimonialsSlider');
      const listEl   = document.getElementById('testimonialsList');
      hideEl(skeleton);
      listEl.innerHTML = '';
      testimonials.forEach(t => listEl.appendChild(createTestimonialCard(t)));
      showEl(slider);
      generateTestimonialDots();
      updateTestimonialSlider();

      const prev = document.getElementById('testimonialPrev');
      const next = document.getElementById('testimonialNext');
      prev.onclick = () => {
        if (currentTestimonialSlide > 0) { currentTestimonialSlide--; updateTestimonialSlider(); }
      };
      next.onclick = () => {
        const max = Math.ceil(testimonials.length / testimonialsPerSlide) - 1;
        if (currentTestimonialSlide < max) { currentTestimonialSlide++; updateTestimonialSlider(); }
      };
    }

    function createTestimonialCard(testimonial) {
      const li = document.createElement('li');
      li.className = 'testimonial-item';
      const initials = testimonial.initials || utils.getInitials(testimonial.name);
      const rating = Math.max(1, Math.min(5, Number(testimonial.rating) || 5));
      const starsHtml = Array.from({ length: 5 }, (_, i) =>
        `<ion-icon name="${i < rating ? 'star' : 'star-outline'}"></ion-icon>`
      ).join('');
      const imageSrc = testimonial.image || '';
      li.innerHTML = `
        <div class="testimonial-card">
          <div class="testimonial-quote-icon"><ion-icon name="chatbubble-ellipses-outline"></ion-icon></div>
          <div class="testimonial-avatar ${!imageSrc ? 'no-image' : ''}">
            ${imageSrc
              ? `<img src="${imageSrc.startsWith('http') ? imageSrc : './' + imageSrc.replace(/^\.?\//, '')}" alt="${testimonial.name}" loading="lazy">`
              : `<span>${initials}</span>`}
          </div>
          <div class="testimonial-stars">${starsHtml}</div>
          <div class="testimonial-content">
            <p class="testimonial-text">${testimonial.message}</p>
          </div>
          <div class="testimonial-author">
            <h4>${testimonial.name}</h4>
            <p>${testimonial.roleLabel || 'Happy Client'}</p>
          </div>
        </div>`;
      return li;
    }

    function generateTestimonialDots() {
      const dotsEl = document.getElementById('testimonialDots');
      const total  = Math.ceil(testimonials.length / testimonialsPerSlide);
      dotsEl.innerHTML = '';
      for (let i = 0; i < total; i++) {
        const dot = document.createElement('button');
        dot.className = `slider-dot ${i === 0 ? 'active' : ''}`;
        dot.setAttribute('aria-label', `Slide ${i + 1}`);
        dot.onclick = () => { currentTestimonialSlide = i; updateTestimonialSlider(); };
        dotsEl.appendChild(dot);
      }
    }

    function updateTestimonialSlider() {
      const listEl = document.getElementById('testimonialsList');
      const prev   = document.getElementById('testimonialPrev');
      const next   = document.getElementById('testimonialNext');
      const total  = Math.ceil(testimonials.length / testimonialsPerSlide);
      listEl.style.transform = `translateX(-${currentTestimonialSlide * 100}%)`;
      prev.disabled = currentTestimonialSlide === 0;
      next.disabled = currentTestimonialSlide === total - 1;
      document.querySelectorAll('.slider-dot').forEach((d, i) => {
        d.classList.toggle('active', i === currentTestimonialSlide);
      });
    }

    function updateTestimonialsPerSlide() {
      testimonialsPerSlide   = window.innerWidth >= 992 ? 3 : window.innerWidth >= 768 ? 2 : 1;
      currentTestimonialSlide = 0;
      if (testimonials.length > 0) { generateTestimonialDots(); updateTestimonialSlider(); }
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
                  <img src="${area.imageUrl}" alt="${area.title} — ${cityLabel}" width="800" height="560" loading="lazy">
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

      // Responsive testimonials
      updateTestimonialsPerSlide();
      window.addEventListener('resize', updateTestimonialsPerSlide);

      // Data loading (do not block page render on slow external auth API)
      checkUserAuthentication();
      await Promise.all([
        loadFeaturedProperties(),
        loadTestimonials(),
        loadServiceAreas()
      ]);
    });
  </script>
  <?php require __DIR__ . '/assets/includes/cookie-banner.php'; ?>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
  <script src="./assets/js/site-banners.js" defer></script>
</body>
</html>