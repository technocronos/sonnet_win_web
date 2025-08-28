<?php

/**
 * 指定された内容で <select> を生成する。
 * $_POST に該当のパラメータがある場合はselectedも処理する。
 *
 * パラメータ一覧)
 *     name     <select>のname。
 *     src      <option>のvalueをキー、テキストを値に格納している配列。
 *     initial  $_POST に該当のパラメータがない場合のvalue。
 *     empty    指定した場合は、リストの先頭に、このテキストの選択肢をvalue=カラで追加する。
 */
function smarty_function_form_select($params, &$smarty) {

    // 省略可能なパラメータを補う。
    $params += array('empty'=>false, 'initial'=>'');

    // $_POST に該当のパラメータがある場合は取得。
    $selectedValue = isset($_REQUEST[ $params['name'] ]) ? $_REQUEST[ $params['name'] ] : $params['initial'];
    unset($params['initial']);

    // <option> を格納する文字列を初期化。
    $options = '';

    // emptyパラメータを処理する。
    if($params['empty'] !== false) {
        $options .= sprintf('<option value="">%s</option>',
            htmlspecialchars($params['empty'], ENT_QUOTES)
        );
    }
    unset($params['empty']);

    // srcパラメータに指定された配列を見て、<option>を生成していく。
    foreach($params['src'] as $key => $value) {
        $options .= sprintf('<option value="%s" %s>%s</option>',
            htmlspecialchars($key, ENT_QUOTES),
            ($selectedValue == (string)$key) ? 'selected' : '',
            htmlspecialchars($value, ENT_QUOTES)
        );
    }
    unset($params['src']);

    // リターン。
    return
        '<select' . ViewUtil::convertAttributes($params) . ">\n" .
        $options . "\n" .
        '</select>';
}
