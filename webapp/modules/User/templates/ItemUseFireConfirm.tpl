{include file="include/header.tpl" title="ｱｲﾃﾑを使う"}


{* アイテム画像 *}
<div style="text-align:center">
  {item_image id=`$uitem.item_id`}<br />
  {include file='include/itemEffect.tpl' item=`$uitem`}
</div>
<br />

{* 使用確認メッセージ or エラーメッセージ *}
{image_tag file='navi_mini.gif' float='left'}
{if $error}
  <span style="color:{#errorColor#}">{$error}</span>
{else}
  <span style="color:{#termColor#}">{$uitem.item_name}</span>使うのだ?
{/if}
<br clear="all" /><div style="clear:both"></div>

{* 使用ボタン *}
{if !$error}
  <div style="text-align:center">
    {if $uitem.item_type == constant('Item_MasterService::INCR_PARAM')}
      あと<span style="color:{#statusValueColor#}">{$uitem.item_limitation-$useCount}</span>回まで<br />
    {/if}
    {button_link _self=true _sign=true go="1" _accesskey="5"}使う{/button_link}
  </div>
{/if}

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a>


{include file="include/footer.tpl"}
