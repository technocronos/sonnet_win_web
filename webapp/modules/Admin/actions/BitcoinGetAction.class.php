<?php

class BitcoinGetAction extends AdminBaseAction {

    public function execute() {

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'go' => 'required',
            'id' => array('numonly'),
            'update_at_from' => 'datetime',
            'update_at_to' => 'dateend',
            '_form' => array(
                array('lowerupper' => array('update_at_from', 'update_at_to')),
            ),
        ));
        $this->setAttribute('validator', $validator);

        // 入力値の検査。
        $validator->validate($_GET);

        // エラーがあるならココまで。
        if($validator->isError())
            return View::SUCCESS;

        // 指定された条件のユーザを取得する。
        $this->find($validator->values);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された条件のユーザを取得する。
     *
     * @param object    フォームの入力値を正規化したバリデータ。
     */
    private function find($condition) {

        // 先に検索条件をシリアル化しておく。
        $this->setAttribute( 'target', urlencode(json_encode($condition)) );

        // 検索。
        $res = Service::create('Vcoin_Flag_Log')->getBtcLog($condition);
        $table = array();
        $i = 0;
        $total = 0;

        foreach($res as $row){
            if($row["flag_group"] == Vcoin_Flag_LogService::MONSTER){
                $table[$i]["reason"] = "モンスター討伐";
                $table[$i]["owner_id"] = $row["owner_id"];

                $chara = Service::create('Character_Info')->getRecord($row["flag_id"]);
                $text_log = Service::create('Text_Log')->getRecord($chara["name_id"]);
                $table[$i]["name"] = $text_log["body"];
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
            }else if($row["flag_group"] == Vcoin_Flag_LogService::BATTLE_RANKING){
                $table[$i]["reason"] = "バトルランキング";
                $table[$i]["owner_id"] = $row["owner_id"];
                $table[$i]["name"] = (int)substr($row["flag_id"], -4) . "位";
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
            }else if($row["flag_group"] == Vcoin_Flag_LogService::FIELD){
                $table[$i]["reason"] = "フィールド";
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Quest_Master')->getRecord((int)substr($row["flag_id"], 0, 5));
                $table[$i]["name"] = $quest["quest_name"];
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
                
            }
            $total += $row["flag_value"];
            $i++;
        }

        // ビューに割り当て。
        $this->setAttribute('table', $table);
        $this->setAttribute('total', sprintf('%f', $total));
    }
}
