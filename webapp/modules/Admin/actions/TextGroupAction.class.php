<?php

class TextGroupAction extends AdminBaseAction {

    public function execute() {

        // モバゲでのみ実行する。
        if(PLATFORM_TYPE == 'mbga') {

            $api = new MobageApi();

            // 作成フォームが送信されているなら。
            if(isset($_POST['create'])) {
                $this->setAttribute('response', $api->createTextGroup($_POST['id']));
            }

            // 削除フォームが送信されているなら。
            if(isset($_POST['delete'])) {
                $this->setAttribute('response', $api->deleteTextGroup($_POST['id']));
            }

            // 現在あるグループを確認。
            $this->setAttribute('groups', $api->getTextGroups());
        }

        return View::SUCCESS;
    }
}
