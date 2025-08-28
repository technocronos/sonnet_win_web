{include file='include/header.tpl'}


<h2>アイテム配布受け取り人数</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="ItemDistribute" />
  <table>
    <tr>
      <th>キャンペーン</th>
      <td>
        {$validator->listbox('flag_id', $distributions)}<input type="submit" value="検索">
      </td>
    </tr>
  </table>
</form>


{if $validator->isValid()}
  <hr />
  人数:<span class="value">{$total}</span>
{/if}


{include file='include/footer.tpl'}
