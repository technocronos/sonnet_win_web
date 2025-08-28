<?php

class RivalListAction extends UserBaseAction {

    public function execute() {

        $sessSvc = new Mini_SessionService();

        // 現在のキャラを取得。
        $charaSvc = new Character_InfoService();
        $chara = $charaSvc->needAvatar($this->user_id, true);
        $this->setAttribute('character', $chara);

        // 戦闘種別を取得。
        $tourId = Tournament_MasterService::TOUR_MAIN;
        $tourSvc = new Tournament_MasterService();
        $this->setAttribute('tournament', $tourSvc->needRecord($tourId));

        // 対戦相手一覧の取得と、他画面遷移時のbacktoパラメータの決定。
        // GET変数 did がない場合。
        if( empty($_GET['did']) ){

            // 対戦相手一覧を抽出。
            $battleUtil = new UserBattleUtil();
            $rivalList = $battleUtil->getRivalList($chara, $tourId, 8);
            $this->setAttribute('rivalList', $rivalList);

            // 抽出した対戦相手一覧をDBに保存、戻るときはその保存IDと共に戻るようにする。
            $dataId = $sessSvc->setData($rivalList);
            $backto = ViewUtil::serializeBackto(array('did'=>$dataId));

        // GET変数 did がある場合。
        }else {

            // DBに保存しておいた相手一覧を使用する。
            $this->setAttribute('rivalList', $sessSvc->needData($_GET['did']));
            $backto = ViewUtil::serializeBackto();
        }

        // 他者のページへ遷移するときの追加パラメータをセットする。
        $this->setAttribute('params', array(
            'backto' => $backto,
        ));

        return View::SUCCESS;
    }
}
