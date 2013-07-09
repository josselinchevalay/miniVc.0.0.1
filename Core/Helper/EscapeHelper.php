<?php
class EscapeHelper{
    public static function escape($string){
        if(is_string($string))
        {
           return mysql_real_escape_string(htmlspecialchars($string)); 
        }elseif (is_int($string)){
            return intval($string);
        }       
    }
}
?>