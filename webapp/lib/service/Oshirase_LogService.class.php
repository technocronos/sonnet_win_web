<?php

class Oshirase_LogService extends Service {

    public static $IMPORTANCES = array(
        0 => '日誌',
        1 => '予告・お知らせ',
        2 => '注意・重大なお知らせ',
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で受け取った "body" 列の値を、HTMLに変換して返す。
     *
     * @param string    "body" 列の値。
     * @return string   HTMLに変換したもの。
     */
    public static function getBodyHtml($body) {

        $bodyHtml = $body;

        // タグ表記の変換
        $bodyHtml = preg_replace('#<color\s+([^>]+)>#', '<span style="color:$1">', $bodyHtml);
        $bodyHtml = preg_replace('#<large>#', '<span style="font-size:medium">', $bodyHtml);
        $bodyHtml = preg_replace_callback('#<link\s+([^>]+)>#', 'Oshirase_LogService::getBodyUrl', $bodyHtml);

        // 閉じタグの処理。
        $bodyHtml = preg_replace('#</(?:color|large)>#', '</span>', $bodyHtml);
        $bodyHtml = preg_replace('#</link>#', '</a>', $bodyHtml);

        // 改行、空白文字の反映
        $bodyHtml = nl2br($bodyHtml);

        // リターン。
        return $bodyHtml;
    }

    /**
     * getBodyHtml() のヘルパメソッド。<link> を <a> に置き換える。
     */
    public static function getBodyUrl($matches) {

        parse_str($matches[1], $urlParams);

        return sprintf('<a href="%s">', Common::genContainerURL($urlParams));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 間近のレコードを数件取得する。
     *
     * @param string    種別。"notice":お知らせ か "diary":開発日誌 のいずれか
     * @param int       取得件数。
     * @return array    取得したレコード。
     */
    public function getNewestEntries($type = 'notice', $limit = 3) {

        return $this->selectResultset(array(
            'importance' => ($type == 'notice') ? array('sql'=>'> 0') : 0,
            'notify_at' => array('sql'=>'<= FROM_UNIXTIME(?)', 'value'=>time()&0x7FFFFE00),    // NOW()を使ってないのはクエリキャッシュへの期待。だいたい8.5分くらいの精度。
            'ORDER BY' => 'notify_at DESC',
            'LIMIT' => $limit,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * お知らせの一覧を、ページを指定して取得する。
     *
     * @param array     検索条件。次のキーを持つ配列。
     *                      type    種別。"notice":お知らせ か "diary":開発日誌 のいずれか
     *                              省略時はすべて。
     *                      all     true か false で。公開前のお知らせも含めるかどうか。
     *                              省略時は false。
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getList($condition, $numOnPage, $page) {
        $lang = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : 0;

        $where = array();
        $where ['ORDER BY'] = 'notify_at DESC';

        // 種別の制限。
        if(isset($condition['type']))
            $where['importance'] = ($condition['type'] == 'notice') ? array('sql'=>'> 0') : 0;

        if($lang != 0){
            $where['title_en'] = array('sql'=>'IS NOT NULL');
        }

        // 公開前のお知らせも含めるかどうか。
        if( empty($condition['all']) )
            $where['notify_at'] = array('sql'=>'<= FROM_UNIXTIME(?)', 'value'=>time()&0x7FFFFE00);    // NOW()を使ってないのはクエリキャッシュへの期待。だいたい8.5分くらいの精度。

        return $this->selectPage($where, $numOnPage, $page);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'oshirase_id';

    protected $isMaster = true;


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecordをオーバーライド。以下の拡張列を追加する。
     *     isNew        24時間以内に作成されたものならtrue、そうでないならfalse。
     *     importance_text    importanceの値をテキストで表現したもの。
     *     importance_icon    importanceの値を絵文字で表現したもの。
     */
    protected function processRecord(&$record) {

        // "isNew" 列をセット。
        $record['isNew'] = time()-24*60*60 <= strtotime($record['notify_at']);

        // "importance_text" と "importance_icon" 列をセット。
        $record['importance_text'] = isset(self::$IMPORTANCES[$record['importance']]) ? self::$IMPORTANCES[$record['importance']] : '';
        $record['importance_icon'] = mb_substr($record['importance_text'], 0, 1, 'UTF-8');
    }
}
