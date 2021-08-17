<?php
/*
clean_for_bdd();
*/


function clean_for_bdd($string)
{
    if(ctype_digit($string))
    {
        $string = intval($string);
    }else{      
        $string = htmlentities($string, ENT_QUOTES);        
        $string = strip_tags($string);
        $string = trim($string);       
    }   
    return $string;
}