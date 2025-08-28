<?php

/**
 * 「ガチャのラインナップ」を処理するアクション。
 * 
 * @param gachaId ガチャID
 *        go ガチャ種別　課金：charge マグナ：gold チケット：ticket
 *        count 回数　何連ガチャか 。1か11のいずれか。
 */
class GachaApiAction extends ApiBaseAction {

    protected function doExecute($params) {
        $gachaSvc = new Gacha_MasterService();

        // ガチャの一覧を取得。
        $gachaSvc = new Gacha_MasterService();
        $gacha = $gachaSvc->getGachaList($this->user_id, 10000, 0);

        $array['gacha'] = $gacha["resultset"];

        foreach($array['gacha'] as &$row){
            if($row["notice_time"])
                $row["caption"] = $row["clear_event_name"] . "イベ期間中に回せる！";
        }

        // 共通フリーチケットの数を数える。
        $count = Service::create('User_Item')->getHoldCount($this->user_id, Gacha_MasterService::FREETICKET_ID);
        $array['ticketCount'] = $count;

        // 無料ガチャをまわせるかどうかを取得。
        $tryable = (int)date('Ymd') > Service::create('User_Property')->getProperty($this->user_id, 'free_gacha_date');
        $array['freeGacha'] = $tryable;

        return $array;

    }
}
