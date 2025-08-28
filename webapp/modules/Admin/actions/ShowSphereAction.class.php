<?php

/**
 * スフィアチェック用。
 * デバックメニュー。
 */
class ShowSphereAction extends AdminBaseAction {

    public function execute() {

        // フォームが送信されているなら...
        if($_POST) {

            $sphereSvc = new Sphere_InfoService();

            // 現在のレコードを取得。
            $record = $sphereSvc->needRecord($_GET['id']);

            // 「ポジションチェンジ」が入力されている場合。
            if($_POST['pos']) {

                // 入力を座標データに変換
                $pos = preg_split('/\s*,\s*/', $_POST['pos']);

                // 主人公ユニットの位置を差し替える。
                foreach($record['state']['units'] as &$unit) {
                    if($unit['code'] == 'avatar') {
                        $unit['pos'] = $pos;
                        break;
                    }
                }
            }

            // 保存。
            $sphereSvc->updateState($record['sphere_id'], $record['state'], $record['rev']);

            // この画面にリダイレクト。
            Common::redirect( array('_self'=>true) );
        }

        return View::SUCCESS;
    }
}
