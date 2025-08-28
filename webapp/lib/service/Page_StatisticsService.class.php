<?php

class Page_StatisticsService extends Service {

    // ページごとのカウントのとり方。
    private static $PAGES = array(

        // "User" モジュール
        'User' => array(
            'Index' => array('rate'=>50),
            'BattleResult' => array('rate'=>100),
            'GachaList' => array('rate'=>5),
            'GachaDetail' => array('rate'=>2),
            'FieldEnd' => array('rate'=>30),
            'FieldReady' => array('rate'=>30),
            'FieldReopen' => array('rate'=>30),
            'HisPage' => array('rate'=>30),
            'QuestList' => array('rate'=>30),
            'RivalList' => array('rate'=>30),
            'Status' => array('rate'=>30),
            'Suggest' => array('rate'=>30),
        ),

        // "Swf" モジュール
        'Swf' => array(
            'Main' => array('rate'=>100),
            'Battle' => array('rate'=>100),
            'Tutorial' => array('rate'=>50),
            'Sphere' => array('rate'=>100),
            'SphereCommand' => array('rate'=>500),
            'FieldDrama' => array('rate'=>30),
            'QuestDrama' => array('rate'=>30),
            'Tutorial' => array('rate'=>30),
            'Detain' => array('rate'=>1),

            // 以降、カウントの必要がないもの。
            'CharaPair' => array('rate'=>0),        // バトル確認でキャラが向かい合ってるFLASH。
            'BattleOpen' => array('rate'=>0),       // バトル開始コマンド
        ),
    );


    // public メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された期間のページ集計を行う。
     *
     * @param string    集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param string    集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    次の列を含む結果セット。
     *                      date    日付
     *                      page    ページ識別子
     *                      point   アクセス数
     */
    public function sumupByDate($from, $to) {

        $sql = '
            SELECT date
                 , page
                 , SUM(count * rate) AS point
            FROM page_statistics
            WHERE date >= ?
              AND date < ?
            GROUP BY date, page
        ';

        return $this->createDao(true)->getAll($sql, array($from, $to));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 現在アクセス中のページをカウントする。
     */
    public function countAccess() {

        // POSTメソッドはカウントしない。
        if($_SERVER['REQUEST_METHOD'] == 'POST')
            return;

        // モジュール名とアクション名から、カウント設定を取得する。
        $context = Controller::getInstance()->getContext();
        $setting = self::$PAGES[ $context->getModuleName() ][ $context->getActionName() ];

        // カウント設定の省略に対応する。
        if(!$setting)  $setting = array();
        if( !isset($setting['rate']) )         $setting['rate'] = 10;
        if( !isset($setting['param_mask']) )   $setting['param_mask'] = array();

        // カウントしないことになっている、あるいは、精度的に無視して良さそうならここまで。
        if( $setting['rate'] == 0  ||  1 < mt_rand(1, $setting['rate']) )
            return;

        // ページパラメータマスクに "module", "action" を補う。
        $setting['param_mask'][] = 'module';  $setting['param_mask'][] = 'action';

        // GET変数の中から、ページの識別に使用するものを抽出。
        $page = Common::cutRefArray($_GET);
        $page['module'] = $context->getModuleName();
        $page['action'] = $context->getActionName();
        $page = array_intersect_key( $page, array_flip($setting['param_mask']) );

        // キーを昇順に並べ替えて文字列とする。
        ksort($page);
        $page = http_build_query($page);

        // カウントレコードを作成。
        $record = array(
            'page' => $page,
            'date' => date('Y/m/d'),
            'rate' => $setting['rate'],
            'count' => 1,
        );

        // カウントアップ or INSERT。
        $this->saveRecord($record, array(
            'count' => array('sql'=>'count + 1')
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('date', 'page', 'rate');
}
