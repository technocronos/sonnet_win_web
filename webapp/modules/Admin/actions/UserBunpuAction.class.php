<?php

class UserBunpuAction extends AdminBaseAction {

    // 棒グラフのスケール
    const GRAPH_SCALE = 300;


    public function execute() {

        // デフォルト値の設定。
        if(strlen($_GET['from']) == 0  &&  strlen($_GET['to']) == 0) {
            $_GET['from'] = '';
            $_GET['to'] = '10000';
        }

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'from' => 'numonly',
            'to' => 'numonly',
            '_form' => array(
                array('lowerupper' => array('from', 'to')),
                'interval' => array('interval' => array('from', 'to', '50000')),
            ),
        ));
        $this->setAttribute('validator', $validator);

        // 入力値の検査。
        $validator->validate($_GET);

        // フォームが送信されていない、あるいはエラーがあるならココまで。
        if($validator->isError()  ||  empty($_GET['go']))
            return View::SUCCESS;

        // 省略されている場合はは補う。
        if(strlen($validator->values['from']) == 0) $validator->values['from'] = max(0, $validator->values['to'] - 9999);
        if(strlen($validator->values['to']) == 0) $validator->values['to'] = $validator->values['from'] + 9999;

        // 分布集計。
        $charaSvc = New Character_InfoService();
        $this->setAttribute('data', $charaSvc->getDistribution($validator->values['from'], $validator->values['to']));

        // 縦軸になる値をすべて抽出する。
        $rows = array();
        $stepFrom = (int)floor($validator->values['from'] / 1000);
        $stepTo =   (int)floor($validator->values['to'] / 1000);
        for($i = $stepFrom ; $i <= $stepTo ; $i++)
            $rows[$i] = sprintf('～%d', ($i+1)*1000 - 1);
        $this->setAttribute('rows', $rows);

        // 横軸になる階級をすべて取得。
        $grades = Service::create('Grade_Master')->getList('ASC');
        $this->setAttribute('cols', ResultsetUtil::colValues($grades, 'grade_name', 'grade_id'));

        // スケールをセット。
        $this->setAttribute("scale" , self::GRAPH_SCALE);

        return View::SUCCESS;
    }
}
