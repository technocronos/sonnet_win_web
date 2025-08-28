<?php

/**
 * チーム対戦の特殊処理を記述する
 */
class SphereOn99998 extends SphereCommon {

    // 基底メソッドのオーバーライド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    protected function createState($roomName, $enterUnits, $reason) {

        // 指定されたルーム番号を使わずに、ユーザが選択したものを使う。
        parent::createState($this->state['initialize']['roomNo'], $enterUnits, $reason);

        // 敵リーダーを倒したときの終了ギミックを仕込む。
        $this->addGimmick('finish', array(
            'trigger'=>'unit_exit', 'unit_exit'=>'rival', 'type'=>'escape', 'escape_result'=>'success'
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    protected function initUnits($roomName, &$roomInfo, $enterUnits, $reason) {

        // 親のメソッドを経由せずに、完全にオーバーライドする。

        // 挑戦側リーダーユニットを配置。
        // 動的参戦ユニットの第0要素と断定している。将来的には問題になるかもしれないが…
        $unit = $enterUnits[0];
        $unit->setPos( $this->pickupPos($roomInfo['start_poses']['challenger']) );
        $this->addUnit($unit);

        // 挑戦側配下ユニットを配置。
        for($i = 1 ; $i <= 2 ; $i++)
            $this->summonFriend($this->state['initialize']['friends'][$i], 1, $this->pickupPos($roomInfo['start_poses']['challenger']));

        // 防衛側リーダーを作成。
        $rivalAvatarId = Service::create('Character_Info')->needAvatarId($this->state['initialize']['rival']);
        $unit = SphereUnit::makeUnitData($rivalAvatarId, array());
        $unit['pos'] = $this->pickupPos($roomInfo['start_poses']['defender']);
        $unit['code'] = 'rival';
        $unit['union'] = 2;
        $unit['icon'] = 'avatarE';
        $unit['player_owner'] = false;
        $unit['act_brain'] = 'keep';
        $unit['keep_pos'] = $unit['pos'];
        $this->addUnit( SphereUnit::load($unit, $this) );

        // 防衛側ユニットの出現場所をステートに格納しておく。
        $this->state['x_rival_poses'] = $roomInfo['start_poses']['defender'];
    }


    //-----------------------------------------------------------------------------------------------------
    protected function progressRoomOpen(&$leads) {

        parent::progressRoomOpen($leads);

        // 防衛側メンバを決定。ステートに格納しておく。
        $rivalFriends = $this->decideRivalMember();
        $this->state['x_rival_friends'] = $rivalFriends;

        // 防衛側配下ユニットを作成・配置。
        $i = 1;
        foreach($rivalFriends as $i => $friend) {

            // 出現場所を取得。
            $pos = $this->pickupPos($this->state['x_rival_poses']);

            // 出現場所にフォーカスを持ってくる。
            $leads[] = sprintf("PFOCS %02d %02d", $pos[0], $pos[1]);

            // ユニット登場。
            $enemy = $this->summonFriend($friend, 2, $pos, $leads);

            // メッセージ。
            $leads[] = sprintf("NOTIF %s Lv%d\nが現れました", $enemy->getProperty('name'), $enemy->getProperty('level'));
        }

        // 防衛側リーダーにフォーカスを合わせてメッセージ。
        $rival = $this->getUnitByCode('rival');
        $leads[] = sprintf('FOCUS %03d', $rival->getNo());
        $leads[] = sprintf("NOTIF \n%sを倒せ\n", $rival->getProperty('name'));

        // ここまで来たら、この情報はもう必要ない。
        unset($this->state['x_rival_poses']);

        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    protected function progressClose(&$leads, $resultCode) {

        $histSvc = new History_LogService();

        // 勝利して終了している場合は...
        if($resultCode == Sphere_InfoService::SUCCESS) {

            $charaSvc = new Character_InfoService();

            // 勝利時の階級ptを計算。
            $gradePt = $this->calcWinningPt();

            // 挑戦側メンバーのユーザID一覧を配列 $team に取得。
            $team = $this->state['initialize']['friends'];
            $team[] = $this->info['user_id'];

            // 一人ずつ、階級ptを付与していく。
            foreach($team as $member) {
                $avatarId = $charaSvc->needAvatarId($member);
                $charaSvc->gainGradePt($avatarId, $gradePt);
            }

            // ステートに階級ptを格納しておく。
            $this->state['x_winning_pt'] = $gradePt;

            // 表示する。
            $leads[] = sprintf("NOTIF 勝利ボーナス！\nチーム全員に階級pt+%d！", $gradePt);
        }

        // 挑戦側チームメンバと防衛側リーダーに履歴を挿入する。
        $histSvc->insertRecord(array(
            'user_id' => $this->info['user_id'],
            'type' => History_LogService::TYPE_TEAM_BATTLE,
            'ref1_value' => $this->info['sphere_id'],
            'ref2_value' => 1,
        ));

        $histSvc->insertRecord(array(
            'user_id' => $this->state['initialize']['rival'],
            'type' => History_LogService::TYPE_TEAM_BATTLE,
            'ref1_value' => $this->info['sphere_id'],
            'ref2_value' => 2,
        ));

        foreach($this->state['initialize']['friends'] as $friendId) {
            $histSvc->insertRecord(array(
                'user_id' => $friendId,
                'type' => History_LogService::TYPE_TEAM_BATTLE,
                'ref1_value' => $this->info['sphere_id'],
                'ref2_value' => 11,
            ));
        }

        // あとは基底のメソッドに任せる。
        return parent::progressClose($leads, $resultCode);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 次のキーを加える。
     *      x_allies  見方のユーザIDの配列。
     *      x_rivals  対戦相手のユーザIDの配列。
     */
    public function getSummary() {

        $summary = parent::getSummary();

        // "x_allies" を作成。
        $summary['x_allies'] = $this->state['initialize']['friends'];
        $summary['x_allies'][0] = $this->info['user_id'];

        // "x_rivals" を作成。
        $summary['x_rivals'] = $this->state['x_rival_friends'];
        array_unshift($summary['x_rivals'], $this->state['initialize']['rival']);

        return $summary;
    }


    // private メソッド。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザのアバターを召還する。
     *
     * @param int           ユーザID
     * @param int           所属。1 か 2
     * @param array         出現位置
     * @param reference     ユニット追加をSWFに伝えるための指揮コマンドの格納先。不要な場合は省略できる。
     * @param SphereUnit    召還したユニット。
     */
    private function summonFriend($friendId, $union, $pos, &$leads = array()) {

        // 指定されたユーザのアバタのIDを取得。
        $charaId = Service::create('Character_Info')->needAvatarId($friendId);

        // ユニットデータを作成。
        $props = SphereUnit::makeUnitData($charaId, array());
        $props['union'] = $union;
        $props['pos'] = $pos;
        $props['icon'] = ($union == 1) ? 'avatarF' : 'avatarS';
        $props['act_brain'] = 'generic';
        $props['player_owner'] = false;

        // SphereUnitとしてインスタンス化。
        $unit = SphereUnit::load($props, $this);
        $leads = array_merge($leads, $this->addUnit($unit));

        // リターン。
        return $unit;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された座標の配列からランダムに一つ取り出す。取り出された座標は配列から削除される。
     *
     * @param array         座標の配列
     * @return array        取り出した座標
     */
    private function pickupPos(&$poses) {

        $key = array_rand($poses);

        $value = $poses[$key];
        unset($poses[$key]);

        return $value;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 防衛側メンバを決定する。
     */
    private function decideRivalMember() {

        $charaSvc = new Character_InfoService();

        // 挑戦側、防衛側の合計Lvを取得。
        $challengersLevel = 0;
        $defendersLevel = 0;
        foreach($this->units as $unit) {
            if( $unit->getUnion() == 1 )
                $challengersLevel += $unit->getProperty('level');
            else
                $defendersLevel += $unit->getProperty('level');
        }

        // 仲間のユーザIDをすべて取得してシャッフル。
        $memberIds = Service::create('User_Member')->getMemberIds($this->state['initialize']['rival']);
        shuffle($memberIds);

        // 戻り値初期化。
        $result = array();

        // 最初の6人を取り出す。
        for($i = 0 ; $i < 6 ; $i++) {

            // 防衛側合計Lvが挑戦側合計Lvを上回ったら中止。
            if($challengersLevel <= $defendersLevel)
                break;

            // 途中で仲間が尽きたら中止。
            if(!isset($memberIds[$i]))
                break;

            // 戻り値として追加。
            $result[] = $memberIds[$i];

            // キャラのレベルを取得して、防衛側合計Lvに足しこむ。
            $chara = $charaSvc->needAvatar($memberIds[$i]);
            $defendersLevel += $chara['level'];
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 勝利時の階級ptを計算する。
     */
    private function calcWinningPt() {

        $charaSvc = new Character_InfoService();
        $gradeSvc = new Grade_MasterService();

        // 挑戦側と防衛側のユーザIDを配列で取得。
        $challengers = $this->state['initialize']['friends'];
        $challengers[] = $this->info['user_id'];

        $defenders = $this->state['x_rival_friends'];
        $defenders[] = $this->state['initialize']['rival'];

        // 挑戦側の平均階級序列を取得。
        $challengerGrade = 0;
        foreach($challengers as $userId) {
            $avatar = $charaSvc->needAvatar($userId);
            $challengerGrade += $gradeSvc->getGradeOrder($avatar['grade_id']);
        }
        $challengerGrade /= count($challengers);

        // 同じく防衛側。
        $defenderGrade = 0;
        foreach($defenders as $userId) {
            $avatar = $charaSvc->needAvatar($userId);
            $defenderGrade += $gradeSvc->getGradeOrder($avatar['grade_id']);
        }
        $defenderGrade /= count($defenders);

        // 防衛側の平均階級から挑戦側の平均階級を引いた値を求める。
        $gradeDef = $defenderGrade - $challengerGrade;

        // 防衛側のほうが階級が高い場合の計算式。
        if($gradeDef >= 0) {
            $pt = 10 + $gradeDef * 6;
            if($pt > 30) $pt = 30;

        // 挑戦側のほうが階級が高い場合の計算式。
        }else {
            $pt = 10 + $gradeDef * 3;   // $gradeDefはマイナスであることに注意。
            if($pt < 0) $pt = 1;
        }

        return $pt;
    }
}
