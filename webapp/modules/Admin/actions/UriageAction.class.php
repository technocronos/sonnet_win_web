<?php


class UriageAction extends AdminBaseAction {

    // 棒グラフのスケール
    const MONEY_SCALE = 10000;      // 金額
    const AMOUNT_SCALE = 100;       // 数量


    public function execute() {

        // デフォルト値の設定。
        if(strlen($_GET['from']) == 0  &&  strlen($_GET['to']) == 0) {
			$_GET['from'] = sprintf('%04d-%02d-%02d', date('Y'), date('m'), 1);
            //$_GET['from'] = date('Y/m/d', strtotime('-1month'));
            $_GET['to'] = '';
        }

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'from' => 'datetime',
            'to' => array('ifempty'=>date('Y/m/d'), 'dateend'),
            '_form' => array(
                array('lowerupper' => array('from', 'to')),
                'interval' => array('dateinterval' => array('from', 'to', '1000day')),
            ),
        ));
        $this->setAttribute('validator', $validator);

        // 入力値の検査。
        $validator->validate($_GET);

        // エラーがあるならココまで。
        if($validator->isError())
            return View::SUCCESS;

        // fromが省略されている場合はtoの2ヶ月前とする。
        if(!$validator->values['from']) $validator->values['from'] = DateTimeUtil::add('-2month', $validator->values['to'], 'Y/m/d H:i:s');

        // 売上集計してビュー用に割り当てる。
        $this->sumupSales($validator->values['from'], $validator->values['to']);

        // 課金アイテムの一覧を取得してビューに割り当てる。
        $this->setAttribute('items', $this->getItems());

        // その他ビュー用割り当て。
        $this->setAttribute('moneyScale', self::MONEY_SCALE);
        $this->setAttribute('amountScale', self::AMOUNT_SCALE);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された期間の売り上げを集計して、ビューに割り当てる。
     *
     * @param string    期間開始の日時
     * @param string    期間終了の日時
     */
    private function sumupSales($from, $to) {

        // 売上集計。
        $svc = new Payment_LogService();
        $data = $svc->sumupPayment($from, $to);

        // 範囲内の日付をすべて抽出。
        $toTime = strtotime($to);
        $fromTime = strtotime($from);
        $rows = array();
        for($time = $toTime - 1 ; $time >= $fromTime ; $time = strtotime('-1day', $time))
            $rows[] = date('Y-m-d', $time);

        // 行方向に日付、列方向にアイテム種別とIDをとる2次元配列に変換する。
        if(PLATFORM_TYPE == "nati"){
            $table = ResultsetUtil::makeTable($data, 'sale_date', 'os', null, $rows);
        }else{
            $table = ResultsetUtil::makeTable($data, 'sale_date', 'item', null, $rows);
        }
        // 作成されたテーブルを全走査して、"day_sales" という列を加える。
        foreach($table as &$record) {

            if($record) {
                $record['day_sales'] = 0;
                foreach($record as $cell)
                    $record['day_sales'] += $cell['sales'];
            }

        }unset($record);

        // ビュー用に割り当てる。
        $this->setAttribute('table', $table);

        // "day_sales" 列を合算して、売上合計を求める。
        $this->setAttribute('totalSales', ResultsetUtil::sum($table, 'day_sales'));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 課金アイテムの一覧を返す。
     *
     * @return array    アイテム種別とIDを連結した文字列をキー、その名前を値とする配列。
     */
    private function getItems() {

        if(PLATFORM_TYPE == "nati"){
            $result['ios'] = "ios";
            $result['android'] = "android";

            return $result;
        }

        // 戻り値初期化。
        $result = array();

        // 単品売りの課金アイテムの一覧を戻り値へ。
        $items = Service::create('Shop_Content')->getSaleList(Shop_ContentService::COIN_SHOP);
        foreach($items as $item)
            $result['IT' . $item['item_id']] = $item['item']['item_name'];

        // ガチャの一覧を戻り値へ。
        $gachas = Service::create('Gacha_Master')->getAllRecords();
        foreach($gachas as $gacha)
            $result['GC' . $gacha['gacha_id']] = $gacha['gacha_name'];

        // リターン。
        return $result;
    }
}
