<?php

class Raid_DungeonService extends Service {

    // ステータス の値。
    const NONE = 0;                     //　期間外
    const READY = 1;                    //　準備中
    const START = 2;                    //　イベント中
    const SUCCESS = 3;                  //　成功
    const FAILURE = 4;                  //　時間切れ

    const REQUIRE_NONE = 0;                     //　必要なし
    const REQUIRE_ETHADDR = 1;                  //　ETHアドレス


    //-----------------------------------------------------------------------------------------------------
    /**
     * 配信の一覧を、ページを指定して取得する。
     *
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getList($numOnPage, $page) {

        $where = array();
        $where ['ORDER BY'] = 'start_at DESC';

        return $this->selectPage($where, $numOnPage, $page);
    }

    public function getStatus($event) {

        if($event == null) return self::NONE;

        //開始時間前
        if(strtotime($event['notice_at']) <= time() && strtotime($event['start_at']) > time()){
            return self::READY;
        //開始時間内の場合
        }else if(strtotime($event['start_at']) <= time() && strtotime($event['end_at']) > time()){
            //if(Service::create('Raid_Monster_User')->is_clear($event['id']))
            //    return self::SUCCESS;
            //else
                return self::START;
        //時間終了
        }else if(strtotime($event['end_at']) <= time() && strtotime($event['close_at']) > time()){
            //if(Service::create('Raid_Monster_User')->is_clear($event['id']))
                return self::SUCCESS;
            //else
            //    return self::FAILURE;
        }else{
            return self::NONE;
        }

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 現在アクティブなデータを返す
     *
     * @param user_id     ユーザーID。
     */
    public function getCurrent() {

        // SQL作成＆実行
        $sql = "
          SELECT *
          FROM raid_dungeon
          WHERE notice_at <= now() and close_at > now()
        ";

        $record =  $this->createDao(true)->getRow($sql, array($status));

        if(is_null($record))
            return null;

        //クリアしてる場合
        //if(Service::create('Raid_Monster_User')->is_clear($record['id'])){
        //    $last_record = Service::create('Raid_Monster_User')->getLatestDefeatDate($record['id']);

        //    $record["close_at"] = DateTimeUtil::add('+3 day', $last_record, 'Y-m-d 00:00:00');
        //}

        $record = $this->getTransText($record);

        return $record;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["title", "description"];

        foreach($columns as $column){
            $data = AppUtil::getText("raid_dungeon_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'id';

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

}
