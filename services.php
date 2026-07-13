<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php
require_once __DIR__ . '/includes/PageContentService.php';
require_once __DIR__ . '/includes/site_helpers.php';
$servicesPage = PageContentService::getPage('services');
$svcHero = $servicesPage['hero'] ?? [];
$svcShowcase = $servicesPage['showcase'] ?? [];
$svcCards = $servicesPage['cards'] ?? [];
$svcCta = $servicesPage['cta'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Hope Architects — architecture and construction services in Nigeria. Architectural design, project management, civil construction, and building consultancy.">
  <meta name="keywords" content="architecture services Nigeria, construction company, building design, project management, Hope Architects">
  <meta name="author" content="Hope Architects">
  <title>Our Services | Hope Architects — Architecture & Construction</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/services.css">
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
</head>
<body>

  <?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

  <main id="main-content">
    <!-- Hero Section -->
    <section class="services-hero">
      <div class="hero-shape"></div>
      <div class="container">
        <div class="hero-badge"><div class="line"></div><span><?= siteEscape((string) ($svcHero['badge'] ?? 'Premium Services')) ?></span></div>
        <h1><?= $svcHero['title'] ?? 'Comprehensive <span class="gold-accent">Real Estate</span> Solutions' ?></h1>
        <p class="hero-description"><?= siteEscape((string) ($svcHero['description'] ?? '')) ?></p>
        <div class="hero-stats">
          <?php foreach (($svcHero['stats'] ?? []) as $stat): ?>
          <div class="hero-stat"><div class="number"><?= siteEscape((string) ($stat['num'] ?? '')) ?></div><div class="label"><?= siteEscape((string) ($stat['label'] ?? '')) ?></div></div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Services Grid -->
    <section class="services-showcase">
      <div class="container">
        <div class="section-eyebrow reveal"><div class="line"></div><span><?= siteEscape((string) ($svcShowcase['eyebrow'] ?? 'What We Offer')) ?></span><div class="line"></div></div>
        <h2 class="section-title reveal reveal-delay-1"><?= siteEscape((string) ($svcShowcase['title'] ?? 'Tailored Services for Every Need')) ?></h2>
        <div class="service-grid">
          <?php foreach ($svcCards as $i => $card): ?>
          <?php
            $revealClass = 'reveal';
            if ($i % 3 === 0) $revealClass .= ' reveal-left';
            elseif ($i % 3 === 1) $revealClass .= ' reveal-up';
            else $revealClass .= ' reveal-right';
            if ($i > 2) $revealClass .= ' reveal-delay-1';
          ?>
          <div class="service-card-premium <?= $revealClass ?>">
            <div class="service-icon-wrapper"><div class="service-icon"><ion-icon name="<?= siteEscape((string) ($card['icon'] ?? 'home-outline')) ?>"></ion-icon></div></div>
            <div class="service-content">
              <h3 class="service-title"><?= siteEscape((string) ($card['title'] ?? '')) ?></h3>
              <p class="service-description"><?= siteEscape((string) ($card['description'] ?? '')) ?></p>
              <ul class="service-features">
                <?php foreach (($card['features'] ?? []) as $feature): ?>
                <li><ion-icon name="checkmark-circle-outline"></ion-icon> <?= siteEscape((string) $feature) ?></li>
                <?php endforeach; ?>
              </ul>
              <a href="<?= pageHref((string) ($card['linkPage'] ?? 'contact')) ?>" class="service-link"><?= siteEscape((string) ($card['linkLabel'] ?? 'Learn More')) ?> <ion-icon name="arrow-forward-outline"></ion-icon></a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- How It Works Process -->
    <section class="process-section">
      <div class="container">
        <div class="section-eyebrow reveal"><div class="line"></div><span>Simple Process</span><div class="line"></div></div>
        <h2 class="section-title reveal reveal-delay-1">How We Deliver Excellence</h2>
        <div class="process-steps">
          <div class="process-step reveal"><div class="step-number">01</div><h3 class="step-title">Consultation</h3><p class="step-text">Understanding your needs, goals, and budget to create a personalized strategy.</p></div>
          <div class="process-step reveal reveal-delay-1"><div class="step-number">02</div><h3 class="step-title">Property Search</h3><p class="step-text">Curated selection of properties matching your exact requirements.</p></div>
          <div class="process-step reveal reveal-delay-2"><div class="step-number">03</div><h3 class="step-title">Verification</h3><p class="step-text">Thorough due diligence, legal checks, and property inspection.</p></div>
          <div class="process-step reveal reveal-delay-3"><div class="step-number">04</div><h3 class="step-title">Closing</h3><p class="step-text">Seamless transaction with full documentation and support.</p></div>
        </div>
      </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose">
      <div class="container">
        <div class="section-eyebrow reveal"><div class="line"></div><span>Why Choose Us</span><div class="line"></div></div>
        <h2 class="section-title reveal reveal-delay-1">The Hope Architects Advantage</h2>
        <div class="benefits-grid">
          <div class="benefit-card reveal"><div class="benefit-icon"><ion-icon name="shield-checkmark-outline"></ion-icon></div><h3 class="benefit-title">100% Integrity</h3><p class="benefit-text">Built on a foundation of transparency and honest dealings.</p></div>
          <div class="benefit-card reveal reveal-delay-1"><div class="benefit-icon"><ion-icon name="speedometer-outline"></ion-icon></div><h3 class="benefit-title">Fast & Efficient</h3><p class="benefit-text">Streamlined processes that save you time and stress.</p></div>
          <div class="benefit-card reveal reveal-delay-2"><div class="benefit-icon"><ion-icon name="people-outline"></ion-icon></div><h3 class="benefit-title">Expert Team</h3><p class="benefit-text">Seasoned professionals with deep local market knowledge.</p></div>
          <div class="benefit-card reveal reveal-delay-3"><div class="benefit-icon"><ion-icon name="headset-outline"></ion-icon></div><h3 class="benefit-title">24/7 Support</h3><p class="benefit-text">Always available to answer questions and provide guidance.</p></div>
        </div>
      </div>
    </section>

    <!-- CTA Banner -->
    <section class="cta-banner">
      <div class="container">
        <h2 class="reveal">Ready to Start Your Property Journey?</h2>
        <p class="reveal reveal-delay-1">Let's turn your building vision into reality. Contact our expert team today.</p>
        <a href="<?= pageHref('contact') ?>" class="cta-button reveal reveal-delay-2"><ion-icon name="chatbubble-outline"></ion-icon>Schedule a Consultation</a>
      </div>
    </section>
  </main>

  <?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

<script src="./assets/js/site-header.js" defer></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
// Scroll to top
    // Scroll reveal
    const observer = new IntersectionObserver((entries) => { entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } }); }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    // Delay classes
    const delays = { 'reveal-delay-1': 100, 'reveal-delay-2': 200, 'reveal-delay-3': 300 };
    Object.keys(delays).forEach(cls => { document.querySelectorAll(`.${cls}`).forEach((el, i) => el.style.transitionDelay = `${delays[cls] + i * 50}ms`); });
  </script>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
</body>
</html>