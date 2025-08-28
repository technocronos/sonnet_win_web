{include file="include/header.tpl" title="`$target.short_name`へのﾒｯｾｰｼﾞ"}


{if $target.user_id == $userId}
  <div style="text-align:center">
    {switch_link _name='type' _value='receive' page='0'}受信{/switch_link}
    /
    {switch_link _name='type' _value='send' page='0'   }送信{/switch_link}
  </div>
  {image_tag file='hr.gif'}<br />
{/if}

{include file="include/messageList.tpl" userId=`$target.user_id` type=`$smarty.get.type` count="10" pagerType="neighbors"}

<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
