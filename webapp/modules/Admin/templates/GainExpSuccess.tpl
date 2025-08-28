{include file='include/header.tpl'}


<h2>キャラ経験値付与(デバック用)</h2>

<form action="{$smarty.server.REQUEST_URI}" method="post" onSubmit="return confirm('よろしいですか？')">
  キャラクターID<input type="text" name="charaId" value="{$smarty.get.charaId}" />
  に
  <input type="text" name="exp" value="" /> 付与
  <input type="submit" value="go" />
</form>

<hr />

{if $smarty.get.charaId}
  <pre>{$chara|@var_dump}</pre>
{/if}


{include file='include/footer.tpl'}
