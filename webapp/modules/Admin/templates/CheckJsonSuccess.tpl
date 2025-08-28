{include file='include/header.tpl'}


<h2>JSONデコードチェック(デバック用)</h2>

<form action="{$smarty.server.REQUEST_URI}" method="post">
  JSON<br />
  <textarea name="json" style="font-family:monospace; width:95%; height:40em">{$smarty.post.json}</textarea>
  <input type="submit" value="チェック" />
</form>

<hr />

{if $smarty.post}

  {if $error}
    デコードできません
  {else}
    <div style="color:green">OK</div>
    <pre style="font-family:monospace">{$result|@var_dump}</pre>
  {/if}
{/if}


{include file='include/footer.tpl'}
