<?php

/**
 * 汎用フラグオンのバリデーションコードの取得。
 * デバックメニュー。
 */
class FlagonValidAction extends AdminBaseAction {

    public function execute() {

        // id が指定されているなら...
        if(isset($_GET['id'])) {

            // 指定されたフラグIDのバリデーションコードを取得。
            $this->setAttribute( 'validCode', sha1($_GET['id'] . DramaQuest::FLAGON_KEY) );
        }

        return View::SUCCESS;
    }
}
