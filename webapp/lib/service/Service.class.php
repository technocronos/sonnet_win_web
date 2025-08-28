<?php

/**
 * Serviceオブジェクトの基底クラス
 */
class Service {

    // 静的publicメンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたサービスオブジェクトを new して返す。
     * newした後にすぐメソッドを呼びたいときなどに使うショートカットメソッド。
     *    例) Service::create('Foo')->func();
     *
     * @param string    サービスクラス名。例えば FooService を作成したいなら "Foo"。
     * @return Service  指定されたサービスクラスのオブジェクト。
     */
    public static function create($svcName) {
        $svcName .= 'Service';
        return new $svcName();
    }


    // public
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 主キーを指定してレコードを取得する。
     *
     * 例)
     *     $svc->getRecord(123);            // 単一列主キーの場合。主キー値 123 のレコードを取得。
     *     $svc->getRecord(12, 34);         // 複数列主キーの場合。主キー値 12-34 のレコードを取得。
     *
     *     // なんでもいいからレコードを一つ取得。
     *     // レコードが一つしかないことが分かっているテーブル等で使用する。
     *     $svc->getRecord();
     *
     * 注) このメソッドは戻り値を一つのリクエストを通してキャッシュしているので、
     *     同じ主キー値を指定して複数回呼び出すと、DBへの問い合わせをせずに前回のキャッシュを返す。
     *
     * @param           主キー。例を参照。
     * @return array    値が単一値で指定されている場合はレコード。なかった場合は null。
     */
    public function getRecord(/* 可変引数 */) {

        $args = func_get_args();

        // キャッシュがある場合はキャッシュから返す。
        $cache = $this->getPkCache($args);
        if($cache !== false)
            return $cache;

        // 指定されているレコードの取得と加工。
        $record = $this->queryRecord($args);
        if($record)
            $this->processRecord($record);

        // 戻り値をキャッシュしてから、リターン。
        $this->setPkCache($args, $record);
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getRecord と同じだが、レコードが存在しなかった場合に例外を発生させてエラーにする。
     */
    public function needRecord(/* 可変引数 */) {

        // 呼び出しを getRecord に転送。
        $args = func_get_args();
        $result = call_user_func_array(array($this, 'getRecord'), $args);

        // なかったらエラー。
        if(!$result) {
            throw new MojaviException(sprintf('必要なレコードがありません。サービス:%s, 主キー:%s'
                , get_class($this)
                , print_r($args, true)
            ));
        }

        // あるならリターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getRecord と同じだが、主キーを複数指定して、複数のレコードを取得する。
     *
     * 例1) 単一列主キーの場合。主キー値 123, 456, 789 のレコードを取得。
     *
     *     $result = $svc->getRecord(array(123, 456));
     *
     *     // この時点で、$result は次のような配列になる。
     *     array(
     *         123 => array('col1'=>1, 'col2'=>2),      // レコードを表す配列
     *         456 => array('col1'=>2, 'col2'=>3),      // レコードを表す配列
     *         789 => null,                             // レコードがなかった場合。
     *     )
     *
     * 例2) 複数列主キーの場合。主キー値 12-1, 12-2, 14-5 のレコードを取得。
     *
     *     $result = $svc->getRecord(array(array(12,1), array(12,2), array(14,5)));
     *
     *     // この時点で、$result は次のような配列になる。
     *     array(
     *         12 => array(
     *             1 => array('col1'=>1, 'col2'=>2),      // レコードを表す配列
     *             2 => array('col1'=>1, 'col2'=>2),      // レコードを表す配列
     *         ),
     *         14 => array(
     *             5 => array('col1'=>1, 'col2'=>2),      // レコードを表す配列
     *         ),
     *     )
     *
     * 第二引数にfalseを指定すると、主キー値をキーとする配列でなく、単なる結果セットとして返す。
     * この場合、該当レコードなしを表すnullは配置されない。
     */
    public function getRecordsIn($pks, $keyShift = true) {

        // 引数正規化。
        if(!is_array($pks)) $pks = array($pks);

        // そんなに大量じゃない問い合わせなら、キャッシュから取る。
        $cacheSet = array();
        if(count($pks) <= 100) {

            $queryKeys = array();

            // 指定されたキーを一つずつチェックする。
            foreach($pks as $index => $pk) {

                $cache = $this->getPkCache($pk);

                // キャッシュになかったなら問い合わせ対象として追加。
                // あったなら配列 $cacheSet へ。
                if($cache === false) {
                    $queryKeys[] = $pk;
                }else {
                    if(!is_null($cache))  $cacheSet[] = $cache;
                }
            }

        // キャッシュを調べられない状態にあるなら、指定されたキーをすべて問い合わせる。
        }else {
            $queryKeys = $pks;
        }

        // レコードの取得と加工。
        $querySet = $queryKeys ? $this->queryRecordsIn($queryKeys) : array();
        $this->processResultset($querySet);

        // 取得したレコードがそんなに大量じゃないなら、キャッシュに格納する。
        if(count($queryKeys) <= 20) {
            foreach($queryKeys as $pk) {
                $record = ResultsetUtil::findRow2($querySet, $this->primaryKey, $pk);
                $this->setPkCache($pk, $record);
            }
        }

        // 取得したレコードとキャッシュを合成。
        $mergeSet = array_merge($cacheSet, $querySet);

        // 主キー値をキーとする配列に直すように指定されていないならここでリターン。
        if(!$keyShift)
            return $mergeSet;

        // 主キー値をキーとする配列に変換する。
        $result = ResultsetUtil::keyShift($mergeSet, $this->primaryKey);

        // 指定されたレコードがすべてとれているならここでリターン。
        if(count($mergeSet) == $pks)
            return $result;

        // とれなかったレコードをnullとして補う。
        // 引数で指定されたキーを一つずつチェック。
        foreach($pks as $pk) {

            // 単一列主キーの場合でも配列に統一。
            if(!is_array($pk))  $pk = array($pk);

            // 戻り値の該当要素へのリファレンスを $cursor に取得。
            $cursor = &$result;
            foreach($pk as $value) {

                // サブキーがない場合は配列として掘り下げておく。
                if(!array_key_exists($value, $cursor))
                    $cursor[$value] = array();

                $cursor = &$cursor[$value];
            }

            // カラ配列が入っている、つまりレコードがなかったならnullを入れておく。
            if(!$cursor)
                $cursor = null;
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * すべてのレコードを返す。
     *
     * @return array    すべてのレコードを含む序数配列。
     */
    public function getAllRecords() {

        // 基底の実装では主キー昇順とする。
        return $this->selectResultset( array('ORDER BY'=>$this->primaryKey) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * レコードを挿入する。
     *
     * @param array     レコードの内容を表す配列。
     * @param bool      オートナンバーされた値を返してほしいかどうか。
     * @return mixed    第二引数にtrueを指定している場合は、オートナンバーされた値。
     */
    public function insertRecord($values, $returnAutoNumber = false) {

        // メンバ変数 deleteOlds が設定されている場合は 1/30 の確率で古いレコードの削除を行う。
        // ただし、ビジーな時間帯は除く。
        if($this->deleteOlds > 0  &&  mt_rand(1, 30) <= 1)
            $this->deleteOldRecords();

        // 主キーが含まれている場合はキャッシュから破棄しておく。
        $pk = $this->getPk($values);
        if($pk !== false)
            $this->setPkCache($pk, false);

        // INSERT。
        return $this->createDao()->insert($values, $returnAutoNumber);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 主キーを指定してレコードを更新する。
     *
     * @param mixed     主キー。複数列主キーの場合は配列で指定する。
     * @param array     更新する列をキー、その内容を値とする配列。
     * @return bool     行が更新されたならtrue、更新されなかった(行がない、MySQLで更新値が既存値と同じ等)
     *                  のならfalse。
     */
    public function updateRecord($pk, $update) {

        // 該当レコードをキャッシュから破棄。
        $this->setPkCache($pk, false);

        return (bool)$this->createDao()->update($this->pkStruct($pk), $update);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 主キーを指定してレコードを削除する。
     *
     * @param mixed     主キー。getRecordと同様。
     */
    public function deleteRecord(/* 可変引数 */) {

        $pk = func_get_args();

        // 該当レコードをキャッシュから破棄。
        $this->setPkCache($pk, false);

        $this->createDao()->delete($this->pkStruct($pk));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 主キーを基準に見て、引数に指定したレコードがすでに存在するならUPDATE、存在しないならINSERTを行う。
     *
     * @param array     INSERT/UPDATE するレコード。
     *                  主キー列の値がすべて揃っていない場合は無条件でINSERTになる。
     * @param array     UPDATEの場合に、INSERTとは違う値を格納したい列がある場合はここで指定する。
     *
     * 例)
     *     // 主キー:5、列col1:3 のレコードをINSERTする。
     *     // すでに主キー:5のレコードがある場合は列col1を3にUPDATEしようとする。
     *     $service->saveRecord(array('pk1'=>5, 'col1'=>3));
     *
     *     // 列col1:4 のレコードをINSERTする。主キーは指定しない。
     *     $service->saveRecord(array('col1'=>4));
     *
     *     // 主キー:5、列col1:2 のレコードをINSERTする。
     *     // すでに主キー:5のレコードがある場合は列col1を col1 + 1 にUPDATEしようとする。
     *     $service->saveRecord(array('pk1'=>5, 'col1'=>2), array('col1'=>array('sql'=>'col1 + 1')));
     *
     *     // 主キー:5、列col1:3、列col2:100 のレコードをINSERTする。
     *     // すでに主キー:5のレコードがある場合はUPDATEを行うが、col2は更新しない。
     *     $service->saveRecord(array('pk1'=>5, 'col1'=>3, 'col2'=>100), array('col2'=>false);
     */
    public function saveRecord($record, $onUpdate = array()) {

        // レコードに含まれている主キーを取得。
        $pk = $this->getPk($record);

        // 指定されたレコードに主キーが含まれているなら、UPDATEを試行する。
        if($pk !== false) {

            $update = $record;

            // 指定されたレコードから主キー列を取り除く。
            foreach((array)$this->primaryKey as $key)
                unset($update[$key]);

            // 引数で指定された、UPDATE時の列値をマージ。
            foreach($onUpdate as $column => $value) {
                if($value === false)
                    unset($update[$column]);
                else
                    $update[$column] = $value;
            }

            // UPDATE。更新行があるなら終了。
            if(count((array)$this->primaryKey) == count($pk)) {
                if($this->updateRecord($pk, $update))
                    return;
            }

            // MySQLの場合は更新行がなくてもマッチ行はある場合があるので、チェック。
            // マッチ行がある場合は終了
            if( $this->countRecord($this->pkStruct($pk)) )
                return;
        }

        // ここまで来たら INSERT する。
        $this->insertRecord($record);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 主キーを指定して、レコードの指定列の値をプラス／マイナスする。
     *
     * @param mixed     主キー。getRecordと同様。
     * @param array     [列名]=>[増減値]の形式の連想配列。減らしたいときはマイナス値で指定する。
     */
    public function plusValue($primaryKey, $plusValues) {

        $update = array();

        // 指定された増減列を一つずつ見ていく。
        foreach($plusValues as $column => $plus) {

            // 値が 0, NULL になっている列は無視。
            if(!$plus)
                continue;

            // 増減値をSQLに変換。
            // 最初からSQLになっているものはそのまま。(この機能は派生クラスのためにある。派生クラスでない
            // 場合はこの機能に依存するべきでない)
            if( is_array($plus) )
                $update[$column] = $plus;
            else
                $update[$column] = array('sql'=>"{$column} + ?", 'value'=>$plus);
        }

        // update実行。
        if($update)
            $this->updateRecord($primaryKey, $update);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたレコードに、標準では追加しない拡張用の擬似列を加える。
     * どんな擬似列が追加されるかは各実装によるので、各サービスクラスの exColumn のコメントを参照。
     *
     * @param array     単一レコード、あるいは結果セット。
     *                  処理結果もここに返される。
     * @param bool      第一引数に指定したものが結果セットである場合は true を指定する。
     */
    public function addExColumn(&$record, $isResultset = false) {

        // レコードでない、あるいはカラの結果セットが渡されている場合は何もしない。
        if(!$record)
            return;

        // 結果セットが渡されている場合。
        if($isResultset) {
            foreach($record as &$rec)
                $this->exColumn($rec);
        }else {
            $this->exColumn($record);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getRecordでレコードを取得して、addExColumnで拡張列を追加して返す。
     * ショートカットメソッド。
     */
    public function getExRecord(/* 可変引数 */) {

        // getRecordで取得。
        $args = func_get_args();
        $record = call_user_func_array(array($this, 'getRecord'), $args);

        // レコードがあったなら拡張列を追加。
        if($record)
            $this->addExColumn($record);

        // リターン。
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getExRecord と同じだが、レコードがなかった場合は例外を投げる。
     */
    public function needExRecord(/* 可変引数 */) {

        // 取得。
        $args = func_get_args();
        $record = call_user_func_array(array($this, 'getExRecord'), $args);

        // なかったらエラー。
        if(!$record) {
            throw new MojaviException(sprintf('必要なレコードがありません。サービス:%s, 主キー:%s'
                , get_class($this)
                , print_r($args, true)
            ));
        }

        // リターン。
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     */
    public function __construct() {

        // tableName, primaryKey プロパティがセットされていない場合は推測で付ける。
        if(!$this->tableName)
            $this->tableName = strtolower( substr(get_class($this), 0, -strlen('Service')) );
        if(!$this->primaryKey)
            $this->primaryKey = $this->tableName . '_id';
    }


    // protected
    //=====================================================================================================

    // テーブル名。オーバーライドしなかった場合はクラス名から推測される。
    protected $tableName = '';

    // 主キー。オーバーライドしなかった場合はクラス名から推測される。
    // 複合主キーの場合は配列で指定。
    protected $primaryKey = '';

    // 蓄積系のテーブルで、古いものを削除していく場合は、最低保存日数を設定する。
    // 自動削除しない場合は 0。
    // 値を設定する場合はcreate_at列が存在し、インデックスが張られていることが必須要件になる。
    // ※現在の実装は、insertRecord呼び出し時、1/30の確率で最大40件の削除が行われる仕様。
    protected $deleteOlds = 0;

    // 更新されることがほとんどないマスタ系のテーブルかどうか。
    // サーバ側クエリキャッシュの使用など、パフォーマンス調整で使用される。
    protected $isMaster = false;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 参照系のメソッド(getRecord, getRecordsIn, selectRecord, selectPage)で使用するSQLの
     * SELECT, FROM 句を返す。
     * 必要なら、派生クラスでオーバーライドする。
     *
     * @return string   SQL文。
     */
    protected function getSelectPhrase() {
        return "
            SELECT *
            FROM {$this->tableName}
        ";
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * レコード数を数えるメソッド(countRecord, selectPage)で使用するSQLのSELECT, FROM 句を返す。
     * 必要なら、派生クラスでオーバーライドする。
     *
     * @return string   SQL文。
     */
    protected function getCountPhrase() {
        return "
            SELECT COUNT(*)
            FROM {$this->tableName}
        ";
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 参照系のメソッド(getRecord, getRecordsIn, selectRecord, countRecord, selectPage)で返されるレコードの
     * 加工を行う。必要なら、派生クラスでオーバーライドする。
     *
     * @param reference     データベースから取得したレコードへの参照。
     */
    protected function processRecord(&$record) {
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された結果セットに含まれるすべてのレコードに対して processRecord と同じことをする。
     */
    protected function processResultset(&$resultset) {

        foreach($resultset as &$record)
            $this->processRecord($record);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * addExColumnの実行部分。
     * 必要なら、派生クラスでオーバーライドする。
     */
    protected function exColumn(&$record) {
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getRecordのSQL実行部分。
     *
     * @param array     主キーの値。getRecordと同じ。
     *                  単一主キーでも第0要素に値が入っている配列になっている。
     * @return array    読み出したレコード。なかった場合はnull。
     */
    protected function queryRecord($pk) {

        $sql = $this->getSelectPhrase() . DataAccessObject::buildWhere($this->pkStruct($pk), $sqlParams);
        return $this->createDao(true)->getRow($sql, $sqlParams);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getRecordsInのSQL実行部分。
     *
     * @param mixed     主キーの値。getRecordsInと同じ
     * @return array    読み出したレコードを結果セット形式の配列で返す。なかったレコードは含まなくて良い。
     *                  したがって、一つもレコードがなかった場合はカラ配列を返す。
     */
    protected function queryRecordsIn($pks) {

        // WHERE句を表す配列の作成。
        // 単一主キーの場合は簡単なのだが...
        if( !is_array($this->primaryKey) ) {
            $where[$this->tableName.'.'.$this->primaryKey] = $pks;

        // 連結主キーのテーブルはちょっと大変。
        }else {

            // WHERE (pk1 = ?  AND  pk2 = ?)
            //    OR (pk1 = ?  AND  pk2 = ?)
            //    OR ...
            // というようなWHERE条件になるようにする。

            // 初期化。
            $where = array( 'OR'=>array() );

            // 指定された主キーの値を一つずつ処理。
            $i = 0;
            foreach($pks as $pk) {

                $i++;

                // pkStructで「(pk1 = ?  AND  pk2 = ?)」の部分を作成して、
                // それがORでつながっていくようにする。。
                $where['OR']["AND:{$i}"] = $this->pkStruct($pk);
            }
        }

        // 作成したWHERE配列でSQLを作成＆実行。
        $sql = $this->getSelectPhrase() . DataAccessObject::buildWhere($where, $sqlParams);
        return $this->createDao(true)->getAll($sql, $sqlParams);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された条件を満たす結果セットを問い合わせて返す。
     *
     * @param array     取得したいレコードを特定するWHERE句を表す配列。
     *                  そのままDataAccessObject::buildWhereに渡されるので、詳細はそちらを参照
     * @return array    読み出した結果セットで返す。なかった場合はカラ配列。
     */
    protected function selectResultset($where) {

        // SQLの作成＆実行
        $sql = $this->getSelectPhrase() . DataAccessObject::buildWhere($where, $sqlParams);
        $resultset = $this->createDao(true)->getAll($sql, $sqlParams);

        // レコードの加工をしてリターン。
        $this->processResultset($resultset);
        return $resultset;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * selectResultset() と同じだが、結果セットでなく最初に見付けた1行のみを返す。
     *
     * @param array     selectResultset()と同様。
     * @return array    見付けた最初のレコード。なかった場合はnull。
     */
    protected function selectRecord($where) {

        // 条件を追加。
        $where['LIMIT'] = 1;

        // selectResultset に転送。
        $resultset = $this->selectResultset($where);

        // 先頭のレコードを返す。
        return $resultset ? $resultset[0] : null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * selectRecordと同様だが、ページを指定して取得する。戻り値の仕様も異なる。
     *
     * @param mixed     selectRecordの第一引数と同様。
     * @param int       1ページあたりの件数。省略時は10。
     * @param int       取得するページ。0スタート。省略時は0。
     * @param bool      このメソッドはレコード取得用のSQLにSQL_CALC_FOUND_ROWSオプションを使用することで、
     *                  レコード取得と件数取得を一度のSQL発行で処理しようとする。
     *                  しかし、FROM句が非常に複雑である等、別のSQLでカウント処理したほうが速そうな
     *                  場合はfalseを指定する。
     * @param array     以下のキーをもつ連想配列。
     *                      totalRows   条件にマッチするレコード件数。
     *                      totalPages  totalRowsと1ページあたりの件数から割り出したページ数。
     *                      resultset   取得できたレコードの配列。なかった場合はカラ配列。
     */
    protected function selectPage($where = array(), $numOnPage = 10, $page = 0, $useFoundRows = true) {

        // SQL_CALC_FOUND_ROWSを使用する場合。
        if($useFoundRows) {

            // SQL作成＆実行
            $sql = $this->getSelectPhrase() . DataAccessObject::buildWhere($where, $sqlParams);
            $result = $this->createDao(true)->getPage($sql, $sqlParams, $numOnPage, $page);

            // レコードの加工。
            $this->processResultset($result['resultset']);

        // SQL_CALC_FOUND_ROWSを使用しない場合。
        }else {

            // まずは数を数える。
            $result = array();
            $result['totalRows'] = $this->countRecord($where);
            $result['totalPages'] = (int)ceil($result['totalRows'] / $numOnPage);

            // 次に結果セットをとる。
            if($result['totalRows'] > 0) {
                $where['OFFSET'] = $numOnPage * $page;
                $where['LIMIT'] = $numOnPage;
                $result['resultset'] =  $this->selectResultset($where);
            }else {
                $result['resultset'] = array();
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された条件を満たすレコードの数を返す。
     *
     * @param array     数を数えるレコードを含むWHERE句を表す配列。詳細はDataAccessObject::buildWhereを参照。
     * @return int      指定された条件を満たすレコードの数。
     */
    protected function countRecord($where = array()) {

        $sql = $this->getCountPhrase() . DataAccessObject::buildWhere($where, $sqlParams);
        return $this->createDao(true)->getOne($sql, $sqlParams);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * メンバ変数deleteOldsの設定にしたがって、古いレコードの削除を行う。
     */
    protected function deleteOldRecords() {

        $this->createDao()->delete(array(
            'create_at' => array('sql'=>'< NOW() - INTERVAL ? DAY', 'value'=>$this->deleteOlds),
            'LIMIT' => 40,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * このサービスで使うDAOオブジェクトを作成する。
     *
     * @param bool  読み取り専用で良い(スレーブを使う)ならばtrueを指定する。
     */
    protected function createDao($readonly = false) {

        // DAOを作成してリターン。
        $dao = new DataAccessObject($this->getDbName($readonly), $this->tableName);
        $dao->useQueryCache = $this->isMaster;
        return $dao;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 使用するDBの設定名を返す。
     * Adminモジュールの場合のスレーブ切り替えも加味される。
     *
     * @param bool  読み取り専用で良い(スレーブを使う)ならばtrueを指定する。
     */
    protected function getDbName($readonly) {

        global $TABLE_DATABASE;

        // データベース設定を取得。なかったら "default" のデータベースを使う。
        $setting = $TABLE_DATABASE[$this->tableName];
        if(!$setting)
            $setting = 'default';

        // 単なる文字列でセットされている場合はそれを返す。
        if( !is_array($setting) )
            return $setting;

        // 初期化
        $dbName = null;

        // 読み取り専用で良いならばスレーブの設定を取得。
        if($readonly) {

            // Adminモジュールの場合のスレーブが定義されているならそちらから取得。
            if(Controller::getInstance()->getContext()->getModuleName() == 'Admin')
                $dbName = isset($setting['kanri']) ? $setting['kanri'] : null;

            // Adminモジュールの場合のスレーブがないなら、通常のスレーブ設定から取得。
            if(!$dbName)
                $dbName = isset($setting['slave']) ? $setting['slave'] : null;
        }

        // 読み取り専用でない、あるいは、スレーブ設定がない場合はマスタ設定を使う。
        if(!$dbName)
            $dbName = $setting['master'];

        // リターン。
        return $dbName;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたレコードから主キー列の値を取得する。
     *
     * 例)
     *     // メンバ変数 $primaryKey が 'col1' だとして...
     *     $this->getPk(array('col1'=>5, 'col2'=>6));    // 5 が返る。
     *
     *     // メンバ変数 $primaryKey が array('col1', 'col2') だとして...
     *     $this->getPk(array('col1'=>5, 'col2'=>6, 'col3'=>7));     // array(5, 6) が返る。
     *
     * @param array     レコードを表す配列
     * @return mixed    主キーの値。
     *                  指定のレコードに主キーがすべて含まれていない場合は false。
     */
    protected function getPk(&$record) {

        $pk = array();

        foreach((array)$this->primaryKey as $key) {

            // 指定のレコードに主キーが含まれていない場合は false を返す。
            if( !array_key_exists($key, $record) )
                return false;

            $pk[] = $record[$key];
        }

        // 単一主キーならその値を、複数主キーなら配列で返す。
        return (count($pk) == 1) ? $pk[0] : $pk;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値を主キーとするWHERE句を作成するための配列を返す。
     *
     * 例)
     *     // メンバ変数 $primaryKey が 'col1' だとして...
     *     $this->pkStruct(5);               // array('col1' => 5) が返る。
     *
     *     // メンバ変数 $primaryKey が array('col1', 'col2') だとして...
     *     $this->pkStruct(array(5,10));     // array('col1'=>5, 'col2'=>10) が返る。
     *
     *     // このように指定してもOK。
     *     $this->pkStruct(5, 10);           // array('col1'=>5, 'col2'=>10) が返る。
     *
     * @param mixed         主キーの値。
     * @return array        WHERE句を作成するための配列。
     */
    protected function pkStruct($pk) {

        // 単一主キーの場合は簡単なのだが...
        if( !is_array($this->primaryKey) ) {
            return array($this->tableName.'.'.$this->primaryKey => $pk);

        // 連結主キーの場合。
        }else {

            // 連結主キーなんだから配列で指定されているはず。第一引数が配列でないなら、
            // 引数リストで指定されていると予想する。
            if(!is_array($pk)) $pk = func_get_args();

            // 主キー列名と値のペアを作成していく。
            $result = array();
            foreach($this->primaryKey as $index => $pkColumn)
                $result[ $this->tableName.'.'.$pkColumn ] = $pk[$index];

            // リターン
            return $result;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された主キー値を持つレコードをキャッシュから取得する。setPkCacheの対。
     * キャッシュと言っても、リクエストごとに破棄される簡単なもの。
     *
     * @param mixed     主キーの値。getRecordと同じ。
     * @return array    キャッシュから見つけたレコード。「そのレコードはない」としてキャッシュされている
     *                  ならnull、まだキャッシュされていないならfalse。
     */
    protected function getPkCache($pk) {

        // 引数正規化。
        if(!is_array($pk)) $pk = array($pk);

        // まずはクラス名でキャッシュをたどる。
        if( !array_key_exists(get_class($this), self::$pkCache) )
            return false;

        $cursor = &self::$pkCache[get_class($this)];

        // 主キー列の数だけたどっていく。
        foreach($pk as $value) {

            // 要素がなかったらfalseリターン。
            if( !array_key_exists($value, $cursor) )
                return false;

            $cursor = &$cursor[$value];
        }

        // 見つけた要素をリターン。
        return $cursor;
    }

    /**
     * 引数で指定された主キー値を持つレコードとして、引数で指定されたレコードをキャッシュする。
     * getPkCacheの対。
     *
     * @param mixed     主キーの値。getRecordと同じ。
     * @param array     キャッシュするレコード。「そのレコードはない」としてキャッシュするならnull。
     *                  キャッシュを破棄したい場合は false。
     */
    protected function setPkCache($pk, $record) {

        // 引数正規化。
        if(!is_array($pk)) $pk = array($pk);

        // まずはクラス名の要素があるかどうか。ないなら作成。
        if( !array_key_exists(get_class($this), self::$pkCache) )
            self::$pkCache[get_class($this)] = array();

        $cursor = &self::$pkCache[get_class($this)];

        // 主キー列の数だけたどっていく。
        foreach($pk as $value) {

            // 要素がなかったら作成。
            if( !array_key_exists($value, $cursor) )
                $cursor[$value] = array();

            $cursor = &$cursor[$value];
        }

        // たどりついた場所にレコードを格納する。
        $cursor = $record;
    }

    // getPkCache, setPkCache の実装で使用しているレコードキャッシュ。
    // 次のような階層構造になっている。
    //
    //     [クラス名] ⇒                // 単一列主キー
    //         [主キー列1の値] => レコード
    //         [主キー列1の値] => レコード
    //     [クラス名] ⇒                // 複数列主キー
    //         [主キー列1の値] =>
    //             [主キー列2の値] => レコード
    //             [主キー列2の値] => レコード
    //         [主キー列1の値] =>
    //             [主キー列2の値] => レコード
    //             [主キー列2の値] => レコード
    private static $pkCache = array();
}
