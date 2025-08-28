{include file="include/header.tpl" title="階級表"}


<div style="text-align:right"><a href="{url_for action='Ranking' _backto=true}" class="buttonlike next">ﾊﾞﾄﾙｲﾍﾞﾝﾄ⇒</a></div>

{image_tag file='navi_mini.gif' float='left'}
見たい階級を選択するのだ｡階級上がると必殺技を出せるようになるのだ｡<a href="{url_for action='Shop' cat='ITM' currency='coin' buy='1909'}" class="buttonlike next">奥義の書</a>があると発動確率がグッとするのだ
<br clear="all" /><div style="clear:both"></div>
<br />

<span style="color:{#noticeColor#}">人数は{const name='Grade_MasterService::DISTRIBUTION_CACHE_HOURS'}時間ごとに更新</span><br />
{foreach from=`$list` item="item"}

  <a href="{url_for module='User' action='GradeDetail' gradeId=`$item.grade_id`}" class="buttonlike label">{$item.grade_name}</a>
  ({$distribute[$item.grade_id]|int}人)
  <br />

  {if $item.grade_id == $chara.grade_id}
    ┗{text id=`$chara.name_id`}<br />
  {/if}
{/foreach}

<br />
<a href="{url_for module='Swf' action='Main'}" class="buttonlike back">←ｿﾈｯﾄﾒﾆｭｰ</a><br />


{include file="include/footer.tpl"}
