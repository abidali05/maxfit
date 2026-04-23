<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px; border-radius: 8px; color: #333;">
    <h2 style="text-align: center; color: #57B9FF; margin-bottom: 30px;">Super Admin Login Alert</h2>

    <p style="font-size: 16px;">Hi <strong>{{ $user->name }}</strong>,</p>

    <p style="font-size: 15px;">
        A new login was just detected to your Super Admin account on <strong>MaxFit</strong>.
        Please review the login details below:
    </p>

    <div style="background-color: #ffffff; padding: 20px; border-radius: 6px; border: 1px solid #ddd; margin-top: 20px;">
        <p style="margin: 0 0 10px;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin: 0 0 10px;"><strong>IP Address:</strong> {{ $ip }}</p>
        <p style="margin: 0 0 10px;"><strong>Browser:</strong> {{ $browser }}</p>
        <p style="margin: 0 0 10px;"><strong>Platform:</strong> {{ $platform }}</p>
        <p style="margin: 0;"><strong>Time:</strong> {{ now()->toDayDateTimeString() }}</p>
    </div>

    <p style="font-size: 15px; margin-top: 25px;">
        If this was <strong>not you</strong>, we highly recommend you <a href="{{ route('password.request') }}" style="color: #57B9FF;">reset your password</a> immediately and contact security.
    </p>

    <p style="font-size: 15px; margin-top: 30px;">Regards,<br><strong>MaxFit Security Team</strong></p>
</div>
