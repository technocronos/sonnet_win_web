{include file='include/header.tpl'}


<h2>メッセージ配信の一覧</h2>

<p>
  {if $list.resultset}

    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:3em">編集</th>
        <th style="width:12em">タイトル</th>
        <th style="width:12em">本文</th>
        <th style="width:4em">状態</th>
        <th style="width:12ex">開始日時</th>
        <th style="width:12ex">終了日時</th>
        <th style="width:5em">予想件数</th>
        <th style="width:5em">送信件数</th>
        <th style="width:5em">効果</th>
        <th style="width:12ex">設定日時</th>
        <th style="width:3em">対象</th>
      </tr>

      {foreach from=`$list.resultset` item=item}
        <tr>
          <td style="text-align:center">
            {if $item.step <= constant('Delivery_LogService::GOING')}
              <a href="?module=Admin&action=DeliveryEdit&id={$item.delivery_id}" onClick="window.open(this.href, '_blank', 'menubar=no,toolbar=no,width=500,height=400'); return false;">編集</a>
            {else}
              &nbsp;
            {/if}
          </td>
          <td>{$item.title}</td>
          <td>{$item.body|nl2br}</td>
          <td style="text-align:center; background-color:{switch value=`$item.step` 0='palegreen' 1='hotpink' 2='lightskyblue' 3='darkgray'}">{switch value=`$item.step` 0='待機' 1='配信中' 2='終了' 3='取消'}</td>
          <td style="text-align:center">{$item.start_at|date_ex:"m/d H:i"}</td>
          <td style="text-align:center">{$item.finish_at|date_ex:"m/d H:i":'&nbsp;'}</td>
          {$item.expect_count|table_cell}
          {$item.send_count|table_cell}
          {$item.wonderness|table_cell}
          <td style="text-align:center">{$item.create_at|date_ex:"m/d H:i"}</td>
          <td style="text-align:center"><a href="?module=Admin&action=FindUser&{$item.target_string}">対象</a></td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだ入力されていません。

  {/if}
</p>

<hr />
<p>
  <form action="{$smarty.const.APP_WEB_ROOT}" target="_blank">
    <input type="hidden" name="module" value="Task" />
    <input type="hidden" name="action" value="MessageDelivery" />
    <input type="hidden" name="send" value="1" />

    <input type="text" name="unit" value="20" />人を対象に(最高20)
    <button type="submit">配信タイミングを手動で送信</button>
  </form>
</p>

{include file='include/footer.tpl'}
