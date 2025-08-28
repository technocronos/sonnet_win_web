{include file="include/header.tpl" title=`$gacha.gacha_name`}

</br>
{* 内容一覧 *}
<div style="text-align:center; font-size:{$css_small}">ガチャ出現内容一覧</div></br>
{image_tag file='hr.gif'}<br />
{foreach from=`$list` item="content"}
  {assign var='item' value=`$content.item`}

  {* アイテム画像とアイテム名、出現率 *}
  {item_image id=`$item.item_id` float='left'}
  <span style="color:{#termColor#}">{$item.item_name}</span><br />
    ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$item.rear_level`}</span><br clear="all" />
  <div style="clear:both"></div>

  {* アイテムの詳細。ガラケーは重くて表示できないので表示しない *}
  {if ($carrier == 'iphone' || $carrier == 'android')}
    {include file='include/itemSpec.tpl'}
  {/if}

提供割合：{$content.rate}%</br>

  {image_tag file='hr.gif'}<br />
{/foreach}

<br />
<a href="{url_for action='GachaDetail' gachaId=`$smarty.get.gachaId` backto=`$smarty.get.backto`}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl" showCompanyName=true}
