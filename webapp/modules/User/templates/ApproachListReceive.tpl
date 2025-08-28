{include file="include/header.tpl" title="仲間申請･受信"}


<div style="text-align:center">
    {switch_link _name='side' _value='receive' page='0'}受信勧誘{/switch_link
  }/{switch_link _name='side' _value='send'    page='0'}送信勧誘{/switch_link}
</div>
<br />

{* 結果メッセージ or ナビゲーションメッセージ *}
{image_tag file='navi_mini.gif' float='left'}
{if $smarty.get.result}
  <span style="color:{#resultColor#}">{switch value=`$smarty.get.result`
    accept='よく尽くすように言っておいたのだ'
    reject='身の程を知れと言ってきたのだ'
  }</span><br />
  <a href="{url_for action='HisPage' userId=`$smarty.get.companion_id`}" class="buttonlike next">相手のﾍﾟｰｼﾞへ</a>
{elseif $list.totalRows == 0}
  誰からも申請されてないのだ…もっと目立てなのだ
{else}
  仲間になろうと言ってきたやつらなのだ｡下僕にするも足蹴にするも好きにするのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

{* 一覧 *}
{if $list.totalRows > 0}

  {image_tag file='hr.gif'}<br />

  {foreach from=`$list.resultset` item='item'}
    <form action="{url_for _self=true result=''}" method="post">
      <input type="hidden" name="approach_id" value="{$item.approach_id}" />

      {platform_thumbnail src=`$item.thumbnail_url` size='M' float='left'}

      <a href="{url_for module='User' action='HisPage' userId=`$item.companion.user_id` _backto=true}" class="buttonlike next">{$item.companion.short_name}</a><br />

      <input type="submit" name="accept" value="承認" />
      <input type="submit" name="reject" value="拒否" />
      <br clear="all" /><div style="clear:both"></div>
    </form>
  {image_tag file='hr.gif'}<br />
  {/foreach}

  {include file='include/pager.tpl' totalPages=`$list.totalPages`}

{/if}

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
