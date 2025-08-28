<?php

class GreeRedirectAction extends DramaBaseAction {

    protected function onExecute() {

        // 再生する寸劇のIDを設定。
        $this->dramaId = 9900098;

        // 戻り先の設定。
        $this->endTo = "http://mixi.sonnet.t-cronos.co.jp/demo/redirect.php";
    }

}
