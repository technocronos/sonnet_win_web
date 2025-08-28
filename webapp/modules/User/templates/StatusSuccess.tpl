{include file="include/header.tpl" title="ステータス"}

{* 操作の結果画面にもなっているので、必要ならば結果メッセージを表示。 *}
{if $smarty.get.result}

  {image_tag file='navi_mini.gif' float='left'}
  <span style="color:{#resultColor#}">{switch value=`$smarty.get.result` item='ｱｲﾃﾑ使っちゃったのだ' name='名前変えたのだ' tweet='世界中に叫んどいたのだ'}</span>
  <br clear="all" /><div style="clear:both"></div>

{* つぶやきのエラーがあるなら表示 *}
{elseif $error}
  {image_tag file='navi_mini.gif' float='left'}
  <span style="color:{#errorColor#}">{$error}</span>
  <br clear="all" /><div style="clear:both"></div>

{* いずれでもなく、ステータス画面のチュートリアル中なら表示 *}
{elseif $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_STATUS') }
  {image_tag file='navi_mini.gif' float='left'}
  …というわけでｺｺがｽﾃｰﾀｽ画面なのだ｡下までｽｸﾛｰﾙするのだ
  <br clear="all" /><div style="clear:both"></div>
  {image_tag file='hr.gif'}<br />
{/if}

{* チュートリアル中は出さない *}
{if $userInfo.tutorial_step != constant('User_InfoService::TUTORIAL_STATUS') }
  <div style="text-align:right"><a href="{url_for action='MonsterTop'}" class="buttonlike next">ﾓﾝｽﾀｰ図鑑⇒</a></div>

  {* つぶやきフォーム *}
  {*
  <form action="{url_for _self=true}" method="post">
    {image_tag file='fukidashi_upper.gif'}<br />
    <table style="width:100%"><tr>
      <td><span style="font-size:{$css_small}">{form_input name="tweet"}</span></td>
      <td><span style="font-size:{$css_small}"><input type="submit" value="{if $carrier != 'android'}{else}書き込む{/if}" /></span></td>
    </tr></table>
    <span style="color:{#noticeColor#}">本名､ﾒｱﾄﾞなど個人の特定ができる情報は入力NGです</span><br />
    {image_tag file='fukidashi_lower.gif'}<br />
  </form>
  *}
{/if}

{* ユーザの状態(行動pt、金、仲間) *}
{fieldset color='brown' width='95%'}
  <div pc-style="line-height:2em">

    {platform_thumbnail size='M' float='left'}
    <span style="color:{#statusNameColor#}">行動pt</span><span style="color:{#statusValueColor#}">{$userInfo.action_pt|int|space_format:'%3d'}</span>{include file='include/gauge.tpl' value=`$userInfo.action_pt` max=`$smarty.const.ACTION_PT_MAX` type='AP'}<br />
    <span style="color:{#statusNameColor#}">対戦pt</span><span style="color:{#statusValueColor#}">{$userInfo.match_pt|int|space_format:'%3d'}</span>{include file='include/gauge.tpl' value=`$userInfo.match_pt` max=`$smarty.const.MATCH_PT_MAX` type='MP'}<br />
    <span style="color:{#statusNameColor#}">{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">{$userInfo.gold}</span><br />
    <span style="color:{#statusNameColor#}">仲間</span><span style="color:{#statusValueColor#}">{$member.current}</span>+<span style="color:{#statusNameColor#}">申請</span><span style="color:{#statusValueColor#}">{$member.request+$member.receive}</span>/<span style="color:{#statusNameColor#}">最大</span><span style="color:{#statusValueColor#}">{$member.limit}</span>
    <br clear="all" /><div style="clear:both"></div>

  </div>
{/fieldset}

{* キャラ選択の必要がない操作 *}
<div style="text-align:center">
  {if $smarty.const.SONNET_NOW_OPEN}
  <a href="{url_for action='Comment' _backto=true}" class="buttonlike label">{$smarty.const.COMMENT_NAME}</a>
  <br />
  {/if}
  ｱｲﾃﾑを
  <a href="{url_for action='ItemList' cat='ITM'}" class="buttonlike label">使う</a>
  <a href="{url_for action='ItemList'}" class="buttonlike label">確認</a>
  <a href="{url_for action='Shop' cat='ITM' _backto=true}" class="buttonlike label">買う</a>
</div>

{* キャラ、および、キャラ選択の必要がある操作 *}
{image_tag file='hr.gif'}<br />

{fieldset color='brown' width='95%'}
  <div pc-style="line-height:2em">
    <legend>{text id=`$character.name_id`}</legend>

    {chara_img chara=`$character` float='left'}

    
    <a href="{url_for action='Equip' charaId=`$character.character_id`}" class="buttonlike label">装備変更</a><br />
    <a href="{url_for action='NameChange' charaId=`$character.character_id`}" class="buttonlike label">名前変更</a><br />
    {if $character.param_seed > 0}<span style="text-decoration:blink"></span><a href="{url_for action='ParamUp' charaId=`$character.character_id` _backto=true}" class="buttonlike label">振り分け</a>{else}<span style="color:{#noticeColor#}">振り分け</span>{/if}<br />
    &nbsp;&nbsp;┗<span style="color:{#statusNameColor#}">ｽﾃｰﾀｽpt</span><span style="color:{#statusValueColor#}">{$character.param_seed}</span><br />
    <br clear="all" /><div style="clear:both"></div>

{/fieldset}

{fieldset color='brown' width='95%'}

    <legend>ステータス</legend>

    {* キャラのステータス *}
    {include file='include/characterStatus.tpl' chara=`$character` equip=true}

{/fieldset}

{fieldset color='brown' width='95%'}

    <legend>階級</legend>

    <span style="color:{#statusNameColor#}">階級</span><span style="color:{#statusValueColor#}">{text grade=`$character.grade_id`}</span>(<span style="color:{#statusNameColor#}">階級pt</span><span style="color:{#statusValueColor#}">{$character.grade_pt}</span>)<br />
    ┗<span style="color:{#statusNameColor#}">昇格ﾗｲﾝ</span>{if $grade.raise_border}<span style="color:{#statusValueColor#}">{$grade.raise_border}</span>{else}<span style="color:#FF3333; text-decoration:blink">最高位</span>{/if} <span style="color:{#statusNameColor#}">降格ﾗｲﾝ</span><span style="color:{#statusValueColor#}">{if $grade.abase_border}{$grade.abase_border}{else}--{/if}</span><br />

{/fieldset}

{fieldset color='brown' width='95%'}

    <legend>レベル</legend>

    <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$character.level}</span>(経験値<span style="color:{#statusValueColor#}">{$character.exp}</span>)<br />
    ┗LvUPあと<span style="color:{#statusValueColor#}">{if $exp.relative_next > 0}{$exp.relative_next-$exp.relative_exp}{else}---{/if}</span>{include file='include/gauge.tpl' value=`$exp.relative_exp` max=`$exp.relative_next` type='EXP'}<br />

{/fieldset}

{fieldset color='brown' width='95%'}

    <legend>戦歴</legend>

    <span style="color:{#statusValueColor#}">{$ctour.fights}</span>戦<span style="color:{#statusValueColor#}">{$ctour.win}</span>勝<span style="color:{#statusValueColor#}">{$ctour.lose}</span>敗<span style="color:{#statusValueColor#}">{$ctour.draw}</span>分<br />
    週間ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$rank.weekly|default:'--'}</span>位<br />
    日別ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$rank.daily|default:'--'}</span>位<br />

    {* アイテム効果の期限 *}
    {include file='include/effectExpires.tpl'}

  </div>
{/fieldset}


<br />
{* ステータス画面のチュートリアル中なら表示 *}
{if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_STATUS') }
  {image_tag file='hr.gif'}<br />
  {image_tag file='navi_mini.gif' float='left'}
  ｺﾞﾁｬｺﾞﾁｬしてるのだけど､<span style="color:{#termColor#}">ｱｲﾃﾑ使</span>ったり<span style="color:{#termColor#}">装備変更</span>するときはｺｺに来るのだ<br />
  分かったら次はｼｮｯﾌﾟに行く…前にじじぃにもらうﾓﾉもらうのだ<a href="{url_for module='Swf' action='Tutorial' done='Status'}">⇒go</a>
  <br clear="all" /><div style="clear:both"></div>

{else}
  <a href="{url_for action='Main'}" class="buttonlike back">←戻る</a><br />
{/if}


{include file="include/footer.tpl"}
