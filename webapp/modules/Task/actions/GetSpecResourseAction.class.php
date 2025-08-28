<?php

/**
 * /img/chara/ 以下へのリクエストを mod_rewrite によって受け取るアクション。
 */
class GetSpecResourseAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // race の値に応じて、パーツ画像置き場を取得。
        $partsDir = IMG_RESOURCE_DIR . '/' . $_GET["race"] . "_sm";

        $imagePath = $partsDir . "/" . $_GET["filename"];

        // Content-Type を png 用に調整して出力。
        header('Content-Type: image/png');
        readfile($imagePath);

        return View::NONE;
    }
}
