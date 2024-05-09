<?php
namespace App\Jobs;

use App\Mail\HrimsDTRMail;
use App\Models\_PersonalInfo;
use App\Models\DTRlogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class HrimsDTRJob implements ShouldQueue
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
        // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
        // Config::set('mail.mailers.smtp.port', 587);
        // Config::set('mail.mailers.smtp.username', 'hr@lnu.edu.ph');
        // Config::set('mail.mailers.smtp.password', 'LNU@hrmo2024');
        // Config::set('mail.mailers.smtp.encryption', 'tls');
        $id_no = $this->details['id_no'];
        $type = $this->details['type'];
        $staff = _PersonalInfo::whereHas('user', function ($q) use ($id_no) {
            $q->where('id_no', $id_no);
        })->first();
        if ($staff) {
            $date = date("Y-m-d", strtotime($this->details['dateTime']));
            $logs = DTRlogs::where('id_no', $id_no)
                ->whereRaw("DATE(dateTime) = ?", [$date])
                ->get();
            $logs_count = $logs->count();
            $x = 0;
            if($logs_count==1){
                $x++;
            }else{
                if($type==1){
                    $x++;
                }
            }
            if($x>0){
                $email = $staff->email_official;
                if($email){
                    Mail::to($email)->send(new HrimsDTRMail($this->details));
                }
            }
        }
    }
}

