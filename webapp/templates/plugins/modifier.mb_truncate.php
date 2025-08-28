<?php

/**
 * 指定の長さで文字列を切り詰める。半角文字は1カウント、全角文字は2カウント。
 * 第３引数は切り詰められた場合に表示される切り詰めマークを指定する。
 */
function smarty_modifier_mb_truncate($string, $width = 80, $trimmarker = '...') {

    if(!$width)
        return $string;
    else
        return mb_strimwidth($string, 0, $width, $trimmarker, 'UTF-8');
}
