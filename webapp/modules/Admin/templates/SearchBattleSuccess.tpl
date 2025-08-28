{include file='include/header.tpl'}


<h2>ユーザーバトル検索</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="SearchBattle" />
  <input type="hidden" name="go" value="1" />

  <table style="width:30em">
    <tr>
      <th>検索キャラID</th>
      <td nowrap>
        {$validator->outputError('characterId')}
        <input type="text" name="characterId" style="width:12ex" value="{$validator->input('characterId')}" />
      </td>
    </tr>
    <tr>
      <th>対戦者キャラID</th>
      <td nowrap>
        {$validator->outputError('rivalCharacterId')}
        <input type="text" name="rivalCharacterId" style="width:12ex" value="{$validator->input('rivalCharacterId')}" />
      </td>
    </tr>
    <tr>
      <th>対戦日時</th>
      <td nowrap>
        {$validator->outputError('create_at_from', '開始')}
        {$validator->outputError('create_at_to', '終了')}
        <input type="text" name="create_at_from" style="width:12ex" value="{$validator->input('create_at_from')}" />
        ～
        <input type="text" name="create_at_to" style="width:12ex" value="{$validator->input('create_at_to')}" />
      </td>
    </tr>
    <tr>
      <td nowrap colspan="2" style="text-align:center"><input type="submit" value="検索"></td>
    </tr>
  </table>
</form>


{if $smarty.get.go}
  <hr />
  {if count($table.resultset) == 0}

    該当のデータがありません。

  {else}
    <table>
      <tr>
        <td nowrap colspan="16" style="border-style:none">{$table.totalRows}件</td>
      </tr>
      <tr>
        <th nowrap>バトルID</th>
        <th nowrap>挑戦者ID</th>
        <th nowrap>挑戦者名</th>
        <th nowrap>挑戦者階級</th>
        <th nowrap>挑戦者LV</th>
        <th nowrap>挑戦者パラメータ</th>
        <th nowrap>挑戦者装備</th>
        <th nowrap>防衛者ID</th>
        <th nowrap>防衛者名</th>
        <th nowrap>防衛者階級</th>
        <th nowrap>防衛者LV</th>
        <th nowrap>防衛者パラメータ</th>
        <th nowrap>防衛者装備</th>
        <th nowrap>ステータス</th>
        <th nowrap>ターン数</th>
        <th nowrap>挑戦者サマリー</th>
        <th nowrap>防衛者サマリー</th>
        <th nowrap>開始日時</th>
        <th nowrap>終了日時</th>
        <th nowrap>対戦時間</th>
      </tr>
      {foreach from=`$table.resultset` key="index" item="record"}
      <tr>
        <td nowrap>{$record.battle_id}</td>
        <td nowrap>{$record.challenger_id}</td>
        <td nowrap {if $record.status == 11}style="color: red;"{elseif $record.status == 12}style="color: blue;"{/if}>{$record.challenger_name}({if $record.status == 11}勝ち{elseif $record.status == 12}負け{elseif $record.status == 14}時間切れ{/if})</td>
        <td nowrap>{$record.ready_detail.challenger.grade_name}</td>
        <td nowrap>{$record.ready_detail.challenger.level}</td>
        <td nowrap>
          <table>
            <tr>
              <th>
                火（攻）
              </th>
              <th>
                水（攻）
              </th>
              <th>
                雷（攻）
              </th>
              <th>
                速さ
              </th>
            </tr>
            <tr>
              <td nowrap>
                {$record.ready_detail.challenger.total_attack1}
              </td>
              <td nowrap>
                {$record.ready_detail.challenger.total_attack2}
              </td>
              <td nowrap>
                {$record.ready_detail.challenger.total_attack3}
              </td>
              <td nowrap>
                {$record.ready_detail.challenger.total_speed}
              </td>
            </tr>
          </tr>
            <tr>
              <th>
                火（防）
              </th>
              <th>
                水（防）
              </th>
              <th>
                雷（防）
              </th>
            </tr>
            <tr>
              <td nowrap>
                {$record.ready_detail.challenger.total_defence1}
              </td>
              <td nowrap>
                {$record.ready_detail.challenger.total_defence2}
              </td>
              <td nowrap>
                {$record.ready_detail.challenger.total_defence3}
              </td>
            </tr>
          </tr>
          </table>
        </td>
        <td nowrap>
          {foreach from=`$record.ready_detail.challenger.equip` key="index" item="equip"}
            {$equip.category} {$equip.item_name}<br>
          {/foreach}
        </td>
        <td nowrap>{$record.defender_id}</td>
        <td nowrap {if $record.status == 12}style="color: red;"{elseif $record.status == 11}style="color: blue;"{/if}>{$record.defender_name}({if $record.status == 12}勝ち{elseif $record.status == 11}負け{elseif $record.status == 14}時間切れ{/if})</td>
        <td nowrap>{$record.ready_detail.defender.grade_name}</td>
        <td nowrap>{$record.result_detail.defender.character.level}</td>
        <td nowrap>
          <table>
            <tr>
              <th>
                火（攻）
              </th>
              <th>
                水（攻）
              </th>
              <th>
                雷（攻）
              </th>
              <th>
                速さ
              </th>
            </tr>
            <tr>
              <td nowrap>
                {$record.ready_detail.defender.total_attack1}
              </td>
              <td nowrap>
                {$record.ready_detail.defender.total_attack2}
              </td>
              <td nowrap>
                {$record.ready_detail.defender.total_attack3}
              </td>
              <td nowrap>
                {$record.ready_detail.defender.total_speed}
              </td>
            </tr>
          </tr>
            <tr>
              <th>
                火（防）
              </th>
              <th>
                水（防）
              </th>
              <th>
                雷（防）
              </th>
            </tr>
            <tr>
              <td nowrap>
                {$record.ready_detail.defender.total_defence1}
              </td>
              <td nowrap>
                {$record.ready_detail.defender.total_defence2}
              </td>
              <td nowrap>
                {$record.ready_detail.defender.total_defence3}
              </td>
            </tr>
          </tr>
          </table>
        </td>
        <td nowrap>
          {foreach from=`$record.ready_detail.defender.equip` key="index" item="equip"}
            {$equip.category} {$equip.item_name}<br>
          {/foreach}
        </td>

        <td nowrap>
            {if $record.status == 0}
              作成
            {elseif $record.status == 1}
              対戦中
            {elseif $record.status == 2}
              無効
            {elseif $record.status == 3}
              コンティニュー
            {elseif $record.status == 11}
              挑戦者勝利
            {elseif $record.status == 12}
              防衛者勝利
            {elseif $record.status == 13}
              ドロー
            {elseif $record.status == 14}
              時間切れ
            {/if}
        </td>
        <td nowrap>{$record.result_detail.match_length}回</td>
        <td nowrap>
            <table>
              <tr>
                <th style="">開始時HP</th><td nowrap style="width: 100%;">{$record.ready_detail.challenger.hp|intval}/{$record.ready_detail.challenger.hp_max|intval}</td>
              </tr>
              <tr>
                <th style="">終了時HP</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.hp_on_end|intval}</td>
              </tr>
              <tr>
                <th>与ダメージ</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.total_hurt|intval}</td>
              </tr>
              <tr>
                <th>通常ダメージ  </th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.normal_hurt|intval}</td>
              </tr>
              <tr>
                <th>通常ヒット</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.normal_hits|intval}/{$record.result_detail.challenger.summary.normal_attacks|intval}回</td>
              </tr>
              <tr>
                <th>ユニゾン</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.tact0|intval}回</td>
              </tr>
              <tr>
                <th>リベンジダメージ</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.revenge_hurt|intval}</td>
              </tr>
              <tr>
                <th>リベンジ回数</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.revenge_count|intval}回</td>
              </tr>
              <tr>
                <th>リベンジヒット</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.revenge_hits|intval}/{$record.result_detail.challenger.summary.revenge_attacks|intval}回</td>
              </tr>
              <tr>
                <th>強攻</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.tact1|intval}回</td>
              </tr>
              <tr>
                <th>慎重</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.tact2|intval}回</td>
              </tr>
              <tr>
                <th>吸収</th><td nowrap style="width: 100%;">{$record.result_detail.challenger.summary.tact3|intval}回</td>
              </tr>
            </table>
        </td>
        <td nowrap>
            <table>
              <tr>
                <th style="">開始時HP</th><td nowrap style="width: 100%;">{$record.ready_detail.defender.hp|intval}/{$record.ready_detail.defender.hp_max|intval}</td>
              </tr>
              <tr>
                <th style="">終了時HP</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.hp_on_end|intval}</td>
              </tr>
              <tr>
                <th>与ダメージ</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.total_hurt|intval}</td>
              </tr>
              <tr>
                <th>通常ダメージ  </th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.normal_hurt|intval}</td>
              </tr>
              <tr>
                <th>通常ヒット</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.normal_hits|intval}/{$record.result_detail.defender.summary.normal_attacks|intval}回</td>
              </tr>
              <tr>
                <th>ユニゾン</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.tact0|intval}回</td>
              </tr>
              <tr>
                <th>リベンジダメージ</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.revenge_hurt|intval}</td>
              </tr>
              <tr>
                <th>リベンジ回数</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.revenge_count|intval}回</td>
              </tr>
              <tr>
                <th>リベンジヒット</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.revenge_hits|intval}/{$record.result_detail.defender.summary.revenge_attacks|intval}回</td>
              </tr>
              <tr>
                <th>強攻</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.tact1|intval}回</td>
              </tr>
              <tr>
                <th>慎重</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.tact2|intval}回</td>
              </tr>
              <tr>
                <th>吸収</th><td nowrap style="width: 100%;">{$record.result_detail.defender.summary.tact3|intval}回</td>
              </tr>
            </table>
        </td>
        <td nowrap>{$record.create_at}</td>
        <td nowrap>{$record.result_at}</td>
        <td nowrap>{$record.pasttime}</td>
      </tr>
      {/foreach}
      <tr>
        <td nowrap colspan="16" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$table.totalPages`}</td>
      </tr>
    </table>

  {/if}


{/if}


{include file='include/footer.tpl'}
