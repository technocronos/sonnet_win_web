{include file="include/header.tpl" title="装備(`$mount.mount_name`)"}

{if $result == "gousei"}
{if $afterlv > $beforelv}
<div style="text-align:center">
    {image_tag file='levelup.gif'}<br />
    ﾚﾍﾞﾙ<span style="color:{#statusValueColor#}">{$afterlv}</span>になりました<br /><br />
</div>
{/if}
{$currentExuip.item_name}に{$sourceItem.item_name}を合成しました。<br />
{$currentExuip.item_name}の<span style="color:{#statusNameColor#}">経験値</span><span style="color:{#statusValueColor#}">{$beforeexp}⇒{if $max}[MAX]{else}{$afterexp}{/if}</span><br />
{if $afterlv > $beforelv}
{$currentExuip.item_name}の<span style="color:{#statusNameColor#}">ﾚﾍﾞﾙ</span><span style="color:{#statusValueColor#}">{$beforelv}⇒{$afterlv}</span><br />
{/if}
{$sourceItem.item_name}は消滅した・・<br /><br />
{/if}

{image_tag file='navi_mini.gif' float='left'}
{if $list.totalRows > 0}装備・合成するｱｲﾃﾑを選択するのだ
{else}装備できるもの持ってないのだ…{/if}
<div style="text-align:right"><a href="{url_for action='Shop' cat='EQP' _backto=true}" class="buttonlike next">ｼｮｯﾌﾟで買う⇒</a></div>
<br clear="all" /><div style="clear:both"></div>

{if $list.totalRows > 0}

  {* 装備を外す *}
  <div style="text-align:left">
    <a href="{url_for _self=true _sign=true change='NONE'}" class="buttonlike next">装備をはずす</a>
  </div>

  {* 装備可能品の一覧 *}
  {include file="include/userItemList.tpl" list=`$list` nameFunc=`$nameCallback` nameFuncGousei=`$nameCallbackGousei`}
{/if}

<br />
{if $smarty.get.backto}<a href="{backto_url}" class="buttonlike back">←戻る</a><br />{/if}
<a href="{url_for action='Equip' charaId=`$smarty.get.charaId` backto=`$smarty.get.backto`}" class="buttonlike back">←箇所選択</a><br />


{include file="include/footer.tpl"}
