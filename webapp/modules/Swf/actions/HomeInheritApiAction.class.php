<?php

/**
 * ---------------------------------------------------------------------------------
 * ユーザー情報引き継ぎを送信する
 * @param inherit_code      引き継ぎコード
 * ---------------------------------------------------------------------------------
 */
class HomeInheritApiAction extends ApiBaseAction {
    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;

    protected function doExecute($params) {

        $array = array();

        if(!$params["inherit_code"])
            return array("result" => -1);

        $inherit_code = $params["inherit_code"];

        if(!$_REQUEST['opensocial_owner_id'])
            return array("result" =>  -2);

        //数字の場合はNG
        if(is_numeric($inherit_code))
            return array("result" =>  -3);

        $platformUid = $_REQUEST['opensocial_owner_id'];

        //自分で自分のコードの場合はNG
        if($inherit_code == $platformUid)
            return array("result" =>  -3);

        // 前のレコード取得。なかったらリターン。
        $record = Service::create('User_Info')->getRecordByPuid($inherit_code);
        if(!$record)
            return array("result" => -3);

        //レコードを更新する
        $userId = $record["user_id"];
        Service::create('User_Info')->updateRecord($userId, array(
            'platform_uid' => $platformUid
        ));

        return array("result" => 0);

    }
}
