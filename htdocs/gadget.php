<?php
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('short_open_tag', 0);
    $_SERVER["REQUEST_SCHEME"] = @$_SERVER["REQUEST_SCHEME"] ?: 'http';
    require_once("../webapp/config.php");
?>
<?xml version="1.0" encoding="UTF-8" ?>
<Module>

  <ModulePrefs title="<?php echo SITE_NAME ?>">

    <?php // mixi 君はライフサイクルイベントのURL指定で、GETの場合はクエリパラメータを使用できないという糞仕様なので対応する ?>
    <Link rel="event.addapp" href="<?php echo APP_WEB_ROOT ?>index.php/module/Event/action/AddApp" method="GET" />
    <Link rel="event.removeapp" href="<?php echo APP_WEB_ROOT ?>index.php/module/Event/action/RemoveApp" method="GET" />

    <?php // mixi 君は理解できない属性でいちいちエラーを吐く仕様なので混乱させないように配慮してあげる ?>
    <?php if(PLATFORM_TYPE != 'mixi'): ?>
       <?php // ワクプラもだめらしい・・ ?>
       <?php if(PLATFORM_TYPE != 'waku'): ?>
        <Link rel="gree.invite_callback_url" href="<?php echo APP_WEB_ROOT ?>?module=User&amp;action=Help&amp;id=invite" method="GET"/>
        <Link rel="gree.join_community" href="<?php echo APP_WEB_ROOT ?>?module=Event&amp;action=JoinCommunity" method="GET" />
      <?php endif ?>
      <Link rel="event.joingroup" href="<?php echo APP_WEB_ROOT ?>?module=Event&amp;action=JoinCommunity" method="GET" />
      <Link rel="event.join_community" href="<?php echo APP_WEB_ROOT ?>?module=Event&amp;action=JoinCommunity" method="GET" />
      <Link rel="event.leavegroup" href="<?php echo APP_WEB_ROOT ?>?module=Event&amp;action=LeaveGroup" method="GET" />
    <?php endif ?>

    <Require feature="opensocial-0.8" />
    <Require feature="dynamic-height" />
  </ModulePrefs>

  <Content type="url" view="mobile" href="<?php echo APP_WEB_ROOT ?>?module=User&amp;action=Index" />

  <?php if(PLATFORM_TYPE != 'waku'): ?>
    <Content type="url" view="touch" href="<?php echo APP_WEB_ROOT ?>?module=User&amp;action=Index" />
  <?php endif ?>

  <?php if(PLATFORM_TYPE == 'niji'): ?>
    <Content view="canvas" type="url" href="<?php echo APP_WEB_ROOT ?>?module=User&amp;action=Index" padding_top="0" />
  <?php endif ?>

  <?php // アプリヒルズはこれがあるとダメみたい… ?>
  <?php if(PLATFORM_TYPE != 'hill'): ?>
    <Content type="url" view="smartphone"  href="<?php echo APP_WEB_ROOT ?>?module=User&amp;action=Index" />
  <?php endif ?>

  <?php // ゲソてん用 ?>
  <Content type="html" view="default,canvas" ><![CDATA[

    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.js"></script>
    <script src="<?php echo APP_WEB_ROOT ?>js/frame-mounter.js"></script>
    <script>

      if(!window["Promise"])  window["Promise"] = ES6Promise;

      var appBase = "<?php echo APP_WEB_ROOT ?>";
      var topUrl = "<?php echo APP_WEB_ROOT ?>?module=User&action=Index";

      //--------------------------------------------------------------------------------------------------
      /**
       * ロードされたら...
       */
      gadgets.util.registerOnLoadHandler(function(){

          // ゲームサーバに連絡して、oauthキーを取得。
          callServerApi("OauthMake", null, {agent:navigator.userAgent}).then(function(response){

              // 取得出来たらアプリフレームにトップページをロードする。
              var appFrame = document.getElementById("mounted-frame");
              appFrame.src = topUrl + "&oauth=" + response.oauth;
          });
      });

    </script>

    <!-- アプリが実行されるフレーム -->
    <iframe id="mounted-frame" class="game_core_iframe" style="width:100%; border-style:none"></iframe>

  ]]></Content>
</Module>
