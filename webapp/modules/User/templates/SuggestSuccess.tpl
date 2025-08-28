{include file="include/header.tpl"}


{* 回復アイテム持っている場合 *}
{if $uitem}

  {image_tag file='navi2_mini.gif' float='left'}
  <span style="color:{#termColor#}">{$uitem.item_name}</span>使うんか?ん?使うんじゃろ?
  <br clear="all" /><div style="clear:both"></div>
  <br />

  <form action="{url_for _self=true}" method="post">
    <input type="hidden" name="mode" value="use"/>

    <div style="text-align:center">
      {item_image id=`$uitem.item_id`}<br />
      {include file='include/itemEffect.tpl' item=`$uitem`}<br />
      <input type="submit" value="使う" id="submit_button" /><br />
      所持<span style="color:{#statusValueColor#}">{$uitem.num}</span>個
    </div>
  </form>

{* 回復アイテム持っていない場合 *}
{else}

  {image_tag file='navi2_mini.gif' float='left'}
  <span style="color:{#termColor#}">{$item.item_name}</span>買うんか?買っとけ買っとけ!安いぞ!
  <br clear="all" /><div style="clear:both"></div>

  {image_tag file='hr.gif'}<br />

  <form action="{url_for _self=true}" method="post">
    <input type="hidden" name="mode" value="buy" />

    {item_image id=`$item.item_id` float='left'}
    {$item.item_name}<br />
    {charge_price price=`$price`}<br />
    {include file='include/itemEffect.tpl' item=`$item`}
    <br clear="all" /><div style="clear:both"></div>

    <div style="text-align:center">
      <select name="num">
        <option value="1">1個</option>
        <option value="2">2個</option>
        <option value="3">3個</option>
        <option value="4">4個</option>
        <option value="5">5個</option>
        <option value="6">6個</option>
        <option value="7">7個</option>
        <option value="8">8個</option>
        <option value="9">9個</option>
      </select>
      <input type="submit" value="買う" id="submit_button" />{charge_mark}
    </div>
  </form>

  {if $smarty.get.type=='ap' || $smarty.get.type=='repaire'}
    {image_tag file='hr.gif'}<br />
    {image_tag file='navi2_mini.gif' float='right'}
    <a href="{url_for action='Help' id='other-shoutai'}" class="buttonlike next">友だち招待</a>でも手に入るでの｡誘っとけ
    <br clear="all" /><div style="clear:both"></div>
  {/if}

{/if}

{if ($carrier == 'iphone' || $carrier == 'android')}
  {* 何もなし *}

{else}
  {* プラットフォームのユーザ記事 *}
  {if $smarty.get.type == 'ap'}
    {image_tag file='hr.gif'}<br />
    {$smarty.const.PLATFORM_ARTICLE_NAME}で<span style="color:{#termColor#}">行動pt</span><span style="color:{#statusValueColor#}">{$smarty.const.ARTICLE_AP}</span><br />
    {article_form return=`$returnTo` body='ｿﾈｯﾄ･ｵﾌﾞ･ｳｨｻﾞｰﾄﾞﾌﾟﾚｲ中｡ｲﾍﾞﾝﾄやｽﾄｰﾘｰが沢山あって面白さ200%!この内容を日記に書くだけで行動ptが回復するから､今すぐ開始しようよ!'}%button%<span style="color:{#noticeColor#}">一日一回まで</span>{/article_form}
  {/if}


  {if $smarty.get.type == "ap" && !$uitem}
    {image_tag file='hr.gif'}<br />
    {* バトルイベントのリンク *}
    <div style="text-align:center">
      <span style="color:{#termColor#}">それでも行動ptがないならバトル！</span></br>
      <a href="{url_for action='Ranking' _backto=true}">{image_tag file='b_q_battle_event.gif'}</a><br />期間{$term.begin|date_ex:'n/j'}～{$term.end|date_ex:'n/j'}!!<br />{if $cycle==1}中間集計が出たよ!!{elseif $cycle==2}残り1日!急げ!{elseif $cycle==3}{$prev.end|date_ex:'n/j'}までの結果発表中!!{elseif $cycle==4}{$prev.end|date_ex:'n/j'}までの結果集計中!!{/if}<br />
    </div>
  {/if}
{/if}
  {* 次ページ *}
  <br />
  {if $smarty.get.backto}<a href="{backto_url}" class="buttonlike back">←戻る</a><br />{/if}
  <a href="{url_for action='Main'}" class="buttonlike back">←ｿﾈｯﾄﾒﾆｭｰ</a><br />

  {* 広告表示 *}
  {include file="include/ad.tpl" place="ap"}


{include file="include/footer.tpl" showCompanyName=true}
