<?php

/**
 * 内容を小見出しとして出力する。
 *
 * パラメータ)
 *     display      出力形式。以下のいずれか。
 *                      inline      <span> を使ったインライン形式。省略可能。
 *                      block       <div>とセンター揃えを使ったブロック形式
 */
function smarty_block_caption($params, $content, &$smarty, &$repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    if($params['display'] == 'block') {
        return sprintf(
              '<div style="text-align:center; color:%s; background-color:%s">%s</div>'
            , htmlspecialchars($smarty->get_config_vars('subTextColor'), ENT_QUOTES)
            , htmlspecialchars($smarty->get_config_vars('subBgColor'), ENT_QUOTES)
            , $content
        );
    }else {
        return sprintf(
              '<span style="color:%s; background-color:%s">&nbsp;%s&nbsp;</span>'
            , htmlspecialchars($smarty->get_config_vars('subTextColor'), ENT_QUOTES)
            , htmlspecialchars($smarty->get_config_vars('subBgColor'), ENT_QUOTES)
            , $content
        );
    }
}
