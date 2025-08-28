{include file="include/header.tpl" title=""}


{* コメント入力完了の場合 *}
{if $smarty.get.result}

  {image_tag file='comment.gif' float='left'}
  ｺﾒﾝﾄを付けました｡
  <br clear="all" /><div style="clear:both"></div>

  {* チュートリアルなら追加のメッセージ表示 *}
  {if $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_RIVAL')}
    {image_tag file='hr.gif'}<br />
    {image_tag file='navi_mini.gif' float='left'}
    しっかりｺﾒﾝﾄを残すとはエラいのだ｡もじょは少しだけ感心したのだ<br />
    さて<a href="{url_for module='Swf' action='Tutorial' done='Rival'}" class="buttonlike next">じじぃの家</a>に戻るのだ
    <br clear="all" /><div style="clear:both"></div>
  {/if}

{* コメント入力完了ではない場合 *}
{else}

  {* コメント入力でエラーがないなら表示 *}
  {if !$error}


    {* バトル結果 *}
    <div style="text-align:center">
      {if $battle.bias_status == 'win'  ||  $battle.bias_status == 'lose'}
        {image_tag file="`$battle.bias_status`.gif"}
      {else}
        {switch value=`$battle.bias_status` draw='相討ち' timeup='時間切れ'}
      {/if}
    </div>


	{if ($carrier == 'iphone' || $carrier == 'android')}
	    {* バトルサマリ(バトル情報とか獲得経験値とか) *}
	    {include file='include/battleSummary_pc.tpl' battle=`$battle` current=`$current`}</br>

	    {* 自分のキャラについて表示しているなら表示する *}
	    {if $battle.bias_user_id == $userId}

	      {* チーム対戦チケットの入手 *}
	      {if $result.gain.ticket}
	        {item_image id=99002 float='left'}
	        <span style="text-decoration:blink">本日初ﾊﾞﾄﾙ</span><br />
	        <span style="color:{#termColor#}">ﾁｰﾑ対戦ﾁｹｯﾄ</span>をｹﾞｯﾄ<br />
	        <a href="{url_for action='TeamList'}" class="buttonlike next">ﾁｰﾑ対戦⇒</a>
	        <br clear="all" /><div style="clear:both"></div>
	      {/if}

	      {* キャラの昇格／降格表示 *}
	      {include file='include/gradeup.tpl' beforeId=`$ready.grade_id` afterId=`$result.character.grade_id`}

	      {* キャラのレベルアップ表示 *}
	      {include file='include/levelup.tpl' before=`$ready` after=`$result.character`}

	      {* モンスターキャプチャーの表示 *}
	      {if $capture}
		    {fieldset color='black' width='90%'}
			  <legend>図鑑ｷｬﾌﾟﾁｬｰ</legend>
	          <div style="text-align:center"><a href="{url_for action='MonsterDetail' id=`$capture.character_id` _backto=true}">{item_image id=`$capture.character_id` cat='monster'}</a></div>
		    {/fieldset}
			 </br>
	      {/if}

	      {* 獲得アイテムの表示 *}
	      {if $result.gain.uitem}
			  {fieldset color='black' width='90%'}
			  <legend>ｱｲﾃﾑﾄﾞﾛｯﾌﾟ</legend>
		        {foreach from=`$result.gain.uitem` item="item"}
				  {fieldset color='black' width='95%'}
				  <legend>{$item.item_name}</legend>
					{item_image id=`$item.item_id` float='left'}
		          	{include file='include/itemSpec.tpl' item=`$item` style='compact'}
			      {/fieldset}
		        {/foreach}
		      {/fieldset}
			  </br>
	      {/if}


		  {fieldset color='black' width='90%'}
		  <legend>装備品</legend>

	      {* 装備品の消耗・レベルアップ *}
	      {foreach from=`$result.equip.before` key='index' item="before"}
	        {assign var='after' value=`$result.equip.after.$index`}

		    {fieldset color='black' width='95%'}
		    <legend>{$before.item_name} Lv<span style="color:{#statusValueColor#}">{$after.level}</span>{item_level_max uitem=`$after`}</legend>

	        <div style="text-align:right">
	          {if $after}

	            {* Lvアップ、耐久値の変化、修理リンク *}
	            {if $before.level != $after.level}<span style="text-decoration:blink">Lv<span style="color:{#statusValueColor#}; text-decoration:blink">{$after.level}</span>にUP</span>{/if}
	            耐久<span style="color:{#statusValueColor#}">{$before.durable_count|durability}</span>{if $before.durable_count != $after.durable_count}⇒<span style="color:{#statusValueColor#}">{$after.durable_count|durability}</span>{/if}
	            {if $after.repaire_useto}{if $holdRecover}{else}{charge_mark}{/if}<a href="{url_for action='Suggest' type='repaire' targetId=`$before.user_item_id` useto=`$after.repaire_useto` _backto=true}" class="buttonlike next">修理</a>{/if}
	            <br />

	            {* 修理して戻ってきている場合は、その旨表示する *}
	            {if array_key_exists('repaire', $after)}
	              <span style="color:{#resultColor#}">修理しました</span>⇒<span style="color:{#statusValueColor#}">{$after.repaire|durability}</span><br />
	            {/if}

	            {* レベルアップしている場合はどのようにアップしたのかを表示する *}
	            {if $before.level != $after.level}
	              <div style="text-align:left">
	                <table align="center">
	                  <tr>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_att1.gif'}{if $after.attack1 != $before.attack1}<span style="color:{#statusValueColor#}">{$after.attack1-$before.attack1|abs}</span>{if $before.attack1 < $after.attack1}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_att2.gif'}{if $after.attack2 != $before.attack2}<span style="color:{#statusValueColor#}">{$after.attack2-$before.attack2|abs}</span>{if $before.attack2 < $after.attack2}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_att3.gif'}{if $after.attack3 != $before.attack3}<span style="color:{#statusValueColor#}">{$after.attack3-$before.attack3|abs}</span>{if $before.attack3 < $after.attack3}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_speed.gif'}{if $after.speed != $before.speed}<span style="color:{#statusValueColor#}">{$after.speed-$before.speed|abs}</span>{if $before.speed < $after.speed}{else}{/if}{else}--{/if}</span></td>
	                  </tr>
	                  <tr>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_def1.gif'}{if $after.defence1 != $before.defence1}<span style="color:{#statusValueColor#}">{$after.defence1-$before.defence1|abs}</span>{if $before.defence1 < $after.defence1}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_def2.gif'}{if $after.defence2 != $before.defence2}<span style="color:{#statusValueColor#}">{$after.defence2-$before.defence2|abs}</span>{if $before.defence2 < $after.defence2}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_def3.gif'}{if $after.defence3 != $before.defence3}<span style="color:{#statusValueColor#}">{$after.defence3-$before.defence3|abs}</span>{if $before.defence3 < $after.defence3}{else}{/if}{else}--{/if}</span></td>
	                    <td>{if $before.defenceX != 0 || $after.defenceX != 0}<span style="font-size:{$css_small}">{image_tag file='picon_defX.gif'}{if $after.defenceX != $before.defenceX}<span style="color:{#statusValueColor#}">{$after.defenceX-$before.defenceX|abs}</span>{if $before.defenceX < $after.defenceX}{else}{/if}{else}--{/if}</span>{/if}</td>
	                  </tr>
	                </table>
	              </div>
	            {/if}

	          {else}
	            <span style="color:{#strongColor#}; text-decoration:blink">壊れました</span><br />
	          {/if}
	        </div>
	        {/fieldset}
	      {/foreach}
	      {/fieldset}

	      {* アイテム効果の期限 *}
	      {include file='include/effectExpires.tpl'}
	    {/if}

	{else}
	    {* バトルサマリ(バトル情報とか獲得経験値とか) *}
	    {include file='include/battleSummary.tpl' battle=`$battle` current=`$current`}

	    {* 自分のキャラについて表示しているなら表示する *}
	    {if $battle.bias_user_id == $userId}

	      {* チーム対戦チケットの入手 *}
	      {if $result.gain.ticket}
	        {item_image id=99002 float='left'}
	        <span style="text-decoration:blink">本日初ﾊﾞﾄﾙ</span><br />
	        <span style="color:{#termColor#}">ﾁｰﾑ対戦ﾁｹｯﾄ</span>をｹﾞｯﾄ<br />
	        <a href="{url_for action='TeamList'}">ﾁｰﾑ対戦⇒</a>
	        <br clear="all" /><div style="clear:both"></div>
	      {/if}

	      {* キャラの昇格／降格表示 *}
	      {include file='include/gradeup.tpl' beforeId=`$ready.grade_id` afterId=`$result.character.grade_id`}

	      {* キャラのレベルアップ表示 *}
	      {include file='include/levelup.tpl' before=`$ready` after=`$result.character`}

	      {* モンスターキャプチャーの表示 *}
	      {if $capture}
	        <div style="background-color:{#subBgColor#}; text-align:center">図鑑ｷｬﾌﾟﾁｬｰ</div>
	        <div style="text-align:center"><a href="{url_for action='MonsterDetail' id=`$capture.character_id` _backto=true}">{item_image id=`$capture.character_id` cat='monster'}</a></div>
	      {/if}

	      {* 獲得アイテムの表示 *}
	      {if $result.gain.uitem}
	        <div style="background-color:{#subBgColor#}; text-align:center">ｱｲﾃﾑﾄﾞﾛｯﾌﾟ</div>
	        {foreach from=`$result.gain.uitem` item="item"}
	          {item_image id=`$item.item_id` float='left'}
	          <span style="color:{#termColor#}">{$item.item_name}</span><br />
	          {include file='include/itemSpec.tpl' item=`$item` style='compact'}
	        {/foreach}
	      {/if}

	      {* 装備品の消耗・レベルアップ *}
	      {foreach from=`$result.equip.before` key='index' item="before"}
	        {assign var='after' value=`$result.equip.after.$index`}

	        <div style="background-color:{#subBgColor#}">
	          {$before.item_name} Lv<span style="color:{#statusValueColor#}">{$after.level}</span>{item_level_max uitem=`$after`}
	        </div>
	        <div style="text-align:right">
	          {if $after}

	            {* Lvアップ、耐久値の変化、修理リンク *}
	            {if $before.level != $after.level}<span style="text-decoration:blink">Lv<span style="color:{#statusValueColor#}; text-decoration:blink">{$after.level}</span>にUP</span>{/if}
	            耐久<span style="color:{#statusValueColor#}">{$before.durable_count|durability}</span>{if $before.durable_count != $after.durable_count}⇒<span style="color:{#statusValueColor#}">{$after.durable_count|durability}</span>{/if}
	            {if $after.repaire_useto}{if $holdRecover}{else}{charge_mark}{/if}<a href="{url_for action='Suggest' type='repaire' targetId=`$before.user_item_id` useto=`$after.repaire_useto` _backto=true}">修理</a>{/if}
	            <br />

	            {* 修理して戻ってきている場合は、その旨表示する *}
	            {if array_key_exists('repaire', $after)}
	              <span style="color:{#resultColor#}">修理しました</span>⇒<span style="color:{#statusValueColor#}">{$after.repaire|durability}</span><br />
	            {/if}

	            {* レベルアップしている場合はどのようにアップしたのかを表示する *}
	            {if $before.level != $after.level}
	              <div style="text-align:left">
	                <table align="center">
	                  <tr>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_att1.gif'}{if $after.attack1 != $before.attack1}<span style="color:{#statusValueColor#}">{$after.attack1-$before.attack1|abs}</span>{if $before.attack1 < $after.attack1}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_att2.gif'}{if $after.attack2 != $before.attack2}<span style="color:{#statusValueColor#}">{$after.attack2-$before.attack2|abs}</span>{if $before.attack2 < $after.attack2}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_att3.gif'}{if $after.attack3 != $before.attack3}<span style="color:{#statusValueColor#}">{$after.attack3-$before.attack3|abs}</span>{if $before.attack3 < $after.attack3}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_speed.gif'}{if $after.speed != $before.speed}<span style="color:{#statusValueColor#}">{$after.speed-$before.speed|abs}</span>{if $before.speed < $after.speed}{else}{/if}{else}--{/if}</span></td>
	                  </tr>
	                  <tr>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_def1.gif'}{if $after.defence1 != $before.defence1}<span style="color:{#statusValueColor#}">{$after.defence1-$before.defence1|abs}</span>{if $before.defence1 < $after.defence1}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_def2.gif'}{if $after.defence2 != $before.defence2}<span style="color:{#statusValueColor#}">{$after.defence2-$before.defence2|abs}</span>{if $before.defence2 < $after.defence2}{else}{/if}{else}--{/if}</span></td>
	                    <td><span style="font-size:{$css_small}">{image_tag file='picon_def3.gif'}{if $after.defence3 != $before.defence3}<span style="color:{#statusValueColor#}">{$after.defence3-$before.defence3|abs}</span>{if $before.defence3 < $after.defence3}{else}{/if}{else}--{/if}</span></td>
	                    <td>{if $before.defenceX != 0 || $after.defenceX != 0}<span style="font-size:{$css_small}">{image_tag file='picon_defX.gif'}{if $after.defenceX != $before.defenceX}<span style="color:{#statusValueColor#}">{$after.defenceX-$before.defenceX|abs}</span>{if $before.defenceX < $after.defenceX}{else}{/if}{else}--{/if}</span>{/if}</td>
	                  </tr>
	                </table>
	              </div>
	            {/if}

	          {else}
	            <span style="color:{#strongColor#}; text-decoration:blink">壊れました</span><br />
	          {/if}
	        </div>
	      {/foreach}

	      {* アイテム効果の期限 *}
	      {include file='include/effectExpires.tpl'}
	    {/if}
	{/if}

    {* チュートリアルならメッセージ表示 *}
    {if $battle.player_id == $userId  &&  $userInfo.tutorial_step == constant('User_InfoService::TUTORIAL_RIVAL')}
      {image_tag file='hr.gif'}<br />
      {image_tag file='navi_mini.gif' float='left'}
      {switch value=`$battle.bias_status`
        win='仕留めちゃうとは大したものなのだ｡驚いたのだ'
        lose='ま､最初はそんなもんなのだ'
        draw='随分珍しいことしてるのだ…'
        timeup='大抵､一回でｹﾘがつくのは珍しいのだ'
      }<br />
      ﾊﾞﾄﾙにはｺﾒﾝﾄつけることができるのだ｡べつに付けなくてもいいのだ｡相手を励ましてもいいし､ｳﾗﾐﾂﾗﾐを書き込んでもいいのだ<br />
      じじぃの家に戻るなら<a href="{url_for module='Swf' action='Tutorial' done='Rival'}" class="buttonlike next">ｺｺ</a>をｸﾘｯｸなのだ
      <br clear="all" /><div style="clear:both"></div>
    {/if}
  {/if}

<!--
  {* バトルのコメント *}
  {if $battle.player_id == $userId  &&  $battle.tournament_id != constant('Tournament_MasterService::TOUR_QUEST')}
    {image_tag file='hr.gif'}<br />
    <form method="post" action="{url_for _self=true}">

      {image_tag file='comment.gif' float='left'}
      どんなﾊﾞﾄﾙでしたか?<br />(相手にも見えます)
      <br clear="all" /><div style="clear:both"></div>

      {if $error}<div style="color:{#errorColor#}">{$error}</div>{/if}
      <table style="width:100%"><tr>
        <td><span style="font-size:{$css_small}">{form_input name="comment" initial=`$battle.comment` }</span></td>
        <td><span style="font-size:{$css_small}"><input type="submit" value="{if $carrier != 'android'}{else}書き込む{/if}" /></span></td>
      </tr></table>
      <span style="color:{#noticeColor#}">本名､ﾒｱﾄﾞなど個人の特定ができる情報は入力NGです</span><br />
    </form>

  {elseif $battle.comment}
    {image_tag file='comment.gif' float='left'}
    {$battle.comment|nl2br}<br clear="all" /><div style="clear:both"></div>
  {/if}
-->

{/if}


<br />
{if $smarty.get.backto}
  <a href="{backto_url}" accesskey="5" class="buttonlike back">戻る</a><br />
{elseif $battle.tournament_id == constant('Tournament_MasterService::TOUR_QUEST')}
  <div style="text-align:center">
    <a href="{url_for module='Swf' action='Sphere' id=`$battle.relate_id` _nocache=true}" accesskey="5" class="buttonlike next">ﾌｨｰﾙﾄﾞへ</a><br />
  </div>
{else}
  <a href="{url_for module='User' action='RivalList' tourId=`$battle.tournament_id`}" accesskey="5" class="buttonlike next">対戦相手一覧へ</a><br />
  <a href="{url_for module='User' action='HisPage' userId=`$battle.rival_user_id`}" class="buttonlike next">←対戦相手のページへ</a><br />
  <br />
  {foreach from=`$neighborGrades` item='grade'}
    <a href="{url_for module='User' action='GradeDetail' gradeId=`$grade.grade_id` _backto=true}" class="buttonlike label">{$grade.grade_name}の一覧</a><br />
  {/foreach}
{/if}

{include file="include/footer.tpl"}
