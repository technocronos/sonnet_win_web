{include file="include/header.tpl" title="`$target.short_name`の仲間"}


{* 自分の一覧の場合 *}
{if $userId == $target.user_id}
  <div style="text-align:right">
    <a href="{url_for action='MemberSearch' _backto=true}" class="buttonlike next">仲間を探す⇒</a><br />
    <a href="{url_for action='ApproachList' _backto=true}" class="buttonlike next">申請ﾘｽﾄ⇒</a><br />
    <a href="{url_for action='Help' id='other-shoutai' _backto=true}" class="buttonlike next">友だちを招待⇒</a><br />
  </div>
{/if}

総勢{$list.totalRows}人<br />

{if $list.totalRows > 0}
  {include file='include/userList.tpl' list=`$list.resultset` with='chara'}
  {include file='include/pager.tpl' totalPages=`$list.totalPages`}
{else}
  <br />
  {image_tag file='navi_mini.gif' float='left'}
  誰もいないのだ…<br />
  {if $userId == $target.user_id}{* 自分の場合 *}
    泣く前に仲間を探すのだ
  {else}{* 他人の場合 *}
    背中が寂しさを訴えているのだ…
  {/if}
  <br clear="all" /><div style="clear:both"></div>
{/if}

<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
