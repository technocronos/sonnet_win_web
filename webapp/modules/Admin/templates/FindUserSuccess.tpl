{include file='include/header.tpl'}


<h2>ユーザ検索</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="FindUser" />
  <input type="hidden" name="go" value="1" />

  <table style="width:30em">
    <tr>
      <th>ユーザID</th>
      <td>
        {$validator->outputError('id')}
        <input type="text" name="id" style="width:12ex" value="{$validator->input('id')}" />
      </td>
    </tr>
    <tr>
      <th>キャラID</th>
      <td>
        {$validator->outputError('character_id')}
        <input type="text" name="character_id" style="width:12ex" value="{$validator->input('character_id')}" />
      </td>
    </tr>

    {if $smarty.const.PLATFORM_TYPE !== "nati"}
    <tr>
      <th>ユーザ名</th>
      <td>
        {$validator->outputError('name')}
        <input type="text" name="name" style="width:12em" value="{$validator->input('name')}" />
      </td>
    </tr>
    {/if}
    <tr>
      <th>キャラ名</th>
      <td>
        {$validator->outputError('body')}
        <input type="text" name="body" style="width:12em" value="{$validator->input('body')}" />
      </td>
    </tr>
    <tr>
      <th>最終アクセス日時</th>
      <td>
        {$validator->outputError('access_date_from', '開始')}
        {$validator->outputError('access_date_to', '終了')}
        <input type="text" name="access_date_from" style="width:12ex" value="{$validator->input('access_date_from')}" />
        ～
        <input type="text" name="access_date_to" style="width:12ex" value="{$validator->input('access_date_to')}" />
      </td>
    </tr>
    <tr>
      <th>登録日時</th>
      <td>
        {$validator->outputError('create_at_from', '開始')}
        {$validator->outputError('create_at_to', '終了')}
        <input type="text" name="create_at_from" style="width:12ex" value="{$validator->input('create_at_from')}" />
        ～
        <input type="text" name="create_at_to" style="width:12ex" value="{$validator->input('create_at_to')}" />
      </td>
    </tr>
    <tr>
      <th>階級</th>
      <td>
          {form_select name='grade_id' src=`$grades`}
      </td>
    </tr>

    <tr>
      <th>ビットコイン（以上）</th>
      <td>
        {$validator->outputError('virtual_coin')}
        <input type="text" name="virtual_coin" style="width:12em" value="{$validator->input('virtual_coin')}" />
      </td>
    </tr>
    <tr>
      <th>合計売上（以上）</th>
      <td>
        {$validator->outputError('sales')}
        <input type="text" name="sales" style="width:12em" value="{$validator->input('sales')}" />
      </td>
    </tr>
    <tr>
      <th>af_status<br>(Non-organic or organic)</th>
      <td>
        {$validator->outputError('af_status')}
        <input type="text" name="af_status" style="width:12em" value="{$validator->input('af_status')}" />
      </td>
    </tr>
    <tr>
      <th>campaign</th>
      <td>
        {$validator->outputError('campaign')}
        <input type="text" name="campaign" style="width:12em" value="{$validator->input('campaign')}" />
      </td>
    </tr>
    <tr>
      <th>media_source</th>
      <td>
        {$validator->outputError('media_source')}
        <input type="text" name="media_source" style="width:12em" value="{$validator->input('media_source')}" />
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
      該当:<span class="value">{$hit}</span>人 (ライブユーザ:<span class="value">{$live}</span>人)
      &nbsp;&nbsp;
      <a href="?module=Admin&action=DeliveryEdit&target={$target}&expect={$live}" onClick="window.open(this.href, '_blank', 'menubar=no,toolbar=no,width=500,height=400'); return false;">この条件でメッセージ送信</a><br />
    </p>

    {include file='include/show_userset.tpl' resultset=`$table`}

  {else}
    該当ありません
  {/if}
{/if}


{include file='include/footer.tpl'}
