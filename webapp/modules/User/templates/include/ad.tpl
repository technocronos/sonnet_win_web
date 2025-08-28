{*
    広告を表示するテンプレート
    パラメータ)
        place   表示する場所。以下のいずれか。
                    top   トップページ
                    ap    行動pt不足ページ
*}


{if $place == 'top'}

{elseif $place == 'ap'}

  {image_tag file='hr.gif'}<br />
  <div style="text-align:center">
    CM
    <!-- Begin GREE Ad Program, Zone: [gree-p-1567-2227-ソネット・オブ・ウィザード00-south] -->
    <div data-gree-src="gap-fp-zone:zone_id=ODE0NQ%3D%3D%0A&oe=sjis&title_color=0000FF&text_color=000000&align=center"></div>
    <!-- End GREE Ad Program -->
  </div>

{/if}


{*
{image_tag file='hr.gif'}<br />
<div style="text-align:center">

  {if time() % 3 == 0}
    待ってる間に秘宝デラックス
    <a href="http://pf.mbga.jp/12003567">{image_tag file='hihou.gif' cat='timely'}</a><br />

  {elseif time() % 2 == 0}
    天下一の剣豪を目指せ！<br />
    <a href="http://pf.mbga.jp/12001751">{image_tag file='tenka.gif' cat='timely'}</a><br />

  {else}
    待ってる間に育毛人生♪<br />
    <a href="http://pf.mbga.jp/12002543">{image_tag file='ikumou.gif' cat='timely'}</a><br />
  {/if}

</div>
*}
