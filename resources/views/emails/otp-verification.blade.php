<!-- resources/views/emails/otp-verification.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background-color: #4f46e5;
            padding: 24px;
            text-align: center;
        }

        .email-header img {
            width: 180px;
            height: auto;
        }

        .email-body {
            padding: 32px 24px;
        }

        .email-greeting {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111827;
        }

        .email-message {
            font-size: 16px;
            margin-bottom: 24px;
            color: #4b5563;
        }

        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 8px;
            text-align: center;
            background-color: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            margin: 24px 0;
            color: #111827;
        }

        .email-info {
            font-size: 14px;
            color: #6b7280;
            padding: 0 16px;
            margin-bottom: 24px;
        }

        .email-footer {
            background-color: #f3f4f6;
            padding: 16px 24px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .logo-text {
            color: white;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1 class="logo-text">Signix</h1>
        </div>

        <div class="email-body">
            <div class="email-greeting">
                Hello, {{ $name }}!
            </div>

            <div class="email-message">
                Thank you for updating your email address. To verify your new email address, please use the verification code below:
            </div>

            <div class="otp-code">
                {{ $otp }}
            </div>

            <div class="email-message">
                This verification code will expire in 15 minutes. If you didn't request this code, you can safely ignore this email.
            </div>

            <div class="email-info">
                <strong>Note:</strong> This is an automated message, please do not reply to this email.
            </div>
        </div>

        <div class="email-footer">
            &copy; {{ date('Y') }} Sistem Pengesahan Dokumen Digital | All rights reserved.
        </div>
    </div>
</body>
</html>