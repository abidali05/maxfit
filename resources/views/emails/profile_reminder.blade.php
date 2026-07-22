<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Complete Your Profile</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #FF416C, #FF4B2B);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px 40px;
            line-height: 1.6;
        }
        .content p {
            margin: 0 0 16px;
            font-size: 16px;
        }
        .warning-box {
            background-color: #fff9e6;
            border-left: 4px solid #ffcc00;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box p {
            margin: 0;
            color: #8a6d3b;
            font-weight: 500;
        }
        .fields-list {
            background-color: #f9f9f9;
            border: 1px solid #eeeeee;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .fields-list ul {
            margin: 0;
            padding-left: 20px;
        }
        .fields-list li {
            margin-bottom: 8px;
            color: #555555;
            font-size: 14px;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0 10px;
        }
        .btn {
            background: linear-gradient(135deg, #FF416C, #FF4B2B);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3);
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Action Required: Complete Your Registration</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            <p>Thank you for registering on <strong>MaxFit</strong>! We noticed that your profile is currently incomplete.</p>
            
            <div class="warning-box">
                <p>Please complete your missing information within the next <strong>24 hours</strong>. Otherwise, we will suspend and delete your account from our database.</p>
            </div>

            @if(count($missingFields) > 0)
                <p>The following information is required and currently missing:</p>
                <div class="fields-list">
                    <ul>
                        @foreach($missingFields as $field)
                            <li><strong>{{ $field }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p>Please log in and ensure that both your personal profile details and physical assessment steps are fully completed.</p>
            @endif

            <div class="btn-container">
                <a href="{{ config('app.url') }}" class="btn">Complete My Profile</a>
            </div>
        </div>
        <div class="footer">
            <p>If you have already updated your information, please ignore this email.</p>
            <p>&copy; {{ date('Y') }} MaxFit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
