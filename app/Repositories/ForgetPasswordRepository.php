<?php

namespace App\Repositories;

use App\Models\User;
use App\Mail\LoginAlertMail;
use App\Repositories\Contracts\ForgetPasswordRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordRepository implements ForgetPasswordRepositoryInterface
{
    public function login(array $data): User
    {
        $user = User::where('email', $data['email'])->first();

        if ($user && Auth::attempt(['email' => $data['email'], 'password' => $data['password']]) && $user->role == 'admin') {

            // Send email
            $ip = request()->ip();
            $agent = request()->userAgent();
            $browser = get_browser_name($agent);
            $platform = get_os_name($agent);

            if (Mail::to($user->email)->send(new LoginAlertMail($user, $ip, $browser, $platform))) {

                return $user;
            } else {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['email not sent'],
                ]);
            }
        }

        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
}
