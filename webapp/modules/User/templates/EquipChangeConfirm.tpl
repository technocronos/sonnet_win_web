{include file="include/header.tpl" title="合成する"}

<span style="color:{#statusNameColor#}">{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">{$userInfo.gold}</span><br />

  {* アイテム画像 *}
  <div style="text-align:center">
    {item_image id=`$uitem.item_id`}<br />
  </div>
  <br />
  <div style="text-align:center">
	<span style="color:{#statusNameColor#}">必要{$smarty.const.GOLD_NAME}</span><span style="color:{#statusValueColor#}">{$needgold}</span><br /><br />
  </div>

  {* 合成できるなら表示 *}
  {if !$error}

    {* 確認メッセージ *}
    {image_tag file='navi_mini.gif' float='left'}
    {if $smarty.get.setumei}
      合成ってのは現在の装備にいらなくなったものを吸収しちゃう操作なのだ。<br />
	  同じレア度の装備なら装備ﾚﾍﾞﾙが1上がるくらいの経験値が入るのだ。レア度が下がる装備だと装備に応じて経験値が入るのだ。<br />
	  合成にはマグナがいるのだ。ただし全く同じ装備を使って合成するときは得られる経験値が2倍になるのだ！<br />
    {else}
      <span style="color:{#termColor#}">{$uitem.item_name}</span>を今の装備に合成するのだ？<br /><a href="{url_for _self=true setumei=1}" class="buttonlike next">合成って？</a><br />
    {/if}
    <br clear="all" /><div style="clear:both"></div>

    {* 確定ボタン *}
    <div style="text-align:center">
      <form action="{url_for _self=true}" method="post"><input type="submit" name="go" value="合成する" id="submit_button"  /></form>
    </div>

  {* 捨てられないならそれを表示 *}
  {else}

    {image_tag file='navi_mini.gif' float='left'}
    {switch value=`$error` equipping='装備中のものは合成できないのだ' maxlevel='もうこれ以上合成してもﾚﾍﾞﾙあがらないのだ' noitem='アイテムがないのだ。なんの間違いなのだ？' nomoney='マグナが足りてないのだ'}
    <br clear="all" /><div style="clear:both"></div>

  {/if}


<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a>


{include file="include/footer.tpl"}
