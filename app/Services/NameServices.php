<?php
namespace App\Services;

class NameServices
{
    public function firstname($lastname,$firstname,$middlename,$extname){
        if($middlename!=''){
            $middlename = ' '.$middlename[0].'.';
        }
        if($extname!=''){
            $extname = ' '.$extname;
        }
        $name = $firstname.$middlename.' '.$lastname.$extname;
        return $name;
    }
    public function lastname($lastname,$firstname,$middlename,$extname){
        if($middlename!=''){
            $middlename = ' '.$middlename[0].'.';
        }
        if($extname!=''){
            $extname = ' '.$extname;
        }
        $name = $lastname.', '.$firstname.$middlename.$extname;
        return $name;
    }
}

?>