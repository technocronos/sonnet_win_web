<?php

/**
 * 指定された配列を <input type="hidden"> で出力する。
 *
 * パラメータ一覧)
 *     src      nameをキー、valueを値に格納している配列。省略時は $_POST が使用される。
 */
function smarty_function_form_dump_on_hidden($params, &$smarty) {

    // srcパラメータが省略されている場合は $_POST から。
    if( !isset($params['src']) )
        $params['src'] = Common::cutRefArray($_POST);

    // プラットフォームから渡される値やフレームワーク固有の値は出力しない。
    unset(
        $params['src']['opensocial_app_id'],
        $params['src']['opensocial_owner_id'],
        $params['src']['opensocial_viewer_id'],
        $params['src']['module'],
        $params['src']['action']
    );

    // 戻り値初期化。
    $result = '';

    // 要素を一つずつ見て、hiddenフィールドに変換していく。
    foreach($params['src'] as $key => $value) {
        $result .= sprintf(
            '<input type="hidden" name="%s" value="%s" />'."\n",
            htmlspecialchars($key, ENT_QUOTES),
            htmlspecialchars($value, ENT_QUOTES)
        );
    }

    // リターン。
    return $result;
}
