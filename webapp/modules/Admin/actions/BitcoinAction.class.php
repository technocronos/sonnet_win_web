<?php


class BitcoinAction extends AdminBaseAction {

    public function execute() {

        if(empty($_GET['page'])) $_GET['page'] = 0;

        // 配信の一覧を取得。
        $list = Service::create('Vcoin_Payment_Log')->getList(1000, $_GET['page']);
//Common::varDump($list);
        $total_amount = 0;

        foreach($list["resultset"] as &$row){
            $user = Service::create('User_Info')->getRecord($row["user_id"]);

            $text_log = Service::create('Text_Log')->getWriter($user["user_id"]);
            $row["chara_name"] = $text_log[0]["body"];

            $total_amount += $row["amount"];
        }

        // ビューに割り当てる。
        $this->setAttribute('list', $list);
        $this->setAttribute('total_amount', $total_amount);

        return View::SUCCESS;
    }
}
