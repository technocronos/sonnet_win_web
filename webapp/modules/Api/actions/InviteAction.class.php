<?php

/**
 * ---------------------------------------------------------------------------------
 * ユーザー情報引き継ぎを送信する
 * @param inherit_code      引き継ぎコード
 * ---------------------------------------------------------------------------------
 */
class InviteAction extends SmfBaseAction {

    protected function doExecute($params) {

        $array = array();

        if(!$params["inviterId"])
            return array("result" => -1);

        if(!$params["recipientId"])
            return array("result" => -1);

        //招待レコードを作成する
        $result = Service::create('Invitation_Log')->makeInvitation($params["inviterId"], $params["recipientId"]);

        return array("result" => $result);

    }
}
