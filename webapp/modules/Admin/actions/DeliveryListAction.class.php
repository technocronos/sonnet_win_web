<?php

class DeliveryListAction extends AdminBaseAction {

    public function execute() {

        if(empty($_GET['page'])) $_GET['page'] = 0;

        // 配信の一覧を取得。
        $list = Service::create('Delivery_Log')->getList(20, $_GET['page']);

        // 検索条件をクエリストリングに変換する。
        foreach($list['resultset'] as &$record) {
            $record['target_string'] = http_build_query($record['target']);
        }unset($record);

        // ビューに割り当てる。
        $this->setAttribute('list', $list);

        return View::SUCCESS;
    }
}
