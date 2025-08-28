<?php

/**
 * パラメータで指定された値を元に、出力する文字列を切り替える。
 *
 * パラメータ一覧)
 *     value        切り替えの判断根拠にする値。
 *     ...          valueの値に応じた出力を、[valueの値]=[出力] の形式で指定する。
 *     default      該当するパラメータがない場合の出力。
 */
function smarty_function_switch($params, &$smarty) {

    if( isset($params[ $params['value'] ]) )
        return htmlspecialchars($params[ $params['value'] ], ENT_QUOTES);
    else
        return isset($params['default']) ? htmlspecialchars($params['default'], ENT_QUOTES) : '';
}
