<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitation to Join Athlete Group</title>
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
            background: linear-gradient(135deg, #1e3c72, #2a5298);
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
        .info-box {
            background-color: #f0f4f8;
            border-left: 4px solid #2a5298;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0 0 8px;
            color: #2a5298;
            font-weight: 500;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
            color: #4a5568;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0 10px;
        }
        .btn {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(42, 82, 152, 0.3);
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
            <h1>New Athlete Group Invitation</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            <p>You have been invited to join the athlete group <strong>{{ $group->name }}</strong> created by coach <strong>{{ $group->coach->name }}</strong> on MaxFit.</p>
            
            <div class="info-box">
                <p><strong>Group Details:</strong></p>
                <ul>
                    <li><strong>Group Name:</strong> {{ $group->name }}</li>
                    <li><strong>Coach:</strong> {{ $group->coach->name }}</li>
                    @if($group->age_group)
                        <li><strong>Target Age Group:</strong> {{ $group->age_group }}</li>
                    @endif
                    @if($group->gender)
                        <li><strong>Gender Category:</strong> {{ $group->gender }}</li>
                    @endif
                </ul>
            </div>

            <p>Please open the MaxFit mobile application to view and respond to this invitation.</p>

            <div class="btn-container">
                <a href="{{ config('app.url') }}" class="btn">Open MaxFit App</a>
            </div>
        </div>
        <div class="footer">
            <p>If you did not expect this invitation, you can decline it inside the app.</p>
            <p>&copy; {{ date('Y') }} MaxFit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
