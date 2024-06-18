<?php

namespace App\Mail;

use App\Models\DTRlogs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        $name = $this->details['name'];
        $reference_no = $this->details['reference_no'];
        $dateTime = $this->details['dateTime'];
        $temporary_password = $this->details['temporary_password'];

        return $this->subject('Forgot Password - LNU-IDS')
                    ->view('emails.forgot_password')
                    ->with([
                        'name' => $name,
                        'reference_no' => $reference_no,
                        'dateTime' => date('M d, Y h:i:s a', strtotime($dateTime)),
                        'temporary_password' => $temporary_password,
                    ]);
    }
}

