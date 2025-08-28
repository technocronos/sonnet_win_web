<?php

/**
 * 複数のレコードを一度にINSERTするような処理を高速に行う。MySQL限定。
 * 内部的には一時ファイルと LOAD DATA INFILE を使う。
 *
 * …と洒落込みたかったのだが、LOAD DATA INFILE しようとすると、
 *      PDOStatement::execute(): LOAD DATA LOCAL INFILE forbidden
 * と怒られてしまう。PDO::MYSQL_ATTR_LOCAL_INFILE オプションを指定しててもダメ。
 * ここになんかバグ報告があって...
 *      https://bugs.php.net/bug.php?id=54158
 * 5.3.x で mysqlnd を使ってると発生する？
 * 2011-09-09 にSVNでフィックスされたっぽいから、5.3.9以降は解決するかもしれない。
 *
 * なので、旧コードの複数 VALUES リストを使う。
 *
 * 使用例)
 *     $inserter = new BulkInserter('table1');          // table1 に挿入する。
 *     $inserter->insert(1, 2, 3);                      // 挿入する行を、テーブルの列順にしたがって指定。
 *     $inserter->insert(array("abc", "hello", null));  // 配列でもOK
 *     $inserter->flush();                              // 挿入実行
 */
class BulkInserter {

    // public フィールド
    //=====================================================================================================

    // 挿入するテーブルの名前。
    public $table;


    // public メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     *
     * @param string    挿入対象のテーブルの名前
     * @param object    SQLの実行に使用する DataAccessObject
     */
    public function __construct($table, $dao) {

        $this->dao = $dao;
        $this->table = $table;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された値をもつレコードを内部で保持して、次回flush()の呼び出しでinsertされるようにする。
     * 引数はフィールドの数だけ指定できる。任意個数を指定でき、全ての引数がSQLでの表現に変換された上でデータベースに送られる。
     * たとえば insert("first", 2, null)」と指定すると「('first', 2, NULL)」と変換される。
     */
    public function insert() {

        // 引数リストを取得。
        $record = func_get_args();

        // 配列で指定されている場合の引数統一。
        if( is_array($record[0]) )
            $record = $record[0];

        // 指定されたレコードを内部で保持する。
        $this->records[] = $record;

        // 規定の数を超えたらINSERTを実行する。
        if( count($this->records) >= 100 )
            $this->flush();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 内部で保持しているレコードのINSERTを実行する。
     */
    public function flush() {

        // 一つも保持していなかったらリターン。
        if( 0 == count($this->records) )
            return;

        // VALUES 以降のSQLと、そこに埋め込むパラメータを初期化。
        $valuesPhrase = '';
        $valuesParams = array();

        // バッファにあるレコードを一つずつ見ていく。
        foreach($this->records as $record) {

            // SQLに "(?, ?, ?)" のような形式でレコード追加。
            $valuesPhrase .= "(" . substr(str_repeat('?, ', count($record)), 0, -2) . "),\n";

            // パラメータも追加。
            $valuesParams = array_merge($valuesParams, array_values($record));
        }

        // SQLの最後についてる ",\n" をカット。
        $valuesPhrase = substr($valuesPhrase, 0, -2);

        // INSERT文を作成。
        $sql = "INSERT INTO {$this->table} VALUES\n"
             . $valuesPhrase;

        // 実行。
        $this->dao->execute($sql, $valuesParams);

        // 保持用配列を初期化する。
        $this->records = array();
    }


    // private フィールド
    //=====================================================================================================

    // VALUESの後に記述するレコードを保持する配列。
    private $records;
}


# class BulkInserter {
#
#     // public フィールド
#     //=====================================================================================================
#
#     // 挿入するテーブルの名前。
#     public $table;
#
#
#     // public メソッド
#     //=====================================================================================================
#
#     //-----------------------------------------------------------------------------------------------------
#     /**
#      * コンストラクタ。
#      *
#      * @param string    挿入対象のテーブルの名前
#      * @param object    SQL の実行に使用する DataAccessObject
#      */
#     public function __construct($table, $dao) {
#
#         $this->dao = $dao;
#         $this->table = $table;
#     }
#
#
#     //-----------------------------------------------------------------------------------------------------
#     /**
#      * 引数で指定された値をもつレコードを内部で保持して、次回flush()の呼び出しでinsertされるようにする。
#      * 引数はフィールドの数だけ指定できる。任意個数を指定でき、全ての引数がSQLでの表現に変換された上でデータベースに送られる。
#      * たとえば insert("first", 2, null)」と指定すると「('first', 2, NULL)」と変換される。
#      */
#     public function insert() {
#
#         // 一時ファイルをまだオープンしていないならオープン。
#         if(!$this->fp)
#             $this->openDataFile();
#
#         // 引数リストを取得。
#         $record = func_get_args();
#
#         // 配列で指定されている場合の引数統一。
#         if( is_array($record[0]) )
#             $record = $record[0];
#
#         // 各フィールドを LOAD DATA INFILE で使えるようにエスケープしていく。
#         foreach($record as &$field) {
#
#             if( is_null($field) )
#                 $field = "\\N";
#             else
#                 $field = strtr($field, array("\n"=>"\\\n", "\t"=>"\\\t", "\\"=>"\\\\"));
#         }
#
#         // 一時ファイルに書き込む。
#         fwrite($this->fp, implode("\t", $record) . "\n");
#     }
#
#
#     //-----------------------------------------------------------------------------------------------------
#     /**
#      * 内部で保持しているレコードのINSERTを実行する。
#      */
#     public function flush() {
#
#         if(!$this->fp)
#             return;
#
#         fclose($this->fp);
#
#         $this->dao->execute("LOAD DATA LOCAL INFILE '{$this->path}' INTO TABLE {$this->table}");
#
#         unlink($this->path);
#
#         // メンバ変数を初期化。
#         $this->records = array();
#         $this->fp = null;
#     }
#
#
#     // private メンバ
#     //=====================================================================================================
#
#     // 挿入時に使用する一時ファイルのパスと、ファイルポインタ。
#     private $path, $fp;
#
#     // VALUESの後に記述するレコードを保持する配列。
#     private $records;
#
#
#     //-----------------------------------------------------------------------------------------------------
#     /**
#      * 一時ファイルを作成＆オープンする。
#      */
#     private function openDataFile() {
#
#         // 一時ファイルのパスを決定。
#         $this->path = tempnam('', 'bi_');
#
#         // それをオープンする。
#         $this->fp = fopen($this->path, 'wb');
#     }
# }
