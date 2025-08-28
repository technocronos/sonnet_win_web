<?php

class QuestDramaAction extends DramaBaseAction {

    protected function onExecute() {

        // 指定されたクエストをロード。
        $this->questObj = QuestCommon::factory($_GET['questId'], $this->user_id);

        // クエスト種別がおかしい場合はエラー
        if( !($this->questObj instanceof DramaQuest) )
            throw new MojaviException('クエスト種別が不正');

        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
            //スマホ版は地点でクエスト実行を縛るのはやめる。この地点で食い違っていたら移動してしまう。
            if($_GET['placeId'])
                $this->processMove();
        }

        // 本当に実行できる状態にあるのかチェック。
        if( !$this->questObj->isExecutable() )
            Common::redirect('Swf', 'Main', array("firstscene" => "quest"));

        // 再生する寸劇のIDを取得。
        $this->dramaId = $this->questObj->getDramaId();

        // ドラマがちゃんと用意されているかチェック。
        // 用意されていない場合はアンダーコンストラクションのページへ。
        if( !Service::create('Drama_Master')->getRecord($this->dramaId) )
            Common::redirect('User', 'Static', array('id'=>'UnderConstruct'));

        // 行動ptが足りない場合は回復アイテムへの誘導ページへ。
        if($this->userInfo['action_pt'] < Service::create('Quest_Master')->getConsumePt($_GET['questId'])) {
            $useto = ViewUtil::serializeBackto();
            $backto = ViewUtil::serializeParams(array('module'=>'User', 'action'=>'QuestList'));
            Common::redirect('User', 'Suggest', array('type'=>'ap', 'backto'=>$backto, 'useto'=>$useto));
        }

        // 再生が終了して戻ってきている場合はその処理へ。
        if($_GET['end'])
            $this->processEnd();

        // クエストの開始の処理を行う。
        $this->questObj->startQuest();

        // 戻り先の設定。
        $endToUrl = Common::genContainerUrl(
            'Swf', 'QuestDrama', array('questId'=>$_GET['questId'], '_sign'=>true, 'end'=>1), true
        );
        if(URL_TYPE == "container")
            $this->endTo = $endToUrl . '%26code%3D';
        else
            $this->endTo = $endToUrl . '&code=';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 再生終了時の処理を行う。
     */
    private function processEnd() {

        // GETリクエストだけで、該当のクエストを終了状態に出来てしまうので、一応CSRF対策…いらん気もするけど。
        if( !Common::validateSign() )
            Common::redirect('Swf', 'Main', array("firstscene" => "quest"));

        // クエスト終了処理。
        $this->questObj->endQuest(true, $_GET['code']);

        // クエスト一覧にリダイレクト
        Common::redirect('Swf', 'Main', array("firstscene" => "quest"));
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

        Common::redirect('Swf', 'QuestDrama', array('questId'=>$_GET['questId']));
    }
}
