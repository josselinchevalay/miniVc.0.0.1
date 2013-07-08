<?php
class EscapeHelper{
    public static function escape($string){
        return htmlspecialchars($string);
    }
}
?>