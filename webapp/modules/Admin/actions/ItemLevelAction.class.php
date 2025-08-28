<?php

/**
 * 装備アイテムのステータスを反映する。
 */
class ItemLevelAction extends AdminBaseAction {

    public function execute() {

        // フォームが送信されているならそれを処理する。
        if($_POST)
            $this->processPost();

        // 装備アイテムをすべて取得。
        $items = Service::create('Item_Master')->getDurables();
        $this->setAttribute('items', ResultsetUtil::colValues($items, 'item_name', 'item_id'));

        // フォームが送信されている場合、該当するアイテムのレベルをすべて取得。
        if( isset($_POST['itemId']) )
            $this->setAttribute('levels', Service::create('Item_Level_Master')->getLevels($_POST['itemId']));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームが送信された場合の処理を行う。
     */
    private function processPost() {

        // 「データ」欄に入力される値の格納先になる列を定義。
        static $KEYS = array('exp', 'attack1', 'attack2', 'attack3', 'defence1', 'defence2', 'defence3', 'speed', 'defenceX');

        // データが入力されていないなら何もしない。
        if(strlen($_POST['data']) == 0)
            return;

        // 入力されたデータを改行で区切る。
        $data = explode("\n", $_POST['data']);

        // 一行ずつレコードに変換して、配列 $levels に格納していく。
        $levels = array();
        for($i = 0 ; $i < count($data) ; $i++) {

            // カラ行は無視。
            $datum = trim($data[$i]);
            if(strlen($datum) == 0)
                continue;

            // 水平タブで区切る。
            $values = explode("\t", $datum);

            // 列順の通りに値があるものとして、変数 $level にレコードを作成。
            $level = array();
            foreach($KEYS as $keyIndex => $key)
                $level[$key] = empty($values[$keyIndex]) ? 0 : (int)trim($values[$keyIndex]);

            // 配列 $levels に格納。
            $level['level'] = $i + 1;
            $levels[] = $level;
        }

        // 既存のレコードを置き換える。
        Service::create('Item_Level_Master')->replaceLevels($_POST['itemId'], $levels);
    }
}
