<?php

/**
 * ---------------------------------------------------------------------------------
 * 戦歴リストを送信する
 * @param charaId  キャラID
 * @param tourId   Tournament_MasterServiceの定数
 * @param side     challenge：仕掛けた defend：仕掛けられた
 * @param page     ページ数
 *
 * ---------------------------------------------------------------------------------
 */
class BattleHistoryApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $charaSvc = new Character_InfoService();

        // 指定されていないURL変数を補う。
        if( empty($_GET['charaId']) )   $_GET['charaId'] = $charaSvc->needAvatarId($this->user_id);
        if( empty($_GET['tourId']) )    $_GET['tourId'] = Tournament_MasterService::TOUR_MAIN;
        if( empty($_GET['side']) )      $_GET['side'] = 'defend';
        if( empty($_GET['page']) )      $_GET['page'] = '0';

        // システムキャラの戦歴を出そうとしているならエラー。
        if($_GET['charaId'] < 0)
            throw new MojaviException('システムキャラの戦歴を出そうとした');

        // クエスト戦闘の戦歴を出そうとしているならエラー。
        if($_GET['tourId'] == Tournament_MasterService::TOUR_QUEST)
            throw new MojaviException('クエスト戦闘の戦歴を出そうとした。');

        // 指定されているキャラを取得。
        $character = $charaSvc->needRecord($_GET['charaId']);
        $array['character'] = $character;
        $array['charaName'] = Text_LogService::get($character['name_id']);

        // 現在のキャラの、指定の戦闘種別の戦績を取得。
        $ctour = Service::create('Character_Tournament')->needRecord($_GET['charaId'], $_GET['tourId']);
        $array['win'] = $ctour[$_GET['side'].'_win'];
        $array['lose'] = $ctour[$_GET['side'].'_lose'];
        $array['draw'] = $ctour[$_GET['side'].'_draw'];
        $array['fights'] = $ctour[$_GET['side'].'_win'] + $ctour[$_GET['side'].'_lose'] + $ctour[$_GET['side'].'_timeup'] + $ctour[$_GET['side'].'_draw'];

        $battleSvc = new Battle_LogService();

        // 戦歴を取得。
        $condition = array(
            'characterId' => $_GET['charaId'],
            'tourId' => $_GET['tourId'],
            'side' => $_GET['side']
        );
        $list = $battleSvc->getBattleList($condition, 8, $_GET['page']);

        // 戦歴の持ち主の主観で、擬似列を加える
        $battleSvc->addBiasColumn($list['resultset'], $_GET['charaId'], true);

        // サムネイルURLを一覧の列に追加する。
        if($list["resultset"]){
            foreach($list["resultset"] as &$row){
                $g = Service::create('Grade_Master')->needRecord($row["rival_ready"]['grade_id']);
                $row["rival_ready"]["grade_name"] = $g["grade_name"];
                //$prof = PlatformApi::queryProfile($row["rival_user_id"], array('thumbnailUrl'));
                //$row["thumbnailUrl"] = $prof["thumbnailUrl"];
                //$row["thumbnailUrlSmall"] = $prof["thumbnailUrlSmall"];
                //$row["thumbnailUrlLarge"] = $prof["thumbnailUrlLarge"];

                $avatar = Service::create('Character_Info')->needAvatar($row["rival_user_id"], true);
                $row["player_name"] = Text_LogService::get($avatar['name_id']);

                // 画像情報を取得。
                $spec = CharaImageUtil::getSpec($avatar);
                $path = sprintf('%s.%s.gif', $spec, 'full');
                $row['imageUrl'] = $path;
            }
        }


        // リストをテンプレートにアサイン。
        $array["list"] = $list;

        $array["result"] = "ok";

        return $array;

    }
}
