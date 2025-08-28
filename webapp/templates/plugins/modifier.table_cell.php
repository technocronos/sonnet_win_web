<?php

/**
 * 引数に受け取った値を<table>のセルとして出力する。
 * カラの値だった場合や、数値の場合の右寄せなどを行う。
 * 第二引数は、カラだった場合の値。
 */
function smarty_modifier_table_cell($value, $ifEmpty = null) {

    if( (string)$value === '' ) {
        if( is_null($ifEmpty) )
            return '<td>&nbsp</td>';
        else
            $value = $ifEmpty;
    }

    $align = is_numeric($value) ? 'right' : 'left';
    return sprintf(
        '<td style="text-align:%s">%s</td>',
        $align,
        nl2br($value)
    );
}
