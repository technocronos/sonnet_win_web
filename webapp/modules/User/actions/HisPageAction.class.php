<?php

class HisPageAction extends UserBaseAction {

    public function execute() {

        // 対象ユーザがアクセス中のユーザを禁止していないかチェック。
        if(PlatformApi::isForbidden($_GET['userId']))
            return View::SUCCESS;

        // ワクプラの場合、自分が無視リストに入れている場合でも仲間にできるのはダメなんだそうだ。
        if( PLATFORM_TYPE == 'waku'  &&  PlatformApi::isForbidden('@me', $_GET['userId']) )
            return View::SUCCESS;

        // 対象ユーザの基本情報を取得。
        $he = Service::create('User_Info')->getRecord($_GET['userId']);

        $this->setAttribute('he', $he);
        if(!$he)
            return View::SUCCESS;

        // 一番新しいコメントを取得。
        $histSvc = new History_LogService();
        $this->setAttribute('comment', $histSvc->getNewestComment($_GET['userId']));

        // 対象ユーザが仲間かどうかを取得。
        $memberSvc = new User_MemberService();
        $this->setAttribute('isMember', $memberSvc->isMember($this->user_id, $_GET['userId']));

        // 対象のユーザに仲間申請を出しているかどうかを取得。
        $approachSvc = new Approach_LogService();
        $this->setAttribute('isApproaching', (bool)$approachSvc->getApproachRecord($this->user_id, $_GET['userId']));

        // 対象ユーザのアバタを取得。
        $chara = Service::create('Character_Info')->needAvatar($_GET['userId'], true);
        $this->setAttribute('chara', $chara);

        // 対象ユーザの、ユーザ対戦の戦績を取得。
        $ctourSvc = new Character_TournamentService();
        $this->setAttribute('ctour', $ctourSvc->needRecord($chara['character_id'], Tournament_MasterService::TOUR_MAIN));

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $this->setAttribute('mounts', $mounts);

        // ランキングに関する情報を取得。
        $this->setAttribute('rank', Service::create('Ranking_Log')->getHighestRank($_GET['userId']));

        return View::SUCCESS;
    }
}
