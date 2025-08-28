<?php

/**
 * 課金であることを示すマークを出力する。
 */
function smarty_function_charge_mark($params, $smarty) {

    switch(PLATFORM_TYPE) {
        case 'gree':
            return '<img src="http://i.m.gree.jp/img/icon/coin_16.gif" width="16" height="16" />';
        case 'mixi':
        case 'mbga':
            return '';
    }
}
