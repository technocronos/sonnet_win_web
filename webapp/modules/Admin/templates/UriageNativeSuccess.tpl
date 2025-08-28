{include file='include/header.tpl'}


<h2>コイン売上</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="UriageNative" />
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
        <input type="submit" value="検索">
      </td>
    </tr>
  </table>
</form>


{if $table}

  <p>
    総合計:<span class="value">{$totalSales|money}</span>コイン
  </p>

  <table style="width:500em">
    <tr>
      <th style="width:6ex">購入日</th>
      <th style="width:6em">合計額</th>
      {foreach from=`$items` item='item'}
        <th style="width:5em">{$item}</th>
      {/foreach}
    </tr>

    {foreach key='date' item='item' from=`$table`}
      <tr>
        <td>{$date|date_color:'m/d':'&nbsp;'}</td>
        <td>{$item.day_sales|bar_graph:$moneyScale:'%dコイン'}</td>
        {foreach from=`$items` key='id' item='dummy'}
          <td>{$item.$id.amount|bar_graph:$amountScale:'%d個'}{$item.$id.sales|bar_graph:$moneyScale:'%dコイン'}</td>
        {/foreach}
      </tr>
    {/foreach}

  </table>
{/if}


{include file='include/footer.tpl'}
