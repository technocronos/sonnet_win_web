{include file="include/header.tpl" title="ｷﾞﾌﾞｱｯﾌﾟ"}


{* ナビメッセージ *}
{image_tag file='navi_mini.gif' float='left'}
ｷﾞﾌﾞｱｯﾌﾟして<span style="color:{#termColor#}">{$quest.quest_name}</span>強制終了するのだ？<br />
{if $quest.penalty_pt > 0}
  <span style="color:{#statusNameColor#}">ﾏｸﾞﾅ</span>が<span style="color:{#statusValueColor#}">{$quest.penalty_pt}</span>減っちゃうけど､また最初っからやり直せるのだ｡
{else}
  このｸｴｽﾄはｷﾞﾌﾞｱｯﾌﾟしてもﾍﾟﾅﾙﾃｨないのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

<form action="{url_for _self=true}" method="post">
  <input type="hidden" name="giveup" value="1" />
  <div style="text-align:center"><input type="submit" value="ｷﾞﾌﾞｱｯﾌﾟ{if $carrier != 'android'}{/if}" /></div>
</form>

<br />
<a href="{url_for _self=true giveup=null}" class="buttonlike back">←ｷｬﾝｾﾙ</a><br />


{include file="include/footer.tpl"}
