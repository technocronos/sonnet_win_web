<?php

/**
 * 数値の先頭に+/-を付けて出力する。
 */
function smarty_modifier_plus_minus($numeric) {

    if( !is_int($numeric)  &&  !is_float($numeric) )
        $numeric = (false === strpos('.', $numeric)) ? (int)$numeric : (float)$numeric;

    if($numeric > 0)
        return '+' . (string)$numeric;
    else if($numeric < 0)
        return (string)$numeric;
    else
        return '±' . (string)$numeric;
}
