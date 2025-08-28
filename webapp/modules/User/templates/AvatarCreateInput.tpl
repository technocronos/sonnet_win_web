{include file="include/header.tpl" title="ｷｬﾗ作成"}


{image_tag file='navigator.gif' float='right'}
{if $error_name}
  <span style="color:{#errorColor#}">{$error_name}</span>
{else}
  {if $smarty.post.tukkomi}
    仕方ないのだ何人案内させられてると思ってるのだ<br />
    <br />
    あとでｱｲﾃﾑ欄にｱｲﾃﾑ横流ししとくから許せなのだ<br />
  {elseif $smarty.post.back}
    やり直すのだ?<br />
    優柔不断の苦労性なのだ<br />
  {else}
    また誰か来たのだ…<br />
    <br />
    ここに来たやつにはとにかく礼を言うように言われてるから｢ありがとう｣と言ってやるのだ｡ありがたく受け取れなのだ<br />
    <br />
    礼を言ったら案内するように言われてるから案内してやるのだ<br />
    <br />
    まずは主人公の名前を決めるのだ<br />
  {/if}
{/if}
<br clear="all" /><div style="clear:both"></div>

<form action="{url_for _self=true}" method="post">
  <input type="hidden" name="appology" value="{$smarty.post.appology}" />

  {if $smarty.post.back  &&  !$smarty.post.appology}
    <div style="background-color:{#subBgColor#}; text-align:center">
      女の子と言い忘れてたことに<input type="submit" name="tukkomi" value="ツっこむ" />
    </div>
  {/if}

  (全角{$smarty.const.USERNAME_DISPLAY_WIDTH/2}文字まで)<br />
  <table style="width:100%"><tr>
    <td><span style="font-size:{$css_small}">{form_input name="name"}</span></td>
    <td><span style="font-size:{$css_small}"><input type="submit" value="確認{if $carrier != 'android'}{/if}" /></span></td>
  </tr></table>
  <span style="color:{#noticeColor#}">本名､ﾒｱﾄﾞなど個人の特定ができる情報は入力NGです</span><br />
</form>


{include file="include/footer.tpl"}
