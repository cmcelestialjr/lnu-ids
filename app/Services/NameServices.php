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
    public function firstname_middlename_last($lastname,$firstname,$middlename,$extname){
        if($middlename!=''){
            $middlename = '-'.$middlename;
        }
        if($extname!=''){
            $extname = ' '.$extname;
        }
        $name = $firstname.' '.$lastname.$middlename.$extname;
        return $this->capitalize_first($name); 
    }
    public function lastname_middlename_last($lastname,$firstname,$middlename,$extname){
        if($middlename!=''){
            $middlename = '-'.$middlename;
        }
        if($extname!=''){
            $extname = ' '.$extname;
        }
        $name = $lastname.$middlename.', '.$firstname.$extname;
        return $this->capitalize_first($name);
    }
    public function username($lastname,$firstname,$middlename,$extname){
        if($middlename!=''){
            $middlename = mb_strtolower($middlename[0]);
        }
        if($extname!=''){
            $extname = mb_strtolower($extname);
        }
        $explode = explode(' ',$firstname);
        
        $name = mb_strtolower($explode[0]).$middlename.mb_strtolower(str_replace(' ','',$lastname)).$extname;
        return $name;
    }
    private function capitalize_first($name){
        return mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");
    }
}

?>