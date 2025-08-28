<?php

class TeamBattleAction extends UserBaseAction {


    public function execute() {

        $charaSvc = new Character_InfoService();

        // 双方のキャラクタIDを取得。
        $chara1Id = $charaSvc->needAvatarId($this->user_id);
        $chara2Id = $_GET['rivalId'];

        // チケットアイテムの情報を取得しておく。
        $this->setAttribute('ticket', Service::create('Item_Master')->needRecord(TeamBattleUtil::TICKET_ID));

        // 相手の情報を取得しておく。
        $rivalChara = $charaSvc->needRecord($_GET['rivalId']);
        $this->setAttribute('rival', Service::create('User_Info')->needRecord($rivalChara['user_id']));

        // チームバトルできるかどうかを取得。
        $canBattle = TeamBattleUtil::canBattle($chara1Id, $chara2Id);
        $this->setAttribute('canBattle', $canBattle);

        // バトルできないなら...以降の処理は不要。
        if($canBattle != 'ok')
            return 'Member';

        // フォームが送信されているなら開始処理。制御は戻ってこない。
        if($_POST)
            $this->processStart($chara1Id, $chara2Id);

        // メンバが選択されている場合...
        if($_GET['member1']  &&  $_GET['member2']) {

            // チケットアイテムの所持数をビューにセット。
            $this->setAttribute('ticketCount',
                Service::create('User_Item')->getHoldCount($this->user_id, TeamBattleUtil::TICKET_ID)
            );

            // ルームの一覧を取得。
            $define = Service::create('Field_Master')->needRecord(99998);
            $this->setAttribute('rooms', $define['rooms']);

            // ルーム選択のビューへ。
            return 'Field';

        // メンバがまだ選択されていない場合...
        }else {

            // アクセス中のユーザの仲間リストを取得。
            $list = Service::create('User_Member')->getMemberList($this->user_id, 10, $_GET['page']);

            // 各仲間のサムネイルURLを取得してリストに入れる。
            AppUtil::embedUserThumbnail($list['resultset']);

            // 各仲間のキャラクター情報を取得してリストに入れる。
            foreach($list['resultset'] as &$record) {
                $record['character'] = $charaSvc->needAvatar($record['user_id']);
            }unset($record);

            // リストをビューに割り当てる
            $this->setAttribute('list', $list);

            // 何人目を選択しようとしているのかを取得。
            $memberCount = $_GET['member1'] ? 2 : 1;
            $this->setAttribute('memberCount', $memberCount);

            // 仲間リストの名前選択時のURLを作成。
            $this->setAttribute('selectLink', Common::genContainerURL(array('_self'=>true, 'page'=>0, "member{$memberCount}"=>'_id_')));

            // 本日の日付を作成。
            $this->setAttribute('today', date('Y-m-d'));

            // 仲間選択のビューへ。
            return 'Member';
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * チーム対戦の開始処理を行う。
     * リダイレクトするので制御は戻ってこない。
     */
    private function processStart($chara1Id) {

        // チーム対戦チケットを1枚消費
        $uitemSvc = new User_ItemService();
        $ticketRecord = $uitemSvc->getRecordByItemId($this->user_id, TeamBattleUtil::TICKET_ID);
        $uitemSvc->consumeItem($ticketRecord['user_item_id']);

        // 選択メンバにマークを付ける。
        $memberSvc = new User_MemberService();
        $memberSvc->markCooperation($this->user_id, $_GET['member1']);
        $memberSvc->markCooperation($this->user_id, $_GET['member2']);

        // 相手の情報を取得しておく。
        $rivalChara = Service::create('Character_Info')->needRecord($_GET['rivalId']);

        // 指定されたクエストをロード。
        $questObj = QuestCommon::factory(Quest_MasterService::TEAM_BATTLE, $this->user_id);

        // クエスト開始時に渡すパラメータを作成。
        $initialize = array(
            'roomNo' => $_POST['roomNo'],
            'friends' => array(1=>$_GET['member1'], 2=>$_GET['member2']),
            'rival' => $rivalChara['user_id'],
        );

        // スフィアを作成。IDを得る。
        $sphereId = $questObj->startField($chara1Id, array(), $initialize);

        // フィールドフラッシュへリダイレクトする。
        Common::redirect('Swf', 'Sphere', array('id'=>$sphereId));
    }
}
