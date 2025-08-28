<?php

class MoveAction extends UserBaseAction {

    private static $GLOBAL_POINTS = array(
        0 => array(0, 0),   1 => array(427, 49),    2 => array(115, 431),
        3 => array(58, 105), 4 => array(100, 100),
    );


    public function execute() {

        $placeSvc = new Place_MasterService();

        // 移動決定されているならそれ用の処理。制御は戻ってこない。
        if($_GET['Id'])
            $this->processMove();

        // ユーザの現在地点の地点を取得。
        $currentPlace = $placeSvc->needRecord($this->userInfo['place_id']);

        // 地域IDが指定されていない場合は現在地点の地域とする。
        if(strlen($_GET['region']) == 0)
            $_GET['region'] = $currentPlace['region_id'];

        // 指定の地域の、移動可能なポイント一覧を取得。
        $points = $this->getPoints($_GET['region']);

//Common::varDump($points);

		$this->setAttribute('points', $points);

        // ポイント一覧を渡す。
		$this->setAttribute('placeNum', max(array_keys($points)));

        // 開始時にナビに喋らせたいことがあれば設定する。
        //$this->setupOpenLines($points);

		$this->setAttribute('region', sprintf('%02d', $_GET['region']));

        // グローバルマップを表示する場合は...
        if($_GET['region'] == 0) {

            // マップの地域名。
			$this->setAttribute('regionName', 'グローバルマップ');

            // 現在地点の番号。
			$this->setAttribute('currPlaceId', $currentPlace['region_id']);

            // グローバルマップを表示しようとしているなら、グローバルマップチュートリアルは終了
            Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_GLOBALMOVE);

            $_GET['scope'] = 'global';

        // ローカルマップを表示する場合は...
        }else {

            // マップの地域名。
            $region = $placeSvc->needRecord($_GET['region']);
			$this->setAttribute('regionName', $region['place_name']);

            // 現在地点の番号。
			$this->setAttribute('currPlaceId', $this->userInfo['place_id']);

            $_GET['scope'] = 'local';
        }

		return View::SUCCESS;
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
        //array_unshift($points, $globalPoint);

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
        if( !Service::create('Place_Master')->isMovable($this->user_id, $_GET['Id']) )
            throw new MojaviException('移動できない場所に移動しようとした');

        // 移動チュートリアルを終了させる。
        $userSvc->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_MOVE);

        // 移動。
        $userSvc->movePlace($this->user_id, $_GET['Id']);

        // 到着イベントがある場合はそちらへ、ないならメインメニューへリダイレクト。
        $arrivalEvent = Service::create('Place_Master')->getEventOnMove($this->user_id, $_GET['Id']);
        if($arrivalEvent)
            Common::redirect('Swf', 'QuestDrama', array('questId'=>$arrivalEvent));
        else
            Common::redirect('User', 'Main');
    }
}
