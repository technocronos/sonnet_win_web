<?php

/**
 * 水汲みクエストの特殊処理を記述する
 */
class SphereOn11002 extends SphereCommon {

    // 初めてクリアした後は「師匠への報告」を連続実行させる。
    protected $nextQuestId = 11003;

    // ミッション達成までの...
    const MISSION_TURNS = 7;        // ターン数
    const MISSION_ENEMIES = 6;      // 撃破数

    // ミッション達成時の報酬金。
    protected $missionReward = 100;



    //-----------------------------------------------------------------------------------------------------
    /**
     * doBattleEnd() をオーバーライド。
     * ギミック "after_fight" を起動する。
     */
    public function doBattleEnd($battle) {

        parent::doBattleEnd($battle);

        $this->pushStateEvent(array(
            'type' => 'gimmick',
            'name' => 'after_fight',
            'trigger' => $this->getUnit('avatar'),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmick() をオーバーライド。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {

        // "goal_drama" の場合に、2番目の指揮を条件によって差し替える。
        if($gimmick['name'] == 'goal_drama') {
            $gimmick['leads'][1] =
                ($this->state['try_count'] == 1) ?
                "SPEAK %avatar% " . AppUtil::getText("sphere_11002_11002000_goal_drama_select1") :
                "SPEAK %avatar% "  . AppUtil::getText("sphere_11002_11002000_goal_drama_select2") 
            ;

            //未クリアの場合
            if(!$this->state['cleared']){
                if(strtotime(STARTDUSH_CAMPAIGN_START_DATE) <= strtotime($this->getCurrentTime()) && strtotime(STARTDUSH_CAMPAIGN_END_DATE) > strtotime($this->getCurrentTime())){
                    $svc = new User_ItemService();
                    $uitemId = $svc->gainItem($this->info['user_id'], STARTDUSH_CAMPAIGN_GET_ITEM, STARTDUSH_CAMPAIGN_GET_AMOUNT);

                    $gimmick['leads'] = array_merge($gimmick['leads'], AppUtil::getTexts("sphere_11002_11002000_goal_drama_add"));
                }
            }

        }

        // "last_enemy_explain" の場合に、"enemy6" が既に起動しているなら無効にする。
        if($gimmick['name'] == 'last_enemy_explain') {
            if( !$this->state['gimmicks']["enemy6"] )
                $gimmick['type'] = 'disabled';
        }

        // あとは、通常通り処理。
        return parent::fireGimmick($leads, $gimmick, $unit);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * closeGimmick() をオーバーライド。
     */
    protected function closeGimmick(&$leads, &$gimmick, $unit) {

        // "enemy1" か "enemy2" の敵出現の場合に、"find1" か "find2" のギミックが連動して起動するようにする。
        if($gimmick['name'] == 'enemy1'  ||  $gimmick['name'] == 'enemy2') {
            $first = array_key_exists('enemy1', $this->state['gimmicks'])  &&  array_key_exists('enemy2', $this->state['gimmicks']);
            $gimmick['chain'] = $first ? 'find1' : 'find2';
        }

        return parent::closeGimmick($leads, $gimmick, $unit);
    }

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

        //未クリアでにじよめの場合
        if(!$this->state['cleared']){
            if(strtotime(STARTDUSH_CAMPAIGN_START_DATE) <= strtotime($this->getCurrentTime()) && strtotime(STARTDUSH_CAMPAIGN_END_DATE) > strtotime($this->getCurrentTime())){
                //スタートダッシュキャンペーン中なら
                $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_11002_11002000_startdush")));
                //$leads[] = 'NOTIF ' . STARTDUSH_CAMPAIGN_END_DATE . 'まで！';
            }
        }

        // 初トライであれば、くすりびん２つプレゼントを処理する。
        if($this->state['try_count'] == 1) {

            $avatar = $this->getUnitByCode('avatar');

            $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_11002_11002000_start1")));

            for($i = 0 ; $i < 2 ; $i++)
                $leads = array_merge($leads, $this->gainTreasure(1001, $avatar, true));

            $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_11002_11002000_start2")));

        // 未クリアで...
        }else if(!$this->state['cleared']) {

            // くすりびんの合計所持量が２つもないなら、救済ギミックを起動。
            $count = Service::create('User_Item')->getHoldCount($this->info['user_id'], 1001);
            if($count < 2)  {
                $this->pushStateEvent(array(
                    'type' => 'gimmick',
                    'name' => 'relief_speak',
                    'trigger' => $this->getUnitByCode('avatar')->getNo(),
                ));
            }
        }

        return parent::progressRoomOpen($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * progressPreComm() をオーバーライド。
     * 未クリアで、主人公ユニットのとき、HPが規定以下だったら警告する。
     */
    protected function progressPreComm(&$leads) {

        if(!$this->state['cleared']  &&  $this->getUnit()->getCode() == 'avatar'  &&  $this->getUnit()->getProperty('hp') <= 70) {
            $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_11002_11002000_notice")));
        }

        return parent::progressPreComm($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * progressAfterComm() をオーバーライド。
     * 4ターン目のプレイヤーユニット行動後、ギミック "enemy2" を強制起動する。
     */
    protected function progressAfterComm(&$leads) {

        if($this->state['rotation'] == 4  &&  $this->getUnit()->getCode() == 'avatar') {
            $this->pushStateEvent(array(
                'type' => 'gimmick',
                'name' => 'enemy2',
                'trigger' => $this->getUnit()->getNo(),
            ));
        }

        return parent::progressAfterComm($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * rotateNext() をオーバーライド。
     */
    protected function rotateNext(&$leads) {

        // サブミッション達成までの残を表示する。
        if($this->state['mission_exists']  &&  $this->state['rotation'] < self::MISSION_TURNS) {

            // 残りゴブリンとターン数を取得。基底の処理前であることに留意。
            $remainTurns = self::MISSION_TURNS - $this->state['rotation'];
            $remainEnemies = self::MISSION_ENEMIES - $this->state['termination'];

            // 最初のターンは表示しない。それ以外であれば...
            if($remainTurns < self::MISSION_TURNS) {

                $enemyText = ($remainEnemies > 0) ? AppUtil::getText("sphere_11002_11002000_missionmsg1", false, "[remainEnemies]", $remainEnemies) : AppUtil::getText("sphere_11002_11002000_missionmsg2");
                $turnText = ($remainTurns > 1) ? AppUtil::getText("sphere_11002_11002000_missionmsg3", false, "[remainTurns]", $remainTurns) : AppUtil::getText("sphere_11002_11002000_missionmsg4");

                $leads[] = "NOTIF {$enemyText}\n{$turnText}";
            }
        }

        return parent::rotateNext($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * checkAchievement()をオーバーライド
     */
    protected function checkAchievement($resultCode) {

        return $resultCode == Sphere_InfoService::SUCCESS
            && $this->state['rotation'] <= self::MISSION_TURNS
            && $this->state['termination'] >= self::MISSION_ENEMIES;
    }
}
