{include file="include/header.tpl" title="日誌一覧"}


ここには運営ﾁｰﾑによるどうでもいい開発日誌を不定期に掲載します｡<br />
いや､決してｲｲﾜｹのためでは…<br />
{image_tag file='hr.gif'}<br />

{include file='include/oshiraseList.tpl' list=`$list.resultset`}
{include file='include/pager.tpl' totalPages=`$list.totalPages`}

<br />
<a href="{url_for module='User' action='Index'}">←戻る</a>


{include file="include/footer.tpl"}
