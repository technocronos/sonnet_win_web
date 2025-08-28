{include file="include/header.tpl" title="対戦確認"}

{if $smarty.const.TEAM_BATTLE_OPEN}
<div style="text-align:right"><a href="{url_for _self=true action='TeamBattle'}" class="buttonlike next">ﾁｰﾑ対戦⇒</a></div>
{/if}

{if $canBattle == 'ok'}

  {if $smarty.const.PLATFORM_TYPE != 'mixi' && $smarty.const.PLATFORM_TYPE != 'waku'}
    <!--<object data="{url_for module='Swf' action='CharaPair' chara1=`$spec1` chara2=`$spec2`}" type="application/x-shockwave-flash" width="{$chara_width}" height="{$chara_height}"></object>-->
  {/if}
  {include file='include/fightData.tpl' chara1=`$chara1` chara2=`$chara2`}

  {* チュートリアルならメッセージ表示 *}
  {if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_RIVAL')}
    {image_tag file='hr.gif'}<br />
    {image_tag file='navi_mini.gif' float='left'}
    ｺｺで<span style="color:{#termColor#}">対戦開始</span>を押したらﾊﾞﾄﾙなのだ｡<br />
    ﾊﾞﾄﾙで減ったHPは時間で回復するのだけど､時間かかるのだ｡<span style="color:{#termColor#}">くすりびん</span>使えば一発で回復なのだ｡<br />
    ちなみに対戦のHPと<span style="color:{#termColor#}">ｸｴｽﾄ</span>のHPは別なのだ｡ｸｴｽﾄ出るときはいつもHP満タンからｽﾀｰﾄなのだ｡<br />
    案内はこんなとこなのだ｡<a href="{url_for module='Swf' action='Tutorial' done='Rival'}" class="buttonlike next">じじぃの家に戻る</a>のだ<br />
    対戦したかったらこのまま対戦してもいいのだ<br clear="all" />
    <div style="clear:both"></div>
    {image_tag file='hr.gif'}<br />
  {/if}

  <br />
  <form method="post" action="{url_for _self=true}">
    <div style="text-align:center">
      現在の<span style="color:{#statusNameColor#}">対戦pt</span><span style="color:{#statusValueColor#}">{$userInfo.match_pt|int}</span><br />
      <input type="submit" id="submit_button" name="doBattle" value="{if $carrier != 'android'}{/if}対戦開始{if $carrier != 'android'}{/if}" accesskey="5" /><br />
      ﾊﾞﾄﾙは<span style="color:{#statusNameColor#}">対戦pt</span><span style="color:{#statusValueColor#}">{$smarty.const.USER_BATTLE_CONSUME}</span>消費します｡<br />
    </div>
  </form>

{else}

  <br />
  {image_tag file='navi_mini.gif' float='left'}
  {switch value=`$canBattle`
    consume_pt= "対戦pt足りないのだ…"
    count_rival="こいつ今日もう`$smarty.const.DUEL_LIMIT_ON_DAY_RIVAL`回戦ってるのだ｡あんまりやるとになるのだ"
    sphere=     "ﾌｨｰﾙﾄﾞｸｴｽﾄ中だから対戦できないのだ｡もうちょっと後にしてみるのだ"
  }
  <br clear="all" /><div style="clear:both"></div>

{/if}

<br />
<a href="{url_for action='HisPage' userId=`$chara2.user_id` backto=`$smarty.get.backto`}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
