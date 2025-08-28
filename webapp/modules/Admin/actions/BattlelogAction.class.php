<?php

class BattlelogAction extends AdminBaseAction {

    public function execute() {

        if(!isset($_GET["create_at_from"]))
            $_GET["create_at_from"] = RELEASE_DATE;

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'go' => 'required',
            'characterId' => array('required', 'numonly'),
            'create_at_from' => 'datetime',
            'create_at_to' => 'dateend',
            '_form' => array(
                array('lowerupper' => array('create_at_from', 'create_at_to')),
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

        $Srv = new Battle_LogService();

        // 先に検索条件をシリアル化しておく。
        $this->setAttribute( 'target', urlencode(json_encode($condition)) );
        // ユーザ検索。
        $limit = 1000;
        $condition['return_matched_rows'] = true;
        $table = $Srv->getBattleList2($condition, $limit,0);
        $matchedRows = $limit;

print_r($table);
exit;


        // アンインストールユーザを除いたカウントを得る。
        $condition['except_retire'] = true;
        $limit = 1;
        $userSvc->findUsers($condition, $limit);
        $liveRows = $limit;

        foreach($table as &$row){
            if(PLATFORM_TYPE == "nati"){
                unset($row["name"],$row["short_name"]);
            }

            $text_log = Service::create('Text_Log')->getWriter($row["user_id"]);
            $row["chara_name"] = $text_log[0]["body"];

            $chara = Service::create('Character_Info')->getAvatar($row["user_id"]);
            $row["character_id"] = $chara["character_id"];
            $row["grade"] = Service::create('Grade_Master')->name($chara["grade_id"]);
            $level = Service::create('Level_Master')->getLevelByExp("PLA", $chara["exp"]);
            $row["level"] = $level["level"];
            $row["exp"] = $chara["exp"];
            $row["param_seed"] = $chara["param_seed"];
            $row["hp_max"] = $chara["hp_max"];
            $row["attack1"] = $chara["attack1"];
            $row["attack2"] = $chara["attack2"];
            $row["attack3"] = $chara["attack3"];
            $row["defence1"] = $chara["defence1"];
            $row["defence2"] = $chara["defence2"];
            $row["defence3"] = $chara["defence3"];
            $row["speed"] = $chara["speed"];

            $payment = Service::create('Payment_Log')->sumupUserPayment($row["user_id"]);
            $row["total_payment"] = $payment[0]["sales"];

            // coinアイテムのuser_itemレコードを取得。
            $coin_info = Service::create('User_Item')->getRecordByItemId($row["user_id"], COIN_ITEM_ID);

            $row["coin"] = $coin_info['num'];
            $row["virtual_coin"] = sprintf('%f', $row["virtual_coin"]);
        }

        // ビューに割り当て。
        $this->setAttribute('table', $table);
        $this->setAttribute('hit', $matchedRows);
        $this->setAttribute('live', $liveRows);
    }
}
