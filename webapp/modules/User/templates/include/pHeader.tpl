{*
    ページ内の段落ヘッダを表示する
    パラメータ)
        text    ヘッダテキスト
*}
<div style="text-align:center; background-color:{#pHeaderBgColor#}; color:{#pHeaderTextColor#}">
  {if $carrier == 'au'}
    {image_tag file='komon.gif'}&nbsp;&nbsp;
    {$text}&nbsp;&nbsp;
    {image_tag file='komon.gif'}
  {else}
    {image_tag file='komon.gif' float='left'}
    {image_tag file='komon.gif' float='right'}
    {$text}<br style="clear:both" />
  {/if}
</div>
