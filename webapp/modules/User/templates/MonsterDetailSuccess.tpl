{include file="include/header.tpl"}


<div style="text-align:center">
  {* <object data="{url_for module='Swf' action='Monster' id=`$monster.character_id`}" type="application/x-shockwave-flash" width="120" height="175"></object> *}
  {item_image id=`$monster.character_id` cat='dictionary'}
</div>

<span style="color:{#statusNameColor#}">種族</span><span style="color:{#statusValueColor#}">{$monster.category_text}</span>
<span style="color:{#statusNameColor#}">希少</span><span style="color:{#statusValueColor#}">{$monster.rare_level_text}</span><span style="color:#FFFF00">{$monster.rare_level_indicator}</span>
<br />
<span style="color:{#statusNameColor#}">生息地</span><span style="color:{#statusValueColor#}">{$monster.habitat}</span><br />

{include file='include/characterStatus.tpl' chara=`$monster`}

{if $dtech}
  <span style="color:{#statusNameColor#}">必殺技</span><span style="color:{#statusValueColor#}">{$dtech.dtech_name}</span><br />
{/if}


<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />
<a href="{url_for action='MonsterTop'}" class="buttonlike back">←ﾓﾝｽﾀｰ図鑑</a><br />


{include file="include/footer.tpl"}
