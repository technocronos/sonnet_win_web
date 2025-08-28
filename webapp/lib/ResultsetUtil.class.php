<?php

/**
 * データベース問い合わせの結果セットのような2次元配列に関するユーティリティ。
 */
class ResultsetUtil {

    //=========================================================================================================
    /**
     * 引数に指定された2次元配列から、特定のカラムの値を取り出して新しい配列を作成する。
     * たとえば、次のような配列があるとして、
     *     array(
     *           'alpha' => array('col1' =>  1, 'col2' =>  2, 'col3' =>  3 )
     *         , 'beta'  => array('col1' => 11, 'col2' => 12, 'col3' => 13 )
     *         , 'gamma' => array('col1' => 21, 'col2' => 22, 'col3' => 23 )
     *     )
     * キー'col1'から値を抜き出して...
     *     array(
     *           'alpha' =>  1
     *         , 'beta'  => 11
     *         , 'gamma' => 21
     *     )
     * という配列を作りたいときに使う。
     *
     * あるいは'col2'列をキー, 'col3'列を値とする
     *     array(
     *            2 =>  3
     *         , 12 => 13
     *         , 22 => 23
     *     )
     * という配列をつくることもできる。
     *
     * 配列でない要素のキー、あるいは指定されたカラムが存在しないキーは返されない。
     *
     * @param array     処理元の配列。
     * @param mixed     値として取り出したい列。
     * @param mixed     キーとして取り出したい列。falseを指定した場合、第一次のキーが使用される。
     */
    public static function colValues(&$source, $valColumn, $keyColumn = false) {

        // 1行ずつ見て、戻り値 $result に追加していく。
        $result = array();
        foreach($source as $key => $record) {

            // 行が配列になっていない場合は無視。
            if( !is_array($record) )
                continue;

            // 指定された要素がない行は無視。
            if( !array_key_exists($valColumn, $record) )
                continue;

            // キーとして取り出す列が指定されている場合は取り出す。
            if($keyColumn !== false)
                $key = $record[$keyColumn];

            // 戻り値にセット。
            $result[$key] = $record[$valColumn];
        }

        // リターン。
        return $result;
    }


    //=========================================================================================================
    /**
     * 引数に指定された結果セット形式の配列から、特定のカラムの値をキーとする新しい配列を作成する。
     *
     * 例)
     *     次のような配列を第一引数に指定し...
     *         array(
     *             array('alpha' => 5, 'beta' => 10, 'gamma' => 21),
     *             array('alpha' => 6, 'beta' => 10, 'gamma' => 22),
     *             array('alpha' => 7, 'beta' => 11, 'gamma' => 23),
     *         );
     *
     *     第二引数に 'alpha' を指定すると、次のような配列を返す。
     *         array(
     *             5 => array('alpha' => 5, 'beta' => 10, 'gamma' => 21),
     *             6 => array('alpha' => 6, 'beta' => 10, 'gamma' => 22),
     *             7 => array('alpha' => 7, 'beta' => 11, 'gamma' => 23),
     *         );
     *
     *     また、第二引数に配列 array('beta', 'alpha') を指定すると、次のような二次元配列を返す。
     *         array(
     *             10 => array(
     *                 5 => array('alpha' => 5, 'beta' => 10, 'gamma' => 21),
     *                 6 => array('alpha' => 6, 'beta' => 10, 'gamma' => 22),
     *             ),
     *             11 => array(
     *                 7 => array('alpha' => 7, 'beta' => 11, 'gamma' => 23),
     *             ),
     *         )
     *
     * 第三引数にtrueを渡すと、キーに変換した列を削除するようになる。
     */
    public static function keyShift(&$source, $keys, $unsetShift = false) {

        // キー変換列を正規化。
        if(!is_array($keys)) $keys = array($keys);

        // 戻り値初期化。
        $result = array();

        // 結果セットに含まれるレコードを一つずつ戻り値の該当箇所に振り分けていく。
        foreach($source as $row) {

            // レコードを格納するべき、戻り値の該当箇所を変数 $corsor で指し示すようにする。
            $cursor = &$result;
            foreach($keys as $key) {

                // 列の値を取得。
                // ついでに、キー変換列を削除するならここで削除しておく。
                $value = $row[$key];
                if($unsetShift) unset($row[$key]);

                // 戻り値に該当箇所がないなら作成しておく。
                if( !array_key_exists($value, $cursor) )
                    $cursor[$value] = array();

                $cursor = &$cursor[$value];
            }

            // 格納するべき場所にレコードを格納。
            $cursor = $row;
        }

        // リターン。
        return $result;
    }


    //=====================================================================================================
    /**
     * 引数に指定された2次元配列の特定の列をコピーする。指定された配列に直接結果を返すことに注意。
     * 指定された列が存在しないレコードはコピーも実行されない。
     *
     *     次のような2次元配列で...
     *
     *           │col1   │col2
     *         ──────────
     *         1 │alpha  │aaa
     *         2 │beta   │bbb
     *         3 │gamma  │ccc
     *
     *     col1 列をコピーしたい場合等に使う。
     *
     *           │col1   │col2   │col3
     *         ───────────────
     *         1 │alpha  │aaa    │alpha
     *         2 │beta   │bbb    │beta
     *         3 │gamma  │ccc    │gamma
     *
     * @param array     列をコピーしたい配列。
     * @param mixed     コピー元の列名。ひとつだけならそのキー値を、複数ある場合は配列で指定する。
     * @param mixed     コピー先の列名。ひとつだけならそのキー値を、複数ある場合は配列で指定する。
     */
    public static function colCopy(&$resultset, $source, $dest) {

        // 配列以外の引数が指定されたらNULLを返す。
        if( !is_array($resultset) )
            return null;

        // 引数正規化。
        if( !is_array($source) )
            $source = array($source);
        if( !is_array($dest) )
            $dest = array($dest);

        // コピー元の数とコピー先の数が一致していない場合は警告
        if( count($source) != count($dest) )
            trigger_error('コピー元の数とコピー先の数が一致していません', E_USER_WARNING);

        // レコードを一つずつ処理していく。
        foreach($resultset as &$record) {

            // 指定された列を一つずつコピーする。
            for($i = 0 ; $i < count($source) ; $i++) {

                // コピー元が存在しない場合はスキップ。
                if( !isset($record[ $source[$i] ]) )
                    continue;

                // コピー。
                $record[ $dest[$i] ] = $record[ $source[$i] ];
            }
        }
    }


    //=====================================================================================================
    /**
     * 引数に指定された2次元配列に指定された列を加える。指定された配列に直接結果を返すことに注意。
     *
     *     例1) 固定値で列を増やす。
     *
     *         次のような2次元配列で...
     *
     *               │col1   │col2
     *             ──────────
     *             1 │alpha  │aaa
     *             2 │beta   │bbb
     *             3 │gamma  │ccc
     *
     *         次のように呼ぶと...
     *
     *             ResultsetUtil::colInsert($set, 'col3', 100);
     *
     *         次のようになる。
     *
     *               │col1   │col2   │col3
     *             ───────────────
     *             1 │alpha  │aaa    │100
     *             2 │beta   │bbb    │100
     *             3 │gamma  │ccc    │100
     *
     *     例2) 各行の値を指定して増やす
     *
     *         次のような2次元配列で...
     *
     *             [$set]
     *               │col1   │col2
     *             ──────────     [$array]
     *             1 │alpha  │aaa         100
     *             2 │beta   │bbb         200
     *             3 │gamma  │ccc         300
     *
     *         次のように呼ぶと...
     *
     *             ResultsetUtil::colInsert($set, 'col3', $array);
     *
     *         次のようになる。
     *
     *               │col1   │col2   │col3
     *             ───────────────
     *             1 │alpha  │aaa    │100
     *             2 │beta   │bbb    │200
     *             3 │gamma  │ccc    │300
     *
     *     例3) 対応表を使って列を増やす。
     *
     *         次のような2次元配列で...
     *
     *             [$set]                   [$array]
     *               │col1   │col2        [1] => 'one'
     *             ──────────     [2] => 'two'
     *             1 │alpha  │1
     *             2 │beta   │2
     *             3 │gamma  │2
     *             4 │delta  │3
     *
     *         次のように呼ぶと...
     *
     *             // 'col2' の値に対応する値を使って 'col3' を作成する。
     *             ResultsetUtil::colInsert($set, 'col3', $array, 'col2');
     *
     *         次のようになる。
     *
     *             [$set]
     *               │col1   │col2   │col3
     *             ──────────────
     *             1 │alpha  │1      │one
     *             2 │beta   │2      │two
     *             3 │gamma  │2      │two
     *             4 │delta  │3      │NULL
     *
     * @param array     列を増やしたい配列。
     * @param string    増やす列の名前。
     * @param mixed     増やした列に格納する値。
     * @param mixed     第3引数を配列で指定した場合に、値を選択する基準となる列。
     */
    public static function colInsert(&$resultset, $column, $value, $keyColumn = null) {

        // 配列以外の引数が指定されたらNULLを返す。
        if( !is_array($resultset) )
            return null;

        // 配列から値を選択しながら追加する場合(例3)
        if( is_array($value)  &&  strlen($keyColumn) > 0 ) {
            foreach($resultset as &$record) {

                $keyValue = isset($record[$keyColumn]) ? $record[$keyColumn] : null;

                if( !is_null($keyValue)  &&  isset($value[$keyValue]) )
                    $record[$column] = $value[$keyValue];
                else
                    $record[$column] = null;
            }

        // 配列をまるごと列として追加する場合(例2)
        }else if( is_array($value) ) {

            $val = reset($value);
            foreach($resultset as &$record) {
                $record[$column] = $val;
                $val = next($value);
            }

        // 固定値を追加する場合(例1)
        }else {
            foreach($resultset as &$record) {
                $record[$column] = $value;
            }
        }
    }


    //=====================================================================================================
    /**
     * 指定された序列でレコードをソートした結果を返す。
     *
     * @param array     ソートしたい２次元配列。
     * @param mixed     序列。[列名]=>[方向] というペアを任意数もつ配列。([方向] は "ASC" か "DESC")
     *                  単一カラムの昇順なら、カラム名のみで指定することも可能。
     * @return array    ソートした結果。
     */
    public static function sort($set, $orderBy) {

        // 引数正規化。序列が単一値で指定されているなら配列に直す。
        if( !is_array($orderBy) )
            $orderBy = array($orderBy => 'ASC');

        // array_multisortに渡す引数リストを初期化。
        $args = array();

        // 指定された序列を見ながら、引数リストを作成していく。
        foreach($orderBy as $column => $dir) {
            $args[] = self::colValues($set, $column);
            $args[] = ($dir == 'ASC') ? SORT_ASC : SORT_DESC;
        }

        // 最後に並べ替えるセットを追加。
        $args[] = &$set;

        // array_multisortをコールしてリターン。
        call_user_func_array('array_multisort', $args);
        return $set;
    }


    //=====================================================================================================
    /**
     * ある列を基準に、指定された順番でレコードを並べ替えた結果を返す。
     *
     * 例)
     *     次のような2次元配列で...
     *
     *            │col1   │col2
     *         ──────────
     *         10 │alpha  │5
     *         20 │beta   │6
     *         30 │gamma  │7
     *
     *     1. "col2" 列を基準に、6→7→5 の順に並べ替える。
     *
     *         ResultsetUtil::order($set, 'col2', array(6, 7, 5));
     *
     *         [結果]
     *            │col1   │col2
     *         ──────────
     *         20 │beta   │6
     *         30 │gamma  │7
     *         10 │alpha  │5
     *
     *     2. キー値を基準に、30→10→20 の順に並べ替える。
     *
     *         ResultsetUtil::order($set, null, array(30, 10, 20));
     *
     *         [結果]
     *            │col1   │col2
     *         ──────────
     *         20 │beta   │6
     *         10 │alpha  │5
     *         30 │gamma  │7
     *
     * @param array     並べ替えたい２次元配列。
     * @param string    基準になる列名。キーを基準にする場合はnull。
     * @param array     並べ替えるべき順番。例を参照。
     * @return array    並べ替えた結果。
     */
    public static function order($set, $column, $order) {

        // 戻り値初期化。
        $result = array();

        // 指定された順番の値を一つずつ見て、該当するレコードを戻り値に順次移動していく。
        foreach($order as $value) {

            // キーが基準の場合。
            if(is_null($column)) {
                if(array_key_exists($value, $set)) {
                    $result[$value] = $set[$value];
                    unset($set[$value]);
                }

            // 列が基準の場合。
            }else {
                foreach($set as $index => $record) {
                    if(array_key_exists($column, $record)  &&  $record[$column] == $value) {
                        $result[$index] = $record;
                        unset($set[$index]);
                    }
                }
            }
        }

        // 指定された順番の値のいずれにも該当しないレコードが残っている可能性があるので、
        // それらは戻り値の末尾に追加。
        if($set)
            $result += $set;

        // リターン。
        return $result;
    }


    //=====================================================================================================
    /**
     * 引数に指定された2次元配列の行と列を入れ替えた配列を返す。
     *
     *     次のような2次元配列なら...
     *
     *           │col1   │col2
     *         ──────────
     *         1 │alpha  │aaa
     *         2 │beta   │bbb
     *         3 │gamma  │ccc
     *
     *     次のようになる...
     *
     *              │1      │2      │3
     *         ───────────────────
     *         col1 │alpha  │beta   │gamma
     *         col2 │aaa    │bbb    │ccc
     */
    public static function rotate(&$source) {

        // 配列以外の引数が指定されたらNULLを返す。
        if( !is_array($source) )
            return null;

        // 戻り値初期化。
        $result = array();

        // 行キーと列キーを入れ替えて$resultに格納していく。
        foreach($source as $rowKey => $record) {

            // 配列になっていない行の値は無視。
            if( !is_array($record) )
                continue;

            foreach($record as $colKey => $value)
                $result[$colKey][$rowKey] = $value;
        }

        // リターン。
        return $result;
    }


    //=====================================================================================================
    /**
     * 引数に指定された列の値の合計値を返す。
     *
     * @param array     結果セットを表す２次元配列。
     * @param mixed     合計値を取得したい列名
     * @return number   合計値
     */
    public static function sum(&$resultset, $column) {

        $total = 0;

        foreach($resultset as &$record) {
            $total += isset($record[$column]) ? $record[$column] : 0;
        }

        return $total;
    }


    //=====================================================================================================
    /**
     * 引数に指定された値を持つ行を返す。複数ある場合は最初に見つけた行を返す。
     *
     * @param array     結果セットを表す２次元配列。
     * @param array     列名と値のペアを含む配列。
     * @return array    見つけた行。見付からなかった場合はnull。
     */
    public static function findRow(&$resultset, $condition) {

        // レコードを一つずつ、合致するかどうか見ていく。
        foreach($resultset as &$record) {

            // フラグ初期化。
            $matched = true;

            // 条件列を一つずつチェック。値が違う列を見つけたらフラグOFF。
            foreach($condition as $column => $value) {
                if($record[$column] != $value) {
                    $matched = false;
                    break;
                }
            }

            // 全列一致しているならそのレコードをリターン。
            if($matched)
                return $record;
        }

        // ここまでくるのは合致するレコードがなかったら。nullリターン。
        return null;
    }


    //=====================================================================================================
    /**
     * findRowと同じだが、引数の仕様が違う。
     *
     * @param array     結果セットを表す２次元配列。
     * @param array     列名のリスト。
     * @param array     値のリスト。列名リストと順番が一致している必要がある。
     * @return array    見つけた行。見付からなかった場合はnull。
     */
    public static function findRow2(&$resultset, $columns, $values) {

        if(!is_array($columns)) $columns = array($columns);
        if(!is_array($values))  $values =  array($values);

        return self::findRow($resultset, array_combine($columns, $values));
    }


    //=====================================================================================================
    /**
     * 結果セットを集計して表を生成する。
     *
     * 例) 単純な表生成
     *
     *     次のような結果セットから...
     *
     *           │weekday│hour   │count
     *         ──────────────
     *         1 │sun    │morning│   5
     *         2 │sun    │evening│   6
     *         3 │sun    │night  │  12
     *         4 │mon    │morning│  20
     *         5 │mon    │evening│   3
     *         6 │mon    │night  │  10
     *         7 │tue    │morning│   8
     *         8 │tue    │night  │  12
     *         9 │wed    │evening│  13   ←重複行
     *        10 │wed    │evening│   3   ←重複行
     *
     *     // "weekday"列の値を縦軸、"hour"列の値を横軸とし、"count"列を集計して
     *     // 次のような2次元配列を生成する。
     *     ResultsetUtil::makeTable($resultset, 'weekday', 'hour', 'count');
     *
     *         [結果]
     *         ※重複行は加算されていることに留意
     *             │morning    │evening     │night
     *         ──────────────────────
     *         sun │         5 │         6  │        12
     *         mon │        20 │         3  │        10
     *         tue │         8 │(要素なし)  │        12
     *         wed │(要素なし) │        16  │(要素なし)
     *
     * 例) 複数の列を合計する表生成
     *
     *     次のような結果セットから...
     *
     *           │weekday│hour   │count │count2
     *         ──────────────────
     *         1 │mon    │morning│  20  │   1
     *         2 │mon    │evening│   3  │   2
     *         3 │mon    │night  │  10  │  12
     *         4 │tue    │morning│   8  │   5
     *         5 │tue    │night  │  12  │   6
     *         6 │wed    │evening│  13  │   2
     *         7 │wed    │evening│   3  │   3
     *
     *     // "weekday", "hour" 以外の列をそれぞれ合計する。
     *     ResultsetUtil::makeTable($resultset, 'weekday', 'hour', null);
     *
     *         [結果]
     *             │morning                         │evening                         │night
     *         ─────────────────────────────────────────────────────
     *         mon │array('count'=>20, 'count2'=> 1)│array('count'=> 3, 'count2'=> 2)│array('count'=>10, 'count2'=>12)
     *         tue │array('count'=> 8, 'count2'=> 5)│                      (要素なし)│array('count'=>12, 'count2'=> 6)
     *         wed │                      (要素なし)│array('count'=>16, 'count2'=> 5)│                      (要素なし)
     *
     * 例) コールバックを使った複雑な表生成
     *
     *     次のような結果セットから...
     *
     *           │weekday│hour   │count │rate
     *         ──────────────────
     *         1 │sun    │morning│   5  │   3
     *         2 │sun    │evening│   6  │   2
     *         3 │mon    │morning│  20  │   1
     *         4 │mon    │evening│   3  │   2
     *         5 │mon    │evening│   1  │   3
     *
     *     次のようなコールバックを使用して...
     *
     *         // 第一引数は現在処理中のレコード。
     *         // 第二引数は結果テーブルの該当セルへの参照。
     *         // 第三引数は結果テーブルの該当行への参照。
     *         function calc_cell($record, &$cell, &$row) {
     *
     *             // 現在値に、count * rate の値を足す
     *             $cell += $record['count'] * $record['rate'];
     *         }
     *
     *     // count * rate の累積値をセルとする表を生成する。
     *     ResultsetUtil::makeTable($resultset, 'weekday', 'hour', 'calc_cell');
     *
     *             │morning │evening
     *         ────────────
     *         sun │     15 │     12
     *         mon │     20 │      9
     *
     * @param array     処理対象の結果セット
     * @param string    縦軸になる値を持っている列名
     * @param string    横軸になる値を持っている列名
     * @param string    セル内容になる値を持っている列名
     *                  またはセル内容を決めるコールバック関数。
     *                  またはnull。
     * @param array     省略可能。縦軸の必須になるキーを保持する配列。
     *                  指定すると、テーブルにこの縦要素が現れなかった場合にカラ配列を補うようになる。
     * @param array     省略可能。横軸の必須になるキーを保持する配列。
     *                  指定すると、各行にこの横要素が現れなかった場合にNULLを補うようになる。
     * @return array    生成した2次元表
     */
    public static function makeTable(&$resultset, $rowColumn, $colColumn, $cellColumn = null, $rows = null, $cols = null) {

        // 戻り値初期化。
        $table = array();

        // 必須の縦軸キーが指定されているならここで作成しておく。
        if($rows)
            $table = array_fill_keys($rows, $cols ? array_fill_keys($cols, null) : array());

        // 結果セットのレコードを一つずつ見ていく。
        foreach($resultset as $record) {

            // 該当の縦軸キーがまだ生成されていないなら生成しておく。
            if( !array_key_exists($record[$rowColumn], $table) )
                $table[ $record[$rowColumn] ] = $cols ? array_fill_keys($cols, null) : array();

            // 該当の横軸キーがまだ生成されていないなら生成しておく。
            if( !array_key_exists($record[$colColumn], $table[ $record[$rowColumn] ]) )
                $table[ $record[$rowColumn] ][ $record[$colColumn] ] = null;

            // 該当セルへの参照を取得。
            $cell = &$table[ $record[$rowColumn] ][ $record[$colColumn] ];

            // セルになる列が指定されていない場合。
            if(is_null($cellColumn)) {

                if($cell == null) $cell = array();

                unset($record[$rowColumn], $record[$colColumn]);

                foreach($record as $key => $value)
                    $cell[$key] += $value;

            // セルになる列がコールバックでない形で指定されている場合は単純な足し算。
            }else if( (is_int($cellColumn) || is_string($cellColumn)) && array_key_exists($cellColumn, $record) ) {
                $cell += $record[$cellColumn];

            // コールバックの場合はコール。
            }else if(is_callable($cellColumn)) {
                call_user_func_array($cellColumn, array($record, &$cell, &$table[$record[$rowColumn]]));
            }
        }

        // 生成した表をリターン。
        return $table;
    }


    //=====================================================================================================
    /**
     * 引数に指定された、結果セット形式の2次元配列を、指定したサマリ列の値が一致するものごとにグループ分け
     * したものを返す。
     *
     * 例)
     *     次のような2次元配列を第一引数にして...
     *
     *           │header1│header2│detail1│detail2
     *         ───────────────────
     *         1 │alpha  │aaa    │rec1   │10
     *         2 │alpha  │aaa    │rec2   │20
     *         3 │beta   │bbb    │rec3   │30
     *         4 │beta   │bbb    │rec4   │30
     *         5 │beta   │ccc    │rec5   │30
     *
     *     次のような第二引数を渡すと...
     *
     *         array('header1', 'header2')
     *
     *     次のような配列が返る
     *
     *         [0] => ['header1'] => alpha
     *             => ['header2'] => aaa
     *             => ['contents'] => [0] => ['detail1'] => rec1
     *                                    => ['detail2'] => 10
     *                                [1] => ['detail1'] => rec2
     *                                    => ['detail2'] => 20
     *         [1] => ['header1'] => beta
     *             => ['header2'] => bbb
     *             => ['contents'] => [0] => ['detail1'] => rec3
     *                                    => ['detail2'] => 30
     *                                [1] => ['detail1'] => rec4
     *                                    => ['detail2'] => 30
     *         [2] => ['header1'] => beta
     *             => ['header2'] => ccc
     *             => ['contents'] => [0] => ['detail1'] => rec5
     *                                    => ['detail2'] => 30
     *
     * 各レコードを、指定したサマリ列の値が一致するものごとにグループ分けしたい場合に用いる。
     *
     * @param array     グループ分けしたい結果セット形式の２次元配列。
     * @param array     サマリ列。1列のみの場合は文字列で指定することも可能。
     * @return array    グループ分けした結果。
     */
    public static function grouping(&$source, $summaryCols) {

        // 第二引数を配列に統一。
        if( !is_array($summaryCols) )
            $summaryCols = array($summaryCols);

        // 第二引数に指定された配列の値をそのままキーとする配列を作成。
        $summaryKeys = array();
        foreach($summaryCols as $col)
            $summaryKeys[$col] = 1;

        // 戻り値 $result とインデックス保持配列 $indices を初期化。
        $result = array();
        $indices = array();

        // 第一引数に指定された配列を一行ずつ処理する。
        foreach($source as $record) {

            // サマリ列だけを取り出した配列を作成。
            $summaryVals = array_intersect_key($record, $summaryKeys);

            // グループ分けのキーを作成。serialize関数の結果とする。…配列そのままでもいいかも。
            $groupKey = serialize($summaryVals);

            // 新規に登場したグループキーである場合は、戻り値の配列中に配置するインデックス番号を決定。
            if( !isset($indices[$groupKey]) )
                $indices[$groupKey] = count($indices);

            // 戻り値の配列中のどこに配置するかを取得。
            $index = $indices[$groupKey];

            // その要素がまだ作成されていない場合は作成。
            if( !isset($result[$index]) ) {
                $result[$index] = $summaryVals;
                $result[$index]['contents'] = array();
            }

            // 処理中の行からサマリ列をカットして、['contents'] に追加する。
            $result[$index]['contents'][] = array_diff_key($record, $summaryKeys);
        }

        // リターン。
        return $result;
    }
}
