<?php

/**
 * 例えばショップのカテゴリ切り替え用リンクなどのように、基本的に自画面リンクだが、
 * GETパラメータの値を一つ切り替えるためのリンクを表示する。
 *
 * パラメータ)
 *     _name    切り替え対象のGETパラメータ名
 *     _value   リンクへ遷移したとき、_nameで指定されたパラメータの値がどうなるか。
 *     その他   リンクへ遷移したときに追加で変更したいGETパラメータ名と値。
 *
 * 使用例)
 *     {switch_link _name='foo' _value='bar'}barモード{/switch_link}
 *
 *     $_GET['foo'] == 'bar' の場合、リンクなしで「barモード」と出力される。
 *     $_GET['foo'] != 'bar' の場合、リンクありで「barモード」と出力される。
 */
function smarty_block_switch_link($params, $content, &$smarty, &$repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    // 指定されたモードにすでになっている場合。
    if($_GET[ $params['_name'] ] == $params['_value']) {

        // 文字列をリンクせずに出力。
        return sprintf('<span style="background-color:%s; color:%s">%s</span>'
            , $smarty->get_config_vars('selectedBgColor')
            , $smarty->get_config_vars('selectedTextColor')
            , htmlspecialchars($content, ENT_QUOTES)
        );

    // 指定されたモードにすでになっている場合。
    }else {

        // リンク先URLのパラメータを取得。
        $urlParams = $params;
        unset($urlParams['_name'], $urlParams['_value']);
        $urlParams['_self'] = true;
        $urlParams[ $params['_name'] ] = $params['_value'];

        // 文字列をリンクせずに出力。
        return sprintf('<a href="%s" class="buttonlike label">%s</a>'
            , htmlspecialchars(Common::genContainerURL($urlParams), ENT_QUOTES)
            , htmlspecialchars($content, ENT_QUOTES)
        );
    }
}
