<?php

class GachaFreeAction extends UserBaseAction {

    const FREE_GACHA_ID = 9997;


    public function execute() {

        $gachaSvc = new Gacha_MasterService();

        // 今日、もう回しているかどうかを取得。ただし、チュートリアル中なら無条件で回せるようにする。
        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_GACHA) {
            $tryable = true;
        }else{
            $lastTry = Service::create('User_Property')->getProperty($this->user_id, 'free_gacha_date');
            $tryable = ( $lastTry < (int)date('Ymd') );
        }
        $this->setAttribute('tryable', $tryable);

        // トライしているならそれ用の処理。
        if($_POST  &&  $tryable)
            $this->tryFreeGacha();

        // 以下、ガチャの内容を表示する場合。

        // ガチャの詳細と中身のリストを取得。
        $this->setAttribute('list', $gachaSvc->getContents(self::FREE_GACHA_ID));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フリーチケット使用でガチャをまわす処理。
     */
    private function tryFreeGacha() {

        $gachaSvc = new Gacha_MasterService();
        $userItemSvc = new User_ItemService();

        // ガチャチュートリアル中ならステップアップ
        Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_GACHA);

        // ガチャからアイテムを一つ引く。ただしチュートリアル中は無条件で時計を出す。
        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_GACHA){
            $itemId = 1902;
        }else{
            $itemIds = $gachaSvc->drawItem(self::FREE_GACHA_ID);
            $itemId = $itemIds[0]["item_id"];
        }

        // 引いたアイテムをユーザに付与。
        $uitemId = $userItemSvc->gainItem($this->user_id, $itemId);

        // 無料ガチャを引いた記録をつける。
        Service::create('User_Property')->updateProperty($this->user_id, 'free_gacha_date', date('Ymd'));

        // 結果画面へ。
        Common::redirect('User', 'ItemGet', array('uitemId'=>$uitemId, 'backto'=>$_GET['backto']));
    }
}
