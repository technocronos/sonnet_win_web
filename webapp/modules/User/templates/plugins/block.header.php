<?php

require_once(dirname(__FILE__).'/block.tile.php');

/**
 * 内容で指定されたHTMLをヘッダとして出力する。
 *
 * タイプ1)
 *      <div> で背景色を変えるだけの単純なヘッダ
 *
 * タイプ2)
 *      イメージを使って、こんな感じのデザインにする。
 *          │ ヘッダ
 *          ┴───────────
 *
 * タイプ3)
 *      下線イメージと中央合わせで、こんな感じのデザインにする。
 *                   ヘッダ
 *          ────────────
 *
 * パラメータ)
 *     type     ヘッダ種別。1,2,3のいずれか。省略時は1。
 *     color    ヘッダの色。種別によって指定できる色は異なる。
 */
function smarty_block_header($params, $content, $smarty, $repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    switch($params['type']) {

        // タイプ3
        case 3:

            // 使用するイメージタグを取得。
            $imgUnder = ViewUtil::getImageTag(array('path'=>'parts/h3.gif'));

            return <<<HDOC
                <div style="text-align:center">
                  <span style="vertical-align:bottom">{$content}</span><br />
                  <div style="line-height:1px">{$imgUnder}</div>
                </div>
HDOC;

        // タイプ2
        case 2:

            switch($params['color']) {
                case 'red':     $color = '_red';    break;
                default:        $color = '';
            }

            // 使用するイメージタグを取得。
            $imgRide = ViewUtil::getImageTag(array('path'=>"parts/h2_left{$color}.gif", 'style'=>'vertical-align:bottom'));
            $imgUnder = ViewUtil::getImageTag(array('path'=>"parts/h2_under{$color}.gif", 'style'=>'vertical-align:top'));

            // smartyブロック関数 tile で処理する。
            $html = <<<HDOC
                <part>{$imgRide}<span style="vertical-align:bottom">{$content}</span></part>
                <part style="line-height:1px">{$imgUnder}</part>
HDOC;

            return smarty_block_tile(array(), $html, $smarty, $repeat);


        // タイプ1
        default:
            return sprintf('<div style="text-align:center; background-color:%s; color:%s">%s</div>'
                , $smarty->get_config_vars('subBgColor')
                , $smarty->get_config_vars('subTextColor')
                , $content
            );
    }
}
