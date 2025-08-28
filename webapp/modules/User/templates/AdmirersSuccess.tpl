{include file="include/header.tpl" title="`$smarty.const.ADMIRATION_NAME`ﾘｽﾄ'}


{image_tag file='navi_mini.gif' float='left'}
No{$smarty.get.id}に{$smarty.const.ADMIRATION_NAME}したやつらなのだ
<br clear="all" /><div style="clear:both"></div>

{foreach from=`$list.resultset` item='admire'}
  {platform_thumbnail src=`$admire.thumbnail_url` size='M' float='left'}
  <a href="{url_for module='User' action='HisPage' userId=`$admire.admirer_id`}">{$admire.short_user_name}</a><br />
  {$admire.create_at|date_ex:'m/d H:i'}
  <br clear="all" /><div style="clear:both"></div>
  {image_tag file='hr.gif'}<br />
{/foreach}

{include file='include/pager.tpl' totalPages=`$list.totalPages`}

<br />
<a href="{backto_url}">←戻る</a><br />


{include file="include/footer.tpl"}
