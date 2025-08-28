{include file='include/header.tpl'}


<h2>テキストグループの作成・表示(デバック用)</h2>

{if $smarty.const.PLATFORM_TYPE != 'mbga'}

  <p>
    この機能が使えるのはモバゲのみ。<br />
    …というか、他では必要ない。<br />
  </p>

{else}

  現在のグループ<br />
  <pre>{$groups|@var_dump}</pre>

  <hr />

  <form method="post" action="{$smarty.server.REQUEST_URI}">
    作成<input type="text" name="id" /><input type="submit" name="create" value="go" />
  </form>

  {if $smarty.post}
    返答: <pre>{$response|@var_dump}<pre>
  {/if}

{/if}


{include file='include/footer.tpl'}
