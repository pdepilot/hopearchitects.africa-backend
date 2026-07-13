/**
 * Hope Architects — shared monolith footer (clock + parallax)
 */
(function () {
  'use strict';

  function updateFooterClock() {
    var el = document.getElementById('footerLocalTime');
    if (!el) return;
    var now = new Date();
    var opts = { hour: '2-digit', minute: '2-digit', hour12: true };
    el.textContent = 'Lagos · ' + now.toLocaleTimeString('en-NG', opts);
  }

  function initFooterYear() {
    var el = document.getElementById('footerYear');
    if (el) el.textContent = String(new Date().getFullYear());
  }

  function initFooterParallax() {
    var footer = document.querySelector('.footer--monolith');
    if (!footer || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var watermark = footer.querySelector('.footer-monolith__watermark');
    if (!watermark) return;

    var ticking = false;
    window.addEventListener('scroll', function () {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(function () {
        var rect = footer.getBoundingClientRect();
        var vh = window.innerHeight;
        if (rect.top < vh && rect.bottom > 0) {
          var progress = 1 - rect.top / vh;
          watermark.style.transform = 'translateX(-50%) translateY(' + (progress * 24) + 'px)';
        }
        ticking = false;
      });
    }, { passive: true });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initFooterYear();
    updateFooterClock();
    setInterval(updateFooterClock, 60000);
    initFooterParallax();
  });
})();
