{include file="include/header.tpl" title="ｼｮｯﾌﾟ"}


{* ガチャへのリンク *}
{if $userInfo.tutorial_step != constant('User_InfoService::TUTORIAL_SHOPPING')}
  <div style="text-align:center">
	<a href="{url_for module='User' action='GachaList' _backto=true}">{item_image cat='gacha' id='00002'}</a></br>
  </div></br>

{/if}

{* エラー表示 or 飾りテキストの表示 *}
{if $smarty.get.currency == 'gold'}
  {image_tag file='navi_mini.gif' float='left'}
  {if $error}
    <span style="color:{#errorColor#}">{$error}</span>
  {elseif $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_SHOPPING')}
    ｼｮｯﾌﾟではｱｲﾃﾑや装備買えるのだ｡特に<span style="color:{#termColor#}">くすりびん</span>はﾊﾞﾝﾊﾞﾝ使うから､ﾊﾞﾝﾊﾞﾝ補充するのだ<br />
    …残念ながら永久脱毛剤は売ってないのだ｡仕方ないからおとなしく<span style="color:{#termColor#}">くすりびん</span>買うのだ
  {else}
    {$shop.flavor_text}
  {/if}
{else}
  {image_tag file='navi2_mini.gif' float='left'}
  {$shop.flavor_text}
{/if}
<br clear="all" /><div style="clear:both"></div>
<br />

{* 課金／非課金の切り替え *}
<div style="text-align:center">
  {if $userInfo.tutorial_step != constant('User_InfoService::TUTORIAL_SHOPPING')}
      {switch_link _name='currency' _value='gold' buy='' page='0'}{$smarty.const.GOLD_NAME}で買う{/switch_link
    }/{switch_link _name='currency' _value='coin' buy='' page='0'}{$smarty.const.PLATFORM_CURRENCY_NAME}で買う{/switch_link}{charge_mark}
  {else}
      <span style="background-color:{#selectedBgColor#}; color:{#selectedTextColor#}">{$smarty.const.GOLD_NAME}で買う</span>/{$smarty.const.PLATFORM_CURRENCY_NAME}で買う{charge_mark}
  {/if}
</div>


{* カテゴリ切り替え *}
<div style="text-align:center">
  {if $userInfo.tutorial_step != constant('User_InfoService::TUTORIAL_SHOPPING')}
      {switch_link _name='cat' _value='ITM' buy='' page='0'}ｱｲﾃﾑ{/switch_link
    }/{switch_link _name='cat' _value='EQP' buy='' page='0'}装備品{/switch_link}
  {else}
      <span style="background-color:{#selectedBgColor#}; color:{#selectedTextColor#}">ｱｲﾃﾑ</span>/装備品
  {/if}
</div>

<br />

{* 次のLvリリースがあるならそのLvを表示 *}
{if $next}<div style="text-align:center"><span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$next.unlock_level}</span>で新ｱｲﾃﾑ</div>{/if}

{* マグナショップなら現在のマグナの量を表示 *}
{if $smarty.get.currency == 'gold'}<span style="color:{#statusNameColor#}">{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">{$userInfo.gold}</span><br />{/if}
{image_tag file='hr.gif'}<br />

{* アイテム一覧 *}
{if $list.totalRows > 0}

  {foreach from=`$list.resultset` item="content"}
    {assign var='item' value=`$content.item`}

    {if ($carrier == 'iphone' || $carrier == 'android')}
      {fieldset color='brown' width='95%'}
        <legend>
        {if $item.category!='ITM' && $item.category!='SYS'}<span style="color:white">{switch value=`$item.category` HED='頭' BOD='服' WPN='武器' ACS='ｱｸｾｻﾘ'}</span>:{/if}
          <span>{$item.item_name}</span>
        </legend>
        <div>
          {item_image id=`$item.item_id` float='left'}

          {* 条件レベル OR 価格 *}
          {if $content.show_only}
            <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$content.unlock_level}</span>から購入可
          {else}
            {charge_price price=`$content.price` currency=`$smarty.get.currency`}
            {if $content.hold}所持<span style="color:{#statusValueColor#}">{$content.hold}</span>個{/if}
          {/if}<br />

          {include file='include/itemSpec.tpl' item=`$item`}

          {if !$content.show_only}
            <div style="text-align:right">
              <a href="{$itemLink|replace:'--id--':$item.item_id}" class="buttonlike next">購入</a>
            </div>
          {/if}

        </div>
      {/fieldset}<br />
    {else}
      {* アイテム画像を float:left で表示 *}
      {item_image id=`$item.item_id` float='left'}

      {* アイテム名 *}
      {if $item.category!='ITM' && $item.category!='SYS'}<span style="color:{#statusValueColor#}">{switch value=`$item.category` HED='頭' BOD='服' WPN='武器' ACS='ｱｸｾｻﾘ'}</span>:{/if}
      {if $content.show_only}
        <span style="color:{#termColor#}">{$item.item_name}</span>
      {else}
        <a href="{$itemLink|replace:'--id--':$item.item_id}">{$item.item_name}</a>
      {/if}<br />

      {* 条件レベル OR 価格 *}
      {if $content.show_only}
        <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$content.unlock_level}</span>から購入可
      {else}
        {charge_price price=`$content.price` currency=`$smarty.get.currency`}
        {if $content.hold}所持<span style="color:{#statusValueColor#}">{$content.hold}</span>個{/if}
      {/if}

      <br clear="all" /><div style="clear:both"></div>

      {include file='include/itemSpec.tpl' item=`$item`}

      {image_tag file='hr.gif'}<br />
    {/if}
  {/foreach}

  {include file="include/pager.tpl" totalPages=`$list.totalPages`}

{else}
  <br />
  <div style="text-align:center">このｶﾃｺﾞﾘの商品はありません</div>
{/if}


<a href="{backto_url}" class="buttonlike back">←戻る</a><br />

{include file="include/footer.tpl" showCompanyName=true}
