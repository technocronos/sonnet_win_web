<?php

class ApproachAction extends UserBaseAction {

    public function execute() {

        // 申請確定されているなら、それ用の処理へ。エラー以外で制御は戻ってこない。
        if( isset($_POST['approach']) )
            $this->processApproach();

        // 解除確定されているなら、それ用の処理へ。制御は戻ってこない。
        if( isset($_POST['dissolve']) )
            $this->processDissolve();

        // 申請相手の情報を取得。
        $userSvc = new User_InfoService();
        $this->setAttribute('companion', $userSvc->needRecord($_GET['companionId']));

        // 結果画面を表示することになっているならそれ用の処理へ。
        if( !empty($_GET['result']) )
            return 'Result';

        // 以降、確認画面時の処理。

        // 対象ユーザが仲間かどうかを取得。
        $isMember = Service::create('User_Member')->isMember($this->user_id, $_GET['companionId']);
        $this->setAttribute('isMember', $isMember);

        // タイトルを決定
        $this->setAttribute('title', $isMember ? '仲間解除' : '仲間申請');

        // すでに仲間なら、解除フォームを表示するので、以降の処理は不要。
        if($isMember)
            return 'Confirm';

        // ユーザの仲間人数についての情報を取得。
        $memberSvc = new User_MemberService();
        $this->setAttribute('memberInfo', $memberSvc->getMemberInfo($this->user_id));

        // 仲間申請を出せるのかどうかチェック
        $apprSvc = new Approach_LogService();
        $this->setAttribute('error',
            $apprSvc->checkApproachable($this->user_id, $_GET['companionId'])
        );

        // 確認画面用のビューを表示。
        return 'Confirm';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 申請確定を処理する。
     */
    private function processApproach() {

        $apprSvc = new Approach_LogService();

        // 本当に仲間申請を出せるのかチェック。出せないのなら確認画面に戻す。
        if( 'ok' != $apprSvc->checkApproachable($this->user_id, $_GET['companionId']) )
            Common::redirect(array('_self'=>true));

        // 申請レコードを作成。
        $apprSvc->makeApproach($this->user_id, $_GET['companionId']);

        // プラットフォームメッセージを飛ばす。
        $title = sprintf('[%s]仲間申請', SITE_SHORT_NAME);
        PlatformApi::sendMessage($_GET['companionId'], SITE_NAME.'から仲間申請を受けました', $title, Common::genUrl('User', 'ApproachList'));

        // 結果画面へ。
        Common::redirect(array('_self'=>true, 'result'=>'approach'));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 解除確定を処理する。
     */
    private function processDissolve() {

        Service::create('User_Member')->dissolveFriend($this->user_id, $_GET['companionId']);

        // 結果画面へ。
        Common::redirect(array('_self'=>true, 'result'=>'dissolve'));
    }
}
