<?php

class DeliveryEditAction extends AdminBaseAction {

    public function execute() {

        // 結果画面を表示することになっているならアクションの処理はない。
        if(isset($_GET['result']))
            return View::SUCCESS;

        // 検証ルールを作成。
        $rule = array();
        $rule['title'] = 'required';
        $rule['body'] = 'required';
        $rule['start_at'] = 'datetime';

        if(PLATFORM_TYPE == 'mbga')
            unset($rule['title']);

        $validator = new MyValidator($rule);
        $this->setAttribute('validator', $validator);

        // フォームが送信されているならば。
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 取消なら、レコードを削除して結果画面へ。
            if(isset($_POST['stop'])) {
                Service::create('Delivery_Log')->stopDelivery($_GET['id']);
                Common::redirect('Admin', 'DeliveryEdit', array('result'=>1));

            // 保存の場合。
            }else {

                // 入力を検証。
                $validator->validate();

                // 問題ないなら、レコードを保存して結果画面へ。
                if($validator->isValid()) {
                    $this->save($validator->values);
                    Common::redirect('Admin', 'DeliveryEdit', array('result'=>1));
                }
            }
        }

        // 編集画面を表示する準備。idが指定されている場合は初期値としてロードしておく。
        if(!empty($_GET['id']))
            $validator->defaults = Service::create('Delivery_Log')->needRecord($_GET['id']);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 保存処理を行う。
     */
    private function save($values) {

        // レコードを作成。
        $record = array();
        if(strlen($_GET['id'])) $record['delivery_id'] = $_GET['id'];
        $record['title'] = $values['title'];
        $record['body'] = $values['body'];
        $record['target'] = $values['target'];
        $record['expect_count'] = $values['expect'];
        $record['start_at'] = $values['start_at'] ?: date('Y/m/d H:i:s');

        // 保存。ただしUPDATE時、target列とexpect_count列は更新しない。
        Service::create('Delivery_Log')->saveRecord($record, array(
            'target'=>false, 'expect_count'=>false
        ));
    }
}
