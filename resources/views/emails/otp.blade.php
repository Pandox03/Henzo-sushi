<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Henzo Sushi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header .sushi {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .otp-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 32px;
            font-weight: bold;
            padding: 20px;
            border-radius: 10px;
            letter-spacing: 5px;
            margin: 30px 0;
            display: inline-block;
            min-width: 200px;
        }
        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="sushi">🍣</div>
            <h1>Henzo Sushi</h1>
            <p>Email Verification</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }}!</h2>
            
            <p class="message">
                Thank you for registering with Henzo Sushi! To complete your registration, 
                please verify your email address using the OTP code below:
            </p>
            
            <div class="otp-code">{{ $otp }}</div>
            
            <p class="message">
                Enter this code in the verification form to activate your account.
            </p>
            
            <div class="warning">
                <strong>Important:</strong> This code will expire in 10 minutes. 
                If you didn't request this verification, please ignore this email.
            </div>
            
            <p class="message">
                Once verified, you'll be able to order delicious sushi and track your deliveries!
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Henzo Sushi. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>


