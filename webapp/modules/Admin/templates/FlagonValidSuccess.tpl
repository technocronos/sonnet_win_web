{include file='include/header.tpl'}


<h2>汎用フラグオンのバリデーションコードの取得(デバック用)</h2>

<form>
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="FlagonValid" />

  フラグID?
  <input type="text" name="id" value="{$smarty.get.id}" />
  <input type="submit" value="go" />
</form>

{if $smarty.get.id}
  <hr />
  <p>
    バリデーションコード: {$validCode}
  </p>
{/if}


{include file='include/footer.tpl'}
