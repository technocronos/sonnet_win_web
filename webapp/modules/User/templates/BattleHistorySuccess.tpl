{include file="include/header.tpl" title="`$charaName`の戦歴"}


<div style="text-align:center">
  {switch_link _name='side' _value='defend' page='0'}しかけられた{/switch_link
  }/{
  switch_link _name='side' _value='challenge' page='0'}しかけた{/switch_link}
</div>

<div style="text-align:center">
  <span style="color:{#statusValueColor#}">{$fights}</span>戦<span style="color:{#statusValueColor#}">{$win}</span>勝<span style="color:{#statusValueColor#}">{$lose}</span>敗<span style="color:{#statusValueColor#}">{$draw}</span>分
</div>
<br />

{if $list.totalRows > 0}

  {* リスト表示 *}
  <div style="text-align:right; color:{#noticeColor#}">{$charaName}は左側</div>
  {image_tag file='hr.gif'}<br />
  {foreach from=`$list.resultset` item="row"}

    {* 相手ユーザのプラットフォームアバター *}
    {platform_thumbnail src=`$row.thumbnail_url` size='M' float='left'}

    {* 日時、結果、相手ユーザ名、相手キャラ名＆階級 *}
    {$row.create_at|date_ex:'n/j H:i'} <span style="color:{#termColor#}">{switch value=`$row.bias_status` win='勝ち' lose='負け' draw='相討ち' timeup='時間切れ'}</span><br />
    <a href="{url_for module='User' action='HisPage' userId=`$row.rival_user_id` _backto=true}" class="buttonlike label">{$row.rival_user_name}</a><br />
    {$row.rival_character_name} {text grade=`$row.rival_ready.grade_id`}
    <br clear="all" /><div style="clear:both"></div>

    {* バトル詳細 *}
    {include file='include/battleSummary.tpl' battle=`$row`}

    {* コメント *}
    {if $row.comment}
      {image_tag file='comment.gif' float='left'}
      {$row.comment|nl2br}
      <br clear="all" /><div style="clear:both"></div>
    {/if}

    {image_tag file='hr.gif'}<br />
  {/foreach}

  {* ページャ表示 *}
  {include file="include/pager.tpl" totalPages=`$list.totalPages`}

{else}
  <div style="text-align:center">まだありません。</div>
  <br />
{/if}

<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
