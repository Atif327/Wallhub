/**
 * Lazy Loading & Performance Enhancements
 */

// Lazy load images and videos with Intersection Observer
document.addEventListener('DOMContentLoaded', () => {
  // Create Intersection Observer for lazy loading
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        
        if (img.dataset.src) {
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
        }
        
        // Remove loading class after image loads
        img.addEventListener('load', () => {
          img.classList.remove('lazy');
        });
        
        observer.unobserve(img);
      }
    });
  }, {
    rootMargin: '50px',
  });

  const videoObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const video = entry.target;
        
        // For data-src lazy loading
        if (video.dataset.src) {
          video.src = video.dataset.src;
          video.removeAttribute('data-src');
        }
        
        // Autoplay when visible (for videos with preload="none")
        if (video.paused) {
          video.play().catch(() => {
            // Silent fail if autoplay is blocked
          });
        }
        
        observer.unobserve(video);
      }
    });
  }, {
    rootMargin: '100px',
  });

  // Observe all lazy images
  document.querySelectorAll('img.lazy[data-src]').forEach((img) => {
    imageObserver.observe(img);
  });

  // Observe all videos (both lazy-loaded and preload="none")
  document.querySelectorAll('video').forEach((video) => {
    videoObserver.observe(video);
  });
});

// Limit concurrent image loading
let activeLoads = 0;
const MAX_CONCURRENT = 6;

const imageQueue = [];

function processImageQueue() {
  while (activeLoads < MAX_CONCURRENT && imageQueue.length > 0) {
    const img = imageQueue.shift();
    activeLoads++;

    img.addEventListener(
      'load',
      () => {
        activeLoads--;
        processImageQueue();
      },
      { once: true }
    );

    img.addEventListener(
      'error',
      () => {
        activeLoads--;
        processImageQueue();
      },
      { once: true }
    );

    if (img.dataset.src) {
      img.src = img.dataset.src;
    }
  }
}

// Add images to queue when they become visible
document.addEventListener('DOMContentLoaded', () => {
  const gridObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && entry.target.dataset.src) {
        imageQueue.push(entry.target);
        processImageQueue();
        gridObserver.unobserve(entry.target);
      }
    });
  }, {
    rootMargin: '100px',
  });

  document.querySelectorAll('.wallpaper-image[data-src]').forEach((img) => {
    gridObserver.observe(img);
  });
});

// Prefetch next wallpaper on hover
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('a[href*="/wallpaper/"]').forEach((link) => {
    link.addEventListener('mouseover', () => {
      const img = link.querySelector('img, video');
      if (img && img.src) {
        const prefetch = new Image();
        prefetch.src = img.src;
      }
    });
  });
});
