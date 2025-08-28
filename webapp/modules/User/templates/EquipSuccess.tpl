{include file="include/header.tpl" title="装備"}


{image_tag file='navi_mini.gif' float='left'}
{if $smarty.get.result == 'auto_equip' || $smarty.get.result == 'all_release' || $smarty.get.result == 'change'}
  <span style="color:{#resultColor#}">{switch value=`$smarty.get.result` auto_equip='こんなもんなのだ？' all_release='装備全部はずしたのだ' change='装備を変えたのだ'}</span><br />
  <div style="text-align:right"><a href="{url_for action='QuestList'}" class="buttonlike next">ｸｴｽﾄ⇒</a> <a href="{url_for action='RivalList'}" class="buttonlike next">対戦⇒</a></div>
{elseif $smarty.get.result == 'sphere'}<span style="color:{#resultColor#}">ﾌｨｰﾙﾄﾞｸｴｽﾄ中は装備変更や合成はできないのだ｡先にｸｴｽﾄ終わらせるのだ</span>
{else}変更したい箇所を選択するのだ{/if}
<br clear="all" /><div style="clear:both"></div>

{* 最強装備 *}
<form action="{url_for _self=true}" method="post">
  <input type="hidden" name="function" value="auto_equip" />
  <div style="text-align:center"><input type="submit" value="自動最強装備{if $carrier != 'android'}{/if}" /></div>
</form>

{if ($carrier == 'iphone' || $carrier == 'android')}
{* 装備全解除 *}
<form action="{url_for _self=true}" method="post">
  <input type="hidden" name="function" value="all_release" />
  <div style="text-align:center"><input type="submit" value="すべてはずす{if $carrier != 'android'}{/if}" /></div>
</form>
{/if}

{* 装備箇所の一覧 *}
{foreach from=`$mounts` item='mount'}
  {assign var='mountId' value=`$mount.mount_id`}
  {assign var='uitem' value=`$character.equip.$mountId`}

  {if ($carrier == 'iphone' || $carrier == 'android')}
    {fieldset color='brown' width='95%'}
      <legend>
        <div style="text-align:center">{$mount.mount_name}</div>
      </legend>
      <div pc-style="line-height:2em">

	    {if $uitem}
		  {if $uitem.set.set_name}<span style="color:{#termColor#}">{$uitem.set.set_name}</span> ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$uitem.set.rear_id`}</span><div style="clear:both"></div>{/if}
	      {item_image id=`$uitem.item_id` float='left'}
	      {$uitem.item_name}
	      <br />
	      <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$uitem.level}</span>{item_level_max uitem=`$uitem`}
	      <span style="color:{#statusNameColor#}">耐久</span><span style="color:{#statusValueColor#}">{$uitem.durable_count|durability}</span>
	      <br clear="all" />
	      <div style="clear:both"></div>

	      {include file='include/equipSpec.tpl' item=`$uitem`}

	    {else}
	      (装備なし)<br />
	    {/if}

      </div>
      <div style="text-align:right">
        <a href="{url_for action='EquipChange' charaId=`$character.character_id` mountId=`$mount.mount_id` backto=`$smarty.get.backto`}" class="buttonlike next">選択</a>
      </div>
    {/fieldset}
    <br />
  {else}

    <div style="background-color:{#subBgColor#}; text-align:center"><a href="{url_for action='EquipChange' charaId=`$character.character_id` mountId=`$mount.mount_id` backto=`$smarty.get.backto`}">{$mount.mount_name}</a></div>

    {if $uitem}

	  {if $uitem.set.set_name}<span style="color:{#termColor#}">{$uitem.set.set_name}</span> ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$uitem.set.rear_id`}</span><div style="clear:both"></div>{/if}
      {item_image id=`$uitem.item_id` float='left'}
      {$uitem.item_name}
      <br />
      <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$uitem.level}</span>{item_level_max uitem=`$uitem`}
      <span style="color:{#statusNameColor#}">耐久</span><span style="color:{#statusValueColor#}">{$uitem.durable_count|durability}</span>
      <br clear="all" />
      <div style="clear:both"></div>

      {include file='include/equipSpec.tpl' item=`$uitem`}

    {else}
      (装備なし)<br />
    {/if}
  {/if}
{/foreach}

{if ($carrier != 'iphone' && $carrier != 'android')}
{* 装備全解除 *}
<div style="background-color:{#subBgColor#}">&nbsp;</div>
<form action="{url_for _self=true}" method="post">
  <input type="hidden" name="function" value="all_release" />
  <div style="text-align:center"><input type="submit" value="すべてはずす{if $carrier != 'android'}{/if}" /></div>
</form>
{/if}

<br />
{if $smarty.get.backto}<a href="{backto_url}" class="buttonlike back">←戻る</a><br />{/if}
<a href="{url_for action='Status'}" class="buttonlike back">←ｽﾃｰﾀｽ</a><br />


{include file="include/footer.tpl"}
