<?php

class SummerCampaignAction extends DramaBaseAction {

    protected function onExecute() {

        // 再生する寸劇のIDを設定。
        $this->dramaId = 9900097;

        // 戻り先の設定。
        $this->endTo = Common::genContainerUrl('Swf', 'Main', null, true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * flowCompile() をオーバーライド。
     */
    protected function flowCompile($flow) {
        // あとは親クラスの実装に任せる。
        return parent::flowCompile($flow);
    }


}
