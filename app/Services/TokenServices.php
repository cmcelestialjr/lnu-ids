<?php
namespace App\Services;

class TokenServices
{
    public function token($length){
        $token = "";
        $codeAlphabet = "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);
        
        for ($i=0; $i < $length; $i++) {
         $token .= $codeAlphabet[rand(0, $max-1)];
        }
        return $token;
    }
}

?>