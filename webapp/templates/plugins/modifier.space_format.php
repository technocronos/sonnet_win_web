<?php

/**
 * Smarty標準の string_format と同じだが、スペース文字を &nbsp; に置き換える点が
 * 異なる。
 */
function smarty_modifier_space_format($string, $format){

    return str_replace(' ', '&nbsp;', sprintf($format, $string));
}
