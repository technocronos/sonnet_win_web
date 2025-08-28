{include file="include/header.tpl" title=`$gacha.gacha_name`}


{* コントロールのHTMLを決定。後で2箇所で出力する *}
{capture name='control'}

  <table align="center"><tr>
    {if $gacha.freeticket_item_id}
	  <div style="text-align:center; font-size:{$css_small}">
		ガチャチケット<span style="color:{#statusValueColor#}">{$gacha.freeticket_count}</span>枚で１回無料！
	  </div>

    <td>
      <div style="text-align:center; font-size:{$css_small}">
        {if $freeticketCount >= $gacha.freeticket_count}
          <a href="{url_for _self=true go='freeticket'}">{image_tag file='try_ticket.gif'}</a><br />
        {else}
          {image_tag file='try_ticket_d.gif'}<br />
        {/if}
        <span style="color:{#statusValueColor#}">{$freeticketCount}</span>枚所持
      </div>
    </td>
    {/if}
    <td>
      <div style="text-align:center; font-size:{$css_small}">
        {if $gacha.gacha_kind == 2}
            <a href="{url_for _self=true go='charge'}">{image_tag file='try_gacha.gif'}</a><br />
            {charge_price price=`$gacha.price`}
        {else}
            {if $userInfo.gold < $gacha.price}
                {image_tag file='try_gacha.gif'}<br />
            {else}
                <a href="{url_for _self=true go='gold'}">{image_tag file='try_gacha.gif'}</a><br />
            {/if}
            {charge_price price=`$gacha.price` currency='gold'}
        {/if}
      </div>
    </td>
  </tr></table>
{/capture}


{image_tag file='navi2_mini.gif' float='left'}
{if $freeticketCount > 0 && $gacha.freeticket_item_id}ﾀﾀﾞ券か{$smarty.const.PLATFORM_CURRENCY_NAME}かえらぶのじゃ
{else}{$gacha.flavor_text}{/if}
<br clear="all" /><div style="clear:both"></div>
{if $gacha.gacha_kind == 1}
<br />
<span style="color:{#statusNameColor#}">{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">{$userInfo.gold}</span>
{/if}
{* コントロール *}
<br />
{$smarty.capture.control|smarty:nodefaults}

{* 内容一覧 *}
{if $gacha.gacha_id == 9998 || $gacha.gacha_id == 9997}
<span style="color:{#noticeColor#}">以下の装備品の中からいずれか一つが入手できます</span><br />
<span style="color:{#noticeColor#}">出現確率はすべて均等です｡入手済みでも重複して出現します</span><br />
{elseif $gacha.gacha_id == 2}
<span style="color:{#noticeColor#}">以下のセットの装備品の中からいずれか一つのパーツが入手できます。セットでは手に入りません。</span><br />
<span style="color:{#noticeColor#}">入手済みでも重複して出現します</span><br />
<span style="color:{#noticeColor#}">入手できる装備一覧は<a href="{url_for action='GachaLineup' gachaId=`$smarty.get.gachaId` backto=`$smarty.get.backto`}" >こちら</a>をご覧ください。</span><br />
{else}
<span style="color:{#noticeColor#}">以下のセットの装備品の中からいずれか一つのパーツが入手できます</span><br />
<span style="color:{#noticeColor#}">セットでは手に入りません</span><br />
{/if}
{image_tag file='hr.gif'}<br />

{foreach from=`$list` item="content"}
  {assign var='item' value=`$content.item`}
	{fieldset color='brown' width='95%'}
	  {if $gacha.gacha_id == 9998 || $gacha.gacha_id == 9997}
		  {* アイテム画像とアイテム名、出現率 *}
		  {item_image id=`$item.item_id` float='left'}
		  <span style="color:{#termColor#}">{$item.item_name}</span><br />
		  ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$item.rear_level`}</span><br clear="all" />
		  <div style="clear:both"></div>
		  {* アイテムの詳細 *}
		  {include file='include/itemSpec.tpl'}
	  {else}
		  {image_tag file="`$item.set.set_id`.png" cat="set" float='left'}
		  <span style="color:{#termColor#}">{$item.set.set_name}</span><br />
		  ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$item.set.rear_id`}</span><br /><br />
		  {$item.set.set_text}<br />
	  {/if}
	{/fieldset}
  {image_tag file='hr.gif'}<br />
{/foreach}

{* コントロール *}
{$smarty.capture.control|smarty:nodefaults}

<br />
<a href="{url_for action='GachaList' backto=`$smarty.get.backto`}" class="buttonlike back">←ｶﾞﾁｬ一覧</a><br />


{include file="include/footer.tpl" showCompanyName=true}
