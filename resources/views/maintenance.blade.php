{{-- resources/views/maintenance.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - {{ siteName() }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(siteFavicon())
        <link rel="icon" href="{{ siteFavicon() }}" type="image/x-icon">
    @endif
</head>

<body>
    <div class="d-flex align-items-center justify-content-center vh-100 bg-light">
        <div class="text-center">
            @if(siteLogo())
                <img src="{{ siteLogo() }}" alt="{{ siteName() }}" height="80" class="mb-4">
            @endif
            <div class="mb-4">
                <i class="fas fa-tools text-warning" style="font-size: 80px;"></i>
            </div>
            <h1 class="display-4 fw-bold">Under Maintenance</h1>
            <p class="lead text-muted">{{ $message ?? 'We are currently under maintenance. Please check back later.' }}
            </p>
            <p class="text-muted small">
                <i class="fas fa-clock me-1"></i> Expected to be back soon
            </p>
            <div class="mt-4">
                <a href="mailto:{{ siteEmail() }}" class="btn btn-primary">
                    <i class="fas fa-envelope me-2"></i> Contact Support
                </a>
            </div>
            <div class="mt-4">
                <small class="text-muted">{{ copyrightText() }}</small>
            </div>
        </div>
    </div>
</body>

</html>
