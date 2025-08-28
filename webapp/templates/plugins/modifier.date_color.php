<?php

/**
 * smarty_modifier_date_exと同じだが、土、日の場合に文字色を変える。
 */
function smarty_modifier_date_color($timestamp, $format, $ifNull = '') {

    $timestamp = DateTimeUtil::normalize($timestamp, null);

    $output = DateTimeUtil::dateEx($format, $timestamp, $ifNull);

    switch(idate('w', $timestamp)) {
        case 0:  $color = 'red';   break;
        case 6:  $color = 'blue';  break;
        default: $color = '';
    }

    if($color)
        return sprintf('<span style="color:%s">%s</span>', $color, $output);
    else
        return $output;
}
