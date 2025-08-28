{include file="include/header.tpl" title="ｽﾍﾟｼｬﾙｶﾞﾁｬｼｮｯﾌﾟ"}


{image_tag file='navi2_mini.gif' float='left'}
{if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_GACHA')}
  ここはﾜｼが案内してやろう｡<br />
  ｶﾞﾁｬには すぺっしゃる なｱｲﾃﾑがたくさんあるぞい｡だいたい{$smarty.const.PLATFORM_CURRENCY_NAME}で回すんじゃがな｡一日一回なら無料で回せるやつもある｡<span style="color:{#termColor#}">雑貨ｶﾞﾁｬ</span>をｸﾘｯｸじゃ!
{else}
  {$smarty.const.PLATFORM_CURRENCY_NAME}{charge_mark}でｶﾞﾁｬを回してｱｲﾃﾑｹﾞｯﾄするのじゃ｡回せ回せ!人生とはｷﾞｬﾝﾌﾞﾙじゃぞ!
{/if}
<br clear="all" /><div style="clear:both"></div>


{* フリーチケット *}
{if $ticketCount > 0}
  <div style="text-decoration:blink; text-align:center">ﾌﾘｰﾁｹｯﾄ<span style="color:{#statusValueColor#}; text-decoration:blink">{$ticketCount}</span>枚所持</div>
{/if}

{image_tag file='hr.gif'}<br />

{* ガチャチュートリアル中は無料ガチャだけを出す *}
{if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_GACHA')}

  <div style="text-align:center">
    <a href="{url_for action='GachaFree' _backto=true}">{item_image cat='gacha' id=9998}</a><br />
  </div>
  雑貨ｱｲﾃﾑ詰め合わせのｶﾞﾁｬじゃ｡ここにしかないものもあるぞい｡無料のときと有料のときで中身が変わるんじゃ<br />
  {image_tag file='hr.gif'}<br />

{else}

  {foreach from=`$list.resultset` item="gacha"}

    <div style="text-align:center">

      {* 画像 *}
      {if $gacha.unlock_level <= $userLevel}
        {if $freeGacha && $gacha.gacha_id == 9998}
          <a href="{url_for action='GachaFree' _backto=true}">{item_image cat='gacha' id=`$gacha.gacha_id`}</a>
        {else}
          <a href="{url_for action='GachaDetail' gachaId=`$gacha.gacha_id` backto=`$smarty.get.backto`}">{item_image cat='gacha' id=`$gacha.gacha_id`}</a>
        {/if}
      {else}
        {item_image cat='gacha' id=`$gacha.gacha_id`}
      {/if}<br />

      {* 開放条件 *}
      {if $gacha.unlock_level <= $userLevel}
        {if $gacha.gacha_kind == 2}
            {charge_price price=`$gacha.price`}
        {else}
            {charge_price price=`$gacha.price` currency='gold'}
        {/if}
      {else}
        ﾚﾍﾞﾙ<span style="color:{#statusValueColor#}">{$gacha.unlock_level}</span>から購入可能<br />
      {/if}
    </div>

    {* 飾りテキスト *}
    {$gacha.flavor_text}<br />

    {image_tag file='hr.gif'}<br />
  {/foreach}

  {include file='include/pager.tpl' totalPages=`$list.totalPages`}
{/if}

{if $userInfo.tutorial_step != constant('User_InfoService::TUTORIAL_GACHA')}
  <a href="{backto_url}" class="buttonlike back">←戻る</a><br />
{/if}


{include file="include/footer.tpl" showCompanyName=true}
