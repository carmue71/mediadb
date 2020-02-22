<?php
function esc(String $str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Purpose: Tries to cut a string at a blank
 * 
 */
function cutStr($str, int $maxlen, int $tolerance=10, String $addon=' ...'){
    if ( $str == null)
        return "";
    if ( strlen($str) <= $maxlen)
        return $str; //nothing to do
    $i = 0;
    //
    while ( $str[$maxlen-$i]<>" " && $maxlen-$i > 0 && $i < $tolerance  )
        $i++;
    
    return substr($str, 0, $maxlen-$i).$addon;
}