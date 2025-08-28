<?php

/**
 * ユーザページでの日時表示を行う。
 * 18時間以内なら時間を、それ以外は日付を表示する。
 */
function smarty_modifier_datetime($timestamp, $ifNull = '') {

    // 引数で指定された日時をタイムスタンプに統一する。
    $timestamp = DateTimeUtil::normalize($timestamp);

    // 18時間以内なら時間を、それ以外は日付を返す。
    if(time() < $timestamp + 18*60*60)
        return date('H:i', $timestamp);
    else
        return date('m/d', $timestamp);
}
