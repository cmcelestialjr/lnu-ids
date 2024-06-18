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
    public function token_w_upper($length){
        $token = "";
        $codeAlphabet = "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= strtoupper("abcdefghijklmnpqrstuvwxyz");
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++) {
         $token .= $codeAlphabet[rand(0, $max-1)];
        }
        return $token;
    }
    public function token_w_upper_special_char($length){
        $token = "";
        $codeAlphabet = "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= strtoupper("abcdefghijklmnpqrstuvwxyz");
        $codeAlphabet .= "0123456789";
        $codeAlphabet .= "!@#$%^&*";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++) {
         $token .= $codeAlphabet[rand(0, $max-1)];
        }
        return $token;
    }
    public function num_only($length){
        $token = "";
        $codeAlphabet = "0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++) {
         $token .= $codeAlphabet[rand(0, $max-1)];
        }
        return $token;
    }
}

?>
