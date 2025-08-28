{include file="include/header.tpl" title=`$title`}


<div>{$item.importance_icon}{$item.title}{if $item.isNew}{/if}</div>

<div style="text-align:right">{$item.notify_at|date_ex:"m/d H:i"}</div>

<div>{call func='Oshirase_LogService::getBodyHtml' 0=`$item.body`}</div>

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a>


{include file="include/footer.tpl"}
