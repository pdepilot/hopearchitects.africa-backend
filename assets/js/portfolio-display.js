(function () {
  'use strict';

  const catalog = (window.HOPE_SITE && window.HOPE_SITE.portfolioImages) || [];

  function encodePath(url) {
    if (!url || /^https?:\/\//i.test(url) || url.startsWith('data:')) {
      return url;
    }
    return String(url)
      .split('/')
      .map(function (segment) {
        if (!segment || segment === '.') {
          return segment;
        }
        try {
          return encodeURIComponent(decodeURIComponent(segment));
        } catch (err) {
          return encodeURIComponent(segment);
        }
      })
      .join('/');
  }

  function imageAt(index) {
    if (!catalog.length) {
      return encodePath('./assets/images/Scene 10_1.png');
    }
    const i = ((index % catalog.length) + catalog.length) % catalog.length;
    return encodePath(catalog[i]);
  }

  function isRemotePlaceholder(url) {
    return /unsplash\.com|placehold\.co/i.test(url || '');
  }

  function resolveMediaUrl(url, fallbackIndex) {
    const idx = typeof fallbackIndex === 'number' ? fallbackIndex : 0;
    if (url) {
      if (/^https?:\/\//i.test(url)) {
        if (isRemotePlaceholder(url)) {
          return imageAt(idx);
        }
        return url;
      }
      if (url.startsWith('/')) {
        return encodePath(url);
      }
      const normalized = url.replace(/^\.\//, '');
      if (normalized.startsWith('assets/')) {
        return encodePath('./' + normalized);
      }
      return encodePath(url);
    }
    return imageAt(idx);
  }

  function categoryBadge(type) {
    if (type === 'rent') {
      return { label: 'Interior Design', className: 'interior' };
    }
    return { label: 'Architecture', className: 'architecture' };
  }

  function propertyImage(property, index) {
    index = typeof index === 'number' ? index : 0;
    const candidates = [];
    if (property.imageUrl) candidates.push(property.imageUrl);
    if (Array.isArray(property.images)) {
      property.images.forEach(function (img) {
        if (img) candidates.push(img);
      });
    }
    if (Array.isArray(property.galleryUrls)) {
      property.galleryUrls.forEach(function (img) {
        if (img) candidates.push(img);
      });
    }

    for (let i = 0; i < candidates.length; i++) {
      const candidate = candidates[i];
      if (isRemotePlaceholder(candidate)) {
        continue;
      }
      // Skip known deleted legacy filenames.
      if (/picture1\.jpg$/i.test(candidate)) {
        continue;
      }
      return resolveMediaUrl(candidate, index + i);
    }

    return imageAt(index);
  }

  function bindImageFallback(root, fallbackIndex) {
    const scope = root && root.querySelectorAll ? root : document;
    const imgs = scope.querySelectorAll ? scope.querySelectorAll('img[data-portfolio-fallback]') : [];
    imgs.forEach(function (img, i) {
      if (img.dataset.fallbackBound) return;
      img.dataset.fallbackBound = '1';
      img.addEventListener('error', function onErr() {
        img.removeEventListener('error', onErr);
        const idx = Number(img.getAttribute('data-portfolio-fallback') || fallbackIndex || i || 0);
        const next = imageAt(idx);
        if (img.getAttribute('src') !== next) {
          img.setAttribute('src', next);
        }
      });
    });
  }

  window.HOPE_PORTFOLIO = {
    catalog: catalog,
    imageAt: imageAt,
    encodePath: encodePath,
    resolveMediaUrl: resolveMediaUrl,
    categoryBadge: categoryBadge,
    propertyImage: propertyImage,
    bindImageFallback: bindImageFallback,
  };
})();
