{include file='include/header.tpl'}


<h2>ページ別アクセス数</h2>

<p>
  <form action="{$smart.server.PHP_SELF}">
    <input type="hidden" name="module" value="Admin" />
    <input type="hidden" name="action" value="PageStatis" />

    <table>
      <tr>
        <th>期間</th>
        <td>
          {$validator->outputError('from', '開始')}
          {$validator->outputError('to', '終了')}
          {$validator->outputError('interval')}
          <input type="text" name="from" style="width:12ex" value="{$validator->input('from')}" />
          ～
          <input type="text" name="to" style="width:12ex" value="{$validator->input('to')}" />
          <input type="submit" value="表示">
        </td>
      </tr>
    </table>
  </form>
</p>

{if $data}
  <p>
    {include file='include/cube_table.tpl'
      data=       `$data`
      rowColumn=  'page'
      colColumn=  'date'
      cellColumn= 'point'
      cols=       `$cols`
      rowsOrder=  'ASC'
      rowCaptions=`$pageTitles`
      scale=      `$scale`
    }
  </p>
{/if}

{include file='include/footer.tpl'}
