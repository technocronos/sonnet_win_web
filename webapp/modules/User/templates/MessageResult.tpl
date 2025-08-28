{include file="include/header.tpl" title="ﾒｯｾｰｼﾞ"}


{image_tag file='navi_mini.gif' float='left'}
ﾒｯｾｰｼﾞ送っといてやったのだ<br />
{if $smarty.get.result == 'companion_limit'}
  このﾕｰｻﾞには<span style="color:{#statusValueColor#}">{const name='Message_LogService::FAVOR_LIMIT_PER_COMPANION'}</span>回以上ﾒｯｾｰｼﾞしてるから､今日はもう<span style="color:{#statusNameColor#}">階級pt</span>入らないのだ
{elseif $smarty.get.result == 'daily_limit'}
  全部で<span style="color:{#statusValueColor#}">{const name='Message_LogService::FAVOR_LIMIT_PER_DAY'}</span>回以上ﾒｯｾｰｼﾞしてるから､今日はもう<span style="color:{#statusNameColor#}">階級pt</span>入らないのだ
{elseif $smarty.get.result == 'take_favor'}
  相手のﾕｰｻﾞに<span style="color:{#statusNameColor#}">階級pt</span><span style="color:{#statusValueColor#}">+1</span>なのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
