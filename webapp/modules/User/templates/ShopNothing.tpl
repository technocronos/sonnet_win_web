{include file="include/header.tpl" title="ｼｮｯﾌﾟ"}


{image_tag file='navi_mini.gif' float='left'}
{$shop.flavor_text}
<br clear="all" /><div style="clear:both"></div>

<div style="text-align:right">
  <a href="{url_for _self=true currency='coin'}">{$smarty.const.PLATFORM_CURRENCY_NAME}{charge_mark}で買う⇒</a>
</div>

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl" showCompanyName=true}
