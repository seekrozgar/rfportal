<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>404 - Page Not Found</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }

        .error-content {
            text-align: center;
            padding: 40px;
        }

        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #e74c3c;
            line-height: 1;
        }

        .error-title {
            font-size: 36px;
            font-weight: 600;
            color: #2c3e50;
            margin: 20px 0;
        }

        .error-desc {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        .btn-home {
            padding: 12px 40px;
            font-size: 18px;
            border-radius: 50px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background: #2980b9;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.4);
        }
    </style>
</head>

<body>
    <div class="error-page">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-desc">Oops! The page you are looking for does not exist or has been moved.</p>
            <a href="{{ route('home') }}" class="btn-home">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>
</body>

</html>