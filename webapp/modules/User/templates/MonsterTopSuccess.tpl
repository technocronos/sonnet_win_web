{include file="include/header.tpl" title='ﾓﾝｽﾀｰ図鑑'}


{image_tag file='navi_mini.gif' float='left'}
今までに倒したﾓﾝｽﾀｰの一覧なのだ
<br clear="all" /><div style="clear:both"></div>

<div style="text-align:center"><span style="color:{#statusNameColor#}">ｷｬﾌﾟﾁｬ率</span><span style="color:{#statusValueColor#}">{$capture}</span>/<span style="color:{#statusValueColor#}">{$monster}</span></div>

{include file='include/pHeader.tpl' text='種族別'}

<div style="text-align:center">
  {foreach from=`$categories` key='index' item='category' name='for'}
    <a href="{url_for action='MonsterList' field='category' value=`$index`}" class="buttonlike label">{$category}</a>
    {if !$smarty.foreach.for.last}/{/if}
    {if $smarty.foreach.for.iteration % 3 == 0}<br />{/if}
  {/foreach}
</div>

{include file='include/pHeader.tpl' text='レア度別'}

<div style="text-align:center">
  {foreach from=`$rares` key='index' item='rare' name='for'}
    <a href="{url_for action='MonsterList' field='rare_level' value=`$index`}" class="buttonlike label">{$rare}</a>
    {if !$smarty.foreach.for.last}/{/if}
  {/foreach}
</div>

{include file='include/pHeader.tpl' text='登場地別'}

<div style="text-align:center">
  {foreach from=`$appearances` key='index' item='appearance' name='for'}
    <a href="{url_for action='MonsterList' field='appearance' value=`$index`}" class="buttonlike label">{$appearance}</a>
    {if !$smarty.foreach.for.last}/{/if}
    {if $smarty.foreach.for.iteration % 2 == 0}<br />{/if}
  {/foreach}
</div>

{include file='include/pHeader.tpl' text='ｲﾍﾞﾝﾄ別'}

<div style="text-align:center">
  {foreach from=`$events` key='index' item='ivent' name='for'}
    <a href="{url_for action='MonsterList' field='appearance' value=`$index`}" class="buttonlike label">{$ivent}</a>
    {if !$smarty.foreach.for.last}/{/if}
    {if $smarty.foreach.for.iteration % 2 == 0}<br />{/if}
  {/foreach}
</div>

{include file='include/pHeader.tpl' text='その他'}

<div style="text-align:center">
  <a href="{url_for action='MonsterList' field='terminate'}" class="buttonlike label">倒した一覧</a>
</div>

<br />
<a href="{url_for action='Status'}" class="buttonlike back">←ｽﾃｰﾀｽ</a><br />
<a href="{url_for action='QuestList'}" class="buttonlike back">←ｸｴｽﾄ一覧</a><br />


{include file="include/footer.tpl"}
