<?php

/**
 * ユーザーバトルができるかを確認する。
 */
class BattleConfirmAction extends SmfBaseAction {

    protected function doExecute($params) {

        $array['result'] = 'ok';

        // 戦闘種別を取得。
        $tourId = Tournament_MasterService::TOUR_MAIN;

        // キャラ双方の情報を取得。
        $charaSvc = new Character_InfoService();
        $chara1 = $charaSvc->needAvatar($this->user_id, true);
        $chara2 = $charaSvc->needExRecord($_GET['rivalId']);

        //プレイヤー名取得
        $chara1['player_name'] = Text_LogService::get($chara1['name_id']);
        $chara2['player_name'] = Text_LogService::get($chara2['name_id']);

        //階級名取得
        $gradeinfo = Service::create('Grade_Master')->needRecord($chara1['grade_id']);
        $chara1['grade_name'] =  $gradeinfo["grade_name"];

        $gradeinfo = Service::create('Grade_Master')->needRecord($chara2['grade_id']);
        $chara2['grade_name'] =  $gradeinfo["grade_name"];

        $array['chara1'] = $chara1;
        $array['chara2'] = $chara2;

        // バトルできるかどうかを取得。
        $battleUtil = new UserBattleUtil();
        $canBattle = $battleUtil->canBattle($chara1['character_id'], $chara2['character_id'], $tourId);
        $array['canBattle'] = $canBattle;

        // バトルできないなら...以降の処理は不要。
        if($canBattle != 'ok') {
            $array['result'] = 'error';
            $array['err_code'] = $canBattle;
        }

        // 開始ボタンが押されている場合はバトル開始処理。制御は戻ってこない。
        if(isset($_POST['doBattle']))
            return $this->processStart($chara1, $chara2, $tourId);

        // 以降、確認画面を表示するための準備。

        // 双方の画像情報を取得。
        $spec1 = $this->getFormation($chara1);
        $array['equip_infoP'] = $spec1;

        $spec1 = $this->getFormation($chara2);
        $array['equip_infoE'] = $spec1;

        $array['matchPt'] = (int)$this->userInfo['match_pt'];

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザバトルの開始処理を行う。
     */
    private function processStart($chara1, $chara2, $tourId) {

        // バトル前の準備＆バトルレコードの作成。
        $battleUtil = new UserBattleUtil();
        $battleId = $battleUtil->createBattle(array(
            'challenger' => $chara1,
            'defender' => $chara2,
            'tournament_id' => $tourId,
            'player_id' => $this->user_id,
        ));

        $array['result'] = 'ok';
        $array['url'] = Common::genContainerUrl('Api', 'Battle', array('battleId'=>$battleId));

        return $array;
    }
}
