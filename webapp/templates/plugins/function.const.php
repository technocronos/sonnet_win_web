<?php

/**
 * パラメータで指定された定数を出力する。
 *
 * パラメータ一覧)
 *     name     定数名。
 */
function smarty_function_const($params, &$smarty) {

    return constant($params['name']);
}
