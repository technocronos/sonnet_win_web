{include file="include/header.tpl" title="ﾁｰﾑ対戦ﾌｨｰﾙﾄﾞ選択"}


<div style="text-align:right"><a href="{url_for _self=true action='TeamBattleConfirm'}">通常対戦⇒</a></div>

{image_tag file='navi_mini.gif' float='left'}
戦いの舞台を選んで<span style="color:{#termColor#}">対戦開始</span>を押すのだ
<br clear="all" /><div style="clear:both"></div>

<a href="{url_for _self=true page=0 member2=null}">←ｷｬﾝｾﾙ</a><br />
<br />

<form method="post" action="{url_for _self=true}">

  {foreach from=`$rooms` item='room' key='index' name='foo'}
    <div><input type="radio" name="roomNo" value="{$index}" {if $smarty.foreach.foo.first}checked="checked"{/if} />{$room.x_room_name}</div>
  {/foreach}
  <br />

  <div style="text-align:center">
    <input type="submit" value="対戦開始{if $carrier != 'android'}{/if}" accesskey="5" /><br />
    <span style="color:{#termColor#}">{$ticket.item_name}</span><span style="color:{#statusValueColor#}">{$ticketCount}</span>枚所持<br />
    対戦すると<span style="color:{#statusValueColor#}">1</span>枚消費します<br />
  </div>
</form>

<br />
<a href="{url_for action='HisPage' userId=`$rival.user_id` backto=`$smarty.get.backto`}">←戻る</a><br />


{include file="include/footer.tpl"}
