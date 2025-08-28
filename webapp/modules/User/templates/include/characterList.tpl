{*
    キャラクターの一覧を表示するテンプレート。
    パラメータ)
        list        キャラクターの一覧
        params      他者のページへのリンクに追加するパラメータ。
        withMember  仲間数を表示するかどうか
*}
{php}

    // backto パラメータが指定されていないなら自動的に取得。
    $params = $this->get_template_vars('params');
    if( is_null($params['backto']) )
        $params['backto'] = ViewUtil::serializeBackto();
    $this->assign('params', $params);

    // リストを一旦取得。
    $list = $this->get_template_vars('list');

    // ユーザIDをすべて取得。
    $userIds = ResultsetUtil::colValues($list, 'user_id');

    // ユーザ情報を取得して、一覧にユーザ情報を埋め込む。
    $users = Service::create('User_Info')->getRecordsIn($userIds);
    foreach($list as &$record)
        $record['user'] = $users[ $record['user_id'] ];
    unset($record);

    // "withMember" が指定されているなら仲間数を取得。
    if( $this->get_template_vars('withMember') )
        $this->assign('member', Service::create('User_Member')->getMemberCount($userIds));

    // 一覧をアサインしなおす。
    $this->assign('list', $list);
{/php}


{if ($list|@count) > 0}

  {* リスト表示 *}
  {image_tag file='hr.gif'}<br />
  {foreach from=`$list` item="chara"}
    {if ($carrier == 'iphone' || $carrier == 'android')}
      {fieldset color='brown' width='95%'}
        <legend>{$chara.user.short_name}</legend>
        <div>
          {chara_img chara=`$chara` size='nail' float='left'}
          {text id=`$chara.name_id`}
          {if $withMember}
            <span style="color:{#statusNameColor#}">仲間数</span><span style="color:{#statusValueColor#}">{$member[$chara.user_id]|int}</span>
          {/if}<br />
          <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$chara.level}</span> <span style="color:{#statusValueColor#}">{text grade=`$chara.grade_id`}</span>
          <br clear="all" /><div style="clear:both"></div>
          {include file='include/characterStatus.tpl' chara=`$chara`}
          <div style="text-align:right"><a href="{url_for module='User' action='HisPage' userId=`$chara.user_id` _params=`$params`}" class="buttonlike next">対戦する</a></div>
        </div>

      {/fieldset}<br />

    {else}
	  {* なぜか size='nail' を指定すると本番環境のガラケーのみ表示が崩れる *}
      {chara_img chara=`$chara` float='left'}
      <a href="{url_for module='User' action='HisPage' userId=`$chara.user_id` _params=`$params`}">{$chara.user.short_name}</a><br />
      {text id=`$chara.name_id`}
      {if $withMember}
        <span style="color:{#statusNameColor#}">仲間数</span><span style="color:{#statusValueColor#}">{$member[$chara.user_id]|int}</span>
      {/if}<br />
      <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$chara.level}</span> <span style="color:{#statusValueColor#}">{text grade=`$chara.grade_id`}</span>
      <br clear="all" /><div style="clear:both"></div>

      {include file='include/characterStatus.tpl' chara=`$chara`}

      {image_tag file='hr.gif'}<br />
    {/if}

  {/foreach}

{else}
  <br />
  <div style="text-align:center">まだいません。</div>
  <br />
{/if}
