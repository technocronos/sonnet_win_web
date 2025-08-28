{include file="include/header.tpl" title="仲間申請･送信"}


<div style="text-align:center">
    {switch_link _name='side' _value='receive' page='0'}受信勧誘{/switch_link
  }/{switch_link _name='side' _value='send'    page='0'}送信勧誘{/switch_link}
</div>
<br />

{* 結果メッセージ or ナビゲーションメッセージ *}
{image_tag file='navi_mini.gif' float='left'}
{if $smarty.get.result}
  <span style="color:{#resultColor#}">{switch value=`$smarty.get.result`
    cancel='そ～っとｷｬﾝｾﾙしといたのだ｡きっとﾊﾞﾚてないのだ'
    clear='ｷﾚｲにしてやったのだ'
  }</span>
{elseif $list.totalRows == 0}
  誰にも申請してないのだ…けっこう女王様気質なのだ
{else}
  仲間になるように命令しといたやつらなのだ｡
{/if}
<br clear="all" /><div style="clear:both"></div>

{* 一覧 *}
{if $list.totalRows > 0}

  {image_tag file='hr.gif'}<br />
  {foreach from=`$list.resultset` item='item'}
    <form action="{url_for _self=true result=''}" method="post">
      <input type="hidden" name="approach_id" value="{$item.approach_id}" />

      {platform_thumbnail src=`$item.thumbnail_url` size='M' float='left'}

      <a href="{url_for module='User' action='HisPage' userId=`$item.companion.user_id` _backto=true}">{$item.companion.short_name}</a><br />

      {if $item.status == 0}
        <input type="submit" name="cancel" value="ｷｬﾝｾﾙ" />
      {else}
        <span style="color:{#resultColor#}">{switch value=`$item.status` 1='承認されました' 2='拒否されました'}</span>
      {/if}
      <br clear="all" /><div style="clear:both"></div>
    </form>
    {image_tag file='hr.gif'}<br />
  {/foreach}

  {include file='include/pager.tpl' totalPages=`$list.totalPages`}

  {if $unconfirmed > 0}
    <form action="{url_for _self=true result=''}" method="post" style="text-align:center">
      <div style="text-align:center"><input type="submit" name="clear" value="回答済みをｸﾘｱ{if $carrier != 'android'}{/if}" accesskey="5" /></div>
    </form>
  {/if}

{/if}

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
