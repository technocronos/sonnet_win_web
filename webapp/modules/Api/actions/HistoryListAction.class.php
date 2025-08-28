<?php

/**
 * ---------------------------------------------------------------------------------
 * 履歴リストを送信する
 * @param userId
 * @param type
 *                 comment   コメントの履歴
 *                 history   コメント以外の履歴
 * @param category me：自分の履歴 member：仲間の履歴
 *
 * ---------------------------------------------------------------------------------
 */
class HistoryListAction extends SmfBaseAction {

    protected function doExecute($params) {

        // パラメータが省略されていたら補う。
        if(empty($_GET['userId']))  $_GET['userId'] = $this->user_id;
        if(empty($_GET['page']))  $_GET['page'] = 0;

        // パラメータのデフォルト値を設定。
        $count = 10;

        // タイトルを初期化。
        $title = '';

        // 他人の履歴なら「○○の」を付ける。
        if($_GET['userId'] != $this->user_id) {
            $array["targetUser"] = Service::create('User_Info')->needRecord($_GET['userId']);
            $array["targetUser"]["title"] = $targetUser['short_name'] . 'の';
        }

        if($_GET["category"] == "me"){
            $method = "getUserHistory";
            // 指定された履歴を取得。
            $list = Service::create('History_Log')->$method(
                $_GET['userId'],
                $_GET["type"],
                $count,
                $_GET["page"]
            );
        }else{
            //履歴を取得するメソッド名を決定。
            $method = ($_GET["type"] == 'comment') ? 'getTimeLine' : 'getFriendsHistory';
            // 指定された履歴を取得。
            $list = Service::create('History_Log')->$method(
                $_GET['userId'],
                $count,
                $_GET["page"]
            );
        }

        // ユーザ名とサムネイルURLを一覧の列に追加する。
        if($list["resultset"]){
            foreach($list["resultset"] as &$row){
                $avatar = Service::create('Character_Info')->needAvatar($row["user_id"], true);
                $row["player_name"] = Text_LogService::get($avatar['name_id']);

                //$prof = PlatformApi::queryProfile($row["user_id"], array('thumbnailUrl'));
                //$row["thumbnailUrl"] = $prof["thumbnailUrl"];
                //$row["thumbnailUrlSmall"] = $prof["thumbnailUrlSmall"];
                //$row["thumbnailUrlLarge"] = $prof["thumbnailUrlLarge"];
                if($row["monster"]){
                    $row["monster"]["monster_name"] = Text_LogService::get($row["monster"]["name_id"]);
                }

                // 画像情報を取得。
                $spec1 = $this->getFormation($avatar);
                $row['equip_info'] = $spec1;
            }
        }

        // リストをテンプレートにアサイン。
        $array["list"] = $list;

        $array["result"] = "ok";

        return $array;

    }
}
