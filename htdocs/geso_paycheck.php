<?php
/**
 * ゲソてんのサンドボックスに登録するitem_check_url(決済前コールバックURL)。
 * 在庫の確認などに使うのだが…そんなものはないので固定的なレスポンスでOK。.html にしても良いくらいだ…
 */

    error_reporting(E_ALL ^ E_NOTICE);
    require_once("../webapp/config.php");


    # varLog("geso_paycheck");
    # varLog($_SERVER);
    # varLog($_POST);


    function varLog($var) {

        ob_start();
        var_dump($var);
        $output = ob_get_contents();
        ob_end_clean();

        file_put_contents(MO_LOG_DIR.'/debug.log', $output, FILE_APPEND);
    }

?>
{"appResponseCode":"ok", "appMessage":"no problem!!"}
