{include file="include/header.tpl" title="ﾒｯｾｰｼﾞ"}


{if $canCommunicate == 'ok'}

  {image_tag file='navi_mini.gif' float='left'}
  {if $error}<span style="color:{#errorColor#}">{$error}</span>
  {else}ﾒｯｾｰｼﾞ送ると､受けたほうが<span style="color:{#termColor#}">階級pt</span>ﾁｮｯﾋﾟﾘもらえるのだ{/if}
  <br clear="all" /><div style="clear:both"></div>

  <form action="{url_for _self=true}" method="post">
    {form_textarea name='body' rows=2}<br />
    <input type="submit" value="送る{if $carrier != 'android'}{/if}" />(全角{$smarty.const.MESSAGE_LENGTH_LIMIT}文字まで)<br />
    <span style="color:{#noticeColor#}">本名､ﾒｱﾄﾞなど個人の特定ができる情報は入力NGです</span><br />
  </form>
  <br />

  {include file='include/pHeader.tpl' text="`$companion.short_name`へのﾒｯｾｰｼﾞ"}
  {include file="include/messageList.tpl" userId=`$companion.user_id` count="3" page="0" pagerType="more"}

{else}

  <span style="color:{#errorColor#}">このﾕｰｻﾞにはﾒｯｾｰｼﾞを送信できません。</span><br />

{/if}

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
