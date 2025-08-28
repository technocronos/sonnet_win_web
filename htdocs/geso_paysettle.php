<?php
/**
 * ゲソてんのサンドボックスに登録するitem_buy_url(決済確定時コールバックURL)。
 *
 * 他のプラットフォームなら決済API呼び出し時に動的に生成したURLになるのだが、ゲソてんではサンドボックスに固定的に登録することしか出来ない。
 * {item_id} の部分に本来の確定時コールバックURLを押し込んでいるので、ここでなんとか擬似的にコールする。
 */

    require_once("../webapp/config.php");
    require_once(MOJAVI_FILE);

    // main() で主たる処理。失敗したら "geso_paysettle.log" というログに記録する。
    try {
        $result = main();
        $message = array('ok'=>'no problem!!', 'ce'=>'crypto error')[$result];
    }
    catch(Exception $e) {

        $log = sprintf('%s %s:%d %s', get_class($e), $e->getFile(), $e->getLine(), $e->getMessage()) . "\n"
             . $e->getTraceAsString() . "\n"
             . 'SERVER: ' . print_r($_SERVER, true)
             . 'POST: ' . print_r($_POST, true);

        file_put_contents(MO_LOG_DIR.'/geso_paysettle.log', $log, FILE_APPEND);

        $result = 'pe';
        $message = $log;
    }

    //----------------------------------------------------------------------------------------------------------
    function main() {

        // デバッグ用に残しておく。リクエスト情報をログ。
        # varLog("geso_paysettle");
        # varLog($_SERVER);
        # varLog($_POST);

        // リクエストはこんな感じ
        # $_SERVER=> array(50) {
        #   ["HOSTNAME"]=> string(21) "localhost.localdomain"
        #   ["TERM"]=> string(5) "xterm"
        #   ["SHELL"]=> string(9) "/bin/bash"
        #   ...
        #   ["HTTP_CONNECTION"]=> string(10) "Keep-Alive"
        #   ["HTTP_HOST"]=> string(30) "test.geso.sonnet.crns-game.net"
        #   ["CONTENT_TYPE"]=> string(48) "application/x-www-form-urlencoded; charset=UTF-8"
        #   ["HTTP_CONTENT_LENGTH"]=> string(3) "235"
        #   ["REMOTE_PORT"]=> string(5) "54442"
        #   ["REMOTE_ADDR"]=> string(14) "163.44.190.254"
        #   ["SERVER_NAME"]=> string(30) "test.geso.sonnet.crns-game.net"
        #   ["SERVER_ADDR"]=> string(15) "111.171.203.167"
        #   ["SERVER_PORT"]=> string(2) "80"
        #   ["GATEWAY_INTERFACE"]=> string(7) "CGI/1.1"
        #   ["SERVER_SOFTWARE"]=> string(15) "lighttpd/1.4.43"
        #   ["SERVER_PROTOCOL"]=> string(8) "HTTP/1.1"
        #   ["REQUEST_METHOD"]=> string(4) "POST"
        #   ["DOCUMENT_ROOT"]=> string(47) "/srv/www/test.geso.sonnet.crns-game.net/htdocs/"
        #   ["SCRIPT_FILENAME"]=> string(65) "/srv/www/test.geso.sonnet.crns-game.net/htdocs/geso_paysettle.php"
        #   ["SCRIPT_NAME"]=> string(19) "/geso_paysettle.php"
        #   ["REDIRECT_STATUS"]=> string(3) "200"
        #   ["REQUEST_URI"]=> string(19) "/geso_paysettle.php"
        #   ["QUERY_STRING"]=> string(0) ""
        #   ["CONTENT_LENGTH"]=> string(3) "235"
        #   ["FCGI_ROLE"]=> string(9) "RESPONDER"
        #   ["PHP_SELF"]=> string(19) "/geso_paysettle.php"
        #   ["REQUEST_TIME_FLOAT"]=> float(1526621764.8946)
        #   ["REQUEST_TIME"]=> int(1526621764)
        # }
        # $_POST=> array(8) {
        #   ["order_id"]=> string(36) "5a3ea4ec-790f-4c97-a3bc-6c9ad7f3de27"
        #   ["owner_id"]=> string(8) "12257739"
        #   ["app_id"]=> string(4) "8653"
        #   ["item_id"]=> string(54) "dataId-8fxa2xxMA7xVL5OrLgvm3mxyLoxLCAmH_action-BuyItem"
        #   ["item_price"]=> string(3) "200"
        #   ["amount"]=> string(1) "2"
        #   ["time"]=> string(14) "20180518143604"
        #   ["signature"]=> string(30) "hJk0RrOX/Ttm/o2DBp8o/TTTbUI=
        # "
        # }

        // 署名検証。NGならリターン。
        if( !validateSignature() )  return 'ce';

        // {item_id} の部分に本来の決済待ち受けURLに関する情報を埋め込んでいるので $params に復元する。
        // 詳細は GesoApi::readyPayment() の実装を参照。
        $item = str_replace(['-','_'], ['=','&'], $_POST['item_id']);
        parse_str($item, $params);

        // 埋め込まれているパラメータに対応するアクションをコール出来るようにして...
        $_GET = array_merge($_GET, $params);
        $_GET['module'] = 'Event';
        # varLog($_GET);

        // なんとか擬似的にコールする。
        $class = $_GET['action'] . 'Action';
        $src = MO_BASE_DIR . sprintf('/webapp/modules/%s/actions/%s.class.php', $_GET['module'], $class);

        require_once($src);
        $action = new $class();

        ob_start();
        $action->execute();
        # $output = ob_get_contents();
        # varLog($output);
        ob_end_clean();

        return 'ok';
    }

    //------------------------------------------------------------------------------------------------------
    function validateSignature() {

        // 1時間以前の場合エラー。
        if($_POST['time'] + 60*60 <= time())  return false;

        // 署名のもとになる文字列を作成。
        $params[] = $_POST['order_id'];
        $params[] = $_POST['owner_id'];
        $params[] = $_POST['app_id'];
        $params[] = $_POST['item_id'];
        $params[] = $_POST['amount'];
        $params[] = $_POST['time'];
        $base = implode(':', $params);

        // 署名生成。
        $signature = hash_hmac('sha1', $base, CONSUMER_SECRET, true);

        // 比較。
        return $signature == base64_decode($_POST['signature']);
    }

    //------------------------------------------------------------------------------------------------------
    function varLog($var) {

        ob_start();
        var_dump($var);
        $output = ob_get_contents();
        ob_end_clean();

        file_put_contents(MO_LOG_DIR.'/debug.log', $output, FILE_APPEND);
    }

?>
{"appResponseCode":"<?php echo $result ?>","appMessage":"<?php echo $message ?>"}
