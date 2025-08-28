{include file="include/header.tpl" title="ﾌｨｰﾙﾄﾞ終了"}


{* ナビメッセージ *}
{image_tag file='navi_mini.gif' float='left'}
{if $record.result == constant('Sphere_InfoService::SUCCESS')}
  <span style="color:{#termColor#}">{$quest.quest_name}</span>ｸﾘｱなのだ｡ｵﾂｶﾚｻﾝなのだ
{elseif $record.result == constant('Sphere_InfoService::ESCAPE')}
  <span style="color:{#termColor#}">{$quest.quest_name}</span>を脱出したのだ｡準備して再挑戦なのだ
{else}
  <span style="color:{#termColor#}">{$quest.quest_name}</span>失敗なのだ…くじけずに準備して再挑戦なのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

{* サマリ *}
<table align="center">
  <tr>
    <td><span style="font-size:{$css_small}"><span style="color:{#statusNameColor#}">ﾀｰﾝ数</span></span></td>
    <td><span style="font-size:{$css_small}"><span style="color:{#statusValueColor#}">{$summary.turn}</span></span></td>
  </tr>
  <tr>
    <td><span style="font-size:{$css_small}"><span style="color:{#statusNameColor#}">倒した数</span></span></td>
    <td><span style="font-size:{$css_small}"><span style="color:{#statusValueColor#}">{$summary.terminate}</span></span></td>
  </tr>
</table>
{if ($record.result == constant('Sphere_InfoService::FAILURE') || $record.result == constant('Sphere_InfoService::GIVEUP'))  &&  $quest.penalty_pt > 0}
  <div style="text-align:center">
    ｸｴｽﾄ失敗<br />
    <span style="color:{#statusNameColor#}">{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">-{$quest.penalty_pt}</span>⇒<span style="color:{#statusValueColor#}">{$userInfo.gold}</span><br />
  </div>
{/if}
{if $summary.mission.achieve}
  <div style="text-align:center">
    <div style="text-decoration:blink">ﾐｯｼｮﾝ達成</div>
    <span style="color:{#statusNameColor#}">{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">+{$summary.mission.gold}</span>⇒<span style="color:{#statusValueColor#}">{$userInfo.gold}</span><br />
  </div>
  <span style="color:{#noticeColor#}">ﾐｯｼｮﾝは達成するとなくなります</span><br />
{/if}

{* トレジャー *}
<div style="background-color:{#subBgColor#}; text-align:center">入手ｱｲﾃﾑ</div>
{foreach from=`$treasures` item='item'}
  {item_image id=`$item.item_id` float='left'}
  <span style="color:{#termColor#}">{$item.item_name}</span><br />
  {include file='include/itemSpec.tpl' item=`$item` style='compact'}
  <br />
{foreachelse}
  <div style="text-align:center">なし</div>
{/foreach}

{image_tag file='hr.gif'}<br />

{* チュートリアル中の場合 *}
{if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_BATTLE')}
  <div style="text-align:center"><a href="{url_for module='Swf' action='Tutorial'}">{image_tag file='tutorial_next.gif'}</a></div>

{* 平常時 *}
{else}

  {* 次の画面 *}
  {if $next}
    <div style="text-align:center">
      <a href="{url_for module='Swf' action='QuestDrama' questId=`$next.quest_id`}" class="buttonlike next">{$next.quest_name}</a>⇒
    </div>
  {else}
    <a href="{url_for action='Main'}" class="buttonlike back">←ｿﾈｯﾄﾒﾆｭｰ</a><br />
  {/if}
  <br />

  {* プラットフォームのユーザ記事 *}
  {$smarty.const.PLATFORM_ARTICLE_NAME}で<span style="color:{#termColor#}">行動pt</span><span style="color:{#statusValueColor#}">{$smarty.const.ARTICLE_AP}</span><br />
  {article_form return=`$returnTo` body=`$body`}%button%<span style="color:{#noticeColor#}">一日一回まで</span>{/article_form}
{/if}


{include file="include/footer.tpl"}
