{include file="include/header.tpl" title=""}


<br />
{image_tag file='navi_mini.gif' float='left'}
何か書いてきたのだ？
<br clear="all" /><div style="clear:both"></div>
<br />

<div>
  {if $smarty.get.result == 'ap_recov'}
    <span style="color:{#termColor#}">行動pt</span><span style="color:{#statusValueColor#}">+{$smarty.const.ARTICLE_AP}</span>⇒<span style="color:{#statusValueColor#}">{$userInfo.action_pt|int}</span>{include file='include/gauge.tpl' value=`$userInfo.action_pt` max=`$smarty.const.ACTION_PT_MAX` type='AP'}<br />
  {elseif $smarty.get.result == 'ap_full'}
    <span style="color:{#termColor#}">行動pt</span>はすでに満杯です｡
  {elseif $smarty.get.result == 'day_limit'}
    今日はすでに回復しているため､<span style="color:{#termColor#}">行動pt</span>の回復はありません｡
  {/if}
</div>

<br />
<a href="{url_for action='Main'}">←ｿﾈｯﾄﾒﾆｭｰ</a><br />


{include file="include/footer.tpl"}
