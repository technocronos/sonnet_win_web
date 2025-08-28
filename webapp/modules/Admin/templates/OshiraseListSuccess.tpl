{include file='include/header.tpl'}


<h2>お知らせの一覧</h2>

<p>
  <button type="button" onClick="window.open('?module=Admin&action=OshiraseEdit', '_blank', 'menubar=no,toolbar=no,width=500,height=400')">新規作成</button>
</p>

<p>
  {if $list.resultset}

    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:3em">編集</th>
        <th style="width:3.5em">重要度</th>
        <th style="width:12em">タイトル</th>
        <th style="width:12em">本文</th>
        <th style="width:12em">タイトル(en)</th>
        <th style="width:12em">本文(en)</th>
        <th style="width:15ex">公開日時</th>
      </tr>

      {foreach from=`$list.resultset` item=item}
        <tr>
          <td style="text-align:center">
            <a href="?module=Admin&action=OshiraseEdit&id={$item.oshirase_id}" onClick="window.open(this.href, '_blank', 'menubar=no,toolbar=no,width=500,height=400'); return false;">編集</a>
          </td>
          <td>{$item.importance_text}</td>
          <td>{$item.title}</td>
          <td>{call func='Oshirase_LogService::getBodyHtml' 0=`$item.body`}</td>
          <td>{$item.title_en}</td>
          <td>{call func='Oshirase_LogService::getBodyHtml' 0=`$item.body_en`}</td>
          <td>{$item.notify_at|date_ex:"y/m/d H:i"}</td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだ入力されていません。

  {/if}
</p>


{include file='include/footer.tpl'}
