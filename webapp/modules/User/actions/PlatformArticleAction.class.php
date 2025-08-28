<?php

/**
 * プラットフォームのユーザ投稿(「GREEひとこと」など)を投稿した後の戻り画面。
 */
class PlatformArticleAction extends UserBaseAction {

    public function execute() {

        // ミクシィはキャンセルしてもココにくるのでチェックしておく
        if(PLATFORM_TYPE == 'mixi'  &&  $_GET["result"] == "false")
            Common::redirect('User', 'Main');

        // 投稿して戻ってきているなら。
        if($_GET['done']) {

            // 簡単ながら、タイムスタンプでバリデーションを行う。
            if($_GET['done'] + 24*60*60 < time())
                throw new MojaviException('タイムスタンプが古すぎる');

            // 行動pt回復処理。
            $code = $this->recoveryAp();

            // 結果画面に遷移。
            Common::redirect('User', 'PlatformArticle', array('result'=>$code));
        }

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * インセンティブ付与を処理して、処理コードを返す。
     */
    private function recoveryAp() {

        $incSvc = new Incentive_LogService();

        // 最近付与している回数を数える。制限に達しているならその旨のリターン。
        $hotCount = $incSvc->getHotCount($this->user_id, Incentive_LogService::ARTICLE);
        if($hotCount >= 1)
            return 'day_limit';

        // 行動ptがすでに全快ならその旨のリターン。
        if($this->userInfo['action_pt'] >= ACTION_PT_MAX)
            return 'ap_full';

        // 行動pt回復。
        $userSvc = new User_InfoService();
        $userSvc->plusValue($this->user_id, array('action_pt'=>ARTICLE_AP));
        $this->reloadUser();

        // インセンティブ付与を記録する。
        $incSvc->logIncentive($this->user_id, Incentive_LogService::ARTICLE);

        // それを表すコードをリターン。
        return 'ap_recov';
    }
}
