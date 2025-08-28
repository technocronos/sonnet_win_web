{include file='include/header.tpl'}


<h2>バトルログ検索</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="Battlelog" />
  <input type="hidden" name="go" value="1" />

  <table style="width:30em">
    <tr>
      <th>キャラID</th>
      <td>
        {$validator->outputError('characterId')}
        <input type="text" name="characterId" style="width:30ex" value="{$validator->input('characterId')}" />
      </td>
    </tr>
    <tr>
      <th>バトル種別</th>
      <td>
        <select name="tourId">
          <option value="1">ユーザーバトル</option>
          <option value="2">モンスターバトル</option>
        </select><br />
      </td>
    </tr>
    <tr>
      <th>バトル日時</th>
      <td>
        {$validator->outputError('create_at_from', '開始')}
        {$validator->outputError('create_at_to', '終了')}
        <input type="text" name="create_at_from" style="width:22ex" value="{$validator->input('create_at_from')}" />
        ～
        <input type="text" name="create_at_to" style="width:22ex" value="{$validator->input('create_at_to')}" />
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

    {include file='include/show_resultset.tpl' resultset=`$table`}

  {else}
    該当ありません
  {/if}
{/if}


{include file='include/footer.tpl'}
