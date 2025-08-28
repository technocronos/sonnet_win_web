<?php

class TutorialAction extends AdminBaseAction {

    // 棒グラフのスケール。
    const GRAPH_SCALE = 5000;


    public function execute() {

        // チュートリアルステップの表示の仕方。
        $TUTORIAL_NAMES = array(
            User_InfoService::TUTORIAL_MORNING =>     '名前入力後～最初の師匠の会話',
            User_InfoService::TUTORIAL_MAINMENU =>    '最初の師匠の会話終了～精霊の洞窟進入前',
            User_InfoService::TUTORIAL_FIELD =>       '精霊の洞窟進入～終了',
            User_InfoService::TUTORIAL_BATTLE =>      '精霊の洞窟終了～チュートバトル終了',
            User_InfoService::TUTORIAL_AFTERBATTLE => 'チュートバトル終了～終了後寸劇',
            User_InfoService::TUTORIAL_STATUS =>      'ｽﾃｰﾀｽ案内',
            User_InfoService::TUTORIAL_PRESHOP =>     'ｽﾃｰﾀｽ案内終了～小遣いせびり終了',
            User_InfoService::TUTORIAL_SHOPPING =>    'ショップ案内',
            User_InfoService::TUTORIAL_GACHA =>       'ガチャ案内',
            User_InfoService::TUTORIAL_RIVAL =>       '対戦案内',
            User_InfoService::TUTORIAL_LAST =>        '水汲み依頼',
            User_InfoService::TUTORIAL_END =>         'チュートリアル一次終了',
            User_InfoService::TUTORIAL_MOVE =>        '初めての移動',
            User_InfoService::TUTORIAL_GLOBALMOVE =>  '初めての移動終了～グローバルマップ',
            User_InfoService::TUTORIAL_FINISH =>      'グローバルマップ表示済み',
        );

        // 集計
        $sumup = Service::create('User_Info')->sumupTutorialStep();

        // チュートリアルステップの値を名前に差し替える。
        foreach($sumup as &$record) {
            $record['tutorial_step'] = sprintf('%s(%d)', $TUTORIAL_NAMES[ $record['tutorial_step'] ], $record['tutorial_step']);
        }unset($record);

        // ビュー変数にセット
        $this->setAttribute("list" , $sumup);
        $this->setAttribute("colCaptions" , array('tutorial_step'=>'チュートリアル', 'count'=>'総人数', 'secession'=>'放置数', 'retire'=>'ｱﾝｲﾝｽﾄｰﾙ'));
        $this->setAttribute("colWidth", array('count'=>'7em', 'secession'=>'7em', 'retire'=>'7em'));
        $this->setAttribute("colTypes" , array(
            'count' => array('graph'=>self::GRAPH_SCALE),
            'secession' => array('graph'=>self::GRAPH_SCALE),
            'retire' => array('graph'=>self::GRAPH_SCALE),
        ));

        return View::SUCCESS;
    }
}
