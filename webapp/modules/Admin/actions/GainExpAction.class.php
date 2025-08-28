<?php

/**
 * キャラクター経験値付与。
 * デバックメニュー。
 */
class GainExpAction extends AdminBaseAction {

    public function execute() {

        $charaSvc = new Character_InfoService();

        // フォームが送信されている場合。
        if($_POST) {

            $charaSvc->gainExp($_POST['charaId'], $_POST['exp']);
            Common::redirect(array('_self'=>true, 'charaId'=>$_POST['charaId']));
        }

        // キャラ情報を取得。
        if( isset($_GET['charaId']) )
            $this->setAttribute('chara', $charaSvc->getRecord($_GET['charaId']));

        return View::SUCCESS;
    }
}
