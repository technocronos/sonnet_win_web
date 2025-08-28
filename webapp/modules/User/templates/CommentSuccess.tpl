{include file="include/header.tpl" title=`$smarty.const.COMMENT_NAME`}


{if $forbidden}

  このつぶやきにﾚｽを付けることはできません<br />
  <br />

{elseif $smarty.get.result}

  {image_tag file='navi_mini.gif' float='left'}
  <span style="color:{#resultColor#}">世界中に叫んどいたのだ</span>
  <br clear="all" /><div style="clear:both"></div>

{else}

  {image_tag file='navi_mini.gif' float='left'}
  {if $error}
    <span style="color:{#errorColor#}">{$error}</span>
  {elseif $smarty.get.for && $smarty.get.target}
    No{$smarty.get.for}の全部のﾚｽにﾚｽし返すのだ
  {elseif $smarty.get.for}
    No{$smarty.get.for}にﾚｽ付けちゃうのだ
  {else}
    なんでもいいからとりあえずコメントするのだ
  {/if}
  <br clear="all" /><div style="clear:both"></div>

  <form action="{url_for _self=true}" method="post">
    {form_textarea name='tweet' rows=2 initial=`$initial`}<br />
    <input type="submit" value="{$smarty.const.COMMENT_NAME}{if $carrier != 'android'}{/if}" />(全角{const name='CommentAction::COMMENT_LENGTH_LIMIT'}文字まで)<br />
    <span style="color:{#noticeColor#}">本名､ﾒｱﾄﾞなど個人の特定ができる情報は入力NGです</span><br />
  </form>

{/if}


<br />
<a href="{backto_url}"  class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
