{include file="include/header.tpl" title="捨てる"}


{if $smarty.get.result}

  {image_tag file='navi_mini.gif' float='left'}
  ﾎﾟｲｯ⌒☆と流れ星にしといてやったのだ
  <br clear="all" /><div style="clear:both"></div>

{else}

  {* アイテム画像 *}
  <div style="text-align:center">
    {item_image id=`$uitem.item_id`}<br />
  </div>
  <br />

  {* 捨てられるなら表示 *}
  {if !$error}

    {* 確認メッセージ *}
    <a href="{url_for _self=true miss=1}">{image_tag file='navi_mini.gif' float='left'}</a>
    {if $smarty.get.miss}
      …もじょじゃなくて､<span style="color:{#termColor#}">{$uitem.item_name}</span>なのだ
    {else}
      <span style="color:{#termColor#}">{$uitem.item_name}</span>捨てるのだ?{if $uitem.num > 1}一つだけじゃなくて全部捨てるのだ{/if}
    {/if}
    <br clear="all" /><div style="clear:both"></div>

    {* 確定ボタン *}
    <div style="text-align:center">
      <form action="{url_for _self=true}" method="post"><input type="submit" name="go" value="捨てる" /></form>
    </div>

  {* 捨てられないならそれを表示 *}
  {else}

    {image_tag file='navi_mini.gif' float='left'}
    {switch value=`$error` equipping='装備中のものは捨てられないのだ' forbidden='それ捨てちゃﾀﾞﾒなのだ｡なんでもﾎﾟｲﾎﾟｲやっちゃﾀﾞﾒなのだ'}
    <br clear="all" /><div style="clear:both"></div>

  {/if}
{/if}


<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a>


{include file="include/footer.tpl"}
