<?php

class Ranking_LogService extends Service {

    // "type" 列の値。
    const GRADEPT_DAILY = 11;
    const GRADEPT_WEEKLY = 12;

    // 褒章アイテムの設定。キーは、"type"列の値 => 順位。
    public static $PRIZES = array(
        self::GRADEPT_WEEKLY => array(
              1  => array("id" => 14033, "count"=> 1, "btc" => 0.002),
              2  => array("id" => 14032, "count"=> 1, "btc" => 0.001),
              3  => array("id" => 14031, "count"=> 1, "btc" => 0.0009),
              4  => array("id" => 14030, "count"=> 1, "btc" => 0.0008),
              5  => array("id" => 14029, "count"=> 1, "btc" => 0.0007),
              6  => array("id" => 14024, "count"=> 1, "btc" => 0.0006),
              7  => array("id" => 14027, "count"=> 1, "btc" =>  0.0005),
              8  => array("id" => 14028, "count"=> 1, "btc" => 0.0004),
              9  => array("id" => 14026, "count"=> 1, "btc" => 0.0003),
              10  => array("id" => 14025, "count"=> 1, "btc" =>0.0002),
              11 => array("id" => 14023, "count"=> 1, "btc" => 0.0001),
              12 => array("id" => 14022, "count"=> 1, "btc" => 0.00009),
              13 => array("id" => 14017, "count"=> 1, "btc" => 0.00008),
              14 => array("id" => 14020, "count"=> 1, "btc" => 0.00007),
              15  => array("id" => 14018, "count"=> 1, "btc" =>0.00006),
              16  => array("id" => 14021, "count"=> 1, "btc" =>0.00005),
              17  => array("id" => 14016, "count"=> 1, "btc" =>0.00004),
              18  => array("id" => 14015, "count"=> 1, "btc" =>0.00003),
              19  => array("id" => 14013, "count"=> 1, "btc" =>0.00002),
              20  => array("id" => 14012, "count"=> 1, "btc" =>0.00001),
              21  => array("id" => 14014, "count"=> 1, "btc" =>0.00001),
              22  => array("id" => 14009, "count"=> 1, "btc" =>0.00001),
              23  => array("id" => 14011, "count"=> 1, "btc" =>0.00001),
              24  => array("id" => 14008, "count"=> 1, "btc" =>0.00001),
              25=> array("id" => 1906, "count"=> 1, "btc" => 0),
              26=> array("id" => 1906, "count"=> 1, "btc" => 0),
              27=> array("id" => 1906, "count"=> 1, "btc" => 0),
              28=> array("id" => 1906, "count"=> 1, "btc" => 0),
              29=> array("id" => 1906, "count"=> 1, "btc" => 0),
              30=> array("id" => 1906, "count"=> 1, "btc" => 0),
              31=> array("id" => 1906, "count"=> 1, "btc" => 0),
              32=> array("id" => 1906, "count"=> 1, "btc" => 0),
              33=> array("id" => 1906, "count"=> 1, "btc" => 0),
              34=> array("id" => 1906, "count"=> 1, "btc" => 0),
              35=> array("id" => 1906, "count"=> 1, "btc" => 0),
              36=> array("id" => 1906, "count"=> 1, "btc" => 0),
              37=> array("id" => 1906, "count"=> 1, "btc" => 0),
              38=> array("id" => 1906, "count"=> 1, "btc" => 0),
              39=> array("id" => 1906, "count"=> 1, "btc" => 0),
              40=> array("id" => 1906, "count"=> 1, "btc" => 0),
              41=> array("id" => 1906, "count"=> 1, "btc" => 0),
              42=> array("id" => 1906, "count"=> 1, "btc" => 0),
              43=> array("id" => 1906, "count"=> 1, "btc" => 0),
              44=> array("id" => 1906, "count"=> 1, "btc" => 0),
              45=> array("id" => 1906, "count"=> 1, "btc" => 0),
              46=> array("id" => 1906, "count"=> 1, "btc" => 0),
              47=> array("id" => 1906, "count"=> 1, "btc" => 0),
              48=> array("id" => 1906, "count"=> 1, "btc" => 0),
              49=> array("id" => 1906, "count"=> 1, "btc" => 0),
              50=> array("id" => 1906, "count"=> 1, "btc" => 0),
              51=> array("id" => 1906, "count"=> 1, "btc" => 0),
              52=> array("id" => 1906, "count"=> 1, "btc" => 0),
              53=> array("id" => 1906, "count"=> 1, "btc" => 0),
              54=> array("id" => 1906, "count"=> 1, "btc" => 0),
              55=> array("id" => 1906, "count"=> 1, "btc" => 0),
              56=> array("id" => 1906, "count"=> 1, "btc" => 0),
              57=> array("id" => 1906, "count"=> 1, "btc" => 0),
              58=> array("id" => 1906, "count"=> 1, "btc" => 0),
              59=> array("id" => 1906, "count"=> 1, "btc" => 0),
              60=> array("id" => 1906, "count"=> 1, "btc" => 0),
              61=> array("id" => 1906, "count"=> 1, "btc" => 0),
              62=> array("id" => 1906, "count"=> 1, "btc" => 0),
              63=> array("id" => 1906, "count"=> 1, "btc" => 0),
              64=> array("id" => 1906, "count"=> 1, "btc" => 0),
              65=> array("id" => 1906, "count"=> 1, "btc" => 0),
              66=> array("id" => 1906, "count"=> 1, "btc" => 0),
              67=> array("id" => 1906, "count"=> 1, "btc" => 0),
              68=> array("id" => 1906, "count"=> 1, "btc" => 0),
              69=> array("id" => 1906, "count"=> 1, "btc" => 0),
              70=> array("id" => 1906, "count"=> 1, "btc" => 0),
              71=> array("id" => 1906, "count"=> 1, "btc" => 0),
              72=> array("id" => 1906, "count"=> 1, "btc" => 0),
              73=> array("id" => 1906, "count"=> 1, "btc" => 0),
              74=> array("id" => 1906, "count"=> 1, "btc" => 0),
              75=> array("id" => 1906, "count"=> 1, "btc" => 0),
              76=> array("id" => 1906, "count"=> 1, "btc" => 0),
              77=> array("id" => 1906, "count"=> 1, "btc" => 0),
              78=> array("id" => 1906, "count"=> 1, "btc" => 0),
              79=> array("id" => 1906, "count"=> 1, "btc" => 0),
              80=> array("id" => 1906, "count"=> 1, "btc" => 0),
              81=> array("id" => 1906, "count"=> 1, "btc" => 0),
              82=> array("id" => 1906, "count"=> 1, "btc" => 0),
              83=> array("id" => 1906, "count"=> 1, "btc" => 0),
              84=> array("id" => 1906, "count"=> 1, "btc" => 0),
              85=> array("id" => 1906, "count"=> 1, "btc" => 0),
              86=> array("id" => 1906, "count"=> 1, "btc" => 0),
              87=> array("id" => 1906, "count"=> 1, "btc" => 0),
              88=> array("id" => 1906, "count"=> 1, "btc" => 0),
              89=> array("id" => 1906, "count"=> 1, "btc" => 0),
              90=> array("id" => 1906, "count"=> 1, "btc" => 0),
              91=> array("id" => 1906, "count"=> 1, "btc" => 0),
              92=> array("id" => 1906, "count"=> 1, "btc" => 0),
              93=> array("id" => 1906, "count"=> 1, "btc" => 0),
              94=> array("id" => 1906, "count"=> 1, "btc" => 0),
              95=> array("id" => 1906, "count"=> 1, "btc" => 0),
              96=> array("id" => 1906, "count"=> 1, "btc" => 0),
              97=> array("id" => 1906, "count"=> 1, "btc" => 0),
              98=> array("id" => 1906, "count"=> 1, "btc" => 0),
              99=> array("id" => 1906, "count"=> 1, "btc" => 0),
              100=> array("id" => 1906, "count"=> 1, "btc" => 0),
        ),
        self::GRADEPT_DAILY => array(
              1 => array("id" => 1901, "count"=> 1, "btc" => 0),
              2 => array("id" => 1901, "count"=> 1, "btc" => 0),
              3 => array("id" => 1901, "count"=> 1, "btc" => 0),
              4 => array("id" => 1901, "count"=> 1, "btc" =>  0),
              5 => array("id" => 1901, "count"=> 1, "btc" =>  0),
              6 => array("id" => 1901, "count"=> 1, "btc" =>  0),
              7 => array("id" => 1901, "count"=> 1, "btc" =>  0),
              8 => array("id" => 1901, "count"=> 1, "btc" =>  0),
              9 => array("id" => 1901, "count"=> 1, "btc" =>  0),
              10 => array("id" => 1901, "count"=> 1, "btc" => 0),
              11 => array("id" => 1901, "count"=> 1, "btc" => 0),
              12 => array("id" => 1901, "count"=> 1, "btc" => 0),
              13 => array("id" => 1901, "count"=> 1, "btc" => 0),
              14 => array("id" => 1901, "count"=> 1, "btc" => 0),
              15 => array("id" => 1901, "count"=> 1, "btc" => 0),
              16 => array("id" => 1901, "count"=> 1, "btc" => 0),
              17 => array("id" => 1901, "count"=> 1, "btc" => 0),
              18 => array("id" => 1901, "count"=> 1, "btc" => 0),
              19 => array("id" => 1901, "count"=> 1, "btc" => 0),
              20 => array("id" => 1901, "count"=> 1, "btc" => 0),
              21 => array("id" => 1901, "count"=> 1, "btc" => 0),
              22=> array("id" => 1901, "count"=> 1, "btc" => 0),
              23=> array("id" => 1901, "count"=> 1, "btc" => 0),
              24=> array("id" => 1901, "count"=> 1, "btc" => 0),
              25=> array("id" => 1901, "count"=> 1, "btc" => 0),
              26=> array("id" => 1901, "count"=> 1, "btc" => 0),
              27=> array("id" => 1901, "count"=> 1, "btc" => 0),
              28=> array("id" => 1901, "count"=> 1, "btc" => 0),
              29=> array("id" => 1901, "count"=> 1, "btc" => 0),
              30=> array("id" => 1901, "count"=> 1, "btc" => 0),
              31=> array("id" => 1901, "count"=> 1, "btc" => 0),
              32=> array("id" => 1901, "count"=> 1, "btc" => 0),
              33=> array("id" => 1901, "count"=> 1, "btc" => 0),
              34=> array("id" => 1901, "count"=> 1, "btc" => 0),
              35=> array("id" => 1901, "count"=> 1, "btc" => 0),
              36=> array("id" => 1901, "count"=> 1, "btc" => 0),
              37=> array("id" => 1901, "count"=> 1, "btc" => 0),
              38=> array("id" => 1901, "count"=> 1, "btc" => 0),
              39=> array("id" => 1901, "count"=> 1, "btc" => 0),
              40=> array("id" => 1901, "count"=> 1, "btc" => 0),
              41=> array("id" => 1901, "count"=> 1, "btc" => 0),
              42=> array("id" => 1901, "count"=> 1, "btc" => 0),
              43=> array("id" => 1901, "count"=> 1, "btc" => 0),
              44=> array("id" => 1901, "count"=> 1, "btc" => 0),
              45=> array("id" => 1901, "count"=> 1, "btc" => 0),
              46=> array("id" => 1901, "count"=> 1, "btc" => 0),
              47=> array("id" => 1901, "count"=> 1, "btc" => 0),
              48=> array("id" => 1901, "count"=> 1, "btc" => 0),
              49=> array("id" => 1901, "count"=> 1, "btc" => 0),
              50=> array("id" => 1901, "count"=> 1, "btc" => 0),
              51=> array("id" => 1901, "count"=> 1, "btc" => 0),
              52=> array("id" => 1901, "count"=> 1, "btc" => 0),
              53=> array("id" => 1901, "count"=> 1, "btc" => 0),
              54=> array("id" => 1901, "count"=> 1, "btc" => 0),
              55=> array("id" => 1901, "count"=> 1, "btc" => 0),
              56=> array("id" => 1901, "count"=> 1, "btc" => 0),
              57=> array("id" => 1901, "count"=> 1, "btc" => 0),
              58=> array("id" => 1901, "count"=> 1, "btc" => 0),
              59=> array("id" => 1901, "count"=> 1, "btc" => 0),
              60=> array("id" => 1901, "count"=> 1, "btc" => 0),
              61=> array("id" => 1901, "count"=> 1, "btc" => 0),
              62=> array("id" => 1901, "count"=> 1, "btc" => 0),
              63=> array("id" => 1901, "count"=> 1, "btc" => 0),
              64=> array("id" => 1901, "count"=> 1, "btc" => 0),
              65=> array("id" => 1901, "count"=> 1, "btc" => 0),
              66=> array("id" => 1901, "count"=> 1, "btc" => 0),
              67=> array("id" => 1901, "count"=> 1, "btc" => 0),
              68=> array("id" => 1901, "count"=> 1, "btc" => 0),
              69=> array("id" => 1901, "count"=> 1, "btc" => 0),
              70=> array("id" => 1901, "count"=> 1, "btc" => 0),
              71=> array("id" => 1901, "count"=> 1, "btc" => 0),
              72=> array("id" => 1901, "count"=> 1, "btc" => 0),
              73=> array("id" => 1901, "count"=> 1, "btc" => 0),
              74=> array("id" => 1901, "count"=> 1, "btc" => 0),
              75=> array("id" => 1901, "count"=> 1, "btc" => 0),
              76=> array("id" => 1901, "count"=> 1, "btc" => 0),
              77=> array("id" => 1901, "count"=> 1, "btc" => 0),
              78=> array("id" => 1901, "count"=> 1, "btc" => 0),
              79=> array("id" => 1901, "count"=> 1, "btc" => 0),
              80=> array("id" => 1901, "count"=> 1, "btc" => 0),
              81=> array("id" => 1901, "count"=> 1, "btc" => 0),
              82=> array("id" => 1901, "count"=> 1, "btc" => 0),
              83=> array("id" => 1901, "count"=> 1, "btc" => 0),
              84=> array("id" => 1901, "count"=> 1, "btc" => 0),
              85=> array("id" => 1901, "count"=> 1, "btc" => 0),
              86=> array("id" => 1901, "count"=> 1, "btc" => 0),
              87=> array("id" => 1901, "count"=> 1, "btc" => 0),
              88=> array("id" => 1901, "count"=> 1, "btc" => 0),
              89=> array("id" => 1901, "count"=> 1, "btc" => 0),
              90=> array("id" => 1901, "count"=> 1, "btc" => 0),
              91=> array("id" => 1901, "count"=> 1, "btc" => 0),
              92=> array("id" => 1901, "count"=> 1, "btc" => 0),
              93=> array("id" => 1901, "count"=> 1, "btc" => 0),
              94=> array("id" => 1901, "count"=> 1, "btc" => 0),
              95=> array("id" => 1901, "count"=> 1, "btc" => 0),
              96=> array("id" => 1901, "count"=> 1, "btc" => 0),
              97=> array("id" => 1901, "count"=> 1, "btc" => 0),
              98=> array("id" => 1901, "count"=> 1, "btc" => 0),
              99=> array("id" => 1901, "count"=> 1, "btc" => 0),
              100=> array("id" => 1901, "count"=> 1, "btc" => 0),
        ),
    );

    //-----------------------------------------------------------------------------------------------------
    /**
     * ランキングの集計をするかどうかを返す。ランキング大会は日曜から翌週日曜なので集計は月曜から翌週月曜まで。
     *
     * @return bool
     */
    public function isAggregate() {
        //第何週で開催するか
        $startweek = BATTLE_RANK_WEEK;

        //設定が無い場合は常時開催でリターン
        if($startweek == 0){
            return true;
        }

        $now = new DateTime();
        $now->setTime(0, 0, 0);

        //集計開始日
        $startdate = Common::getDateFromWeekInfo(date("Y"), date("m"), $startweek, 0)->add(new DateInterval('P0Y0M1D'));
        //結果発表日
        $resultdate = Common::getDateFromWeekInfo(date("Y"), date("m"), $startweek, 0)->add(new DateInterval('P0Y0M7D'));

        //集計を受け付ける
        if($now >= $startdate && $now <= $resultdate){
            return true;
        //ここでまだ非開催の場合
        }else{
            //先月の開催日の結果発表が被ってるかどうか調べる
            //先月の開催日の結果発表が被ってるかどうか調べる
            $y = date("Y");
            $m = date("m") - 1;

            if((int)date("m") == 1){
                $y = date("Y") - 1;
            }
            $laststartdate = Common::getDateFromWeekInfo($y, $m, $startweek , 0)->add(new DateInterval('P0Y0M1D'));
            $lastresultdate = Common::getDateFromWeekInfo($y, $m, $startweek, 0)->add(new DateInterval('P0Y0M7D'));
            //まだ先月の結果発表日の場合
            if($now >= $laststartdate && $now <= $lastresultdate){
                return true;
            }
        }

        return false;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ランキングの集計対象外ユーザーかどうか判別する。
     * とりあえずリリース日時より前にできているユーザーはテストユーザーと見なす。
     *
     * @return bool
     */
    public function isApplicable($user_id) {

//調査したところそんな影響ないのでtrueで・・
return true;

        $user = Service::create('User_Info')->getRecord($user_id);

        if(strtotime($user["create_at"]) < strtotime(RELEASE_DATE)){
Common::varLog("ランキング集計対象外ユーザー :" . $user_id . " create_at :" . $user["create_at"]);
            return false;
        }else{
Common::varLog("ランキング集計対象ユーザー :" . $user_id . " create_at :" . $user["create_at"]);
            return true;
        }

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ランキングの状態を取得する。
     *
     * @return array      1:開催中 2:結果発表中 3:非開催 4:準備中
     */
    public function getRankingStatus() {
        $startweek = BATTLE_RANK_WEEK;

        $res = array();

        $res["status"] = 3;
        $res["in_aggregate"] = false;
        $res["start_date"] = null;
        $res["result_date"] = null;
        $res["end_date"] = null;

        //設定が無い場合は常時開催でリターン
        if($startweek == 0){
            $res["status"] = 1;
            return $res;
        }

        $now = new DateTime();

        $nowdate = new DateTime();
        $nowdate = $nowdate->setTime(0, 0, 0);

        //ランキング開始日
        $startdate = Common::getDateFromWeekInfo(date("Y"), date("m"), $startweek, 0);
        $res["start_date"] = $startdate->getTimestamp();

        //現在日との差分
        $interval = $nowdate->diff($startdate);

        //開始3日前の場合
        if($interval->invert == 0 && $interval->d <= 3 && $interval->d > 0){
            $res["status"] = 4;
        }

        //結果発表日
        $resultdate = Common::getDateFromWeekInfo(date("Y"), date("m"), $startweek, 0)->add(new DateInterval('P0Y0M7D'));
        $res["result_date"] = $resultdate->getTimestamp();

        //ランキング開催中
        if($nowdate >= $startdate && $nowdate < $resultdate){
            //最初の集計が終わるまでは集計中・・
            if($now < Common::getDateFromWeekInfo(date("Y"), date("m"), $startweek, 0)->add(new DateInterval('P0Y0M1DT5H')))
                $res["in_aggregate"] = true;

            $res["status"] = 1;
        }

        //終了日
        $closedate = Common::getDateFromWeekInfo(date("Y"), date("m"), $startweek, 0)->add(new DateInterval('P0Y0M14D'));
        $res["end_date"] = $closedate->getTimestamp();

        //結果発表中
        if($nowdate >= $resultdate && $nowdate < $closedate){
            //最後の集計が終わるまでは集計中・・
            if($now < Common::getDateFromWeekInfo(date("Y"), date("m"), $startweek, 0)->add(new DateInterval('P0Y0M7DT5H')))
                $res["in_aggregate"] = true;

            $res["status"] = 2;
        }

        //ここでまだ非開催の場合
        if($res["status"] == 3){
            //先月の開催日の結果発表が被ってるかどうか調べる
            $y = date("Y");
            $m = date("m") - 1;

            if((int)date("m") == 1){
                $y = date("Y") - 1;
            }

            $laststartdate = Common::getDateFromWeekInfo($y, $m, $startweek , 0);
            $lastresultdate = Common::getDateFromWeekInfo($y, $m, $startweek, 0)->add(new DateInterval('P0Y0M7D'));
            $lastclosedate = Common::getDateFromWeekInfo($y, $m, $startweek, 0)->add(new DateInterval('P0Y0M14D'));

            //まだ先月の結果発表日の場合
            if($nowdate >= $lastresultdate && $nowdate < $lastclosedate){
                //最後の集計が終わるまでは集計中・・
                if($now < Common::getDateFromWeekInfo($y, $m, $startweek, 0)->add(new DateInterval('P0Y0M7DT5H')))
                    $res["in_aggregate"] = true;

                $res["start_date"] = $laststartdate->getTimestamp();
                $res["result_date"] = $lastresultdate->getTimestamp();
                $res["end_date"] = $lastclosedate->getTimestamp();
                $res["status"] = 2;
            }
        }

        return $res;     
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの過去最高位を取得する。
     *
     * @param int       ユーザID。
     * @return array    次のキーをもつ配列。
     *                      daily       日別ランキング最高位。nullはまだエントリしていないことを表す
     *                      weekly      週別ランキング最高位。nullはまだエントリしていないことを表す
     */
    public function getHighestRank($userId) {

        // type=0 のレコードを取り出す。
        $records = $this->selectResultset(array('type'=>0, 'user_id'=>$userId));

        // 一つずつ見ていき、戻り値の所定のキーに順位を格納する。
        $result = array();
        foreach($records as $record) {

            switch($record['period']) {
                case self::GRADEPT_DAILY:   $key = 'daily';     break;
                case self::GRADEPT_WEEKLY:  $key = 'weekly';    break;
                default:    $key = '';
            }

            if($key)
                $result[$key] = $record['rank'];
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のランキング種別の、集計されている最新期間を返す。
     *
     * @param int   種別を表す数値。このクラスの定数を使用する。
     * @return int  期間を表す数値。
     */
    public function getNewestPeriod($type) {

        $sql = '
            SELECT MAX(period)
            FROM ranking_log
            WHERE type = ?
        ';

        return $this->createDao(true)->getOne($sql, $type);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のランキング期間の開始と終わりのタイムスタンプを返す。
     *
     * @param int       種別を表す数値。このクラスの定数を使用する。
     * @param string    期間を表す数値。省略時は現在日時が所属する期間で計算する。
     *                  "prev" を指定すると現在日時が所属する期間の前の期間を返す。
     * @return array    次のキーを持つ配列。
     *                      begin   開始日の00:00:00のタイムスタンプ
     *                      end     終了日の23:59:59のタイムスタンプ
     */
    public function getRankingTerm($type, $when = null) {

        // 指定されたランキング種別の期間日数を取得。
        $days = $this->getIntervalDays($type);

        // 開始日の00:00のタイムスタンプを変数 $begin に取得する。期間が指定されている場合は...
        if( is_numeric($when) ) {
            $begin = strtotime($when);

        // 現在日時を元に計算する場合は...
        }else {

            // 現在日時が所属する期間の開始を取得。
            if($days == 1)
                $begin = strtotime('today');
            else
                $begin = (date('l') == 'Sunday') ? strtotime('today') : strtotime('last Sunday');

            // 前の期間を求められている場合は期間分引いておく。
            if($when == 'prev')
                $begin = strtotime("-{$days} day", $begin);
        }

        // 開始日と日数から終了日を計算。
        $end = strtotime("+{$days} day", $begin) - 1;

        // リターン。
        return array('begin'=>$begin, 'end'=>$end);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された種別、期間のランキングをページ分けして取得する。
     *
     * @param int       種別を表す数値。このクラスの定数を使用する。
     * @param int       期間を表す数値。
     * @param int       1ページあたりの件数。省略時は10。
     * @param int       取得するページ。0スタート。省略時は0。
     * @return array    Service::selectPageと同様。
     */
    public function getRankingList($type, $period, $numOnPage, $page = 0) {

        $condition = array(
            'type' => $type,
            'period' => $period,
            'ORDER BY' => 'rank',
        );

        return $this->selectPage($condition, $numOnPage, $page);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ウィークリーのバトルランキングのサイクル(前週の結果が出てる状態とか暫定集計してる状態とか)を返す。
     *
     * @return int      以下の値のいずれか
     *                      1: 現在週の暫定集計結果が出ている状態
     *                      2: 現在週が残り1日の状態
     *                      3: 前週の集計がなされており、現在週の集計がない状態。
     *                      4: 3と同じだが、現在週が始まってまだ時間が経過していない状態(前週の確定結果が
     *                         まだない状態)
     */
    public function getRankingCycle() {

        // 記録のある最新の期間と、現在期間を取得する。
        $newest = $this->getNewestPeriod(Ranking_LogService::GRADEPT_WEEKLY);
        $current = $this->getRankingTerm(Ranking_LogService::GRADEPT_WEEKLY);

        // 現在期間の開始と、記録のある最新期間が一致するなら、現在週の暫定集計結果が出ている状態。
        if(date('Ymd', $current['begin']) == $newest) {

            // 今が最終日かどうかで戻り値を分ける。
            return (date('Ymd', $current['end']) == date('Ymd')) ? 2 : 1;

        // 今週の結果がないなら...
        }else {

            // 今が今週が始まってから5時間経過したかどうかで戻り値を分ける。
            return (time() < strtotime('+5 Hour', $current['begin'])) ? 4 : 3;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種別、期間のランキングデータを削除する。
     *
     * @param int   種別を表す数値。このクラスの定数を使用する。
     * @param int   期間を表す数値。
     */
    public function clearLog($type, $period) {

        $this->createDao()->delete(array(
            'type' => $type,
            'period' => $period,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種別、期間のポイントを集計し、レコードを作成する。
     *
     * @param int   種別を表す数値。このクラスの定数を使用する。
     * @param int   期間を表す数値。
     */
    public function sumupPoint($type, $period) {

        $battleSvc = new Battle_LogService();
        $dao = $this->createDao();

        // 該当期間のレコードをクリア。
        $this->clearLog($type, $period);

        // 期間の開始の日時を取得。
        $begin = substr($period, 0, 4) . '/' . substr($period, 4, 2) . '/' . substr($period, 6, 2);

        // 期間の終わりの日時を取得。
        $unit = $this->getIntervalDays($type).' day';
        $end = date('Y/m/d', strtotime($unit, strtotime($begin)));

        // ユーザIDをキー、ポイントを値とする配列を得る。
        $points = $battleSvc->sumupPoint('grade', $begin, $end);

        // 各ユーザのポイントをすべてレコードとしてINSERT。
        $inserter = new BulkInserter('ranking_log', $dao);
        foreach($points as $userId => $point){
            if($this->isApplicable($userId))
                $inserter->insert($type, $period, $userId, $point, 0);
        }

        $inserter->flush();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種別、期間のレコードを対象として、ポイントを元に "rank" 列を更新する。
     *
     * @param int   種別を表す数値。このクラスの定数を使用する。
     * @param int   期間を表す数値。
     */
    public function updateRank($type, $period) {

        $dao = $this->createDao();

        // 指定された種別、期間のレコードを "point" 列降順に並び替えて取得。
        $sql = '
            SELECT user_id, point
            FROM ranking_log
            WHERE type = ?
              AND period = ?
            ORDER BY point DESC
        ';
        $orders = $dao->getAll($sql, array($type, $period));

        // ユーザIDとその順位を保持する一時テーブルを作成。
        $dao->execute('
            CREATE TEMPORARY TABLE ranking_t (
                user_id INT NOT NULL,
                rank INT NOT NULL,
                PRIMARY KEY (user_id)
            ) ENGINE=MEMORY
        ');

        // 一時テーブルにデータを入れていく。"point" 降順とはいえ、同点同位の可能性があるので、留意する。
        $inserter = new BulkInserter('ranking_t', $dao);
        $curRank = 0;   $curPoint = 0x7FFFFFFF;   $index = 1;
        foreach($orders as $order) {

            if($order['point'] < $curPoint) {
                $curRank = $index;
                $curPoint = $order['point'];
            }

            $inserter->insert($order['user_id'], $curRank);
            $index++;
        }
        $inserter->flush();

        // 一時テーブルとJOINする形で一括UPDATEを行う。
        $sql = '
            UPDATE ranking_log
                   INNER JOIN ranking_t USING (user_id)
            SET ranking_log.rank = ranking_t.rank
            WHERE ranking_log.type = ?
              AND ranking_log.period = ?
        ';
        $dao->execute($sql, array($type, $period));

        // 一時テーブルを削除。切断すれば勝手に削除されるけど、後続の処理のために一応。
        $dao->execute('DROP TABLE ranking_t');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種別、期間のレコードを対象として、褒賞付与を行う。
     *
     * @param int   種別を表す数値。このクラスの定数を使用する。
     * @param int   期間を表す数値。
     */
    public function awardPrize($type, $period) {

        // ランキング種別に応じた褒賞アイテムの設定を取得。設定されていない場合は何もしない。
        $prizes = self::$PRIZES[$type];
        if(!$prizes)
            return;

        // 指定のランキングの上位100位を取得。
        $sql = '
            SELECT user_id, rank
            FROM ranking_log
            WHERE type = ?
              AND period = ?
            ORDER BY rank
            LIMIT 100
        ';
        $orders = $this->createDao(true)->getAll($sql, array($type, $period));

        // 上位から順に見ていく。
        foreach($orders as $order) {

            // 順位に応じた褒賞アイテムを取得。褒賞のない順位まで下ったら処理をやめる。
            $prize = $prizes[ $order['rank'] ];
            if(!$prize)
                break;

            // 褒賞付与。
            Service::create('User_Item')->gainItem($order['user_id'], $prize["id"], $prize["count"]);

            // 週間ランキングの場合は履歴に格納。
            if($type == self::GRADEPT_WEEKLY) {
                Service::create('History_Log')->insertRecord(array(
                    'user_id' => $order['user_id'],
                    'type' => History_LogService::TYPE_WEEKLY_HIGHER,
                    'ref1_value' => $order['rank'],
                    'ref2_value' => $prize["id"],
                ));
            }

            //キャンペーン期間中なら・・
            if(strtotime(BTC_CAMPAIGN_START_DATE) <= strtotime(Common::getCurrentTime()) && strtotime(BTC_CAMPAIGN_END_DATE) > strtotime(Common::getCurrentTime())){
                //ビットコインがあるなら付与
                if($prize["btc"] > 0){
                    $flag_id = date("ymd") . sprintf('%04d', $order['rank']);
                    Service::create('User_Info')->setVirtualCoin($order['user_id'], Vcoin_Flag_LogService::BATTLE_RANKING, $prize["btc"], $flag_id);
Common::varLog("バトルランキング BTC付与 user_id=" . $order['user_id'] . " flag_id=" . $flag_id . " prize=" . $prize["btc"]);
                }
            }

            // メッセージ送信
            $title = sprintf('[%s]上位入賞!', SITE_SHORT_NAME);
            PlatformApi::sendMessage($order['user_id'], 'ﾊﾞﾄﾙｲﾍﾞﾝﾄで上位入賞!賞品をｹﾞｯﾄしたよ!', $title);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種別、期間のレコードを参照して、ユーザごとの過去最高位の更新を行う。
     *
     * @param int   種別を表す数値。このクラスの定数を使用する。
     * @param int   期間を表す数値。
     */
    public function updateHighestRank($type, $period) {

        // ついでに古すぎるランキングデータを削除する。
        $this->deleteOldRanking($type);

        $dao = $this->createDao();

        // ユーザIDとその順位を保持する一時テーブルを作成。
        $dao->execute('
            CREATE TEMPORARY TABLE ranking_t (
                user_id INT NOT NULL,
                rank INT NOT NULL
            ) ENGINE=MEMORY
        ');

        // 今回の順位が過去最高位よりも高いユーザのIDと順位を一時テーブルへ。
        $sql = '
            INSERT INTO ranking_t
            SELECT this_rank.user_id
                 , this_rank.rank
            FROM (
                    SELECT user_id, rank
                    FROM ranking_log
                    WHERE type = ?
                      AND period = ?
                 ) AS this_rank
                 LEFT OUTER JOIN (
                    SELECT user_id, rank
                    FROM ranking_log
                    WHERE type = 0
                      AND period = ?
                 ) AS highest_rank USING (user_id)
            WHERE this_rank.rank < IFNULL(highest_rank.rank, 2147483647)
        ';
        $dao->execute($sql, array($type, $period, $type));

        // 一時テーブルに格納されている最高位更新データを反映させる。
        $sql = '
            REPLACE INTO ranking_log
            SELECT 0, ?, user_id, 0, rank
            FROM ranking_t
        ';
        $dao->execute($sql, $type);

        // 一時テーブルを削除。切断すれば勝手に削除されるけど、後続の処理のために一応。
        $dao->execute('DROP TABLE ranking_t');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種別の古すぎるランキングデータを削除する。
     *
     * @param int   種別を表す数値。このクラスの定数を使用する。
     */
    public function deleteOldRanking($type) {

        // 10回より古いランキングが削除されるようにする。

        $threshold = -10 * $this->getIntervalDays($type);
        $threshold = date('Ymd', strtotime($threshold.' day'));

        $this->createDao()->delete(array(
            'type' => $type,
            'period' => array('sql'=>'<= ?', 'value'=>$threshold),
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('type', 'period', 'user_id');


    // private メンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された種別のランキングの集計期間を日数で返す。
     *
     * @param int       種別を表す数値。このクラスの定数を使用する。
     * @return int      集計期間の日数
     */
    private function getIntervalDays($type) {

        if($type == 0)
            throw new MojaviException('過去最高位のデータの期間を取得しようとした');

        return ($type % 10 == 1) ? 1 : 7;
    }
}
