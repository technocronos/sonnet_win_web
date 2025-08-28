{include file="include/header.tpl" title="ﾌﾟﾚｾﾞﾝﾄ"}


<div style="text-align:center">{item_image id=`$uitem.item_id`}</div>

{if !$error}

  {image_tag file='navi_mini.gif' float='left'}
  <span style="color:{#termColor#}">{$companion.short_name}</span>に<span style="color:{#termColor#}">{$uitem.item_name}</span>あげちゃうのだ?
  <br clear="all" /><div style="clear:both"></div>

  <form action="{url_for _self=true}" method="post">
    <div style="text-align:center"><input type="submit" name="go" value="ﾌﾟﾚｾﾞﾝﾄする" /></div>
  </form>

{else}

  {image_tag file='navi_mini.gif' float='left'}
  {switch value=`$error` equipping='装備中のものはプレゼントできないのだ' forbidden='それﾌﾟﾚｾﾞﾝﾄしちゃﾀﾞﾒって言われてるのだ'}
  <br clear="all" /><div style="clear:both"></div>

{/if}

<br />
<a href="{url_for _self=true uitemId=''}" class="buttonlike back">←ｷｬﾝｾﾙ</a><br />


{include file="include/footer.tpl"}
