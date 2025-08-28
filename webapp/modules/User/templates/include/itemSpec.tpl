{*
    ショップのアイテム等、ユーザが手に入れているわけではないアイテムの詳細情報を
    表示するテンプレート。

    パラメータ)
        item    Item_MasterService::getExRecordで取得したアイテムレコード。
        style   飾り文言をアイテム画像の回り込みとして表示したいなら "compact" を指定する。
*}

  {* 飾り文言 *}
  {if $style=='compact'}
    {$item.flavor_text}<br clear="all" />
    <div style="clear:both"></div>
  {else}
    {$item.flavor_text}<br />
  {/if}

  {* 効果 *}
  {if call_user_func('Item_MasterService::isDurable', $item.category)}
    <span style="color:{#statusNameColor#}">耐久</span><span style="color:{#statusValueColor#}">{$item.durability|durability}</span>
    <br />
    {include file='include/equipSpec.tpl' item=`$item`}

  {else}
    {include file='include/itemEffect.tpl' item=`$item`}<br />
  {/if}
