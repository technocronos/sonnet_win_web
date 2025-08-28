{include file="include/header.tpl" title='ﾁｰﾑ対戦履歴'}


<br />
{include file="include/historyList.tpl" targetId=`$userId` type='team' count="10"}

<br />
<a href="{url_for action='Information'}">←ｲﾝﾌｫﾒｰｼｮﾝ</a><br />


{include file="include/footer.tpl"}
