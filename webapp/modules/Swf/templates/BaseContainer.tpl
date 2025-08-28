<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html>
<html style="overflow:hidden">

  <head>
    <title>{$smarty.const.SITE_NAME}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,viewport-fit={$safe_mode}">
    <link rel="stylesheet" href="{$smarty.const.APP_WEB_ROOT}css/site.css?{"`$smarty.const.MO_HTDOCS`/css/site.css"|filemtime}">

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

            /*
             *---------------------------------------------------------------------------------------------------
             * 画面共通のスクロールバーのCSS
             *
             *---------------------------------------------------------------------------------------------------
            */
            .iScrollVerticalScrollbar {literal}{{/literal}
               overflow: hidden;
               position: absolute;
               *zoom: 1;
               filter: progid:DXImageTransform.Microsoft.gradient(gradientType=0, startColorstr='#FF1F6B8E', endColorstr='#FF0A5181');
               background-image: url("{$smarty.const.APP_WEB_ROOT}/img/parts/sp/scroll_win.png");
               background-size: cover;
            {literal}}{/literal}


            .iScrollVerticalScrollbar .iScrollIndicator {literal}{{/literal}
              width: 100%;
              margin-top: 8px;
              margin-left: 2px;
            {literal}}{/literal}

            /* styled scrollbars */
            .iScrollVerticalScrollbar {literal}{{/literal}
              width: 19px;
              top: -2px;
              right: 0;
              bottom: -2px;
            {literal}}{/literal}

            .iScrollIndicator {literal}{{/literal}
              position: absolute;
              box-sizing: border-box;
              background-image: url("{$smarty.const.APP_WEB_ROOT}/img/parts/sp/scrollbar.png");
              background-position: 50% 50%;
              /*background-repeat: no-repeat;*/
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
    <script src="{$smarty.const.APP_WEB_ROOT}js/jquery.fittext.js?{"`$smarty.const.MO_HTDOCS`/js/jquery.fittext.js"|filemtime}"></script>
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
    <script src="{$smarty.const.APP_WEB_ROOT}js/server/PreRegLogApi.js?{"`$smarty.const.MO_HTDOCS`/js/server/PreRegLogApi.js"|filemtime}"></script>

    <script src="{$smarty.const.APP_WEB_ROOT}js/page/DisplayCommon.js?{"`$smarty.const.MO_HTDOCS`/js/page/DisplayCommon.js"|filemtime}"></script>

    {foreach from=`$canvas_list` key='key' item='item'}
        <script src="{$smarty.const.APP_WEB_ROOT}js/canvas/{$key}.js?{"`$smarty.const.MO_HTDOCS`/js/canvas/`$key`.js"|filemtime}" ></script>
    {/foreach}

  </head>

  <body style="background-color:#000000; color:#FFFFFF; margin:0px; padding:0px;">
     <script>

        if(!window["Promise"])  window["Promise"] = ES6Promise;
        // PC版マウンターの存在を確認する。
        Platform.ping();

        //pexオブジェクト宣言
        var pex;
        // グローバル変数
        var support_type = "frame";// ラッパーの形態。proxy:プロキシ型, frame:フレーム型, gadget:PC型 のいずれか。
        var swf = {$swfUrl|json_encode|smarty:nodefaults};
        //pageオブジェクトをnewする
        var Page = new Page();
        //web audio APIを使う場合、サウンドを読み込む
        var audio = new audio();
        audio.sndfx = new webaudio;

        var ENVIRONMENT_TYPE = "{$smarty.const.ENVIRONMENT_TYPE}";
        var PLATFORM_TYPE = "{$smarty.const.PLATFORM_TYPE}";

        var VCOIN_FEE = "{$smarty.const.VCOIN_FEE}";
        var VCOIN_MINIMAM = "{$smarty.const.VCOIN_MINIMAM}";
        var VCOIN_MINIMAM_PAYMENT = "{$smarty.const.VCOIN_MINIMAM_PAYMENT}";
        var VCOIN_RELEASE_FLG = "{$smarty.const.VCOIN_RELEASE_FLG}";
        var DUEL_LIMIT_ON_DAY_RIVAL = "{$smarty.const.DUEL_LIMIT_ON_DAY_RIVAL}";

        var carrier = "{$carrier}";
        var is_tablet = "{$isTablet}";

        var devicewidth = $(window).width();
        var deviceheight = screen.height;

        $("body").addClass(carrier);

//alert(devicewidth);//600
//alert(deviceheight);//960

        //URLのルート
        var WEB_ROOT = "{$smarty.const.APP_WEB_ROOT}";
        var CDN_WEB_ROOT = "{$smarty.const.APP_WEB_ROOT}";
        var wide_stamp = "{$smarty.const.WIDE_STAMP}";

        var bgm = "{$bgm}";

        var font_name = "{$fontName}";
        
        var PartialDraw = {$PartialDraw};

        var ActionName = "{$ActionName}";

        var dataId = "{$dataId}";
        var firstscene = "{$firstscene}";
        var his_user_id = "{$his_user_id}";
        var sphereId = "{$sphereId}";

        var monster_capture = "{$monster_capture}";
        var monster_count = "{$monster_count}";

        var URL_TYPE = "{$URL_TYPE}";

        var URL_TOP = "{url_for module='User' action='Index'}";
        var urlOnMain = "{$urlOnMain}";

        //ユーザー情報
        {foreach from=`$ibonus` key='key' item='item' name=loopname}
            var ibonus_{$smarty.foreach.loopname.iteration} = {literal}{{/literal}"item_id" : "{$item.item_id}", "item_name" : "{$item.item_name}"{literal}}{/literal};
        {/foreach}

        {foreach from=`$abonus` key='key' item='item' name=loopname}
            var abonus_{$smarty.foreach.loopname.iteration} = {literal}{{/literal}"item_id" : "{$item.item_id}", "item_name" : "{$item.item_name}"{literal}}{/literal};
        {/foreach}

        //ユーザー情報
        {foreach from=`$chara` key='key' item='item'}
            var chara_{$key} = "{$item}";
        {/foreach}

        //API用URL
        {foreach from=`$api_list` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        //History_Log定数用URL
        {foreach from=`$History_Log_Const` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        //Tournament_Master_Const定数用URL
        {foreach from=`$Tournament_Master_Const` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        //Item_Master_Const定数用URL
        {foreach from=`$Item_Master_Const` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        //Character_Effect_Const定数用URL
        {foreach from=`$Character_Effect_Const` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        //User_Info_Tutorial_Const定数用URL
        {foreach from=`$User_Info_Tutorial_Const` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        //User_Info_Tutorial_Const定数用URL
        {foreach from=`$Vcoin_Payment_Log_Const` key='key' item='item'}
            var {$key} = "{$item}";
        {/foreach}

        var Ranking_Log_Prize_Week = {$Ranking_Log_Prize_Week};

        //プリロードしておく画像リスト
        var preload_list =  [
                  {foreach from=`$img_list` key='key' item='item'}
                      {literal}{{/literal}'name' : '{$key}','url' : '{$smarty.const.APP_WEB_ROOT}{$item}?{"`$smarty.const.MO_HTDOCS`/$item"|filemtime}'{literal}}{/literal},
                  {/foreach}
                      ];

        //HTML用URL
        {foreach from=`$html_list` key='key' item='item'}
            var {$key}Html = (function () {literal}{{/literal}/*<script src="{$smarty.const.APP_WEB_ROOT}js/page/{$key}Display.js?{"`$smarty.const.MO_HTDOCS`/js/page/`$key`Display.js"|filemtime}" />{include file="$item"}*/{literal}}{/literal}).toString().match(/[^]*\/\*([^]*)\*\/\}$/)[1];
        {/foreach}

        //インクルード無しHTML用URL
        {foreach from=`$no_inc_html_list` key='key' item='item'}
            var {$key}Html = (function () {literal}{{/literal}/*{include file="$item"}*/{literal}}{/literal}).toString().match(/[^]*\/\*([^]*)\*\/\}$/)[1];
        {/foreach}

        //SEリスト
        var selist =  [
                      {foreach from=`$use_web_audio` item='audio_name'}
                        {if $carrier == 'iphone'}
                          {literal}{{/literal}"alias":"{$audio_name}","src":"{sound_url file=`$web_audio_list.$audio_name` containar=1 ext='m4a'}"{literal}}{/literal},
                        {else}
                          {literal}{{/literal}"alias":"{$audio_name}","src":"{sound_url file=`$web_audio_list.$audio_name` containar=1 ext='mp3'}"{literal}}{/literal},
                        {/if}
                      {/foreach}
                      ];

        {literal}
          window.onload = function(event) {

              // Pexが描画するダイナミックテキストを MPlus-1c-bold に変更するため、CanvasRenderingContext2D のフォント描画をすべて固定する。
              CanvasRenderingContext2D.prototype._fillText = CanvasRenderingContext2D.prototype.fillText;
              CanvasRenderingContext2D.prototype.fillText = function(...args) {
                  this.font = this.font.replace("sans-serif", "{/literal}{$fontName}{literal}");
                  var font_size = this.font.split(' ')[0].replace("px", "");
                  this.shadowColor = this.fillStyle;
                  if(font_size <= 10)
                      this.shadowBlur = font_size / 2;
                  else
                      this.shadowBlur = font_size / 3;

                  this.rotate(0.000017453292519943296);   // 1/1000度
                  this._fillText(...args);
                  this.rotate(-0.000017453292519943296);
              }

              CanvasRenderingContext2D.prototype._strokeText = CanvasRenderingContext2D.prototype.strokeText;
              CanvasRenderingContext2D.prototype.strokeText = function(...args) {
                  this.font = this.font.replace("sans-serif", "{/literal}{$fontName}{literal}");
                  var font_size = this.font.split(' ')[0].replace("px", "");
                  this.shadowColor = this.strokeStyle;
                  this.shadowBlur = font_size / 2;
                  this.rotate(0.000017453292519943296);   // 1/1000度
                  this._strokeText(...args);
                  this.rotate(-0.000017453292519943296);
              }

              $("#out").css("width", "100%");

              // ページ先頭までスクロールする。こうしとかないとiPhone4Sなどの縦の短いデバイスで
              // 下端まで表示しきれない。
              location.hash = "container";

              // PCガジェットで動いている場合に、親フレームに高さ合わせの依頼を飛ばす。
              Platform.adjustHeight();

              $("#out").html(eval(ActionName + "ContentsHtml"));

          };

          //---------------------------------------------------------------------------------------------------------
          //縦横が切り替わった場合
          $(window).on("orientationchange resize",function(){
              $(window).scrollTop(0);

              if(Math.abs(window.orientation) === 90) {
                  //alert("ヨコです。"); // ここに実行したい処理を書く
              }
              else {
                  //alert("タテです。"); // ここに実行したい処理を書く
              }
          });
          //---------------------------------------------------------------------------------------------------------
          /**
           * visibilitychangeイベントハンドラ
           * タブ移動や、ウィンドウを隠した際にBGMが止まるようにしたもの。
           */
          document.addEventListener("visibilitychange", function(){ audio.visibilitychange() }, false);

        {/literal}

      </script>
      <div id="out">
        <!--ここに読み込んだ内容を表示します。-->
      </div>

  </body>

</html>
