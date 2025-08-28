{include file='include/header.tpl'}


<h2>アイテム付与</h2>

<form method="post" action="{$smarty.server.REQUEST_URI}">
  ユーザID
  <input type="text" name="userId" value="{$smarty.get.userId}" />
  に
  {form_select name='itemId' src=`$items`}
  を
  <input type="text" name="amount" value="{$amount}" />個
  付与する<input type="submit" value="go" />
</form>

{if $result}
  <hr>

  <p>
    {if !$result.user}
      そいついなくないっすか？
    {else}
      <span style="color:green">{$result.user.name}({$result.user.user_id})</span> に <span style="color:green">{$result.item.item_name}</span> を<span style="color:green">{$result.amount}</span>個付与しました。
    {/if}
  </p>
{/if}


{include file='include/footer.tpl'}
