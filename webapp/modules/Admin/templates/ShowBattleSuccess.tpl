{include file='include/header.tpl'}


<h2>バトル表示(デバック用)</h2>

<form>
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="ShowBattle" />
  バトルID<input type="text" name="id" value="{$smarty.get.id}" /><input type="submit" value="go" />
</form>

{if $data}

  {* detail系以外の列を表示 *}
  <table>
    <tr>
      {foreach from=`$data` key="column" item="value"}
        <th>{$column}</th>
      {/foreach}
    </tr>
    <tr>
      {foreach from=`$data` key="column" item="value"}
        <td>{$value}</td>
      {/foreach}
    </tr>
  </table>

  <hr />

  {* ready_detailを表示 *}
  <table>
    <tr>
      <td style="vertical-align:top">
        <h3>ready_detail.challenger</h3>
        <pre>{$ready_detail.challenger|@print_r:true}</pre>
      </td>
      <td style="vertical-align:top">
        <h3>ready_detail.defender</h3>
        <pre>{$ready_detail.defender|@print_r:true}</pre>
      </td>
      <td style="vertical-align:top">
        <h3>ready_detail.other</h3>
        <pre>{$ready_other|@print_r:true}</pre>
      </td>
    </tr>
  </table>

  <hr />

  ターン数: {$result_detail.match_length}

  {* result_detailを表示 *}
  <table><tr>
    <td style="vertical-align:top">
      <h3>result_detail.challenger</h3>
      <pre>{$result_detail.challenger|@print_r:true}</pre>
    </td>
    <td style="vertical-align:top">
      <h3>result_detail.defender</h3>
      <pre>{$result_detail.defender|@print_r:true}</pre>
    </td>
  </tr></table>


{elseif $smarty.get.id}
  データがありません。
{/if}


{include file='include/footer.tpl'}
