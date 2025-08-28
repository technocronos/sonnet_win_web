{include file="include/header.tpl" title='ﾊﾞﾄﾙｲﾍﾞﾝﾄ'}


<div style="text-align:right">
  <a href="{url_for action='GradeList'}" class="buttonlike next">階級表⇒</a> <br />
  <a href="{url_for action='RivalList'}" class="buttonlike next">ﾕｰｻﾞ対戦⇒</a>
</div>

{image_tag file='navi_mini.gif' float='left'}
{if $smarty.get.type == $type1}
  ﾊﾞﾄﾙｲﾍﾞﾝﾄの順位表なのだ｡今は{$term.begin|date_ex:'n/j'}～{$term.end|date_ex:'n/j'}までで開催中なのだ!ﾙｰﾙとかは<a href="{url_for action='Help' id='other-ranking' _backto=true}" class="buttonlike label">ｺｺ</a>を見るのだ<br />今週の景品は<span style="color:{#termColor#}">にわとりの時計</span>なのだ
{else}
  日ごとの順位表なのだ｡
{/if}
<br clear="all" /><div style="clear:both"></div>


{* デイリー／ウィークリーの切り替え *}
<div style="text-align:center">
    {switch_link _name='type' _value=`$type1` page='0'}ｳｨｰｸﾘｰ{/switch_link
  }/{switch_link _name='type' _value=`$type2` page='0'}ﾃﾞｲﾘｰ{/switch_link}
</div>

{* ウィークリーランキングのサイクルによってバナーを表示 *}
{if $smarty.get.type == $type1  &&  $cycle == 3}
  <div style="text-align:center">{image_tag file='ranking.gif' cat='notice'}</div>
{/if}


{if $period}

  <div style="text-align:center">{$period|strtotime|date_ex:'n/j'}の{if $smarty.get.type==$type1}ｳｨｰｸﾘｰ{else}ﾃﾞｲﾘｰ{/if}ﾗﾝｷﾝｸﾞ</div>
  {if $yourRank}<div style="text-align:center">あなたは<span style="color:{#statusValueColor#}">{$yourRank}</span>位です</div>{/if}
  {image_tag file='hr.gif'}<br />

  {include file='include/ranking.tpl' type=`$smarty.get.type` period=`$period` count='10'}

{else}
  <div style="text-align:center">まだ集計されていません</div>
{/if}

<br />
<a href="{backto_url}" class="buttonlike next">←戻る</a><br />


{include file="include/footer.tpl"}
