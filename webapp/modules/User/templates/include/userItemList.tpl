{*
    ユーザが持っているアイテムの一覧を表示するテンプレート。
    ページ分けも自動で行われるが、このときにGETパラメータ "page" を使う。

    パラメータ)
        list        アイテム一覧の情報を持っている連想配列。
                    User_ItemService::getHoldListの戻り値。
        itemLink    アイテムが選択されたときの遷移先URL。
                    "--id--" という文字列が含まれている場合は、user_item_id に置き換えられる。
        equipSelectable
                    trueに指定すると装備中のアイテムも選択可能になる。
        nameFunc    アイテム名部分の出力を返す関数。アイテム名の出力をカスタムしたい場合に指定する。
                    この関数は user_item レコードを引数にとり、アイテム名を出力するHTMLを返さなければならない。
                    このパラメータを指定した場合、itemLink, equipSelectable は無視される。
*}

{image_tag file='hr.gif'}<br />

{if $list.totalRows > 0}

  {foreach from=`$list.resultset` item="row"}

    {if ($carrier == 'iphone' || $carrier == 'android')}
    	
        {fieldset color='brown' width='95%'}
          <legend>
		    {* アイテム名 *}
            {$row.item_name}
		  </legend>
          <div>
 		    {if $row.set.set_name}<span style="color:{#termColor#}">{$row.set.set_name}</span> ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$row.set.rear_id`}</span><div style="clear:both"></div>{/if}
		    {* アイテムの画像 *}
		    {item_image id=`$row.item_id` float='left'}

		    {* 耐久装備品の場合 *}
		    {if call_user_func('Item_MasterService::isDurable', $row.category)}

		      {if $row.free_count == 0}<span style="color:#FF3333">[装備中]</span>{/if}
		      <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$row.level}</span>{item_level_max uitem=`$row`}
		      <span style="color:{#statusNameColor#}">耐久</span><span style="color:{#statusValueColor#}">{$row.durable_count|durability}</span>
		      <br clear="all" /><div style="clear:both"></div>

		      {include file='include/equipSpec.tpl' item=`$row`}

		    {* 消費アイテムの場合 *}
		    {else}

		      ｽﾄｯｸ<span style="color:{#statusValueColor#}">{$row.free_count}</span>個(合計<span style="color:{#statusValueColor#}">{$row.num}</span>個)
		      <br clear="all" /><div style="clear:both"></div>

		      {include file='include/itemEffect.tpl' item=`$row`}<br />

		    {/if}

			{if $nameFuncGousei}
			<div style="text-align:center">{call func=`$nameFuncGousei` 0=`$row`}</div>
			{/if}

			<div style="text-align:right">
			    {if $nameFunc}
			      {call func=`$nameFunc` 0=`$row`}
			    {elseif $itemLink && ($row.free_count > 0 || $equipSelectable)}
			      <a href="{$itemLink|replace:'--id--':$row.user_item_id}" class="buttonlike next">{$row.item_name}</a>
			    {/if}<br />
            </div>

          </div>

        {/fieldset}<br />

    {else}
	    {if $row.set.set_name}<span style="color:{#termColor#}">{$row.set.set_name}</span> ﾚｱ度<span style="color:red">{call func='str_repeat' 0='★' 1=`$row.set.rear_id`}</span><div style="clear:both"></div>{/if}
	    {* アイテムの画像 *}
	    {item_image id=`$row.item_id` float='left'}

	    {* アイテム名 *}
	    {if $nameFunc}
	      {call func=`$nameFunc` 0=`$row`}
	    {elseif $itemLink && ($row.free_count > 0 || $equipSelectable)}
	      <a href="{$itemLink|replace:'--id--':$row.user_item_id}">{$row.item_name}</a>
	    {else}
	      {$row.item_name}
	    {/if}<br />

	    {* 耐久装備品の場合 *}
	    {if call_user_func('Item_MasterService::isDurable', $row.category)}

	      {if $row.free_count == 0}<span style="color:#FF3333">[装備中]</span>{/if}
	      <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$row.level}</span>{item_level_max uitem=`$row`}
	      <span style="color:{#statusNameColor#}">耐久</span><span style="color:{#statusValueColor#}">{$row.durable_count|durability}</span>
	      <br clear="all" /><div style="clear:both"></div>

	      {include file='include/equipSpec.tpl' item=`$row`}

	    {* 消費アイテムの場合 *}
	    {else}

	      ｽﾄｯｸ<span style="color:{#statusValueColor#}">{$row.free_count}</span>個(合計<span style="color:{#statusValueColor#}">{$row.num}</span>個)
	      <br clear="all" /><div style="clear:both"></div>

	      {include file='include/itemEffect.tpl' item=`$row`}<br />

	    {/if}

		{if $nameFuncGousei}
		<div style="text-align:center">{call func=`$nameFuncGousei` 0=`$row`}</div>
		{/if}

	    {image_tag file='hr.gif'}<br />
	{/if}

  {/foreach}

  {* ページャ表示 *}
  {include file="include/pager.tpl" totalPages=`$list.totalPages`}

{else}
  <div style="text-align:center">ｱｲﾃﾑがありません｡</div>
  {image_tag file='hr.gif'}<br />
{/if}
