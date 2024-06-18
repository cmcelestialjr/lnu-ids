<?php
namespace App\Jobs;

use App\Mail\ForgotPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class ForgotPasswordJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function handle()
    {
        // Set up SMTP configuration

        Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
        Config::set('mail.mailers.smtp.port', 587);
        Config::set('mail.mailers.smtp.username', 'hr@lnu.edu.ph');
        Config::set('mail.mailers.smtp.password', 'HRMO@!TSO@lnu@2024');
        Config::set('mail.mailers.smtp.encryption', 'tls');
        $email = $this->details['email'];
        Mail::to($email)->send(new ForgotPasswordMail($this->details));
    }
}

