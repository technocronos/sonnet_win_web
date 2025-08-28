{*
    ユーザのメッセージ一覧を表示するテンプレート。
    パラメータ)
        userId      メッセージの持ち主のユーザID。省略時は現在アクセス中のユーザのID。
        type        受信一覧なら "receive"、送信一覧なら "send"。省略時は "receive"。
        count       何件表示するか。省略時は10
        page        何ページ目を表示するか。0スタート。省略時はGETパラメータ page から取得。
        pagerType   ページャのタイプ。現在はnone, neighbors, more のいずれか。
*}
{php}
    // パラメータのデフォルト値を設定。
    if( is_null($this->get_template_vars('type')) )
        $this->assign('type', 'receive');
    if( is_null($this->get_template_vars('count')) )
        $this->assign('count', 10);
    if( is_null($this->get_template_vars('page')) )
        $this->assign('page', (int)$_GET['page'] );

    // 指定されたユーザのメッセージ一覧を取得。
    $methodName = ($this->get_template_vars('type') == 'send') ? 'getSendList' : 'getReceiveList';
    $messageSvc = new Message_LogService();
    $list = $messageSvc->$methodName(
        $this->get_template_vars('userId'),
        $this->get_template_vars('count'),
        $this->get_template_vars('page')
    );

    // メッセージ相手のユーザID列の名前を取得。
    $companionCol = ($this->get_template_vars('type') == 'send') ? 'receive_user_id' : 'send_user_id';
    $this->assign('companionCol', $companionCol);

    // メッセージ相手のアバターURLを一覧の列に追加する。
    Common::embedThumbnailColumn($list['resultset'], $companionCol);

    // メッセージ相手のユーザ情報をまとめて取得。
    $companionIds = array_unique(ResultsetUtil::colValues($list['resultset'], $companionCol));
    $companions = Service::create('User_Info')->getRecordsIn($companionIds);

    // メッセージ相手のユーザ名を擬似列 "comanion_name" として埋め込む。
    foreach($list['resultset'] as &$record) {
        $record['comanion_name'] = $companions[ $record[$companionCol] ]['short_name'];
    }unset($record);

    // 一覧をアサイン。
    $this->assign('list', $list);

    // 「もっと見る」のURLをアサイン。
    $this->assign('moreUrl', Common::genContainerURL('User', 'MessageList', array(
        'userId' => $this->get_template_vars('userId'),
        'type' => $this->get_template_vars('type'),
        '_backto' => true,
    )));
{/php}


{if $list.totalRows > 0}

  {* リスト表示 *}
  {foreach from=`$list.resultset` item="row"}

    {platform_thumbnail src=`$row.thumbnail_url` size='M' float='left'}

    {$row.create_at|datetime}
    <a href="{url_for module='User' action='HisPage' userId=`$row[$companionCol]` _backto=true}">{$row.comanion_name}</a>
    {if $row.favor_flg}<br /><span style="color:{#statusNameColor#}">階級pt</span><span style="color:{#statusValueColor#}">+1</span>{/if}
    <br clear="all" /><div style="clear:both"></div>

    {* 本文 *}
    {$row.body|nl2br}
    <br />

    {image_tag file='hr.gif'}<br />
  {/foreach}

  {* ページャ表示 *}
  {include file="include/pager.tpl" totalPages=`$list.totalPages` current=`$page` type=`$pagerType` moreUrl=`$moreUrl`}

{else}
  <br />
  <div style="text-align:center">まだありません。</div>
  <br />
{/if}
