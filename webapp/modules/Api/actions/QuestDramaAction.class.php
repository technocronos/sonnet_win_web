<?php

class QuestDramaAction extends ApiDramaBaseAction {

    protected function onExecute() {

        // 指定されたクエストをロード。
        $this->questObj = QuestCommon::factory($_GET['questId'], $this->user_id);

        // クエスト種別がおかしい場合はエラー
        if( !($this->questObj instanceof DramaQuest) )
            throw new MojaviException('クエスト種別が不正');

        //地点でクエスト実行を縛るのはやめる。この地点で食い違っていたら移動してしまう。
        if($_GET['placeId'])
            $this->processMove();

        // 本当に実行できる状態にあるのかチェック。
        if( !$this->questObj->isExecutable() ){
            $array['errscene'] = Common::genContainerUrl('Api', 'Quest', array());
        }

        // 再生する寸劇のIDを取得。
        $this->dramaId = $this->questObj->getDramaId();

        // ドラマがちゃんと用意されているかチェック。
        // 用意されていない場合はアンダーコンストラクションのページへ。
        if( !Service::create('Drama_Master')->getRecord($this->dramaId) )
            $array['under_construct'] = true;

        // 行動ptが足りない場合は回復アイテムへの誘導ページへ。
        if($this->userInfo['action_pt'] < Service::create('Quest_Master')->getConsumePt($_GET['questId'])) {
            $useto = ViewUtil::serializeBackto();
            $backto = ViewUtil::serializeParams(array('module'=>'User', 'action'=>'QuestList'));
            $array['errscene'] = Common::genContainerUrl('Api', 'Suggest', array('type'=>'ap', 'backto'=>$backto, 'useto'=>$useto));
        }

        // 再生が終了して戻ってきている場合はその処理へ。
        if($_GET['end']){
            $array = $this->processEnd();
            return $array;
        }

        // クエストの開始の処理を行う。
        $this->questObj->startQuest();

        $array["dramaId"] = $this->dramaId;

        $array['result'] = 'ok';

        // 戻り先の設定。
        return $array;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 再生終了時の処理を行う。
     */
    private function processEnd() {

        // クエスト終了処理。
        $this->questObj->endQuest(true, $_GET['code']);

        // クエスト一覧にリダイレクト
        $array['nextscene'] =  Common::genContainerUrl('Api', 'Quest', array());
        return $array;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * flowCompile() をオーバーライド。
     */
    protected function flowCompile($flow) {

        // フローの中で、プログラムで変更する箇所があれば、反映する。
        $this->questObj->changeFlow($flow);

        return parent::flowCompile($flow);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 移動している場合の処理。
     */
    private function processMove() {

        $userSvc = new User_InfoService();

        // 一応、移動できるかどうかチェック。
        if( !Service::create('Place_Master')->isMovable($this->user_id, $_GET['placeId']) )
            throw new MojaviException('移動できない場所に移動しようとした');

        // 移動チュートリアルを終了させる。
        $userSvc->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_MOVE);

        // 移動。
        $userSvc->movePlace($this->user_id, $_GET['placeId']);

    }
}
