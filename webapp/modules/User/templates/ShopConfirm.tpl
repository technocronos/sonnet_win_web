{include file="include/header.tpl" title="ｼｮｯﾌﾟ"}


{* アイテム画像、アイテム名、値段 *}
{item_image id=`$sale.item_id` float='left'}
{$sale.item.item_name}<br />
{charge_price price=`$sale.price` currency=`$smarty.get.currency`}
<br clear="all" /><div style="clear:both"></div>
{include file='include/itemSpec.tpl' item=`$sale.item`}
<br />

{* 案内 *}
{if $smarty.get.currency == 'gold'}
  {image_tag file='navi_mini.gif' float='left'}
  {if call_user_func('Item_MasterService::isDurable', $sale.item.category)}なら｢購入｣を押すのだ
  {else}個数を選んで｢購入｣を押すのだ{/if}
{else}
  {image_tag file='navi2_mini.gif' float='left'}
  {if call_user_func('Item_MasterService::isDurable', $sale.item.category)}｢購入｣を押すのじゃ｡ﾎﾚ押さんかい
  {else}個数を選んで｢購入｣を押すのじゃ｡ｶﾞｯといけｶﾞｯと{/if}
{/if}
<br clear="all" /><div style="clear:both"></div>

<form action="{url_for _self=true}" method="post">

  {* ゲーム内通貨で購入なら、現在の所持金を表示 *}
  {if $smarty.get.currency == 'gold'}
    <div style="text-align:center">
      <span style="color:{#statusNameColor#}">所持{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">{$userInfo.gold}</span><br />
    </div>
  {/if}

  {* 個数決定＆確定ボタン *}
  <div style="text-align:center">
    {if call_user_func('Item_MasterService::isDurable', $sale.item.category)}
      <input type="hidden" name="num" value="1" />
    {else}
      <select name="num">
        {*
          mixi先生は「一度に課金できるのは3000円までよ(はぁと)とおっしゃるので、単価最高を500円と仮定して、選択肢を6個までとする
          また「mixiポイントを表記するときは『mixi吹き出し(画像)＋ポイント数＋「pt」』にしなさいともおっしゃるので、
          画像を表示できないリストボックスでは表記しないようにする。
        *}
        {if $smarty.const.PLATFORM_TYPE=='mixi'}{assign var='loop' value=7}{else}{assign var='loop' value=10}{/if}
        {section name="foo" start=1 loop=`$loop`}
          <option value="{$smarty.section.foo.index}">{$smarty.section.foo.index}個{if PLATFORM_TYPE!='mixi'}({$sale.price*$smarty.section.foo.index}{$currencyName}){/if}</option>
        {/section}
      </select>
    {/if}
    <input type="submit" value="購入" />
  </div>
</form>

{* キャンセル *}
<br />
<a href="{url_for _self=true buy=''}" class="buttonlike back">←ｷｬﾝｾﾙ</a>


{include file="include/footer.tpl" showCompanyName=true}
