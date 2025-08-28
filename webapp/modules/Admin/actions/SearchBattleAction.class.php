<?php

class SearchBattleAction extends AdminBaseAction {

    public function execute() {
        $_GET["tourId"] = 1;

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'go' => 'required',
            'tourId' => 'numonly',
            'characterId' => 'numonly',
            'rivalCharacterId' => 'numonly',
            'create_at_from' => 'datetime',
            'create_at_to' => 'datetime',
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

        $battleSvc = new Battle_LogService();
        $userSvc = new User_InfoService();

        // 先に検索条件をシリアル化しておく。
        $this->setAttribute( 'target', urlencode(json_encode($condition)) );

        // ユーザ検索。
        $limit = 100;
        $condition['return_matched_rows'] = true;
        $page = 0;
        $table = $battleSvc->getBattleListAdmin($condition, $limit, $page);

        $battleSvc->addBiasColumn($table["resultset"], null, true);

        $matchedRows = $limit;

        foreach($table["resultset"] as &$row){
            $challenger = Service::create('Character_Info')->needUserId($row["challenger_id"]);
            $defender = Service::create('Character_Info')->needUserId($row["defender_id"]);

            $text_log = Service::create('Text_Log')->getWriter($challenger);
            $row["challenger_name"] = $text_log[0]["body"];
            $text_log = Service::create('Text_Log')->getWriter($defender);
            $row["defender_name"] = $text_log[0]["body"];
            if($row["result_at"] != null){

                $end = new DateTime($row["create_at"]);
                $start = new DateTime($row["result_at"]);
                $diff = $end->diff($start);
                $row["pasttime"] = $diff->format('%h時間%i分%s秒');
            }else{
                $row["pasttime"] = "";
            }
        }

        // ビューに割り当て。
        $this->setAttribute('table', $table);
    }
}
