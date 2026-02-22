<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Upload Wallpaper</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  @vite(['resources/css/style.css'])
  <style>
    body {
      background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
      min-height: 100vh;
      padding: 40px 20px;
      font-family: 'Segoe UI', Roboto, sans-serif;
    }

    .form-card {
      background: rgba(30, 30, 30, 0.7);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      max-width: 700px;
      margin: auto;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
      transition: all 0.3s ease;
    }

    .form-card:hover {
      box-shadow: 0 20px 60px rgba(255, 193, 7, 0.15);
      border-color: rgba(255, 193, 7, 0.3);
    }

    .form-title {
      color: #ffc107;
      font-size: 32px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
      text-shadow: 0 2px 10px rgba(255, 193, 7, 0.3);
    }

    .upload-area {
      background: rgba(20, 20, 20, 0.8);
      border: 2px dashed rgba(255, 193, 7, 0.4);
      border-radius: 16px;
      padding: 40px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 25px;
      position: relative;
      overflow: hidden;
      min-height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .upload-area.has-file {
      border-color: rgba(76, 175, 80, 0.5);
      background: rgba(20, 30, 20, 0.8);
      padding: 0;
      min-height: auto;
    }

    .upload-area.has-file .upload-icon,
    .upload-area.has-file .upload-text,
    .upload-area.has-file .upload-hint {
      display: none;
    }

    .upload-area:hover {
      border-color: rgba(255, 193, 7, 0.8);
      background: rgba(30, 30, 30, 0.9);
      transform: translateY(-2px);
    }

    .upload-area.drag-over {
      border-color: #ffc107;
      background: rgba(255, 193, 7, 0.1);
    }

    .upload-icon {
      font-size: 48px;
      color: #ffc107;
      margin-bottom: 15px;
    }

    .upload-text {
      color: #ccc;
      font-size: 16px;
      margin-bottom: 8px;
    }

    .upload-hint {
      color: #888;
      font-size: 13px;
    }

    #preview {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 14px;
      margin-bottom: 25px;
      display: none;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
      animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    .form-label {
      color: #ffc107;
      font-weight: 600;
      margin-bottom: 10px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-control, .form-select {
      background: rgba(28, 28, 28, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      padding: 14px 18px;
      border-radius: 12px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
      font-size: 15px;
    }

    .form-control:focus, .form-select:focus {
      background: rgba(36, 36, 36, 0.95);
      border-color: #ffc107;
      box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
      color: #fff;
      outline: none;
    }

    .form-control::placeholder {
      color: #666;
    }

    textarea.form-control {
      resize: vertical;
      min-height: 120px;
    }

    .btn-container {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }

    .upload-btn {
      background: linear-gradient(135deg, #ffc107, #ff9800);
      border: none;
      padding: 14px 35px;
      border-radius: 12px;
      color: #000;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      flex: 1;
      box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }

    .upload-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(255, 193, 7, 0.5);
      background: linear-gradient(135deg, #ffcd1f, #ffa91f);
    }

    .upload-btn:active {
      transform: translateY(-1px);
    }

    .cancel-btn {
      background: rgba(60, 60, 60, 0.8);
      border: 1px solid rgba(255, 255, 255, 0.1);
      padding: 14px 35px;
      border-radius: 12px;
      color: #ccc;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .cancel-btn:hover {
      background: rgba(80, 80, 80, 0.9);
      color: #fff;
      border-color: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
    }

    .alert {
      border-radius: 12px;
      border: 1px solid rgba(255, 193, 7, 0.3);
      background: rgba(255, 193, 7, 0.1);
      color: #ffc107;
      margin-bottom: 25px;
      animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .file-input-wrapper {
      position: relative;
      overflow: hidden;
    }

    .file-input-wrapper input[type=file] {
      position: absolute;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    .selected-file {
      color: #ffc107;
      font-size: 14px;
      margin-top: 10px;
      display: none;
    }

    .selected-file i {
      margin-right: 8px;
    }

    .categories-checkboxes {
      background: rgba(28, 28, 28, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 15px;
      max-height: 250px;
      overflow-y: auto;
      margin-bottom: 20px;
    }

    .category-parent {
      margin-bottom: 8px;
    }

    .category-parent-header {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      padding: 8px;
      background: rgba(255, 195, 0, 0.1);
      border-radius: 6px;
      margin-bottom: 4px;
      transition: background 0.2s ease;
    }

    .category-parent-header:hover {
      background: rgba(255, 195, 0, 0.2);
    }

    .category-parent-header .toggle-icon {
      font-size: 12px;
      color: #ffc107;
      transition: transform 0.2s ease;
    }

    .category-parent-header .toggle-icon.expanded {
      transform: rotate(90deg);
    }

    .category-children {
      display: none;
      padding-left: 30px;
      margin-top: 4px;
    }

    .category-children.show {
      display: block;
    }

    .category-checkbox {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
      padding: 8px 12px;
      background: rgba(255, 255, 255, 0.02);
      border-radius: 6px;
      transition: background 0.2s ease;
    }

    .category-checkbox:hover {
      background: rgba(255, 195, 0, 0.1);
    }

    .category-checkbox input[type="checkbox"] {
      margin-right: 10px;
      cursor: pointer;
    }

    .category-checkbox label {
      margin: 0;
      cursor: pointer;
      flex: 1;
      font-size: 14px;
    }
      padding: 8px 0;
    }

    .category-checkbox:last-child {
      margin-bottom: 0;
    }

    .category-checkbox input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin-right: 10px;
      cursor: pointer;
      accent-color: #ffc107;
    }

    .category-checkbox label {
      cursor: pointer;
      color: #ddd;
      font-size: 15px;
      margin: 0;
      flex: 1;
    }

    .category-checkbox input[type="checkbox"]:checked + label {
      color: #ffc107;
      font-weight: 600;
    }

    .categories-checkboxes::-webkit-scrollbar {
      width: 8px;
    }

    .categories-checkboxes::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
    }

    .categories-checkboxes::-webkit-scrollbar-thumb {
      background: rgba(255, 193, 7, 0.3);
      border-radius: 10px;
    }

    .categories-checkboxes::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 193, 7, 0.5);
    }
  </style>
</head>
<body class="text-light">
  <div class="form-card">
    <h1 class="form-title">
      <i class="fas fa-cloud-upload-alt me-2"></i>Upload Wallpaper
    </h1>

    @if($errors->any())
      <div class="alert">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('wallpapers.store') }}" method="post" enctype="multipart/form-data" id="uploadForm">
      @csrf
      
      <!-- Community Guidelines Alert -->
      <div style="background: rgba(76, 175, 80, 0.15); border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 12px; padding: 20px; margin-bottom: 25px;">
        <div style="display: flex; align-items: flex-start; gap: 12px;">
          <i class="fas fa-info-circle" style="color: #4CAF50; font-size: 20px; margin-top: 2px; flex-shrink: 0;"></i>
          <div>
            <h5 style="color: #4CAF50; font-weight: 600; margin-bottom: 10px; margin-top: 0;">Thanks for contributing to our wallpaper collection!</h5>
            <p style="color: #A3A3A3; font-size: 14px; margin-bottom: 12px;">Please review our community rules and remember that all uploads are moderated. Adding tags and a caption to your uploads will help other users find your content easily.</p>
            <div style="background: rgba(0, 0, 0, 0.3); border-left: 3px solid #4CAF50; padding: 12px; border-radius: 4px;">
              <p style="color: #E3E3E3; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Remember:</p>
              <ul style="color: #A3A3A3; font-size: 13px; margin-bottom: 0; padding-left: 20px;">
                <li>No selfies or personal photos</li>
                <li>No screenshots</li>
                <li>No offensive images</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Image Preview -->
      <img id="preview" alt="Preview">

      <!-- Progress Bar for Image Validation -->
      <div id="validationProgress" style="display: none; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <span style="font-size: 13px; font-weight: 600; color: #ffc107;">
            <i class="fas fa-spinner fa-spin me-2"></i>Checking image dimensions...
          </span>
          <span id="progressText" style="font-size: 12px; color: #888;">0%</span>
        </div>
        <div style="width: 100%; height: 6px; background: rgba(255, 255, 255, 0.1); border-radius: 3px; overflow: hidden;">
          <div id="progressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #ffc107, #ff9800); transition: width 0.2s ease; border-radius: 3px;"></div>
        </div>
      </div>

      <!-- Validation Status -->
      <div id="validationStatus" style="display: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
        <i id="statusIcon" class="fas fa-check-circle"></i>
        <span id="statusText"></span>
      </div>

      <!-- Upload Area -->
      <div class="upload-area file-input-wrapper" id="uploadArea">
        <input type="file" name="image" id="imageInput" accept="image/*,video/mp4" required />
        <div class="upload-icon">
          <i class="fas fa-image"></i>
        </div>
        <div class="upload-text">Drag & Drop or Click to Browse</div>
        <div class="upload-hint">Supported: JPG, PNG, WEBP, MP4 (Max: 24.9MB)</div>
        <div class="selected-file" id="selectedFile">
          <i class="fas fa-check-circle"></i>
          <span id="fileName"></span>
        </div>
      </div>

      <!-- Name Field -->
      <div class="mb-3">
        <label class="form-label">
          <i class="fas fa-signature me-2"></i>Wallpaper Name
        </label>
        <input type="text" name="name" class="form-control" maxlength="100" placeholder="Enter wallpaper name..." required />
      </div>

      <!-- Category Field -->
      <div class="mb-3">
        <label class="form-label">
          <i class="fas fa-tags me-2"></i>Categories
          <button type="button" id="refreshCategoriesBtn" class="btn btn-sm btn-link" style="padding: 0; margin-left: 10px; font-size: 12px; color: #ffc107;" title="Refresh categories">
            <i class="fas fa-sync-alt"></i> Refresh
          </button>
        </label>
        <small style="display: block; color: #999; font-size: 12px; margin-bottom: 10px;">
          <i class="fas fa-info-circle me-1"></i>
          Click parent category to expand and view subcategories. Select any category to organize your wallpaper.
        </small>
        <div id="categoriesContainer" class="categories-checkboxes">
          <!-- Categories will be loaded dynamically here -->
        </div>
      </div>

      <!-- Description Field -->
      <div class="mb-3">
        <label class="form-label">
          <i class="fas fa-align-left me-2"></i>Description
        </label>
        <textarea name="description" class="form-control" rows="4" maxlength="1000" placeholder="Describe this wallpaper..."></textarea>
      </div>

      <!-- Upload Progress -->
      <div id="uploadProgress" style="display: none; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <span style="font-size: 14px; font-weight: 600; color: #ffc107;">
            <i class="fas fa-spinner fa-spin me-2"></i><span id="uploadStatusText">Preparing upload...</span>
          </span>
          <span id="uploadPercentage" style="font-size: 13px; color: #888;">0%</span>
        </div>
        <div style="width: 100%; height: 10px; background: rgba(255, 255, 255, 0.1); border-radius: 5px; overflow: hidden;">
          <div id="uploadProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #ffc107, #ff9800); transition: width 0.3s ease; border-radius: 5px;"></div>
        </div>
        <div id="uploadDetails" style="font-size: 12px; color: #888; margin-top: 8px; display: flex; justify-content: space-between;">
          <span id="uploadSpeed"></span>
          <span id="uploadETA"></span>
        </div>
      </div>

      <!-- Buttons -->
      <div class="btn-container">
        <button class="upload-btn" type="submit">
          <i class="fas fa-upload me-2"></i>Upload Wallpaper
        </button>
        <a href="{{ url('/') }}" class="cancel-btn">
          <i class="fas fa-times me-2"></i>Cancel
        </a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('preview');
    const uploadArea = document.getElementById('uploadArea');
    const selectedFile = document.getElementById('selectedFile');
    const fileName = document.getElementById('fileName');
    const validationProgress = document.getElementById('validationProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const validationStatus = document.getElementById('validationStatus');
    const statusIcon = document.getElementById('statusIcon');
    const statusText = document.getElementById('statusText');
    const uploadForm = document.getElementById('uploadForm');
    let imageIsValid = false;

    // Minimum resolution: 1280x720 (720p)
    const MIN_WIDTH = 1280;
    const MIN_HEIGHT = 720;

    // Validate image dimensions
    function validateImageDimensions(file) {
      return new Promise((resolve) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          const img = new Image();
          img.onload = function() {
            const width = img.width;
            const height = img.height;
            
            // Simulate progress animation
            let progress = 0;
            const progressInterval = setInterval(() => {
              progress += Math.random() * 30;
              if (progress > 90) progress = 90;
              progressBar.style.width = progress + '%';
              progressText.textContent = Math.round(progress) + '%';
            }, 100);

            // Simulate validation delay (50ms more)
            setTimeout(() => {
              clearInterval(progressInterval);
              progressBar.style.width = '100%';
              progressText.textContent = '100%';

              // Check if image meets minimum resolution
              const isValid = width >= MIN_WIDTH && height >= MIN_HEIGHT;
              
              // Hide progress bar after completion
              setTimeout(() => {
                validationProgress.style.display = 'none';
                
                // Show validation status
                validationStatus.style.display = 'flex';
                
                if (isValid) {
                  statusIcon.className = 'fas fa-check-circle';
                  statusIcon.style.color = '#4CAF50';
                  statusText.textContent = `✓ Image valid: ${width}x${height} (${width}x${height} is suitable for wallpapers)`;
                  statusText.style.color = '#4CAF50';
                  validationStatus.style.background = 'rgba(76, 175, 80, 0.15)';
                  validationStatus.style.borderLeft = '3px solid #4CAF50';
                  imageIsValid = true;
                } else {
                  statusIcon.className = 'fas fa-times-circle';
                  statusIcon.style.color = '#f44336';
                  statusText.textContent = `✗ Image too small: ${width}x${height}. Minimum required: ${MIN_WIDTH}x${MIN_HEIGHT} (720p)`;
                  statusText.style.color = '#f44336';
                  validationStatus.style.background = 'rgba(244, 67, 54, 0.15)';
                  validationStatus.style.borderLeft = '3px solid #f44336';
                  imageIsValid = false;
                }
              }, 300);

              resolve(isValid);
            }, 500);
          };
          
          img.onerror = function() {
            validationProgress.style.display = 'none';
            validationStatus.style.display = 'flex';
            statusIcon.className = 'fas fa-exclamation-circle';
            statusIcon.style.color = '#ff9800';
            statusText.textContent = 'Error: Could not load image. Please try another file.';
            statusText.style.color = '#ff9800';
            validationStatus.style.background = 'rgba(255, 152, 0, 0.15)';
            validationStatus.style.borderLeft = '3px solid #ff9800';
            imageIsValid = false;
            resolve(false);
          };
          
          img.src = e.target.result;
        };
        
        reader.readAsDataURL(file);
      });
    }

    // Image preview on file selection
    imageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Check if it's a video file
        const isVideo = file.type.startsWith('video/');
        
        if (isVideo) {
          // Skip dimension validation for videos
          imageIsValid = true;
          validationStatus.style.display = 'flex';
          statusIcon.className = 'fas fa-check-circle';
          statusIcon.style.color = '#4CAF50';
          statusText.textContent = `✓ Video file accepted: ${file.name}`;
          statusText.style.color = '#4CAF50';
          validationStatus.style.background = 'rgba(76, 175, 80, 0.15)';
          validationStatus.style.borderLeft = '3px solid #4CAF50';
          fileName.textContent = file.name;
          selectedFile.style.display = 'block';
          uploadArea.classList.add('has-file');
          preview.style.display = 'none';
          return;
        }
        
        // Show progress bar for images
        validationProgress.style.display = 'block';
        progressBar.style.width = '0%';
        progressText.textContent = '0%';
        validationStatus.style.display = 'none';

        // Validate dimensions for images
        validateImageDimensions(file).then(isValid => {
          if (isValid) {
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
              preview.src = e.target.result;
              preview.style.display = 'block';
              fileName.textContent = file.name;
              selectedFile.style.display = 'block';
              uploadArea.classList.add('has-file'); // Hide upload text
            };
            reader.readAsDataURL(file);
          } else {
            // Clear preview if invalid
            preview.style.display = 'none';
            selectedFile.style.display = 'none';
            uploadArea.classList.remove('has-file');
            imageInput.value = ''; // Clear file input
          }
        });
      }
    });

    // Upload progress elements
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadProgressBar = document.getElementById('uploadProgressBar');
    const uploadPercentage = document.getElementById('uploadPercentage');
    const uploadStatusText = document.getElementById('uploadStatusText');
    const uploadSpeed = document.getElementById('uploadSpeed');
    const uploadETA = document.getElementById('uploadETA');
    const uploadBtn = document.querySelector('.upload-btn');
    
    let startTime = 0;
    let uploadedBytes = 0;

    // Form submission with progress tracking
    uploadForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (!imageIsValid) {
        statusIcon.className = 'fas fa-exclamation-circle';
        statusIcon.style.color = '#f44336';
        statusText.textContent = 'Please select a valid image with at least 1280x720 resolution.';
        statusText.style.color = '#f44336';
        validationStatus.style.display = 'flex';
        validationStatus.style.background = 'rgba(244, 67, 54, 0.15)';
        validationStatus.style.borderLeft = '3px solid #f44336';
        validationStatus.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
      }

      // Prepare form data
      const formData = new FormData(uploadForm);
      const file = imageInput.files[0];
      const totalSize = file.size;
      
      // Log selected categories for debugging
      const selectedCategories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked'))
        .map(cb => ({ id: cb.value, name: cb.nextElementSibling?.textContent || 'Unknown' }));
      console.log('Selected categories:', selectedCategories);
      
      // Show upload progress
      uploadProgress.style.display = 'block';
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
      uploadProgressBar.style.background = 'linear-gradient(90deg, #ffc107, #ff9800)';
      uploadProgressBar.style.width = '0%';
      uploadPercentage.textContent = '0%';
      uploadStatusText.innerHTML = '<i class="fas fa-cloud-upload-alt me-2"></i>Uploading to storage...';
      uploadSpeed.textContent = 'Preparing...';
      uploadETA.textContent = '';
      
      // Create XMLHttpRequest
      const xhr = new XMLHttpRequest();
      startTime = Date.now();
      let githubUploadStarted = false;
      
      // Hide client upload progress, only show after reaching server
      xhr.upload.addEventListener('loadend', function() {
        uploadProgressBar.style.width = '0%';
        uploadPercentage.textContent = '0%';
        uploadStatusText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading to storage...';
        uploadSpeed.textContent = '';
        uploadETA.textContent = '';
        githubUploadStarted = true;
        
        // Start simulating GitHub upload progress
        const totalMB = (totalSize / (1024 * 1024)).toFixed(2);
        let simulatedProgress = 0;
        const githubStartTime = Date.now();
        
        const githubInterval = setInterval(() => {
          // Increment progress more slowly for larger files
          const increment = totalSize > 10 * 1024 * 1024 ? Math.random() * 3 : Math.random() * 5;
          simulatedProgress += increment;
          
          if (simulatedProgress > 95) simulatedProgress = 95; // Cap at 95% until server confirms
          
          uploadProgressBar.style.width = simulatedProgress + '%';
          uploadPercentage.textContent = Math.round(simulatedProgress) + '%';
          
          // Show estimated uploaded amount
          const uploadedMB = ((simulatedProgress / 100) * totalSize / (1024 * 1024)).toFixed(2);
          uploadSpeed.textContent = `${uploadedMB}MB / ${totalMB}MB`;
          
          // Estimate ETA
          const elapsed = (Date.now() - githubStartTime) / 1000;
          if (elapsed > 0 && simulatedProgress > 0) {
            const estimatedTotal = (elapsed / simulatedProgress) * 100;
            const remaining = Math.max(0, estimatedTotal - elapsed);
            const etaMin = Math.floor(remaining / 60);
            const etaSec = Math.round(remaining % 60);
            
            if (etaMin > 0) {
              uploadETA.textContent = `ETA: ~${etaMin}m ${etaSec}s`;
            } else if (etaSec > 0) {
              uploadETA.textContent = `ETA: ~${etaSec}s`;
            }
          }
        }, 400);
        
        xhr.githubInterval = githubInterval;
      });
      
      // Handle completion
      xhr.addEventListener('load', function() {
        if (xhr.githubInterval) {
          clearInterval(xhr.githubInterval);
        }
        
        if (xhr.status === 200 || xhr.status === 302) {
          uploadProgressBar.style.width = '100%';
          uploadPercentage.textContent = '100%';
          uploadStatusText.innerHTML = '<i class="fas fa-check-circle me-2"></i>Upload Complete!';
          uploadProgressBar.style.background = 'linear-gradient(90deg, #4CAF50, #8BC34A)';
          const totalMB = (totalSize / (1024 * 1024)).toFixed(2);
          uploadSpeed.textContent = `${totalMB}MB / ${totalMB}MB`;
          uploadETA.textContent = '';
          
          // Redirect after success
          setTimeout(() => {
            try {
              const response = JSON.parse(xhr.responseText);
              if (response.redirect) {
                window.location.href = response.redirect;
              }
            } catch (e) {
              const redirectUrl = xhr.getResponseHeader('Location');
              if (redirectUrl) {
                window.location.href = redirectUrl;
              } else {
                window.location.reload();
              }
            }
          }, 1500);
        } else {
          uploadProgressBar.style.width = '0%';
          uploadPercentage.textContent = '0%';
          uploadStatusText.innerHTML = '<i class="fas fa-times-circle me-2"></i>Upload Failed';
          uploadProgressBar.style.background = '#f44336';
          uploadBtn.disabled = false;
          uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Wallpaper';
          uploadSpeed.textContent = 'Error';
          uploadETA.textContent = '';
          alert('Upload failed. Please try again.');
        }
      });
      
      // Handle errors
      xhr.addEventListener('error', function() {
        if (xhr.githubInterval) {
          clearInterval(xhr.githubInterval);
        }
        
        uploadProgressBar.style.width = '0%';
        uploadPercentage.textContent = '0%';
        uploadStatusText.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Upload Error';
        uploadProgressBar.style.background = '#f44336';
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Wallpaper';
        uploadSpeed.textContent = 'Network error';
        uploadETA.textContent = '';
        alert('An error occurred during upload. Please try again.');
      });
      
      // Send request to server
      xhr.open('POST', uploadForm.action);
      xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
      xhr.send(formData);
      
      return false;
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
      e.preventDefault();
      uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', function(e) {
      e.preventDefault();
      uploadArea.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', function(e) {
      e.preventDefault();
      uploadArea.classList.remove('drag-over');
      
      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        imageInput.files = e.dataTransfer.files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        imageInput.dispatchEvent(event);
      }
    });

    // ===== DYNAMIC CATEGORY LOADING =====
    const refreshCategoriesBtn = document.getElementById('refreshCategoriesBtn');
    const categoriesContainer = document.getElementById('categoriesContainer');

    // Load categories from API
    async function loadCategories() {
      try {
        const response = await fetch('/api/categories');
        const categories = await response.json();
        
        // Get current selections
        const checkedIds = new Set();
        document.querySelectorAll('.category-checkbox input:checked').forEach(checkbox => {
          checkedIds.add(checkbox.value);
        });
        
        // Clear existing checkboxes
        categoriesContainer.innerHTML = '';
        
        // Add categories as checkboxes with subcategories
        if (categories.length === 0) {
          categoriesContainer.innerHTML = '<p style="color: #999; font-size: 14px; margin: 0;">No categories available</p>';
          return;
        }

        categories.forEach(category => {
          // Parent category
          const parentDiv = document.createElement('div');
          parentDiv.className = 'category-parent';
          
          // If has children, create expandable header
          if (category.children && category.children.length > 0) {
            const headerDiv = document.createElement('div');
            headerDiv.className = 'category-parent-header';
            headerDiv.onclick = function(e) {
              // Don't toggle if clicking on checkbox or label
              if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
                return;
              }
              const childrenDiv = parentDiv.querySelector('.category-children');
              const icon = headerDiv.querySelector('.toggle-icon');
              childrenDiv.classList.toggle('show');
              icon.classList.toggle('expanded');
            };
            
            const parentIsChecked = checkedIds.has(category.id.toString()) ? 'checked' : '';
            headerDiv.innerHTML = `
              <i class="fas fa-chevron-right toggle-icon"></i>
              <input type="checkbox" name="categories[]" id="category_${category.id}" value="${category.id}" class="parent-checkbox" data-parent-id="${category.id}" ${parentIsChecked} style="margin: 0;" />
              <label for="category_${category.id}" style="margin: 0; font-weight: 600;">${category.icon || '📁'} ${category.name}</label>
            `;
            parentDiv.appendChild(headerDiv);
            
            // Children container
            const childrenDiv = document.createElement('div');
            childrenDiv.className = 'category-children';
            
            category.children.forEach(subcategory => {
              const subIsChecked = checkedIds.has(subcategory.id.toString()) ? 'checked' : '';
              const subDiv = document.createElement('div');
              subDiv.className = 'category-checkbox';
              subDiv.style.marginBottom = '8px';
              
              subDiv.innerHTML = `
                <input type="checkbox" name="categories[]" id="category_${subcategory.id}" value="${subcategory.id}" class="child-checkbox" data-parent-id="${category.id}" ${subIsChecked} />
                <label for="category_${subcategory.id}">${subcategory.icon || '📌'} ${subcategory.name}</label>
              `;
              childrenDiv.appendChild(subDiv);
            });
            
            parentDiv.appendChild(childrenDiv);
            
            // Add event listeners for smart checkbox handling
            const parentCheckbox = headerDiv.querySelector('.parent-checkbox');
            const childCheckboxes = childrenDiv.querySelectorAll('.child-checkbox');
            
            // When parent is checked, uncheck all children
            parentCheckbox.addEventListener('change', function() {
              if (this.checked) {
                childCheckboxes.forEach(child => {
                  child.checked = false;
                });
              }
            });
            
            // When a child is checked, uncheck parent
            childCheckboxes.forEach(child => {
              child.addEventListener('change', function() {
                if (this.checked) {
                  parentCheckbox.checked = false;
                  // Also uncheck other children (only one subcategory at a time)
                  childCheckboxes.forEach(otherChild => {
                    if (otherChild !== this) {
                      otherChild.checked = false;
                    }
                  });
                }
              });
            });
          } else {
            // No children, simple checkbox
            const parentIsChecked = checkedIds.has(category.id.toString()) ? 'checked' : '';
            parentDiv.innerHTML = `
              <div class="category-checkbox">
                <input type="checkbox" name="categories[]" id="category_${category.id}" value="${category.id}" ${parentIsChecked} />
                <label for="category_${category.id}" style="font-weight: 600;">${category.icon || '📁'} ${category.name}</label>
              </div>
            `;
          }
          
          categoriesContainer.appendChild(parentDiv);
        });
      } catch (error) {
        console.error('Error loading categories:', error);
        categoriesContainer.innerHTML = '<p style="color: #ff6b6b; font-size: 14px; margin: 0;">Error loading categories</p>';
      }
    }

    // Refresh categories on button click
    refreshCategoriesBtn.addEventListener('click', function(e) {
      e.preventDefault();
      this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
      loadCategories();
      
      // Reset button text after 1 second
      setTimeout(() => {
        this.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
      }, 1000);
    });

    // Load categories on page load
    document.addEventListener('DOMContentLoaded', loadCategories);
  </script>
</body>
</html>