{include file='include/header.tpl'}


<h2>ユーザリセット(デバック用)</h2>

<form action="{$smarty.server.REQUEST_URI}" method="post" onSubmit="return confirm('よろしいですか？')">
  ユーザID<input type="text" name="userId" />を<input type="submit" value="リセット" />
  <label for="sakujo"><input type="checkbox" checked="true" name="delete" value="1" id="sakujo" />リセットじゃなくて削除する</label>
</form>

<hr />

{if $smarty.get.userId}
  <p>
    ID:{$smarty.get.userId} で処理しました。
  </p>
{/if}


{include file='include/footer.tpl'}
