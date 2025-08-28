{*
    バトルの結果詳細を表示するテンプレート。
    パラメータ)
        battle    battle_log レコード。ただし、Battle_LogService::addBiasColumn() で
                  擬似列が追加されているものであること。
        current   省略可能。現在の経験値など。指定するなら、次のキーを含む配列。
                      exp
                      gold
                      grade_pt
*}
  <table>
    <tr>
      <td><span style="font-size:{$css_small}"><span style="color:{#statusNameColor#}">ﾀｰﾝ数</span></span></td>
      <td><span style="font-size:{$css_small}"><span style="color:{#statusValueColor#}">{$battle.result_detail.match_length|space_format:'%2d'}</span></span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}"><span style="color:{#statusNameColor#}">与ﾀﾞﾒｰｼﾞ</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.summary.total_hurt|space_format:'%4d'}</span>
        vs
        <span style="color:{#statusValueColor#}">{$battle.rival_result.summary.total_hurt|space_format:'%4d'}</span>
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}">┗<span style="color:{#statusNameColor#}">通常</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.summary.normal_hurt|space_format:'%4d'}</span>
        vs
        <span style="color:{#statusValueColor#}">{$battle.rival_result.summary.normal_hurt|space_format:'%4d'}</span>
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}">&nbsp;&nbsp;┗<span style="color:{#statusNameColor#}">ﾋｯﾄ</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.summary.normal_hits|space_format:'%2d'}</span>回
        vs
        <span style="color:{#statusValueColor#}">{$battle.rival_result.summary.normal_hits|space_format:'%2d'}</span>回
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}">&nbsp;&nbsp;┗<span style="color:{#statusNameColor#}">ﾕﾆｿﾞﾝ</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.summary.tact0|space_format:'%2d'}</span>回
        vs
        <span style="color:{#statusValueColor#}">{$battle.rival_result.summary.tact0|space_format:'%2d'}</span>回
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}">┗<span style="color:{#statusNameColor#}">ﾘﾍﾞﾝｼﾞ</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.summary.revenge_hurt|space_format:'%4d'}</span>
        vs
        <span style="color:{#statusValueColor#}">{$battle.rival_result.summary.revenge_hurt|space_format:'%4d'}</span>
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}">&nbsp;&nbsp;┗<span style="color:{#statusNameColor#}">発動</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.summary.revenge_count|space_format:'%2d'}</span>回
        vs
        <span style="color:{#statusValueColor#}">{$battle.rival_result.summary.revenge_count|space_format:'%2d'}</span>回
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}">&nbsp;&nbsp;┗<span style="color:{#statusNameColor#}">命中</span></span></td>
      <td><span style="font-size:{$css_small}">
        {if $battle.bias_result.summary.revenge_attacks > 0}
          <span style="color:{#statusValueColor#}">{$battle.bias_result.summary.revenge_hits/$battle.bias_result.summary.revenge_attacks*100|space_format:'%3d'}</span>%
        {else}
          <span style="color:{#statusValueColor#}">---</span>%
        {/if}
        vs
        {if $battle.rival_result.summary.revenge_attacks > 0}
          <span style="color:{#statusValueColor#}">{$battle.rival_result.summary.revenge_hits/$battle.rival_result.summary.revenge_attacks*100|space_format:'%3d'}</span>%
        {else}
          <span style="color:{#statusValueColor#}">---</span>%
        {/if}
      </span></td>
    </tr>
  </table>

  {if $battle.bias_status == 'draw' &&  $battle.tournament_id != constant('Tournament_MasterService::TOUR_QUEST')}
    <div style="text-align:center; text-decoration:blink">相討ちﾎﾞｰﾅｽ</div>
  {/if}

  <table>
    <tr>
      <td><span style="font-size:{$css_small}"><span style="color:{#statusNameColor#}">経験値</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.gain.exp|plus_minus}</span>
        {if $current}
          {if $current.exp.relative_next > 0}
            {include file='include/gauge.tpl' value=`$current.exp.relative_exp` max=`$current.exp.relative_next` type='EXP'}
          {else}
            [MAX]
          {/if}
        {/if}
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}"><span style="color:{#statusNameColor#}">{$smarty.const.GOLD_NAME}</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.gain.gold|plus_minus}</span>
        {if $current}
          ⇒<span style="color:{#statusValueColor#}">{$current.gold}</span>
        {/if}
      </span></td>
    </tr>
    <tr>
      <td><span style="font-size:{$css_small}"><span style="color:{#statusNameColor#}">階級pt</span></span></td>
      <td><span style="font-size:{$css_small}">
        <span style="color:{#statusValueColor#}">{$battle.bias_result.gain.grade_nominal|plus_minus}</span>
        {if $current}
          ⇒<span style="color:{#statusValueColor#}">{$current.grade_pt|plus_minus}</span>{if $battle.bias_result.gain.grade < $battle.bias_result.gain.grade_nominal}[MAX]{/if}
        {/if}
      </span></td>
    </tr>
  </table>
