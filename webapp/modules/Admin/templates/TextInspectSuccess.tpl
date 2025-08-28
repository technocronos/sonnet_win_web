{include file='include/header.tpl'}


<div style="height:20em">
  <h2>監査テキストの表示(デバック用)</h2>

  <form method="get" action="{$smarty.server.PHP_SELF}">
    <input type="hidden" name="module" value="Admin" />
    <input type="hidden" name="action" value="TextInspect" />
    テキストID<input type="text" name="id" value="{$smarty.get.id}" /><input type="submit" value="go" />
  </form>

  {if $smarty.get.id}
    プラットフォームから返されたデータ<br />
    <pre>{$response|@var_dump}</pre>
  {/if}
</div>

<h2>監査テキストの削除(デバック用)</h2>

{if $smarty.const.PLATFORM_TYPE == 'gree'}

  <p>
    GREEではこの機能は利用できないよ。
  </p>

  <p>
    GREEで監査NGをテストしたいときは "ngdata" っていうテキストを投稿すると、時間をおいてNGにしてくれる。<br />
    テスト環境限定だけど。
  </p>

{else}

  <form method="post" action="{$smarty.server.REQUEST_URI}">
    テキストID<input type="text" name="id" /><input type="submit" value="go" />
  </form>

  {if $smarty.post.id}
    <hr />
    プラットフォームから返されたデータ<br />
    <pre>{$response|@var_dump}</pre>
  {/if}

{/if}


{include file='include/footer.tpl'}
