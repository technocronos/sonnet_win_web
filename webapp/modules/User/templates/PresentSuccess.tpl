{include file="include/header.tpl" title="ﾌﾟﾚｾﾞﾝﾄ"}


{if !$error}

  {image_tag file='navi_mini.gif' float='left'}
  {if $smarty.get.result}
    <span style="color:{#resultColor#}">ｱｲﾃﾑ届けといたのだ｡</span><br />
    <div style="text-align:right"><a href="{url_for action='Message' companionId=`$smarty.get.companionId` backto=`$smarty.get.backto`}" class="buttonlike next">ﾒｯｾｰｼﾞを送る⇒</a></div>
  {else}
    ﾌﾟﾚｾﾞﾝﾄするｱｲﾃﾑを選択するのだ
  {/if}
  <br clear="all" /><div style="clear:both"></div>

  {* カテゴリ切り替え *}
  <div style="text-align:center">
    {switch_link _name='cat' _value='WPN'}武器{/switch_link} /
    {switch_link _name='cat' _value='BOD'}服{/switch_link} /
    {switch_link _name='cat' _value='HED'}頭{/switch_link} /
    {switch_link _name='cat' _value='ACS'}ｱｸｾｻﾘ{/switch_link}<br />
    {switch_link _name='cat' _value='ITM'}消費ｱｲﾃﾑ{/switch_link} /
    {switch_link _name='cat' _value='SYS'}その他{/switch_link}
  </div>

  {* アイテム一覧 *}
  {include file="include/userItemList.tpl" list=`$list` itemLink=`$itemLink`}

{else}

  <br />
  <div style="text-align:center; color:{#errorColor#}">このﾕｰｻﾞにはﾌﾟﾚｾﾞﾝﾄできません。</div>

{/if}

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
