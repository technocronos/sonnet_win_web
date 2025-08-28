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


{if count($resultset) == 0}

  該当のデータがありません。

{else}

  <table>
    <tr>
      {foreach from=`$cols` key='col' item='caption'}
        <th style="width:{$colWidth.$col|default:'auto'}">{$caption}</th>
      {/foreach}
    </tr>

    {foreach from=`$resultset` key='index' item='record'}
      <tr style="background-color:{$rowBgColor.$index|default:'auto'}">
        {foreach from=`$cols` key='col' item='dummy'}
          {if $display.$col.type == 'date'}
            <td>{$record.$col|date_color:'y/m/d':'&nbsp;'}</td>
          {elseif $display.$col.type == 'format'}
            <td>{$record.$col|string_format:$display.$col.format|default:'&nbsp;'}</td>
          {elseif $display.$col.type == 'graph'}
            <td>{$record.$col|bar_graph:$display.$col.scale:$display.$col.format}</td>
          {else}
            {$record.$col|table_cell}
          {/if}
        {/foreach}
      </tr>
    {/foreach}
  </table>

{/if}
