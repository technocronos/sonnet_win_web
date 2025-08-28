<?php

class OshiraseEditAction extends AdminBaseAction {

    public function execute() {

        $svc = new Oshirase_LogService();

        // 結果画面を表示することになっているならアクションの処理はない。
        if(isset($_GET['result']))
            return View::SUCCESS;

        // 検証ルールを作成。
        $validator = new MyValidator(array(
            'importance' => array('required', 'numonly'),
            'title' => 'required',
            'body' => 'required',
            'notify_at' => array('datetime'),
        ));
        $this->setAttribute('validator', $validator);

        // フォームが送信されているならば。
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 削除なら、レコードを削除して結果画面へ。
            if(isset($_POST['delete'])) {
                $svc->deleteRecord($_GET['id']);
                Common::redirect('Admin', 'OshiraseEdit', array('result'=>1));

            // 保存の場合。
            }else {

                // 入力を検証。
                $validator->validate();

                // 問題ないなら、レコードを保存して結果画面へ。
                if($validator->isValid()) {
                    $this->save($validator);
                    Common::redirect('Admin', 'OshiraseEdit', array('result'=>1));
                }
            }
        }

        // 編集画面を表示する準備。idが指定されている場合は初期値としてロードしておく。
        if(!empty($_GET['id']))
            $validator->defaults = $svc->needRecord($_GET['id']);

        // 重要度の<option>をセットする。
        $this->setAttribute('importances', Oshirase_LogService::$IMPORTANCES);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 保存処理を行う。
     */
    private function save($validator) {

        $record = array();
        if(isset($_GET['id'])) $record['oshirase_id'] = $_GET['id'];
        $record['importance'] = $validator->values['importance'];
        $record['title'] = $validator->values['title'];
        $record['body'] = $validator->values['body'];
        $record['title_en'] = $validator->values['title_en'];
        $record['body_en'] = $validator->values['body_en'];
        $record['notify_at'] = $validator->values['notify_at'] ?: date('Y/m/d H:i:s', time()-8*60);
        $record['update_at'] = date('Y/m/d H:i:s');

        Service::create('Oshirase_Log')->saveRecord($record);
    }
}
