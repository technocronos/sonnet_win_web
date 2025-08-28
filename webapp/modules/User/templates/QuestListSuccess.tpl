{include file="include/header.tpl" title="ｸｴｽﾄ一覧"}


{* ナビメッセージ。エラー⇒チュートリアル⇒標準の順で表示する *}
{image_tag file='navi_mini.gif' float='left'}
{if $smarty.get.error == 'actpt'}
  <span style="color:{#errorColor#}">行動pt足りないのだ…</span>
{elseif $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_MAINMENU')}
  ｺｺには今いる場所で実行できるｸｴｽﾄが出てくるのだ<br />
  一覧の中身は状況次第で変わるから要ﾁｪｯｸなのだ<br />
  <br />
  分かったら<span style="color:{#termColor#}">精霊の洞窟</span>にいくのだ<br />
  じじぃもﾒｼ食い終わるのだ
{elseif $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_MOVE')}
  他の場所に行くなら､｢他の場所に移動｣で<span style="color:{#termColor#}">移動</span>するのだ<br />
  移動するとｸｴｽﾄの一覧変わるのだ
{else}
  <span style="color:{#termColor#}">{$place.place_name}</span>で実行できるｸｴｽﾄなのだ
  {if !$list}
    <br />
    でも何もないのだ…
  {/if}
{/if}
<br clear="all" /><div style="clear:both"></div>

{* 移動メニュー。移動できるようになってから表示する。 *}
{if $userInfo.tutorial_step >= constant('User_InfoService::TUTORIAL_MOVE')}
  <br />
  <div style="text-align:center">
  {if ($carrier == 'iphone' || $carrier == 'android')}
    <a href="{url_for action='Move'}">{image_tag file='btn_map.gif'}</a>
  {else}
    <a href="{url_for module='Swf' action='Move'}" class="buttonlike label">他の場所に移動⇒</a>
  {/if}
  </div>
{/if}

<br />

  {* 行動ptの表示 *}
  <div style="text-align:center">
    <span style="color:{#statusNameColor#}">行動pt</span><span style="color:{#statusValueColor#}">{$userInfo.action_pt|int}</span>{include file='include/gauge.tpl' value=`$userInfo.action_pt` max=`$smarty.const.ACTION_PT_MAX` type='AP'}<br />
  </div></br>


  {* 期間限定クエスト *}
  {if $userInfo.tutorial_step >= constant('User_InfoService::TUTORIAL_END')}
  <div style="text-align:center">
      {image_tag file='caption_eventquest.png'}<br /><br />
  </div>

  <div style="text-align:center">
    <a href="{url_for module='Swf' action='Terminable' questId='98001'}">{image_tag file='b_q_98001.gif'}</a><br />
	<span style="color:red;">只今ﾘﾚｲｻﾞｰ取り放題!</br>
    {if $smarty.const.PLATFORM_TYPE=='waku'}期間11/06～11/20!!<br />{/if}
	</span>
  </div>
  <br />
  {/if}

{if $list}
  <div style="text-align:center">
  	{image_tag file='caption_storyquest.png'}<br /><br />
  </div>

  {foreach from=`$list` item='quest' name="questlist"}
	    <div style="text-align:center; background-color:{#subBgColor#}; color:{#subTextColor#}">{$quest.quest_name}　{if $quest.status == 1}{image_tag file='icon_new.png'}{elseif $quest.status == 2}{image_tag file='icon_on_quest.png'}{else}{image_tag file='icon_clear.png'}{/if}</div>
	    <div>{$quest.flavor_text|nl2br}</div>

	    <table style="width:100%"><tr>
	      <td><span style="font-size:{$css_small}">
	        <span style="color:{#statusNameColor#}">ﾀｲﾌﾟ</span><span style="color:{#statusValueColor#}">{if $quest.type=='FLD'}ﾌｨｰﾙﾄﾞ{elseif $quest.repeatable}探索{else}ｲﾍﾞﾝﾄ{/if}</span><br />
	        {if $quest.consume_pt && $quest.type=='DRM'}
	          <span style="color:{#statusNameColor#}">行動pt</span><span style="color:{#statusValueColor#}">-{$quest.consume_pt}</span>
	        {/if}
	        {if $quest.penalty_pt}
	          <span style="color:{#statusNameColor#}">失敗時ﾏｸﾞﾅ</span><span style="color:{#statusValueColor#}">-{$quest.penalty_pt}</span>
	        {/if}
	      </span></td>
	      <td style="text-align:right"><span style="font-size:{$css_small}">
	        {if $quest.type == 'FLD'}
	          <a href="{url_for module='Swf' action='Ready' questId=`$quest.quest_id`}" class="buttonlike next">GO⇒</a>
	        {else}
	          <a href="{url_for module='Swf' action='QuestDrama' questId=`$quest.quest_id`}" class="buttonlike next">GO⇒</a>
	        {/if}
	      </span></td>
	    </tr></table>

	    <div style="text-align:center">
	    </div>
    <br />

  {/foreach}

{/if}

	{* モンスターの洞窟クエスト *}
  {if $userInfo.tutorial_step >= constant('User_InfoService::TUTORIAL_END')}
    <div style="text-align:center">
    	{image_tag file='caption_weekquest.png'}<br /><br />
    </div>

	<div style="text-align:center">
	  <a href="{url_for module='Swf' action='Terminable' questId=99999}">{image_tag file='b_q_99999.gif'}</a><br />
	  <span style="color:red;">{$week_quest_str}</span><br />
	</div>
	<br />
  {/if}
<a href="{url_for action='Main'}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
