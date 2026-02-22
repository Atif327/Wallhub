<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bulk Upload - Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            min-height: 100vh;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: rgba(20, 20, 20, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #ffc107;
            padding: 1rem 0;
        }

        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .page-header {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .upload-card {
            background: rgba(30, 30, 30, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .drop-zone {
            border: 3px dashed rgba(255, 193, 7, 0.4);
            border-radius: 15px;
            padding: 3rem 2rem;
            text-align: center;
            background: rgba(40, 40, 40, 0.5);
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .drop-zone:hover {
            border-color: #ffc107;
            background: rgba(50, 50, 50, 0.6);
        }

        .drop-zone.dragover {
            border-color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
            transform: scale(1.02);
        }

        .drop-zone-icon {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 1.5rem;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .preview-item {
            position: relative;
            aspect-ratio: 16/9;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(40, 40, 40, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .preview-remove:hover {
            background: #dc3545;
            transform: scale(1.1);
        }

        .preview-name {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            padding: 1rem 0.5rem 0.5rem;
            font-size: 0.75rem;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .category-select {
            background: rgba(40, 40, 40, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 0.75rem;
            border-radius: 10px;
        }

        .category-select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
            background: rgba(50, 50, 50, 0.9);
            color: #fff;
        }

        .btn-upload {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            border: none;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .btn-upload:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 193, 7, 0.4);
        }

        .btn-upload:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .upload-progress {
            display: none;
            margin-top: 2rem;
        }

        .progress {
            height: 30px;
            background: rgba(40, 40, 40, 0.8);
            border-radius: 15px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, #ffc107, #ff9800);
            transition: width 0.3s ease;
        }

        .file-count {
            display: inline-block;
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            margin-top: 1rem;
            font-weight: 600;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-shield-alt me-2" style="color: #ffc107;"></i>
                Admin Panel
            </a>
            <div class="ms-auto">
                <a href="{{ route('admin.wallpapers') }}" class="btn btn-outline-light me-2">
                    <i class="fas fa-images me-2"></i>All Wallpapers
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-warning">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="mb-2">
                <i class="fas fa-cloud-upload-alt me-3" style="color: #ffc107;"></i>
                Bulk Upload Wallpapers
            </h1>
            <p class="text-muted mb-0">Upload multiple wallpapers at once (up to 30 images)</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Upload Errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Upload Form -->
        <form id="bulkUploadForm" action="{{ route('admin.bulk.upload.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="upload-card">
                <!-- Drop Zone -->
                <div class="drop-zone" id="dropZone">
                    <i class="fas fa-cloud-upload-alt drop-zone-icon"></i>
                    <h4 class="mb-3">Drag & Drop Images Here</h4>
                    <p class="text-muted mb-3">or click to browse</p>
                    <input type="file" id="fileInput" name="wallpapers[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;">
                    <button type="button" class="btn btn-outline-warning" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-folder-open me-2"></i>Choose Files
                    </button>
                </div>

                <!-- File Count -->
                <div id="fileCount" class="text-center" style="display: none;">
                    <span class="file-count">
                        <i class="fas fa-images me-2"></i>
                        <span id="countText">0 files selected</span>
                    </span>
                </div>

                <!-- Preview Grid -->
                <div id="previewGrid" class="preview-grid"></div>

                <!-- Categories -->
                <div class="mt-4">
                    <label class="form-label">
                        <i class="fas fa-layer-group me-2"></i>
                        Select Category (Optional)
                    </label>
                    <div id="categoriesContainer" style="max-height: 300px; overflow-y: auto; background: rgba(20, 20, 20, 0.5); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 10px; padding: 15px;">
                        @foreach($categories as $category)
                            @if($category->children && $category->children->count() > 0)
                                <!-- Parent with children -->
                                <div class="category-parent mb-2">
                                    <div class="category-parent-header" style="display: flex; align-items: center; gap: 8px; padding: 8px; cursor: pointer; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,193,7,0.1)'" onmouseout="this.style.background='transparent'" onclick="toggleBulkCategory({{ $category->id }})">
                                        <i class="fas fa-chevron-right toggle-icon" id="toggle-icon-{{ $category->id }}" style="color: #ffc107; transition: transform 0.3s;"></i>
                                        <input type="radio" name="categories[]" id="bulk_cat_{{ $category->id }}" value="{{ $category->id }}" style="margin: 0;" onclick="event.stopPropagation(); uncheckChildren({{ $category->id }});">
                                        <label for="bulk_cat_{{ $category->id }}" style="margin: 0; font-weight: 600; cursor: pointer; flex: 1;" onclick="event.stopPropagation(); document.getElementById('bulk_cat_{{ $category->id }}').click();">{{ $category->icon ?? '📁' }} {{ $category->name }}</label>
                                    </div>
                                    <div class="category-children" id="children-{{ $category->id }}" style="display: none; padding-left: 30px; margin-top: 5px;">
                                        @foreach($category->children as $subcategory)
                                            <div style="padding: 6px 8px; margin-bottom: 4px;">
                                                <input type="radio" name="categories[]" id="bulk_cat_{{ $subcategory->id }}" value="{{ $subcategory->id }}" onclick="uncheckParent({{ $category->id }});">
                                                <label for="bulk_cat_{{ $subcategory->id }}" style="margin: 0; cursor: pointer;">{{ $subcategory->icon ?? '📌' }} {{ $subcategory->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <!-- Parent without children -->
                                <div class="mb-2" style="padding: 8px;">
                                    <input type="radio" name="categories[]" id="bulk_cat_{{ $category->id }}" value="{{ $category->id }}">
                                    <label for="bulk_cat_{{ $category->id }}" style="margin: 0; font-weight: 600; cursor: pointer;">{{ $category->icon ?? '📁' }} {{ $category->name }}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Select one category or subcategory for all wallpapers
                    </small>
                </div>

                <!-- Upload Button -->
                <div class="text-center mt-4">
                    <button type="submit" id="uploadBtn" class="btn btn-upload" disabled>
                        <i class="fas fa-upload me-2"></i>
                        Upload Wallpapers
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="upload-progress" id="uploadProgress">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 14px; font-weight: 600; color: #ffc107;">
                            <i class="fas fa-spinner fa-spin me-2"></i><span id="uploadStatusText">Preparing upload...</span>
                        </span>
                        <span id="uploadPercentage" style="font-size: 13px; color: #888;">0%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="uploadProgressBar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div id="uploadDetails" style="font-size: 12px; color: #888; margin-top: 8px; display: flex; justify-content: space-between;">
                        <span id="uploadSpeed"></span>
                        <span id="uploadETA"></span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const previewGrid = document.getElementById('previewGrid');
        const uploadBtn = document.getElementById('uploadBtn');
        const fileCount = document.getElementById('fileCount');
        const countText = document.getElementById('countText');
        const uploadProgress = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('uploadProgressBar');
        const uploadPercentage = document.getElementById('uploadPercentage');
        const uploadStatusText = document.getElementById('uploadStatusText');
        const uploadSpeed = document.getElementById('uploadSpeed');
        const uploadETA = document.getElementById('uploadETA');
        const form = document.getElementById('bulkUploadForm');

        let selectedFiles = [];

        // Drag & Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', handleDrop, false);
        dropZone.addEventListener('click', () => fileInput.click());

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            files = [...files].filter(file => file.type.startsWith('image/'));
            
            if (files.length > 30) {
                alert('Maximum 30 images allowed at once');
                files = files.slice(0, 30);
            }

            selectedFiles = files;
            updatePreview();
            updateFileCount();
        }

        function updatePreview() {
            previewGrid.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="preview-remove" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="preview-name">${file.name}</div>
                    `;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            uploadBtn.disabled = selectedFiles.length === 0;
        }

        function updateFileCount() {
            if (selectedFiles.length > 0) {
                fileCount.style.display = 'block';
                countText.textContent = `${selectedFiles.length} file${selectedFiles.length !== 1 ? 's' : ''} selected`;
            } else {
                fileCount.style.display = 'none';
            }
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            
            // Update the file input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            
            updatePreview();
            updateFileCount();
        }

        // Category toggle functions
        function toggleBulkCategory(categoryId) {
            const childrenDiv = document.getElementById('children-' + categoryId);
            const icon = document.getElementById('toggle-icon-' + categoryId);
            
            if (childrenDiv.style.display === 'none') {
                childrenDiv.style.display = 'block';
                icon.style.transform = 'rotate(90deg)';
            } else {
                childrenDiv.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function uncheckChildren(parentId) {
            const childrenDiv = document.getElementById('children-' + parentId);
            if (childrenDiv) {
                const childCheckboxes = childrenDiv.querySelectorAll('input[type="radio"]');
                childCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        }

        function uncheckParent(parentId) {
            const parentCheckbox = document.getElementById('bulk_cat_' + parentId);
            if (parentCheckbox) {
                parentCheckbox.checked = false;
            }
        }

        // Form submission with progress
        form.addEventListener('submit', function(e) {
            if (selectedFiles.length === 0) {
                e.preventDefault();
                return;
            }

            e.preventDefault();
            
            // Prepare form data
            const formData = new FormData(form);
            
            // Calculate total size
            let totalSize = 0;
            selectedFiles.forEach(file => {
                totalSize += file.size;
            });

            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            uploadProgress.style.display = 'block';
            progressBar.style.background = 'linear-gradient(90deg, #ffc107, #ff9800)';
            progressBar.style.width = '0%';
            uploadPercentage.textContent = '0%';
            uploadStatusText.innerHTML = '<i class="fas fa-cloud-upload-alt me-2"></i>Uploading to storage...';
            uploadSpeed.textContent = 'Preparing...';
            uploadETA.textContent = '';
            
            // Create XMLHttpRequest
            const xhr = new XMLHttpRequest();
            const startTime = Date.now();
            let githubUploadStarted = false;
            
            // Hide client upload progress, only show after reaching server
            xhr.upload.addEventListener('loadend', function() {
                progressBar.style.width = '0%';
                uploadPercentage.textContent = '0%';
                uploadStatusText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading Wallpaper...';
                uploadSpeed.textContent = '';
                uploadETA.textContent = '';
                githubUploadStarted = true;
                
                // Start simulating GitHub upload progress
                const totalMB = (totalSize / (1024 * 1024)).toFixed(2);
                let simulatedProgress = 0;
                const githubStartTime = Date.now();
                
                const githubInterval = setInterval(() => {
                    // Increment progress more slowly for larger files
                    const increment = totalSize > 10 * 1024 * 1024 ? Math.random() * 2 : Math.random() * 4;
                    simulatedProgress += increment;
                    
                    if (simulatedProgress > 95) simulatedProgress = 95; // Cap at 95% until server confirms
                    
                    progressBar.style.width = simulatedProgress + '%';
                    uploadPercentage.textContent = Math.round(simulatedProgress) + '%';
                    
                    // Show estimated uploaded amount
                    const uploadedMB = ((simulatedProgress / 100) * totalSize / (1024 * 1024)).toFixed(2);
                    uploadSpeed.textContent = `${uploadedMB}MB / ${totalMB}MB (${selectedFiles.length} files)`;
                    
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
                }, 500);
                
                xhr.githubInterval = githubInterval;
            });
            
            // Handle completion
            xhr.addEventListener('load', function() {
                if (xhr.githubInterval) {
                    clearInterval(xhr.githubInterval);
                }
                
                if (xhr.status === 200 || xhr.status === 302) {
                    progressBar.style.width = '100%';
                    uploadPercentage.textContent = '100%';
                    uploadStatusText.innerHTML = '<i class="fas fa-check-circle me-2"></i>Upload Complete!';
                    progressBar.style.background = 'linear-gradient(90deg, #4CAF50, #8BC34A)';
                    const totalMB = (totalSize / (1024 * 1024)).toFixed(2);
                    uploadSpeed.textContent = `${totalMB}MB / ${totalMB}MB (${selectedFiles.length} files completed)`;
                    uploadETA.textContent = '';
                    
                    // Redirect after success
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.wallpapers") }}';
                    }, 1500);
                } else {
                    progressBar.style.width = '0%';
                    uploadPercentage.textContent = '0%';
                    uploadStatusText.innerHTML = '<i class="fas fa-times-circle me-2"></i>Upload Failed';
                    progressBar.style.background = '#f44336';
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Wallpapers';
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
                
                progressBar.style.width = '0%';
                uploadPercentage.textContent = '0%';
                uploadStatusText.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Upload Error';
                progressBar.style.background = '#f44336';
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Wallpapers';
                uploadSpeed.textContent = 'Network error';
                uploadETA.textContent = '';
                alert('An error occurred during upload. Please try again.');
            });
            
            // Send request to server
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(formData);
        });
    </script>
</body>
</html>
