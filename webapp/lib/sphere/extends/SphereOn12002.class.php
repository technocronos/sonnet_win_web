<?php

/**
 * 西の森の洞窟(前庭)の特殊処理を記述する
 */
class SphereOn12002 extends SphereCommon {

    // 初めてクリアした後は「そこは牧場」を連続実行させる。
    protected $nextQuestId = 13001;

    // ミッション達成までの...
    const MISSION_ITEMS = 3;        // アイテム数

    // ミッション達成時の報酬金。
    protected $missionReward = 200;

    /**
     * ----------------------------------------------------------
     * getCurrentTime()
     * 現在時間を取得する
     * ----------------------------------------------------------
     */
    function getCurrentTime() {
      $dt = new DateTime();
      $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
     
      return $dt->format('Y-m-d H:i:s');
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressRoomOpen() をオーバーライド。
     */
    protected function progressRoomOpen(&$leads) {

        //未クリアでワクプラの場合
        if(!$this->state['cleared'] && PLATFORM_TYPE == 'waku'){
            //スタートダッシュキャンペーン中なら
            if(strtotime(STARTDUSH_CAMPAIGN_START_DATE) <= strtotime($this->getCurrentTime()) && strtotime(STARTDUSH_CAMPAIGN_END_DATE) > strtotime($this->getCurrentTime())){
                // ダンジョン突入時のみ表示を行う。
                if($this->state['rotation_all'] == 0) {

	                //スタートダッシュキャンペーン中なら
					if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
	        	        $leads[] = 'NOTIF スタートダッシュキャンペーン中！';
	            	    $leads[] = 'NOTIF このクエをクリアで10Sプレゼント！';
	                	$leads[] = 'NOTIF ' . STARTDUSH_CAMPAIGN_END_DATE . 'まで！';
					}else{
	        	        $leads[] = 'NOTIF ｽﾀｰﾄﾀﾞｯｼｭｷｬﾝﾍﾟｰﾝ中！';
	            	    $leads[] = 'NOTIF このｸｴをｸﾘｱで10Sﾌﾟﾚｾﾞﾝﾄ！';
	                	$leads[] = 'NOTIF ' . STARTDUSH_CAMPAIGN_END_DATE . 'まで！';
					}
                }
            }
        }

        return parent::progressRoomOpen($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processItem()をオーバーライド。
     */
    protected function processItem(&$leads, $use) {

        $ret = parent::processItem($leads, $use);

        $unit = $this->getUnit();

        // 敵ユニットが攻撃アイテムを使ったのなら、ギミック "throw_surprise" を起動する。
        if($unit->getUnion() == 2  &&  $use['uitem']['item_type'] == Item_MasterService::TACT_ATT) {
            $this->pushStateEvent(array(
                'type' => 'gimmick',
                'name' => 'throw_surprise',
                'trigger' => $unit->getNo(),
            ));
        }

        return $ret;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * closeGimmick()をオーバーライド。
     */
    protected function closeGimmick(&$leads, &$gimmick, $unit) {

        // 基底の処理を先にしてないと、ミッションアイテムのカウントが狂うことに留意。
        $ret = parent::closeGimmick($leads, $gimmick, $unit);

        // ミッションアイテム取得ギミックの場合。
        if($gimmick['type'] == 'x_mission') {

            // 残りを数える。
            $remain = self::MISSION_ITEMS - $this->getMissionItems();

            // 残り表示。
            if($remain > 0)
                $leads[] = sprintf(AppUtil::getText("sphere_12002_mission1"), $remain);
            else
                $leads[] = AppUtil::getText("sphere_12002_mission2");
        }

        return $ret;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * checkAchievement()をオーバーライド
     */
    protected function checkAchievement($resultCode) {

        return $this->getMissionItems() >= self::MISSION_ITEMS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ミッション対象のアイテムを何個取得しているかを返す。
     */
    private function getMissionItems() {

        // クエストメモリに、"mission_item" で始まるエントリがいくつあるかを数えて返す。
        $result = 0;
        foreach($this->state['memory'] as $name => $dummy) {
            if(strpos($name, 'mission_item') === 0)
                $result++;
        }

        return $result;
    }
}
