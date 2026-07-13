/**
 * Hope Architects — index sections (footer + project gallery)
 * Does not touch hero module in index.js
 */
(function () {
  'use strict';

  function initProjectGallery() {
    var rail = document.getElementById('propertiesList');
    var prev = document.getElementById('projectGalleryPrev');
    var next = document.getElementById('projectGalleryNext');
    var nav = document.getElementById('projectGalleryNav');
    var progress = document.getElementById('projectGalleryProgress');
    var progressBar = document.getElementById('projectGalleryProgressBar');

    if (!rail) return;

    function scrollAmount() {
      var first = rail.querySelector('li');
      if (!first) return rail.clientWidth * 0.85;
      var style = window.getComputedStyle(rail);
      var gap = parseFloat(style.gap) || 24;
      return first.offsetWidth + gap;
    }

    function updateGalleryUi() {
      if (rail.classList.contains('u-hidden')) return;

      var maxScroll = rail.scrollWidth - rail.clientWidth;
      var hasOverflow = maxScroll > 8;

      if (nav) nav.hidden = !hasOverflow;
      if (progress) progress.hidden = !hasOverflow;

      if (prev) prev.disabled = rail.scrollLeft <= 4;
      if (next) next.disabled = rail.scrollLeft >= maxScroll - 4;

      if (progressBar && hasOverflow) {
        var ratio = rail.clientWidth / rail.scrollWidth;
        var offset = maxScroll > 0 ? rail.scrollLeft / maxScroll : 0;
        progressBar.style.width = Math.max(ratio * 100, 12) + '%';
        progressBar.style.transform = 'translateX(' + (offset * ((1 / ratio) - 1) * 100) + '%)';
      }
    }

    if (prev) {
      prev.addEventListener('click', function () {
        rail.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
      });
    }
    if (next) {
      next.addEventListener('click', function () {
        rail.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
      });
    }

    rail.addEventListener('scroll', updateGalleryUi, { passive: true });
    window.addEventListener('resize', updateGalleryUi);

    updateGalleryUi();
  }

  window.initProjectGallery = initProjectGallery;

  function initCitiesGallery() {
    var rail = document.getElementById('areasList');
    var prev = document.getElementById('citiesGalleryPrev');
    var next = document.getElementById('citiesGalleryNext');
    var nav = document.getElementById('citiesGalleryNav');
    var progress = document.getElementById('citiesGalleryProgress');
    var progressBar = document.getElementById('citiesGalleryProgressBar');

    if (!rail) return;

    function scrollAmount() {
      var first = rail.querySelector('li');
      if (!first) return rail.clientWidth * 0.85;
      var style = window.getComputedStyle(rail);
      var gap = parseFloat(style.gap) || 24;
      return first.offsetWidth + gap;
    }

    function updateUi() {
      var maxScroll = rail.scrollWidth - rail.clientWidth;
      var hasOverflow = maxScroll > 8;

      if (nav) nav.hidden = !hasOverflow;
      if (progress) progress.hidden = !hasOverflow;
      if (prev) prev.disabled = rail.scrollLeft <= 4;
      if (next) next.disabled = rail.scrollLeft >= maxScroll - 4;

      if (progressBar && hasOverflow) {
        var ratio = rail.clientWidth / rail.scrollWidth;
        var offset = maxScroll > 0 ? rail.scrollLeft / maxScroll : 0;
        progressBar.style.width = Math.max(ratio * 100, 12) + '%';
        progressBar.style.transform = 'translateX(' + (offset * ((1 / ratio) - 1) * 100) + '%)';
      }
    }

    if (prev) {
      prev.onclick = function () {
        rail.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
      };
    }
    if (next) {
      next.onclick = function () {
        rail.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
      };
    }

    rail.addEventListener('scroll', updateUi, { passive: true });
    window.addEventListener('resize', updateUi);
    updateUi();
  }

  window.initCitiesGallery = initCitiesGallery;

  document.addEventListener('DOMContentLoaded', function () {
    initProjectGallery();
    initCitiesGallery();
  });
})();
