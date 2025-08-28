{include file="include/header.tpl" title=`$title`}


{$flavor}<br />
{if ($smarty.get.field=='rare_level' && $smarty.get.value>=2)}
  <a href="{url_for action='Shop' cat='ITM' currency='coin' buy='1907'}">まもののｴｻ</a>でおびきよせることができる
{/if}

{if $list.totalRows > 0}
  <table align="center">
    {foreach from=`$list.resultset` item='item' name='for'}
      {if $smarty.foreach.for.first}<tr>{/if}

      <td>
        {if $item.terminate_at}
          <a href="{url_for action='MonsterDetail' id=`$item.character_id` _backto=true}">{item_image id=`$item.character_id` cat='monster'}</a>
        {else}
          {image_tag file='close.gif' cat='monster'}
        {/if}<br />
        <div style="text-align:center">{$item.monster_no}</div>
      </td>

      {if $smarty.foreach.for.iteration % 2 == 0}</tr><tr>{/if}
      {if $smarty.foreach.for.last}</tr>{/if}
    {/foreach}
  </table>

  {* ページャ *}
  {include file="include/pager.tpl" totalPages=`$list.totalPages`}

{else}
  <div style="text-align:center">まだいません</div>
{/if}


<br />
<a href="{url_for action='MonsterTop'}" class="buttonlike back">←戻る</a><br />


{include file="include/footer.tpl"}
