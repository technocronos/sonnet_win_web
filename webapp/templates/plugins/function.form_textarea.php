<?php

/**
 * 指定された内容で <textarea> を生成する。
 * $_POST に該当のパラメータがある場合はvalueをその値とする。
 *
 * パラメータ一覧)
 *     name     <textarea>のname。
 *     initial  $_POST に該当のパラメータがない場合の入力内容。
 *     ...      その他のパラメータは属性として展開される。
 */
function smarty_function_form_textarea($params, &$smarty) {

    // 省略可能なパラメータを補う。
    $params += array('initial'=>'');

    // valueに表示すべき値を取得。
    $value = isset($_POST[$params['name']]) ? $_POST[$params['name']] : $params['initial'];

    // HTMLの属性に展開。
    unset($params['initial']);
    $attributes = ViewUtil::convertAttributes($params);

    // リターン。
    return "<textarea {$attributes}>" . htmlspecialchars($value, ENT_QUOTES) . "</textarea>\n";
}
