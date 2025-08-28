<?php

/**
 * 指定された内容で <input type="text"> を生成する。
 * $_POST に該当のパラメータがある場合はvalueをその値とする。
 *
 * パラメータ一覧)
 *     name     <input>のname。
 *     initial  $_POST に該当のパラメータがない場合のvalue。
 *     get      trueに指定すると、$_POSTでなく$_GETから取得する。
 *     ...      その他のパラメータは属性として展開される。
 */
function smarty_function_form_input($params, &$smarty) {

    // 省略可能なパラメータを補う。
    $params += array('initial'=>'');

    // valueに表示すべき値を取得。
    $src = empty($params['get']) ? $_POST : $_GET;
    $params['value'] = isset($src[$params['name']]) ? $src[$params['name']] : $params['initial'];

    // HTMLの属性に展開。
    unset($params['get'], $params['initial']);
    $attributes = ViewUtil::convertAttributes($params);

    // リターン。
    return '<input type="text" ' . $attributes . ' />';
}
