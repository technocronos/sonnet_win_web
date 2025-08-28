{*
    結果セットを集計して、数値表を表示するテンプレート。

    パラメータ)
        data          表の元になる結果セット
        rowColumn     縦軸になる値を保持する列名
        colColumn     横軸になる値を保持する列名
        cellColumn    各セルの値を保持する列名
        rows          縦軸の順番を、値をキー、表示文字列を値とする配列で。省略可能。
        rowCaptions   rowsを省略した場合に利用される。
                      一部の行タイトルを値でなくカスタムした文字列にしたい場合に、
                      その値をキー、行タイトルを値とする配列で指定する。
        rowsOrder     rowsを省略した場合に利用される。縦軸の並び順。"ASC" か "DESC" のどちらか。
        cols          横軸の順番を、値をキー、表示文字列を値とする配列で。省略可能。
        colCaptions   colsを省略した場合に利用される。
                      一部の行タイトルを値でなくカスタムした文字列にしたい場合に、
                      その値をキー、行タイトルを値とする配列で指定する。
        leftTop       一番左上のセルに表示する文字列。省略可能。
        scale         棒グラフのスケール。
*}
{php}

    // 結果セットから表形式の2次元配列を作成。
    if($this->get_template_vars('data')) {

        // 結果セットを表に変換。
        $table = ResultsetUtil::makeTable(
            $this->get_template_vars('data'),
            $this->get_template_vars('rowColumn'),
            $this->get_template_vars('colColumn'),
            $this->get_template_vars('cellColumn')
        );

        // rowsOrderが指定されているなら処理する。
        if($this->get_template_vars('rowsOrder')) {
            $sortFunc = ($this->get_template_vars('rowsOrder') == 'DESC') ? 'krsort' : 'ksort';
            $sortFunc($table);
        }

        // rowsが省略されている場合は生成した表から取得。
        if( !$this->get_template_vars('rows') ) {

            $rowValues = array_keys($table);
            $captions = $this->get_template_vars('rowCaptions');

            $rows = array();
            foreach($rowValues as $row)
                $rows[$row] = isset($captions[$row]) ? $captions[$row] : $row;

            $this->assign('rows', $rows);
        }

        // colsが省略されている場合は生成した表の最初の行から取得。
        if( !$this->get_template_vars('cols') ) {

            $colValues = array_keys( reset($table) );
            $captions = $this->get_template_vars('colCaptions');

            $cols = array();
            foreach($colValues as $col)
                $cols[$col] = isset($captions[$col]) ? $captions[$col] : $col;

            $this->assign('cols', $cols);
        }

        $this->assign('table', $table);

    }
{/php}


{if !$table}

  該当のデータがありません

{else}

  <table>
    <tr>
      <th>{$leftTop}</th>
      {foreach from=`$cols` item='item'}
        <th>{$item}</th>
      {/foreach}
    </tr>

    {foreach from=`$rows` key='rowValue' item='rowCaption'}
      <tr>
        {$rowCaption|table_cell}
        {foreach from=`$cols` key='colValue' item='dummy'}
          <td>{$table.$rowValue.$colValue|bar_graph:$scale}</td>
        {/foreach}
      </tr>
    {/foreach}
  </table>
{/if}
