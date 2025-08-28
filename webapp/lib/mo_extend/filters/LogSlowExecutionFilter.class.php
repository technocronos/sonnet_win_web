<?php

/**
 * 実行に数秒以上かかっているリクエストをログするためのフィルタ。
 *
 * 設定例)
 *
 *     class           = "LogSlowExecutionFilter"
 *     param.threshold = 2.0               ; 全体の閾値を 2.0 秒に
 *     param.Task.MessageDelivery = 10.0   ; module=Task&action=MessageDelivery の閾値を 10.0 秒に。
 *     param.Task.LongLong = 0             ; module=Task&action=LongLong をログしない。
 *
 */
class LogSlowExecutionFilter extends WideFilter {

    private $beginTime;

    //-----------------------------------------------------------------------------------------------------
    /**
     * preProcessをオーバーライド。
     */
    protected function preProcess() {

        // 開始時間を取得しておく。
        $this->beginTime = microtime(true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * postProcessをオーバーライド。
     */
    protected function postProcess() {

        // 実行にかかった時間を取得。
        $processTime = microtime(true) - $this->beginTime;

        // 閾値を取得。
        $threshold = $this->getThreshold();

        // 閾値よりも長くかかったなら記録。
        if($threshold > 0  &&  $threshold <= $processTime) {

            // ログ内容を作成。
            $logging = sprintf("%s 実行時間超過: %.2f秒\n", date('Y-m-d H:i:s'), $processTime)
                     . "_SERVER = " . print_r($_SERVER, true)
                     . "\n";

            // ログファイル名を決定。
            $fileName = sprintf('%s/perflog_%s.log', MO_LOG_DIR, date('Ymd'));

            // ログ記録。
            file_put_contents($fileName, $logging, FILE_APPEND);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 記録する閾値を返す。
     */
    private function getThreshold() {

        // モジュール名とアクション名を取得。
        $module = $this->getContext()->getModuleName();
        $action = $this->getContext()->getActionName();

        // アクション個別の閾値設定を取得。
        $custom = $this->getParameter("custom.{$module}.{$action}");

        // 個別の閾値設定があるならそれ、ないならグローバル値を返す。
        return isset($custom) ? $custom : $this->getParameter('threshold', 5.0);
    }
}
