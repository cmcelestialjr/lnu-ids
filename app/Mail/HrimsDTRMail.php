<?php

namespace App\Mail;

use App\Models\DTRlogs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HrimsDTRMail extends Mailable
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
        $date = date("Y-m-d", strtotime($this->details['dateTime']));
        $logs = DTRlogs::where('id_no', $this->details['id_no'])
            ->whereRaw("DATE(dateTime) = ?", [$date])
            ->get();
        return $this->subject('DTR - '.date('m/d/Y',strtotime($date)))
                    ->view('emails.hrims_dtr')
                    ->with([
                        'logs' => $logs,
                        'date' => date('m/d/Y',strtotime($date))
                    ]);
    }
}

