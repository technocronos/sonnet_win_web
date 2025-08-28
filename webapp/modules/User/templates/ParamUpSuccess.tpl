{include file="include/header.tpl" title='ｽﾃｰﾀｽ振り分け'}


{image_tag file='navi_mini.gif' float='left'}
{if $error}
  <span style="color:{#errorColor#}">{$error}</span>
{else}
  <span style="color:{#termColor#}">{text id=`$chara.name_id`}</span>の<span style="color:{#statusNameColor#}">ｽﾃｰﾀｽpt</span>を振り分けるのだ
{/if}
<br clear="all" /><div style="clear:both"></div>

<div style="text-align:center">
  <span style="color:{#statusNameColor#}">ｽﾃｰﾀｽpt</span><span style="color:{#statusValueColor#}">{$chara.param_seed}</span>
</div>

{image_tag file='hr.gif'}<br />

<form method="post" action="{url_for _self=true}">
  {image_tag file='picon_att1.gif'}攻撃(炎)<span style="color:{#statusValueColor#}">{$chara.attack1}</span>
  {form_select name='attack1' src=`$selectOptions`}<br />

  {image_tag file='hr.gif'}<br />

  {image_tag file='picon_att2.gif'}攻撃(水)<span style="color:{#statusValueColor#}">{$chara.attack2}</span>
  {form_select name='attack2' src=`$selectOptions`}<br />

  {image_tag file='hr.gif'}<br />

  {image_tag file='picon_att3.gif'}攻撃(雷)<span style="color:{#statusValueColor#}">{$chara.attack3}</span>
  {form_select name='attack3' src=`$selectOptions`}<br />

  {image_tag file='hr.gif'}<br />

  {image_tag file='picon_def1.gif'}防御(炎)<span style="color:{#statusValueColor#}">{$chara.defence1}</span>
  {form_select name='defence1' src=`$selectOptions`}<br />

  {image_tag file='hr.gif'}<br />

  {image_tag file='picon_def2.gif'}防御(水)<span style="color:{#statusValueColor#}">{$chara.defence2}</span>
  {form_select name='defence2' src=`$selectOptions`}<br />

  {image_tag file='hr.gif'}<br />

  {image_tag file='picon_def3.gif'}防御(雷)<span style="color:{#statusValueColor#}">{$chara.defence3}</span>
  {form_select name='defence3' src=`$selectOptions`}<br />

  {image_tag file='hr.gif'}<br />

  {image_tag file='picon_speed.gif'}ｽﾋﾟｰﾄﾞ<span style="color:{#statusValueColor#}">{$chara.speed}</span>
  {form_select name='speed' src=`$selectOptions`}<br />

  {image_tag file='hr.gif'}<br />

  {image_tag file='picon_hp.gif'}HP<span style="color:{#statusValueColor#}">{$chara.hp_max|int}</span>
  {form_select name='hp_max' src=`$selectOptions`}<br />
  HPは<span style="color:{#statusValueColor#}">1</span>振り分けで<span style="color:{#statusValueColor#}">{const name='Character_InfoService::HP_SCALE'}</span>上昇します｡

  {image_tag file='hr.gif'}<br />

  <div style="text-align:center">
    <input type="submit" value="決定" />
  </div>
</form>


<a href="{backto_url}" class="buttonlike back">←ｷｬﾝｾﾙ</a><br />


{include file="include/footer.tpl"}
