{include file="include/header.tpl"}


{$distribute.caption}<br />
<br />

{if $smarty.get.result}

  <br />
  <div style="text-align:center; color:{#resultColor#}">ﾌﾟﾚｾﾞﾝﾄを受け取りました</div>
  <br />
  <div style="text-align:right"><a href="{url_for action='Main'}">ｿﾈｯﾄﾒﾆｭｰ⇒</a></div>
  <br />

{elseif $error}

  <br />
  <div style="text-align:center">すでに受け取っています</div>
  <br />
  <div style="text-align:right"><a href="{url_for action='Main'}">ｿﾈｯﾄﾒﾆｭｰ⇒</a></div>
  <br />

{else}

  {foreach from=`$items` item='item'}
    {item_image id=`$item.item_id` float='left'}
    <span style="color:{#termColor#}">{$item.item_name}</span><br />
    {include file='include/itemSpec.tpl' item=`$item` style='compact'}
    <br />
  {/foreach}

  <form action="{url_for _self=true}" method="post">
    <input type="hidden" name="go" value="1" />

    <div style="text-align:center">
      <input type="submit" value="{if $carrier != 'android'}{/if}ﾌﾟﾚｾﾞﾝﾄをｹﾞｯﾄ{if $carrier != 'android'}{/if}" accesskey="5" />
    </div>
  </form>

{/if}


{include file="include/footer.tpl"}
