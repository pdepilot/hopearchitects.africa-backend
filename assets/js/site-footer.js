/**
 * Hope Architects — shared monolith footer (clock + watermark align)
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

  function positionFooterWatermark() {
    var footer = document.querySelector('.footer--monolith');
    if (!footer) return;

    var watermark = footer.querySelector('.footer-monolith__watermark');
    var social = footer.querySelector('.footer-monolith__social');
    if (!watermark || !social) return;

    var footerRect = footer.getBoundingClientRect();
    var socialRect = social.getBoundingClientRect();
    var top = socialRect.top - footerRect.top + (socialRect.height / 2);

    watermark.style.top = top + 'px';
    watermark.style.bottom = 'auto';
    watermark.style.left = '50%';
    watermark.style.transform = 'translate(-50%, -50%)';
  }

  function initFooterWatermark() {
    positionFooterWatermark();
    window.addEventListener('resize', positionFooterWatermark);
    window.addEventListener('load', positionFooterWatermark);

    if (typeof ResizeObserver !== 'undefined') {
      var footer = document.querySelector('.footer--monolith');
      if (footer) {
        var ro = new ResizeObserver(positionFooterWatermark);
        ro.observe(footer);
      }
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    initFooterYear();
    updateFooterClock();
    setInterval(updateFooterClock, 60000);
    initFooterWatermark();
  });
})();
