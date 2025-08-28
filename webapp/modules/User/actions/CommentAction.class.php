<?php

/**
 * コメントを行うアクション。
 *
 * GETパラメータ)
 *      for     省略可能。リプライである場合に、そのリプライ先のコメントID
 *      target  省略可能。"res" を指定すると、forで指定したコメントについている
 *              すべてのリプライに対してまとめて返信する。
 */
class CommentAction extends UserBaseAction {

    const COMMENT_LENGTH_LIMIT = 100;


    public function execute() {

        $histSvc = new History_LogService();

        // レス先が指定されているなら...
        if($_GET['for']) {

            // 返信先の取得。
            if($_GET['target'] != 'res')
                $replyTo = array($_GET['for']);
            else
                $replyTo = Service::create('History_Reply')->getReplyIds($_GET['for']);

            // 返信になる場合はそのユーザ宛のメッセージと同じ扱いらしいので、Blacklist API のチェックを行う。
            // 返信先を一つずつ見ていく。
            foreach($replyTo as $index => $to) {

                // 返信先ユーザが、アクセス中のユーザを禁止設定している場合は返信先から外す。
                $toComment = $histSvc->getRecord($to);
                if( $toComment  &&  PlatformApi::isForbidden($toComment['user_id']) )
                    unset($replyTo[$index]);
            }

            // 上記処理の結果、返信先がなくなった場合は、禁止画面を表示する。
            if(!$replyTo) {
                $this->setAttribute('forbidden', 1);
                return View::SUCCESS;
            }

            // レス先がつぶやきなら、入力初期値とする。
            $history = $histSvc->needRecord($_GET['for']);
            if($history['type'] == History_LogService::TYPE_COMMENT)
                $this->setAttribute('initial', '> '.Text_LogService::get($history['ref1_value']));
        }

        // フォームが送信されているなら、内容を検証して保存＆結果画面に遷移。
        if( $_POST  &&  $this->validateForm() ) {
            $histSvc->createComment($this->user_id, $_POST['tweet'], $replyTo);
            Common::redirect(array('_self'=>true, 'result'=>'tweet'));
        }

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フィーリングフォームを検証する。
     */
    private function validateForm() {

        $errorMess = Common::validateInput($_POST['tweet'], array('length'=>self::COMMENT_LENGTH_LIMIT));

        $this->setAttribute('error', $errorMess);

        return ($errorMess == '');
    }
}
