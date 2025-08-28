<?php

/**
 * 「ガチャのラインナップ」を処理するアクション。
 */
class GachaLineupAction extends SmfBaseAction {

    protected function doExecute($params) {

        $gachaSvc = new Gacha_MasterService();

        // 以下、ガチャの内容を表示する場合。

        // ガチャの詳細と中身のリストを取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);
        $list = $gachaSvc->getContents($_GET['gachaId']);

        return array("result" => "ok","gacha" => $gacha, "list" => $list);

    }
}
