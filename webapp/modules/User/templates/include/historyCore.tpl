{*
    指定されたhistory_logレコードの内容を説明するHTMLを出力するテンプレート

    パラメータ)
        history   表示したいhistory_logレコード。
*}

{if $history.deleted_at}
  (削除)
{elseif $history.type == constant('History_LogService::TYPE_BATTLE_CHALLENGE') || $history.type == constant('History_LogService::TYPE_BATTLE_DEFENCE')}

  {if $history.battle}

    {* 誰と戦ったのか *}
    <a href="{url_for module='User' action='HisPage' userId=`$history.battle.rival_user_id` _backto=true}" class="buttonlike label">{$history.battle.rival_user_name}</a>の<span style="color:{#termColor#}">{$history.battle.rival_character_name}</span>に対戦を{if $history.type==1}挑みました{else}挑まれました{/if}<br />

    {* バトルサマリ *}
    <span style="color:{#statusNameColor#}">結果</span><span style="color:{#statusValueColor#}">{switch value=`$history.battle.bias_status` win='勝ち' lose='負け' draw='相討ち' timeup='時間切れ'}</span>
    {if $history.battle.tournament_id == constant('Tournament_MasterService::TOUR_MAIN')}
      <span style="color:{#statusNameColor#}">階級pt</span><span style="color:{#statusValueColor#}">{$history.battle.bias_result.gain.grade|plus_minus}</span>
    {/if}

    {* コメント *}
    {if $history.battle.comment}
      <br />
      {image_tag file='comment.gif' float='left'}
      {$history.battle.comment|nl2br}
      <br clear="all" /><div style="clear:both"></div>
    {/if}

  {else}
    ﾊﾞﾄﾙ情報は削除されました
  {/if}

{elseif $history.type == constant('History_LogService::TYPE_CHANGE_GRADE')}
  <span style="color:{#statusValueColor#}">{text id=`$history.character.name_id`}</span>が<span style="color:{#termColor#}">{$history.grade.grade_name}</span>に<span style="color:{#statusValueColor#}">{if $history.ref2_value > 0}昇格{else}降格{/if}</span>しました

{elseif $history.type == constant('History_LogService::TYPE_LEVEL_UP')}
  <span style="color:{#statusValueColor#}">{text id=`$history.character.name_id`}</span>のﾚﾍﾞﾙが<span style="color:{#statusValueColor#}">{$history.ref2_value}</span>になりました

{elseif $history.type == constant('History_LogService::TYPE_EFFECT_TIMEUP')}
  <span style="color:{#termColor#}">{text id=`$history.character.name_id`}</span>の<span style="color:{#termColor#}">{$history.effect_name}</span>の効果がきれました

{elseif $history.type == constant('History_LogService::TYPE_INVITE_SUCCESS')}
  {if $history.invited}
    <a href="{url_for module='User' action='HisPage' userId=`$history.ref1_value` _backto=true}" class="buttonlike label">{$history.invited.short_name}</a>さんがｹﾞｰﾑ招待に応じてくれました<span style="color:{#strongColor#}">特典ｹﾞｯﾄ!</span>
  {else}
    友だち招待に応じたため<span style="color:{#strongColor#}">特典ｹﾞｯﾄ!</span>
  {/if}

{elseif $history.type == constant('History_LogService::TYPE_PRESENTED')}
  <a href="{url_for module='User' action='HisPage' userId=`$history.ref1_value` _backto=true}" class="buttonlike label">{$history.giver.short_name}</a>さんから<span style="color:{#termColor#}">{$history.item.item_name}</span>をﾌﾟﾚｾﾞﾝﾄしてもらいました

{elseif $history.type == constant('History_LogService::TYPE_QUEST_FIN')}
  ｸｴｽﾄ<span style="color:{#termColor#}">{$history.quest.quest_name}</span>を{switch value=`$history.ref2_value` 1='成功' 2='失敗' 3='ｷﾞﾌﾞｱｯﾌﾟ' 4='脱出'}しました

{elseif $history.type == constant('History_LogService::TYPE_ITEM_BREAK')}
  <span style="color:{#termColor#}">{$history.item.item_name}</span>が壊れました

{elseif $history.type == constant('History_LogService::TYPE_ITEM_LVUP')}
  <span style="color:{#termColor#}">{$history.item.item_name}</span>がLv<span style="color:{#statusValueColor#}">{$history.ref2_value}</span>になりました

{elseif $history.type == constant('History_LogService::TYPE_WEEKLY_HIGHER')}
  週間ﾗﾝｷﾝｸﾞ<span style="color:{#statusValueColor#}">{$history.ref1_value}</span>位!<span style="color:{#termColor#}">{$history.item.item_name}</span>をｹﾞｯﾄしました

{elseif $history.type == constant('History_LogService::TYPE_CAPTURE')}
  <span style="color:{#statusValueColor#}">{$history.rare_name}</span>ﾓﾝｽﾀｰ<span style="color:{#termColor#}">{text id=`$history.monster.name_id`}</span>をｹﾞｯﾄしました

{elseif $history.type == constant('History_LogService::TYPE_ADMIRED')}
  <a href="{url_for action='CommentTree' top=`$history.ref1_value` _backto=true}" class="buttonlike label">No{$history.ref1_value}</a>のつぶやきに<span style="color:{#termColor#}">{$smarty.const.ADMIRATION_NAME}</span>をもらいました

{elseif $history.type == constant('History_LogService::TYPE_REPLIED')}
  <a href="{url_for action='CommentTree' top=`$history.ref1_value` _backto=true}" class="buttonlike label">No{$history.ref1_value}</a>のつぶやきに<span style="color:{#termColor#}">ﾚｽ</span>がつきました

{elseif $history.type == constant('History_LogService::TYPE_COMMENT')}
  {$history.comment|nl2br}

{elseif $history.type == constant('History_LogService::TYPE_QUEST_FIN2')}
  ｸｴｽﾄ<span style="color:{#termColor#}">{$history.summary.quest_name}</span>を<span style="color:{#strongColor#}">{switch value=`$history.summary.result` 1='成功' 2='失敗' 3='ｷﾞﾌﾞｱｯﾌﾟ' 4='脱出'}</span>しました
  {if $history.summary.attain_stair}<br /><span style="color:{#statusValueColor#}">{$history.summary.attain_stair}</span>階まで到達{/if}

{elseif $history.type == constant('History_LogService::TYPE_TEAM_BATTLE')}

  {if $history.summary}
    {if $history.ref2_value == 1}
      ﾁｰﾑ対戦を挑んで<span style="color:{#strongColor#}">{switch value=`$history.summary.result` 1='勝利' 2='敗北' 3='ｷﾞﾌﾞｱｯﾌﾟ' 4='脱出'}</span>しました
    {elseif $history.ref2_value == 2}
      ﾁｰﾑ対戦を挑まれて<span style="color:{#strongColor#}">{switch value=`$history.summary.result` 1='敗北しました' 2='勝利しました' 3='ｷﾞﾌﾞｱｯﾌﾟさせました' 4='脱出させました'}</span>
    {elseif $history.ref2_value == 11}
      ﾁｰﾑ対戦に召還されて<span style="color:{#strongColor#}">{switch value=`$history.summary.result` 1='勝利' 2='敗北' 3='ｷﾞﾌﾞｱｯﾌﾟ' 4='脱出'}</span>しました
    {/if}<br />

    挑戦側
    {foreach from=`$history.challenger` item='user'}
      <a href="{url_for action='HisPage' userId=`$user.user_id` _backto=true}" class="buttonlike label">{$user.short_name}</a>
    {/foreach}<br />

    防衛側
    {foreach from=`$history.defender` item='user'}
      <a href="{url_for action='HisPage' userId=`$user.user_id` _backto=true}" class="buttonlike label">{$user.short_name}</a>
    {/foreach}
  {/if}

{/if}
