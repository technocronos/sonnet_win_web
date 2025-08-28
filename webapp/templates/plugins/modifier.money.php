<?php

/**
 * 3桁ごとのカンマ区切りにして返す。
 */
function smarty_modifier_money($int) {

    return number_format($int);
}
