{include file="include/header.tpl" title='ﾁｰﾑ対戦'}


<div style="text-align:right"><a href="{url_for action='RivalList'}">ﾕｰｻﾞ対戦⇒</a></div>

{image_tag file='navi_mini.gif' float='left'}
ﾁｰﾑ対戦できるやつらの一覧なのだ
<br clear="all" /><div style="clear:both"></div>

{include file='include/characterList.tpl' list=`$rivalList` withMember=true params=`$params`}

<div style="text-align:center">{button_link _self=true did=null _nocache=true _accesskey="5"}ﾘｽﾄを更新{/button_link}</div>

<a href="{url_for module='User' action='Main'}">←戻る</a><br />


{include file="include/footer.tpl"}
