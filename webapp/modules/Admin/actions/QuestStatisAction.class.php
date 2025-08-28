<?php

class QuestStatisAction extends AdminBaseAction {

    // 最終アクセスから何日が経過したら退去ユーザと判断するか。
    const LIVING_DAYS = 4;

    // 棒グラフのスケール。
    const PERSON_SCALE = 15000;
    const COUNT_SCALE = 20000;


    public function execute() {

        // フォームの送信ボタンが押されているなら集計する。
        if($_GET['go'])
            $this->sumup();

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * クエストの集計処理を行う。
     */
    private function sumup() {

        $flagSvc = new Flag_LogService();

        // クエストをすべて取得。
        $quests = Service::create('Quest_Master')->getAllRecords();

        //
        $clear = $flagSvc->sumupFlagGroup(Flag_LogService::CLEAR);
        $clear = ResultsetUtil::keyShift($clear, 'flag_id');
        $try = $flagSvc->sumupFlagGroup(Flag_LogService::TRY_COUNT);
        $try = ResultsetUtil::keyShift($try, 'flag_id');
        $mission = $flagSvc->sumupFlagGroup(Flag_LogService::MISSION);
        $mission = ResultsetUtil::keyShift($mission, 'flag_id');

        // テーブルデータを作成する。
        $table = array();
        $rowBgColor = array();
        foreach($quests as $quest) {

            $questId = $quest['quest_id'];

            $table[] = array(
                'quest_name' => $quest['quest_name'],
                'try_person' => $try[$questId]['count'],
                'clear_person' => $clear[$questId]['count'],
                'mission_clear' => $mission[$questId]['count'],
                'clear_rate_p' => ($try[$questId]['count'] > 0) ? $clear[$questId]['count'] / $try[$questId]['count'] * 100 : 0,
                'mission_rate' => ($try[$questId]['count'] > 0) ? $mission[$questId]['count'] / $try[$questId]['count'] * 100 : 0,
                'try_count' => $try[$questId]['sum'],
                'clear_count' => $clear[$questId]['sum'],
                'clear_rate' => ($try[$questId]['sum'] > 0) ? $clear[$questId]['sum'] / $try[$questId]['sum'] * 100 : 0,
                'try_rate' => ($try[$questId]['count'] > 0) ? $try[$questId]['sum'] / $try[$questId]['count'] : 0,
            );

            if($quest['type'] == 'FLD')
                $rowBgColor[count($table) - 1] = 'lavender';
        }

        // ビューに割り当てる。
        $this->setAttribute('table', $table);
        $this->setAttribute('rowBgColor', $rowBgColor);
        $this->setAttribute("colCaptions", array(
            'quest_name' => '',
            'try_person' => '挑戦人数',
            'clear_person' => 'クリア人数',
            'mission_clear' => 'ﾐｯｼｮﾝ達成人数',
            'clear_rate_p' => 'クリア割合',
            'mission_rate' => 'ﾐｯｼｮﾝ達成割合',
            'try_count' => '挑戦回数',
            'clear_count' => 'クリア回数',
            'clear_rate' => 'クリア率',
            'try_rate' => '挑戦回数/人',
        ));
        $this->setAttribute("colWidth", array(
            'try_person' => '14ex',
            'clear_person' => '14ex',
            'mission_clear' => '14ex',
            'clear_rate_p' => '14ex',
            'mission_rate' => '14ex',
            'try_count' => '14ex',
            'clear_count' => '14ex',
            'clear_rate' => '14ex',
            'try_rate' => '14ex',
        ));
        $this->setAttribute("colTypes", array(
            'try_person' => array('graph'=>self::PERSON_SCALE),
            'clear_person' => array('graph'=>self::PERSON_SCALE),
            'mission_clear' => array('graph'=>self::PERSON_SCALE),
            'clear_rate_p' => array('graph'=>100.0, 'format'=>'%0.2f'),
            'mission_rate' => array('graph'=>100.0, 'format'=>'%0.2f'),
            'try_count' => array('graph'=>self::COUNT_SCALE),
            'clear_count' => array('graph'=>self::COUNT_SCALE),
            'clear_rate' => array('graph'=>100.0, 'format'=>'%0.2f'),
            'try_rate' => array('graph'=>10, 'format'=>'%0.2f'),
        ));
    }
}
