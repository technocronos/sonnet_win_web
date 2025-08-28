<?php

class MessageAction extends UserBaseAction {

    public function execute() {

        // 送信されているなら、それ用の処理へ。
        if($_POST)
            $this->processPost();

        // 相手の情報を取得。
        $userSvc = new User_InfoService();
        $this->setAttribute('companion', $userSvc->needRecord($_GET['companionId']));

        // 結果画面を表示することになっているならそれ用の処理へ。
        if( !empty($_GET['result']) ) {
            $this->processResult();
            return 'Result';
        }

        // 以降、入力時の処理。

        // メッセージを送信できるのかどうかを取得。
        $messSvc = new Message_LogService();
        $this->setAttribute('canCommunicate',
            $messSvc->canCommunicate($this->user_id, $_GET['companionId'])
        );

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 結果画面表示を処理する。
     */
    private function processResult() {

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
            $this->setAttribute('error', $error);
            return;
        }

        // メッセージ送信。特典付与の処理結果を得る。
        $favor = $messSvc->sendMessage($this->user_id, $_GET['companionId'], $_POST['body']);

        // 受信ユーザにプラットフォームメッセージを送る。
        $title = sprintf('[%s]ﾒｯｾｰｼﾞが来ました', SITE_SHORT_NAME);
        PlatformApi::sendMessage($_GET['companionId'], 'ﾒｯｾｰｼﾞをもらったよ｡いますぐ確認してみよう', $title, Common::genUrl('User', 'MessageList'));

        // 結果画面へ。
        Common::redirect(array(
            '_self' => true,
            'result' => $favor,
        ));
    }
}
