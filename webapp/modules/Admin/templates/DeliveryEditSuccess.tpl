{include file='include/header.tpl'}


{* 処理完了ならば、このウィンドウは閉じる *}
{if $smarty.get.result}

  <script>{literal}
      window.opener.location.href = "?module=Admin&action=DeliveryList&_nc=" + (new Date()).getTime();
      window.close();
  {/literal}</script>

  更新しました。

{else}
  <form method="post" action="?module=Admin&action=DeliveryEdit&id={$smarty.get.id}" onSubmit="return confirm('よろしいですか？')">
    <input type="hidden" name="target" value="{$smarty.request.target}" />
    <input type="hidden" name="expect" value="{$smarty.request.expect}" />

    <p>
      <div>※絵文字は使えません</div>
      <table>
        <tr>
          <th>タイトル</th>
          <td>
            {$validator->outputError('title')}
            <input type="text" name="title" value="{$validator->input('title')}" style="width:26ex" /><br />
            ※GREEなら半角26文字以内<br />
            ※MOBAGEでは無視される
          </td>
        </tr>
        <tr>
          <th>本文</th>
          <td>
            {$validator->outputError('body')}
            <textarea name="body" style="width:24em; height:8em">{$validator->input('body')}</textarea><br />
            ※GREEなら半角100文字以内<br />
            ※MOBAGEなら38文字以内
          </td>
        </tr>
        <tr>
          <th>配信開始日時</th>
          <td>
            {$validator->outputError('start_at')}
            <input type="text" name="start_at" value="{$validator->input('start_at')}" style="width:19ex" />
            (例) {$smarty.now|date_ex:'Y/m/d'} 12:00:00<br />
            ※空白の場合は即時配信<br />
            ※タイマー精度は5分程度
          </td>
        <tr>
      </table>
    </p>

    <p>
      <div style="float:left">
        <input type="submit" name="save" value="保存">
        {if $smarty.get.id}
          <input type="submit" name="stop" value="取消">
        {/if}
      </div>
      <div style="float:right">
        <button type="button" onClick="window.close()">キャンセル</button>
      </div>
    </p>
  </form>
{/if}


{include file='include/footer.tpl'}
