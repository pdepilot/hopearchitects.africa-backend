'use strict';

/**
 * Premium luxury hero — GSAP + ScrollTrigger
 * Requires: window.HOPE_HERO.slides (injected from index.php)
 */
(function initLuxuryHero() {
  const heroSection = document.getElementById('home');
  if (!heroSection || typeof gsap === 'undefined') return;

  const slides = window.HOPE_HERO?.slides || window.BIVER_HERO?.slides || [];
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const SLIDE_INTERVAL = 7000;

  const els = {
    content: document.getElementById('heroContent'),
    media: document.getElementById('heroMedia'),
    bgSlides: heroSection.querySelectorAll('.bg-slide'),
    dots: heroSection.querySelectorAll('.slide-dots .dot'),
    eyebrowText: document.getElementById('heroEyebrowText'),
    titleLines: document.getElementById('heroTitleLines'),
    tagline: document.getElementById('heroTagline'),
    slideCurrent: document.getElementById('heroSlideCurrent'),
    accentLine: heroSection.querySelector('.hero-accent-line'),
    location: document.getElementById('heroLocation'),
    actions: document.getElementById('heroActions'),
    stats: document.getElementById('heroStats'),
    scrollIndicator: document.getElementById('heroScrollIndicator'),
    slideCounter: heroSection.querySelector('.hero-slide-counter'),
    video: document.getElementById('heroBgVideo'),
  };

  let currentSlide = 0;
  let slideTimer = null;
  let isTransitioning = false;
  let introPlayed = false;
  let booted = false;

  let scrollTriggers = [];

  gsap.registerPlugin(ScrollTrigger);

  function getHeroTextElements() {
    const titleLineEls = els.titleLines
      ? Array.from(els.titleLines.querySelectorAll('.hero-title-line-inner'))
      : [];
    return [
      els.location,
      els.eyebrowText?.parentElement,
      els.tagline,
      ...titleLineEls,
      ...(els.actions ? Array.from(els.actions.children) : []),
    ].filter(Boolean);
  }

  /** Restore hero text visibility when scrolling back into the hero. */
  function resetHeroTextVisibility() {
    if (!heroSection || prefersReduced) return;
    const rect = heroSection.getBoundingClientRect();
    const nearHeroTop = rect.top >= -30 && rect.top <= window.innerHeight * 0.4;
    if (!nearHeroTop) return;

    gsap.set(getHeroTextElements(), { clearProps: 'opacity,transform' });
    if (els.content) {
      gsap.set(els.content, { opacity: 1, y: 0 });
    }
  }

  /* ── Lazy-load background slides 2+ ── */
  function initLazyHeroSlides() {
    setTimeout(() => {
      heroSection.querySelectorAll('.bg-slide[data-bg]').forEach((slide) => {
        const url = slide.getAttribute('data-bg');
        if (!url) return;
        const img = new Image();
        img.onload = () => {
          slide.style.backgroundImage = `url('${url}')`;
          slide.removeAttribute('data-bg');
        };
        img.src = url;
      });
    }, 1500);
  }

  /* ── Split title HTML into animated lines ── */
  function buildTitleLines(html) {
    if (!els.titleLines) return [];
    els.titleLines.innerHTML = html;

    const raw = html.includes('<br')
      ? html.split(/<br\s*\/?>/i)
      : [html];

    els.titleLines.innerHTML = '';
    const lineEls = [];

    raw.forEach((chunk) => {
      const trimmed = chunk.trim();
      if (!trimmed) return;
      const line = document.createElement('span');
      line.className = 'hero-title-line';
      const inner = document.createElement('span');
      inner.className = 'hero-title-line-inner';
      inner.innerHTML = trimmed;
      line.appendChild(inner);
      els.titleLines.appendChild(line);
      lineEls.push(inner);
    });

    if (lineEls.length === 0) {
      const inner = document.createElement('span');
      inner.className = 'hero-title-line-inner';
      inner.id = 'heroTitleText';
      inner.innerHTML = html;
      const line = document.createElement('span');
      line.className = 'hero-title-line';
      line.appendChild(inner);
      els.titleLines.appendChild(line);
      lineEls.push(inner);
    } else {
      lineEls[0].id = 'heroTitleText';
    }

    return lineEls;
  }

  function padSlideNum(n) {
    return String(n + 1).padStart(2, '0');
  }

  function updateDots(index) {
    els.dots.forEach((dot, i) => {
      const active = i === index;
      dot.classList.toggle('active', active);
      dot.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    if (els.slideCurrent) els.slideCurrent.textContent = padSlideNum(index);
  }

  /* ── Cinematic page-load intro ── */
  function playIntro() {
    if (introPlayed) return;

    if (prefersReduced) {
      introPlayed = true;
      els.content?.classList.add('is-ready');
      buildTitleLines(slides[0]?.title || '');
      gsap.set([els.location, els.eyebrowText?.parentElement, els.tagline, els.actions, els.stats, els.scrollIndicator, els.slideCounter, els.accentLine], { opacity: 1, y: 0, scaleY: 1 });
      const activeBg = els.bgSlides[0];
      if (activeBg) {
        activeBg.classList.add('active', 'is-intro');
        gsap.set(activeBg, { scale: 1, opacity: 1 });
      }
      startSlideshow();
      return;
    }

    introPlayed = true;
    const activeBg = els.bgSlides[0];
    const titleLineEls = buildTitleLines(slides[0]?.title || els.titleLines?.innerHTML || '');

    gsap.set(activeBg, { scale: 1.1, opacity: 1 });
    activeBg?.classList.add('active');

    gsap.set(els.content, { opacity: 0 });
    gsap.set(titleLineEls, { yPercent: 110 });
    gsap.set([els.location, els.eyebrowText?.parentElement, els.tagline], { opacity: 0, y: 28 });
    gsap.set(els.actions?.children || [], { opacity: 0, y: 32 });
    gsap.set([els.stats, els.scrollIndicator, els.slideCounter, els.accentLine], { opacity: 0 });
    gsap.set(els.accentLine, { scaleY: 0 });

    const tl = gsap.timeline({
      onStart: () => els.content?.classList.add('is-ready'),
      defaults: { ease: 'power3.out' },
    });

    tl.to(els.content, { opacity: 1, duration: 0.6 }, 0)
      .to(activeBg, { scale: 1, duration: 2.4, ease: 'power2.out' }, 0)
      .to(els.location, { opacity: 1, y: 0, duration: 0.7 }, 0.35)
      .to(els.eyebrowText?.parentElement, { opacity: 1, y: 0, duration: 0.7 }, 0.5)
      .to(titleLineEls, { yPercent: 0, duration: 0.9, stagger: 0.14 }, 0.65)
      .to(els.tagline, { opacity: 1, y: 0, duration: 0.75 }, 0.95)
      .to(els.actions?.children || [], { opacity: 1, y: 0, duration: 0.65, stagger: 0.12 }, 1.15)
      .to(els.accentLine, { opacity: 1, scaleY: 1, duration: 1, ease: 'power2.inOut' }, 1.3)
      .to([els.stats, els.scrollIndicator, els.slideCounter], { opacity: 1, duration: 0.6, stagger: 0.08 }, 1.45)
      .add(() => startSlideshow())
      .add(() => resetHeroTextVisibility());

    activeBg?.classList.add('is-intro');
  }

  /* ── Slide content transition ── */
  function animateSlideContent(index, skipFadeOut) {
    const slide = slides[index];
    if (!slide) return Promise.resolve();

    const titleLineEls = buildTitleLines(slide.title || '');
    const targets = [
      els.eyebrowText?.parentElement,
      ...titleLineEls,
      els.tagline,
      ...(els.actions ? Array.from(els.actions.children) : []),
    ].filter(Boolean);

    if (prefersReduced) {
      if (els.eyebrowText) els.eyebrowText.textContent = slide.eyebrow || '';
      if (els.tagline) els.tagline.textContent = slide.tagline || '';
      return Promise.resolve();
    }

    return new Promise((resolve) => {
      const tl = gsap.timeline({ onComplete: resolve, defaults: { ease: 'power2.inOut' } });

      if (!skipFadeOut) {
        tl.to(targets, { opacity: 0, y: -18, duration: 0.45, stagger: 0.04 });
      }

      tl.add(() => {
        if (els.eyebrowText) els.eyebrowText.textContent = slide.eyebrow || '';
        if (els.tagline) els.tagline.textContent = slide.tagline || '';
        gsap.set(titleLineEls, { yPercent: 110, opacity: 1 });
        gsap.set(els.actions?.children || [], { y: 28, opacity: 0 });
      });

      tl.to(titleLineEls, { yPercent: 0, duration: 0.75, stagger: 0.12 })
        .to(els.eyebrowText?.parentElement, { opacity: 1, y: 0, duration: 0.5 }, '-=0.55')
        .to(els.tagline, { opacity: 1, y: 0, duration: 0.6 }, '-=0.35')
        .to(els.actions?.children || [], { opacity: 1, y: 0, duration: 0.55, stagger: 0.1 }, '-=0.3')
        .add(() => resetHeroTextVisibility());
    });
  }

  function syncBackgroundVideo(index) {
    if (!els.video) return;
    const slide = slides[index];
    const videoUrl = slide?.video || els.bgSlides[index]?.getAttribute('data-video');
    if (!videoUrl) {
      els.video.classList.remove('is-active');
      els.video.pause();
      els.video.removeAttribute('src');
      return;
    }
    if (els.video.getAttribute('src') !== videoUrl) {
      els.video.src = videoUrl;
      els.video.load();
    }
    els.video.play().catch(() => {});
    gsap.to(els.video, { opacity: 1, duration: 1.1, ease: 'power2.out' });
    els.video.classList.add('is-active');
  }

  /* ── Background crossfade with scale ── */
  function animateBackground(fromIndex, toIndex) {
    const fromSlide = els.bgSlides[fromIndex];
    const toSlide = els.bgSlides[toIndex];
    if (!toSlide) return Promise.resolve();

    if (prefersReduced) {
      fromSlide?.classList.remove('active');
      toSlide.classList.add('active');
      gsap.set(toSlide, { scale: 1, opacity: 1 });
      return Promise.resolve();
    }

    return new Promise((resolve) => {
      toSlide.classList.add('active');
      gsap.set(toSlide, { scale: 1.08, opacity: 0 });

      gsap.timeline({ onComplete: resolve })
        .to(fromSlide, { opacity: 0, scale: 1.04, duration: 1.1, ease: 'power2.inOut' }, 0)
        .to(toSlide, { opacity: 1, scale: 1, duration: 1.3, ease: 'power2.out' }, 0.15)
        .add(() => {
          fromSlide?.classList.remove('active', 'is-intro');
          gsap.set(fromSlide, { clearProps: 'opacity,transform' });
        });
    });
  }

  async function showSlide(index, { manual = false } = {}) {
    if (isTransitioning || index === currentSlide) return;
    if (index < 0 || index >= els.bgSlides.length) return;

    isTransitioning = true;
    if (manual) restartSlideshow();

    const prev = currentSlide;
    currentSlide = index;
    updateDots(index);

    await animateBackground(prev, index);
    syncBackgroundVideo(index);
    await animateSlideContent(index, prev === index);

    isTransitioning = false;
  }

  function nextSlide() {
    showSlide((currentSlide + 1) % els.bgSlides.length);
  }

  function startSlideshow() {
    clearInterval(slideTimer);
    if (els.bgSlides.length <= 1) return;
    slideTimer = setInterval(nextSlide, SLIDE_INTERVAL);
  }

  function restartSlideshow() {
    clearInterval(slideTimer);
    startSlideshow();
  }

  /* ── Scroll parallax & fade (opacity only on chrome — not hero copy) ── */
  function initScrollEffects() {
    if (prefersReduced) return;

    scrollTriggers.push(
      gsap.to(els.media, {
        yPercent: 18,
        ease: 'none',
        scrollTrigger: {
          trigger: heroSection,
          start: 'top top',
          end: 'bottom top',
          scrub: 0.5,
        },
      })
    );

    scrollTriggers.push(
      gsap.to(els.content, {
        y: -40,
        ease: 'none',
        scrollTrigger: {
          trigger: heroSection,
          start: 'top top',
          end: 'bottom top',
          scrub: 0.5,
        },
      })
    );

    scrollTriggers.push(
      ScrollTrigger.create({
        trigger: heroSection,
        start: 'top 40%',
        end: 'bottom top',
        onEnterBack: resetHeroTextVisibility,
      })
    );

    scrollTriggers.push(
      gsap.to([els.scrollIndicator, els.slideCounter], {
        opacity: 0,
        y: 20,
        ease: 'none',
        scrollTrigger: {
          trigger: heroSection,
          start: 'top top',
          end: '40% top',
          scrub: 0.5,
        },
      })
    );

  }

  /* ── Dot navigation ── */
  function bindDots() {
    els.dots.forEach((dot, i) => {
      dot.addEventListener('click', () => showSlide(i, { manual: true }));
    });
  }

  /* ── Boot ── */
  function boot() {
    if (booted) return;
    booted = true;
    initLazyHeroSlides();
    bindDots();
    updateDots(0);
    initScrollEffects();
  }

  window.addEventListener('hope:preloader-done', () => {
    playIntro();
    boot();
  }, { once: true });
  window.addEventListener('biver:preloader-done', () => {
    playIntro();
    boot();
  }, { once: true });

  /* Fallback if preloader already dismissed or missing */
  if (document.getElementById('preloader')?.classList.contains('hidden')) {
    playIntro();
    boot();
  } else {
    setTimeout(() => {
      if (!introPlayed) {
        playIntro();
        boot();
      }
    }, 4500);
  }
})();
