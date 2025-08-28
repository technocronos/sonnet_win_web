{include file="include/header.tpl" title="アイテムゲット"}


{* アイテムの画像 *}
{if $item}
<br />
	{if $is_equip}
	  <span style="color:{#termColor#}">{$set_data.set_name}</span><br />
      {item_image id=`$item.item_id` float='left'}
	  <span style="color:{#termColor#}">{$item.item_name}</span><br />
	  ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$set_data.rear_id`}</span><br clear="all" />
	  <div style="clear:both"></div>

	  {* アイテムの詳細 *}
      {include file='include/itemSpec.tpl'}
    {else}
      <div style="text-align:center;">
      {item_image id=`$item.item_id`}
      </div>
	{/if}
	<br clear="all" /><div style="clear:both"></div>
{/if}

{* 取得メッセージと次のアクションへのナビゲート *}
{if $smarty.get.coin}
  {image_tag file='navi2_mini.gif' float='left'}
  <span style="color:{#termColor#}">{$item.item_name}</span>ｹﾞｯﾄじゃぞ!ﾌｫｯﾌｫｯ､やはり{$smarty.const.PLATFORM_CURRENCY_NAME}よのぅ<br />
{else}
  {image_tag file='navi_mini.gif' float='left'}
  <span style="color:{#termColor#}">{$item.item_name}</span>ｹﾞｯﾄなのだ！<br />
{/if}

{if $userInfo.tutorial_step >= constant('User_InfoService::TUTORIAL_END') && $nextUrl}
<br />  <div style="text-align:right"><a href="{$nextUrl}" class="buttonlike next">{if $item.category == 'ITM'}使用する⇒{else}装備する⇒{/if}</a></div>
{/if}
<br clear="all" /><div style="clear:both"></div>

{* ショップチュートリアルの場合の追加メッセージ *}
{if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_GACHA')}
  ついでにｶﾞﾁｬも案内しとくのだ<br />
  <div style="text-align:center"><a href="{url_for action='GachaList'}">{image_tag file='tutorial_next.gif'}</a></div>

{* ガチャチュートリアルの場合の追加メッセージ *}
{elseif $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_LAST')}
  案内はこんなとこなのだ｡じじぃの家に戻るのだ<br />
  <div style="text-align:center"><a href="{url_for module='Swf' action='Tutorial'}">{image_tag file='tutorial_next.gif'}</a></div>

{* 平常時 *}
{else}
  <br />
  <a href="{backto_url}" class="buttonlike back">←戻る</a><br />
{/if}

{include file="include/footer.tpl"}
