<?php

class TextMasterAction extends AdminBaseAction {

    public function execute() {

        $this->setAttribute('category', Text_MasterService::$CATEGORY);

        $this->setAttribute('progress', array(0=>"指定なし", 1=>"未作業", 2=>"作業済み"));

        if(empty($_GET['page'])) $_GET['page'] = 0;

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'go' => 'required',
            'symbol' => 'string',
            'characount' => 'string',
            'ja' => 'string',
            'en' => 'string',
            'category' => 'bool',
            'progress' => 'bool',
        ));
        $this->setAttribute('validator', $validator);

        // 入力値の検査。
        $validator->validate($_GET);

        // エラーがあるならココまで。
        if($validator->isError())
            return View::SUCCESS;

        // 指定された条件のユーザを取得する。
        $this->find($validator->values);

        // お知らせの一覧を取得。
        //$list = Service::create('Oshirase_Log')->getList(array('all'=>true), 20, $_GET['page']);
        

        return View::SUCCESS;
    }

   //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された条件のユーザを取得する。
     *
     * @param object    フォームの入力値を正規化したバリデータ。
     */
    private function find($condition) {

        // 先に検索条件をシリアル化しておく。
        $this->setAttribute( 'target', urlencode(json_encode($condition)) );

        // ユーザ検索。
        $limit = 100;
        $list = Service::create('Text_Master')->findRecords($condition, $limit, $_GET["page"]);

        // ビューに割り当て。
        $this->setAttribute('list', $list);
    }


}
