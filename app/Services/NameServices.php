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
        return $this->capitalize_first($name); 
    }
    public function lastname($lastname,$firstname,$middlename,$extname){
        if($middlename!=''){
            $middlename = ' '.$middlename[0].'.';
        }
        if($extname!=''){
            $extname = ' '.$extname;
        }
        $name = $lastname.', '.$firstname.$extname.$middlename;
        return $this->capitalize_first($name);
    }
    private function capitalize_first($name){
        return mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");
    }
}

?>