{*
    ユーザの履歴を表示するテンプレート。

    パラメータ)
        type          リスト種別。以下のいずれか。
                          comment   コメントの履歴
                          history   コメント以外の履歴
                          team      チーム対戦の履歴
        targetId      履歴の基準になるユーザID。
        count         1ページに何件表示するか。省略時は10
        page          何ページ目を表示するか。0スタート。省略時はGETパラメータ page から取得。
        pagerType     ページャのタイプ。現在はnone, neighbors, more のいずれか。
*}
{php}

    // パラメータのデフォルト値を設定。
    if( is_null($this->get_template_vars('count')) )
        $this->assign('count', 10);
    if( is_null($this->get_template_vars('page')) )
        $this->assign('page', $_GET['page']);

    // 指定された履歴を取得。
    $list = Service::create('History_Log')->getUserHistory(
        $this->get_template_vars('targetId'),
        $this->get_template_vars('type'),
        $this->get_template_vars('count'),
        $this->get_template_vars('page')
    );

    // リストをテンプレートにアサイン。
    $this->assign('list', $list);

    // 「もっと見る」のURLをアサイン。
    $this->assign('moreUrl', Common::genContainerURL('User', 'HistoryList', array(
        'userId' => $this->get_template_vars('targetId'),
        'type' =>   $this->get_template_vars('type'),
        '_backto' => true,
    )));

{/php}


{if $list.totalRows > 0}

  {* リスト *}
  {foreach from=`$list.resultset` item="row"}
    {include file='include/historyView.tpl' history=`$row` compact=true}
    {image_tag file='hr.gif'}<br />
  {/foreach}

  {* ページャ *}
  {include file="include/pager.tpl" totalPages=`$list.totalPages` current=`$page` type=`$pagerType` moreUrl=`$moreUrl`}

{else}
  <br />
  <div style="text-align:center">まだありません。</div>
{/if}
