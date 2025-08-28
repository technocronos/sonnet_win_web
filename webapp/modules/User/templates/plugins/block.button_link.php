<?php

/**
 * GETフォームを使用して、ボタン状のリンクを表示する。
 * パラメータは url_for と同様だが以下のパラメータを受け付ける。
 *      _accesskey      ショートカットキー
 *
 * 使用例)
 *     {button_link module='Foo' action='Bar'}クリックして移動{/button_link}
 */
function smarty_block_button_link($params, $content, &$smarty, &$repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    // _accesskeyパラメータを処理。
    $accesskey = isset($params['_accesskey']) ? $params['_accesskey'] : '';
    unset($params['_accesskey']);

    // コンテナ経由でないURLを取得。
    $url = htmlspecialchars(Common::genURL($params), ENT_QUOTES);

    // action属性を取得。
    $action = PLATFORM_GADGET_URL;

    // ショートカットキーがある場合、対応する絵文字を取得。
    if(Common::getCarrier() != 'android')
        switch($accesskey) {
            case '0':   $content .= '';  break;
            case '1':   $content .= '';  break;
            case '2':   $content .= '';  break;
            case '3':   $content .= '';  break;
            case '4':   $content .= '';  break;
            case '5':   $content .= '';  break;
            case '6':   $content .= '';  break;
            case '7':   $content .= '';  break;
            case '8':   $content .= '';  break;
            case '9':   $content .= '';  break;
        }


    // HTML作成してリターン。
    $platformUrl = PLATFORM_GADGET_URL;
    return <<<HDOC
<form method="get" action="{$platformUrl}">
  <input type="hidden" name="guid" value="ON" />
  <input type="hidden" name="url" value="{$url}" />
  <input type="submit" value="{$content}" accesskey="{$accesskey}" />
</form>
HDOC;
}
