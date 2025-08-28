{include file="include/header.tpl" title='ソネットなう'}


<div style="text-align:right"><a href="{url_for action='Comment' _backto=true}">{$smarty.const.COMMENT_NAME}⇒</a></div>

{include file="include/historyListAlt.tpl" type='comment' targetId=`$userId` count="10" pagerType="neighbors"}

<br />
<a href="{url_for action='Index'}">←戻る</a><br />


{include file="include/footer.tpl"}
