<?php

/**
 * /img/chara/ 以下へのリクエストを mod_rewrite によって受け取るアクション。
 */
class GetSwfResourseAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        $swfPath = SWF_PATH . '/' . $_GET["swf_name"] . ".swf";

        // Content-Type を swf 用に調整して出力。
        header('Content-Type: application/x-shockwave-flash');
        readfile($swfPath);

        return View::NONE;
    }
}
