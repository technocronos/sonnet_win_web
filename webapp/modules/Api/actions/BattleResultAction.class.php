<?php

class BattleResultAction extends SmfBaseAction {

    protected function doExecute($params) {

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
            return array('result'=> $battle ? $battle['true_status'] : -1);
        }

        //getで指定されてない場合は自分をとる
        if(!isset($_GET['side']) || $_GET['side'] == "")
            $_GET['side'] = $battle['side_reverse'] ? 'def' : 'cha';

        // バトルの詳細を取得する。
        $array = $this->setupResultView($battle);

        // 自分と同じ階級と、隣の階級を取得する。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id, true);
        $gradeSvc = new Grade_MasterService();
        $range = $gradeSvc->getRangeBorder($avatar['grade_id'], 1);

        $array['neighborGrades'] = $gradeSvc->getList('DESC', $range['upper'], $range['lower']);

        $array["chara"] = $avatar;

        //ここまでくればOK
        $array['result'] = "ok";

        return $array;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 結果画面を表示するための準備を行う。
     *
     * @param array     ballt_log レコード。
     */
    private function setupResultView($battle) {

        $array = [];

        // 挑戦側、防衛側のどちらを見ようとしているのかを取得。
        $side = ($_GET['side'] == 'def') ? 'defender' : 'challenger';
        $array['side'] = $side;

        // 表示する側で主観カラムを加える。
        Service::create('Battle_Log')->addBiasColumn($battle, $battle["{$side}_id"]);
        $array['battle'] = $battle;

        // コメント結果ページの表示ならココまででOK。
        if($_GET['result'])
            return $array;

        $array['capture_flg'] = false;

        // 自分のキャラについて表示するなら...
        if($battle['bias_user_id'] == $this->user_id) {

            $uitemSvc = new User_ItemService();

            // 現在値も表示する。
            $array['current'] = array(
                'exp' => Service::create('Character_Info')->getExpInfo($battle['bias_result']['character']),
                'gold' => $this->userInfo['gold'],
                'grade_pt' => $battle['bias_result']['character']['grade_pt'],
            );

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
                        $equip['repaire_useto'] = ViewUtil::serializeBackto(
                            array('scene'=>'Battle', 'repaireId'=>$equip['user_item_id'], 'firstscene' => 'result'
                        ));

                        //アイテム使用ページからの戻るボタンの戻り先
                        $backtoLink = ViewUtil::serializeBackto(
                            array('scene'=>'Battle', 'firstscene' => 'result', 'battleId' => $_GET['battleId'], 'side' => $_GET['side']
                        ));

                        //修理のリンク
                        $equip['urlOnRepaire'] = Common::genContainerUrl(
                            'Api', 'Suggest', array('type' => 'repaire', 'targetId' => $equip['user_item_id'], 'useto' => $equip['repaire_useto'], 'backto' => $backtoLink), true
                        );

                        // 警告があったことを覚えておく。
                        $warnExists = true;
                    }
                }

                //MAXレベル
                $maxLv = Service::create('Item_Level_Master')->getMaxLevel($equip['item_id'], $equip['evolution']);
                $equip['max_level'] = $maxLv;

            }unset($equip);

            //アイテム効果を表示
            if($battle['bias_result']['gain']['uitem'] != null){
                $array['item_flg'] = true;

                foreach($battle['bias_result']['gain']['uitem'] as &$uitem){
                    if($uitem["category"] == "ITM")
                        $uitem["effect"] = AppUtil::itemEffectStr($uitem);
                }unset($uitem);
            }

            // 耐久値警告装備がある場合は、耐久値回復アイテムを持っているかどうかを調べる。
            if($warnExists) {
                $array['holdRecover'] = (bool)$uitemSvc->getRecordByType($this->user_id, Item_MasterService::REPAIRE);
            }

            // キャプチャーモンスターがいる場合、その詳細を取得する。
            if($battle['bias_result']['gain']['monster']) {
                $array['capture_flg'] = true;

                $array['capture'] = Service::create('Monster_Master')->needRecord($battle['bias_result']['gain']['monster']);

                $array['capture']['monster_name'] = Text_LogService::get($array['capture']['name_id']);

                $array['capture']["equip"] = array();

                // 双方の画像情報を取得。
                $spec = CharaImageUtil::getSpec($array['capture']);
                $array['capture']['image_url'] = $spec;
            }else{
                $array['capture'] = null;
            }
        }

        // 詳細データをビューにセット。
        $array['ready'] = $battle['bias_ready'];
        $array['battleresult'] = $battle['bias_result'];

        //グレード昇格してるかどうか
        $before = $array['ready']['grade_id'];
        $after = $array['battleresult']['character']['grade_id'];

        // 昇格している場合...
        if($before < $after) {
            $array['gradeup'] = true;

            // 変化後の階級情報を取得。
            $grade = Service::create('Grade_Master')->needRecord($after);
            $array['grade'] = $grade;

            // 必殺技が設定されているならその情報を取得。
            if($grade['dtech_id'])
                $array['grade']['dtech'] = Service::create('Dtech_Master')->needRecord($grade['dtech_id']);
        }else{
            $array['gradeup'] = false;
        }

        $before = $array['ready'];
        $after = $array['battleresult']['character'];

        // アバターキャラがレベルアップしている場合。
        if($before['entry'] == 'AVT'  &&  $before['level'] < $after['level']) {
            $array['levelup'] = true;
        }else{
            $array['levelup'] = false;
        }

        //フィールドに戻る場合
        $array['urlOnHome'] = Common::genContainerUrl(
            'Api', 'Home', array(), true
        );

        //フィールドに戻る場合
        $array['urlOnSphere'] = Common::genContainerUrl(
            'Api', 'Sphere', array('id'=>$battle['relate_id']), true
        );

        //対戦相手一覧へ戻る場合
        $array['urlOnRivalList'] = Common::genContainerUrl(
            'Api', 'BattleList', array(), true
        );

        //対戦相手のページへの場合
        $array['urlOnHisPage'] = Common::genContainerUrl(
            'Api', 'HisPage', array('his_user_id'=>$array['battle']['rival_user_id']), true
        );

        return $array;
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
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * コメント入力を処理する。未使用・・
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
