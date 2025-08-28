<?php

/**
 * データベースとの接続を管理するクラス。
 */
class ConnectionFactory {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定した設定名のDB接続を取得する。まだ接続していない場合は接続もする。
     *
     * @param string    定数 $DATABASE_SPEC のキー名
     */
    public static function getConnection($dbName) {

        global $DATABASE_SPEC;

        // まだ接続していないなら接続。
        if( !array_key_exists($dbName, self::$connections) ) {

            // データベース定義のどれを使うかを $dbSpec に取得。
            $dbSpec = $DATABASE_SPEC[$dbName];

            // 接続。
            $db = self::connect($dbSpec);
            $db->debugInfo = $dbName;

            // 取得した接続オブジェクトを保持しておく。
            self::$connections[$dbName] = $db;
        }

        // 指定された接続をリターン。
        return self::$connections[$dbName];
    }


    // privateメソッド
    //=====================================================================================================

    // DBへの接続を保持する。
    private static $connections = array();


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたデータベースに接続処理を行う。
     *
     * @param mixed     データベース定義。
     *                  序数配列で複数渡すこともできる。その場合はランダムに一つが選択される。
     * @return DB       PDO のインスタンス。
     */
    private static function connect($dbSpec) {

        // 受け取ったデータベース定義が序数配列ならば、そのうちの一つを選択する。
        if( array_key_exists(0, $dbSpec) || array_key_exists(1, $dbSpec) ) {
            $randomPick = $dbSpec[ array_rand($dbSpec) ];
            return self::connect($randomPick);
        }

        // 接続。
        try {
            $db = new PDO(
                sprintf('mysql:unix_socket=%s;host=%s;port=%s;dbname=%s', $dbSpec['socket'], $dbSpec['hostspec'], $dbSpec['port'], $dbSpec['database']),
                $dbSpec['username'],
                $dbSpec['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
        }catch(Exception $e) {
            throw new MojaviException($e->getMessage());
        }

#         // タイムアウトの設定
#         $conn->setAttribute(PDO::ATTR_TIMEOUT, 60 * 5);

#         // エラー時に例外を発生させる。
#         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // エラー時にWarningを発生させる。
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        return $db;
    }
}
