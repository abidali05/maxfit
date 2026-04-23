<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ip;
    public $browser;
    public $platform;

    public function __construct($user, $ip, $browser, $platform)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->browser = $browser;
        $this->platform = $platform;
    }

    public function build()
    {
        return $this->subject('New Login Detected')
            ->view('emails.login-alert');
    }
}

?>