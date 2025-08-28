{include file="include/header.tpl"}


{* ユーザのプラットフォームサムネイルを表示 *}
<div style="text-align:center">{platform_thumbnail id=`$smarty.get.companionId`}</div>

{* すでに仲間の場合は解除フォームを表示 *}
{if $isMember}

  {* ナビゲーションメッセージ *}
  {image_tag file='navi_mini.gif' float='left'}
  絶交しちゃうのだ？
  <br clear="all" /><div style="clear:both"></div>

  <form action="{url_for _self=true}" method="post">
    <div style="text-align:center">
      <input type="submit" name="dissolve" value="仲間を解除" /><br />
    </div>
  </form>

{* まだ仲間でないなら申請フォームを表示 *}
{else}

  {* ナビゲーションメッセージ *}
  {image_tag file='navi_mini.gif' float='left'}

  {if $error=='recipient_limit'}
    <span style="color:termColor">{$companion.short_name}</span>は仲間たくさんで八方美人なのだ｡落ち着くまで待ってみるのだ

  {elseif $error=='inviter_limit'}
    …おまえちょっとﾁｮｯｶｲ出しすぎなのだ｡申請出したり受けたりしてるなら､ｷｬﾝｾﾙしたり拒否したりするのだ

  {elseif $error=='cross_request'}
    <span style="color:termColor">{$companion.short_name}</span>から申請受けてるのだ｡<a href="{url_for module='User' action='ApproachList' side='receive' _backto=true}" class="buttonlike next">ｺｺ</a>で承認してやるのだ

  {elseif $error=='member_already'}
    <span style="color:termColor">{$companion.short_name}</span>はもう仲間なのだ

  {elseif $error=='self_request'}
    自分に申請出してどうするのだ…｡涙出そうだから一人ぼっち遊びはやめるのだ…

  {else}
    <span style="color:termColor">{$companion.short_name}</span>に下僕…じゃなくて仲間になるように申請を出すのだ?
  {/if}
  <br clear="all" /><div style="clear:both"></div>

  {* 申請を出せるのなら申請フォームを表示 *}
  {if $error == 'ok'}
    <form action="{url_for _self=true}" method="post">
      <br />
      <div style="text-align:center">
        (あと<span style="color:{#statusValueColor#}">{$memberInfo.limit-$memberInfo.total}</span>人に申請可能)<br />
        <input type="submit" name="approach" value="申請を出す" /><br />
      </div>
    </form>
  {/if}

{/if}


<br />
<a href="{backto_url}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
