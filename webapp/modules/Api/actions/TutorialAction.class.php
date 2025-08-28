<?php

// チュートリアル中はフッタリンクやトップページからこのアクションに遷移する。
//
// チュートリアルは結構ややこしいのでココで解説しておく。
// プロローグからチュートリアルは以下のステップで構成されている。
//
//     ユーザ情報なし
//         プロローグ＆名前入力
//     step=0
//         師匠の家・寸劇(オープニング)
//         (終了したらstep10にアップ)
//     step=10
//         メインメニュー＆クエストのチュートリアル
//         (クエスト開始したらstep20にアップ)
//     step=20
//         ファーストクエスト(精霊の洞窟)中
//         (クエスト終了したらstep30にアップ)
//     step=30
//         師匠の家・寸劇(精霊の洞窟を終えて)
//         ⇒続けて、チュートバトル
//         (チュートバトルが終わったらstep60にアップ)
//     step=40(廃止)
//         師匠の家・寸劇(チュートバトルを終えて)
//         (終了したらstep50にアップ)
//     step=50(廃止)
//         ステータス画面へのチュートリアル
//         (ステータス画面で「ｺｺ」リンクをクリックしたら60にアップ)
//     step=60
//         師匠の家・寸劇(ショップへの前振り)
//         (終了したらstep70にアップ)
//     step=70
//         ショップチュートリアル
//         (何かを買ったらstep75にアップ)
//     step=75
//         ガチャチュートリアル
//         (ガチャ回したらstep80にアップ)
//     step=80(廃止)
//         対戦チュートリアル
//         (対戦一覧で誰もいない or バトル確認画面到達で85にアップ)
//     step=85
//         装備チュートリアル
//         (装備完了で90にアップ、スマホ版のみ)
//     step=90
//         師匠の家・寸劇(チュートリアルを終えて＆クエストの依頼)
//         (終了したらstep100にアップ)
//     step=100
//         チュートリアル終了
//
// ユーザはいかなる場合でもこのアクションに来る場合があるので、
// すべてのステップに備えておく必要がある。

class TutorialAction extends ApiDramaBaseAction {

    protected function onExecute() {
        $array = [];

        // ドラマ完了通知の場合の処理。
        if(isset($_GET['end'])){
            //再生すべきdramaIdを取得しておく
            $arr = $this->getTutorialInfo($array);

            $array = $this->processEnd($array);
            $array["dramaId"] = $arr["dramaId"];

            return $array;
        }

        // チュートリアルのステップ完了の場合
        if(isset($_GET['done'])) {

            $userSvc = new User_InfoService();

            // チュートリアルを次段階へ。
            switch($_GET['done']) {
                case 'Status':      $current = User_InfoService::TUTORIAL_STATUS;   break;
                case 'Rival':       $current = User_InfoService::TUTORIAL_RIVAL;    break;
                case 'LAST':       $current = User_InfoService::TUTORIAL_LAST;    break;
                default:            throw new MojaviException('不正な遷移です');
            }
            $userSvc->tutorialStepUp($this->user_id, $current);

            // ユーザレコードを取り直す
            $this->userInfo = $userSvc->needRecord($this->user_id);
        }

        $array['result'] = "ok";
        $array = $this->getTutorialInfo($array);

        return $array;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 完了通知を処理する。
     */
    private function processEnd($array) {

        $array['result'] = "ok";

        $userSvc = new User_InfoService();

        // ショップ前の寸劇が完了したのなら、プレイヤーの所持金を増やす
        if(
               $this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_PRESHOP
            && $_GET['end'] == User_InfoService::TUTORIAL_PRESHOP
        ) {
            $userSvc->plusValue($this->user_id, array('gold'=>30));
        }

        // チュートリアルを次段階へ。
        $userSvc->tutorialStepUp($this->user_id, $_GET['end']);

        // ユーザレコードを取り直す
        $this->userInfo = $userSvc->needRecord($this->user_id);

        //次の遷移先を取得
        $array = $this->getTutorialInfo($array);

        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_LAST){
            //友達招待があるならここで付与する
            Service::create('Invitation_Log')->congraturateInvitation($this->userInfo["user_id"]);

            //スタートダッシュキャンペーン中なら
            $dt = new DateTime();
            $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
            $currenttime = $dt->format('Y-m-d H:i:s');

            if(strtotime(STARTDUSH_CAMPAIGN_START_DATE) <= strtotime($currenttime) && strtotime(STARTDUSH_CAMPAIGN_END_DATE) > strtotime($currenttime)){
                $array['nextscene'] = 'StartDushCampain';
            }
        }

        return $array;
    }
}
