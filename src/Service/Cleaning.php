<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Cleaning extends Controller
{
    public function P2E($string)
    {
        $persian_digits = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $arabian_digits = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
        $english_digits = array('0','1','2','3','4','5','6','7','8','9');

        if(is_array($string)){
            foreach ($string as $key => $value){
                $string[$key] = str_replace($persian_digits,$english_digits, $value);
                $string[$key] = str_replace($arabian_digits,$english_digits, $value);
            }
        }else{
            $string = str_replace($persian_digits,$english_digits, $string);
            $string = str_replace($arabian_digits,$english_digits, $string);
        }

        return $string;
    }

    public function E2P($string){

        $persian_digits = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $arabian_digits = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
        $english_digits = array('0','1','2','3','4','5','6','7','8','9');

        if(is_array($string)){
            foreach ($string as $key => $value){
                $string[$key] = str_replace($english_digits,$arabian_digits, $value);
                $string[$key] = str_replace($english_digits,$persian_digits, $value);
            }
        }else{
            $string = str_replace($english_digits,$arabian_digits, $string);
            $string = str_replace($english_digits,$persian_digits, $string);
        }

        return $string;

    }

    public function String($string){
        $string = trim($string);
        $string = strip_tags($string);
        return str_replace(array('ي','ك'),array('ی','ک'),$string);
    }

}
