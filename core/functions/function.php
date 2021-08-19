<?php
/*
clean_for_bdd();
getClassStateTome();
*/


function clean_for_bdd(string|int $string):string|int
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

function getClassStateTome(int $state):string
{
    $result = match($state){
        0, 2 => 'haventTome', // Mettre bordure jaune pour les "wish" ?
        1 => '', // border border-success
        default => 'haventTome',
    };

    return $result;
}