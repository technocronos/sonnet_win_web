{include file="include/header.tpl" title="ｷｬﾗ作成"}


{image_tag file='navigator.gif' float='right'}
なのだ<br />
<br />
{if $smarty.get.result == 'omake'}
  <span style="color:{#termColor#}">{$omake.item_name}</span>も流しといてやったのだ<br />
  ﾊﾞﾚないように使えなのだ<br />
  <br />
{/if}
あ､そういえばもじょの名前言ってなかったのだ…<br />
もじょは<span style="color:{#termColor#}">もじょ</span>なのだ｡この世界でﾋﾏﾂﾌﾞｼしてる精霊なのだ<br />
期待通りこれからもちょくちょく顔出してやるから喜べなのだ<br />
<br />
…で､物語は主人公の女の子の育てのじじぃがようやく起きてきたとこから始まるのだ…
{if ($carrier == 'iphone' || $carrier == 'android')}
	</br><div style="text-align:right"><a href="{url_for module='Swf' action='Tutorial'}" class="buttonlike next">次へ⇒</a></div>
{else}
	<a href="{url_for module='Swf' action='Tutorial'}">⇒</a>
{/if}

<br clear="all" /><div style="clear:both"></div>
<br />


{include file="include/footer.tpl"}
