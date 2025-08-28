{*
    指定された二人のキャラを比較したデータを表示するテンプレート。
    Character_InfoService::getExRecord で取得した両者のレコードをパラメータ chara1, chara2 で
    指定する。
*}
{php}

    // スピードバランスのインジケータを準備する。
    $speedBalance = BattleCommon::getSpeedBalance(
        $this->get_template_vars('chara1'), $this->get_template_vars('chara2')
    );
    $indicator = str_repeat('', round(abs($speedBalance) * 4));

    $this->assign('speedIndicator1', ($speedBalance > 0) ? $indicator : '');
    $this->assign('speedIndicator2', ($speedBalance < 0) ? $indicator : '');

{/php}

  <table style="width:100%">
    <tr>
      <td style="width:40%; text-align:right"><span style="font-size:{$css_small}">{text id=`$chara1.name_id`}</span></td>
      <td rowspan="2"></td>
      <td style="width:40%"><span style="font-size:{$css_small}">{text id=`$chara2.name_id`}</span></td>
    </tr>
    <tr>
      <td style="text-align:right"><span style="font-size:{$css_small}">{text grade=`$chara1.grade_id`}</span></td>
      <td><span style="font-size:{$css_small}">{text grade=`$chara2.grade_id`}</span></td>
    </tr>
    <tr>
      <td style="text-align:right"><span style="font-size:{$css_small}">{$chara1.level}</span></td>
      <td style="text-align:center"><span style="font-size:{$css_small}; color:{#statusNameColor#}">Lv</span></td>
      <td><span style="font-size:{$css_small}">{$chara2.level}</span></td>
    </tr>
    <tr>
      <td style="text-align:right"><span style="font-size:{$css_small}">
        {$chara1.hp|int}<br />
        {include file='include/gauge.tpl' value=`$chara1.hp` max=`$chara1.hp_max` type='HP' float='right'}
      </span></td>
      <td style="text-align:center">{image_tag file='picon_hp.gif'}</td>
      <td><span style="font-size:{$css_small}">
        {$chara2.hp|int}<br />
        {include file='include/gauge.tpl' value=`$chara2.hp` max=`$chara2.hp_max` type='HP'}
      </span></td>
    </tr>
    <tr>
      <td style="text-align:right"><span style="font-size:{$css_small}">{$chara1.total_attack1-$chara2.total_defence1}</span></td>
      <td style="text-align:center"><span style="font-size:{$css_small}">{image_tag file='picon_att1.gif'}-{image_tag file='picon_def1.gif'}</span></td>
      <td><span style="font-size:{$css_small}">{$chara2.total_attack1-$chara2.total_defence1}</span></td>
    </tr>
    <tr>
      <td style="text-align:right"><span style="font-size:{$css_small}">{$chara1.total_attack2-$chara2.total_defence2}</span></td>
      <td style="text-align:center"><span style="font-size:{$css_small}">{image_tag file='picon_att2.gif'}-{image_tag file='picon_def2.gif'}</span></td>
      <td><span style="font-size:{$css_small}">{$chara2.total_attack2-$chara2.total_defence2}</span></td>
    </tr>
    <tr>
      <td style="text-align:right"><span style="font-size:{$css_small}">{$chara1.total_attack3-$chara2.total_defence3}</span></td>
      <td style="text-align:center"><span style="font-size:{$css_small}">{image_tag file='picon_att3.gif'}-{image_tag file='picon_def3.gif'}</span></td>
      <td><span style="font-size:{$css_small}">{$chara2.total_attack3-$chara2.total_defence3}</span></td>
    </tr>
    <tr>
      <td style="text-align:right"><span style="font-size:{$css_small}"><span style="text-decoration:blink">{$speedIndicator1}</span>{$chara1.total_speed}</span></td>
      <td style="text-align:center"><span style="font-size:{$css_small}">{image_tag file='picon_speed.gif'}</span></td>
      <td><span style="font-size:{$css_small}">{$chara2.total_speed}<span style="text-decoration:blink">{$speedIndicator2}</span></span></td>
    </tr>
  </table>
