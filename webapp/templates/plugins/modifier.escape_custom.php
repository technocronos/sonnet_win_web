<?php


function smarty_modifier_escape_custom($string)
{
    if(is_string($string))
        return htmlspecialchars($string, ENT_QUOTES);
    else
        return $string;
}
