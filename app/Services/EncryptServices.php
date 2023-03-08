<?php
namespace App\Services;
use Illuminate\Support\Facades\Crypt;
use App\Services\TokenServices;
class EncryptServices
{
    public function encrypt($id){
        $tokens = new TokenServices;
        $token1 = $tokens->token(2);
        $token2 = $tokens->token(2);
        $encrypt = Crypt::encryptString($id);
        $encrypted = $token1.$encrypt.$token2;
        
        return $encrypted;
    }
    public function decrypt($id){
        if($id==''){
            $decrypted = '';
        }else{
            $remove_first = substr($id, 2);
            $remove_second = substr($remove_first, 0, -2);
            $decrypted = Crypt::decryptString($remove_second);
        }
        return $decrypted;
    }
}

?>