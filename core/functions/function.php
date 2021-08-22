<?php
/*
clean_for_bdd();
getClassStateTome();
*/


function clean_for_bdd(string|int $string):string|int
{
    if(ctype_digit($string))
        return intval($string);
   
    $string = htmlentities($string, ENT_QUOTES); 
    $string = strip_tags($string);
    $string = trim($string);
    return $string;
}

function getClassStateTome(int $state):string
{
    $result = match($state){
        0, 2 => 'haventTome',
        1 => '', // border border-success
        default => 'haventTome',
    };

    return $result;
}

function getClassBorderColorStateTome(int $state):string
{
    $result = match($state){
        0 => 'danger',
        1 => 'success', // border border-success
        2 => 'warning', // border border-success
        default => 'danger',
    };

    return $result;   
}