<?php

/**
 * 「ガチャのラインナップ」を処理するアクション。
 * 
 * @param gachaId ガチャID
 *        go ガチャ種別　課金：charge マグナ：gold チケット：ticket
 *        count 回数　何連ガチャか 。1か11のいずれか。
 */
class GachaAction extends SmfBaseAction {

    protected function doExecute($params) {
        $gachaSvc = new Gacha_MasterService();

        // ガチャの一覧を取得。
        $gachaSvc = new Gacha_MasterService();
        $gacha = $gachaSvc->getGachaList($this->user_id, 10000, 0);

        $array['gacha'] = $gacha["resultset"];

        foreach($array['gacha'] as &$row){
            if($row["notice_time"])
                $row["caption"] = str_replace("{0}", $row["clear_event_name"] , AppUtil::getText("gacha_event_notice1"));

            $row["guaranteed_count"] = Service::create('User_Property')->getProperty($this->user_id, 'gacha_count_' . $row["gacha_id"]);

            $row["is_guaranteed"] = false;
            // gacha_content のレコードを取得。
            $contents = Service::create('Gacha_Master')->getContentData($row["gacha_id"]);
            foreach($contents as $content) {
                if($content["guaranteed_flg"] == 1){
                    $row["is_guaranteed"] = true;
                    $row["flavor_text"] .= str_replace("{0}", (Gacha_MasterService::GACHA_GUARANTEED_COUNT - $row["guaranteed_count"]) , AppUtil::getText("gacha_guaranteed_count"));
                    break;
                }
            }
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
