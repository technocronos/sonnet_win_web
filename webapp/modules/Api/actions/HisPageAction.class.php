<?php

/**
 * 他人のページを作成する
 */
class HisPageAction extends SmfBaseAction {

    protected function doExecute($params) {

        // 対象ユーザがアクセス中のユーザを禁止していないかチェック。
        if(PlatformApi::isForbidden($_GET['userId']))
            return array("result" => "error");

        // ワクプラの場合、自分が無視リストに入れている場合でも仲間にできるのはダメなんだそうだ。
        if( PLATFORM_TYPE == 'waku'  &&  PlatformApi::isForbidden('@me', $_GET['userId']) )
            return array("result" => "error");

        // 対象ユーザの基本情報を取得。
        $he = Service::create('User_Info')->getRecord($_GET['userId']);

        $this->setAttribute('he', $he);
        if(!$he)
            return array("result" => "error");

        // 一番新しいコメントを取得。
        $histSvc = new History_LogService();
        $array['comment'] = $histSvc->getNewestComment($_GET['userId']);

        // 対象ユーザが仲間かどうかを取得。
        $memberSvc = new User_MemberService();
        $array['isMember'] = $memberSvc->isMember($this->user_id, $_GET['userId']);

        // 対象のユーザに仲間申請を出しているかどうかを取得。
        $approachSvc = new Approach_LogService();
        $array['isApproaching'] = (bool)$approachSvc->getApproachRecord($this->user_id, $_GET['userId']);

        // 対象ユーザのアバタを取得。
        $chara = Service::create('Character_Info')->needAvatar($_GET['userId'], true);
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id, true);

        //アバター名取得
        $chara['player_name'] = Text_LogService::get($chara['name_id']);

        // 画像情報を取得。
        $spec1 = $this->getFormation($chara);
        $chara['equip_info'] = $spec1;

        //階級名取得
        $gradeinfo = Service::create('Grade_Master')->needRecord($chara['grade_id']);
        $chara['grade_name'] =  $gradeinfo["grade_name"];

        $array['chara'] = $chara;

        // 対象ユーザの、ユーザ対戦の戦績を取得。
        $ctourSvc = new Character_TournamentService();
        $array['ctour'] = $ctourSvc->needRecord($chara['character_id'], Tournament_MasterService::TOUR_MAIN);

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $array['mounts'] = $mounts;

        // ランキングに関する情報を取得。
        $array['rank'] = Service::create('Ranking_Log')->getHighestRank($_GET['userId']);

        // バトルできるかどうかを取得。
        $battleUtil = new UserBattleUtil();
        $canBattle = $battleUtil->canBattle($avatar['character_id'], $chara['character_id'],  Tournament_MasterService::TOUR_MAIN);
        $array['canBattle'] = $canBattle;

        $array['result'] = 'ok';

        return $array;

    }
}
