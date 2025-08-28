{include file="include/header.tpl" title=`$title`}


<br />
{include file="include/historyList.tpl" targetId=`$smarty.get.userId` type=`$smarty.get.type` count="10" pagerType="neighbors"}

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
