<?php

class GachaListAction extends UserBaseAction {

    public function execute() {

        // 指定されていないURL変数を補う。
        if( empty($_GET['page']) )    $_GET['page'] = '0';

        // ガチャの一覧を取得。
        $gachaSvc = new Gacha_MasterService();
        $this->setAttribute('list', $gachaSvc->getGachaList($this->user_id, 5, $_GET['page']));

        // ユーザのレベルを取得。
        $charaSvc = new Character_InfoService();
        $chara = Service::create('Character_Info')->needAvatar($this->user_id);
        $this->setAttribute('userLevel', $chara['level']);

        // 共通フリーチケットの数を数える。
        $count = Service::create('User_Item')->getHoldCount($this->user_id, Gacha_MasterService::FREETICKET_ID);
        $this->setAttribute('ticketCount', $count);

        // 無料ガチャをまわせるかどうかを取得。
        $tryable = (int)date('Ymd') > Service::create('User_Property')->getProperty($this->user_id, 'free_gacha_date');
        $this->setAttribute('freeGacha', $tryable);

        return View::SUCCESS;
    }
}
