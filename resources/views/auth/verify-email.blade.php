<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Wallpaper Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        .verify-container {
            width: 100%;
            max-width: 500px;
            padding: 40px;
            background: rgba(30, 34, 43, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 195, 0, 0.1);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.6s ease-out;
            text-align: center;
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

        .verify-icon {
            font-size: 64px;
            color: #FFC300;
            margin-bottom: 20px;
        }

        h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #FFC300;
            margin-bottom: 15px;
        }

        p {
            color: #A3A3A3;
            font-size: 15px;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .resend-btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            border: none;
            border-radius: 10px;
            color: #0d0d0d;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .resend-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 195, 0, 0.4);
        }

        .logout-link {
            margin-top: 20px;
        }

        .logout-link a {
            color: #A3A3A3;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .logout-link a:hover {
            color: #FFC300;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            animation: slideInDown 0.3s ease-out;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #4CAF50;
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
    <div class="verify-container">
        <div class="verify-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>

        <h1>Verify Your Email</h1>

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <p>
            Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to <strong>{{ auth()->user()->email }}</strong>.
        </p>

        <p>
            If you didn't receive the email, we'll gladly send you another.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="resend-btn">
                <i class="fas fa-paper-plane"></i> Resend Verification Email
            </button>
        </form>

        <div class="logout-link">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #A3A3A3; cursor: pointer; font-size: 13px; text-decoration: underline;">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
