<?php

class DetainAction extends DramaBaseAction {

    protected function onExecute() {

        // 再生する寸劇のIDを設定。
        $this->dramaId = 9900099;

        // 戻り先の設定。
        $this->endTo = Common::genContainerUrl('User', 'Index', null, true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * flowCompile() をオーバーライド。
     */
    protected function flowCompile($flow) {

        $itemSvc = new Item_MasterService();

        // 特典内容を表示するメッセージを作成する。
        $message = '';
        foreach(AppUtil::$ABSENCE_BONUS as $bonus) {

            if($bonus['gold'])
                $message .= sprintf("%sマグナゲット\n", $bonus['gold']);

            if($bonus['item']) {
                $item = $itemSvc->needRecord($bonus['item']);
                $message .= sprintf("%sゲット\n", $item['item_name']);
            }
        }

        // 最後の改行はいらない
        $message = substr($message, 0, -1);

        // メッセージを挿入する。
        $flow = str_replace('%bonus%', $message, $flow);

        // あとは親クラスの実装に任せる。
        return parent::flowCompile($flow);
    }
}
