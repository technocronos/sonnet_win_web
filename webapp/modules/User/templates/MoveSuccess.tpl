{include file="include/header.tpl" title="`$regionName`"}

{image_tag file="`$region`.jpg" cat="moveMap"}

{image_tag file='navi_mini.gif' float='left'}
{if $smarty.get.scope == 'local'}
ここは{$regionName}なのだ<br />
ここで地点を移動するのだ<br />
好きな地域を選べなのだ
{else}
ここはグローバルマップなのだ<br />
好きな地域を選べなのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

<div style="text-align:center">
	    {switch_link _name='scope' _value='local' page='0' region="`$currPlaceId`"}ローカル{/switch_link
	  }/{switch_link _name='scope' _value='global' page='0' region='0'}グローバル{/switch_link}
</div></br>

{* アイテム一覧 *}
{if $placeNum >= 0}

  {foreach from=`$points` item="region"}
        {if $currPlaceId != $region.Id}
		    <div style="display: table; margin: 0 auto; ">
			  {if $smarty.get.scope == 'local'}
				<a href="{url_for action='Move' Id="`$region.Id`"}">
			  {else}
				<a href="{url_for action='Move' region="`$region.Id`"}">
			  {/if}
		      <div style="display: table-cell; width:200px;height:30px;text-align:center; vertical-align:middle; font-weight: bold; background-image : url('{image_url file='title_caption.gif'}');">{$region.Name}</div>
			  </a>
		    </div></br>
        {else}
		    <div style="display: table; margin: 0 auto; ">
		      <div style="display: table-cell; width:200px;height:30px;text-align:center; vertical-align:middle; font-weight: bold; background-image : url('{image_url file='title_caption.gif'}');">{$region.Name}</div>
		    </div></br>
	    {/if}
  {/foreach}
{else}
  <div style="text-align:center">行ける場所はありません</div>
{/if}

</br>
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />

{include file="include/footer.tpl"}
