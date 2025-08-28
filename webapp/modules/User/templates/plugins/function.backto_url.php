<?php

/**
 * GET変数backtoパラメータから、戻る先のURLを取得する。
 *
 * パラメータ一覧)
 *     backto   戻る先のURLを決定するための値。
 *              省略した場合はGET変数backtoから取得。
 */
function smarty_function_backto_url($params, $smarty) {

    $backtoValue = isset($params['backto']) ? $params['backto'] : $_GET['backto'];

    return htmlspecialchars(ViewUtil::getBacktoUrl($backtoValue), ENT_QUOTES);
}
