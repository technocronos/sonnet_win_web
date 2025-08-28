<?php

class Gacha_MasterService extends Service {

    const FREETICKET_ID = 99001;
    const GACHA_GUARANTEED_COUNT = 30;

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザがプレイ可能なガチャの一覧をページ分けして返す。
     * 所持しているフリーチケット枚数を表す擬似列 "freeticket_count" もつけて返す。
     *
     * @param int       ユーザID
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getGachaList($userId, $showOnPage, $page) {

        // 指定されたユーザのアバターキャラを取得。
        $chara = Service::create('Character_Info')->needAvatar($userId);
        $userInfo = Service::create('User_Info')->needRecord($userId);

        if($userInfo['tutorial_step'] != User_InfoService::TUTORIAL_GACHA) {

            // 次のガチャがリリースされるLvを取得。
            $next = $this->selectRecord(array(
                'unlock_level' => array('sql'=>'> ?', 'value'=>$chara['level']),
                'ORDER BY' => 'unlock_level',
            ));

            $nextRelease = $next ? $next['unlock_level'] : null;

            // プレイできるガチャの一つ上のレコードも含めて、プレイできるものを取得。
            $where = array();
            $where['ORDER BY'] = array('unlock_level DESC', 'gacha_id');
            $where['gacha_id'] = array('sql'=>'!= ?', 'value'=>9998);
            $where['unlock_level:1'] = array('sql'=>'IS NOT NULL');
            $where['close_flg'] = 0;

            if($nextRelease)
                $where['unlock_level:2'] = array('sql'=>'<= ?', 'value'=>$nextRelease);

            $list = $this->selectPage($where, $showOnPage, $page);

            // ページ1の場合は雑貨ガチャを先頭に追加。
            if($page == 0){
                // 無料ガチャをまわせるかどうかを取得。
                $tryable = (int)date('Ymd') > Service::create('User_Property')->getProperty($userId, 'free_gacha_date');

                if($tryable)
                  array_unshift($list['resultset'], $this->getRecord(9997));
                else
                  array_unshift($list['resultset'], $this->getRecord(9998));
            }

            $tmp = array();

            //ガチャのローテーションをする。
            //毎週日曜日に代わる
            $ROTATE_WEEK = 0; 
            //4個のガチャをローテーションする
            $rotate_num = 4;

            $release_date = new DateTime(RELEASE_DATE);
            $release_date->setTime(0, 0, 0);

            $from = $release_date->getTimestamp(); //リリース日時
            $to   = strtotime("now");         // 現在日時

            //Common::varLog($from);

            $past = ($this->time_diff($from, $to) + (($release_date->format("w") - $ROTATE_WEEK) )) / 7;

            $n = floor($past) / $rotate_num;
            $whole = floor($n);
            $fraction = $n - $whole; // 小数点だけを求める
            $fraction = (string)$fraction; //floatだと比較が効かないので文字列に

            //Common::varLog($fraction);

            //$fractionの値は1を$rotate_numで割った少数で分岐すること。

            //週ガチャ。IDは下記の通り固定。
            if($fraction == 0){
                $SP_GACHA_ID = 2;//今週のガチャ(ユニコーンセット)
            }else if($fraction == 0.25){
                $SP_GACHA_ID = 3;//今週のガチャ(くの一セット)
            }else if($fraction == 0.5){
                $SP_GACHA_ID = 7;//今週のガチャ(巫女セット)
            }else if($fraction == 0.75){
                $SP_GACHA_ID = 5;//今週のガチャ(ギャングセット)
            }

//8に固定
$SP_GACHA_ID = 8;

            foreach($list['resultset'] as $row){
                $row["notice_time"] = false;

                //週ガチャの場合はローテーション
                if($row["wk_flg"] == 1){
                    if($row["gacha_id"] == $SP_GACHA_ID){
                        $tmp[] = $row;
                    }
                //スペシャルガチャ（通常課金ガチャ）
                }elseif ($row["sp_flg"] == 1){
                    $tmp[] = $row;
                }else{
                    if($row["gacha_id"] == 9998 || $row["gacha_id"] == 9997){
                        $tmp[] = $row;
                    }else if($row["gacha_id"] == 2005){
                        $event_name = "";
                        $row["clear_event_name"] = $event_name;
                        $row["clear_event_id"] = 1;
                        $tmp[] = $row;
                        continue;

                        $rankSvc = new Ranking_LogService();
                        $rankinfo = $rankSvc->getRankingStatus();
                        $event_name = AppUtil::getText("TEXT_BATTLE_RANKING");

                        //開催中の場合
                        if($rankinfo["status"] == 1){
                            $row["clear_event_name"] = $event_name;
                            $row["clear_event_id"] = 1;
                            $tmp[] = $row;
                        //準備中
                        }else if($rankinfo["status"] == 4){
                            //予告を出す
                            $row["notice_time"] = true;
                            $row["clear_event_name"] = $event_name;
                            $row["clear_event_id"] = 1;
                            $tmp[] = $row;
                        }
                    }else{
                        //イベントクエストガチャ
                        $eventlist = Service::create('Quest_Master')->onPlace(Quest_MasterService::EVENT_QUEST, "FLD");
                        foreach($eventlist as $event){
                            //クエストに設定してあるガチャIDの場合
                            if($row["gacha_id"] == $event["gacha_id"]){
                                //開始、終了日時チェック
                                if($event['start_date'] != NULL){
                                    //開始日1日前
                                    if(strtotime($event['start_date']) > time() && (strtotime($event['start_date']) - (60 * 60 * 24 * 1)) < time()){
                                        //予告を出す
                                        $row["notice_time"] = true;
                                        $row["clear_event_name"] = $event["quest_name"];
                                        $row["clear_event_id"] = $event["quest_id"];
                                        $tmp[] = $row;

                                    //開催中の場合
                                    }else if(strtotime($event['start_date']) <= time() && strtotime($event['end_date']) > time()){
                                        //未クリアは予告を出す
                                        //if(!Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $userId, $event["quest_id"])){
                                            //$row["notice_time"] = true;
                                        //}
                                        $row["clear_event_name"] = $event["quest_name"];
                                        $row["clear_event_id"] = $event["quest_id"];
                                        $tmp[] = $row;

                                    }
                                }
                            }
                        }
                    }
                }
            }

            $list['resultset'] = $tmp;

        }else{
            //チュートリアル中はチュートリアル専用ガチャと雑貨ガチャ
            $list['resultset'][] = $this->getRecord(9998);
            $list['resultset'][] = $this->getRecord(9000);
        }

        // フリーチケットの枚数も取得。
        $userItemSvc = new User_ItemService();
        $freeticketCounts = $userItemSvc->getHoldCount( $userId,
            ResultsetUtil::colValues($list['resultset'], 'freeticket_item_id')
        );

        ResultsetUtil::colInsert($list['resultset'], 'has_freeticket_count', $freeticketCounts, 'freeticket_item_id');

        // リターン。
        return $list;
    }

    //***************************************
    // 日時の差を計算
    //***************************************
    private function time_diff($time_from, $time_to) 
    {
        // 日時差を秒数で取得
        $dif = $time_to - $time_from;
        // 時間単位の差
        $dif_time = date("H:i:s", $dif);
        // 日付単位の差
        $dif_days = (strtotime(date("Y-m-d", $dif)) - strtotime("1970-01-01")) / 86400;
        //return "{$dif_days}days {$dif_time}";
        return $dif_days;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザに最も適したガチャのレコードを返す。
     *
     * @param int       ユーザID
     * @return array    最も適したガチャのレコード。ない場合はnull
     */
    public function getFitGacha($userId) {

        $chara = Service::create('Character_Info')->needAvatar($userId);

        return $this->selectRecord(array(
            'unlock_level' => array('sql'=>'<= ?', 'value'=>$chara['level']),
            'gacha_id' => array('sql'=>'!= ?', 'value'=>9998),
            'ORDER BY' => 'unlock_level DESC',
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたガチャの中身のリストを返す。
     * 結果セットに含まれるレコードはgacha_contentレコードだが、以下の擬似列が追加されている。
     *     item     Item_MasterService::getExRecord で取得したレコード
     *     rate     出現確率を100分率で。
     */
    public function getContents($gachaId) {

        // gacha_content のレコードを取得。
        $contents = $this->getContentData($gachaId);

        // weightの合計値を取得。
        $weightSum = ResultsetUtil::sum($contents, 'weight');

        // 擬似列を加える
        $itemSvc = new Item_MasterService();
        $setSvc = new Set_MasterService();

        foreach($contents as &$record) {
            $record['item'] = $itemSvc->needExRecord($record['item_id']);
            $record['rate'] = sprintf('%.2f', $record['weight'] / $weightSum * 100);
			      $record['item']['set'] = $setSvc->getRecord($record['item']['set_id']);
        }

        // リターン。
        return $contents;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザが指定のガチャを購入できるかどうかを返す。
     */
    public function canPurchase($userId, $gachaId) {

        // 指定されたユーザのアバターキャラを取得。
        $chara = Service::create('Character_Info')->needAvatar($userId);

        // 指定されたガチャの詳細を取得。
        $gacha = $this->needRecord($gachaId);

        // 開放レベルを満たしていれば購入できる。
        return (isset($gacha['unlock_level'])  &&  $gacha['unlock_level'] <= $chara['level'] && $gacha['close_flg'] == 0);
    }


  //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたガチャで抽選をまわして、引いたアイテムのIDを返す。
     */
    public function drawItem($gachaId, $userId, $amount = 1) {

        // gacha_content のレコードを取得。
        $contents = $this->getContentData($gachaId);

        // weightの合計値を取得。
        $weightSum = ResultsetUtil::sum($contents, 'weight');

        $gacha_guaranteed_count = Service::create('User_Property')->getProperty($userId, 'gacha_count_' . $gachaId);

        // $drawValueから、ガチャ内容のweightの値を引きながら、0以下になったタイミングの
        // レコードを取得する。
        $i = 0;
        $hits = array();

        do{
            // 1～合計値の間でランダム値を取得。
            $drawValue = mt_rand(1, $weightSum);

            foreach($contents as $content) {
                $drawValue -= $content['weight'];
                if($drawValue <= 0) {
                    $exist = false;
                    //すでに引いているかどうか調べる
                    foreach($hits as $hit) {
                        if($hit["item_id"] == $content["item_id"]){
                            $exist = true;
                            break;
                        }
                    }
                    //存在していなかったら追加
                    if(!$exist){
                        $hits[] = $content;
                        $i++;
                    }
                    break;
                }
            }
        }while($i < $amount);

        //ガチャ回数を更新
        $gacha_guaranteed_count = $gacha_guaranteed_count + $amount;

        if($gacha_guaranteed_count >= self::GACHA_GUARANTEED_COUNT){
            Common::varLog("ガチャ天井確定処理 :" . $userId . " gachaId :" . $gachaId);

            //ガチャ回数更新
            Service::create('User_Property')->updateProperty($userId, 'gacha_count_' . $gachaId, (self::GACHA_GUARANTEED_COUNT - $gacha_guaranteed_count) * -1);

            //当たりアイテムを得る
            $guaranteed_item = array();
            foreach($contents as $content) {
                if($content["guaranteed_flg"] == 1){
                    $guaranteed_item[] = $content;
                }
            }

            if(count($guaranteed_item) > 0){
                $key = array_rand($guaranteed_item, 1);

                $atari_key = 0;
                $i = 0;
                //もし当たりを引いているようならそれと入れ替える。
                foreach($hits as $row){
                    if($row["guaranteed_flg"] == 1){
                        $atari_key = $i;
                        break;
                    }
                    $i++;
                }

                $hits[$atari_key] = $guaranteed_item[$key];
                $hits[$atari_key]["is_guaranteed"] = true;

                Common::varLog($hits[0]);

            }else{
                Common::varLog("あたりアイテムが無いガチャ");
            }

        }else{
            $res = Service::create('User_Property')->updateProperty($userId, 'gacha_count_' . $gachaId, $gacha_guaranteed_count);
        }

        // レコードを取得できていないのはエラー。
        if( count($hits) == 0)
            throw new MojaviException('ガチャを抽選したが、アタリを取得できなかった。');

        // リターン。
        return $hits;
    }


    // privateメンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたガチャのgacha_contentレコードを返す。
     */
    public function getContentData($gachaId) {

        $sql = '
            SELECT *
            FROM gacha_content
            WHERE gacha_id = ?
            ORDER BY sort_order
        ';

        return $this->createDao(true)->getAll($sql, $gachaId);
    }

	//アイテムIDから検索する
    public function getItemId($item_id) {

        $sql = '
            SELECT gacha_content.*, gacha_master.unlock_level
            FROM gacha_master inner join gacha_content ON
				gacha_master.gacha_id = gacha_content.gacha_id
            WHERE item_id = ? and unlock_level > 0
            ORDER BY sort_order
        ';

        return $this->createDao(true)->getAll($sql, $item_id);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["gacha_name", "caption", "flavor_text"];

        foreach($columns as $column){
            $data = AppUtil::getText("gacha_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'gacha_id';

    protected $isMaster = true;


    /**
     * getRecordをオーバーライドして多言語対応
     */
    public function getRecord(/* 可変引数 */) {
        $args = func_get_args();
        $record = parent::getRecord($args[0]);

        $record = $this->getTransText($record);

        return $record;
    }

    /**
     * selectResultsetをオーバーライドして多言語対応
     */
    public function selectResultset($where) {
        $record = parent::selectResultset($where);

        foreach($record as &$row){
            $row = $this->getTransText($row);
        }

        return $record;
    }

    protected function processRecord(&$record) {
        $record = $this->getTransText($record);
    }
}
