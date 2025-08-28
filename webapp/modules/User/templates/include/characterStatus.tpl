{*
    キャラクターのステータスを表示するテンプレート。
    パラメータ)
        chara   Character_InfoService::getExRecord で拡張情報と共に取得した
                キャラクター情報。
        equip   装備による追加分を表示するなら true を指定する。
*}
<table align="center">
  <tr><td colspan="4"><div style="text-align:center; font-size:{$css_small}">{image_tag file='picon_hp.gif'}<span style="color:{#statusValueColor#}">{$chara.hp|int}</span>/<span style="color:{#statusValueColor#}">{$chara.hp_max|int}</span>{include file='include/gauge.tpl' value=`$chara.hp` max=`$chara.hp_max` type='HP'}</div></td></tr>
  <tr>
    <td><span style="font-size:{$css_small}">{image_tag file='picon_att1.gif'}<span style="color:{#statusValueColor#}">{$chara.total_attack1}</span>&nbsp;{if $equip}<br />({$chara.equip_attack1|plus_minus}){/if}</span></td>
    <td><span style="font-size:{$css_small}">{image_tag file='picon_att2.gif'}<span style="color:{#statusValueColor#}">{$chara.total_attack2}</span>&nbsp;{if $equip}<br />({$chara.equip_attack2|plus_minus}){/if}</span></td>
    <td><span style="font-size:{$css_small}">{image_tag file='picon_att3.gif'}<span style="color:{#statusValueColor#}">{$chara.total_attack3}</span>&nbsp;{if $equip}<br />({$chara.equip_attack3|plus_minus}){/if}</span></td>
    <td><span style="font-size:{$css_small}">{image_tag file='picon_speed.gif'}<span style="color:{#statusValueColor#}">{$chara.total_speed}</span>&nbsp;{if $equip}<br />({$chara.equip_speed|plus_minus}){/if}</span></td>
  </tr>
  <tr>
    <td><span style="font-size:{$css_small}">{image_tag file='picon_def1.gif'}<span style="color:{#statusValueColor#}">{$chara.total_defence1}</span>&nbsp;{if $equip}<br />({$chara.equip_defence1|plus_minus}){/if}</span></td>
    <td><span style="font-size:{$css_small}">{image_tag file='picon_def2.gif'}<span style="color:{#statusValueColor#}">{$chara.total_defence2}</span>&nbsp;{if $equip}<br />({$chara.equip_defence2|plus_minus}){/if}</span></td>
    <td><span style="font-size:{$css_small}">{image_tag file='picon_def3.gif'}<span style="color:{#statusValueColor#}">{$chara.total_defence3}</span>&nbsp;{if $equip}<br />({$chara.equip_defence3|plus_minus}){/if}</span></td>
    <td>{if $chara.total_defenceX}<span style="font-size:{$css_small}">{image_tag file='picon_defX.gif'}<span style="color:{#statusValueColor#}">{$chara.total_defenceX}</span>&nbsp;{if $equip}<br />({$chara.equip_defenceX|plus_minus}){/if}</span>{/if}</td>
  </tr>
</table>
