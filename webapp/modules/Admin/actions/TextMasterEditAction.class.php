<?php

class TextMasterEditAction extends AdminBaseAction {

    public function execute() {

        $svc = new Text_MasterService();

        // 結果画面を表示することになっているならアクションの処理はない。
        if(isset($_GET['result']))
            return View::SUCCESS;

        // 検証ルールを作成。
        $validator = new MyValidator(array(
            'symbol' => 'required',
        ));
        $this->setAttribute('validator', $validator);

        // フォームが送信されているならば。
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 削除なら、レコードを削除して結果画面へ。
            if(isset($_POST['delete'])) {
                $svc->deleteRecord($_GET['id']);
                Common::redirect('Admin', 'TextMasterEdit', array('result'=>1));

            // 保存の場合。
            }else {

                // 入力を検証。
                $validator->validate();

                // 問題ないなら、レコードを保存して結果画面へ。
                if($validator->isValid()) {
                    $this->save($validator);
                    Common::redirect('Admin', 'TextMasterEdit', array('result'=>1));
                }
            }
        }

        // 編集画面を表示する準備。idが指定されている場合は初期値としてロードしておく。
        if(!empty($_GET['id'])){
            $record = $svc->needRecord($_GET['id']);
            $this->setAttribute('record', $record);
            $validator->defaults = $record;
        }

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 保存処理を行う。
     */
    private function save($validator) {

        $record = array();
        if(isset($_GET['id'])) $record['text_id'] = $_GET['id'];
        $record['symbol'] = $validator->values['symbol'];
        $record['ja'] = $validator->values['ja'];
        $record['en'] = $validator->values['en'];
        $record['category'] = $validator->values['category'];
        $record['characount'] = $validator->values['characount'];

        Service::create('Text_Master')->saveRecord($record);
    }
}
