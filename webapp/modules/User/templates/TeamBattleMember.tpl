{include file="include/header.tpl" title="ﾁｰﾑ対戦ﾒﾝﾊﾞｰ選択"}


<div style="text-align:right"><a href="{url_for _self=true action='BattleConfirm'}">通常対戦⇒</a></div>

{if $canBattle == 'ok'}

  {image_tag file='navi_mini.gif' float='left'}
  {if $memberCount==1}
    <span style="color:{#termColor#}">{$rival.short_name}</span>とﾁｰﾑ対戦するのだ<br />
    仲間からﾒﾝﾊﾞｰを選ぶのだ｡同じやつは一日に一回しか選べないのだ｡とりあえず1人目を選ぶのだ
  {else}
    <span style="color:{#statusValueColor#}">{$memberCount}</span>人目のﾒﾝﾊﾞｰを選ぶのだ
  {/if}
  <br clear="all" /><div style="clear:both"></div>

  {if $memberCount>=2}
    <a href="{url_for _self=true page=0 member1=null}">←ｷｬﾝｾﾙ</a><br />
  {/if}

  {image_tag file='hr.gif'}<br />
  {foreach from=`$list.resultset` item="row"}

    {platform_thumbnail src=`$row.thumbnail_url` size='M' float='left'}

    {if $row.last_cooperate_at==$today}
      {$row.short_name}<span style="color:{#noticeColor#}">選択不可</span><br />
    {elseif $smarty.get.member1==$row.user_id}
      {$row.short_name}<span style="color:{#strongColor#}">選択済</span><br />
    {else}
      <a href="{$selectLink|replace:'_id_':$row.user_id}">{$row.short_name}</a><br />
    {/if}

    {text id=`$row.character.name_id`} <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$row.character.level}</span><br />
    {text grade=`$row.character.grade_id`}

    <br clear="all" /><div style="clear:both"></div>
    {image_tag file='hr.gif'}<br />
  {/foreach}

  {include file='include/pager.tpl' totalPages=`$list.totalPages`}

{else}

  <br />
  {image_tag file='navi_mini.gif' float='left'}
  {if $canBattle=='sphere'}
    ﾌｨｰﾙﾄﾞｸｴｽﾄ中はﾁｰﾑ対戦できないのだ
  {elseif $canBattle=='member-rival'}
    ｺｲﾂは仲間がいないからﾁｰﾑ対戦できないのだ
  {elseif $canBattle=='member'}
    仲間が<span style="color:{#statusValueColor#}">2</span>人以上いないと対戦できないのだ<br />
    <a href="{url_for action='MemberSearch' _backto=true}">誰か適当に</a>仲間申請するかｺﾐｭで仲間募集してみるのだ
  {elseif $canBattle=='ticket'}
    <span style="color:{#termColor#}">{$ticket.item_name}</span>持ってないから対戦できないのだ
    <a href="{url_for action='Shop' cat='ITM' currency='coin' buy='99002'}">買う⇒</a>
  {/if}
  <br clear="all" /><div style="clear:both"></div>

{/if}

<br />
<a href="{url_for action='HisPage' userId=`$rival.user_id` backto=`$smarty.get.backto`}">←戻る</a><br />


{include file="include/footer.tpl"}
