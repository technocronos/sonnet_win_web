<?php

/**
 * ---------------------------------------------------------------------------------
 * クエストリスト情報を送信する
 * ---------------------------------------------------------------------------------
 */
class QuestListAction extends SmfBaseAction {

    protected function doExecute($params) {

        $array = array();

        $placeSvc = new Place_MasterService();

        // ユーザの現在地点の地点を取得。
        $currentPlace = $placeSvc->needRecord($this->userInfo['place_id']);

        $region_id = $currentPlace['region_id'];
        $array['currRegion'] = $region_id ;

        // すでにフィールドクエストに出ているかどうか
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        if($avatar['sally_sphere']){
            $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);
            $questObj = Service::create('Quest_Master')->getRecord($sphere['quest_id']);

            $array["sally_quest_id"] = $sphere['quest_id'];
            $array["sally_sphere"] = $avatar['sally_sphere'];

            //再出発のURL
            $questObj["url"] = Common::genContainerUrl(
                 'Swf', 'Sphere', array('id'=>$avatar['sally_sphere'], 'reopen' => 'resume'), true
            );
            $array["sally_quest"] = $questObj;

            //ギブアップ完了後の結果画面URL
            $array['urlOnFieldEnd'] = Common::genContainerUrl(
                'Swf', 'FieldEnd', array('sphereId'=>$avatar['sally_sphere']), true
            );

        }else{
            $array["sally_quest_id"] = "";
        }

        // 移動可能な箇所がある地域の一覧を取得。
        $regions = $placeSvc->getMovableRegions($this->user_id);

        //マップ、クエストの実行可能なリストをすべて取得する。
        $g_points= array();
        $points_string = "";
        $quest_string = "";
        $questnum_string = "";

        foreach($regions as $reg) {

            // 指定の地域の、移動可能なポイント一覧を取得。
            $points = $this->getPoints($reg["place_id"]);

            if($reg["place_id"] == $currentPlace['region_id']){
                // 現在地点の番号。
                $array['currPlace'] = $this->searchPointNo($points, $reg["place_id"] , $this->userInfo['place_id']);
            }

            // ポイント一覧をSWFに渡す。
            $array['place'][$reg["place_id"]] = $points;

            // 指定の地域の、移動可能なクエスト一覧を取得。
            $quests = $this->getQuests($points, $reg["place_id"]);

            //セットしたものを連結して保存しておく
            $array['quest'][$reg["place_id"]] = $quests;

            //グローバルマップ
            $g_points[$reg["place_id"]] = array(
                'X' => $reg["map_x"],
                'Y' => $reg["map_y"],
                'Name' => $reg['place_name'],
                'Id' => $reg['place_id'],
            );
        }

        //グローバルマップをSWFに渡すために連結する
        $array['globalplace'] = $g_points;

        // グローバルマップのURLとキャンセル時のURLをセット
        $array['globalUrl'] = Common::genContainerUrl('Swf', 'Move', array('region'=>'0'), true);
        $array['cancelUrl'] = Common::genContainerUrl('Swf', 'Main', null, true);

        // 初期表示マップの地域名。
        $region = $placeSvc->needRecord($region_id);
        $array['regionName'] = $region['place_name'];

        // 決定動作などの送信先URLを渡す。
        $array['decideUrl'] = Common::genContainerUrl('Swf', 'Move', null, true);

        //グローバルボタン表示フラグ
        $cnt = Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->userInfo['user_id'], 11002);
        $array["showGlobal"] = $cnt == 0 ? false : true;

        return $array;

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

        // 指定のマップで移動可能なポイントの一覧を取得。
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

        // リターン
        return $points;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定の地域の、実行可能なクエスト一覧を返す。
     *
     * @param int       地域ID
     * @return array    SWFに渡すポイント一覧
     */
    private function getQuests($points, $region_id) {
        $array = array();

        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);

        if($avatar['sally_sphere'])
            $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);
//Common::varLog($points);
//Common::varLog($region_id);
//Common::varLog($addquestlist);

        foreach($points as $key => $point) {
            //クエストリスト取得
            $questlist = QuestCommon::getExecutableList($this->user_id, $point['Id']);

            // すでにチュートリアルが終わっているなら追加
            if($this->userInfo['tutorial_step'] >= User_InfoService::TUTORIAL_END){
                //イベントクエスト
                $eventquestlist = QuestCommon::getExecutableList($this->user_id, Quest_MasterService::EVENT_QUEST);

                foreach($eventquestlist as $eventquest){
                    if($eventquest["type"] == "FLD")
                        $questlist[] = $eventquest;
                }

                //地点無しクエスト
                $wildquestlist = QuestCommon::getExecutableList($this->user_id, Quest_MasterService::WILD_PLACE);
                foreach($wildquestlist as $wildquest){
                    if($wildquest["type"] == "FLD")
                        $questlist[] = $wildquest;
                }
            }

            foreach($questlist as &$quest) {
                if($quest['place_id'] == 0){
                    //曜日クエの場合は内容を書き換える
                  	$reword = FieldBattle99999Util::getRewordDay();
                    $quest["special_explain"] = $reword['str'];
                }

                if($quest["type"] == "FLD"){
                    if($avatar['sally_sphere'] != null && $sphere["quest_id"] == $quest['quest_id']){
                        //すでに出発している
                        $url = Common::genContainerUrl(
                             'Swf', 'Sphere', array('id'=>$avatar['sally_sphere'], 'reopen' => 'resume'), true
                        );
                    }
                }else{
                    $url = Common::genContainerUrl(
                         'Swf', 'QuestDrama', array('questId'=>$quest['quest_id'], 'placeId' => $point['Id']), true
                    );
                }

                $quest["url"] = $url;
            }

            $array[$key] = $questlist;
        }

//Common::varDump($questlist);

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたポイント一覧から、指定のIDを持つものを返す。
     *
     * @param array     ポイントデータの配列。
     * @param int       探すポイントのID
     * @return int      見つけたポイントのインデックス値。見付からなかった場合は -1。
     */
    private function searchPointNo($points, $region_id ,$searchId) {

        foreach($points as $no => $point) {
            if($point['Id'] == $searchId)
                return $no;
        }

        return -1;
    }

}
