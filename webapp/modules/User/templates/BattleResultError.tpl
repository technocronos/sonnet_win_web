{include file="include/header.tpl" title=""}

<br />
{image_tag file='navi_mini.gif' float='left'}

{* 指定されたバトルデータがなかった *}
{if $status == -1}
  ﾊﾞﾄﾙ情報もうなくなってるのだ｡<br />
  残念なのだ

{* まだ決着していない *}
{else}
  {switch value=`$status`
    0='まだ始まってないのだ…'
    1='まだ対戦中ってことになってるのだ…'
    2='時間かかりすぎで中断になってるのだ…'
  }
{/if}

<br clear="all" /><div style="clear:both"></div>


<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
