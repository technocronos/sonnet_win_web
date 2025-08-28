<?php

class IndexAction extends UserBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;

    const LONG_ABSENCE_DAYS = 10;

    //=====================================================================================================
    public function execute() {


        if(Common::isTablet() != "tablet")
            $this->img_list["titlelogo"] = "img/parts/titlelogo.native.jpg";
        else
            $this->img_list["titlelogo"] = "img/parts/titlelogo.tablet.jpg";


        $this->setAttribute('img_list', $this->img_list);

        if((Common::getCarrier() == "android" && ANDROID_VER > $_GET["ver"]) || (Common::getCarrier() == "iphone" && IOS_VER > $_GET["ver"])){
            $this->setAttribute('is_newest_version', false);
        }else{
            $this->setAttribute('is_newest_version', true);
        }

        // mixiの申請前チェックシートで、「非対応キャリアへの表示はありますか？」とワザワザ念押しされている
        // ので、willcomの場合は非対応表示を出す。
        if( PLATFORM_TYPE == 'mixi'  &&  preg_match("/WILLCOM/i", $_SERVER['HTTP_USER_AGENT']) )
            Common::redirect('User', 'Static', array('id'=>'non-compliant'));

        //androidはchromeかfirefoxかSafariのみ対応
    		if(Common::getCarrier() == "android" && !preg_match("/Chrome|Firefox|Safari/", $_SERVER['HTTP_USER_AGENT']) )
                Common::redirect('User', 'Static', array('id'=>'non-compliant_browser'));

    		//iphoneは5以上対応
		
        // すでに登録している場合に、前回アクセスからの経過日数が規定数以上である場合は特典付与する。
        // 制御は戻ってこない。
        //if($this->userInfo  &&  $this->userInfo['absence_days'] > self::LONG_ABSENCE_DAYS)
        //    $this->processLongAbsence();

        // 「ゲーム開始」のリンク先のアクションを決定。
        $this->decideStartAction();

        // ログインボーナスの付与。
        if($this->userInfo) {
            //$loginBonusResult = AppUtil::gainLoginBonus($this->user_id);
            //$this->setAttribute('loginBonus', $loginBonusResult['bonus']);
        }

        // 最も新しいお知らせを数件取得。
        //$oshiraseSvc = new Oshirase_LogService();
        //$this->setAttribute('oshiraseList', $oshiraseSvc->getNewestEntries());

        // 開発日誌の最終日時を取得。
        //$newestDiary = $oshiraseSvc->getNewestEntries('diary', 1);
        //$this->setAttribute('diaryUpData', $newestDiary ? $newestDiary[0]['notify_at'] : 0);

        // 最高レベルを取得。
        //$maxLevel = Service::create('Level_Master')->getMaxLevel('PLA');
        //$this->setAttribute('maxLevel', $maxLevel);

        // バトルランキングに関する情報を取得。
        //$rankSvc = new Ranking_LogService();
        //$this->setAttribute('term', $rankSvc->getRankingTerm(Ranking_LogService::GRADEPT_WEEKLY));
        //$this->setAttribute('prev', $rankSvc->getRankingTerm(Ranking_LogService::GRADEPT_WEEKLY, 'prev'));
        //$this->setAttribute('cycle', $rankSvc->getRankingCycle());

        //にじよめの場合でスタートダッシュキャンペーン中なら
        $dt = new DateTime();
        $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        $currenttime = $dt->format('Y-m-d H:i:s');

        $this->setAttribute('titlelogo', (Common::isTablet() != "tablet" ? "titlelogo.native.jpg" : "titlelogo.tablet.jpg"));

        // ユーザ登録している場合に...
        if($this->userInfo) {

            $histSvc = new History_LogService();

            // 最も適しているガチャを取得。
            $fitGacha = Service::create('Gacha_Master')->getFitGacha($this->user_id);
            $this->setAttribute('fitGacha', $fitGacha);

            // 未読メッセージ件数を取得。
            $this->setAttribute('unreadCount', Service::create('Message_Log')->getUnreadCount($this->user_id));

            // 未回答の被申請数、未確認の回答数を取得。
            $invSvc = new Approach_LogService();
            $unansweredCount = $invSvc->getUnansweredCount($this->user_id);
            $this->setAttribute('unanswerCount', $unansweredCount['receive']);
            $this->setAttribute('unconfirmCount', $invSvc->getUnconfirmedCount($this->user_id));

            // プレゼント履歴、招待成功履歴、履歴称賛、履歴レスのうち未チェックのものを取得。
            $attentions = $histSvc->checkHistory($this->user_id, array(
                History_LogService::TYPE_PRESENTED, History_LogService::TYPE_INVITE_SUCCESS,
                History_LogService::TYPE_ADMIRED, History_LogService::TYPE_REPLIED
            ));
            $this->setAttribute('attentions',$attentions);

            // 無料ガチャをまわせるかどうかを取得。
            $tryable = (int)date('Ymd') > Service::create('User_Property')->getProperty($this->user_id, 'free_gacha_date');
            $this->setAttribute('freeGacha', $tryable);

      			//曜日クエ内容
      			$reword = FieldBattle99999Util::getRewordDay();
                  $this->setAttribute('week_quest_str', $reword['str']);

            if($this->user_id == TEST_USER)
                $this->setAttribute('testaccess',true);

        }else{

            if(!isset($_REQUEST["opensocial_owner_id"]) || $_REQUEST["opensocial_owner_id"] == ""){
                $this->setAttribute('session_expire',true);
            }
        }

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 規定日数以上の期間を置いてアクセスした場合の処理。
     */
    private function processLongAbsence() {

        $userSvc = new User_InfoService();
        $uitemSvc = new User_ItemService();

        // 特典付与
        foreach(AppUtil::$ABSENCE_BONUS as $bonus) {
            if($bonus['gold'])  $userSvc->plusValue($this->user_id, array('gold'=>$bonus['gold']));
            if($bonus['item'])  $uitemSvc->gainItem($this->user_id, $bonus['item']);
        }

        Service::create('User_Property')->updateProperty($this->user_id, 'absence_bonus_date', date('Ymd'));

        // 結果画面にリダイレクト
        Common::redirect('Swf', 'Detain');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 「ゲーム開始」のリンク先のアクションをビュー用変数にセットする。
     */
    private function decideStartAction() {

        $charaSvc = new Character_InfoService();

        // まだ未登録ならオープニング。
        if(!$this->userInfo) {
            $this->setAttribute('startAction', Common::genContainerURL('Swf', 'Prologue'));

        // エラー等で、ユーザレコードは出来てるのに、キャラクターレコードが出来てない場合。
        }else if( !$charaSvc->getAvatarId($this->userInfo['user_id']) ) {
            $this->setAttribute('startAction', Common::genContainerURL('User', 'AvatarCreate'));

        // まだチュートリアル中なら...
        }else if($this->userInfo['tutorial_step'] < User_InfoService::TUTORIAL_END) {
            $this->setAttribute('startAction', Common::genContainerURL('Swf', 'Tutorial'));

        // いずれでもないならメイン。
        }else {
            $this->setAttribute('startAction', Common::genContainerURL('Swf', 'Main'));
        }
    }
}
