{include file='include/header.tpl'}


<h2>ビットコインログ</h2>

<p>
  {if $list.resultset}

    合計出金額：{$total_amount}BTC<br/>
    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:3em">ログID</th>
        <th style="width:12em">ユーザーID</th>
        <th style="width:12em">ユーザー名</th>
        <th style="width:12em">ビットコインアドレス</th>
        <th style="width:4em">出金依頼額</th>
        <th style="width:12ex">手数料</th>
        <th style="width:12ex">付与額</th>
        <th style="width:12ex">トランザクションID</th>
        <th style="width:5em">ステータス</th>
        <th style="width:12ex">申請日時</th>
        <th style="width:12ex">更新日時</th>
      </tr>

      {foreach from=`$list.resultset` item=item}
        <tr>
          <td style="text-align:center">
            {$item.log_id}
          </td>
          <td>{$item.user_id}</td>
          <td>{$item.chara_name}</td>
          <td>{$item.address}</td>
          <td>{$item.amount|floatval}</td>
          <td>{$item.fee|floatval}</td>
          <td>{$item.amount-$item.fee|floatval}</td>
          <td>{$item.transaction}</td>
          <td style="text-align:center; background-color:{switch value=`$item.step` 0='palegreen' 1='hotpink' 2='lightskyblue' 3='darkgray'}">
            {switch value=`$item.status` 0='未処理' 10='処理中' 20='終了' 30='取消'}
          </td>
          <td style="text-align:center">{$item.status_update_at|date_ex:"Y/m/d H:i"}</td>
          <td style="text-align:center">{$item.create_at|date_ex:"Y/m/d H:i":'&nbsp;'}</td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだ入力されていません。

  {/if}
</p>

{include file='include/footer.tpl'}
