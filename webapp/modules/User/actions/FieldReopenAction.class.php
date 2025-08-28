<?php

class FieldReopenAction extends UserBaseAction {

    public function execute() {

        // フィールドクエストに出ていない場合はこの画面は表示不可。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        $this->setAttribute('sphereId', $avatar['sally_sphere']);
        if( !$avatar['sally_sphere'] )
            Common::redirect('User', 'Main');

        // 出てるけど、それは自分のスフィアではない(援護)なら表示不可。
        $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);
        if($sphere['user_id'] != $this->user_id)
            Common::redirect('User', 'Main');

        // 一番最初のクエスト(精霊の洞窟)の場合、この画面はスキップする。
        if($sphere['quest_id'] == 11001)
            Common::redirect('Swf', 'Sphere', array('id'=>$avatar['sally_sphere'], 'reopen'=>'resume'));

        // ギブアップ確認フォームが送信されているならギブアップ処理。制御は戻ってこない。
        if( !empty($_POST['giveup']) )
            $this->processGiveup($avatar['sally_sphere']);

        // 実行中のクエストを取得。
        $quest = Service::create('Quest_Master')->needRecord($sphere['quest_id']);
        $this->setAttribute('quest', $quest);

        // ギブアップを選択している場合は確認画面を表示。
        if( !empty($_GET['giveup']) )
            return 'Suspend';

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ギブアップを処理する。
     */
    private function processGiveup($sphereId) {

        // スフィアを閉じる。
        Service::create('Sphere_Info')->closeSphere($sphereId, Sphere_InfoService::GIVEUP);

        // 完了画面に遷移。
        Common::redirect('User', 'FieldEnd', array('sphereId'=>$sphereId));
    }
}
