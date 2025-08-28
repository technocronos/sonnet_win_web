{include file="include/header.tpl" title="ｷｬﾗ作成"}


<form action="{url_for _self=true}" method="post">
  {form_dump_on_hidden}

  {image_tag file='navigator.gif' float='right'}
  <span style="color:{#termColor#}">{$smarty.post.name}</span>っていう名前でいいのだ?
  {if !$smarty.post.appology}
    <br />言い忘れてたけど､主人公は女の子なのだ
  {/if}
  <br clear="all" /><div style="clear:both"></div>

  <div style="text-align:center">
    <input type="submit" name="save" value="{if $carrier != 'android'}{else}ok{/if}">
    <input type="submit" name="back" value="{if $carrier != 'android'}{else}戻る{/if}">
  </div>
</form>


{include file="include/footer.tpl"}
