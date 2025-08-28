{include file='include/header.tpl'}


<h2>最近のユーザ間メッセージ</h2>

<form action="{$smarty.const.APP_WEB_ROOT}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="UserMessage" />

  {$validator->listbox('type', $types)}
  <input type="submit" value="go" />
</form>

<hr />

{if $list.resultset}

  <table>
    <tr>
      <td colspan="2" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
    </tr>

    <tr>
      <th style="width:12em">メッセージ</th>
      <th style="width:12ex">日時</th>
      <th>書き込みユーザID</th>
    </tr>

    {foreach from=`$list.resultset` item='item'}
      <tr {if $item.inspection_status == constant('Text_LogService::STATUS_NG')}style="background-color:silver"{/if}>
        <td>{$item.body|nl2br}</td>
        <td style="text-align:center">{$item.create_at|date_ex:"m/d H:i"}</td>
        <td style="text-align:center">{$item.writer_id}</td>
      </tr>
    {/foreach}
  </table>

{/if}


{include file='include/footer.tpl'}
