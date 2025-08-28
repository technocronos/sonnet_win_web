<?php

class ShowDramaAction extends ApiDramaBaseAction {


    /*
    シンプルにドラマ再生する。
    */
    protected function onExecute() {

        // 再生する寸劇のIDを設定。
        $this->dramaId = $_GET["dramaId"];

        // 戻り先の設定。
        $this->endTo = $_GET["endTo"];

        $array['result'] = "ok";
        $array["dramaId"] = $this->dramaId;
        $array["nextscene"] = $this->endTo;

        return $array;
    }

}
