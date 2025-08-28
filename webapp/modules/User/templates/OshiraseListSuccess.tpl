{include file="include/header.tpl" title="お知らせ一覧"}

<div pc-style="line-height:2em">
{include file='include/oshiraseList.tpl' list=`$list.resultset`}
</div>
{include file='include/pager.tpl' totalPages=`$list.totalPages`}

<br />
<a href="{url_for module='User' action='Index'}" class="buttonlike back">←戻る</a>


{include file="include/footer.tpl"}
