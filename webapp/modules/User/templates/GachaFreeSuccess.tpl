{include file="include/header.tpl" title='一日一回無料ｶﾞﾁｬ'}


{if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_GACHA')}
  {image_tag file='navi2_mini.gif' float='left'}
  <span style="color:{#termColor#}">ﾄﾗｲ</span>をｸﾘｯｸじゃ<br />
  <br clear="all" /><div style="clear:both"></div>
{else}
  {image_tag file='navi_mini.gif' float='left'}
  このｶﾞﾁｬは一日一回ﾀﾀﾞで回せるのだ
  <br clear="all" /><div style="clear:both"></div>
{/if}


{* コントロール *}
<div style="text-align:center">
  {if $tryable}
    <form action="{url_for _self=true}" method="post">
      <input type="hidden" name="go" value="1" />
      <input type="submit" value="{if $carrier != 'android'}{/if}ﾄﾗｲ" accesskey="5" />
    </form>
  {else}
    今日はもうﾄﾗｲ済みです
  {/if}
</div>
<br />

{* 内容一覧 *}
<span style="color:{#noticeColor#}">以下のｱｲﾃﾑの中からいずれか一つが入手できます</span><br />
<span style="color:{#noticeColor#}">入手済みでも重複して出現します</span><br />
{image_tag file='hr.gif'}<br />
{foreach from=`$list` item="content"}
  {assign var='item' value=`$content.item`}

{fieldset color='brown' width='95%'}
  {* アイテム画像とアイテム名、出現率 *}
  {item_image id=`$item.item_id` float='left'}
  <span style="color:{#termColor#}">{$item.item_name}</span><br />
  ﾚｱ度<span style="color:#FFFF00">{call func='str_repeat' 0='★' 1=`$item.rear_level`}</span><br clear="all" />
  <div style="clear:both"></div>

  {* アイテムの詳細 *}
  {include file='include/itemSpec.tpl'}
{/fieldset}
  {image_tag file='hr.gif'}<br />
{/foreach}

{* コントロール *}
<div style="text-align:center">
  {if $tryable}
    <form action="{url_for _self=true}" method="post">
      <input type="hidden" name="go" value="1" />
      <input type="submit" value="{if $carrier != 'android'}{/if}ﾄﾗｲ" accesskey="5" />
    </form>
  {else}
    今日はもうﾄﾗｲ済みです
  {/if}
</div>

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl" showCompanyName=true}
