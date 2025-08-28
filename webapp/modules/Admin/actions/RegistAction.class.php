<?php

class RegistAction extends AdminBaseAction {

    // 最終アクセスから何日が経過したら退去ユーザと判断するか。
    const LIVING_DAYS = 4;

    // 棒グラフのスケール。
    const REGISTER_SCALE = 1000;
    const INVITE_SCALE = 100;
    const OTHER_SCALE = 600;


    public function execute() {

        // ユーザ数の合計と残存率を取得。
        $sumup = Service::create('User_Info')->sumupTotals(self::LIVING_DAYS);
        $this->setAttribute('sumup', $sumup);

        // デフォルト値の設定。
        if(strlen($_GET['from']) == 0  &&  strlen($_GET['to']) == 0) {
            $_GET['from'] = date('Y/m/d', strtotime('-1month'));
            $_GET['to'] = '';
        }

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'from' => 'datetime',
            'to' => array('ifempty'=>date('Y/m/d'), 'dateend'),
            '_form' => array(
                array('lowerupper' => array('from', 'to')),
                'interval' => array('dateinterval' => array('from', 'to', '1000day')),
            ),
        ));
        $this->setAttribute('validator', $validator);

        // 入力値の検査。
        $validator->validate($_GET);

        // エラーがあるならココまで。
        if($validator->isError())
            return View::SUCCESS;

        // fromが省略されている場合はtoの2ヶ月前とする。
        if(!$validator->values['from']) $validator->values['from'] = DateTimeUtil::add('-2month', $validator->values['to'], 'Y/m/d H:i:s');

        // 指定された期間の日別登録数を集計する。
        $this->sumup($validator->values['from'], $validator->values['to']);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された期間の日別登録数を集計する。
     *
     * @param string    期間開始の日時
     * @param string    期間終了の日時
     */
    private function sumup($from, $to) {

        $userSvc = new User_InfoService();

        // 範囲内の日付をすべて抽出。
        $toTime = strtotime($to);
        $fromTime = strtotime($from);
        $rows = array();
        for($time = $toTime - 1 ; $time >= $fromTime ; $time = strtotime('-1day', $time))
            $rows[] = date('Y-m-d', $time);

        // 指定期間の日別登録数を取得。
        $regarray = $userSvc->sumupRegistration($from, $to);

        $registrations = ResultsetUtil::colValues($regarray, 'count', 'date');
        $registrations_ios = ResultsetUtil::colValues($regarray, 'ios_count', 'date');
        $registrations_android = ResultsetUtil::colValues($regarray, 'android_count', 'date');

        // 指定期間の日別最終アクセス数を取得。
        $secessions = $userSvc->sumupEarlySecession($from, $to);

        // 指定期間の日別最終アクセス数を取得。
        $accesses = $userSvc->sumupLastAccess($from, $to);

        // 指定期間のアンインストール数を取得。
        $retires = $userSvc->sumupRetire($from, $to);

        // 指定期間の日別招待数を取得。
        $invitations = Service::create('Invitation_Log')->sumupInvitation($from, $to);

        // 日付をキー、日別の数をレコードとする結果セットに直す。
        $table = array();
        foreach($rows as $date) {
            $table[] = array(
                'date' => $date,
                'register_ios' => isset($registrations_ios[$date]) ? $registrations_ios[$date] : 0,
                'register_android' => isset($registrations_android[$date]) ? $registrations_android[$date] : 0,
                'register' => isset($registrations[$date]) ? $registrations[$date] : 0,
                'invite' => isset($invitations['create'][$date]) ? $invitations['create'][$date] : 0,
                'accept' => isset($invitations['accept'][$date]) ? $invitations['accept'][$date] : 0,
                'access' => isset($accesses[$date]) ? $accesses[$date] : 0,
                'secession' => isset($secessions[$date]) ? $secessions[$date] : 0,
                'retire' => isset($retires[$date]) ? $retires[$date] : 0,
            );
        }

        // ビューに割り当てる。
        $this->setAttribute('table', $table);
        $this->setAttribute("colCaptions", array('date'=>'', 'register_ios'=>'登録人数(iOS)', 'register_android'=>'登録人数(Android)', 'register'=>'登録人数(合計)', 'invite'=>'招待数', 'accept'=>'招待応諾数', 'access'=>'最終アクセス', 'secession'=>'早期離脱数', 'retire'=>'ｱﾝｲﾝｽﾄｰﾙ'));
        $this->setAttribute("colWidth", array('register'=>'7em', 'invite'=>'7em', 'accept'=>'7em', 'access'=>'7em', 'secession'=>'7em', 'retire'=>'7em'));
        $this->setAttribute("colTypes",
            array(
                'date'=>'date',
                'register'=>array('graph'=>self::REGISTER_SCALE),
                'invite'=>array('graph'=>self::INVITE_SCALE),
                'accept'=>array('graph'=>self::INVITE_SCALE),
                'access'=>array('graph'=>self::OTHER_SCALE),
                'secession'=>array('graph'=>self::OTHER_SCALE),
                'retire'=>array('graph'=>self::OTHER_SCALE),
            )
        );
    }
}
