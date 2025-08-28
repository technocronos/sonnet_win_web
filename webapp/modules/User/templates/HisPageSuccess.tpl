{include file="include/header.tpl" title="他ﾕｰｻﾞのﾍﾟｰｼﾞ"}


{* アクセス可能ならば表示 *}
{if $he}

  {* チュートリアルならメッセージ表示 *}
  {if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_RIVAL')}
    {image_tag file='navi_mini.gif' float='left'}
    ここは他のﾕｰｻﾞのﾍﾟｰｼﾞなのだ｡対戦に限らず何かとｺｺにくるのだ｡ﾒｯｾｰｼﾞ送ったりいろいろできるのだ<br />
    対戦するときは下のほうにある<span style="color:{#termColor#}">ﾊﾞﾄﾙを挑む</span>を選ぶのだ｡最後に確認があるからとりあえず押してみるのだ
    <br clear="all" /><div style="clear:both"></div>
  {/if}

{if $smarty.const.SONNET_NOW_OPEN}
  {* フィーリングを表示 *}
  {image_tag file='fukidashi_upper.gif'}<br />
  {if $comment}{include file='include/historyView.tpl' history=`$comment` compact=true}{else}(まだつぶやいていません)<br />{/if}
  <div style="text-align:right"><a href="{url_for module='User' action='HistoryList' userId=`$he.user_id` type='comment' _backto=true}" class="buttonlike next">つぶやき履歴⇒</a></div>
  {image_tag file='fukidashi_lower.gif'}<br />
{/if}

  {* プラットフォームアバターとユーザに対するメニュー *}
    {fieldset color='brown' width='95%'}
        <legend>{$he.name}</legend>
      <table><tr>
        <td>{platform_thumbnail id=`$he.user_id`}</td>
        <td><span style="font-size:{$css_small}">
          {if $isMember}
            仲間です<a href="{url_for module='User' action='Approach' companionId=`$he.user_id` _backto=true}" class="buttonlike label">⇒解除</a>
          {elseif $isApproaching}
            仲間申請中
          {else}
            <a href="{url_for module='User' action='Approach' companionId=`$he.user_id` _backto=true}" class="buttonlike label">仲間申請</a>
          {/if}<br />
          <a href="{url_for module='User' action='Message' companionId=`$he.user_id` _backto=true}" accesskey="{$smarty.const.SHORTCUT_KEY_SUB2}" class="buttonlike label">ﾒｯｾｰｼﾞを送る</a>{$smarty.const.SHORTCUT_IND_SUB2}<br />
          <a href="{url_for module='User' action='Present' companionId=`$he.user_id` _backto=true}" class="buttonlike label">ｱｲﾃﾑを贈る</a><br />
          <a href="{url_for module='User' action='MemberList' userId=`$he.user_id` _backto=true}" class="buttonlike label">仲間ﾘｽﾄ</a><br />
          <a href="{url_for module='User' action='HistoryList' userId=`$he.user_id` type='history' _backto=true}" class="buttonlike label">履歴</a>
        </span></td>
      </tr></table>
    {/fieldset}

  {image_tag file='hr.gif'}<br />

  {* キャラクターとキャラクターに対するメニュー *}
  {fieldset color='brown' width='95%'}
    <legend>{text id=`$chara.name_id`}</legend>

    {chara_img chara=`$chara` float='left'}
    <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$chara.level}</span> <span style="color:{#statusValueColor#}">{text grade=`$chara.grade_id`}</span><br />
    <span style="color:{#statusValueColor#}">{$ctour.fights}</span>戦<span style="color:{#statusValueColor#}">{$ctour.win}</span>勝<span style="color:{#statusValueColor#}">{$ctour.lose}</span>敗<span style="color:{#statusValueColor#}">{$ctour.draw}</span>分
    {if $he.user_id != $userInfo.user_id}
      <br />
      <a href="{url_for action='BattleConfirm' rivalId=`$chara.character_id`}" accesskey="5" class="buttonlike next">ﾊﾞﾄﾙを挑む</a><br />
      {if $smarty.const.TEAM_BATTLE_OPEN}
      <a href="{url_for action='TeamBattle' rivalId=`$chara.character_id`}" class="buttonlike next">ﾁｰﾑﾊﾞﾄﾙを挑む</a><br />
      {/if}
      <a href="{url_for action='BattleHistory' charaId=`$chara.character_id` _backto=true}" class="buttonlike label">戦歴</a>
    {/if}
    <br clear="all" /><div style="clear:both"></div>
  {/fieldset}

  {fieldset color='brown' width='95%'}
    <legend>戦績</legend>
      週間ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$rank.weekly|default:'--'}</span>位<br />
      日別ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$rank.daily|default:'--'}</span>位<br />
  {/fieldset}

  {* キャラステータス *}
  {fieldset color='brown' width='95%'}
    <legend>ステータス</legend>
    {include file='include/characterStatus.tpl' chara=`$chara` equip=true}
  {/fieldset}

  {* 装備の一覧 *}
  {fieldset color='brown' width='95%'}
    <legend>装備一覧</legend>
      <table>
        {foreach from=`$mounts` item='mount'}
          {assign var='mountId' value=`$mount.mount_id`}
          {assign var='uitem' value=`$chara.equip.$mountId`}
          <tr>
            <td><span style="font-size:{$css_small}; color:{#termColor#}">{if $mount.mount_name == 'ｱｸｾｻﾘ'}ｱｸｾ{else}{$mount.mount_name}{/if}</span></td>
            <td><span style="font-size:{$css_small}">
              {if $uitem}
                {$uitem.item_name}<span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$uitem.level}</span>
              {else}
                (装備なし)
              {/if}
            </span></td>
          </tr>
        {/foreach}
      </table>
  {/fieldset}

{* ブラックリストか存在しないならば *}
{else}

  ﾕｰｻﾞが存在しないか､ｱｸｾｽできないﾕｰｻﾞです｡<br />

{/if}

<br />
{if $smarty.const.PLATFORM_TYPE=='waku'}<div style="text-align:right"><a href="profile:detail?member={$smarty.get.userId}" class="buttonlike next">WAKU+ﾌﾟﾛﾌｨｰﾙ⇒</a></div>{/if}
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />

{include file="include/footer.tpl"}
