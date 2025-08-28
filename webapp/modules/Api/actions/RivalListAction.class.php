<?php

/**
 * 「対戦相手リスト」を処理するアクション。
 */
class RivalListAction extends SmfBaseAction {

    protected function doExecute($params) {

        $sessSvc = new Mini_SessionService();

        // 現在のキャラを取得。
        $charaSvc = new Character_InfoService();
        $chara = $charaSvc->needAvatar($this->user_id, true);

        // 戦闘種別を取得。
        $tourId = Tournament_MasterService::TOUR_MAIN;
        $tourSvc = new Tournament_MasterService();
        $array['tournament'] = $tourSvc->needRecord($tourId);

        // 対戦相手一覧の取得と、他画面遷移時のbacktoパラメータの決定。
        // GET変数 did がない場合。
        if( empty($_GET['did']) ){

            // 対戦相手一覧を抽出。
            $battleUtil = new UserBattleUtil();
            $rivalList = $battleUtil->getRivalList($chara, $tourId, 8);
            $array['rivalList'] = $rivalList;

            // 抽出した対戦相手一覧をDBに保存、戻るときはその保存IDと共に戻るようにする。
            $dataId = $sessSvc->setData($rivalList);
            $backto = ViewUtil::serializeBackto(array('did'=>$dataId));

        // GET変数 did がある場合。
        }else {

            // DBに保存しておいた相手一覧を使用する。
            $array['rivalList'] = $sessSvc->needData($_GET['did']);
            $backto = ViewUtil::serializeBackto();
        }

        $array['rivalList_Num'] = count($array['rivalList']);

        // ユーザIDをすべて取得。
        $userIds = ResultsetUtil::colValues($array['rivalList'], 'user_id');

        // ユーザ情報を取得して、一覧にユーザ情報を埋め込む。
        $users = Service::create('User_Info')->getRecordsIn($userIds);
        foreach($array['rivalList'] as &$record){
            $record['user'] = $users[ $record['user_id'] ];
            $record['player_name'] = Text_LogService::get($record['name_id']);
            $record['member'] = Service::create('User_Member')->getMemberCount( $record['user_id'] );
            $gradeinfo = Service::create('Grade_Master')->needRecord($record['grade_id']);
            $record['grade_name'] =  $gradeinfo["grade_name"];

            // 画像情報を取得。
            $spec1 = $this->getFormation($record);
            $record['equip_info'] = $spec1;

        }
        unset($record);

        // 他者のページへ遷移するときの追加パラメータをセットする。
        $array['params'] =  array(
            'backto' => $backto,
        );

        $array['result'] = 'ok';

        return $array;

    }
}
