{*
    結果セットを<table>で表示するテンプレート。

    パラメータ)
        resultset     表示したい結果セット
        colCaptions   省略可能。生の列名の代わりに表示したい文字列。
                      生の列名をキー、表示したい文字列を値として指定する。
        colTypes      省略可能。各列の表示形式。
                      生の列名をキー、表示形式を値として指定する。表示形式は以下の値をとる。
                          "date"                    日付
                          array('graph' => 100)     横棒グラフと共に表示する。100は可変でグラフのスケール。
                          array('format' => '%s')   printfでフォーマット。%sは可変。
        colWidth      省略可能。各列の幅。
                      生の列名をキー、cssのwidthを値とする配列。
        rowBgColor    省略可能。各行の背景色。
                      行のインデックスをキー、cssの背景色指定を値とする配列。
*}
{php}

    $resultset = $this->get_template_vars('resultset');
    $colCaptions = $this->get_template_vars('colCaptions');
    $colTypes = $this->get_template_vars('colTypes');

    if($resultset) {

        // 結果セットから最初のレコードを取り出して、列リストを得る。
        $cols = reset($resultset);

        // 列名をキー、表示名を値とする配列を作成する。
        foreach($cols as $col => $dummy) {
            if( isset($colCaptions[$col]) )
                $cols[$col] = $colCaptions[$col];
            else
                $cols[$col] = $col;
        }
        $this->assign('cols', $cols);

        // 列名をキー、値の表示の仕方を値とする配列を作成する。
        $display = array();
        foreach($cols as $col => $dummy) {

            if( !isset($colTypes[$col]) )
                continue;

            if( is_string($colTypes[$col]) ) {
                $display[$col] = array('type'=>$colTypes[$col]);

            }else if( is_array($colTypes[$col]) ) {

                if( isset($colTypes[$col]['graph']) )
                    $display[$col] = array('type'=>'graph', 'scale'=>$colTypes[$col]['graph'], 'format'=>$colTypes[$col]['format']);
                else if( isset($colTypes[$col]['format']) )
                    $display[$col] = array('type'=>'format', 'format'=>$colTypes[$col]['format']);
            }
        }
        $this->assign('display', $display);
    }
{/php}


{if $update == 1}
<br>
  <div style="color:red">情報を更新しました。</div>
<br>
{/if}

{if count($resultset) == 0}

  該当のデータがありません。

{else}

  <table>
    <tr>
      {foreach from=`$cols` key='col' item='caption'}
        <th style="width:{$colWidth.$col|default:'auto'}">{$caption}</th>
      {/foreach}
      <th style="width:{$colWidth.$col|default:'auto'}">action</th>
    </tr>

    {foreach from=`$resultset` key='index' item='record'}
      <form method="post" action="{$smart.server.PHP_SELF}" name="form_{$record.user_id}" onSubmit="return check()">
      <tr style="background-color:{$rowBgColor.$index|default:'auto'}">
        {foreach from=`$cols` key='col' item='dummy'}
          {if $display.$col.type == 'date'}
            <td>{$record.$col|date_color:'m/d':'&nbsp;'}</td>
          {elseif $display.$col.type == 'format'}
            <td>{$record.$col|string_format:$display.$col.format|default:'&nbsp;'}</td>
          {elseif $display.$col.type == 'graph'}
            <td>{$record.$col|bar_graph:$display.$col.scale:$display.$col.format}</td>
          {else}
            {if $col == 'platform_uid'}
              <td><a href="?module=User&action=Index&opensocial_owner_id={$record.$col}&ver={$smarty.const.ANDROID_VER}" target="_blank">{$record.$col}</a></td>
            {elseif $col == 'targetimage'}
              <td><iframe src="?module=Admin&action=FindUser&img_id={$record.$col}" style="width:180px;height:200px;"></iframe></td>
            {elseif $col == 'WEP'}
              <td>{$record.$col.item_name}<br>
                  {if $record.user_id == $record.platform_uid }
                      <select name="part[]">
                        {foreach from=`$pla_weapon` item='item'}
                          <option value="{$item.item_id}" {if $item.item_id == $record.$col.item_id}selected{/if}>{$item.item_name}</option>
                        {/foreach}
                      </select>
                  {/if}
              </td>
            {elseif $col == 'BOD'}
              <td>{$record.$col.item_name}<br>
                  {if $record.user_id == $record.platform_uid }
                    <select name="part[]">
                      {foreach from=`$pla_body` item='item'}
                        <option value="{$item.item_id}" {if $item.item_id == $record.$col.item_id}selected{/if}>{$item.item_name}</option>
                      {/foreach}
                    </select>
                  {/if}
              </td>
            {elseif $col == 'HED'}
              <td>{$record.$col.item_name}<br>
                  {if $record.user_id == $record.platform_uid }
                      <select name="part[]">
                        {foreach from=`$pla_head` item='item'}
                          <option value="{$item.item_id}" {if $item.item_id == $record.$col.item_id}selected{/if}>{$item.item_name}</option>
                        {/foreach}
                      </select>
                  {/if}
              </td>
            {elseif $col == 'ACS'}
              <td>{$record.$col.item_name}<br>
                  {if $record.user_id == $record.platform_uid }
                      <select name="part[]">
                        {foreach from=`$pla_shield` item='item'}
                          <option value="{$item.item_id}" {if $item.item_id == $record.$col.item_id}selected{/if}>{$item.item_name}</option>
                        {/foreach}
                      </select>
                  {/if}
              </td>
            {elseif $col == 'level'}
              <td>
                  {if $record.user_id != $record.platform_uid }
                      {$record.$col}
                  {else}
                      <select name="level">
                        {foreach from=`$level_master` item='item'}
                          <option value="{$item.level}" {if $item.level == $record.$col}selected{/if}>{$item.level}</option>
                        {/foreach}
                      </select>
                  {/if}
              </td>
            {elseif $col == 'grade'}
              <td>
                  {if $record.user_id != $record.platform_uid }
                      {$record.$col}
                  {else}
                      <select name="grade">
                        {foreach from=`$grade_list` item='item'}
                          <option value="{$item.grade_id}" {if $item.grade_name == $record.$col}selected{/if}>{$item.grade_name}</option>
                        {/foreach}
                      </select>
                  {/if}
              </td>
            {elseif $col == 'name'}
              <td>
                  {if $record.user_id != $record.platform_uid }
                      {$record.$col}
                  {else}
                      <input type="text" name="user_name" value="{$record.$col}">
                  {/if}
              </td>
            {else}
              {if $col == 'character_id'}<input type="hidden" name="submit_chara_id" value={$record.$col}>{/if}
              {$record.$col|table_cell}
            {/if}
          {/if}
        {/foreach}
        <td>{if $record.user_id == $record.platform_uid }<input type="submit" value="更新">{else}更新不可{/if}</td>
      </tr>
      </form>
    {/foreach}
  </table>

{/if}


<script>{literal}

function check(){

	if(window.confirm('送信してよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		return false; // 送信を中止

	}

}

{/literal}
</script>
