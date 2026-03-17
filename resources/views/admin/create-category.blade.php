<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Admin Panel</title>
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

        .create-container {
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

        .create-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .create-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #FFC300;
            margin-bottom: 10px;
        }

        .create-header p {
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
        textarea,
        select {
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

        select {
            cursor: pointer;
        }

        select option {
            background: #1a1a2e;
            color: #EDEDED;
            padding: 10px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
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

        .icon-picker {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .icon-option {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 195, 0, 0.2);
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .icon-option:hover {
            background: rgba(255, 195, 0, 0.1);
            border-color: #FFC300;
            transform: scale(1.1);
        }

        .icon-option.selected {
            background: rgba(255, 195, 0, 0.2);
            border-color: #FFC300;
            box-shadow: 0 0 10px rgba(255, 195, 0, 0.3);
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
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
    <div class="create-container">
        <div class="create-header">
            <h1><i class="fas fa-plus-circle"></i> Add New Category</h1>
            <p>Create a new category for wallpapers</p>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Category Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    placeholder="Enter category name" 
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="parent_id">
                    <i class="fas fa-folder-tree"></i> Parent Category (Optional)
                </label>
                <select id="parent_id" name="parent_id">
                    <option value="">-- Create as Main Category --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->icon }} {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                <small style="color: #A3A3A3; font-size: 12px; margin-top: 5px; display: block;">
                    Select a parent category to create this as a subcategory
                </small>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Enter category description"
                    maxlength="500"
                    onkeyup="updateCounter(this, 'descCounter', 500)"
                >{{ old('description') }}</textarea>
                <div class="char-counter">
                    <span id="descCounter">0</span>/500
                </div>
            </div>

            <div class="form-group">
                <label>Icon/Emoji</label>
                <div class="icon-picker">
                    <div class="icon-option" onclick="selectIcon('🎨', this)">🎨</div>
                    <div class="icon-option" onclick="selectIcon('📁', this)">📁</div>
                    <div class="icon-option" onclick="selectIcon('🌆', this)">🌆</div>
                    <div class="icon-option" onclick="selectIcon('🎮', this)">🎮</div>
                    <div class="icon-option" onclick="selectIcon('✨', this)">✨</div>
                    <div class="icon-option" onclick="selectIcon('🌸', this)">🌸</div>
                    <div class="icon-option" onclick="selectIcon('🚀', this)">🚀</div>
                    <div class="icon-option" onclick="selectIcon('🎭', this)">🎭</div>
                </div>
                <input 
                    type="text" 
                    id="icon" 
                    name="icon" 
                    value="{{ old('icon') }}" 
                    placeholder="Or enter custom emoji"
                    maxlength="50"
                >
            </div>

            <div class="button-group">
                <a href="{{ route('admin.categories') }}" class="cancel-btn">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="save-btn">
                    <i class="fas fa-check"></i> Create Category
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateCounter(textarea, counterId, maxLength) {
            document.getElementById(counterId).textContent = textarea.value.length;
        }

        function selectIcon(icon, element) {
            document.querySelectorAll('.icon-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('icon').value = icon;
        }

        // Initialize counters
        const descTextarea = document.getElementById('description');
        if (descTextarea) {
            updateCounter(descTextarea, 'descCounter', 500);
        }
    </script>
</body>
</html>
