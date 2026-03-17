// ======= LIKE FUNCTIONALITY (Define first so it's globally available) =======
window.toggleLike = async function(wallpaperId, event) {
  event.preventDefault();
  event.stopPropagation();
  
  console.log('Toggling like for wallpaper ID:', wallpaperId);
  
  if (!wallpaperId || wallpaperId === 0) {
    console.error('Invalid wallpaper ID');
    alert('Invalid wallpaper ID');
    return;
  }
  
  const button = event.currentTarget;
  const likeCount = button.querySelector('.like-count');
  
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }
    
    console.log('Sending request to:', `/wallpaper/${wallpaperId}/like`);
    
    const response = await fetch(`/wallpaper/${wallpaperId}/like`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
        'Accept': 'application/json'
      }
    });
    
    console.log('Response status:', response.status);
    
    if (response.status === 401) {
      alert('Please login to like wallpapers');
      window.location.href = '/login';
      return;
    }
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Error response:', errorText);
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    console.log('Response data:', data);
    
    if (data.liked) {
      button.classList.add('liked');
    } else {
      button.classList.remove('liked');
    }
    
    likeCount.textContent = data.likes;
  } catch (error) {
    console.error('Error toggling like:', error);
    alert('Failed to like wallpaper. Please check console for details.');
  }
}

// ===== VIDEO ERROR HANDLER =====
window.handleVideoError = function() {
  const video = document.getElementById('wallpaperImage');
  if (!video) return;
  
  const errorCode = video.error ? video.error.code : 'unknown';
  const errorMsg = {
    1: 'MEDIA_ERR_ABORTED',
    2: 'MEDIA_ERR_NETWORK',
    3: 'MEDIA_ERR_DECODE',
    4: 'MEDIA_ERR_SRC_NOT_SUPPORTED'
  }[errorCode] || `Error ${errorCode}`;
  
  console.error('Video playback error:', errorMsg);
  
  const loader = document.getElementById('videoLoader');
  if (loader && loader.style.display !== 'none') {
    if (errorCode === 2) {
      // Network error - trying fallback source
      loader.innerHTML = '<span style="color: #ff9800;">Switching to backup source...</span>';
    } else if (errorCode === 4 || errorCode === 3) {
      // Format or decode error
      loader.innerHTML = '<span style="color: #ff6b6b;">Video format not supported</span>';
    } else {
      // Other errors - retry
      loader.innerHTML = '<span style="color: #ff6b6b;">Video error. Retrying...</span>';
      
      setTimeout(() => {
        const source = video.querySelector('source');
        if (source) {
          const src = source.src;
          source.src = '';
          video.load();
          setTimeout(() => {
            source.src = src;
            video.load();
          }, 100);
        }
      }, 2000);
    }
  }
}

// Add timeout detection for videos
document.addEventListener('DOMContentLoaded', function() {
  const video = document.getElementById('wallpaperImage');
  const loader = document.getElementById('videoLoader');
  
  if (video && video.tagName === 'VIDEO' && loader) {
    let timeoutHandle;
    let retryCount = 0;
    const maxRetries = 2;
    
    // Show timeout message after 45 seconds if video hasn't loaded
    const startLoadingTimeout = () => {
      timeoutHandle = setTimeout(() => {
        if (video.readyState < 2) { // Not enough data loaded yet
          if (retryCount < maxRetries) {
            retryCount++;
            console.log('Video loading timeout, attempting retry', retryCount);
            
            // Try next source
            const sources = video.querySelectorAll('source');
            const currentSrc = video.currentSrc || sources[0].src;
            let foundCurrent = false;
            let nextSource = null;
            
            for (let source of sources) {
              if (foundCurrent) {
                nextSource = source;
                break;
              }
              if (source.src === currentSrc) {
                foundCurrent = true;
              }
            }
            
            if (nextSource) {
              console.log('Switching to fallback source');
              video.src = nextSource.src;
              video.load();
              startLoadingTimeout(); // Restart timeout for next source
            } else {
              loader.innerHTML = `
                <div style="text-align: center; font-size: 13px;">
                  <div style="color: #ff6b6b;">❌ Failed to load video</div>
                  <div style="color: #aaa; margin-top: 8px; font-size: 12px;">
                    Check your internet connection and try refreshing the page
                  </div>
                </div>
              `;
            }
          } else {
            loader.innerHTML = `
              <div style="text-align: center; font-size: 13px;">
                <div style="color: #ff9800;">⏳ Slow Connection</div>
                <div style="color: #aaa;">Still loading... please be patient</div>
              </div>
            `;
          }
        }
      }, 45000);
    };
    
    startLoadingTimeout();
    
    // Clear timeout and update when video is ready
    video.addEventListener('canplay', () => {
      clearTimeout(timeoutHandle);
      console.log('Video canplay event fired');
      if (loader && loader.style.display !== 'none') {
        setTimeout(() => {
          loader.style.display = 'none';
        }, 300);
      }
    });
    
    // If error occurs, restart the timeout on retry
    video.addEventListener('error', () => {
      clearTimeout(timeoutHandle);
      console.log('Video error event, restarting timeout');
      startLoadingTimeout();
    });
    
    // Restart timeout if user tries to play again
    video.addEventListener('play', () => {
      if (video.readyState < 2) {
        clearTimeout(timeoutHandle);
        console.log('Play attempted with incomplete buffer, restarting timeout');
        startLoadingTimeout();
      }
    });
  }
});

document.getElementById("auth-modals").innerHTML = `
<!-- Sign Up Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Create an Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="signupForm">
          <div class="mb-3">
            <label for="signupEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="signupEmail" required />
          </div>
          <div class="mb-3">
            <label for="signupPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="signupPassword" required minlength="6" />
          </div>
          <div class="mb-3">
            <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="signupConfirmPassword" required minlength="6" />
          </div>
          <button type="submit" class="btn btn-warning w-100">Sign Up</button>
        </form>
      </div>
      <div class="modal-footer border-secondary">
        <p class="text-center w-100 mb-0">
          Already have an account?
          <a href="#" data-bs-toggle="modal" data-bs-target="#signinModal" data-bs-dismiss="modal" class="text-warning">Sign In</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Sign In Modal -->
<div class="modal fade" id="signinModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Sign In</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="signinForm">
          <div class="mb-3">
            <label for="signinEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="signinEmail" required />
          </div>
          <div class="mb-3">
            <label for="signinPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="signinPassword" required />
          </div>
          <button type="submit" class="btn btn-warning w-100">Sign In</button>
        </form>
      </div>
      <div class="modal-footer border-secondary">
        <p class="text-center w-100 mb-0">
          Don’t have an account?
          <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal" class="text-warning">Sign Up</a>
        </p>
      </div>
    </div>
  </div>
</div>
`;

document.addEventListener("DOMContentLoaded", function () {
  const signupForm = document.getElementById("signupForm");
  const signinForm = document.getElementById("signinForm");

  // Sign Up
  signupForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const email = document.getElementById("signupEmail").value;
    const pass = document.getElementById("signupPassword").value;
    const confirm = document.getElementById("signupConfirmPassword").value;

    if (pass !== confirm) {
      alert("Passwords do not match!");
      return;
    }

    const users = JSON.parse(localStorage.getItem("users")) || [];
    if (users.find((user) => user.email === email)) {
      alert("Email already registered!");
      return;
    }

    users.push({ email, pass });
    localStorage.setItem("users", JSON.stringify(users));
    alert("Account created successfully!");

    signupForm.reset();
    bootstrap.Modal.getInstance(document.getElementById("signupModal")).hide();
  });

  // Sign In
  signinForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const email = document.getElementById("signinEmail").value;
    const pass = document.getElementById("signinPassword").value;
    const users = JSON.parse(localStorage.getItem("users")) || [];

    const user = users.find((u) => u.email === email && u.pass === pass);
    if (user) {
      localStorage.setItem("loggedInUser", email);
      alert(`Welcome back, ${email}!`);
      signinForm.reset();
      bootstrap.Modal.getInstance(document.getElementById("signinModal")).hide();

      const signInBtn = document.querySelector('.btn-outline-light');
      signInBtn.innerHTML = `<i class="fa-solid fa-user"></i> ${email}`;
    } else {
      alert("Invalid credentials!");
    }
  });
});

// ======= WALLPAPER PAGINATION SYSTEM =======

// Wallpapers array (populated from server)
let wallpapers = [];
let allWallpapers = [];

// Load wallpapers from page data
if (window.wallpapersData) {
  allWallpapers = window.wallpapersData.map(w => ({
    id: w.id,
    filename: w.filename,
    name: w.name,
    description: w.description,
    views: w.views,
    likes: w.likes,
    downloads: w.downloads,
    user_liked: w.user_liked,
    category: w.category,
    categories: w.categories || [],
    github_url: w.github_url,
    mime: w.mime || w.mime_type || ''
  }));
  wallpapers = [...allWallpapers];
} 

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  renderWallpapers();
  setupFilters();
});

// References
const wallpaperContainer = document.getElementById("wallpaperContainer");
const skeletonLoaders = document.getElementById("skeletonLoaders");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");
const pageIndicator = document.getElementById("pageIndicator");

// Show/hide skeleton loaders
function showSkeletons() {
  if (skeletonLoaders) skeletonLoaders.style.display = 'grid';
  wallpaperContainer.style.display = 'none';
}

function hideSkeletons() {
  if (skeletonLoaders) skeletonLoaders.style.display = 'none';
  wallpaperContainer.style.display = 'flex';
}

// Setup filter handlers
function setupFilters() {
  const categoryFilter = document.getElementById('categoryFilter');
  const sortFilter = document.getElementById('sortFilter');
  const clearFiltersBtn = document.getElementById('clearFilters');
  
  if (categoryFilter) {
    categoryFilter.addEventListener('change', applyFilters);
  }
  
  if (sortFilter) {
    sortFilter.addEventListener('change', applyFilters);
  }
  
  if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener('click', () => {
      if (categoryFilter) categoryFilter.value = '';
      if (sortFilter) sortFilter.value = 'latest';
      applyFilters();
    });
  }
}

// Apply filters to wallpapers
function applyFilters() {
  showSkeletons();
  
  const categoryFilter = document.getElementById('categoryFilter');
  const sortFilter = document.getElementById('sortFilter');
  
  const categoryId = categoryFilter ? parseInt(categoryFilter.value) : null;
  const sortBy = sortFilter ? sortFilter.value : 'latest';
  
  let filtered = [...window.wallpapersData];
  
  // Filter by category
  if (categoryId) {
    filtered = filtered.filter(w => {
      return w.categories && w.categories.some(c => c.id === categoryId);
    });
  }
  
  // Sort
  switch (sortBy) {
    case 'popular':
      filtered.sort((a, b) => (b.views + b.likes * 2) - (a.views + a.likes * 2));
      break;
    case 'views':
      filtered.sort((a, b) => b.views - a.views);
      break;
    case 'likes':
      filtered.sort((a, b) => b.likes - a.likes);
      break;
    case 'latest':
    default:
      filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      break;
  }
  
  wallpapers = filtered;
  
  setTimeout(() => {
    renderWallpapers();
    hideSkeletons();
  }, 300);
}

// Render Wallpapers
function renderWallpapers() {
  wallpaperContainer.innerHTML = "";
  
  if (wallpapers.length === 0) {
    wallpaperContainer.innerHTML = '<div class="col-12 text-center py-5"><h4 class="text-warning">No wallpapers found</h4><p class="text-muted">Try a different search term</p></div>';
    pageIndicator.textContent = 'No results';
    prevBtn.disabled = true;
    nextBtn.disabled = true;
    hideSkeletons();
    return;
  }

  // Render all wallpapers (already paginated by server)
  wallpapers.forEach((wallpaper) => {
    const div = document.createElement("div");
    div.className = "col-12 col-sm-6 col-md-4 col-lg-3";
    const filename = wallpaper.filename || wallpaper;
    const title = wallpaper.name || filename;
    const wallpaperId = wallpaper.id || 0;
    const views = wallpaper.views || 0;
    const likes = wallpaper.likes || 0;
    const downloads = wallpaper.downloads || 0;
    const mime = wallpaper.mime || '';
    const isVideo = mime.startsWith('video/');
    const fileSize = wallpaper.size || 0; // File size in bytes
    
    // Use GitHub URL (all media are now GitHub-hosted)
    const mediaUrl = wallpaper.github_url || wallpaper.url || '';
    
    console.log('Wallpaper data:', { id: wallpaperId, title, likes, user_liked: wallpaper.user_liked, mime, mediaUrl });
    
    // Handle categories - can be array or string for backward compatibility
    let categoryText = 'Uncategorized';
    if (wallpaper.categories && Array.isArray(wallpaper.categories) && wallpaper.categories.length > 0) {
      categoryText = wallpaper.categories.map(cat => {
        if (cat && typeof cat === 'object') {
          if (cat.display_name) return cat.display_name;
          const childName = cat.name || '';
          const parentName = cat.parent_name || '';
          return parentName ? `${parentName} (${childName})` : (childName || cat);
        }
        return cat;
      }).join(', ');
    } else if (wallpaper.category) {
      categoryText = wallpaper.category;
    }
    
    // For videos, use preload="metadata" to show thumbnail
    const videoPreload = 'metadata';
    
    const mediaElement = isVideo
      ? `<video class="wallpaper-media" src="${mediaUrl}" muted loop playsinline preload="${videoPreload}" aria-label="${title}" onloadeddata="this.play().catch(()=>{})" style="background: #1a1a1a;"></video>`
      : `<img class="wallpaper-media" src="${mediaUrl}" alt="${title}" loading="lazy" />`;

    div.innerHTML = `
      <div class="wallpaper-card-wrapper">
        <a href="/wallpaper/${filename}" class="text-decoration-none">
          <div class="wallpaper-card" style="position: relative;">
            ${mediaElement}
            <div class="wallpaper-card-overlay">
              <div class="wallpaper-card-info">
                <div class="wallpaper-card-title">${title}</div>
                <span class="wallpaper-card-category">${categoryText}${isVideo ? ' · Video' : ''}</span>
              </div>
            </div>
          </div>
        </a>
        <div class="wallpaper-stats">
          <a class="download-btn" href="/download/${filename}/original">
            <i class="fas fa-download"></i> Download
          </a>
          <button class="like-btn ${wallpaper.user_liked ? 'liked' : ''}" data-wallpaper-id="${wallpaperId}" onclick="window.toggleLike(${wallpaperId}, event)">
            <i class="fas fa-heart"></i> <span class="like-count">${likes}</span>
          </button>
        </div>
      </div>
    `;
    wallpaperContainer.appendChild(div);
  });

  // Update pagination info from server
  if (window.paginationData) {
    const currentPage = window.paginationData.current_page || 1;
    const lastPage = window.paginationData.last_page || 1;
    pageIndicator.textContent = `Page ${currentPage} of ${lastPage}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage >= lastPage;
    
    // Update button URLs for server-side pagination
    if (window.paginationData.prev_page_url) {
      prevBtn.onclick = () => window.location.href = window.paginationData.prev_page_url;
    }
    if (window.paginationData.next_page_url) {
      nextBtn.onclick = () => window.location.href = window.paginationData.next_page_url;
    }
  } else {
    // Fallback if no pagination data
    pageIndicator.textContent = `${wallpapers.length} wallpapers`;
    prevBtn.disabled = true;
    nextBtn.disabled = true;
  }
}

// Note: Navigation handlers removed - now handled by onclick in renderWallpapers()

// ======= SEARCH FUNCTIONALITY =======
const searchInput = document.getElementById("searchInput");
const searchBtn = document.getElementById("searchBtn");
const mainSearch = document.getElementById("mainSearch");
const mainSearchBtn = document.getElementById("mainSearchBtn");
const searchResultsHeader = document.getElementById("searchResultsHeader");
const searchQuerySpan = document.getElementById("searchQuery");
const clearSearchBtn = document.getElementById("clearSearchBtn");
let currentSearchQuery = '';

// Search function
async function performSearch(query) {
  if (!query || query.trim() === '') {
    wallpapers = [...allWallpapers];
    currentSearchQuery = '';
    if (searchResultsHeader) searchResultsHeader.style.display = 'none';
    renderWallpapers();
    return;
  }

  try {
    console.log('Searching for:', query);
    const response = await fetch(`/search?q=${encodeURIComponent(query)}`);
    const results = await response.json();
    
    console.log('Search results:', results);
    console.log('Number of results:', results.length);
    
    currentSearchQuery = query;
    if (searchQuerySpan) searchQuerySpan.textContent = query;
    if (searchResultsHeader) searchResultsHeader.style.display = 'block';
    
    if (results.length > 0) {
      wallpapers = results.map(w => ({
        id: w.id,
        filename: w.filename,
        name: w.name,
        slug: w.slug,
        category: w.category,
        categories: w.categories || [],
        github_url: w.github_url,
        mime: w.mime || w.mime_type || '',
        views: w.views || 0,
        likes: w.likes || 0,
        downloads: w.downloads || 0
      }));
      console.log('Mapped wallpapers:', wallpapers);
    } else {
      console.log('No results found');
      wallpapers = [];
    }
    
    renderWallpapers();
  } catch (error) {
    console.error('Search error:', error);
    wallpapers = [];
    renderWallpapers();
  }
}

// Event listeners for search
if (searchBtn) {
  searchBtn.addEventListener("click", (e) => {
    e.preventDefault();
    performSearch(searchInput.value);
  });
}

if (searchInput) {
  searchInput.addEventListener("keypress", (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      performSearch(searchInput.value);
    }
  });
}

if (mainSearchBtn) {
  mainSearchBtn.addEventListener("click", (e) => {
    e.preventDefault();
    performSearch(mainSearch.value);
  });
}

if (mainSearch) {
  mainSearch.addEventListener("keypress", (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      performSearch(mainSearch.value);
    }
  });
}

if (clearSearchBtn) {
  clearSearchBtn.addEventListener('click', () => {
    currentSearchQuery = '';
    mainSearch.value = '';
    if (searchInput) searchInput.value = '';
    if (searchResultsHeader) searchResultsHeader.style.display = 'none';
    wallpapers = [...allWallpapers];
    renderWallpapers();
  });
}

// ======= CATEGORIES MANAGEMENT =======

let allCategories = [];

// Character counters - check if elements exist first
const categoryNameInput = document.getElementById('categoryName');
const categoryDescInput = document.getElementById('categoryDescription');

if (categoryNameInput) {
  categoryNameInput.addEventListener('input', (e) => {
    const counter = document.getElementById('nameCharCount');
    if (counter) counter.textContent = e.target.value.length;
  });
}

if (categoryDescInput) {
  categoryDescInput.addEventListener('input', (e) => {
    const counter = document.getElementById('descCharCount');
    if (counter) counter.textContent = e.target.value.length;
  });
}

// Icon picker functionality
document.querySelectorAll('.icon-btn').forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('categoryIcon').value = btn.dataset.icon;
    document.getElementById('categoryIconCustom').value = '';
  });
});

// Custom icon input
const categoryIconCustomInput = document.getElementById('categoryIconCustom');
if (categoryIconCustomInput) {
  categoryIconCustomInput.addEventListener('input', (e) => {
    if (e.target.value.trim()) {
      document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
      const categoryIconInput = document.getElementById('categoryIcon');
      if (categoryIconInput) {
        categoryIconInput.value = e.target.value;
      }
    }
  });
}

// Toast notification
function showToast(message, type = 'success') {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast-notification ${type}`;
  
  const icon = type === 'success' ? '✓' : '✕';
  toast.innerHTML = `
    <span class="toast-icon">${icon}</span>
    <span class="toast-message">${message}</span>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.classList.add('removing');
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// Load and display categories
async function loadCategories() {
  try {
    const response = await fetch('/api/categories');
    allCategories = await response.json();
    displayCategories();
  } catch (error) {
    console.error('Error loading categories:', error);
    showToast('Failed to load categories', 'error');
  }
}

// Display categories in modal
function displayCategories() {
  const categoriesList = document.getElementById('categoriesList');
  
  if (allCategories.length === 0) {
    categoriesList.innerHTML = `
      <div class="categories-empty">
        <div class="categories-empty-icon">📂</div>
        <p class="categories-empty-text">No categories yet. Create your first one!</p>
      </div>
    `;
    return;
  }

  categoriesList.innerHTML = allCategories.map(category => `
    <div class="category-list-item">
      <div class="category-item-info">
        <div class="category-item-name">
          <span class="category-item-icon">${category.icon || '📁'}</span>
          ${category.name}
        </div>
        <p class="category-item-description">${category.description || 'No description'}</p>
        <div class="category-item-count">${category.wallpaper_count || 0} wallpapers</div>
      </div>
      <button type="button" class="btn btn-delete-category" onclick="deleteCategory(${category.id}, '${category.name.replace(/'/g, "\\'")}')">
        <i class="fas fa-trash me-1"></i>Delete
      </button>
    </div>
  `).join('');
}


