<?php

class ReadyAction extends SwfBaseAction {

    const RECOV_CARRY = 6;
    const ATTAC_CARRY = 6;
    const EQUIP_CARRY = 2;


    protected function doExecute() {

        // すでにフィールドクエストに出ている場合はこの画面は表示不可。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        if($avatar['sally_sphere']){
            // 明示的にギブアップが支持されてるならギブアップ処理。制御は戻ってこない。
            if( !empty($_GET['giveup']) ){
                $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
                $this->processGiveup($avatar['sally_sphere']);
            }else{
                Common::redirect('User', 'FieldReopen');
            }
        }

        // 一番最初のクエスト(精霊の洞窟)の場合、この画面はスキップする。
        if($_GET['questId'] == 11001)
            $_POST['slot0'] = '';

        // フィールド定義があるかどうかチェック。ないならエラー画面へ。
        if( !Service::create('Field_Master')->getRecord($_GET['questId']) )
            Common::redirect('User', 'Static', array('id'=>'UnderConstruct'));

        // フォームが送信されている場合はスフィアを作成してそこへリダイレクト。制御は戻ってこない。
        if($_POST)
            $this->processPost();

        // 以降、画面表示時の処理。

        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
            //スマホ版は地点でクエスト実行を縛るのはやめる。この地点で食い違っていたら移動してしまう。
            if($_GET['placeId'])
                $this->processMove();
        }

        // アイテムの一覧を取得。
        $itemList = $this->getItemList();

        // 完了時、キャンセル時のURLをセット。
        $this->replaceStrings['finishUrl'] = Common::genContainerUrl(array('_self'=>true), null, null, true);
        $this->replaceStrings['cancelUrl'] = Common::genContainerUrl(
            'Swf', 'Main', null, true
        );

        // 各アイテムをセット。
        $this->arrayToFlasm('item1_', $itemList['RCV']);
        $this->replaceStrings['item1_Num'] = count($itemList['RCV']);

        $this->arrayToFlasm('item2_', $itemList['ATT']);
        $this->replaceStrings['item2_Num'] = count($itemList['ATT']);

        $this->arrayToFlasm('item3_', $itemList['WPN']);
        $this->replaceStrings['item3_Num'] = count($itemList['WPN']);

        $this->arrayToFlasm('item4_', $itemList['BOD']);
        $this->replaceStrings['item4_Num'] = count($itemList['BOD']);

        $this->arrayToFlasm('item5_', $itemList['HED']);
        $this->replaceStrings['item5_Num'] = count($itemList['HED']);

        $this->arrayToFlasm('item6_', $itemList['ACS']);
        $this->replaceStrings['item6_Num'] = count($itemList['ACS']);

        // 開始時のセリフをセット。
        $this->setOpenComment();

        // 背景差し替え
        //$this->replaceImages[1] = Place_MasterService::getBgImage($this->userInfo['place_id']);

        $this->replaceStrings['TEXT_NOTHING'] = "(なし)";
        $this->replaceStrings['TEXT_RECOVER'] = "回復";
        $this->replaceStrings['TEXT_FORCE'] = "威力";
        $this->replaceStrings['TEXT_HANI'] = "範囲";
        $this->replaceStrings['TEXT_SHATEI'] = "射程";

        $this->replaceStrings['TEXT_NO_LOAD'] = "読み込まれないのだ…\nもっぺん押してみるのだ？";;

        $this->replaceStrings['STR_REC_ITEM'] = "回復アイテム";
        $this->replaceStrings['STR_ATC_ITEM'] = "攻撃アイテム";
        $this->replaceStrings['STR_EQP_ITEM'] = "装備品";

        $this->replaceStrings['STR_ATO'] = "あと";
        $this->replaceStrings['STR_MADE'] = "個まで";

        $this->replaceStrings['STR_MOCHIDASHI'] = "持ち出し";
        $this->replaceStrings['STR_TORIKESHI'] = "取り消し";

        $this->replaceStrings['STR_NO_ITEM'] = "(アイテムがありません)";

        //サウンド設定。効果音はweb_audio_apiを使う
        $this->use_web_audio = array(
            "se_btn",
        );

        //BGMはaudioタグを使う
        $this->use_audio_tag = array(
            "bgm_dungeon",
        );

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
                $spec = sprintf('%010d %02d %02d %02d %04d %04d %s',
                    $record['user_item_id'], $record['item_type'], $record['item_limitation'], $record['item_spread']+1,
                    $record['item_value'], $record['free_count'], $record['item_name']
                );
            }else {

                $mountId = $equipmSvc->getMount('PLA', $record['item_id']);
                $equippable = $cequipSvc->isEquippable($avatarId, $mountId, $record['user_item_id'], true);

                $spec = sprintf('%010d %+05d %+05d %+05d %+05d %+05d %+05d %+05d %+05d %s Lv%d 耐久%d',
                    ($equippable ? $record['user_item_id'] : 0),
                    $record['attack1'], $record['attack2'], $record['attack3'], $record['speed'],
                    $record['defence1'], $record['defence2'], $record['defence3'], $record['defenceX'],
                    $record['item_name'], $record['level'], $record['durable_count']
                );
            }

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
     * 開始時コメントをセットする。
     */
    private function setOpenComment() {

        $comments = array();

        // コメント欄の幅は半角28程度。3行。

        // 最初の水汲みクエストで...
        if($_GET['questId'] == 11002) {

            // それをまだクリアしていない場合はコメントを表示する。
            $cleared = Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->user_id, 11002);
            if(!$cleared) {
                $comments[] = "フィールドクエストに\nでかけるときは、この画面で\n持ってくアイテム選ぶのだ";
                if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
                    $comments[] = "で上下してで\n持ち出すのだ\nで種類を変えるのだ";
                    $comments[] = "決まったら確認の画面から\n出発なのだ";
                }else{
                    $comments[] = "↑↓で上下して○で\n持ち出すのだ\n←→で種類を変えるのだ";
                    $comments[] = "決まったら確認の画面から\n出発なのだ";
                }
                $comments[] = "クエスト終わったら使って\nないやつは回収されるのだ\nだから遠慮なく持ち出すのだ";
            }
        }

        // セット。
        $this->arrayToFlasm('open', $comments);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 持ち出しアイテムの確定を処理する。
     */
    private function processPost() {

        // 指定されたクエストをロード。
        $questObj = QuestCommon::factory($_GET['questId'], $this->user_id);

        // 本当に実行できる状態にあるのかチェック。
        if( !$questObj->isExecutable() )
            Common::redirect('User', 'QuestList');

        // 本当にフィールド型のクエストかチェック。
        if( !($questObj instanceof FieldQuest) )
            throw new MojaviException('クエスト種別が不正');

        // アバターキャラのIDを取得。
        $avatarId = Service::create('Character_Info')->needAvatarId($this->user_id);

        // アイテムスロットを変数 $slot に取得する。
        $slot = $this->makeSlot();

        // スフィアを作成。IDを得る。
        $sphereId = $questObj->startField($avatarId, $slot);

        // フィールドフラッシュへリダイレクトする。
        Common::redirect('Swf', 'Sphere', array('id'=>$sphereId));
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
     * ギブアップを処理する。
     */
    private function processGiveup($sphereId) {

        // スフィアを閉じる。
        Service::create('Sphere_Info')->closeSphere($sphereId, Sphere_InfoService::GIVEUP);

        Common::redirect('Swf', 'Ready', array('questId'=>$_GET['questId']));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 移動している場合の処理。
     */
    private function processMove() {

        $userSvc = new User_InfoService();

        // 一応、移動できるかどうかチェック。
        if( !Service::create('Place_Master')->isMovable($this->user_id, $_GET['placeId']) )
            throw new MojaviException('移動できない場所に移動しようとした');

        // 移動チュートリアルを終了させる。
        $userSvc->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_MOVE);

        // 移動。
        $userSvc->movePlace($this->user_id, $_GET['placeId']);

        // 到着イベントがある場合はそちらへ、ないならメインメニューへリダイレクト。
        $arrivalEvent = Service::create('Place_Master')->getEventOnMove($this->user_id, $_GET['placeId']);
        if($arrivalEvent)
            Common::redirect('Swf', 'QuestDrama', array('questId'=>$arrivalEvent));
        else
            Common::redirect('Swf', 'Ready', array('questId'=>$_GET['questId']));
    }
}
