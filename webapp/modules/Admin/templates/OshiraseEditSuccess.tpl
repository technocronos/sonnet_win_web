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
      <table>
        <tr>
          <th>重要度</th>
          <td>
            {$validator->outputError('importance')}
            {$validator->listbox('importance', $importances)}
          </td>
        </tr>
        <tr>
          <th>タイトル</th>
          <td>
            {$validator->outputError('title')}
            <input type="text" name="title" value="{$validator->input('title')}" style="width:12em" />
          </td>
        </tr>
        <tr>
          <th>表示内容</th>
          <td>
            {$validator->outputError('body')}
            <textarea name="body" style="width:24em; height:10em">{$validator->input('body')}</textarea>
            <table>
              <tr>
                <td style="border-style:none">※文字を大きく</dt>
                <td style="border-style:none; padding-left:1em">&lt;large&gt;文字を大きくします。&lt;/large&gt;</td>
              </tr>
              <tr>
                <td style="border-style:none">※文字色を変更</dt>
                <td style="border-style:none; padding-left:1em">&lt;color #FF0000&gt;文字を赤くします。&lt;/color&gt;</td>
              </tr>
              <tr>
                <td style="border-style:none">※サイト内リンク</dt>
                <td style="border-style:none; padding-left:1em">&lt;link module=User&amp;action=Index&gt;リンク&lt;/color&gt;</td>
              </tr>
              <tr>
                <td style="border-style:none" colspan="2">※その他HTML有効</dt>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <th>タイトル(en)</th>
          <td>
            {$validator->outputError('title_en')}
            <input type="text" name="title_en" value="{$validator->input('title_en')}" style="width:12em" />
          </td>
        </tr>
        <tr>
          <th>表示内容(en)</th>
          <td>
            {$validator->outputError('body_en')}
            <textarea name="body_en" style="width:24em; height:10em">{$validator->input('body_en')}</textarea>
            <table>
              <tr>
                <td style="border-style:none">※文字を大きく</dt>
                <td style="border-style:none; padding-left:1em">&lt;large&gt;文字を大きくします。&lt;/large&gt;</td>
              </tr>
              <tr>
                <td style="border-style:none">※文字色を変更</dt>
                <td style="border-style:none; padding-left:1em">&lt;color #FF0000&gt;文字を赤くします。&lt;/color&gt;</td>
              </tr>
              <tr>
                <td style="border-style:none">※サイト内リンク</dt>
                <td style="border-style:none; padding-left:1em">&lt;link module=User&amp;action=Index&gt;リンク&lt;/color&gt;</td>
              </tr>
              <tr>
                <td style="border-style:none" colspan="2">※その他HTML有効</dt>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <th>リリース日時</th>
          <td>
            {$validator->outputError('notify_at')}
            <input type="text" name="notify_at" value="{$validator->input('notify_at')}" style="width:19ex" />
            (例) {$smarty.now|date_ex:'Y/m/d'} 12:00:00<br />
            ※空白の場合は即時公開<br />
            ※タイマー精度は5分程度
          </td>
        <tr>
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
