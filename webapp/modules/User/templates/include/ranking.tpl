{*
    ユーザランキングを表示するテンプレート。
    パラメータ)
        type          ランキング種別。Ranking_LogServiceの定数を使用する。
        period        ランキング期間。ranking_log.period の値。
        count         1ページに何件表示するか。省略時は10
        page          何ページ目を表示するか。0スタート。省略時はGETパラメータ page から取得。
        pagerType     ページャのタイプ。現在はnone, neighbors, more のいずれか。
*}
{php}

    $charaSvc = new Character_InfoService();
    $rankSvc = new Ranking_LogService();

    // パラメータのデフォルト値を設定。
    if( is_null($this->get_template_vars('count')) )
        $this->assign('count', 10);
    if( is_null($this->get_template_vars('page')) )
        $this->assign('page', $_GET['page']);

    $list = $rankSvc->getRankingList(
        $this->get_template_vars('type'), $this->get_template_vars('period'),
        $this->get_template_vars('count'), $this->get_template_vars('page')
    );

    // サムネイルURLを一覧の列に追加する。
    Common::embedThumbnailColumn($list['resultset'], 'user_id');

    // ユーザ情報をすべて取得。
    $userIds = ResultsetUtil::colValues($list['resultset'], 'user_id');
    $users = Service::create('User_Info')->getRecordsIn( array_unique($userIds) );

    // 擬似列 "user_name", "avatar", "highest_rank" を追加する。
    foreach($list['resultset'] as &$record) {

        $record['user_name'] = $users[ $record['user_id'] ]['short_name'];

        $record['avatar'] = $charaSvc->needAvatar($record['user_id']);

        $record['highest'] = $rankSvc->getHighestRank($record['user_id']);

    }unset($record);

    // リストをテンプレートにアサイン。
    $this->assign('list', $list);

    // 「もっと見る」のURLをアサイン。
    $this->assign('moreUrl', Common::genContainerURL('User', 'Ranking', array(
        'type' => $this->get_template_vars('type'), '_backto' => true,
    )));

{/php}


{if $list.totalRows > 0}

  {* リスト *}
  {foreach from=`$list.resultset` item="row"}
    {if ($carrier == 'iphone' || $carrier == 'android')}
      {fieldset color='brown' width='95%'}
        <legend>{$row.rank}位</legend>
        <div>
          {platform_thumbnail src=`$row.thumbnail_url` size='M' float='left'}
          {$row.user_name}<br />
          <span style="color:{#statusValueColor#}">{$row.rank}</span>位 <span style="color:{#statusValueColor#}">{$row.point}</span>階級pt<br />
          <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$row.avatar.level}</span> <span style="color:{#statusValueColor#}">{text grade=`$row.avatar.grade_id`}</span><br />
          {if $type==constant('Ranking_LogService::GRADEPT_DAILY')}
            日別ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$row.highest.daily|default:'--'}</span>位<br />
          {else}
            週間ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$row.highest.weekly|default:'--'}</span>位<br />
          {/if}
          <br clear="all" /><div style="clear:both"></div>

          <div style="text-align:right"><a href="{url_for module='User' action='HisPage' userId=`$row.user_id` _backto=true}" class="buttonlike next">プロフ	を見る</a></div>
        </div>

      {/fieldset}<br />

    {else}
      {platform_thumbnail src=`$row.thumbnail_url` size='M' float='left'}
      <a href="{url_for module='User' action='HisPage' userId=`$row.user_id` _backto=true}">{$row.user_name}</a><br />
      <span style="color:{#statusValueColor#}">{$row.rank}</span>位 <span style="color:{#statusValueColor#}">{$row.point}</span>階級pt<br />
      <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$row.avatar.level}</span> <span style="color:{#statusValueColor#}">{text grade=`$row.avatar.grade_id`}</span><br />
      {if $type==constant('Ranking_LogService::GRADEPT_DAILY')}
        日別ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$row.highest.daily|default:'--'}</span>位<br />
      {else}
        週間ﾗﾝｷﾝｸﾞ最高<span style="color:{#statusValueColor#}">{$row.highest.weekly|default:'--'}</span>位<br />
      {/if}
      <br clear="all" /><div style="clear:both"></div>
    {/if}
  {/foreach}

  {* ページャ *}
  {include file="include/pager.tpl" totalPages=`$list.totalPages` current=`$page` type=`$pagerType` moreUrl=`$moreUrl`}

{else}
  <br />
  <div style="text-align:center">まだ集計されていません</div>
{/if}
