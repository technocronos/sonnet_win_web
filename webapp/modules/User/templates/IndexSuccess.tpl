<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html>
<html>
  <head>
    <title>{$smarty.const.SITE_NAME}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    {if $smarty.const.PLATFORM_JS}<script type="text/javascript" src="{$smarty.const.PLATFORM_JS}"></script>{/if}
    <script src="{$smarty.const.APP_WEB_ROOT}js/jquery-3.3.1.min.js"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/frame-platform.js?{"`$smarty.const.MO_HTDOCS`/js/frame-mounter.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/frame-platform.js?{"`$smarty.const.MO_HTDOCS`/js/frame-platform.js"|filemtime}"></script>

  </head>
  <body style="background-color:#000F46; color:#FFFFFF; height:500px">
     <script>

        if(!window["Promise"])  window["Promise"] = ES6Promise;

        // PC版マウンターの存在を確認する。
        Platform.ping();

        {literal}
          window.onload = function(event) {
              // PCガジェットで動いている場合に、親フレームに高さ合わせの依頼を飛ばす。
              Platform.adjustHeight();
          };
        {/literal}

      </script>

      <div id="out">
        このゲームはスマートフォンからしかアクセスできません。
      </div>

      <!-- ゲソてんのフッター表示用スクリプト。フッター上部が隠れるのは...
           上の id="out" の <div> は最大でも高さ560pxであり、このフッターはそのすぐ下に表示されている。
           しかし、<div> の中身がその高さを飛び出してフッタの上に被さるので見えなくなっている。<div> の高さを適切に増やしてやれば見えるはずだが…
      -->
      {if defined('PLATFORM_FOOTER_JS') && $smarty.const.PLATFORM_FOOTER_JS}<script src="{$smarty.const.PLATFORM_FOOTER_JS}" type="application/javascript"></script>{/if}
  </body>
</html>