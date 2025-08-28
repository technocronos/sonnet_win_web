<?php

/**
 * 狼退治完了後、牧場で農夫達と会話する寸劇クエスト
 */
class Quest13003 extends DramaQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * changeFlowをオーバーライド
     */
    public function changeFlow(&$flow) {

        // 狼をトラップに巻き込んだ数を取得。
        $catchNum = Service::create('Flag_Log')->getValue(Flag_LogService::FLAG, $this->userId, 130020298);

        // その数に応じて、フローの "%sun's lines%" を置き換える。
        switch($catchNum) {
            case 0:
                $lines = AppUtil::getText("drama_master_flow_1300301_1");
                break;
            case 1: case 2:
                $lines = AppUtil::getText("drama_master_flow_1300301_2");
                break;
            case 3: case 4:
                $lines = AppUtil::getText("drama_master_flow_1300301_3");
                break;
            default:
                $lines = AppUtil::getText("drama_master_flow_1300301_4");
        }

        $flow = str_replace("%sun's lines%", $lines, $flow);

        // 記念品ゲットのメッセージを置き換える。
        $item = Service::create('Item_Master')->needRecord( $this->getMemorialItem($catchNum) );
        $msg = str_replace("{0}", $item['item_name'], AppUtil::getText("TEXT_GET_2"));

        $flow = str_replace("%get message%", $msg, $flow);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド
     */
    public function endQuest($success, $code) {

        // 狼をトラップに巻き込んだ数を取得。
        $catchNum = Service::create('Flag_Log')->getValue(Flag_LogService::FLAG, $this->userId, 130020298);

        // 記念品をプレゼント
        Service::create('User_Item')->gainItem($this->userId, $this->getMemorialItem($catchNum));

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // 師匠の家へ移動させる。
            $userSvc->movePlace($this->userId, 11);
        }

        // あとは基底に任せる。
        parent::endQuest($success, $code);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 農夫の息子がくれる記念品のIDを返す。
     */
    private function getMemorialItem($catchNum) {

        if($catchNum <= 1) {
            return  1001;           // くすりびん
        }else if($catchNum <= 3) {
            return 14003;           // ぬいぐるみ
        }else if($catchNum <= 4) {
            return 14008;           // 猫の子盾
        }else {
            return 12003;           // 盗賊のベスト
        }
    }
}
