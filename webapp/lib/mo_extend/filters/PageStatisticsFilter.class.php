<?php

/**
 * ページアクセス集計、および効果測定を行うためのフィルタ。
 */
class PageStatisticsFilter extends WideFilter {

    //-----------------------------------------------------------------------------------------------------
    /**
     * preProcessをオーバーライド。
     */
    protected function preProcess() {

        // 効果測定用のキーが付いている場合。
        if($_GET['_touch']) {

            // "-" で区切って、種別とキーに分ける。
            list($type, $key) = explode('-', $_GET['_touch']);

            // …と言っても、今のところメッセージ配信でしか利用していない。
            Service::create('Delivery_Log')->countupWonderness($key);

            // リダイレクトして、URLからキーを取り除く。
            Common::redirect(array('_self'=>true, '_touch'=>null));
        }

        // ページ集計
        Service::create('Page_Statistics')->countAccess();
    }
}
