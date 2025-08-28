<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html>
<html>

  <head>
    <title>{$smarty.const.SITE_NAME}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,viewport-fit=cover">
    <link rel="stylesheet" href="{$smarty.const.APP_WEB_ROOT}css/user.css?{"`$smarty.const.MO_HTDOCS`/css/user.css"|filemtime}" type="text/css" />
    <link rel="stylesheet" href="{$smarty.const.APP_WEB_ROOT}css/site.css?{"`$smarty.const.MO_HTDOCS`/css/site.css"|filemtime}" type="text/css" />

    {if $fontName != ""}
        <style type="text/css">
            @font-face {literal}{{/literal}
              font-family: "{$fontName}";
              src: url({$font_eot}) format("eot"), url({$font_ttf}) format("truetype"), url({$font_woff2}) format("woff2"), url({$font_woff}) format("woff");
            {literal}}{/literal}
            /*WEBフォントを指定する*/
            body{literal}{{/literal}
              font-family: {$fontName};
            {literal}}{/literal}
        </style>
    {/if}

    {if $smarty.const.PLATFORM_JS}<script type="text/javascript" src="{$smarty.const.PLATFORM_JS}"></script>{/if}
    <script src="{$smarty.const.APP_WEB_ROOT}js/jquery-3.3.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/pex-1.2.0.js"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/AppUrl.js?{"`$smarty.const.MO_HTDOCS`/js/AppUrl.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/AppUtil.js?{"`$smarty.const.MO_HTDOCS`/js/AppUtil.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/Delegate.js?{"`$smarty.const.MO_HTDOCS`/js/Delegate.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/Juggler.js?{"`$smarty.const.MO_HTDOCS`/js/Juggler.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/misc.js?{"`$smarty.const.MO_HTDOCS`/js/misc.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/webaudio.js?{"`$smarty.const.MO_HTDOCS`/js/webaudio.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/audio.js?{"`$smarty.const.MO_HTDOCS`/js/audio.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/Dialogue.js?{"`$smarty.const.MO_HTDOCS`/js/Dialogue.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/iscroll.js?{"`$smarty.const.MO_HTDOCS`/js/iscroll.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/Page.js?{"`$smarty.const.MO_HTDOCS`/js/Page.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/SwfInterface.js?{"`$smarty.const.MO_HTDOCS`/js/SwfInterface.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/sparkleh.js?{"`$smarty.const.MO_HTDOCS`/js/sparkleh.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/jquery.transform.js?{"`$smarty.const.MO_HTDOCS`/js/jquery.transform.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/jquery.arctext.js?{"`$smarty.const.MO_HTDOCS`/js/jquery.arctext.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/frame-platform.js?{"`$smarty.const.MO_HTDOCS`/js/frame-platform.js"|filemtime}"></script>

    <script src="{$smarty.const.APP_WEB_ROOT}js/server/Server.js?{"`$smarty.const.MO_HTDOCS`/js/server/Server.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/Api.js?{"`$smarty.const.MO_HTDOCS`/js/server/Api.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/MonsterApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/MonsterApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/GachaApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/GachaApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/NoticeApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/NoticeApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/RivalApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/RivalApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/ShopApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/ShopApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/EquipApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/EquipApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/MypageApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/MypageApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/QuestApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/QuestApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/MessageApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/MessageApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/ApproachApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/ApproachApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/MemberApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/MemberApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/HistoryApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/HistoryApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/BattleLogApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/BattleLogApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/HelpApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/HelpApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/HomeApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/HomeApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/CharaApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/CharaApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/UserItemApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/UserItemApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/BattleApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/BattleApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/GradeApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/GradeApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/SphereApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/SphereApi.js"|filemtime}"></script>
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/VcoinApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/VcoinApi.js"|filemtime}"></script>

    <script src="{$smarty.const.APP_WEB_ROOT}js/page/DisplayCommon.js?{"`$smarty.const.MO_HTDOCS`/js/page/DisplayCommon.js"|filemtime}"></script>

  </head>

  <body style="color:#FFFFFF; margin:0px; padding:0px;">

     <script>

        if(!window["Promise"])  window["Promise"] = ES6Promise;

        // PC版マウンターの存在を確認する。
        Platform.ping();

        var support_type = "frame";// ラッパーの形態。proxy:プロキシ型, frame:フレーム型, gadget:PC型 のいずれか。
        var URL_TYPE = "{$URL_TYPE}";

        //pageオブジェクトをnewする
        var Page = new Page();
        //web audio APIを使う場合、サウンドを読み込む
        var audio = new audio();
        audio.sndfx = new webaudio;

        var is_tablet = "{$isTablet}";

        var devicewidth = $(window).width();
        var deviceheight = screen.height;

        var font_name = "{$fontName}";

        //URLのルート
        var WEB_ROOT = "{$smarty.const.APP_WEB_ROOT}";
        var CDN_WEB_ROOT = "{$smarty.const.APP_WEB_ROOT}";
        var wide_stamp = "{$smarty.const.WIDE_STAMP}";

        var carrier = "{$carrier}";

        $("body").addClass(carrier);

        var GOOGLE_PLAY_URL = "{$smarty.const.GOOGLE_PLAY_URL}";
        var APP_STORE_URL = "{$smarty.const.APP_STORE_URL}";

        var IOS_VER = "{$smarty.const.IOS_VER}";
        var ANDROID_VER = "{$smarty.const.ANDROID_VER}";

        var startAction = "{$startAction}";
        var urlOnMain = "{$urlOnMain}";

        var URL_TOP = "{url_for module='User' action='Index'}";

        var ActionName = "{$ActionName}";
        var template_file = "{$template_file}";
        var PLATFORM_TYPE = "{$smarty.const.PLATFORM_TYPE}";
        var VCOIN_RELEASE_FLG = "{$smarty.const.VCOIN_RELEASE_FLG}";

        {if PLATFORM_TYPE == "gree"}
            GREE.init({literal}{{/literal}
                app_id: "{$smarty.const.APP_ID}",
                {if $smarty.const.ENVIRONMENT_TYPE == "test"}sandbox: true{/if}
            {literal}}{/literal});
        {/if}

        //API用URL
        {foreach from=`$api_list` key='key2' item='item2'}
            var {$key2} = "{$item2}";
        {/foreach}

        var suggest_item_id = "{$item.item_id}";
        var suggest_nexturl = "{$suggest_nexturl}";
        var backto_url = "{backto_url}";
        var main_url = "{url_for action='Main'}";

        var tuto_url = "{url_for module='Swf' action='Tutorial'}";

        var bitem_backtoUrl = "{$bitem_backtoUrl}";
        var bitem_finishUrl = "{$bitem_finishUrl}";

        var price = "{$price}";

        var is_newest_version = "{$is_newest_version}";


        //HTML用URL
        var {$template_file}Html = (function () {literal}{{/literal}/*<script src="{$smarty.const.APP_WEB_ROOT}js/page/{$template_file}Display.js?{"`$smarty.const.MO_HTDOCS`/js/page/`$template_file`Display.js"|filemtime}" />{include file="`$contents_file`"}*/{literal}}{/literal}).toString().match(/[^]*\/\*([^]*)\*\/\}$/)[1];

        var PopupConfirmHtml = (function () {literal}{{/literal}/*<script src="{$smarty.const.APP_WEB_ROOT}js/page/PopupConfirmDisplay.js?{"`$smarty.const.MO_HTDOCS`/js/page/PopupConfirmDisplay.js"|filemtime}" />{include file="`$smarty.const.MO_HTDOCS`/html/PopupConfirm.html"}*/{literal}}{/literal}).toString().match(/[^]*\/\*([^]*)\*\/\}$/)[1];

        var PopupHtml = (function () {literal}{{/literal}/*<script src="{$smarty.const.APP_WEB_ROOT}js/page/PopupDisplay.js?{"`$smarty.const.MO_HTDOCS`/js/page/PopupDisplay.js"|filemtime}" />{include file="`$smarty.const.MO_HTDOCS`/html/Popup.html"}*/{literal}}{/literal}).toString().match(/[^]*\/\*([^]*)\*\/\}$/)[1];

        //変数情報をセット
        {foreach from=`$variable_list` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        //プリロードしておく画像リスト
        var preload_list =  [
                  {foreach from=`$img_list` key='key' item='item'}
                      {literal}{{/literal}'name' : '{$key}','url' : '{$smarty.const.APP_WEB_ROOT}{$item}?{"`$smarty.const.MO_HTDOCS`/$item"|filemtime}'{literal}}{/literal},
                  {/foreach}
                      ];

        //SEリスト
        var selist =  [];

        {literal}
          window.onload = function(event) {

              var devicewidth = this.devicewidth;
              var deviceheight = this.deviceheight;

              $("#out").css("width", "100%");

              $("#out").html(eval(template_file + "Html"));

              var ratio = devicewidth / 750;

              var target = $("#content");

          };
        {/literal}

      </script>
      <div id="out">
        <!--ここに読み込んだ内容を表示します。-->
      </div>

      {if defined('PLATFORM_FOOTER_JS') && $smarty.const.PLATFORM_FOOTER_JS}<script src="{$smarty.const.PLATFORM_FOOTER_JS}" type="application/javascript"></script>{/if}
  </body>

</html>
