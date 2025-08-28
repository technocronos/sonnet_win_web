{include file="include/header.tpl" title='ヘルプ'}


{image_tag file='navi_mini.gif' float='left'}
ﾅﾆが知りたいのだ?ｼﾝｾﾂに教えてやるのだ<br />
ﾚﾍﾞﾙ上がったらもっとｲﾛｲﾛ教えてやるのだ
<br clear="all" /><div style="clear:both"></div>
<br />

{foreach from=`$helpTree` key='groupId' item='helps'}

  {$groups[$groupId]}<br />

  {foreach from=`$helps` item='help'}
    {if !($smarty.const.PLATFORM_TYPE=='waku' && $help.help_id=='other-link')}
      &nbsp;&nbsp;┗<a href="{url_for action='Help' id=`$help.help_id`}" class="buttonlike label">{$help.help_title}</a>{if $help.unlock_level == $avatar.level}<span style="text-decoration:blink"></span>{/if}<br />
    {/if}
  {/foreach}
{/foreach}

<br />
<a href="{url_for action='Main'}" class="buttonlike back">←ｿﾈｯﾄﾒﾆｭｰへ</a><br />


{include file="include/footer.tpl"}
