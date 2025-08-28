{include file='include/header.tpl'}


<h2>経験値段階別、階級別のユーザー分布</h2>

<p>
  <form action="{$smart.server.PHP_SELF}">
    <input type="hidden" name="module" value="Admin" />
    <input type="hidden" name="action" value="UserBunpu" />
    <input type="hidden" name="go" value="1" />

    <table style="display:inline">
      <tr>
        <th>経験値</th>
        <td>
          {$validator->outputError('from', '開始')}
          {$validator->outputError('to', '終了')}
          {$validator->outputError('interval')}
          <input type="text" name="from" style="width:8ex" value="{$validator->input('from')}" />
          ～
          <input type="text" name="to" style="width:8ex" value="{$validator->input('to')}" />
          <input type="submit" value="検索">
        </td>
      </tr>
    </table>
    <span style="color:red">※少し重い。負荷の高い時間帯は禁止</span>
  </form>
</p>

{if $data}
  <p>
    {include file='include/cube_table.tpl'
      data=      `$data`
      rowColumn= 'step'
      colColumn= 'grade_id'
      cellColumn='count'
      rows=      `$rows`
      cols=      `$cols`
      leftTop=   '経験値'
      scale=     `$scale`
    }
  </p>
{/if}

{include file='include/footer.tpl'}
