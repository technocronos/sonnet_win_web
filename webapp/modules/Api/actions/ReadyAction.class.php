<?php

/**
 * 「対戦相手リスト」を処理するアクション。
 */
class ReadyAction extends SmfBaseAction {

    const RECOV_CARRY = 6;
    const ATTAC_CARRY = 6;
    const EQUIP_CARRY = 2;

    protected function doExecute($params) {
        $array = array();

        // すでにフィールドクエストに出ている場合はこの画面は表示不可。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        if($avatar['sally_sphere']){
            $array['result'] = 'ok';
            $array['Api'] = 'FieldReopen';
            return $array;
        }

        // 一番最初のクエスト(精霊の洞窟)の場合、この画面はスキップする。
        if($_GET['questId'] == 11001)
            $_POST['slot0'] = '';

        //消費APが0のクエは準備必要なし
        if($_GET['consume_pt'] == 0)
            $_POST['slot0'] = '';

        // フィールド定義があるかどうかチェック。ないならエラー画面へ。
        if( !Service::create('Field_Master')->getRecord($_GET['questId']) ){
            $array['result'] = 'error';
            $array['err_code'] = 'error_field_access';
            return $array;
        }

        // 以降、画面表示時の処理。
        if($_GET['placeId']){
            $array = $this->processMove();
        }

        // フォームが送信されている場合はスフィアを作成
        if($_POST){
            $array = $this->processPost();
            $array['result'] = 'ok';
            return $array;
        }

        // アイテムの一覧を取得。
        $array['item'] = $this->getItemList();

        // 開始時のセリフをセット。
        $array['comment'] = $this->setOpenComment();

        $array['result'] = 'ok';

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムの一覧を返す。
     */
    private function getItemList() {

        $equipmSvc = new Equippable_MasterService();
        $cequipSvc = new Character_EquipmentService();

        // 戻り値初期化。
        $result = array('RCV'=>array(), 'ATT'=>array(), 'WPN'=>array(), 'BOD'=>array(), 'HED'=>array(), 'ACS'=>array());

        // ユーザのアバターIDを取得。
        $avatarId = Service::create('Character_Info')->needAvatarId($this->user_id);

        // ユーザが持っているアイテムをすべて取得。
        $condition = array(
            'user_id' => $this->user_id,
            'category' => array('ITM', 'WPN', 'BOD', 'HED', 'ACS'),
        );
        $list = Service::create('User_Item')->getHoldList($condition, 999, 0);

        // 一つずつ見ていく。
        foreach($list['resultset'] as $record) {

            // 消費アイテムのうち、フィールドで使えないものは除外。
            if($record['category'] == 'ITM'  &&  !in_array($record['item_type'], Item_MasterService::$ON_FIELD))
                continue;

            // 装備しているものは除外
            if(Item_MasterService::isDurable($record['category'])  &&  $record['free_count'] <= 0)
                continue;


            // アイテム性能文字列の作成。消費アイテムか装備品かで形式が違う。
            if($record['category'] == 'ITM') {

            }else {

                $mountId = $equipmSvc->getMount('PLA', $record['item_id']);
                $equippable = $cequipSvc->isEquippable($avatarId, $mountId, $record['user_item_id'], true);
                $record["equippable"] = $equippable;

                //MAXレベル
                $maxLv = Service::create('Item_Level_Master')->getMaxLevel($record['item_id']);
                $record['max_level'] = $maxLv;
            }

            $spec = $record;

            // category ごとに分けて、性能文字列を追加していく。
            $cat = $record['category'];
            if($cat == 'ITM')  $cat = ($record['item_type'] == Item_MasterService::RECV_HP) ? 'RCV' : 'ATT';
            $result[$cat][] = $spec;
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 開始時コメントをセットする。(現在未使用)
     */
    private function setOpenComment() {

        $comments = array();

        // コメント欄の幅は半角28程度。3行。

        // 最初の水汲みクエストで...
        if($_GET['questId'] == 11002) {

            // それをまだクリアしていない場合はコメントを表示する。
            $cleared = Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->user_id, 11002);
            if(!$cleared) {
                $comments = AppUtil::getTexts("TEXT_NAVI_TUTORIAL_QUEST");
            }
        }

        // セット。
        return $comments;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 持ち出しアイテムの確定を処理する。
     */
    private function processPost() {
        // 指定されたクエストをロード。
        $questObj = QuestCommon::factory($_GET['questId'], $this->user_id);

        // 本当に実行できる状態にあるのかチェック。
        if( !$questObj->isExecutable() ){
            $array["Scene"] = 'Quest';
            return $array;
        }

        // 本当にフィールド型のクエストかチェック。
        if( !($questObj instanceof FieldQuest) )
            throw new MojaviException('クエスト種別が不正');

        // アバターキャラのIDを取得。
        $avatarId = Service::create('Character_Info')->needAvatarId($this->user_id);

        // アイテムスロットを変数 $slot に取得する。
        $slot = $this->makeSlot();

        // クエスト前に再生する寸劇があるか調べる。
        $dramaId = $_GET['questId'] . '00';

        // 寸劇レコードがない、あるいはすでにクエストにトライしたことがあるなら寸劇再生はスキップする。
        if(
                !Service::create('Drama_Master')->getRecord($dramaId)
            ||  Service::create('Flag_Log')->getValue(Flag_LogService::TRY_COUNT, $this->user_id, $_GET['questId'])
        ) {
            // スフィアを作成。IDを得る。
            $sphereId = $questObj->startField($avatarId, $slot);

            // フィールドフラッシュへリダイレクトする。
            //$this->redirect('Swf', 'Sphere', array('id'=>$sphereId));
            $array["Scene"] = 'Sphere';
            $array["id"] = $sphereId;
        }else{
            // スフィアを作成。IDを得る。
            $sphereId = $questObj->startField($avatarId, $slot);

            //前劇がある場合はそちらに飛ばす。
            //$this->redirect('Swf', 'Terminable', array('questId' => $_GET['questId'], 'sphereId' => $sphereId));

            $array["Scene"] = 'Terminable';
            $array["questId"] = $_GET['questId'];
            $array["sphereId"] = $sphereId;
        }

        return $array;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 持ち出すように指定されているアイテムをチェックするとともに、アイテムスロットを表す配列を返す。
     */
    private function makeSlot() {

        // アイテムスロットを変数 $slot に作成、持ち出すアイテムのID配列を $carryIds に作成。
        $slot = array();
        $carryIds = array();
        for($i = 0 ; $i < self::RECOV_CARRY + self::ATTAC_CARRY + self::EQUIP_CARRY ; $i++) {

            $uitemId = $_POST["slot{$i}"];

            $slot[$i] = $uitemId ?: 0;

            if($uitemId)
                $carryIds[] = $uitemId;
        }

        // 持ち出そうとしているアイテムをすべて取得。
        $uitems = Service::create('User_Item')->getRecordsIn($carryIds);

        // 作成したスロットをもう一度見て、チェックを行う。
        $count = array('recov'=>0, 'attac'=>0, 'equip'=>0);
        foreach($slot as $uitemId) {

            // カラスロットは無視。
            if(!$uitemId)
                continue;

            // 所持アイテムレコードを取得。
            $record = $uitems[$uitemId];

            // レコードがないのはエラー。
            if(!$record)
                throw new MojaviException('存在しないアイテムを持ち出そうとした');

            // 他人のアイテムだったらエラー。
            if($record['user_id'] != $this->user_id)
                throw new MojaviException('他人のアイテムを持ち出そうとした');

            // 種別ごとに数を数える。
            if(Item_MasterService::isDurable($record['category']))
                $key = 'equip';
            else if($record['item_type'] == Item_MasterService::RECV_HP)
                $key = 'recov';
            else
                $key = 'attac';

            $count[$key]++;
        }

        // 種別ごとの数が規定を超えているのはエラー。
        if(self::RECOV_CARRY < $count['recov'])
            throw new MojaviException('回復アイテムを規定以上に持ち出そうとした');
        if(self::ATTAC_CARRY < $count['attac'])
            throw new MojaviException('攻撃アイテムを規定以上に持ち出そうとした');
        if(self::EQUIP_CARRY < $count['equip'])
            throw new MojaviException('装備品を規定以上に持ち出そうとした');

        // ここまでくればOK
        return $slot;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 移動している場合の処理。
     */
    private function processMove() {

        $userSvc = new User_InfoService();

        //イベントクエストの場合はスルー
        if($_GET['placeId'] == Quest_MasterService::EVENT_QUEST)
            return;

        // 一応、移動できるかどうかチェック。
        if( !Service::create('Place_Master')->isMovable($this->user_id, $_GET['placeId']) )
            throw new MojaviException('移動できない場所に移動しようとした');

        // 移動チュートリアルを終了させる。
        $userSvc->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_MOVE);

        // 移動。
        $userSvc->movePlace($this->user_id, $_GET['placeId']);

        // 到着イベントがある場合はそちらへ、ないならメインメニューへリダイレクト。
        $arrivalEvent = Service::create('Place_Master')->getEventOnMove($this->user_id, $_GET['placeId']);
        if($arrivalEvent){
            $array["Scene"] = 'QuestDrama';
            $array["questId"] = $arrivalEvent;
            return $array;
        }
    }
}
