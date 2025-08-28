{include file="include/header.tpl" title="名前変更"}


{image_tag file='navi_mini.gif' float='left'}
{if $error_name}
  <span style="color:{#errorColor#}">{$error_name}</span>
{else}
  名前を変更しちゃうのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

<br />
<form action="{url_for _self=true}" method="post">
  {chara_img chara=`$chara` float='right'}
  (全角{$smarty.const.USERNAME_DISPLAY_WIDTH/2}文字まで)<br />
  {form_input name="name" size=12}<input type="submit" value="変更" /><br />
  <span style="color:{#noticeColor#}">本名､ﾒｱﾄﾞなど個人の特定ができる情報は入力NGです</span>
  <br clear="all" /><div style="clear:both"></div>
</form>

<!--
<a href="{backto_url}" class="buttonlike back">←戻る</a>
-->

{include file="include/footer.tpl"}
