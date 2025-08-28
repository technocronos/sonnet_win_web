{include file="include/header.tpl" title="ｲﾝﾌｫﾒｰｼｮﾝ"}


{* メニュー *}
<div style="text-align:right">
  <a href="{url_for action='BattleHistory' _backto=true}" class="buttonlike next">戦歴⇒</a><br />
  {if $smarty.const.TEAM_BATTLE_OPEN}
    <a href="{url_for action='TeamHistory'}" class="buttonlike next">ﾁｰﾑ対戦履歴⇒</a><br />
  {/if}
  {if $smarty.const.SONNET_NOW_OPEN}
    <a href="{url_for module='User' action='HistoryList' userId=`$userId` type='comment' _backto=true}" class="buttonlike next">つぶやき履歴⇒</a><br />
  {/if}
</div>

{* メッセージ *}
{include file='include/pHeader.tpl' text='受信したﾒｯｾｰｼﾞ'}
{include file="include/messageList.tpl" count="1" pagerType="more"}
<br />

{* 履歴 *}
{include file='include/pHeader.tpl' text='あなたの履歴'}
{include file="include/historyList.tpl" targetId=`$userInfo.user_id` type='history' count="1" page="0" pagerType="more"}
<br />

{* コメントタイムライン *}
{if $smarty.const.SONNET_NOW_OPEN}
  {include file='include/pHeader.tpl' text='ｿﾈｯﾄなう'}
  {include file="include/historyListAlt.tpl" type='comment' targetId=`$userId` count="1" page="0" pagerType="more"}
  <br />
{/if}

{* 仲間の履歴 *}
{include file='include/pHeader.tpl' text='仲間の履歴'}
{include file="include/historyListAlt.tpl" type='history' targetId=`$userId` count="1" page="0" pagerType="more"}
<br />


{include file="include/footer.tpl"}
