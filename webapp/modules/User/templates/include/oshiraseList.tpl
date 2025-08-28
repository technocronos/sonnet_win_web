{*
    お知らせの一覧を表示するテンプレート。
    パラメータ)
        list          表示するお知らせの一覧
*}


{if $list}

  {* リスト *}
  {foreach from=`$list` item="row"}

      {$row.notify_at|datetime}
      {$row.importance_icon}<a href="{url_for action='OshiraseDetail' id=`$row.oshirase_id` _backto=true}">{$row.title}</a>{if $row.isNew}{/if}<br />

  {/foreach}

{else}
  <br />
  <div style="text-align:center">まだありません。</div>
  <br />
{/if}
