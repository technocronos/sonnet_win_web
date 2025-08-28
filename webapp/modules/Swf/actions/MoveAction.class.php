<?php

class MoveAction extends SwfBaseAction {

    private static $GLOBAL_POINTS = array(
        0 => array(0, 0),   1 => array(427, 49),    2 => array(115, 431),
        3 => array(58, 105), 4 => array(100, 100), 5 => array(100, 100),
    );


    protected function doExecute() {

        $placeSvc = new Place_MasterService();

        // 移動決定されているならそれ用の処理。制御は戻ってこない。
        if($_POST)
            $this->processMove();

        // ユーザの現在地点の地点を取得。
        $currentPlace = $placeSvc->needRecord($this->userInfo['place_id']);

        // 地域IDが指定されていない場合は現在地点の地域とする。
        if(strlen($_GET['region']) == 0)
            $_GET['region'] = $currentPlace['region_id'];

        // 指定の地域の、移動可能なポイント一覧を取得。
        $points = $this->getPoints($_GET['region']);

        // ポイント一覧をSWFに渡す。
        $this->arrayToFlasm('place', $points);
        $this->replaceStrings['placeNum'] = max(array_keys($points));

        // グローバルマップのURLとキャンセル時のURLをセット
        $this->replaceStrings['globalUrl'] = Common::genContainerUrl('Swf', 'Move', array('region'=>'0'), true);
        $this->replaceStrings['cancelUrl'] = Common::genContainerUrl('Swf', 'Main', null, true);

        // マップ画像をセット。
        $this->replaceImages[5] = IMG_RESOURCE_DIR . sprintf('/moveMap/%02d.jpg', $_GET['region']);

        // 開始時にナビに喋らせたいことがあれば設定する。
        $this->setupOpenLines($points);

        // グローバルマップを表示する場合は...
        if($_GET['region'] == 0) {

            // マップの地域名。
            $this->replaceStrings['regionName'] = 'グローバルマップ';

            // 現在地点の番号。
            $this->replaceStrings['currPlace'] = $currentPlace['region_id'];

            // 決定時の送信先URLを渡す。
            $this->replaceStrings['decideUrl'] = Common::genContainerUrl('Swf', 'Move', null, true) . '%26region%3D';

            // 決定時、GETで送信する。
            $this->replaceStrings['postOnDecide'] = 0;

            // グローバルマップを表示しようとしているなら、グローバルマップチュートリアルは終了
            Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_GLOBALMOVE);

        // ローカルマップを表示する場合は...
        }else {

            // マップの地域名。
            $region = $placeSvc->needRecord($_GET['region']);
            $this->replaceStrings['regionName'] = $region['place_name'];

            // 現在地点の番号。
            $this->replaceStrings['currPlace'] = $this->searchPointNo($points, $this->userInfo['place_id']);

            // 決定動作などの送信先URLを渡す。
            $this->replaceStrings['decideUrl'] = Common::genContainerUrl('Swf', 'Move', null, true);

            // 決定時、POSTで送信する。
            $this->replaceStrings['postOnDecide'] = 1;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定の地域の、移動可能なポイント一覧を返す。
     *
     * @param int       地域ID
     * @return array    SWFに渡すポイント一覧
     */
    private function getPoints($regionId) {

        $placeSvc = new Place_MasterService();

        // 移動可能な箇所がある地域の一覧を取得。
        $regions = $placeSvc->getMovableRegions($this->user_id);

        // 指定のマップで移動可能なポイントの一覧を取得。
        if($regionId == 0)
            $places = $regions;
        else
            $places = $placeSvc->getMovablePlaces($this->user_id, $regionId);

        // 移動可能な地点の一覧をSWFに伝達するポイント一覧に変換する。
        $points = array();
        foreach($places as $place) {
            $points[] = array(
                'X' => $place['map_x'],
                'Y' => $place['map_y'],
                'Name' => $place['place_name'],
                'Id' => $place['place_id'],
            );
        }

        // グローバルマップへ移動するためのポイントを作成する。
        $globalPoint = array(
            'X' => self::$GLOBAL_POINTS[$regionId][0],
            'Y' => self::$GLOBAL_POINTS[$regionId][1],
            'Name' => 'グローバルマップ',
            'Id' => '0',
        );

        // グローバルポイントをインデックス 0、その他の地点をインデックス 1 以降に配置する。
        array_unshift($points, $globalPoint);

        // グローバルマップを表示するとき、あるいは、他の地域に行けないときはグローバルポイントを
        // 無効化する。
        if($regionId == 0  ||  count($regions) <= 1)
            $points[0]['X'] = -1;

        // リターン
        return $points;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたポイント一覧から、指定のIDを持つものを返す。
     *
     * @param array     ポイントデータの配列。
     * @param int       探すポイントのID
     * @return int      見つけたポイントのインデックス値。見付からなかった場合は -1。
     */
    private function searchPointNo($points, $searchId) {

        foreach($points as $no => $point) {
            if($point['Id'] == $searchId)
                return $no;
        }

        return -1;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 開始時にナビに喋らせる内容をセットする。
     */
    private function setupOpenLines($points) {

        // 普段は台詞なし
        $lines = array();

        switch($this->userInfo['tutorial_step']) {

            // 移動チュートリアルの場合。
            case User_InfoService::TUTORIAL_MOVE:
                if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone")
                    $lines = array(
                        "移動するときはこの地図\n使うのだ",
                        "キーで地点を選んで\nキーで移動なのだ",
                        "キー押すとソネットメニューに\n戻るのだ",
                    );
                else
                    $lines = array(
                        "移動するときはこの地図\n使うのだ",
                        "十字ボタンで地点を選んで\n○ボタンで移動なのだ",
                        "×ボタン押すとソネットメニューに\n戻るのだ",
                    );

                break;

            // グローバルマップチュートリアルで、グローバルポイントが有効になっている場合。
            case User_InfoService::TUTORIAL_GLOBALMOVE:

                if($points[0]['X'] >= 0) {
                    $lines = array(
                        "故郷の島じゃない場所に\n行くときは、",
                        "｢グローバルマップ｣を選んで\n広域地図を出すのだ",
                    );
                }

                break;
        }

        $this->arrayToFlasm('open', $lines);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 移動している場合の処理。
     */
    private function processMove() {

        $userSvc = new User_InfoService();

        // 一応、移動できるかどうかチェック。
        if( !Service::create('Place_Master')->isMovable($this->user_id, $_POST['id']) )
            throw new MojaviException('移動できない場所に移動しようとした');

        // 移動チュートリアルを終了させる。
        $userSvc->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_MOVE);

        // 移動。
        $userSvc->movePlace($this->user_id, $_POST['id']);

        // 到着イベントがある場合はそちらへ、ないならメインメニューへリダイレクト。
        $arrivalEvent = Service::create('Place_Master')->getEventOnMove($this->user_id, $_POST['id']);
        if($arrivalEvent)
            Common::redirect('Swf', 'QuestDrama', array('questId'=>$arrivalEvent));
        else
            Common::redirect('User', 'Main');
    }
}
