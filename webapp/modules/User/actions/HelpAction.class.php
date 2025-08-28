<?php

class HelpAction extends UserBaseAction {

    public function execute() {

        // userId パラメータが付いている場合に、それが自分のIDでない場合は、
        // 他者ページに飛ばす。
        // これはモバゲにおいて、ヘルプ「自分ﾍﾟｰｼﾞへﾘﾝｸ」で「このページでブクマをとれ」と
        // 案内している(このときGET変数"userId"が必ず付いている。Common::normalizeUrlParamsを参照)ため。
        // そのブクマから他ユーザが来ると、上記のようなことが起こる仕組み。
        if(isset($_GET['userId'])  &&  $_GET['userId'] != $this->user_id)
            Common::redirect('User', 'HisPage', array('userId'=>$_GET['userId']));

        // 招待後に戻ってきている場合はその処理。
        if($_GET['id'] == 'invite')
            $this->processInvite();

        // 項目が指定されている場合。
        if( !empty($_GET['id']) ) {
            $this->executeContent();
            return 'Content';

        // 項目が指定されていない場合。
        }else {
            $this->executeList();
            return 'List';
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたヘルプの表示準備を行う。
     */
    private function executeContent() {

        // 指定されている項目を取得。
        $help = Service::create('Help_Master')->needRecord($_GET['id']);
        $this->setAttribute('help', $help);

        // リンク方法の場合はリンク用URLを取得。
        if($_GET['id'] == 'other-link') {
            $this->setAttribute('url',
                Common::genContainerUrl('User', 'HisPage', array('userId'=>$this->user_id), true)
            );
        }

        // 友達招待の場合は...
        if($_GET['id'] == 'other-shoutai') {

            $itemSvc = new Item_MasterService();

            // 招待用URLを取得。
            $this->setAttribute('url', PlatformApi::getInvitationUrl(array(
                'finish' =>  Common::genURL('User', 'Help', array('id'=>'invite', 'backto'=>$_GET['backto']), true),
                'subject' => SITE_NAME."友だち招待",
                'body' =>    SITE_NAME."で一緒に遊ぼう!",
            )));

            // 特典のアイテムIDをテンプレートで使えるようにする。
            $this->setAttribute( 'ibonus', $itemSvc->getRecordsIn(array_keys(Invitation_LogService::$INVITE_BONUS)) );
            $this->setAttribute('ibonusNum', Invitation_LogService::$INVITE_BONUS);

            // 特典のアイテムIDをテンプレートで使えるようにする。
            $this->setAttribute( 'abonus', $itemSvc->getRecordsIn(array_keys(Invitation_LogService::$ANSWER_BONUS)) );
            $this->setAttribute('abonusNum', Invitation_LogService::$ANSWER_BONUS);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ヘルプ一覧の表示準備を行う。
     */
    private function executeList() {

        // 現在のユーザのレベルを取得。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        $this->setAttribute('avatar', $avatar);

        // レベルから、アクセスできるヘルプを取得。
        $helps = Service::create('Help_Master')->getList($avatar['level']);

        // キー: group_id
        // 値:   そのグループに属するヘルプの序数配列
        // …になるような配列を作成する。
        $helpTree = array();
        foreach($helps as $help) {

            if( !array_key_exists($help['group_id'], $helpTree) )
                $helpTree[ $help['group_id'] ] = array();

            $helpTree[ $help['group_id'] ][] = $help;
        }

        $this->setAttribute('helpTree', $helpTree);

        // 項目グループの一覧をビューでも使えるようにする。
        $this->setAttribute('groups', Help_MasterService::$GROUPS);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 友達招待して戻ってきている場合の処理を行う。
     */
    private function processInvite() {

        // 招待されたユーザのID一覧を取得。
        $recipientIds = PlatformApi::parseInvitation();

        // 招待されたユーザ一人ひとりに対して招待レコードを作成する。
        foreach($recipientIds as $id)
            Service::create('Invitation_Log')->makeInvitation($this->user_id, $id);

        // 結果画面へ。
        Common::redirect('User', 'Static', array('id'=>'Shoutai', 'backto'=>$_GET['backto']));
    }
}
