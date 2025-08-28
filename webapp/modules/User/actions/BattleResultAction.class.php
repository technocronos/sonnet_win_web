<?php

class BattleResultAction extends UserBaseAction {

    public function execute() {

        // バトル結果の通知ならばそれ用の処理へ。
        if( isset($_POST['result']) ) {
            $this->processResult();
        }

        // バトルにコメントを付けようとしているならそれ用の処理へ。
        if( isset($_POST['comment']) ) {
            $this->processComment();
        }

        // 以降、バトル結果を表示しようとしている場合の処理。

        // バトル基本データを取得。
        $battle = Service::create('Battle_Log')->getRecord($_GET['battleId']);

        // バトルデータがない、あるいは決着していない場合はエラー用のテンプレートへ。
        if(!$battle  ||  $battle['true_status'] < Battle_LogService::SETTLE_BORDER) {
            $this->setAttribute('status', $battle ? $battle['true_status'] : -1);
            return 'Error';
        }

        // バトルの詳細を取得する。
        $this->setupResultView($battle);

        // 自分と同じ階級と、隣の階級を取得する。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        $gradeSvc = new Grade_MasterService();
        $range = $gradeSvc->getRangeBorder($avatar['grade_id'], 1);
        $this->setAttribute('neighborGrades', $gradeSvc->getList('DESC', $range['upper'], $range['lower']));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 結果画面を表示するための準備を行う。
     *
     * @param array     ballt_log レコード。
     */
    private function setupResultView($battle) {

        // 挑戦側、防衛側のどちらを見ようとしているのかを取得。
        $side = ($_GET['side'] == 'def') ? 'defender' : 'challenger';
        $this->setAttribute('side', $side);

        // 表示する側で主観カラムを加える。
        Service::create('Battle_Log')->addBiasColumn($battle, $battle["{$side}_id"]);
        $this->setAttribute('battle', $battle);

        // コメント結果ページの表示ならココまででOK。
        if($_GET['result'])
            return;

        // 自分のキャラについて表示するなら...
        if($battle['bias_user_id'] == $this->user_id) {

            $uitemSvc = new User_ItemService();

            // 現在値も表示する。
            $this->setAttribute('current', array(
                'exp' => Service::create('Character_Info')->getExpInfo($battle['bias_result']['character']),
                'gold' => $this->userInfo['gold'],
                'grade_pt' => $battle['bias_result']['character']['grade_pt'],
            ));

            // 装備リストについて処理する。
            $warnExists = false;
            foreach($battle['bias_result']['equip']['after'] as &$equip) {

                // 修理を行って戻ってきている場合は...
                if($equip  &&  !empty($_GET['repaireId'])  &&  $equip['user_item_id'] == $_GET['repaireId']) {

                    // 修理後の耐久値を取得しておく。
                    $current = $uitemSvc->getRecord($equip['user_item_id']);
                    if($current)
                        $equip['repaire'] = $current['durable_count'];

                // 耐久値が警告値を下回っている場合...
                }else if($equip  &&  $equip['durable_count'] <= User_ItemService::USEFUL_WARN) {

                    // 今でも下回っているのか調べて、そうならマークを付ける。
                    $current = $uitemSvc->getRecord($equip['user_item_id']);
                    if($current  &&  $current['durable_count'] <= User_ItemService::USEFUL_WARN) {

                        // マークとして、回復誘導ページの使用後遷移パラメータを入れる。
                        // 'backto'=>null としているのは入れ子が多すぎてURL長が限界を超えることを懸念して。
                        $equip['repaire_useto'] = ViewUtil::serializeBackto(
                            array('repaireId'=>$equip['user_item_id'], 'backto'=>null
                        ));

                        // 警告があったことを覚えておく。
                        $warnExists = true;
                    }
                }
            }unset($equip);

            // 耐久値警告装備がある場合は、耐久値回復アイテムを持っているかどうかを調べる。
            if($warnExists) {
                $this->setAttribute('holdRecover',
                    (bool)$uitemSvc->getRecordByType($this->user_id, Item_MasterService::REPAIRE)
                );
            }

            // キャプチャーモンスターがいる場合、その詳細を取得する。
            if($battle['bias_result']['gain']['monster']) {
                $this->setAttribute('capture',
                    Service::create('Monster_Master')->needRecord($battle['bias_result']['gain']['monster'])
                );
            }
        }

        // 詳細データをビューにセット。
        $this->setAttribute('ready',  $battle['bias_ready']);
        $this->setAttribute('result', $battle['bias_result']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 結果通知を処理する。
     */
    private function processResult() {

        // 指定されているバトル情報をロード。
        $battleSvc = new Battle_LogService();
        $battle = $battleSvc->needRecord($_GET['battleId']);

        // 他人のバトルの場合はエラー。
        if($battle['player_id'] != $this->user_id)
            throw new MojaviException('他人のバトルを決着させようとした');

        // 検証コードが合わない場合はエラー。
        if($battle['validation_code'] != $_POST['code'])
            throw new MojaviException('検証コードが一致しない');

        // 試合中になっている場合のみ、決着処理を行う。
        //コンティニューアイテム購入のためにCREATEDに戻しているだけの場合、も決着処理を行う
        if($battle['true_status'] == Battle_LogService::IN_GAME || $battle['true_status'] == Battle_LogService::IN_CONTINUE) {

            $battleUtil = BattleCommon::factory($battle);
            $battleUtil->finishBattle($_GET['battleId'], $_POST);
        }

        // 結果画面を表示。
        Common::redirect('User', 'BattleResult', array(
            'battleId' => $battle['battle_id'],
            'side' => $battle['side_reverse'] ? 'def' : 'cha',
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * コメント入力を処理する。
     */
    private function processComment() {

        // 入力されたコメントを検証。
        $errorMess = Common::validateInput($_POST['comment'], array('length'=>40), 2);

        // エラーがある場合はエラーメッセージをビューにセットしてリターン。
        if($errorMess != '') {
            $this->setAttribute('error', $errorMess);
            return;
        }

        // バトル情報を取得。
        $battleSvc = new Battle_LogService();
        $battle = $battleSvc->getRecord($_GET['battleId']);

        // 他人のバトルの場合はエラー。
        if($battle['player_id'] != $this->user_id)
            throw new MojaviException('他人のバトルにコメントを付けようとした');

        // コメントをセット。
        $battleSvc->setComment($_GET['battleId'], $_POST['comment']);

        // コメント結果画面に遷移。
        Common::redirect(array('_self'=>true, 'result'=>'done'));
    }
}
