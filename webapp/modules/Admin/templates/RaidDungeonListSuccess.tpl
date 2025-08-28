{include file='include/header.tpl'}

<h2>レイドダンジョンマスター情報</h2>

<p>
  {if $raid_dungeon}

    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:3em">ID</th>
        <th style="width:12em">タイトル</th>
        <th style="width:12em">参加報酬</th>
        <th style="width:12em">告知日時</th>
        <th style="width:12em">スタート日時</th>
        <th style="width:12em">結果表示日時</th>
        <th style="width:12em">終了日時</th>
        <th style="width:12em">ステータス</th>
        <th style="width:12em">進捗</th>
      </tr>

      {foreach from=`$raid_dungeon` item=item}
        <tr {if $item.status > 0}style="background-color: gold;"{/if}>
          <td style="text-align:center">
            {$item.id}
          </td>
          <td>{$item.title}</td>
          <td>{$item.join_prize|number_format:5} BTC</td>
          <td>{$item.notice_at}</td>
          <td>{$item.start_at}</td>
          <td>{$item.end_at}</td>
          <td>{$item.close_at}</td>
          <td>{if $item.status == 1}準備中{elseif $item.status == 2}開催中{elseif $item.status == 3}成功{elseif $item.status == 4}失敗{else}非開催{/if}</td>
          <td><a href="?module=Admin&action=RaidDungeonDetail&raid_dungeon_id={$item.id}">{$item.defeat_count} / {$item.total_count}</a></td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだ入力されていません。

  {/if}<br><br>

</p>

{include file='include/footer.tpl'}
