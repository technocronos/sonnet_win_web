<?php

class UserMessageAction extends AdminBaseAction {

    public function execute() {

        if(empty($_GET['page']))  $_GET['page'] = 0;

        $validator = new MyValidator();
        $this->setAttribute('validator', $validator);
        $validator->validate($_GET);

        // 種別の選択肢を作成。
        $this->setAttribute('types', array('TWT'=>'つぶやき', 'MSG'=>'メッセージ', 'BTL'=>'バトルコメント', 'CHR'=>'キャラ名'));

        // お知らせの一覧を取得。
        if($_GET['type']) {
            $list = Service::create('Text_Log')->getList(array('type'=>$_GET['type']), 100, $_GET['page']);
            $this->setAttribute('list', $list);
        }

        return View::SUCCESS;
    }
}
