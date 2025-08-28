<?php

/**
 * パラメータで指定された関数をコールして、戻り値を返す。
 *
 * パラメータ一覧)
 *     func         呼び出す関数名
 *     0, 1, ...    引数リスト
 */
function smarty_function_call($params, &$smarty) {

    $func = $params['func'];
    unset($params['func']);

    return call_user_func_array($func, $params);
}
