<?php

class OshiraseListAction extends AdminBaseAction {

    public function execute() {

        if(empty($_GET['page'])) $_GET['page'] = 0;

        // お知らせの一覧を取得。
        $list = Service::create('Oshirase_Log')->getList(array('all'=>true), 20, $_GET['page']);
        $this->setAttribute('list', $list);

        return View::SUCCESS;
    }
}
