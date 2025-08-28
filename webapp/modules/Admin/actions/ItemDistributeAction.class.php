<?php


class ItemDistributeAction extends AdminBaseAction {

    // 棒グラフのスケール
    const GRAPH_SCALE = 200;


    public function execute() {

        // キャンペーン定義のインポート
        require_once(MO_WEBAPP_DIR.'/config/values.php');

        // 検査ルールの作成。
        $validator = new MyValidator(array());
        $this->setAttribute('validator', $validator);

        // キャンペーンの一覧を取得。
        $distributions = array();
        foreach($DISTRIBUTIONS as $distribute)
            $distributions[ $distribute['flag_id'] ] = $distribute['name'];
        $this->setAttribute('distributions', $distributions);

        // 入力値の検査。
        if($_GET['flag_id'])
            $validator->validate($_GET);

        // フォームの入力に問題があるならココまで
        if( !$validator->isValid() )
            return View::SUCCESS;

        // 人数を集計してビュー用に割り当てる。
        $this->sumup($validator->values['flag_id']);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された条件で集計して、ビューに割り当てる。
     *
     * @param int       キャンペーンのflag_idの値。
     */
    private function sumup($flagId) {

        $count = Service::create('Flag_Log')->countFlagHolders(Flag_LogService::DISTRIBUTION, $flagId);

        $this->setAttribute('total', $count);
    }
}
