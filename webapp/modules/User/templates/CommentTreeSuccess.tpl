{include file="include/header.tpl" title="つぶやきNo`$smarty.get.top`}


{if $top}
  {include file='include/historyView.tpl' history=`$top` hide_reply=true}
{else}
  No{$smarty.get.top}<br />
  期限切れにつき削除<br />
{/if}


{include file='include/pHeader.tpl' text="No`$smarty.get.top`へのﾚｽ"}

{if $top.user_id==$userId}
  <div style="text-align:right"><a href="{url_for action='Comment' for=`$top.history_id` target='res' _backto=true}"  class="buttonlike next">一括ﾚｽ⇒</a></div>
{/if}

{foreach from=`$list.resultset` item="row"}
  {include file='include/historyView.tpl' history=`$row` hide_quote=true}
  {image_tag file='hr.gif'}<br />
{/foreach}

{* ページャ *}
{include file="include/pager.tpl" totalPages=`$list.totalPages`}


<br />
<a href="{backto_url}"  class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
