{include file='include/header.tpl'}

<h2>レイドダンジョン詳細</h2>

<p>
  <table>
    <tr>
      <th>ダンジョンID</th><td>{$raid_dungeon.id}</td>
    </tr>
    <tr>
      <th>ダンジョン名</th><td>{$raid_dungeon.title}</td>
    </tr>
    <tr>
      <th>ダンジョン名(英語)</th><td>{$raid_dungeon.title_en}</td>
    </tr>
    <tr>
      <th>参加料</th><td>{$raid_dungeon.join_prize|number_format:5} BTC</td>
    </tr>
    <tr>
      <th>告知日時</th><td>{$raid_dungeon.notice_at}</td>
    </tr>
    <tr>
      <th>スタート日時</th><td>{$raid_dungeon.start_at}</td>
    </tr>
    <tr>
      <th>結果表示日時</th><td>{$raid_dungeon.end_at}</td>
    </tr>
    <tr>
      <th>終了日時</th><td>{$raid_dungeon.close_at}</td>
    </tr>
    <tr>
      <th>説明</th><td>{$raid_dungeon.description|nl2br}</td>
    </tr>
    <tr>
      <th>説明（英）</th><td>{$raid_dungeon.description_en|nl2br}</td>
    </tr>
    <tr>
      <th>ステータス</th><td>{if $raid_dungeon.status == 1}準備中{elseif $raid_dungeon.status == 2}開催中{elseif $raid_dungeon.status == 3}成功{elseif $raid_dungeon.status == 4}失敗{else}非開催{/if}</td>
    </tr>
    <tr>
      <th>進捗</th><td>{$raid_dungeon.defeat_count} / {$raid_dungeon.total_count}</td>
    </tr>
    <tr>
      <th>最終討伐日</th><td>{$raid_dungeon.defeat_date}</td>
    </tr>
  </table>
<br>
<h2>ランキング</h2>

  {if $raid_rank}

    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:12em">順位</th>
        <th style="width:12em">ユーザーID</th>
        <th style="width:12em">ユーザー名</th>
        <th style="width:12em">レベル</th>
        <th style="width:12em">階級</th>
        <th style="width:12em">総合ポイント</th>
      </tr>

      {foreach from=`$raid_rank` item=item}
        <tr {if $item.status > 0}style="background-color: gold;"{/if}>
          <td style="text-align:center">
            {$item.rank}
          </td>
          <td><a href="?module=Admin&action=FindUser&go=1&id={$item.avatar.user_id}">{$item.avatar.user_id}</a></td>
          <td>{$item.avatar.player_name}</td>
          <td>{$item.avatar.level}</td>
          <td>{$item.avatar.grade.grade_name}</td>
          <td>{$item.total_point}</td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだランキングがありません。

  {/if}<br><br>

</p>

<h2>報酬(合計:{$total_prize} BTC)</h2>

  {if $raid_prize}

    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:12em">順位</th>
        <th style="width:12em">報酬</th>
        <th style="width:12em">値</th>
      </tr>

      {foreach from=`$raid_prize` item=item}
        <tr>
          <td style="text-align:center">
            {$item.rank_id}
          </td>
          <td>{if $item.join_prize_kind == 1}BTC{else if $item.join_prize_kind == 2}アイテム{/if}</td>
          <td>{if $item.join_prize_kind == 1}{$item.prize|number_format:5} BTC{else if $item.join_prize_kind == 2}{$item.item_name}{/if}</td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだランキングがありません。

  {/if}<br><br>

  <h2>準備中お知らせ用テキスト</h2><br><br>

掲載日時:{$raid_dungeon.notice_at|date_format:'%Y/%m/%d %H:%M:%S'}<br><br>

{$raid_dungeon.title}開催<br>
---------------------------------------------------------------------------------------<br>
ソネット・オブ・ウィザード運営からお知らせです。<br><br>

{$raid_dungeon.start_at|date_format:'%m月%d日'}（{$raid_dungeon.start_at_w}）～{$raid_dungeon.end_at|date_format:'%m月%d日'}（{$raid_dungeon.end_at_w}）の期間で「{$raid_dungeon.title}」を開催いたします！<br><br>

期間は3日間とし、その期間で累積したポイントでの景品付与となります。<br><br>

ただし、その日のうちに全モンスターを倒しきったらポイントを得たユーザーすべてにBTCが付与されます！<br><br>

みんなで協力して攻略しよう！<br><br>

詳しくはレイドダンジョン特設ページへ<br><br>
---------------------------------------------------------------------------------------<br>
{$raid_dungeon.title_en} held<br>
---------------------------------------------------------------------------------------<br>


This is a notice from the management of Sonnet of Wizards.<br><br>

The “{$raid_dungeon.title_en}” will be held from {$raid_dungeon.start_at|date_format:'%B %d'} ({$raid_dungeon.start_at_w_en}) to {$raid_dungeon.end_at|date_format:'%B %d'} ({$raid_dungeon.end_at_w_en})!<br><br>

The period is 3 days, and prizes will be awarded using the points accumulated during that period.<br><br>

However, if all monsters are defeated on the same day, all users who earned points will receive BTC!<br><br>

Let's all work together to conquer it!<br><br>

For more information, please visit the raid dungeon special page.<br><br>

---------------------------------------------------------------------------------------

  <h2>開催中お知らせ用テキスト</h2><br><br>

掲載日時:{$raid_dungeon.start_at|date_format:'%Y/%m/%d %H:%M:%S'}<br><br>
 
{$raid_dungeon.title}開始！<br>
---------------------------------------------------------------------------------------<br>
ソネット・オブ・ウィザード運営からお知らせです。<br><br>

{$raid_dungeon.title}が開催されました！<br><br>

{$raid_dungeon.start_at|date_format:'%m月%d日'}（{$raid_dungeon.start_at_w}）～{$raid_dungeon.end_at|date_format:'%m月%d日'}（{$raid_dungeon.end_at_w}）の期間で「{$raid_dungeon.title}」を開催いたします！<br><br>

今回は3日間で累積したポイントでの景品付与となります。<br><br>

ただし、その日のうちに全モンスターを倒しきったらポイントを得たユーザーすべてにBTCが付与されます！<br><br>

みんなで協力して攻略しよう！<br><br>

詳しくはレイドダンジョン特設ページへ<br><br>
---------------------------------------------------------------------------------------<br>
{$raid_dungeon.title_en} begin!<br>
---------------------------------------------------------------------------------------<br>
This is a notice from the management of Sonnet of Wizards.<br><br>

The {$raid_dungeon.title_en} has been held!<br><br>

The “{$raid_dungeon.title_en}” will be held from {$raid_dungeon.start_at|date_format:'%B %d'} ({$raid_dungeon.start_at_w_en}) to {$raid_dungeon.end_at|date_format:'%B %d'} ({$raid_dungeon.end_at_w_en})!<br><br>

This time, prizes will be given using points accumulated over 3 days.<br><br>

However, if all monsters are defeated on the same day, all users who earned points will receive BTC!<br><br>

Let's all work together to conquer it!<br><br>

For more information, please visit the raid dungeon special page<br><br>
---------------------------------------------------------------------------------------<br>



  <h2>終了時お知らせ用テキスト</h2><br><br>

掲載日時:{$raid_dungeon.end_at|date_format:'%Y/%m/%d %H:%M:%S'}<br><br>
 
{$raid_dungeon.title}終了<br>

---------------------------------------------------------------------------------------<br>
{$raid_dungeon.title}が終了しました。<br><br>

終了日の3:00に景品を付与しますのでご確認ください。結果は{$raid_dungeon.close_at|date_format:'%d日'}まで表示となります。<br><br>

お疲れ様でした。また次回開催をお楽しみに！<br><br>
---------------------------------------------------------------------------------------<br>

{$raid_dungeon.title_en} ends<br>

---------------------------------------------------------------------------------------<br>
The {$raid_dungeon.title_en} has ended.<br><br>

Prizes will be given out at 3:00 on the end date, so please check back. Results will be displayed until the {$raid_dungeon.close_at|date_format:'%d'}.<br><br>

thank you for your hard work. Look forward to the next event!<br><br>
---------------------------------------------------------------------------------------

</p>


{include file='include/footer.tpl'}
