{include file='include/header.tpl'}


<h2>ユーザデータ表示(デバック用)</h2>

{if $smarty.const.PLATFORM_TYPE!='gree'}

  このプラットフォームはバッチモードでのユーザ問い合わせをサポートしていません<br />

{else}

  <form>
    <input type="hidden" name="module" value="Admin" />
    <input type="hidden" name="action" value="{$smarty.get.action}" />
    プラットフォーム上でのユーザID<input type="text" name="id" value="{$smarty.get.id}" /><input type="submit" value="go" />
  </form>

  {if $smarty.get.id}

    <hr />
    返信データ<br />
    <pre>{$response|@var_dump}</pre>

    {if !$response}
      このゲームインストールしてないのかも...
    {/if}
  {/if}

{/if}


{include file='include/footer.tpl'}
