{include file="include/header.tpl" title="ﾌｨｰﾙﾄﾞ再開"}


{* ナビメッセージ *}
{image_tag file='navi_mini.gif' float='left'}
<span style="color:{#termColor#}">{$quest.quest_name}</span>実行中なのだ
<br clear="all" /><div style="clear:both"></div>

<br />

<div style="text-align:center">
  {if ($carrier == 'iphone' || $carrier == 'android')}
  	<a href="{url_for module='Swf' action='Sphere' id=`$sphereId` reopen='resume'}">{image_tag file='btn_retry.gif'}</a><br />
  	<br />
	<a href="{url_for _self=true giveup=1}">{image_tag file='btn_giveup.gif'}</a><br />
  {else}
  	<a href="{url_for module='Swf' action='Sphere' id=`$sphereId` reopen='resume'}" class="buttonlike label">再開する</a><br />
  	<br />
	<a href="{url_for _self=true giveup=1}" class="buttonlike label">ｷﾞﾌﾞｱｯﾌﾟ</a><br />
  {/if}
</div>

<br />
<a href="{url_for action='Main'}" class="buttonlike back">←ｿﾈｯﾄﾒﾆｭｰ</a><br />


{include file="include/footer.tpl"}
