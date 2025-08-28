<?php

/**
 * ---------------------------------------------------------------------------------
 * 友達申請リストを送信する
 * @param side　
 *        type receive/send
 * ---------------------------------------------------------------------------------
 */
class ApproachListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // 指定されていないURL変数を補う。
        if(empty($_GET['side']))  $_GET['side'] = 'receive';
        if(empty($_GET['page']))  $_GET['page'] = '0';

        // 承認、拒否、キャンセル、クリアがされている場合はそれ用の処理へ。
        if($_POST)
            return $this->processOperation();

        // 以降、一覧表示。

        $apprSvc = new Approach_LogService();

        // 申請の一覧を取得。
        $list["list"] = $apprSvc->getList($this->user_id, $_GET['side'], 10, $_GET['page']);

        // 結果セットにアバターURLを格納する列を埋め込む
//        Common::embedThumbnailColumn($list['resultset'],
//            ($_GET['side'] == 'receive') ? 'approacher_id' : 'recipient_id'
//        );

        if($list["list"]["resultset"]){
            foreach($list["list"]["resultset"] as &$row){
                $row["companion"]["chara"] = Service::create('Character_Info')->needAvatar($row["companion"]["user_id"], true);
                $row["companion"]["player_name"] = Text_LogService::get($row["companion"]["chara"]['name_id']);
                $row["companion"]["grade"] = Service::create('Grade_Master')->needRecord($row["companion"]["chara"]['grade_id']);

                //$prof = PlatformApi::queryProfile($row["companion"]["user_id"], array('thumbnailUrl'));
                //$row["companion"]["thumbnailUrl"] = $prof["thumbnailUrl"];
                //$row["companion"]["thumbnailUrlSmall"] = $prof["thumbnailUrlSmall"];
                //$row["companion"]["thumbnailUrlLarge"] = $prof["thumbnailUrlLarge"];

                // 画像情報を取得。
                $spec = CharaImageUtil::getSpec($row["companion"]["chara"]);
                $path = sprintf('%s.%s.gif', $spec, 'full');
                $row["companion"]['imageUrl'] = $path;

            }
        }
        // ビュー変数としてセット。
        $array = $list;

        // 送信一覧の場合は未確認件数も取得しておく。
        if($_GET['side'] == 'send') {
            $array['unconfirmed'] = $apprSvc->getUnconfirmedCount($this->user_id);
            //確認済みにする
            $apprSvc->confirmResult($this->user_id);
        }

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 承認、拒否、キャンセル、クリアの処理を行う。
     * 最後にリダイレクトするので、制御は戻らない。
     */
    private function processOperation() {

        $apprSvc = new Approach_LogService();
        $memberSvc = new User_MemberService();

        if($_POST['accept'])        $opCode = 'accept';
        else if($_POST['reject'])   $opCode = 'reject';
        else if($_POST['cancel'])   $opCode = 'cancel';
        else                        $opCode = 'clear';

        // 承認／拒否／キャンセルの場合。
        if($opCode == 'accept' || $opCode == 'reject' || $opCode == 'cancel') {

            // 対象の申請レコードを取得。
            $approach = $apprSvc->needRecord($_POST['approach_id']);

            if($approach['status'] != Approach_LogService::STATUS_INIT)
                throw new MojaviException('初期状態でない申請を操作しようとした。');

            if(($_POST['accept'] || $_POST['reject'])  &&  $approach['recipient_id'] != $this->user_id)
                throw new MojaviException('自分宛てでない申請を承認／却下しようとした');

            if($_POST['cancel']  &&  $approach['approacher_id'] != $this->user_id)
                throw new MojaviException('自分のものでない申請をキャンセルしようとした');

            // 承認の場合は...
            if($_POST['accept']) {

                // 友達にする。
                $memberSvc->makeFriend($approach['approacher_id'], $approach['recipient_id']);

                // 送信ユーザにプラットフォームメッセージを送る。
                $title = sprintf('[%s]仲間承認', SITE_SHORT_NAME);
                PlatformApi::sendMessage($approach['approacher_id'], '仲間申請が承認されました。いますぐ確認してみよう', $title, Common::genUrl('User', 'ApproachList', array('side'=>'send')));
            }

            // 申請レコードの status を更新。
            $statusTable = array(
                'accept' => Approach_LogService::STATUS_OK,
                'reject' => Approach_LogService::STATUS_NG,
                'cancel' => Approach_LogService::STATUS_CANCEL_CONFIRMED,
            );
            $apprSvc->setResult($_POST['approach_id'], $statusTable[$opCode]);

            // 結果画面へ。
            $companionId = $approach[ ($opCode == 'cancel') ? 'recipient_id' : 'approacher_id' ];
            return array('result'=>$opCode, 'companion_id'=>$companionId);

        // 「確認済みにする」の場合。
        }else if($opCode == 'clear') {

            $apprSvc->confirmResult($this->user_id);
            return array('result'=>$opCode);
        }
    }
}
