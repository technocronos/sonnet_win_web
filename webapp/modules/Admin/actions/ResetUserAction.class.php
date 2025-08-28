<?php

/**
 * ユーザリセットを行う。
 * デバックメニュー。
 */
class ResetUserAction extends AdminBaseAction {

    public function execute() {

        // フォームが送信されている場合。
        if($_POST) {
            $this->resetUser($_POST['userId']);
            Common::redirect(array('_self'=>true, 'userId'=>$_POST['userId']));
        }

        // ユーザ情報を取得。
        if( isset($_GET['userId']) ) {
            $user = Service::create('User_Info')->getRecord($_GET['userId']);
            $this->setAttribute('user', $user);
        }

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    private function resetUser($userId) {

        $charaSvc = new Character_InfoService();
        $userSvc = new User_InfoService();

        // 指定されたユーザのレコードを取得。
        $user = $userSvc->getRecord($userId);
        if(!$user)
            return;

        // 指定されたユーザのアバターキャラを取得。
        $avatar = $charaSvc->getAvatar($userId);
        if(!$avatar)
            return;

        // キャラの装備、戦績、ステータスアイテム使用回数をリセット
        Service::create('Character_Equipment')->releaseEquips($avatar['character_id']);
        Service::create('Character_Tournament')->resetScore($avatar['character_id']);
        Service::create('Flag_Log')->clearFlag(Flag_LogService::PARAM_UP, $avatar['character_id']);

        // ユーザのフラグをクリア。
        Service::create('Flag_Log')->clearFlag(Flag_LogService::CLEAR, $userId);
        Service::create('Flag_Log')->clearFlag(Flag_LogService::TRY_COUNT, $userId);
        Service::create('Flag_Log')->clearFlag(Flag_LogService::MISSION, $userId);
        Service::create('Flag_Log')->clearFlag(Flag_LogService::FLAG, $userId);

        // 履歴と所持アイテムをクリア
        Service::create('History_Log')->clearHistory($userId);
        Service::create('User_Item')->discardItem($userId);

        // ユーザレコード入れなおし。
        $userSvc->deleteRecord($userId);
        if(!$_POST['delete'])
            $userSvc->insertRecord(array('user_id'=>$userId, 'name'=>$user['name'], 'create_at'=>$user['create_at']));

        // キャラクターレコード入れなおし。
        $charaSvc->deleteRecord($avatar['character_id']);
        if(!$_POST['delete']) {
            $charaSvc->insertRecord(array(
                'character_id' => $avatar['character_id'],
                'user_id' => $userId,
                'entry' => 'AVT',
                'race' => 'PLA',
                'name_id' => $avatar['name_id'],
            ));
        }

        // ユーザサブ属性クリア
        Service::create('User_Property')->clearProperty($userId);
    }
}
