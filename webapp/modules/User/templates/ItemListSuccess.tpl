{include file="include/header.tpl" title="ｱｲﾃﾑ一覧"}


{* 使用アイテムが決まっている場合はそのアイテム画像を表示 *}
{if $smarty.get.uitemId}
  <div style="text-align:center">{item_image id=`$uitem.item_id`}</div>
{/if}

{* ナビによる案内 *}
{image_tag file='navi_mini.gif' float='left'}
{if $smarty.get.uitemId}
  どれに使うのだ？
{else}
  {if $smarty.get.cat == 'ITM'}使いたいｱｲﾃﾑあったら押すのだ｡{else}今持ってるｱｲﾃﾑの確認なのだ｡{/if}捨てるときは⌒☆を押すのだ｡
{/if}
<br clear="all" /><div style="clear:both"></div>

{* カテゴリ切り替え *}
<div style="text-align:center">
  {switch_link _name='cat' _value='WPN'}武器{/switch_link} /
  {switch_link _name='cat' _value='BOD'}服{/switch_link} /
  {switch_link _name='cat' _value='HED'}頭{/switch_link} /
  {switch_link _name='cat' _value='ACS'}ｱｸｾｻﾘ{/switch_link}<br />
  {switch_link _name='cat' _value='ITM'}消費ｱｲﾃﾑ{/switch_link} /
  {switch_link _name='cat' _value='SYS'}その他{/switch_link}
</div>

{* アイテム一覧 *}
{include file="include/userItemList.tpl" list=`$list` nameFunc=`$nameCallback`}


{if $smarty.get.uitemId}
  <a href="{backto_url}"  class="buttonlike back">←ｷｬﾝｾﾙ</a><br />
{else}
  <a href="{url_for action='Status'}"  class="buttonlike back">←ｽﾃｰﾀｽ</a><br />
{/if}


{include file="include/footer.tpl"}
