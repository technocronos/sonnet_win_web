<?php

/**
 * ---------------------------------------------------------------------------------
 * メッセージリストを返す
 * @param userId
 *        type receive/send
 * ---------------------------------------------------------------------------------
 */
class MessageListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // 指定されていないURL変数を補う。
        if(empty($_GET['userId']))  $_GET['userId'] = $this->user_id;
        if(empty($_GET['type']))    $_GET['type'] = 'receive';

        // ユーザの情報を取得。
        $userSvc = new User_InfoService();
        $array['target'] = $userSvc->needRecord($_GET['userId']);

        // 自分以外の送信履歴を見るのはNG。
        if($_GET['userId'] != $this->user_id  &&  $_GET['type'] == 'send')
            throw new MojaviException('自分以外の送信履歴を見ようとした。');

        // 自分の受信履歴を見る場合は、「チェック済み」にする。
        if($_GET['userId'] == $this->user_id  &&  $_GET['type'] == 'receive') {
            $mesSvc = new Message_LogService();
            $mesSvc->markReceiverChecked($this->user_id);
        }

        $count = 100;
        $page = 0;

        // 指定されたユーザのメッセージ一覧を取得。
        $methodName = ($_GET['type'] == 'send') ? 'getSendList' : 'getReceiveList';
        $messageSvc = new Message_LogService();
        $list = $messageSvc->$methodName(
            $_GET['userId'],
            $count,
            $page
        );

        // メッセージ相手のユーザID列の名前を取得。
        $companionCol = ($_GET['type'] == 'send') ? 'receive_user_id' : 'send_user_id';
        $array['companionCol'] = $companionCol;

        // メッセージ相手のアバターURLを一覧の列に追加する。
        //Common::embedThumbnailColumn($list['resultset'], $companionCol);

        $charaSvc = new Character_InfoService();

        foreach($list['resultset'] as &$row){
            //$prof = PlatformApi::queryProfile($row[$companionCol], array('thumbnailUrl'));
            //$row["thumbnailUrl"] = $prof["thumbnailUrl"];
            //$row["thumbnailUrlSmall"] = $prof["thumbnailUrlSmall"];
            //$row["thumbnailUrlLarge"] = $prof["thumbnailUrlLarge"];

            // 画像情報を取得。
            $avatar = $charaSvc->needAvatar($row[$companionCol], true);
            $spec = CharaImageUtil::getSpec($avatar);
            $path = sprintf('%s.%s.gif', $spec, 'full');
            $row['imageUrl'] = $path;

            //アバター名取得
            $row['player_name'] = Text_LogService::get($avatar['name_id']);
        }

        // メッセージ相手のユーザ情報をまとめて取得。
        $companionIds = array_unique(ResultsetUtil::colValues($list['resultset'], $companionCol));
        $companions = Service::create('User_Info')->getRecordsIn($companionIds);

        // メッセージ相手のユーザ名を擬似列 "comanion_name" として埋め込む。
        foreach($list['resultset'] as &$record) {
            $record['comanion_name'] = $record['player_name'];
        }unset($record);

        // 一覧をアサイン。
        $array['list'] = $list;

        $array['result'] = 'ok';

        return $array;

    }
}
