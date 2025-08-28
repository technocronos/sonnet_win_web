{include file='include/header.tpl'}

<h2>バトルランキング情報</h2>
現在:{$battle_rank_info.status_str}{if $battle_rank_info.in_aggregate == 1}(集計中){/if}<br><br>


準備期間：{$battle_rank_info.ready_start_date|date_format:'%Y-%m-%d %H:%M:%S'}～{$battle_rank_info.start_date-1|date_format:'%Y-%m-%d %H:%M:%S'}<br>
開催期間：{$battle_rank_info.start_date|date_format:'%Y-%m-%d %H:%M:%S'}～{$battle_rank_info.ranking_end_date|date_format:'%Y-%m-%d %H:%M:%S'}<br>
結果発表：終了日翌AM5時～{$battle_rank_info.end_date|date_format:'%Y-%m-%d %H:%M:%S'}<br><br>


<h2>バトルランキング景品情報</h2>

<p>
  {if $weekly_list}

    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:3em">順位</th>
        <th style="width:12em">付与アイテム</th>
        <th style="width:12em">付与アイテムID</th>
        <th style="width:12em">付与アイテム名</th>
        <th style="width:12em">付与数</th>
        <th style="width:12em">付与ビットコイン</th>
      </tr>

      {foreach from=`$weekly_list` item=item}
        <tr>
          <td style="text-align:center">
            {$item.order}
          </td>
          <td><img src="img/item_sm/{$item.item_id|string_format:"%05d"}.png" style="width:100px" /></td>
          <td>{$item.item_id}</td>
          <td>{$item.item_name}{if $item.set_name != ""}({$item.set_name}){/if}</td>
          <td>{$item.count}</td>
          <td>{$item.btc|floatval} BTC</td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだ入力されていません。

  {/if}<br><br>
  <h2>準備中お知らせ用テキスト</h2><br><br>

掲載日時:{$battle_rank_info.ready_start_date|date_format:'%Y/%m/%d %H:%M:%S'}<br><br>

{$battle_rank_info.start_date|date_format:'%m月'}のバトルランキングについて<br><br>

---------------------------------------------------------------------------------------<br>
ソネット・オブ・ウィザード運営からお知らせです。<br><br>

今月のバトルランキングは{$battle_rank_info.start_date|date_format:'%m月%d日'}（日）～{$battle_rank_info.ranking_end_date|date_format:'%m月%d日'}（土）までとなります。<br><br>

今回の景品は各装備の{if $weekly_list.1.category == "WPN"}武器{elseif $weekly_list.1.category == "BOD"}服{elseif $weekly_list.1.category == "HED"}頭{elseif $weekly_list.1.category == "ACS"}アクセサリ{/if}になります。<br><br>

ランキング1位の目玉景品としまして、新装備である「{$weekly_list.1.set_name}」の「{$weekly_list.1.item_name}」をゲットすることができます。<br><br>

詳しくはヘルプをご覧下さい。<br><br>

これからもソネットオブウィザードをよろしくお願いします。<br><br>
---------------------------------------------------------------------------------------<br>
About the {$battle_rank_info.start_date|date_format:'%B'} Battle Rankings<br><br>
---------------------------------------------------------------------------------------<br>


This is an announcement from Sonnet of Wizard Management.<br><br>

This month's battle ranking will be from Sunday, {$battle_rank_info.start_date|date_format:'%B %d'} to Saturday, {$battle_rank_info.ranking_end_date|date_format:'%B %d'}.<br><br>

The prizes this time will be {if $weekly_list.1.category == "WPN"}Weapons{elseif $weekly_list.1.category == "BOD"}Clothes{elseif $weekly_list.1.category == "HED"}Head{elseif $weekly_list.1.category == "ACS"}Accessories{/if} of each equipment.<br><br>

As the featured prize for the first place in the ranking, you can get the "{$weekly_list.1.item_name_en}" from the new equipment "{$weekly_list.1.set_name_en}".<br><br>

Please see Help for more details.<br><br>

Thank you for your continued support of Sonnet of Wizards.<br><br>

---------------------------------------------------------------------------------------

  <h2>開催中お知らせ用テキスト</h2><br><br>

掲載日時:{$battle_rank_info.start_date|date_format:'%Y/%m/%d %H:%M:%S'}<br><br>
 
{$battle_rank_info.start_date|date_format:'%m月'}バトルランキング開始！<br><br>

---------------------------------------------------------------------------------------<br>
ソネット・オブ・ウィザード運営からお知らせです。<br><br>

{$battle_rank_info.start_date|date_format:'%m月%d日'}（日）～{$battle_rank_info.ranking_end_date|date_format:'%m月%d日'}（土）の期間で「バトルイベント」を開催いたします！<br><br>

バトルイベントは期間中に右上の対戦ボタンからバトルに参加することで参加することができます。<br><br>

バトルランキングの上位入賞者には下記のアイテムがもらえます。<br><br>

  {foreach from=`$weekly_list` key='index' item='rank' name='for'}
    {if $rank.set_name != ""}
        <div style="display: flex;align-items: center;margin-bottom: 10px;">{$smarty.foreach.for.iteration}位：{$rank.item_name}({$rank.set_name})</div>
    {/if}
  {/foreach}<br>


また、上位100人の方には参加賞で「{$weekly_list.100.item_name}」をプレゼントします。<br><br>

また、デイリーランキング上位100人の方には毎日参加賞でBPが回復する「軍鶏の時計」をプレゼントします。<br><br>


ぜひふるってご参加下さい。<br><br>
---------------------------------------------------------------------------------------<br>
{$battle_rank_info.start_date|date_format:'%B'} Battle Rankings begin!<br><br>

---------------------------------------------------------------------------------------<br>
This is an announcement from Sonnet of Wizard Management.<br><br>

We will be holding a "Battle Event" from Sunday, {$battle_rank_info.start_date|date_format:'%B %d'} to Saturday, {$battle_rank_info.ranking_end_date|date_format:'%B %d'}!<br><br>

You can participate in the Battle Event by joining a battle from the battle button on the upper right during the period.<br><br>

Top finishers of the battle ranking will receive the following items.<br><br>

  {foreach from=`$weekly_list` key='index' item='rank' name='for'}
    {if $rank.set_name != ""}
        <div style="display: flex;align-items: center;margin-bottom: 10px;">{$smarty.foreach.for.iteration}：{$rank.item_name_en}({$rank.set_name_en})</div>
    {/if}
  {/foreach}<br>


The top 100 players will also receive a "{$weekly_list.100.item_name_en}" as a participation prize.<br><br>

In addition, the top 100 players in the daily ranking will receive a "Game Fowl Watch" which recovers BP, as a daily participation prize.<br><br>

We look forward to your participation.<br><br>
---------------------------------------------------------------------------------------<br>



  <h2>終了時お知らせ用テキスト</h2><br><br>

掲載日時:{$battle_rank_info.result_date|date_format:'%Y/%m/%d %H:%M:%S'}<br><br>
 
{$battle_rank_info.start_date|date_format:'%m月'}「バトルランキング」終了<br><br>

---------------------------------------------------------------------------------------<br>
{$battle_rank_info.start_date|date_format:'%m月'}「バトルランキング」が終了いたしました。<br><br>

結果はバトルランキング結果発表ページをご覧下さい。<br><br>

結果は1週間ほどで閉じられます。<br><br>

お疲れ様でした。<br><br>
---------------------------------------------------------------------------------------<br>

{$battle_rank_info.start_date|date_format:'%B'} Battle Ranking ends<br><br>

---------------------------------------------------------------------------------------<br>
The {$battle_rank_info.start_date|date_format:'%B'} Battle Ranking has been completed.<br><br>

Please see the Battle Ranking Results page for the results.<br><br>

The results will be closed in about a week.<br><br>

Thank you for your hard work.<br><br>
---------------------------------------------------------------------------------------


</p>

{include file='include/footer.tpl'}
