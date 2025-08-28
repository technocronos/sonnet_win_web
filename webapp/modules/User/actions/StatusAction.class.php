<?php

class StatusAction extends UserBaseAction {

    public function execute() {

        $userSvc = new User_InfoService();

        // フォームが送信されているなら、内容を検証して、保存＆結果画面に遷移。
        if($_POST) {
            if( $this->validateForm() ) {
                Service::create('Tweet_Log')->createTweet($this->user_id, $_POST['tweet']);
                Common::redirect(array('_self'=>true, 'result'=>'tweet'));
            }
        }

        // 対象ユーザのアバタを取得。
        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);
        $this->setAttribute('character', $avatar);

        // 仲間に関する情報を取得。
        $this->setAttribute('member', Service::create('User_Member')->getMemberInfo($this->user_id));

        // レベルに関する情報を取得。
        $this->setAttribute('exp', $charaSvc->getExpInfo($avatar));

        // 現在の階級のレコードを取得。
        $this->setAttribute('grade', Service::create('Grade_Master')->needRecord($avatar['grade_id']));

        // ユーザ対戦の戦績を取得。
        $ctourSvc = new Character_TournamentService();
        $this->setAttribute('ctour', $ctourSvc->needRecord($avatar['character_id'], Tournament_MasterService::TOUR_MAIN));

        // ランキングに関する情報を取得。
        $this->setAttribute('rank', Service::create('Ranking_Log')->getHighestRank($this->user_id));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フィーリングフォームを検証する。
     */
    private function validateForm() {

        $errorMess = Common::validateInput($_POST['tweet'], array('length'=>100));

        $this->setAttribute('error', $errorMess);

        return ($errorMess == '');
    }
}
