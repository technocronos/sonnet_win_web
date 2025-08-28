<?php

class GachaLineupAction extends UserBaseAction {

    public function execute() {

        $gachaSvc = new Gacha_MasterService();

        // 以下、ガチャの内容を表示する場合。

        // ガチャの詳細と中身のリストを取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);
        $this->setAttribute('gacha', $gacha);
        $this->setAttribute('list', $gachaSvc->getContents($_GET['gachaId']));

        return View::SUCCESS;
    }

}
