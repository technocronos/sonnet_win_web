{include file='include/header.tpl'}


{* 処理完了ならば、このウィンドウは閉じる *}
{if $smarty.get.result}

  <script>{literal}
      window.opener.location.reload();
      window.close();
  {/literal}</script>

  更新しました。

{else}
  <form method="post" action="{$smarty.server.REQUEST_URI}" onSubmit="return confirm('よろしいですか？')">
    <p>
      <table style="width:100%; height:700px;">
        <tr>
          <th style="width:100px;height:22px;">シンボル</th>
          <td>
            {$validator->outputError('symbol')}
            <input type="text" name="symbol" value="{$validator->input('symbol')}" style="width:12em" />
          </td>
        </tr>
        <tr>
          <th style="width:100px;height:22px;">カテゴリ</th>
          <td>
            {$validator->outputError('category')}
            <input type="text" name="category" value="{$validator->input('category')}" style="width:12em" />
          </td>
        </tr>
        <tr>
          <th style="width:100px;height:22px;">文字数</th>
          <td>
            {$validator->outputError('characount')}
            <input type="text" name="characount" value="{$validator->input('characount')}" style="width:12em" />
          </td>
        </tr>
        <tr>
          <th style="width:100px;height:22px;">参考画像</th>
          <td>
            {if $record.image_name ne ''}
                <img src="https://{$smarty.const.SITE_DOMAIN}/img/{$record.image_name}" />
            {/if}
          </td>
        </tr>
        <tr>
          <th>日本語</th>
          <td>
            {$validator->outputError('ja')}
            <textarea name="ja" style="width:100%; height:100%">{$validator->input('ja')}</textarea>
          </td>
        </tr>
        <tr>
          <th>英語</th>
          <td>
            {$validator->outputError('en')}
            <textarea name="en" style="width:100%; height:100%">{$validator->input('en')}</textarea>
          </td>
        </tr>
      </table>
    </p>

    <p>
      <div style="float:left">
        <input type="submit" name="save" value="保存">
        {if $smarty.get.id}
          <input type="submit" name="delete" value="削除">
        {/if}
      </div>
      <div style="float:right">
        <button type="button" onClick="window.close()">キャンセル</button>
      </div>
    </p>
  </form>
{/if}


{include file='include/footer.tpl'}
