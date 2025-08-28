{if !$smarty.get.top}
  <html>
    <head>
      <title>{$smarty.const.SITE_NAME}[{$smarty.const.PLATFORM_TYPE}]管理画面</title>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>

    <frameset cols="150,*">
      <frame src="/?module=Admin&action=Menu" name="memu">
      <frame src="/?module=Admin&action=Index&top=1" name="result">
    </frameset>
  </html>

{else}

  {include file='include/header.tpl'}

  <p>
    左からメニューを選んでください。
  </p>

  {include file='include/footer.tpl'}

{/if}
