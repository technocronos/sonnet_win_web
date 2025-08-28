{include file="include/header.tpl" title=`$grade.grade_name`}


{image_tag file='navi_mini.gif' float='left'}
<span style="color:{#termColor#}">{$grade.grade_name}</span>なやつらなのだ
<br clear="all" /><div style="clear:both"></div>
<br />

(ﾚﾍﾞﾙ順)<br />
{include file='include/characterList.tpl' list=`$list.resultset`}
{include file='include/pager.tpl' totalPages=`$list.totalPages`}

{if $smarty.get.backto}<a href="{backto_url}" class="buttonlike back">←戻る</a><br />{/if}
<a href="{url_for module='User' action='GradeList'}" class="buttonlike back">←階級一覧</a><br />


{include file="include/footer.tpl"}
