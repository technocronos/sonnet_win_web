<?php

/**
 * ---------------------------------------------------------------------------------
 * ユーザー登録を行う
 * @param 
 * ---------------------------------------------------------------------------------
 */
class UserRegistApiAction extends ApiBaseAction {
    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;

    protected function doExecute($params) {

        $array = array();

        // フォームが送信されている場合。
        if($_POST) {
            $result = $this->validateForm();

            // 入力検証。問題ないなら保存処理
            if( $result == 1) {
                $this->saveForm();
            }

            return array("result" => $result);

        }

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームの入力内容を確認する。
     */
    private function validateForm() {

        // 戻り値初期化。
        $result = 1;

        // 入力正規化
        $_POST['name'] = str_replace(array("\r", "\n"), ' ', $_POST['name']);

        // 名前入力をチェック。
        $errorMes = Common::validateInput($_POST['name'], array('length'=>CHARACTER_NAME_LENGTH));
        if($errorMes != '') {
            $result = 2;
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームの入力内容を保存する。
     */
    private function saveForm() {

        // まだユーザ登録していないなら、ここで登録。
        if(!$this->userInfo)
            $this->userInfo = AppUtil::registerUser($_REQUEST['opensocial_owner_id']);

        // キャラクターレコードを作成。
        $chara = new Character_InfoService();
        $chara->insertRecord(array(
            'user_id' => $this->userInfo['user_id'],
            'name' => $_POST['name'],
            'entry' => 'AVT',
            'race' => 'PLA',
        ));

        // アクティビティ送信
        PlatformApi::postActivity(ACTIVITY_GAME_START);
    }

}
