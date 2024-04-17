<?php
namespace App\Services;
use Illuminate\Support\Facades\Crypt;
class PasswordServices
{
    public function master(){
        return 'LNU@'.date('Y');
    }
}

?>