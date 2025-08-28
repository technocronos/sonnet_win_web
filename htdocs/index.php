<?php

$_SERVER["REQUEST_SCHEME"] = @$_SERVER["REQUEST_SCHEME"] ?: 'http';

// mojavi には標準機能で、次のようなURLで...
//     index.php/module/foo/action/bar
// module=foo&action=bar のアクションに転送する機能があるが、これを拡張して、次のようなURLで...
//     index.php/module/foo/action/bar/aaa/bbb
// $_GET を次のように設定するようにする。
//     $_GET = array(
//          'module' => 'foo',
//          'action' => 'bar',
//          'aaa' => 'bbb',
//     );
//

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to our webapp/config.php script.              |
// +---------------------------------------------------------------------------+
require_once("../webapp/config.php");

// メンテナンスモードのときは、"Admin" モジュール以外をアクセスできないようにする。
if(MAINTENANCE_MODE && $_GET['module'] != 'Admin') {

    if($_GET['module'] == 'Event') {
        header("HTTP/1.0 503 Service Unavailable");
        exit();

    }else if($_GET['module'] == 'Api'){
        //メンテ中
        $resData['result'] = 'error';
        $resData['err_code'] = 'maintenance';

        header("Content-Type: application/json; charset=utf-8");
        header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        echo json_encode($resData);
    }else {

        $output =
              '<html><body><br />'
            . "ただいまﾒﾝﾃﾅﾝｽ中です<br />\n"
            . "ご迷惑おかけして申し訳ありません<br />\n"
            . '<br /><br /></body></html>';

        if(preg_match("/^DoCoMo/", $_SERVER['HTTP_USER_AGENT']) || preg_match("/^UP.Browser|^KDDI/", $_SERVER['HTTP_USER_AGENT']))
            $output = mb_convert_encoding($output, 'SJIS-WIN', 'UTF-8');

        echo $output;
    }

    exit();
}

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the mojavi/mojavi.php script.              |
// +---------------------------------------------------------------------------+
require_once(MOJAVI_FILE);

// +---------------------------------------------------------------------------+
// | Create our controller. For this file we're going to use a front           |
// | controller pattern. This pattern allows us to specify module and action   |
// | GET/POST parameters and it automatically detects them and finds the       |
// | expected action.                                                          |
// +---------------------------------------------------------------------------+
$controller = Controller::newInstance('SonnetWebController');

// +---------------------------------------------------------------------------+
// | Dispatch our request.                                                     |
// +---------------------------------------------------------------------------+
$controller->dispatch();
