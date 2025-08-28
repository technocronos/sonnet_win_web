{include file='include/header.tpl'}


<h2>ビットコイン取得ログ</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="BitcoinGet" />
  <input type="hidden" name="go" value="1" />

  <table style="width:30em">
    <tr>
      <th>ユーザーID</th>
      <td>
        {$validator->outputError('id')}
        <input type="text" name="id" style="width:12ex" value="{$validator->input('id')}" />
      </td>
    </tr>
    <tr>
      <th>更新日時</th>
      <td>
        {$validator->outputError('update_at_from', '開始')}
        {$validator->outputError('update_at_to', '終了')}
        <input type="text" name="update_at_from" style="width:12ex" value="{$validator->input('update_at_from')}" />
        ～
        <input type="text" name="update_at_to" style="width:12ex" value="{$validator->input('update_at_to')}" />
      </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:center"><input type="submit" value="検索"></td>
    </tr>
  </table>
</form>


{if $smarty.get.go}
  <hr />

  {if $table}

    <p>
      合計:<span class="value">{$total}</span>BTC
    </p>

    {include file='include/show_resultset.tpl' resultset=`$table`}

  {else}
    該当ありません
  {/if}
{/if}


{include file='include/footer.tpl'}
