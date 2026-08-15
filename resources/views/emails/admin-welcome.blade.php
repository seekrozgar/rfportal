<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Rozgar Finder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            padding: 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            color: #fff;
        }

        .header h1 {
            margin: 0;
        }

        .content {
            padding: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #11998e;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Rozgar Finder</h1>
            <p>Admin Panel Access</p>
        </div>
        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            <p>Your account has been created as <strong>{{ ucfirst($user->role) }}</strong>.</p>

            <p><strong>Login Credentials:</strong></p>
            <ul>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Password:</strong> {{ $password }}</li>
            </ul>

            <p>Please click the button below to verify your email and activate your account:</p>

            <p style="text-align: center; color:#ffffff;">
                <a href="{{ $verificationUrl }}" class="btn">Verify Email Address</a>
            </p>

            <p>If you did not request this account, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Rozgar Finder. All rights reserved.</p>
            <p>Support: support@rozgarfinder.com</p>
        </div>
    </div>
</body>

</html>