<?php

/**
 * ---------------------------------------------------------------------------------
 * メッセージを送信する
 * @param companionId　送信対象ID
 * ---------------------------------------------------------------------------------
 */
class MessageAction extends SmfBaseAction {

    protected function doExecute($params) {

        // 送信されているなら、それ用の処理へ。
        if($_POST)
            $array = $this->processPost();

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * メッセージ送信を処理する。
     */
    private function processPost() {

        $messSvc = new Message_LogService();

        // メッセージ送信できる相手かどうかチェック。
        if('ok' != $messSvc->canCommunicate($this->user_id, $_GET['companionId']))
            throw new MojaviException('メッセージ送信できない相手に送信しようとした');

        // エラーチェック。
        $error = Common::validateInput($_POST['body'], array('length' => MESSAGE_LENGTH_LIMIT*2));
        if($error) {
            return array("result" => $error);
        }

        // メッセージ送信。特典付与の処理結果を得る。
        $favor = $messSvc->sendMessage($this->user_id, $_GET['companionId'], $_POST['body']);

        // 受信ユーザにプラットフォームメッセージを送る。
        $title = sprintf('[%s]ﾒｯｾｰｼﾞが来ました', SITE_SHORT_NAME);
        PlatformApi::sendMessage($_GET['companionId'], 'ﾒｯｾｰｼﾞをもらったよ｡いますぐ確認してみよう', $title, Common::genUrl('User', 'MessageList'));

        return array("result" => 'ok');

    }
}
