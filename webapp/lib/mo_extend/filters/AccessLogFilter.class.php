<?php

/**
 * 特定のリクエストをログするためのフィルタ。
 */
class AccessLogFilter extends WideFilter {

    //-----------------------------------------------------------------------------------------------------
    /**
     * preProcessをオーバーライド。
     */
    protected function preProcess() {

        // 設定ファイルで定められているユーザならばログする。
        if(
               $this->getParameter('targetId') == -2
            || $_REQUEST['opensocial_owner_id'] == $this->getParameter('targetId')
        ) {

            // POST内容を得る。
            $post = print_r($_POST, true);
            $post = str_replace("\n", ', ', $post);

            // ログ内容を作成。
            $logging = array(
                date('Y/m/d H:i:s'),
                $_SERVER['REQUEST_METHOD'],
                $_SERVER['HTTP_HOST'],
                $_SERVER['REQUEST_URI'],
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT'],
                $_SERVER['HTTP_X_FORWARDED_FOR'],
                $_SERVER['HTTP_AUTHORIZATION'],
                $post,
            );
            $logging = implode("\t", $logging) . "\n";

            // ログファイル名を決定。
            $fileName = sprintf('%s/accesslog_%s.log', MO_LOG_DIR, date('YmdH'));

            // ログ記録。
            file_put_contents($fileName, $logging, FILE_APPEND);
        }
    }
}
