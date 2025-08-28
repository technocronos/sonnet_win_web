<?php

class WakuStartDushCampainAction extends DramaBaseAction {


    /*
    9900006の後にスタートダッシュキャンペーン第一段階が終わったことを通知する
    */
    protected function onExecute() {

        // 再生する寸劇のIDを設定。
        $this->dramaId = 9900007;

        // 戻り先の設定。
        $this->endTo = Common::genContainerUrl('Swf', 'Tutorial', null, true);
    }

}
