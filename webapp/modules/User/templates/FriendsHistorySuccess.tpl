{include file="include/header.tpl" title='仲間の履歴'}


{include file="include/historyListAlt.tpl" type='history' targetId=`$userId` count="10" pagerType="neighbors"}

<br />
<a href="{url_for action='Index'}">←戻る</a><br />


{include file="include/footer.tpl"}
