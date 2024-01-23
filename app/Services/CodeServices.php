<?php
namespace App\Services;
class CodeServices
{
    public function encode($id){
        $no_0 = $id[0];
        $no_1 = $id[1];
        $no_2 = $id[2];
        $no_3 = $id[3];
        $no_4 = $id[7];
        $no_5 = $id[8];

        $encode_1 = $this->token(2).str_pad($id[4]+$no_0+$no_5,2,"0",STR_PAD_LEFT);
        $encode_2 = $this->token(1).str_pad($id[5]+$no_0+$no_2+$no_4,2,"0",STR_PAD_LEFT);
        $encode_3 = $this->token(1).str_pad($id[6]+$no_1+$no_4+$no_5,2,"0",STR_PAD_LEFT);
        $encode_4 = str_pad($id[7]+$no_1+$no_3+$no_4,2,"0",STR_PAD_LEFT).$this->token(1);
        $encode_5 = str_pad($id[8]+$no_0+$no_5,2,"0",STR_PAD_LEFT).$this->token(1);

        $encoded = $encode_1.$encode_2.$encode_3.$encode_4.$encode_5;

        return $encoded;
    }
    public function decode($code,$id){
        $decoded = 'error';
        if(strlen($code)==17 && strlen($id)==9){
            $no_0 = $id[0];
            $no_1 = $id[1];
            $no_2 = $id[2];
            $no_3 = $id[3];
            $no_4 = $id[7];
            $no_5 = $id[8];
            
            $code_1 = ($code[2].$code[3])-$no_0-$no_5;
            $code_2 = ($code[5].$code[6])-$no_0-$no_2-$no_4;
            $code_3 = ($code[8].$code[9])-$no_1-$no_4-$no_5;
            $code_4 = ($code[10].$code[11])-$no_1-$no_3-$no_4;
            $code_5 = ($code[13].$code[14])-$no_0-$no_5;

            $decode = $no_0.$no_1.$no_2.$no_3.$code_1.$code_2.$code_3.$code_4.$code_5;

            if($id==$decode){
                $decoded = 'success';
            }
        }
        
        return $decoded;
    }
    public function encode_acct($account){
        $numberString = strval($account);
        $result = '';
        for ($i = 0; $i < strlen($numberString); $i++) {
            $result .= $numberString[$i];
            if ($i % 2 == 1) {
                $result .= $this->token(2);
            }
        }
        return $this->token(3).$result;
    }
    public function decode_acct($account){
        $account = substr($account, 3);
        $decoded = '';
        $len = strlen($account);
        for ($i = 0; $i < $len; $i++) {
            $decoded .= $account[$i];
            if ($i % 4 == 1 || $i % 4 == 2) {
                $i += 2;
            }
        }
        return $decoded;
    }
    // public function encode_acct($account){
    //     $result = $account+281694573;
    //     // $numberString = strval($account);
    //     // $result = '';
    //     // for ($i = 0; $i < strlen($numberString); $i++) {
    //     //     $result .= $numberString[$i];
    //     //     if ($i % 2 == 1) {
    //     //         $result .= $this->token(2);
    //     //     }
    //     // }
    //     return $this->token(2).$result.$this->token(2);
    // }
    // public function decode_acct($account){
    //     $first = substr($account, 2);
    //     $second = substr($first, 0, -2);
    //     $result = $second-281694573;
    //     // $account = substr($account, 3);
    //     // $decoded = '';
    //     // $len = strlen($account);
    //     // for ($i = 0; $i < $len; $i++) {
    //     //     $decoded .= $account[$i];
    //     //     if ($i % 4 == 1 || $i % 4 == 2) {
    //     //         $i += 2;
    //     //     }
    //     // }
    //     return $result;
    // }
    private function token($length){
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