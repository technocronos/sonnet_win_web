<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html>
<html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width" />

    <script>{literal}

        function onSubmit(form) {

            if(form.submitted)  return false;

            form.submitted = true;
            return true;
        }
    {/literal}</script>
  </head>

  <body style="background-color:#000F46; color:#FFFFFF; margin:0px; padding:0px;">

    <h2 style="text-align:center; font-size:medium; font-weight:normal; background-color:mediumslateblue">購入確認</h2>

    <p style="text-align:center">
      <img src="/img/{$smarty.get.img}" /><br />
      {$smarty.get.name}<br />
      <br />
      価格 {$smarty.get.price} ゲソコイン<br />
    </p>

    <form method="post" style="text-align:center" onsubmit="return onSubmit(this)">
      {if $smarty.get.code == '00'}
        <button type="submit" name="go" value="1" style="padding: 0px 1em">購入</button>
      {elseif $smarty.get.code == '01'}
        <span style="color:tomato">コインが不足しています</span>
      {else}
        <span style="color:tomato">エラーが発生しました。<br />チェックAPIレスポンスコード: {$smarty.get.code}</span>
      {/if}
    </form>

    <p style="text-align:left">
      <a href="{$backto}" style="color:deepskyblue">←戻る</a><br />
    </p>

    <!-- ゲソてんフッター -->
    <script src="{$smarty.const.PLATFORM_FOOTER_JS}" type="application/javascript"></script>
  </body>

</html>
