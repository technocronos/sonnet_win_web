<?php

/**
 * モンスターの洞窟でのバトルについての、バトルユーティリティ実装。
   曜日クエスト機能を実装する
 */
Class FieldBattle99999Util extends FieldBattleUtil {

    /**
    　月曜　マグナ2倍クエスト
    　火曜　階級ポイント　2倍
    　水曜　アイテムドロップ確率2倍　クエスト
    　木曜　アイテムドロップ（ニワトリ）　クエスト
    　金曜　アイテムドロップ（マル槌）　クエスト
    　土曜　経験値2倍　クエスト
    　日曜　レア敵出現率UP　クエスト　
     */    
    const MAGUNA_GAIN_DAY = 1;
    const ITEMDROP_RATE_GAIN_DAY = 2;
    const TIMERECOVOR_ITEM_GET_DAY = 3;
    const EXP_GAIN_DAY = 4;
    const REAR_ENCOUNT_GAIN_DAY = 5;
    const GRADEPOINT_GAIN_DAY = 6;
    const WEAPONRECOVOR_ITEM_GET_DAY = 7;
    const CONTINUE_ITEM_GET_DAY = 8;

    //attract = 1 まもののエサと同じ効果。 attract = 2 まもののエサDX　attract = 3 それ以上
    const REAR_ENCOUNT_GAIN_1 = 1;
    const REAR_ENCOUNT_GAIN_2 = 2;
    const REAR_ENCOUNT_GAIN_3 = 3;

    public static function getRewordDay() {

    //レイドダンジョン中なら・・
    $raid = Service::create('Raid_Dungeon')->getCurrent();
    $raid_status = Service::create('Raid_Dungeon')->getStatus($raid);
    if($raid_status == Raid_DungeonService::START){
        return array('str' =>  str_replace("{0}", date('n/j', strtotime($raid["end_at"])), AppUtil::getText("sphere_text_raid_open")));
    }

    $dt = new DateTime();
    $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
    $currenttime = $dt->format('Y-m-d H:i:s');

    //今日は何の報酬の日か返す
    if(strtotime('2021-12-26 00:00:00') <= strtotime($currenttime) && strtotime('2022-01-03 23:59:59') > strtotime($currenttime)){
        return array('reword' => self::MAGUNA_GAIN_DAY, 'str' => '01/03まで獲得マグナ2倍！', 'rate' => 2);
    }

    //曜日クエ停止
    return array('str' => '');

    //マル槌固定
    return array('reword' => self::WEAPONRECOVOR_ITEM_GET_DAY, 'str' => '只今、マルティーニの槌取り放題！', 'rate' => 300);

        //週替わりにする
        switch(date('w')){
            case 1://月
                $result = array('reword' => self::MAGUNA_GAIN_DAY, 'str' => '毎週月曜日は獲得ﾏｸﾞﾅ2倍！', 'rate' => 2);
                break;
            case 2://火
                $result = array('reword' => self::CONTINUE_ITEM_GET_DAY, 'str' => '毎週火曜日はﾘﾚｲｻﾞｰGET！', 'rate' => 200);
                break;
            case 3://水
                $result = array('reword' => self::ITEMDROP_RATE_GAIN_DAY, 'str' => '毎週水曜日はｱｲﾃﾑﾄﾞﾛｯﾌﾟ率2倍！', 'rate' => 2);
                break;
            case 4://木
                //rate=1000分の何か
                $result = array('reword' => self::TIMERECOVOR_ITEM_GET_DAY, 'str' => '毎週木曜日はﾆﾜﾄﾘの時計GET！', 'rate' => 350);
                break;
            case 5://金
                //rate=1000分の何か
                $result = array('reword' => self::WEAPONRECOVOR_ITEM_GET_DAY, 'str' => '毎週金曜日はﾏﾙ槌取り放題！', 'rate' => 300);
                break;
            case 6://土
                $result = array('reword' => self::EXP_GAIN_DAY, 'str' => '毎週土曜日は獲得経験値2倍！', 'rate' => 2);
                break;
            case 0://日
                //１か２か３しか設定しちゃダメ
                $result = array('reword' => self::REAR_ENCOUNT_GAIN_DAY, 'str' => '毎週日曜日はﾚｱ敵遭遇率UP！', 'attract' => self::REAR_ENCOUNT_GAIN_3);
                break;
        }

        return $result;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユニットがフィールド上で打倒された場合の経験値とお金を計算する。
     *
     * @param array     打倒されたユニットのデータ
     * @param array     打倒したユニットのデータ
     * @return array    次のキーを持つ配列。
     *                      exp
     *                      gold
     */
    public static function getFieldReward($terminated, $terminator) {

        $result = parent::getFieldReward($terminated, $terminator);
        $reword = self::getRewordDay();

        //土曜　経験値2倍
        if($reword['reword'] == self::EXP_GAIN_DAY)
            $result['exp'] = $result['exp'] * $reword['rate'];

        //月曜　マグナ2倍
        if($reword['reword'] == self::MAGUNA_GAIN_DAY)
            $result['gold'] = $result['gold'] * $reword['rate'];
        
        return $result;
    }



    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleExpをオーバーライド。
     */
    protected function getBattleExp($battle, $detail) {

        $result = parent::getBattleExp($battle, $detail);
        $reword = self::getRewordDay();

        //土曜　経験値2倍
        if($reword['reword'] == self::EXP_GAIN_DAY){
            $result['defender'] = $result['defender'] * $reword['rate'];
            $result['challenger'] = $result['challenger'] * $reword['rate'];
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleGold()から一部の処理を切り出したもの。
     * 引数で指定されたデータを持つキャラが倒されたときのお金を返す。
     */
    protected function getTerminatedGold($terminated) {

        $result = parent::getTerminatedGold($terminated);
        $reword = self::getRewordDay();

        //月曜　マグナ2倍
        if($reword['reword'] == self::MAGUNA_GAIN_DAY)
            return $result * $reword['rate'];
        else
            return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleGradeをオーバーライド。
     */
    protected function getBattleGrade($battle, $detail) {

        $result = parent::getBattleGrade($battle, $detail);
        $reword = self::getRewordDay();

        if($reword['reword'] == self::GRADEPOINT_GAIN_DAY){
            $result['challenger'] = $result['challenger'] * $reword['rate'];
            $result['defender'] = $result['defender'] * $reword['rate'];
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleItemsをオーバーライド。
     */
    protected function getBattleItems($battle, $detail) {

        // 戻り値初期化。
        $result = array('challenger'=>array(), 'defender'=>array());
        $reword = self::getRewordDay();

        $rate = 1;

        //アイテムドロップ2倍
        if($reword['reword'] == self::ITEMDROP_RATE_GAIN_DAY){
            $rate = $reword['rate'];
        }

        // 相打ちや時間切れではアイテムはない。勝敗が決まっているなら...
        if($detail['result'] == Battle_LogService::CHA_WIN  ||  $detail['result'] == Battle_LogService::DEF_WIN) {

            // 勝ったほう、負けたほうを取得。
            $winer = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender'];
            $loser = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'defender' : 'challenger'];

            // 勝ったほうがプレイヤー配下のユニットならば判定する。
            if($winer['player_owner']) {

                // 戻り値の勝ったほうへの参照を取得。
                $gain = &$result[($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender'];

                // "srare_drop", "rare_drop", "normal_drop" のうち設定されているものを、低確率のものから
                // 判定していく。
                if($loser['srare_drop']  &&  mt_rand(1, 1000) <= 1 * $rate)
                    $gain[] = $loser['srare_drop'];
                else if($loser['rare_drop']  &&  mt_rand(1, 1000) <= 10 * $rate)
                    $gain[] = $loser['rare_drop'];
                else if($loser['normal_drop']  &&  mt_rand(1, 1000) <= 100 * $rate)
                    $gain[] = $loser['normal_drop'];

//Common::varLog("winer_level=" . $winer["level"]);
//Common::varLog("loser_level=" . $loser["level"]);
                
                //プレイヤーのレベル-5よりモンスターが上の場合。あまりに弱い敵だと取れないように。
                if(($winer["level"] - 5) <= $loser["level"]){
//Common::varLog("OK");
                    //ニワトリの時計
                    if($reword['reword'] == self::TIMERECOVOR_ITEM_GET_DAY){
                        if(mt_rand(1, 1000) <= $reword['rate'])
                            $gain[] = 1902;
                    //マルティーニの槌
                    }else if($reword['reword'] == self::WEAPONRECOVOR_ITEM_GET_DAY){
                        if(mt_rand(1, 1000) <= $reword['rate'])
                            $gain[] = 1905;
                    //リレイザー
                    }else if($reword['reword'] == self::CONTINUE_ITEM_GET_DAY){
                        if(mt_rand(1, 1000) <= $reword['rate'])
                            $gain[] = 1911;
                    }
                }
/*
                //レベル別でレア、Sレアに勝つと課金武器がレベルに応じて手に入るようにする。
                $sphereData = Service::create('Sphere_Info')->needRecord($battle['relate_id']);
                //Common::varLog($sphereData);

                $monster = Service::create('Monster_Master')->getRecord($loser['character_id']);
                //Sレア
                if($monster["rare_level"] == 3){
                    if(mt_rand(1, 1000) <= 700){
                        if($sphereData["state"]["current_room"] >= 1 && $sphereData["state"]["current_room"] <= 10){
                            //水着セット
                            $set_id = 10005;
                        }else if($sphereData["state"]["current_room"] >= 11 && $sphereData["state"]["current_room"] <= 20){
                            //ドレスセット
                            $set_id = 10007;
                        }else if($sphereData["state"]["current_room"] >= 21 && $sphereData["state"]["current_room"] <= 30){
                            //アリスセット
                            $set_id = 10009;
                        }else if($sphereData["state"]["current_room"] >= 31 && $sphereData["state"]["current_room"] <= 40){
                            //ダサロボセット
                            $set_id = 10012;
                        }else if($sphereData["state"]["current_room"] >= 41 && $sphereData["state"]["current_room"] <= 50){
                            //踊り子セット
                            $set_id = 10014;
                        }else if($sphereData["state"]["current_room"] >= 51 && $sphereData["state"]["current_room"] <= 60){
                            //ユニコーンセット
                            $set_id = 10016;
                        }else if($sphereData["state"]["current_room"] >= 61 && $sphereData["state"]["current_room"] <= 70){
                            //海賊セット
                            $set_id = 10018;
                        }else if($sphereData["state"]["current_room"] >= 71 && $sphereData["state"]["current_room"] <= 80){
                            //ドラゴンセット
                            $set_id = 10021;
                        }else if($sphereData["state"]["current_room"] >= 81 && $sphereData["state"]["current_room"] <= 90){
                            //オリハルコンセット
                            $set_id = 10023;
                        }else if($sphereData["state"]["current_room"] >= 91 && $sphereData["state"]["current_room"] <= 100){
                            //オリハルコンセット
                            $set_id = 10023;
                        }
                        $mount = mt_rand(1, 4);
                        $item = Service::create('Item_Master')->getSetItem($set_id , $mount);
                        $gain[] = $item["item_id"];
                    }
                //レア
                }else if($monster["rare_level"] == 2){
                    if(mt_rand(1, 1000) <= 700){
                        if($sphereData["state"]["current_room"] >= 1 && $sphereData["state"]["current_room"] <= 6){
                            //初心者セット
                            $set_id = 10001;
                        }else if($sphereData["state"]["current_room"] >= 7 && $sphereData["state"]["current_room"] <= 12){
                            //シーフセット
                            $set_id = 10002;
                        }else if($sphereData["state"]["current_room"] >= 13 && $sphereData["state"]["current_room"] <= 18){
                            //クリスタルセット
                            $set_id = 10003;
                        }else if($sphereData["state"]["current_room"] >= 19 && $sphereData["state"]["current_room"] <= 24){
                            //甲冑セット
                            $set_id = 10006;
                        }else if($sphereData["state"]["current_room"] >= 25 && $sphereData["state"]["current_room"] <= 30){
                            //エルフセット
                            $set_id = 10008;
                        }else if($sphereData["state"]["current_room"] >= 31 && $sphereData["state"]["current_room"] <= 36){
                            //ホームズセット
                            $set_id = 10010;
                        }else if($sphereData["state"]["current_room"] >= 37 && $sphereData["state"]["current_room"] <= 42){
                            //お嬢様セット
                            $set_id = 10011;
                        }else if($sphereData["state"]["current_room"] >= 43 && $sphereData["state"]["current_room"] <= 48){
                            //科学セット
                            $set_id = 10013;
                        }else if($sphereData["state"]["current_room"] >= 49 && $sphereData["state"]["current_room"] <= 54){
                            //戦士セット
                            $set_id = 10015;
                        }else if($sphereData["state"]["current_room"] >= 55 && $sphereData["state"]["current_room"] <= 60){
                            //忍者セット
                            $set_id = 10017;
                        }else if($sphereData["state"]["current_room"] >= 61 && $sphereData["state"]["current_room"] <= 66){
                            //ギャングセット
                            $set_id = 10019;
                        }else if($sphereData["state"]["current_room"] >= 67 && $sphereData["state"]["current_room"] <= 72){
                            //ギャングセット
                            $set_id = 10019;
                        }else if($sphereData["state"]["current_room"] >= 73 && $sphereData["state"]["current_room"] <= 78){
                            //ヒーローセット
                            $set_id = 10020;
                        }else if($sphereData["state"]["current_room"] >= 79 && $sphereData["state"]["current_room"] <= 84){
                            //ヒーローセット
                            $set_id = 10020;
                        }else if($sphereData["state"]["current_room"] >= 85 && $sphereData["state"]["current_room"] <= 90){
                            //魔女セット
                            $set_id = 10022;
                        }else if($sphereData["state"]["current_room"] >= 91 && $sphereData["state"]["current_room"] <= 100){
                            //魔女セット
                            $set_id = 10022;
                        }

                        $mount = mt_rand(1, 4);
                        $item = Service::create('Item_Master')->getSetItem($set_id , $mount);
                        $gain[] = $item["item_id"];
                    }
                }
*/
            }
        }

        // リターン。
        return $result;
    }
}
