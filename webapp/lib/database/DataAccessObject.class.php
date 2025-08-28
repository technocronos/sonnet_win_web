<?php

/**
 * データベースへのアクセス手段を提供するクラス。
 * アクション等から直接呼ぶのではなくて、Service系のクラスから利用されることを想定している。
 */
class DataAccessObject {

    // SQLのデバック出力を行うかどうか。
    public static $DEBUG = false;

    // SELECT系のSQLを実行するときに SQL_CACHE オプションをつけるかどうか。
    public $useQueryCache = false;


    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     *
     * @param string    接続するDB設定名。
     * @param string    insert, delete, update, exists メソッドで使用するテーブル名。
     *                  これらメソッドを使わないなら省略可能。
     */
    public function __construct($dbName, $tableName = '') {

        $this->tableName = $tableName;
        $this->db = ConnectionFactory::getConnection($dbName);
    }


    // SQL実行系メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたSQLかプリペアードステートメントを実行して、得られた結果セットを返す。
     * レコードが無かった場合はカラの配列が返る。
     */
    public function getAll($statement, $params = array()) {

        $result = $this->query($statement, $params);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたSQLかプリペアードステートメントを実行して、先頭のレコードを返す。
     * レコードが無かった場合はnullが返る。
     */
    public function getRow($statement, $params = array()) {

        $result = $this->query($statement, $params);
        $record = $result->fetch(PDO::FETCH_ASSOC);

        return $record ? $record : null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたSQLかプリペアードステートメントを実行して、先頭のレコードの先頭のカラムの値を返す。
     * レコードが無かった場合はfalseが返る。
     */
    public function getOne($statement, $params = array()) {

        $result = $this->query($statement, $params);

        return $result->fetchColumn();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたSQLかプリペアードステートメントを実行して、全レコードの指定列のみを配列で返す。
     * レコードが無かった場合はカラの配列が返る。
     */
    public function getCol($statement, $params = array(), $col = 0) {

        $result = $this->query($statement, $params);

        return $result->fetchAll(PDO::FETCH_COLUMN, $col);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getAllのカスタム版。ページを指定して結果セットを取得する。MySQL限定。
     *
     * @param mixed     実行するSQL。LIMIT句が含まれていてはならない。
     *                  他のメソッドと違って、プリペアードステートメントは使えない。
     * @param array     SQLのパラメータ。
     * @param int       1ページあたりの件数。省略時は10。
     * @param int       取得するページ。0スタート。省略時は0。
     * @param array     以下のキーをもつ連想配列。
     *                      totalRows   条件にマッチするレコード件数。
     *                      totalPages  totalRowsと1ページあたりの件数から割り出したページ数。
     *                      resultset   取得できたレコードの配列。なかった場合はカラ配列。
     */
    public function getPage($sql, $params = array(), $numOnPage = 10, $page = 0) {

        if($numOnPage <= 0)
            throw new MojaviException('1ページあたりの件数が0以下の値で指定されています。');

        // 指定されたページ条件にしたがって、LIMIT句を作成。
        $sql .= sprintf("\n LIMIT %d OFFSET %d", $numOnPage, $numOnPage * $page);

        // 最初の "SELECT" を "SELECT SQL_CALC_FOUND_ROWS" に置き換える
        $sql = preg_replace('/^\s*SELECT\b/i', '$0 SQL_CALC_FOUND_ROWS', $sql);

        // 実行と戻り値の作成。
        $result = array();
        $result['resultset'] =  $this->getAll($sql, $params);
        $result['totalRows'] =  $this->getOne('SELECT FOUND_ROWS()');
        $result['totalPages'] = (int)ceil($result['totalRows'] / $numOnPage);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたSQLかプリペアードステートメントを実行して、影響を受けた行数を返す。
     * INSERT, UPDATE, DELETE等の更新系クエリを想定している。
     */
    public function execute($statement, $params = array()) {

        $result = $this->query($statement, $params);

        return $result->rowCount();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたSQLかプリペアードステートメントを実行して、その結果得られるPDOStatementオブジェクトを
     * 返す。
     * エラーの処理、デバック出力等も行う。
     */
    public function query($statement, $params = array()) {

        // デバック出力を行うよう指定されているなら行う。
        if(self::$DEBUG) {
            echo "<pre style='background-color: #99FF99'>\n";
            echo "<div style='font-weight:bold'>" . $this->db->debugInfo . "</div>\n";
            echo ($statement instanceof PDOStatement) ? '(PDOStatement)' : htmlspecialchars($statement);
            echo "</pre>\n";
            echo "<pre style='background-color: #FF9999'>\n";
            var_dump($params);
            echo "</pre>\n";
        }

        // SQL文が指定されているならプリペアードステートメントに変換する。
        if( !($statement instanceof PDOStatement) ) {

            if($this->useQueryCache)
                $statement = preg_replace('/^\s*SELECT\b/i', '$0 SQL_CACHE', $statement);

            $statement = $this->prepare($statement);
        }

        // SQL実行。
        if(!is_array($params)) $params = array($params);
        $statement->execute($params);

        return $statement;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたSQLからPDOStatementオブジェクトを作成して返す。
     */
    public function prepare($sql) {

        // PDOStatementオブジェクトを作成。
        // …postgresのPDOドライバの場合はバインドするときにいちいち型指定をしなければならない、
        // とても面倒くさいもののようなので、エミュレーションで回避する。
        $statement = $this->db->prepare($sql);
#         $statement = $this->db->prepare($sql, array(PDO::ATTR_EMULATE_PREPARES => true));

        // リターン。
        return $statement;
    }


    // 以下のメソッドは $tableName プロパティが正しくセットされている必要がある。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された条件のレコードが存在するかどうかを返す。
     *
     * @param array     検索対象のWHEREを表す配列。buildWhereに指定するのと同じもの。
     * @return bool     存在するならtrue、しないならfalse。
     */
    public function exists($where) {

        $where['LIMIT'] = 1;

        $sql = "
            SELECT 1
            FROM {$this->tableName}
        " . self::buildWhere($where, $sqlParams);

        return (bool)$this->getOne($sql, $sqlParams);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * INSERTを行う。
     *
     * 次のように呼びだすと…
     *     $dao->insert(
     *         array(
     *             'col1' => 123,
     *             'col2' => 'test',
     *             'col3' => array('sql'=>'CURRENT_TIMESTAMP'),
     *             'col4' => array('sql'=>'CURRENT_DATE + ?', 'value'=>5)
     *         )
     *     );
     * 次のようなSQLになり...
     *     INSERT INTO tableName (col1, col2, col3, col4)
     *     VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_DATE + ?)
     * 次のようなSQLパラメータで実行される。
     *     array(123, 'test', 5)
     *
     * @param array       レコードの各列を表す配列。
     * @param bool        オートナンバーされた値を返してほしいかどうか。
     * @param bool        重複キーエラーを無視するかどうか。
     * @return mixed      第三引数をtrueに指定していて、重複キーエラーで挿入されなかったならfalse。
     *                    挿入された場合...
     *                        第二引数にtrueを指定していたならオートナンバーされた値。
     *                        第二引数にfalseを指定していたなら常にtrue。
     *                    ※SQLエラーの場合は例外が発生する。
     */
    public function insert($values, $returnAutoNumber = false, $ignoreDuplication = false) {

        // SQLの列名リスト、値リストの部分を作成する。そこに含まれるパラメータ配列も作成。
        $columnList = array();
        $valueList = array();
        $sqlParams = array();
        foreach($values as $key => $value) {

            $columnList[] = $key;

            if( is_array($value) ) {
                $valueList[] = $value['sql'];
                if(isset($value['value'])) {
                    if(!is_array($value['value'])) $value['value'] = array($value['value']);
                    $sqlParams = array_merge($sqlParams, $value['value']);
                }

            }else {
                $valueList[] = '?';
                $sqlParams[] = $value;
            }
        }

        // IGNORE オプションの有無を決定
        $ignore = $ignoreDuplication ? 'IGNORE' : '';

        // SQLの作成。
        $sql = "INSERT {$ignore} INTO {$this->tableName}\n"
             . "(" . implode(', ', $columnList) . ")\n"
             . "VALUES\n"
             . "(" . implode(', ', $valueList) . ")\n";

        // 実行
        $rowsAffected = $this->execute($sql, $sqlParams);

        // 行が挿入されていないならfalseリターン。
        if(0 == $rowsAffected) {
            return false;

        // 行が挿入されているなら...
        }else {

            // オートナンバーされた値を返すように指定されている場合はそれ、指定されてないならtrue。
            return $returnAutoNumber ? $this->getOne('SELECT LAST_INSERT_ID()') : true;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * UPDATEを行う。
     *
     * 次のように呼びだすと…
     *     $object->update(
     *         array('pk1' => 456),
     *         array(
     *             'col1' => 'test',
     *             'col2' => array('sql'=>'CURRENT_TIMESTAMP'),
     *             'col3' => array('sql'=>'col3 + ?', 'value'=>10)
     *         )
     *     );
     * 次のようなSQLになり...
     *     UPDATE tableName
     *     SET col1 = ?
     *       , col2 = CURRENT_TIMESTAMP
     *       , col3 = col3 + ?
     *     WHERE pk1 = ?
     * 次のようなSQLパラメータで実行される。
     *     array('test', 10, 456)
     *
     * @param array     update対象のWHEREを表す配列。buildWhereに指定するのと同じもの。
     * @param array     更新を行う列名と更新内容のペアの配列。
     * @return int      影響を受けた行数。
     *                  MySQLの場合、条件にマッチした数ではなく実際に更新された行数であることに注意。
     */
    public function update($whereKeys, $values) {

        // SET節を$settersへ。そこに含まれるパラメータも作成。
        $setters = array();
        $sqlParams = array();
        foreach($values as $column => $value) {

            if( is_array($value) ) {
                $setters[] = $column . ' = ' . $value['sql'];
                if(isset($value['value'])) {
                    if(!is_array($value['value'])) $value['value'] = array($value['value']);
                    $sqlParams = array_merge($sqlParams, $value['value']);
                }
            }else {
                $setters[] = "{$column} = ?";
                $sqlParams[] = $value;
            }
        }

        // WHERE節の作成と、そこに含まれるパラメータの追加。
        $wherePhrase = self::buildWhere($whereKeys, $sqlParams);

        // SQL作成。
        $sql = "UPDATE {$this->tableName}\n"
             . "SET " . implode("\n  , ", $setters) . "\n"
             . $wherePhrase;

        // 実行＆リターン。
        return $this->execute($sql, $sqlParams);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * DELETEを行う。
     *
     * @param array     delete対象のWHEREを表す配列。buildWhereに指定するのと同じもの。
     * @return int      削除された行数。
     */
    public function delete($whereKeys) {

        // SQL作成。
        $sql = "DELETE FROM {$this->tableName}\n"
             . self::buildWhere($whereKeys, $execParams);

        // 実行。
        return $this->execute($sql, $execParams);
    }


    // ユーティリティメソッド。staticにcallできる。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値でWHERE節のSQLを作成する。ついでに ORDER BY や LIMIT も可能。
     *
     * 例1) 単純な例
     *
     *     $where = array(
     *         'col1' => 123,
     *         'col2' => 456,
     *         'col3' => 777,
     *         'col3:2' => 888,     // 同じ列名で異なる条件を指定したい場合は ":" を使う。
     *                              // ":" から後ろは無視されるのでなんでも良い。
     *     );
     *     $ret = DataAccessObject::buildWhere($where, $sqlParams);
     *
     *     $retには次のような文字列が格納される。
     *         WHERE col1 = ?
     *           AND col2 = ?
     *           AND col3 = ?
     *           AND col3 = ?
     *     $sqlParamsはこうなる。
     *         array(123, 456, 777, 888)
     *
     * 例2) 等号以外の条件や、値にSQLを使用する条件。
     *
     *     $where = array(
     *         'col1' => 2,
     *         'col2' => array('sql'=> '< 500'),                            // 等号以外。
     *         'col3' => array('sql'=> '>= NOW()'),                         // 値にSQLを使用。
     *         'col4' => array('sql'=> '= col1 + ?', value=>100),           // パラメータホルダ付きSQL。
     *         'col5' => array('sql'=> '= ? + ?', value=>array(200, 300)),  // 複数のパラメータホルダがあるSQL。
     *     );
     *     $ret = DataAccessObject::buildWhere($where, $sqlParams);
     *
     *     $retには次のような文字列が格納される。
     *         WHERE col1 = ?
     *           AND col2 < 500
     *           AND col3 >= NOW()
     *           AND col4 = col1 + ?
     *           AND col5 = ? + ?
     *     $sqlParamsはこうなる。
     *         array(2, 100, 200, 300)
     *
     * 例3) LIMIT, OFFSET, ORDER BY
     *
     *     $where = array(
     *         'col1' => 456,
     *         'LIMIT' => 10,
     *         'OFFSET' => 100,
     *         'ORDER BY' => array('col2 DESC', 'col3'),    // 列一つだけで ORDER BY するなら配列でなくてもOK
     *     );
     *     $ret = DataAccessObject::buildWhere($where, $sqlParams);
     *
     *     $retには次の文字列が格納される。
     *         WHERE col1 = ?
     *         ORDER BY col2 DESC, col3
     *         LIMIT 10 OFFSET 100
     *
     * 例4) 複雑な条件
     *
     *     $where = array(
     *         'OR' => array(
     *             'col1:1' => 1,
     *             'col1:2' => 2,
     *         ),
     *         'OR:2' => array(
     *             'col3' => 3,
     *             'col4' => 4,
     *         ),
     *         'OR:3' => array(
     *             'AND:1' => array('col3' => 5, 'col4' => 5),
     *             'AND:2' => array('col3' => 6, 'col4' => 6),
     *         ),
     *         'col5' => 5,
     *         'AND' => array(
     *             'col6' => 6,
     *             'col7' => 7,
     *         )
     *     );
     *     $ret = DataAccessObject::buildWhere($where, $sqlParams);
     *
     *     $retには次の文字列が格納される。
     *         WHERE (col1 = ?  OR  col1 = ?)
     *           AND (col3 = ?  OR  col4 = ?)
     *           AND (
     *                   (col3 = ?  AND  col4 = ?)
     *                OR (col3 = ?  AND  col4 = ?)
     *               )
     *           AND col5 = ?
     *           AND (col6 = ?  AND  col7 = ?)
     *
     * @param array     WHEREの構造を表す配列。
     * @param reference 作成されたWHERE文に含まれるパラメータホルダ("?")の値を返してほしい配列。
     *                  ここで指定された配列に追加する形で返される。
     * @return string   作成されたSQL。
     */
    public static function buildWhere($whereValues, &$sqlParams) {

        $wherePhrase = '';
        $supplePhrase = '';

        // 引数正規化。
        if(!is_array($sqlParams)) $sqlParams = array();

        // GROUP BY を処理。
        if( !empty($whereValues['GROUP BY']) )
            $supplePhrase .= "\n GROUP BY " . implode(', ', (array)$whereValues['GROUP BY']);

        // ORDER BY を処理。
        if( !empty($whereValues['ORDER BY']) )
            $supplePhrase .= "\n ORDER BY " . implode(', ', (array)$whereValues['ORDER BY']);

        // HAVING を処理。
        if( !empty($whereValues['HAVING']) )
            $supplePhrase .= "\n HAVING " . implode(', ', (array)$whereValues['HAVING']);

        // LIMIT, OFFSET を処理。
        if( isset($whereValues['LIMIT'])  ||  isset($whereValues['OFFSET']) ) {

            // どちらか一方しかない場合がありうるので、補完する。
            $whereValues += array('LIMIT'=>0x7FFFFFFF, 'OFFSET'=>0);

            // SQLに追加。
            $supplePhrase .= "\n LIMIT " . (int)$whereValues['LIMIT'];
            if($whereValues['OFFSET'])
                $supplePhrase .= " OFFSET " . (int)$whereValues['OFFSET'];
        }

        // 残っている要素をWHEREとして作成。
        unset($whereValues['GROUP BY'], $whereValues['ORDER BY'], $whereValues['HAVING'], $whereValues['LIMIT'], $whereValues['OFFSET']);
        $wherePhrase = 'WHERE ' . self::buildWhereRecursive($whereValues, $sqlParams, 'AND', 6);

        // LIMIT や ORDER BY と連結してリターン。
        return $wherePhrase . $supplePhrase;
    }

    // buildWhereのヘルパ関数。AND, OR等を再帰的に処理していく。
    private static function buildWhereRecursive($whereValues, &$sqlParams, $glue, $indentWidth) {

        if(!is_array($whereValues))
            throw new MojaviException('条件が配列になっていません');

        if(count($whereValues) == 0)
            return "1 = 1\n";

        // 指定された値を一つずつ見ながらWHEREを構成する式に変換していく。
        $wheres = array();
        foreach($whereValues as $column => $value) {

            // ":"より後を無視する。
            $temp = strstr($column, ':', true);
            $column = ($temp === false) ? $column : $temp;

            switch($column) {

                case 'AND': case 'OR':

                    if(!is_array($value))
                        throw new MojaviException('AND や OR の内容は配列でないといけません。');

                    $wheres[] = "(\n"
                              . str_repeat(' ', $indentWidth+4) . self::buildWhereRecursive($value, $sqlParams, $column, $indentWidth+4)
                              . str_repeat(' ', $indentWidth) . ")\n";

                    break;

                default:

                    // インデックスを列名、値をその内容として処理する。
                    if( is_array($value)  &&  isset($value['sql']) ) {
                        $wheres[] = $column . ' ' . $value['sql'] . "\n";
                        if(isset($value['value'])) {
                            if(!is_array($value['value'])) $value['value'] = array($value['value']);
                            $sqlParams = array_merge($sqlParams, $value['value']);
                        }
                    }else {
                        $wheres[] = $column . ' ' . self::buildRightSide($value, $sqlParams) . "\n";
                    }
            }
        }

        // WHERE式を連結する文字列を作成。ORの場合はインデント調整も行う。
        if($glue == 'OR') $glue = 'OR ';
        $glue = str_repeat(' ', $indentWidth) . $glue . ' ';

        // リターン。
        return implode($glue, $wheres);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値を、WHERE条件式の右辺に変換する。
     * null を与えれば "IS NULL"、配列を与えれば IN リストに、その他を与えれば "= ?" に変換する。
     *
     * @param mixed         SQL条件式の右辺になる値。
     * @param reference     作成されたSQL文に含まれるパラメータホルダ("?")の値を返してほしい配列。
     *                      ここで指定された配列に追加する形で返される。
     * @return string       作成された条件式右辺。
     */
    public static function buildRightSide($value, &$params) {

        // 引数正規化。パラメータ格納先を配列に統一する。
        if(!is_array($params))
            $params = array();

        // NULL が与えられているなら
        if(is_null($value)) {
            return 'IS NULL';

        // 配列が与えられているなら
        }else if( is_array($value) ) {

            // ちゃんと要素のある配列ならば処理。カラ配列だったら...とりあえず絶対に成り立たない
            // 条件式にする。
            if(count($value) > 0) {
                $params = array_merge($params, $value);
                return 'IN (' . implode(', ', array_fill(0, count($value), '?')) . ')';
            }else {
                return '= NULL';
            }

        // それ以外が与えられているなら
        }else {
            $params[] = $value;
            return '= ?';
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値を、LIKEの固定文字列として指定できるようにエスケープする。
     *
     * @param string    LIKEの固定部分とする文字列
     * @param string    エスケープ文字
     * @return string   エスケープした後の文字列
     */
    public static function escapeLikeLiteral($literal, $escape = '\\') {

        // サーチリストの順番には意味があるので、留意。
        $search = array($escape, '%', '_');
        $replace = array($escape.$escape, $escape.'%', $escape.'_');
        return str_replace($search, $replace, $literal);
    }


    // privateメンバ
    //=====================================================================================================

    // PDO オブジェクト。
    private $db = null;

    // insert, update, delete等で使用するテーブル名。
    private $tableName = '';
}
