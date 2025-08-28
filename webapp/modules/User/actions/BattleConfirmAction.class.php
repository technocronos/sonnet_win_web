<?php

class BattleConfirmAction extends UserBaseAction {

    public function execute() {

        // 戦闘種別を取得。
        $tourId = Tournament_MasterService::TOUR_MAIN;

        // キャラ双方の情報を取得。
        $charaSvc = new Character_InfoService();
        $chara1 = $charaSvc->needAvatar($this->user_id, true);
        $chara2 = $charaSvc->needExRecord($_GET['rivalId']);
        $this->setAttribute('chara1', $chara1);
        $this->setAttribute('chara2', $chara2);

        // バトルできるかどうかを取得。
        $battleUtil = new UserBattleUtil();
        $canBattle = $battleUtil->canBattle($chara1['character_id'], $chara2['character_id'], $tourId);
        $this->setAttribute('canBattle', $canBattle);

        // バトルできないなら...以降の処理は不要。
        if($canBattle != 'ok') {

            // pt不足でバトルできない場合は、対戦pt不足画面へ飛ばす。
            if($canBattle == 'consume_pt') {

                // pt不足画面の戻り先はユーザページ、回復した場合はこのURLへ戻すようにする。
                $backto = ViewUtil::serializeParams(array('action'=>'HisPage', 'userId'=>$chara2['user_id']));
                $useto = ViewUtil::serializeBackto();
                Common::redirect('User', 'Suggest', array('type'=>'mp', 'backto'=>$backto, 'useto'=>$useto));

            // それ以外はこのままビューへ。
            }else {
                return View::SUCCESS;
            }
        }

        // 開始ボタンが押されている場合はバトル開始処理。制御は戻ってこない。
        if(isset($_POST['doBattle']))
            $this->processStart($chara1, $chara2, $tourId);

        // 以降、確認画面を表示するための準備。

        // 双方の画像情報を取得。
        $this->setAttribute('spec1', CharaImageUtil::getSpec($chara1));
        $this->setAttribute('spec2', CharaImageUtil::getSpec($chara2));

		if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
	        $this->setAttribute('chara_width', "100%");
    	    $this->setAttribute('chara_height', 130);
		}else{
	        $this->setAttribute('chara_width', 240);
    	    $this->setAttribute('chara_height', 100);
		}

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザバトルの開始処理を行う。
     * リダイレクトするので制御は戻ってこない。
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

        // バトルIDとともにバトルFLASHへ。
        Common::redirect('Swf', 'Battle', array('battleId'=>$battleId));
    }
}
