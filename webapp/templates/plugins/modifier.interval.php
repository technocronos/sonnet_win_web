<?php

/**
 * 引数に受け取った秒数を時間間隔として表示を行う。
 */
function smarty_modifier_interval($value) {

    $hour = (int)floor($value / (60*60));
    $minute = (int)floor($value / 60) - ($hour*60);

    $result = '';
    if($hour) $result .= "{$hour}時間";
    $result .= "{$minute}分";

    return $result;
}
