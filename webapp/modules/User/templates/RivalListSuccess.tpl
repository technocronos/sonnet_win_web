{include file="include/header.tpl" title=`$tournament.tournament_name`}


<div style="text-align:right"><a href="{url_for action='Ranking' _backto=true}" class="buttonlike next">ﾊﾞﾄﾙｲﾍﾞﾝﾄ⇒</a></div>
{if $smarty.const.TEAM_BATTLE_OPEN}
  <div style="text-align:right"><a href="{url_for action='TeamList'}" class="buttonlike next">ﾁｰﾑ対戦⇒</a></div>
{/if}

{* ナビのメッセージ表示 *}
{image_tag file='navi_mini.gif' float='left'}
{if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_RIVAL')}
  {if $rivalList}
    まだ実際に対戦までする必要はないのだ<br />
    とりあえず適当に選ぶのだ
  {else}
    と思ったらまだ誰もいないのだ…<br />
    しょーがないから<a href="{url_for module='Swf' action='Tutorial' done='Rival'}" class="buttonlike back">ｺｺ</a>をｸﾘｯｸして戻るのだ
  {/if}
{else}
  勝てそうなやつを選ぶのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

{* ナビのメッセージ表示 *}
{include file='include/characterList.tpl' list=`$rivalList` withMember=true params=`$params`}

<div style="text-align:center">{button_link _self=true did=null _nocache=true _accesskey="5"}ﾘｽﾄを更新{/button_link}</div>

<a href="{url_for module='User' action='Main'}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
