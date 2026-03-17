<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" sizes="32x32">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0d0d0d 0%, #1a1a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #EDEDED;
            padding: 20px;
        }

        .edit-container {
            width: 100%;
            max-width: 500px;
            background: rgba(30, 34, 43, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 195, 0, 0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .edit-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .edit-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #FFC300;
            margin-bottom: 10px;
        }

        .edit-header p {
            color: #A3A3A3;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #EDEDED;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 195, 0, 0.2);
            border-radius: 10px;
            color: #EDEDED;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-color: #FFC300;
            box-shadow: 0 0 15px rgba(255, 195, 0, 0.2);
        }

        .char-counter {
            font-size: 12px;
            color: #A3A3A3;
            margin-top: 5px;
            text-align: right;
        }

        .error-message {
            color: #FF6B6B;
            font-size: 12px;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .save-btn,
        .cancel-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .save-btn {
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            color: #0d0d0d;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 195, 0, 0.4);
        }

        .cancel-btn {
            background: rgba(30, 34, 43, 0.8);
            color: #FFC300;
            border: 1px solid #FFC300;
        }

        .cancel-btn:hover {
            background: rgba(255, 195, 0, 0.1);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            animation: slideInDown 0.3s ease-out;
        }

        .alert-danger {
            background: rgba(255, 107, 107, 0.1);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #FF6B6B;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-header">
            <h1><i class="fas fa-edit"></i> Edit Category</h1>
            <p>Update category information</p>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        @endif

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            @if($category->parent_id)
                <!-- Editing a subcategory -->
                <div class="form-group">
                    <label>Parent Category</label>
                    <input 
                        type="text" 
                        disabled
                        value="{{ $category->parent->name ?? 'N/A' }}"
                        style="width: 100%; padding: 10px; background: rgba(255,195,0,0.1); border: 1px solid rgba(255,195,0,0.3); border-radius: 8px; color: #FFC300; font-size: 14px; font-weight: 600;"
                    >
                    <small style="color: #888; font-size: 12px; margin-top: 4px; display: block;">This is a subcategory under {{ $category->parent->name }}</small>
                </div>

                <div class="form-group">
                    <label for="name">Sub Category Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $category->name) }}" 
                        placeholder="Enter subcategory name" 
                        maxlength="255"
                        required
                    >
                </div>

                <input type="hidden" name="parent_id" value="{{ $category->parent_id }}">
            @else
                <!-- Editing a main category -->
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $category->name) }}" 
                        placeholder="Enter category name" 
                        maxlength="255"
                        required
                    >
                </div>

                <!-- Show existing subcategories -->
                @if($category->children && $category->children->count() > 0)
                    <div class="form-group">
                        <label>Existing Sub Categories ({{ $category->children->count() }})</label>
                        <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,195,0,0.1); border-radius: 8px; padding: 12px; max-height: 150px; overflow-y: auto;">
                            @foreach($category->children as $child)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 6px 8px; margin-bottom: 4px; background: rgba(255,255,255,0.03); border-radius: 6px;">
                                    <span style="color: #ccc; font-size: 13px;">{{ $child->icon ?? '📌' }} {{ $child->name }}</span>
                                    <a href="{{ route('admin.categories.edit', $child) }}" style="color: #64C8FF; font-size: 11px; text-decoration: none;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add new subcategories -->
                <div class="form-group">
                    <label for="subcategories">Add Sub Categories (one per line)</label>
                    <textarea 
                        id="subcategories" 
                        name="subcategories" 
                        class="form-control"
                        rows="5"
                        placeholder="Enter subcategory names, one per line. Example:&#10;Naruto&#10;Solo Leveling&#10;Dragon Ball&#10;One Piece"
                        style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,195,0,0.2); border-radius: 8px; color: #EDEDED; font-size: 14px; font-family: 'Inter', sans-serif; resize: vertical;"
                    >{{ old('subcategories') }}</textarea>
                    <small style="color: #888; font-size: 12px; margin-top: 4px; display: block;">Type each subcategory name on a new line. They will be created under this category.</small>
                </div>
            @endif

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Enter category description"
                    maxlength="500"
                    onkeyup="updateCounter(this, 'descCounter', 500)"
                >{{ old('description', $category->description) }}</textarea>
                <div class="char-counter">
                    <span id="descCounter">0</span>/500
                </div>
            </div>

            <div class="form-group">
                <label for="icon">Icon/Emoji</label>
                <input 
                    type="text" 
                    id="icon" 
                    name="icon" 
                    value="{{ old('icon', $category->icon) }}" 
                    placeholder="e.g., 🎨 or 📁"
                    maxlength="50"
                >
            </div>

            <div class="button-group">
                <a href="{{ route('admin.categories') }}" class="cancel-btn">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="save-btn">
                    <i class="fas fa-check"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateCounter(textarea, counterId, maxLength) {
            document.getElementById(counterId).textContent = textarea.value.length;
        }

        // Initialize counters
        const descTextarea = document.getElementById('description');
        if (descTextarea) {
            updateCounter(descTextarea, 'descCounter', 500);
        }
    </script>
</body>
</html>
