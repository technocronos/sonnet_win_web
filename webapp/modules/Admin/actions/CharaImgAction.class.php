<?php

/**
 * キャラクターの画像をチェックするアクション。
 * デバックメニュー。
 */
class CharaImgAction extends AdminBaseAction {

    public function execute() {

        // イメージが要求されている場合。
        if(isset($_GET['type'])) {
            $this->responseImage();
            return View::NONE;
        }

        $svc = new Equippable_MasterService();
        $this->setAttribute('pla_weapon', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_WEAPON));
        $this->setAttribute('pla_body', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_BODY));
        $this->setAttribute('pla_head', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_HEAD));
        $this->setAttribute('pla_shield', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_SHIELD));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    private function responseImage() {

        // 疑似キャラレコードを作成する。
        switch($_GET['race']) {
            case 'PLA':
                $chara = array(
                    'race' => $_GET['race'],
                    'graphic_id' => Character_InfoService::INITIAL_FACE,
                    'admin_check' => array(
                        Mount_MasterService::PLAYER_WEAPON => $_GET['part'][0],
                        Mount_MasterService::PLAYER_BODY =>   $_GET['part'][1],
                        Mount_MasterService::PLAYER_HEAD =>   $_GET['part'][2],
                        Mount_MasterService::PLAYER_SHIELD => $_GET['part'][3],
                    ),
                );
                break;
            case 'MOB':
                $chara = array(
                    'race' => $_GET['race'],
                    'graphic_id' => $_GET['part'][0],
                );
                break;
        }

        // イメージ作成＆パス取得。
        $imgPath = CharaImageUtil::getImageFromChara($chara, $_GET['type']);

        // 出力。
        header('Content-Type: image/' . ($_GET['type'] == 'swf' ? 'png' : 'gif'));
        readfile($imgPath);
    }
}
