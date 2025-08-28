<?php

class HistoryTouchAction extends UserBaseAction {

    public function execute() {

        $histSvc = new History_LogService();

        // 指定されている履歴を取得。
        $history = $histSvc->needRecord($_GET['id']);

        // 削除の場合。
        if($_GET['touch'] == 'delete') {

            // 自分のものでなかったらエラー。
            if($history['user_id'] != $this->user_id)
                throw new MojaviException('自分のものでない履歴を削除しようとした');

            // 指定されている履歴を削除。
            $histSvc->deleteRecord($_GET['id']);

        // 称賛の場合。
        }else {

            // 自分のものならエラー。
            if($history['user_id'] == $this->user_id)
                throw new MojaviException('自分の履歴を称賛しようとした');

            // すでに称賛しているならエラー。
            if( Service::create('History_Admiration')->getRecord($_GET['id'], $this->user_id) )
                throw new MojaviException('すでに称賛しているのに、再度称賛しようとした');

            // 称賛を処理。
            $histSvc->admireHistory($_GET['id'], $this->user_id);
        }

        // backtoで示されている戻り先へリダイレクト。
        Common::redirect( ViewUtil::unserializeBackto() );
    }
}
