<?php

/**
 * フィールドスフィアに関する処理を収めるクラスの基底。
 */
class SphereCommon {

    // バトルを再開するときのペナルティpt
    const BATTLE_REMAKE_ACTPT = 10;

    // 主人公ユニットがアイテムをいくつまで持てるか。
    const HERO_ITEM_MAX = 8;

    // 一つのスフィアで表示できるユニットグラフィックの最大種類数
    const GRAPH_MAX_VARIETIES = 7;

    const TRANSCEND_LEVEL_MAX = 230;



    // インスタンス作成メソッド。静的メソッド。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのフィールドクエストの開始処理を行う。
     *
     * @param int       クエストID
     * @param int       クエストを実行するユーザのID
     * @param array     持っていくユーザ所持アイテムのID
     * @param mixed     特定のクエストの開始処理で渡したいパラメータがある場合はここに指定する。
     *                  このパラメータはそのまま startState メソッドに渡される。
     * @return object   SphereCommon、あるいはそこから派生したクラス。
     */
    public static function start($questId, $userId, $uitemIds, $initialize) {

        // このクラスのオブジェクトを作成して、スフィア情報を作成。初期値をセット
        $sphere = self::factory($questId);
        $sphere->info = array();
        $sphere->info['sphere_id'] = null;
        $sphere->info['quest_id'] = $questId;
        $sphere->info['state'] = null;
        $sphere->info['user_id'] = $userId;
        $sphere->info['revision'] = 0;
        $sphere->state = &$sphere->info['state'];

        // 指定されたユーザの主人公キャラを取得。
        $avatarId = Service::create('Character_Info')->needAvatarId($userId);

        // state の値をセット。
        $sphere->startState($avatarId, $uitemIds, $initialize);

        // スフィアレコードとして保存。
        $sphere->save();

        // リターン。
        return $sphere;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたスフィア情報で、このクラスオブジェクトを作成する。
     *
     * @param array          スフィアレコード、あるいはスフィアのID
     * @return SphereCommon  SphereCommon、あるいはそこから派生したクラス。
     */
    public static function load($record) {

        // スフィアがIDで指定されている場合はレコードを取得する。
        if( !is_array($record) )
            $record = Service::create('Sphere_Info')->needRecord($record);

        // クラスオブジェクトを作成。
        $sphere = self::factory($record['quest_id']);

        // 一部値は取り出しておく。
        $units = $record['state']['units'];
        $background = $record['state']['background'];
        $structure = $record['state']['structure'];
        $overlayer1 = $record['state']['overlayer1'];
        $overlayer2 = $record['state']['overlayer2'];
        $cover = $record['state']['cover'];
        $head = $record['state']['head'];
        $left = $record['state']['left'];
        $right = $record['state']['right'];
        $foot = $record['state']['foot'];
        $maptips = $record['state']['maptips'];
        $mats = $record['state']['mats'];
        $ornaments = $record['state']['ornaments'];
        unset($record['state']['units'], $record['state']['structure'], $record['state']['background'], $record['state']['overlayer1'], $record['state']['overlayer2'], $record['state']['cover'], $record['state']['head'], $record['state']['left'], $record['state']['right'], $record['state']['foot'], $record['state']['maptips'], $record['state']['mats'], $record['state']['ornaments']);

        // メンバに値をセット。
        $sphere->info = $record;
        $sphere->state = &$sphere->info['state'];

        // メンバ変数 map を復元。
        $sphere->map = SphereMap::factory($record['state']['map_class'], $sphere);
        $sphere->map->loadStructure($structure, $maptips, $mats, $ornaments, $background, $overlayer1, $overlayer2, $cover, $head, $left, $right, $foot);

        // メンバ変数 units を復元。
        $sphere->units = array();
        foreach($units as $index => $unitData) {
            $unit = SphereUnit::load($unitData, $sphere);
            $sphere->units[$index] = $unit;
        }

        // リターン。
        return $sphere;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのフィールドの処理を行うオブジェクトを返す。
     *
     * @param int       クエストID
     * @return object   このクラス、あるいはそこから派生したクラスのオブジェクト。
     */
    protected static function factory($questId) {

        // 派生クラスの名前を取得。
        $customClassName = 'SphereOn' . sprintf('%05d', $questId);

        // 派生クラスが定義されているファイルパスを取得。
        $customFile = dirname(__FILE__).'/extends/'.$customClassName.'.class.php';

        // そのファイルがあるならインクルードして、派生クラスインスタンスを返す。
        if(file_exists($customFile)) {
            require_once($customFile);
            return new $customClassName();

        // ファイルがないなら、SphereCommonインスタンスを返す。
        }else {
            return new self();
        }
    }


    // SphereMap, SphereUnit, SphereBaseAction 以外からのイベント処理を行うメソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 現在再生中の寸劇が終了したときに呼ばれる。
     *
     * @param int   再生終了した寸劇のID
     */
    public function dramaEnd($dramaId) {

        // 現在再生中の寸劇でない場合は無視する。
        if($this->state['scene'] != 'drama'  ||  $this->state['scene_id'] != $dramaId)
            return;

        $this->doDramaEnd();
        $this->save();
    }

    /**
     * dramaEnd() における、保存以外の処理を行う。
     */
    public function doDramaEnd() {

        // シーンをフィールドに戻す。
        $this->state['scene'] = 'field';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルが終了したときに呼ばれる。
     *
     * @param array     終了後のbattle_logレコード。
     */
    public function battleEnd($battle) {

        $this->doBattleEnd($battle);
        $this->save();
    }

    /**
     * battleEnd() における、保存以外の処理を行う。
     */
    public function doBattleEnd($battle) {

        // バトルを表すステートイベントを作成。
        $battleEvent = array(
            'type' => 'battle2',
            'regular' => true,
            'challenger' => $this->state['scene_trigger'],
            'defender' => $this->state['scene_id'],
            'total' => array(
                'challenger' => $battle['result_detail']['defender']['summary']['total_hurt'],
                'defender' => $battle['result_detail']['challenger']['summary']['total_hurt'],
            ),
        );
        $this->pushStateEvent($battleEvent);

        // シーンをフィールドに戻す。
        $this->state['scene'] = 'field';

        // アイテムドロップを処理する。
        for($i = 0 ; $i <= 1 ; $i++) {

            // 挑戦側⇒防衛側の順で処理する。
            $side = $i ? 'challenger' : 'defender';

            // ドロップがある場合、それをトレジャーとして処理する。
            // このままだと取得メッセージが指揮としては発行されないが、バトル結果画面で表示されて
            // いるのでOK。
            $uitem = $battle['result_detail'][$side]['gain']['uitem'][0];
            if($uitem)
                $this->processTreasure($uitem['user_item_id'], $this->getUnit($battleEvent[$side]));
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトル中にコンティニューしたときに呼ばれる。
     *
     * @param array     終了後のbattle_logレコード。
     */
    public function battleContinue($battle, $hp) {

        $this->doBattleContinue($battle, $hp);
        $this->save();
    }

    /**
     * doBattleContinue() における、保存以外の処理を行う。
     */
    public function doBattleContinue($battle, $hp) {

        $unit = $this->getUnitByCode("avatar");

        //スフィアに保存。指揮の$leadsはバトル中なので意味なし
        $unit->recoverHp($leads, $hp);
        
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザが送信したコマンドを処理して、その結果スフィアに起きた出来事を指揮内容として返す。
     *
     * @param array     SWFから送信された(ユーザが送信した)コマンド。以下のキーを含む連想配列。
     *                      rev         ユーザが想定しているスフィアリビジョン番号
     *                      reopen      スフィアの再開。この値が指定されている場合は他のコマンドは
     *                                  無視される。
     *                      moveX       移動先座標X
     *                      moveY       同Y
     *                      act         行動種別。以下のいずれか
     *                                      att     攻撃
     *                                      wait    待機
     *                                      item    アイテム使用
     *                      slot        act:"item" の場合に、使用対象のスロット番号
     *                                  0～7は装備品であることをあらわす。
     *                      useX        行動対象の座標X
     *                      useY        同Y
     * @return array    スフィアに起きた出来事をあらわす指揮内容。
     */
    public function command($command) {

        // 送信されたコマンドをチェック。
        $errorCode = $this->checkCommand($command);
        if($errorCode)
            return array('ERROR ' . $errorCode);

        // 処理前のスフィアの状態を取っておく。
        $before = $this->getRecord();

        // コマンドに対応して、SWFに伝える指揮を得る。
        $leads = $this->respondCommand($command);

        // スフィアの状態が変わっているなら処理結果をセーブ。
        if( $this->save($before) )
            $leads[] = 'REVIS '.$this->info['revision'];

        // リターン。
        return $leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フィールドクエスト完了後のサマリを取得する。
     *
     * @return array    以下のキーを持つ連想配列。
     *                      quest_id    クエストID
     *                      quest_name  クエスト名
     *                      result      結果コード。Sphere_InfoServiceの定数。
     *                      turn        経過ターン数
     *                      terminate   ユニットを倒した数
     *                      mission     ミッションについて...
     *                          achieve     達成したかどうか
     *                          gold        達成によって得られたマグナ
     *                      treasures   クエスト中に手に入れたアイテムのIDの配列
     */
    public function getSummary() {

        // 戻り値作成
        $summary = array();
        $summary['quest_id'] = $this->info['quest_id'];
        $summary['result'] = $this->info['result'];
        $summary['turn'] = $this->state['rotation_all'];
        $summary['terminate'] = $this->state['termination_all'];
        $summary['mission']['achieve'] = $this->state['mission_achieve'];
        $summary['mission']['gold'] = $this->state['mission_achieve'] ? $this->missionReward : 0;
        $summary['treasures'] = $this->state['treasures'];

        // クエスト情報を取得して、クエスト名も取得する。
        $quest = Service::create('Quest_Master')->needRecord($summary['quest_id']);
        $summary['quest_name'] = $quest['quest_name'];

        return $summary;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フィールドクエスト完了後、連続実行させたいクエストのIDを返す。
     *
     * @return int  連続実行させたいクエストのID。ない場合は0。
     */
    public function getNextQuest() {

        // 成功していない、まだ終わっていない場合は 0 を返す。
        if($this->info['result'] != Sphere_InfoService::SUCCESS)
            return 0;

        if(!$this->nextQuestRepeat){
            // はじめてのクリアでないなら 0。
            if($this->state['cleared'])
                return 0;
        }

        // 初めてのクリアなら、定義されている値を返す。
        return $this->nextQuestId;
    }


    // 参照系のメソッド
    //=====================================================================================================

    /**
     * このスフィアのIDを返す。
     */
    public function getSphereId() {

        return $this->info['sphere_id'];
    }

    /**
     * このスフィアのマップユーティリティを返す。
     */
    public function getMap() {

        return $this->map;
    }

    /**
     * scene_idの値を返す。
     */
    public function getSceneId() {

        return $this->state['scene_id'];
    }

    /**
     * このスフィアに存在するユニットの一覧を返す。
     */
    public function getUnits() {

        return $this->units;
    }

    /**
     * アイテムIDの、サーバ側番号とSWF側番号の対応表を返す。
     */
    public function getItemTable() {

        return $this->state['item_table'];
    }

    /**
     * ユニットアイコンの、サーバ側アイコン名とSWF側番号の対応表を返す。
     */
    public function getUnitIcons() {

        return $this->state['unit_icons'];
    }


    /**
     * このスフィアのquestIDを返す。
     */
    public function getQuestId() {

        return $this->info['quest_id'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたステートプロパティを返す。
     *
     * @param string    ステートプロパティの名前
     */
    public function getStateProp($propName) {

        return $this->state[$propName];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された番号のユニットを返す。
     *
     * @param int       ユニット番号。省略時は現在ターンが回ってきているユニットを返す。
     * @param object    SphereUnitのインスタンス。見つからない場合はnull。
     */
    public function getUnit($unitNo = -1) {

        if($unitNo == -1)
            $unitNo = $this->state['act_unit'];

        return $this->units[$unitNo];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたコードネームを持つユニットを返す。
     *
     * @param string    コードネーム。省略時は "avatar"。
     * @param object    SphereUnitのインスタンス。見つからなかったらnull。
     */
    public function getUnitByCode($code = 'avatar') {

        // プレイヤーアバターユニットを探す。
        foreach($this->units as $unit) {
            if($unit->getCode() == $code)
                return $unit;
        }

        // 見つからなかったらnullを返す。
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットのコードネームをキー、ユニット番号を値とする配列を返す。
     */
    protected function getUnitCodeMap() {

        // 戻り値初期化。
        $result = array();

        // ユニットを一つずつ見ていく。
        foreach($this->units as $no => $unit) {

            // コードネームを持っているものを見つけたら、戻り値に追加。
            $code = $unit->getCode();
            if($code)
                $result[$code] = $no;
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたアイテムの情報をSWFに伝えるための文字列を返す。
     *
     * @param array     アイテムマスタの内容を含む配列。
     * @return string   SWFに伝えるための文字列
     */
    public function getItemDataSpec($item) {

        // item_master.item_type に対応する SWF での値。
        static $ITEM_TYPE_MAP = array(
            Item_MasterService::RECV_HP =>  'recov',
            Item_MasterService::TACT_ATT => 'damag',
        );

        return sprintf('%3s %5s %02d %02d %s'
            , Item_MasterService::isDurable($item['category']) ? 'eqp' : 'itm'
            , array_key_exists($item['item_type'], $ITEM_TYPE_MAP) ? $ITEM_TYPE_MAP[$item['item_type']] : 'noeff'
            , $item['item_limitation']
            , $item['item_spread']
            , $item['item_name']
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 想定レベル超越幅を返す。
     *
     * @return int      ユーザの初回突入レベルが、クエストの想定上限レベルよりいくつ高いかを返す。
     *                  ユーザレベルが想定上限より低い、あるいは、想定上限が設定されていないクエスト
     *                  では 0 が返る。
     */
    public function getTranscendLevel() {

        // クエストの想定上限レベルを取得。設定されていないなら 0 を返す。
        $quest = Service::create('Quest_Master')->needRecord($this->info['quest_id']);
        if($quest['upper_level'] == 0)
            return 0;

        // ユーザの初回突入レベルを取得。
        $firstLevel = Service::create('Flag_Log')->getValue(Flag_LogService::FIRST_TRY, $this->info['user_id'], $this->info['quest_id']);

        // 初回突入レベルが想定上限より高いならその差を、そうでないなら 0 を返す。
        if($firstLevel > $quest['upper_level']){
            $lv = $firstLevel - $quest['upper_level'];
            //TRANSCEND_LEVEL_MAX以上は上げない
            if($lv >= self::TRANSCEND_LEVEL_MAX){
                return self::TRANSCEND_LEVEL_MAX;
            }else{
                return $lv;
            }
        }else{
            return 0;
        }

        // 初回突入レベルが想定上限より高いならその差を、そうでないなら 0 を返す。
        return ($firstLevel > $quest['upper_level']) ? ($firstLevel - $quest['upper_level']) : 0;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ミッションがあるかどうかを返す。
     *
     * @return bool     ミッションがあるならtrue、ないならfalse。
     */
    protected function isMissionExists() {

        // 基底では、一度クリアしており、かつミッションは未達成ならミッションがある。
        if( !$this->state['cleared'] )
            return false;

        $achieveCount = Service::create('Flag_Log')->getValue(Flag_LogService::MISSION, $this->info['user_id'], $this->info['quest_id']);
        return ($achieveCount == 0);
    }


    // protectd フィールド
    //=====================================================================================================

    // スフィア情報。
    protected $info;

    // $this->info['state'] への参照。
    protected $state;

    // ユニット(SphereUnit)の配列
    protected $units;

    // マップユーティリティ(SphereMap)。
    protected $map;

    // 初めてクリアした後、連続実行させたいクエストがあるならばそのID。
    // オーバーライド用。
    protected $nextQuestId = 0;

    // 二回目以降クリアした後でも連続実行させたいならオーバーライドしてtrueにする。
    protected $nextQuestRepeat = false;

    // ミッション達成時の報酬金。だいたい、クエスト想定Lvの敵のマグナx7 くらいが基準？
    protected $missionReward = 0;

    // 地形破壊アイテムによる破壊後の地形ID。地形破壊アイテムが使用される可能性がある場合は定義する。
    protected $destructedId = 0;


    // スフィアやルームのセットアップ、ユニットの追加等のメソッド。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 一番最初のルーム入る。
     *
     * @param int       プレイヤーアバタのキャラクタID
     * @param array     プレイヤーアバタがクエストに持っていくユーザアイテムIDの配列。
     * @param mixed     特定のクエストの開始処理で渡したいパラメータがある場合はここに指定する。
     */
    protected function startState($avatarId, $uitemIds, $initialize) {

        // ステートを初期化。
        $this->state = array();
        $this->state['initialize'] = $initialize;
        $this->state['rotation_all'] = 0;
        $this->state['termination_all'] = 0;
        $this->state['mission_achieve'] = false;
        $this->state['memory'] = array();
        $this->state['treasures'] = array();
        $this->state['story_before'] = '';

        // クエストの挑戦回数とクリアしたことがあるかどうかを取得。
        $flagSvc = new Flag_LogService();
        $this->state['try_count'] = $flagSvc->getValue(Flag_LogService::TRY_COUNT, $this->info['user_id'], $this->info['quest_id']);
        $this->state['cleared'] = (bool)$flagSvc->getValue(Flag_LogService::CLEAR, $this->info['user_id'], $this->info['quest_id']);

        // ミッションがあるかどうかを取得。
        $this->state['mission_exists'] = $this->isMissionExists();

        // 主人公ユニットなどの、初期参戦する定義外ユニットをロード。
        $startingUnits = $this->loadStartingUnits($avatarId, $uitemIds);

        // "start"のルームで state の値をセット。
        $this->createState('start', $startingUnits, 'start');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * クエスト開始時、"units" キーで定義されていない登場ユニットをロードする。
     * プレイヤーのアバターキャラや、ルームチェンジしても引き継ぐユニットなどが該当する。
     *
     * @param int       プレイヤーアバタのキャラクタID
     * @param array     プレイヤーアバタがクエストに持っていくユーザアイテムIDの配列。
     * @return array    クエスト開始時のロードユニットを表す SphereUnit の配列。
     */
    protected function loadStartingUnits($avatarId, $uitemIds) {

        // 戻り値初期化。
        $result = array();

        // フィールド定義を取得。
        $field = Service::create('Field_Master')->needRecord($this->info['quest_id']);

        // 主人公ユニットを作成。
        $data = SphereUnit::makeUnitData($avatarId, $uitemIds);
        $data['code'] = 'avatar';
        $data = array_merge($data, (array)$field['hero_unit']);
        $result[] = SphereUnit::load($data, $this);

        // "start_units" キーにあるユニットをロード
        foreach( (array)$field['start_units'] as $define ) {

            // 条件に該当しない場合は無視する。
            if( !$this->testCondition($define['condition'], $define, 'start') )
                continue;

            $unit = SphereUnit::createDefineUnit($define, $this);
            $unit->setProperty('room_takeover', true);
            $result[] = $unit;
        }

        // 参戦するユニットの配列を作成。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたルームに遷移する。
     *
     * @param string    ルーム名
     * @param string    理由を表す文字列。通常、遷移を引き起こしたギミックの名前が入る。
     */
    protected function changeState($roomName, $reason) {

        // 下位互換を保つ。
        if( !$this->state['version'] )
            $reason = sprintf('goto_%s_from_%s', $roomName, $this->state['current_room']);

        // 現在のユニットから引き継ぐユニットを取得。
        $overUnits = $this->unitTakeover($roomName);

        // フィールドステートを作成しなおす。
        $this->createState($roomName, $overUnits, $reason);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 別のルームに移るときに、新しいルームへ引き継ぐユニットを返す。
     *
     * @param int       次のルーム番号。
     * @return array    引き継ぐユニットの配列。
     */
    protected function unitTakeover($newRoom) {

        // コード "avatar"、あるいは "room_takeover" フラグが ON になっているユニットを引き継ぐ。
        // コード "avatar" を見ているのは下位互換のため。
        $result = array();
        foreach($this->units as $no => $unit) {
            if($unit->getProperty('room_takeover')  ||  $unit->getCode() == 'avatar')
                $result[$no] = $unit;
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された番号のルームでフィールドステートを作成、$this->state にセットする。
     *
     * @param string    ルーム名。
     * @param array     初期参戦する定義外ユニットの配列。
     * @param string    理由を表す文字列。通常、クエスト開始なら "start" が、画面遷移した場合は遷移を
     *                  引き起こしたギミックの名前が入る。
     */
    protected function createState($roomName, $enterUnits, $reason) {

        // フィールド定義を取得。
        $field = Service::create('Field_Master')->needRecord($this->info['quest_id']);

        // 存在しないルームが指定されていたらエラー。
        $roomInfo = $this->getRoomDefinition($field, $roomName);
        if(!$roomInfo)
            throw new MojaviException( sprintf('存在しないルームが指定されていた(%s)', $roomName) );

        // グローバル値をマージする。
        if($field['extra_uniticons'])
            $roomInfo['extra_uniticons'] = array_merge($roomInfo['extra_uniticons'] ?: array(), $field['extra_uniticons']);
        if($field['extra_maptips'])
            $roomInfo['extra_maptips'] = array_merge($roomInfo['extra_maptips'] ?: array(), $field['extra_maptips']);
        if($field['global_gimmicks'])
            $roomInfo['gimmicks'] += $field['global_gimmicks'];

        // リセットすべき値をリセット。
        $this->units = array();
        $this->state['version'] = 2;
        $this->state['rotation'] = 0;
        $this->state['termination'] = 0;
        $this->state['current_room'] = $roomName;
        $this->state['change_room'] = null;
        $this->state['scene'] = 'field';
        $this->state['act_unit'] = 0;
        $this->state['act_phase'] = '';
        $this->state['command'] = null;
        $this->state['queue'] = array();
        $this->state['map_class'] = isset($roomInfo['map_class']) ? $roomInfo['map_class'] : '';
        $this->state['item_table'] = array();

        // マップユーティリティを作成。
        $this->map = SphereMap::factory($this->state['map_class'], $this);
        $this->map->initStructure($roomName, $roomInfo, $reason);

        $this->state['id'] = $roomInfo['id'];

        // ギミックを初期化。
        $this->initGimmicks($roomName, $roomInfo, $reason);

        // unit_icons を初期化。
        $this->initIconTable($roomName, $roomInfo, $reason);

        // $this->units を初期化。
        $this->initUnits($roomName, $roomInfo, $enterUnits, $reason);

        //roomsのバトル用BGイメージをstateにマージ
        $this->state['battle_bg'] = $roomInfo['battle_bg'];

        $this->state['sphere_bg'] = $roomInfo['sphere_bg'];
        $this->state['environment'] = isset($roomInfo['environment']) ? $roomInfo['environment'] : "none";

        //roomsのBGMをstateにマージ
        $this->state['bgm'] = $roomInfo['bgm'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたフィールド定義から指定のルームの定義を返す。
     *
     * @param array     フィールド定義。
     * @param string    ルーム名。
     * @return array    ルーム定義。指定のルームがなかったらnull。
     */
    protected function getRoomDefinition($data, $roomName) {

        // フィールド定義から指定のルームのキーを取得。なかったらnull。
        $room = $data['rooms'][$roomName];
        if(!$room)
            return null;

        // キーにルーム情報が入っているならそれを返す。
        if( is_array($room) )
            return $room;

        // それ以外なら、それは別定義ファイルのIDと解釈して読み込む。
        return Service::create('Field_Master')->getRecord($room);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * $this->state のギミック一覧を成すキー gimmicks を初期化する。
     *
     * @param string    ルーム名
     * @param array     ルーム定義
     * @param string    理由を表す文字列。通常、クエスト開始なら "start" が、画面遷移した場合は遷移を
     *                  引き起こしたギミックの名前が入る。
     */
    protected function initGimmicks($roomName, $roomInfo, $reason) {

        // 初期化。
        $this->state['gimmicks'] = array();

        // 定義されているギミックを一つずつ処理していく。
        foreach((array)$roomInfo['gimmicks'] as $name => $gimmick)
            $this->addGimmick($name, $gimmick, $roomName, $reason);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * $this->state のユニットアイコンテーブルを成すキー unit_icons を初期化する。
     *
     * @param string    ルーム名
     * @param array     ルーム定義
     * @param string    理由を表す文字列。通常、クエスト開始なら "start" が、画面遷移した場合は遷移を
     *                  引き起こしたギミックの名前が入る。
     */
    protected function initIconTable($roomName, $roomInfo, $reason) {

        // フィールド情報で定義されているユニットアイコンを取得。
        $icons = $roomInfo['extra_uniticons'] ?: array();

        // 標準使用するアイコンを追加。
        $icons[] = 'avatar';    $icons[] = 'shadow';    $icons[] = 'shadow2';
        $icons = array_unique($icons);

        // ユニットアイコンテーブルを構築。
        $this->state['unit_icons'] = array();
        $no = 1;
        foreach($icons as $name)
            $this->state['unit_icons'][$name] = $no++;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 初期ユニットを作成・配置する。
     *
     * @param string    ルーム名
     * @param array     ルーム定義
     * @param array     初期参戦する定義外ユニットの配列。
     * @param string    理由を表す文字列。通常、クエスト開始なら "start" が、画面遷移した場合は遷移を
     *                  引き起こしたギミックの名前が入る。
     */
    protected function initUnits($roomName, &$roomInfo, $enterUnits, $reason) {

        // 開始座標を取得。
        if( isset($roomInfo['start_pos_on'][$reason]) )
            $startPos = $roomInfo['start_pos_on'][$reason];
        else if( isset($roomInfo['start_pos']) )
            $startPos = $roomInfo['start_pos'];
        else
            throw new MojaviException(sprintf('クエスト"%s"で、"%s"の理由でルーム"%s"への遷移時、開始位置がない。', $this->info['quest_id'], $reason, $roomName));

        if( isset($roomInfo['start_align']) )
            $startAlign = $roomInfo['start_align'];
        else
            $startAlign = 0;

        // 参戦する定義外ユニットを配置。
        foreach($enterUnits as $unit) {
            $unit->setPos($startPos);
            $unit->setAlign($startAlign);
            $this->addUnit($unit);
        }

        // 定義ユニットを配置。
        if( !empty($roomInfo['units']) ) {
            foreach($roomInfo['units'] as $define) {

                // 条件に該当しない場合は追加しない。
                if( !$this->testCondition($define['condition'], $define, $reason) )
                    continue;

                // 追加。
                unset($define['condition']);
                $unit = SphereUnit::createDefineUnit($define, $this);
                $this->addUnit($unit);
            }
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたギミックをこのスフィアに追加する。
     * 条件が付いている場合はそれも処理して、条件が合わない場合は追加しない。
     *
     * @param string    ギミック名
     * @param array     ギミックの内容。フィールド情報で定義されているもの。
     * @param string    ルーム名
     * @param string    理由を表す文字列。通常、クエスト開始なら "start" が、画面遷移した場合は遷移を
     *                  引き起こしたギミックの名前が入る。
     * @return bool     追加したのなら true、しなかったのなら false。
     */
    protected function addGimmick($name, $gimmick, $roomName = '', $reason = '') {

        // "switch" キーが付いているものは最初に処理する。
        if(isset($gimmick['switch'])) {

            // "switch" キーをギミックから取り出す。
            $switch = $gimmick['switch'];
            unset($gimmick['switch']);

            // "switch" キーを一つずつ見ていく。
            foreach($switch as $case) {

                // "switch" キーの要素でオリジナルギミックの内容をマージしてギミックを作成。
                $merge = $case + $gimmick;

                // それを追加してみる。追加できたのなら終了。
                if( $this->addGimmick($name, $merge, $roomName, $reason) )
                    return true;
            }

            // ここまで来るのはいずれの "switch" キーでも追加できなかったから。このギミックは追加できない。
            return false;
        }

        // "one_shot"フラグが定義されている場合は、"yet_flag" と "flag_on" に展開する。
        if(isset($gimmick['one_shot'])) {
            $gimmick['condition']['yet_flag'] = $gimmick['one_shot'];
            $gimmick['flag_on'] = $gimmick['one_shot'];
            unset($gimmick['one_shot']);
        }

        // "memory_shot"フラグが定義されている場合は、"yet_memory" と "memory_on" に展開する。
        if(isset($gimmick['memory_shot'])) {
            $gimmick['condition']['yet_memory'] = $gimmick['memory_shot'];
            $gimmick['memory_on'] = $gimmick['memory_shot'];
            unset($gimmick['memory_shot']);
        }

        // 条件に該当しない場合は追加しない。
        $gimmick['name'] = $name;
        if( !$this->testCondition($gimmick['condition'], $gimmick, $reason) )
            return false;
        unset($gimmick['name'], $gimmick['condition']);

        // 置物を一緒に置くように指定されている場合は...
        if(isset($gimmick['ornament'])) {

            // ornament キーを削除する代わりに ornNo で紐づいている置物を指すようにする。
            $ornType = $gimmick['ornament'];
            unset($gimmick['ornament']);
            $gimmick['ornNo'] = array();

            // ギミックの設置範囲の右下座標を取得。
            $rb = isset($gimmick['rb']) ? $gimmick['rb'] : $gimmick['pos'];

            // 設置範囲のすべての座標に置物を置く。
            for($y = $gimmick['pos'][1] ; $y <= $rb[1] ; $y++) {
                for($x = $gimmick['pos'][0] ; $x <= $rb[0] ; $x++) {

                    $ornNo = $this->map->addOrnament(array(
                        'pos' =>  array($x, $y),
                        'type' => $ornType,
                    ));

                    $gimmick['ornNo'][] = $ornNo;
                }
            }
        }

        //多言語対応
        if(isset($gimmick['type'])) {
            if($gimmick['type'] == "lead"){
                if(isset($gimmick['textsymbol'])){
                    //textsymbolで直接指定されている場合はそちらを優先的に使う
                    $symbol = $gimmick['textsymbol'];
                    unset($gimmick['textsymbol']);
                }else{
                    $symbol = "sphere_" . $this->info['quest_id'] . "_" . $this->state['id'] . "_" . $name;
                    if(AppUtil::getText($symbol) == null){
                        //room_idで無いならroomnameで探してみる
                        $symbol = "sphere_" . $this->info['quest_id'] . "_" . $roomName . "_" . $name;
                    }
                }

                if(AppUtil::getText($symbol) != null){
                    $gimmick['leads'] = AppUtil::getTexts($symbol);
                }

            }
        }

        // コメントは削除。
        unset($gimmick['rem']);

        // スフィアに追加。
        $this->state['gimmicks'][$name] = $gimmick;

        return true;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 通常のギミック終了処理を行わずに、指定されたギミックを削除する。
     *
     * @param string    ギミック名
     */
    public function removeGimmick($name) {

        unset( $this->state['gimmicks'][$name] );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のギミックの指定のプロパティの値を変更する。
     *
     * @param string    ギミック名
     * @param string    ギミックのプロパティ名
     * @param mixed     値
     */
    public function modifyGimmick($gimName, $propName, $value) {

        if( isset($this->state['gimmicks'][$gimName]) )
            $this->state['gimmicks'][$gimName][$propName] = $value;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユニットをスフィアに追加する。
     *
     * @param object    SphereUnit インスタンス。
     * @return array    ユニット追加をSWFに伝えるための指揮コマンドの配列。
     *                  指揮がない場合はカラ配列。
     */
    protected function addUnit($unit) {

        $itemMst =  &$this->state['item_table'];

        // 戻り値初期化。
        $leads = array();

        // ユニットの座標をフリーの座標に修正する。
        $pos = $this->map->getFreePoint($unit->getPos(), $unit);
        $unit->setPos($pos);

        // ユニットを追加。
        $unitNo = $this->units ? max(array_keys($this->units)) + 1 : 1;
        $unit->setNo($unitNo);
        $this->units[$unitNo] = $unit;

        // ユニットの所持アイテムと装備を、user_item_idとSWF側アイテム番号の対応表に追加。
        $leads = array_merge( $leads, $this->addItem($unit->getProperty('items')) );
        $leads = array_merge( $leads, $this->addItem($unit->getProperty('sequip')) );

        // ユニットを登場させるための指揮を追加。
        $specs = $unit->getUnitSpecs();
        $leads[] = sprintf('USTAT %03d %s', $unit->getNo(), $specs['Status']);
        $leads[] = sprintf('UITEM %03d %s', $unit->getNo(), $specs['Item']);
        $leads[] = sprintf('UEQIP %03d %s', $unit->getNo(), $specs['Eqp']);
        $leads[] = sprintf('UADDI %03d %02d %02d %s %s', $unit->getNo(), $specs['X'], $specs['Y'], $specs['Info'], $specs['Name']);

        // リターン。
        return $leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * スフィア内のアイテムマスタに引数で指定されたアイテムを加える。
     *
     * @param mixed     ユーザアイテムID、あるいはその配列。
     * @return array    アイテムマスタ追加をSWFに伝えるための指揮コマンドの配列。
     *                  指揮がない場合はカラ配列。
     */
    public function addItem($uitemId) {

        // 配列で複数指定されている場合は再起して対処。
        if( is_array($uitemId) ) {
            $ret = array();
            foreach($uitemId as $id)
                $ret = array_merge($ret, $this->addItem($id));
            return $ret;
        }

        // 持っていないことをあらわす値なら何もしない。
        if($uitemId == 0)
            return array();

        // すでにマスタにあるなら何もしない。
        if( array_key_exists($uitemId, $this->state['item_table']) )
            return array();

        // マスタに追加。
        $innerItemNo = count($this->state['item_table']) + 1;
        $this->state['item_table'][$uitemId] = $innerItemNo;

        // アイテムデータを取得。
        $uitem = Service::create('User_Item')->getRecord($uitemId);

        if($uitem == null)
            return array();

        // アイテムマスタ追加の指揮を作成。
        return array( sprintf('ITEMD %03d %s', $innerItemNo, $this->getItemDataSpec($uitem)) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された条件に該当しているかどうかを返す。
     *
     * @param array     フィールド情報の gimmicks.condition, gimmicks.ignition や units.condition などの値。
     * @param reference ギミックに対する判定の場合はギミック、units.conditionを判定する場合はユニット定義。
     * @param string    gimmicks.condition, units.condition を判定する場合に、理由を表す文字列。
     *                  通常、クエスト開始なら "start" が、画面遷移した場合は遷移を引き起こしたギミックの
     *                  名前が入る。
     *                  ignitionの判定時は、トリガーユニットのcodeの値が入る。
     * @return bool     条件に該当している、あるいは条件がない場合は true、該当しない場合は false。
     */
    protected function testCondition($condition, &$owner, $reason) {

        // 特に条件がないなら常にtrue。
        if(!$condition)
            return true;

        // 指定されている条件をすべてチェック。
        foreach($condition as $condName => $value) {

            $method = "and";

            // "yet_flag" は "!has_flag" に、"yet_memory" は "!has_memory" に変換する。
            if($condName == 'yet_flag')    $condName = '!has_flag';
            if($condName == 'yet_memory')  $condName = '!has_memory';

            // 条件名が "!" で始まっているものは逆評価する。
            $positive = ($condName{0} != '!');
            if(!$positive)
                $condName = substr($condName, 1);

            // 条件名が "|" で始まっているものはOR条件
            if($condName{0} == '|'){
                $method = "or";
                $condName = substr($condName, 1);
            }

            // 判定。
            $result = $this->judgeCondition($condName, $value, $owner, $reason);

            if($method == "and"){
                // 一つでも偽なら、条件には該当しない。
                if($positive != $result)
                    return false;
            }else{
                // 一つでも真なら、条件に該当。
                if($positive == $result)
                    return true;
            }
        }

        if($method == "and")
            // ここまで来たらすべて該当している。
            return true;
        else
            // ここまで来たらすべてfalse。
            return false;
    }

    /**
     * testCondition()のヘルパ。一つ一つの条件判定を行う。
     */
    protected function judgeCondition($name, $value, &$owner, $reason) {
        switch($name) {

            // クリアしている／いない
            case "cleared":
                return ($this->state['cleared'] == (bool)$value);

            // 指定の理由
            case "reason":
                return in_array($reason, (array)$value);

            // チェックメモリがある
            case 'has_memory':
                return (bool)$this->state['memory'][$value];

            // チェックフラグがある
            case 'has_flag':
                return (bool)Service::create('Flag_Log')->getValue(Flag_LogService::FLAG, $this->info['user_id'], $value);

            // ミッションが存在する／しない
            case "mission":
                return ($this->state['mission_exists'] == (bool)$value);

            // 指定のユニットのどれかがいる。"unit" は下位互換のために残してある。
            case "unit":
            case "unit_exist":
                foreach((array)$value as $code) {
                    if( $this->getUnitByCode($code) )
                        return true;
                }
                return false;

            // 指定のユニットのどれかがいない。
            case "unit_nonexist":
                foreach((array)$value as $code) {
                    if( !$this->getUnitByCode($code) )
                        return true;
                }
                return false;

            // 指定のユニットのどれかがまだ生きている。
            case "unit_alive":
                foreach((array)$value as $code) {
                    $unit = $this->getUnitByCode($code);
                    if($unit  &&  $unit->getProperty('hp') > 0)
                        return true;
                }
                return false;

            // 指定のユニットが起動した
            case "igniter":
                return in_array($reason, (array)$value);

            // カスタムコール
            case "call":
                return $this->$value($owner, $reason);

            // その他の条件は無視。
            default:
                return true;
        }
    }


    // コマンドの実行、スフィアの経過を処理するメソッド。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザが送信したコマンドの妥当性を検証して、どんなコマンドが送られたのかを要約する。
     *
     * @param array     ユーザが送信したFLASHコマンド。command() のときと同様。
     *                  ここで指定された変数に、要約した結果が格納される。
     *                  スフィアの再開の場合は再開方法を現す文字列 "resume" か "continue"、
     *                  ユニットのコマンドが送られている場合はユニットコマンド。
     * @return string   エラーがある場合はそのエラーコード。妥当な場合はカラ文字列。
     */
    protected function checkCommand(&$command) {

        // リビジョン番号が正しいかチェック。
        if($this->info['revision'] != $command['rev']){
            return 'rev';
        }

        // スフィアの再開なら以降のチェックは不要。
        if( !empty($command['reopen']) ) {
            $command = $command['reopen'];
            return '';
        }

        // 以降は、ユニットに対するコマンドに対するチェック。

        // 送信されるべきタイミングでないならエラー。
        if($this->state['scene'] != 'field'  ||  $this->state['act_phase'] != 'command')
            throw new MojaviException('不正なタイミングでユニットコマンドが送信された');

        // 行動ptあるかどうかチェック。
        $user = Service::create('User_Info')->needRecord($this->info['user_id']);
        if($user['action_pt'] < Service::create('Quest_Master')->getConsumePt($this->info['quest_id']))
            return 'actpt';

        // コマンド対象になっているユニットを取得。送信されたコマンドをチェックして
        // ユニットコマンドに変換する。
        return $this->getUnit()->checkCommand($command);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザが送信したコマンドを処理して、スフィアSWFへの指揮を発行する。
     *
     * @param mixed     checkCommand() によって要約されたコマンド。
     * @return array    スフィアSWFへの指揮
     */
    protected function respondCommand($command) {
        $module = Controller::getInstance()->getContext()->getModuleName();

        // 指揮内容を初期化。
        $leads = array();

        // 中断／エラーからの復帰による再開の場合は直前の粗筋を付ける。
        if(is_string($command)  &&  $command == 'resume')
            $this->tellStory($leads);

        // もう終わっている場合はフィールド終了画面へ転送する。
        if($this->info['result'] != Sphere_InfoService::ACTIVE) {
            $leads[] = 'TRANS ' . Common::genContainerUrl(
                $module, 'FieldEnd', array('sphereId'=>$this->info['sphere_id']), true
            );

        // まだ続いているなら...
        }else {

            // シーンにしたがって分岐。
            switch($this->state['scene']) {

                // 寸劇
                case 'drama':
                    $leads[] = 'TRANS ' . Common::genContainerUrl(
                        $module, 'FieldDrama', array('sphereId'=>$this->info['sphere_id']), true
                    );
                    break;

                // バトル
                case 'battle':

                    $userSvc = new User_InfoService();

                    // ユーザ情報を取得。
                    $user = $userSvc->needRecord($this->info['user_id']);

                    // バトルを再び始めるには行動ptが必要。足りない場合はそれを示す指揮を発行する。
                    if($user['action_pt'] < self::BATTLE_REMAKE_ACTPT) {
                        $leads[] = "NOTIF " . AppUtil::getText("sphere_text_battle_recover");
                        $leads[] = "ERROR actpt";

                    // 行動ptが足りている場合。
                    }else {

                        // ペナルティとして行動ptを消費
                        $userSvc->plusValue($this->info['user_id'], array('action_pt'=> -1 * self::BATTLE_REMAKE_ACTPT));

                        // バトルを再作成する。
                        $battleId = $this->makeMainBattle();

                        // バトルフラッシュへの転送指示を出す。
                        $leads[] = 'TRANS ' . Common::genContainerUrl($module, 'Battle', array('battleId'=>$battleId), true);
                    }

                    break;

                // フィールド
                case 'field':

                    // ユニットのコマンドの場合。
                    if( is_array($command) ) {

                        // ユニットのコマンドが送られているということは、それまでの指揮は見てもらえたと判断する。
                        $this->state['story_before'] = '';

                        // 実行されるコマンドとして保持。
                        $this->state['command'] = $command;

                        // 行動ptを消費
                        $userSvc = new User_InfoService();
                        $userSvc->plusValue($this->info['user_id'], array(
                            'action_pt'=> -1 * Service::create('Quest_Master')->getConsumePt($this->info['quest_id']))
                        );

                        // 消費後の行動ptを取得。
                        $user = $userSvc->needRecord($this->info['user_id']);
                        $leads[] = 'ACTPT ' . (int)$user['action_pt'];
                    }

                    // フェーズの経過を処理。
                    $this->progressPassing($leads);

                    // 指揮内容からテキストを抽出して、粗筋として保持しておく。
                    $this->state['story_before'] .= $this->extractText($leads);

                    break;
            }
        }

        // リターン。
        return $leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 直前の粗筋を表示する指揮を発行する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     */
    protected function tellStory(&$leads) {

        // 表示行数
        $DISPLAY_LINES = 17;

        // 粗筋の内容が何もないならリターン。
        if( !$this->state['story_before'] )
            return;

        // 両端の改行を削除して、行ごとに分解する。
        $lines = explode("\n", trim($this->state['story_before'], "\n"));

        // 表示行数ごとにまとめる。
        for($i = 0 ; $i < count($lines) ; $i += $DISPLAY_LINES)
            $leads[] = 'STORY ' . implode("\n", array_slice($lines, $i, $DISPLAY_LINES));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 現在の状態から、画面遷移かプレイヤーコントロールユニットのコマンド待機待ちになるまでの処理を行う。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     */
    protected function progressPassing(&$leads) {

        // SWFへのリターンが発生するまでフェーズを処理していく…けど、一応セーフティをかけておく。
        for($i = 0 ; $i < 50 ; $i++) {
            if( $this->progressPhase($leads) )
                break;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 現在のフェーズを処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressPhase(&$leads) {
        $module = Controller::getInstance()->getContext()->getModuleName();

        // ルーム開始直後なら、ルーム開始処理。
        if($this->state['act_unit'] == 0)
            return $this->progressRoomOpen($leads);

        // キューにエントリがあるなら、イベントキューを処理
        if($this->state['queue'])
            return $this->progressEvent($leads, array_shift($this->state['queue']));

        // ルームチェンジフラグがONになっている場合はルームチェンジ
        if($this->state['change_room']) {

            $this->changeState($this->state['change_room'][0], $this->state['change_room'][1]);

            // リロードするように転送指示を出す。

            $leads[] = 'SOUND ' . 'se_zazaza';
            $leads[] = 'TRANS ' . Common::genContainerUrl(
                $module, 'Sphere', array('id'=>$this->info['sphere_id'], '_nocache'=>true), true
            );
            return true;
        }

        // あとはターンユニットに進行をさせるが、ターンユニットがいなくなっている場合は次のユニットへ。
        if( !$this->getUnit() ) {
            $this->turnNext($leads);
            return false;
        }

        // 各ユニットのターンフェーズを進行していく。
        switch($this->state['act_phase']) {
            case 'precomm':
                return $this->progressPreComm($leads);
            case 'command':
                return $this->progressCommand($leads);
            case 'aftercomm':
                return $this->progressAfterComm($leads);
            case 'turnend':
                return $this->progressTurnEnd($leads);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ルーム開始直後のタイミングで呼ばれる。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressRoomOpen(&$leads) {

        // ダンジョン突入時、レベル調整が発動している場合は表示を行う。
        if($this->state['rotation_all'] == 0) {
            $transcend = $this->getTranscendLevel();
            if($transcend > 0)
                $leads[] = sprintf(AppUtil::getText("sphere_text_difficult_level"), $transcend);
        }

        // 開始直後の位置が暗幕の場合は解除を行う。
        foreach($this->units as $unit) {
            if( $unit->getProperty('player_owner') ) {
                foreach($this->map->findCurtainOn($unit->getPos()) as $curtainName)
                    $this->openCurtain($leads, $curtainName, $unit);
            }
        }

        // 先頭のユニットへ。
        $this->phaseNext($leads);
        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ステートイベントを処理する。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array     イベントの内容
     * @return bool     SWFへのリターンが発生したかどうか。
     */
    protected function progressEvent(&$leads, $event) {

        switch($event['type']) {

            case 'unit':
                $unit = $this->getUnit($event['no']);
                return $unit ? $unit->event($leads, $event) : false;

            case 'lead':
                $leads = array_merge($leads, (array)$event['leads']);
                return !empty($event['return']);

            case 'gimmick':
                return $this->triggerGimmick($leads, $event['name'], $this->getUnit($event['trigger']));

            case 'close':
                return $this->progressClose($leads, $event['result']);

            // 新バトル通知。
            case 'battle2':

                $challenger = $this->getUnit($event['challenger']);
                $defender = $this->getUnit($event['defender']);

                // トータルダメージがセットされている場合。
                if($event['total']) {

                    // 防衛側にダメージ。
                    if($event['total']['defender'] >= 0)
                        $defender->damageHp($leads, $event['total']['defender'], $challenger, $event['regular'] ? -1 : -2);
                    else
                        $defender->recoverHp($leads, -1 * $event['total']['defender'], $challenger);

                    // 挑戦側にダメージ。
                    if($event['total']['challenger'] >= 0)
                        $challenger->damageHp($leads, $event['total']['challenger'], $defender, $event['regular'] ? -1 : -2);
                    else
                        $challenger->recoverHp($leads, -1 * $event['total']['challenger'], $defender);

                // バトルフローがセットされている場合。
                }else {

                    // まだ未実装
                    // $event['flow']
                }

                return false;

            // メソッドcall
            case 'call':

                $method = $event['call'];
                return $this->$method($event);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたギミックが次の起動タイミングで起動するように設定する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param string        ギミック名。配列で複数設定することも可能。
     * @param object        ギミックを発動させたユニットのインスタンス。ユニットによらないならnull。
     * @param bool          trueに指定するとただちに起動する。triggerGimmickと同じになる。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    public function kickGimmick(&$leads, $name, $triggerUnit, $immediately = false) {

        // 起動対象がない場合はなにもしない。
        if(!$name)
            return false;

        // 指定のギミックを一つずつ処理する。即時起動の場合。
        if($immediately) {

            // triggerGimmick() に転送する。
            // 一つでもSWFへのリターンを要求するものがあれば、それを覚えるようにする。
            $swfReturn = false;
            foreach((array)$name as $gimName)
                $swfReturn = $this->triggerGimmick($leads, $gimName, $triggerUnit)  ||  $swfReturn;

            // リターン。
            return $swfReturn;

        // 遅延起動の場合。こちらがデフォルト。
        }else {

            // イベントキューにpush。
            foreach((array)$name as $gimName) {
                $this->pushStateEvent(array(
                    'type' => 'gimmick',
                    'name' => $gimName,
                    'trigger' => $triggerUnit ? $triggerUnit->getNo() : 0,
                ));
            }

            // SWFへのリターンはない。
            return false;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたギミックをただちに起動する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param string        ギミック名。
     * @param object        ギミックを発動させたユニットのインスタンス。ユニットによらないならnull。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    public function triggerGimmick(&$leads, $name, $unit) {

        // 指定のギミックがもう死んでいるなら何もしない。
        if( !array_key_exists($name, $this->state['gimmicks']) )
            return false;

        // 指定されたギミックを取得して、ギミック名を "name" キーに格納する。
        $gimmick = $this->state['gimmicks'][$name];
        $gimmick['name'] = $name;

        // "touch" を処理する。
        foreach((array)$gimmick['touch'] as $touch)
            $this->triggerGimmick($leads, $touch, $unit);

        // "ignition" の条件を満たしていない場合は無視する。
        if( !$this->testCondition($gimmick['ignition'], $gimmick, $unit ? $unit->getCode() : '') )
            return false;

        // 発動⇒終了。
        $swfReturn1 = $this->fireGimmick($leads, $gimmick, $unit);
        $swfReturn2 = $this->closeGimmick($leads, $gimmick, $unit);

        // リターン。
        return $swfReturn1 || $swfReturn2;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたギミックの発動を処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array         ギミック。"name" キーにギミック名が格納されている。
     *                      リファレンスになっているので、後続の処理を変更するために修正することもできる。
     * @param object        ギミックを発動させたユニットのインスタンス。ユニットによらないならnull。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {
        $module = Controller::getInstance()->getContext()->getModuleName();

        // 基底で処理できるなら処理する。
        switch($gimmick['type']) {

            // 指揮
            case 'lead':

                // 埋め込みコードを置き換えて、指揮に追加。
                $leads = array_merge($leads, $this->replaceEmbedCode($gimmick['leads']));

                // SWFへ返すかどうかは設定次第。
                return !empty($gimmick['swf_return']);

            // 寸劇
            case 'drama':

                // シーンを寸劇に変更。
                $this->state['scene'] = 'drama';
                $this->state['scene_id'] = $gimmick['drama_id'];
                $this->state['scene_trigger'] = $gimmick['name'];

                // 寸劇用のフラッシュへの転送指示を出す。
                $leads[] = 'TRANS ' . Common::genContainerUrl(
                    $module, 'FieldDrama', array('sphereId'=>$this->info['sphere_id'], '_nocache'=>true), true
                );

                // SWFへ返す。
                return true;

            // アイテムゲット
            case 'treasure':

                // アイテム取得。
                if( isset($gimmick['item_id']) ) {

                    // アイテムを取得するユニットを取得。
                    // "treasure_catcher" が指定されているならそのユニット、されてないなら起動したユニット。
                    $catcher = $gimmick['treasure_catcher'] ? $this->getUnitByCode($gimmick['treasure_catcher']) : $unit;

                    // 取得。
                    $leads = array_merge($leads, $this->gainTreasure($gimmick['item_id'], $catcher));
                }

                // ゴールド取得。
                if( isset($gimmick['gold']) ) {

                    Service::create('User_Info')->plusValue($this->info['user_id'], array(
                        'gold' => $gimmick['gold']
                    ));

                    $leads[] = sprintf('NOTIF ' . AppUtil::getText("sphere_text_get_gold"), $gimmick['gold']);
                }

                // ビットコイン取得。
                if( isset($gimmick['btc']) ) {

                    Service::create('User_Info')->setVirtualCoin($this->info['user_id'],Vcoin_Flag_LogService::FIELD,$gimmick['btc'],$gimmick['flag_on']);

                    $leads[] = sprintf('NOTIF ' . AppUtil::getText("sphere_text_get_encrypto"), $gimmick['btc']);
                }


                // 装備取得。
                if( isset($gimmick['lv_eqp']) ) {

                    // アイテムを取得するユニットを取得。
                    // "treasure_catcher" が指定されているならそのユニット、されてないなら起動したユニット。
                    $catcher = $gimmick['treasure_catcher'] ? $this->getUnitByCode($gimmick['treasure_catcher']) : $unit;

                    //レベルを取得
                    $level = $catcher->getProperty('level');
                        if($level >= 1 && $level <= 5){
                            //ネコセット
                            $set_id = 10004;
                        }else if($level >= 6 && $level <= 10){
                            //水着セット
                            $set_id = 10005;
                        }else if($level >= 11 && $level <= 20){
                            //ドレスセット
                            $set_id = 10007;
                        }else if($level >= 21 && $level <= 30){
                            //アリスセット
                            $set_id = 10009;
                        }else if($level >= 31 && $level <= 40){
                            //ダサロボセット
                            $set_id = 10012;
                        }else if($level >= 41 && $level <= 50){
                            //踊り子セット
                            $set_id = 10014;
                        }else if($level >= 51 && $level <= 60){
                            //ユニコーンセット
                            $set_id = 10016;
                        }else if($level >= 61 && $level <= 70){
                            //海賊セット
                            $set_id = 10018;
                        }else if($level >= 71 && $level <= 80){
                            //ドラゴンセット
                            $set_id = 10021;
                        }else if($level >= 81 && $level <= 90){
                            //巫女セット
                            $set_id = 10024;
                        }else if($level >= 91 && $level <= 120){
                            //オリハルコンセット
                            $set_id = 10023;
                        }else if($level >= 121 && $level <= 160){
                            //パラディンセット
                            $set_id = 10025;
                        }else{
                            //ダークナイトセット
                            $set_id = 10026;
                        }

                    $mount = mt_rand(1, 4);
                    $item = Service::create('Item_Master')->getSetItem($set_id , $mount);

                    // 取得。
                    $leads = array_merge($leads, $this->gainTreasure($item['item_id'], $catcher));
                }

                // 回復アイテム取得。
                if( isset($gimmick['lv_recv']) ) {

                    // アイテムを取得するユニットを取得。
                    // "treasure_catcher" が指定されているならそのユニット、されてないなら起動したユニット。
                    $catcher = $gimmick['treasure_catcher'] ? $this->getUnitByCode($gimmick['treasure_catcher']) : $unit;

                    //レベルを取得
                    $level = $catcher->getProperty('level');
                    if($level >= 1 && $level <= 26){
                        //くすりびん
                        $item_id = 1001;
                    }else if($level >= 27 && $level <= 59){
                        //キュアハーブス
                        $item_id = 1002;
                    }else if($level >= 60 && $level <= 120){
                        //レキュパレータ
                        $item_id = 1003;
                    }else{
                        //ソーマの薬草
                        $item_id = 1006;
                    }
                    // 取得。
                    $leads = array_merge($leads, $this->gainTreasure($item_id, $catcher));
                }

                return false;

            // 行動pt回復
            case 'ap_recov':

                // 回復。
                $userSvc = new User_InfoService();
                $userSvc->setActionPt($this->info['user_id']);
                $user = $userSvc->needRecord($this->info['user_id']);

                // エフェクト
                $avatarNo = sprintf('%03d', $this->getUnitByCode('avatar')->getNo());
                $leads[] = "FOCUS {$avatarNo}";
                $leads[] = "IPRET " . AppUtil::getText("sphere_text_ap_recover");
                $leads[] = "APDSW 1";
                $leads[] = "DELAY 500";
                $leads[] = "UEFCT {$avatarNo} recov";
                $leads[] = 'ACTPT ' . (int)$user['action_pt'];
                $leads[] = "DELAY 500";
                $leads[] = "IPRET ";
                $leads[] = "APDSW 0";

                return false;

            // HP回復
            case 'hp_recov':

                // 起動したユニットに対して行う。
                if($unit) {

                    $leads[] = sprintf("FOCUS %03d", $unit->getNo());
                    $leads[] = "IPRET " . AppUtil::getText("sphere_text_hp_recover");
                    $leads[] = "DELAY 300";

                    $unit->recoverHp($leads, 500);

                    $leads[] = "IPRET ";
                }

                return false;

            // 戦術アイテムゲット
            case 'ace_card':

                // アイテムを取得するユニットを取得。
                // "treasure_catcher" が指定されているならそのユニット、されてないなら起動したユニット。
                $catcher = $gimmick['treasure_catcher'] ? $this->getUnitByCode($gimmick['treasure_catcher']) : $unit;

                if($catcher)
                    $leads = array_merge($leads, $this->gainAceCard($gimmick['user_item_id'], $catcher));

                return false;

            // ユニットの登場
            case 'unit':

                // ユニットを追加。
                $newUnit = SphereUnit::createDefineUnit($gimmick['unit'], $this);
                $addLeads = $this->addUnit($newUnit);

                // quiet の指定がないならば、ユニット登場の指揮の前後にフォーカスと解説を入れる。
                if( empty($gimmick['quiet']) ) {

                    $pos = $newUnit->getPos();
                    array_unshift($addLeads, sprintf("PFOCS %02d %02d", $pos[0], $pos[1]));

                    $addLeads[] = sprintf("IPRET " . AppUtil::getText("sphere_text_enemy_appear"), $newUnit->getProperty('name'), $newUnit->getProperty('level'));
                    $addLeads[] = 'DELAY 800';
                    $addLeads[] = 'IPRET';
                }

                // 指揮内容に、ユニット登場に関するものを追加。
                $leads = array_merge($leads, $addLeads);

                return false;

            // ユニットのプロパティ変化
            case 'property':

                foreach((array)$gimmick['unit'] as $code) {

                    $unit = $this->getUnitByCode($code);

                    if($unit) {
                        foreach($gimmick['change'] as $name => $value)
                            $unit->setProperty($name, $value);
                    }
                }

                return false;

            // ユニットへのイベントの送信
            case 'unit_event':
            case 'unit_exit':

                // 下位互換を保つため、"unit_exit" を "unit_event" に変換する。
                if($gimmick['type'] == 'unit_exit') {

                    $gimmick['target_unit'] = $gimmick['exit_target'];
                    $gimmick['event'] = array(
                        'name' => 'exit',
                        'reason' => $gimmick['exit_reason'] ?: 'room_exit',
                    );
                }

                // 送信対象のユニットを配列 $targets に取得。
                $targets = array();
                if( isset($gimmick['target_unit']) ) {
                    foreach((array)$gimmick['target_unit'] as $code)
                        $targets[] = $this->getUnitByCode($code);
                }else {
                    $targets[] = $unit;
                }

                // 送信するイベントを作成。ギミックの "event" キーそのままだが、"trigger_unit" は補う。
                $event = $gimmick['event'];
                $event['trigger_unit'] = $unit ? $unit->getNo() : 0;

                // 送信。
                foreach($targets as $target) {
                    if($target)
                        $target->event($leads, $event);
                }

                return false;

            // メソッドコール
            case 'call':

                $method = $gimmick['call'];
                return (bool)$this->$method($leads, $gimmick, $unit);

            // ルームチェンジ
            case 'goto':

                // ルームチェンジフラグを立てる。
                $this->state['change_room'] = array($gimmick['room'], $gimmick['name']);
                return false;

            // クエスト終了／脱出
            case 'goal':
            case 'escape':

                // 下位互換を保つ。
                if($gimmick['type'] == 'goal') {
                    $gimmick['type'] = 'escape';
                    $gimmick['escape_result'] = 'success';
                }

                // "escape_result" 省略時は "escape"。
                if(!$gimmick['escape_result'])  $gimmick['escape_result'] = 'escape';

                // スフィアを閉じるイベントをキュー。
                $this->pushStateEvent(array(
                    'type' => 'close',
                    'result' => constant( 'Sphere_InfoService::' . strtoupper($gimmick['escape_result']) ),
                ));

                return false;

            // マップチップの変更
            case 'square_change':
                $leads[] = $this->map->changeSquare($gimmick['change_pos'], $gimmick['change_tip']);
                return false;

            // 認識不能なものは基底では処理しない。
            default:
                return false;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたギミックの発動終了を処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array         ギミック。"name" キーにギミック名が格納されている。
     *                      リファレンスになっているので、後続の処理を変更するために修正することもできる。
     * @param object        ギミックを発動させたユニットのインスタンス。ユニットによらないならnull。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function closeGimmick(&$leads, &$gimmick, $unit) {

        // 戻り値初期化。
        $swfReturn = false;

        // "lasting" が設定されてないならギミックを削除。
        if( (int)$gimmick['lasting'] <= 1 ) {

            $this->removeGimmick( $gimmick['name'] );

            // 置物が関連付けられている場合は合わせて削除
            if(isset($gimmick['ornNo'])) {
                foreach($gimmick['ornNo'] as $ornNo)
                    $leads[] = $this->map->removeOrnament($ornNo);
            }

        // "lasting" が設定されているならカウントダウンする。
        }else {
            $this->state['gimmicks'][ $gimmick['name'] ]['lasting']--;
        }

        // 永続フラグをONにするよう指示されている場合はONにする。
        if( !empty($gimmick['flag_on']) )
            Service::create('Flag_Log')->flagOn(Flag_LogService::FLAG, $this->info['user_id'], $gimmick['flag_on']);

        // メモリフラグをONにするよう指示されている場合はONにする。
        if( !empty($gimmick['memory_on']) )
            $this->state['memory'][ $gimmick['memory_on'] ] = true;

        // ギミックにchainが設定されている場合は起動する。
        if( isset($gimmick['chain']) ) {
            foreach( (array)$gimmick['chain'] as $chain )
                $swfReturn = $swfReturn || $this->triggerGimmick($leads, $chain, $unit);
        }

        // ギミックにchain_delayedが設定されている場合はイベントをキューに。
        if( isset($gimmick['chain_delayed']) ) {
            foreach( (array)$gimmick['chain_delayed'] as $chain ) {
                $this->pushStateEvent(array(
                    'type' => 'gimmick',
                    'name' => $chain,
                    'trigger' => $unit ? $unit->getNo() : 0,
                ));
            }
        }

        // リターン。
        return $swfReturn;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの行動前フェーズを処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressPreComm(&$leads) {

        // ユニットの行動を決定させる。
        $this->state['command'] = $this->getUnit()->decideCommand($leads);

        // 次のフェーズへ。
        $this->phaseNext($leads);
        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの行動フェーズを処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressCommand(&$leads) {

        // コマンドをユーザに決めてもらう必要がある場合はSWFへ返す。
        if( is_null($this->state['command']) ) {
            $leads[] = 'COMND ' . $this->getUnit()->getNo();
            return true;
        }

        // 戻り値初期化。
        $swfReturn = false;

        // コマンドと、コマンド対象になっているユニットを取得。
        $command = $this->state['command'];
        $commUnit = $this->getUnit();

        // なにはともあれフォーカスする。
        $leads[] = sprintf('FOCUS %03d', $commUnit->getNo());
        $leads[] = 'DELAY 200';

        // 移動する場合。
        if( isset($command['move'])  &&  $command['move']['to'] != $commUnit->getPos() ) {

            // pathが省略されているなら補う。
            if( !isset($command['move']['path']) )
                $this->map->getRoute($commUnit, $command['move']['to'], $command['move']['path']);

            // 移動。
            if(strlen($command['move']['path']) > 0) {
                $commUnit->setPos($command['move']['to']);
                $leads[] = sprintf('UMOVE %03d %s', $this->state['act_unit'], $command['move']['path']);
            }

            // プレイヤーオーナーのユニットならば暗幕解除の判定を行う。
            if( $commUnit->getProperty('player_owner') ) {
                foreach($this->map->findCurtainOn($command['move']['to']) as $curtainName)
                    $this->openCurtain($leads, $curtainName, $commUnit);
            }
        }

        // 「攻撃」コマンドの場合はそれ用の処理を行う。
        if( isset($command['attack']) )
            $swfReturn = $this->processAssault($leads, $this->getUnit($command['attack']));

        // アイテムを使用する場合。
        if( isset($command['use']) ) {

            // アイテム欄から装備品を使おうとしている場合は装備変更として扱う。
            // それ以外はアイテム効果の発揮。
            if($command['use']['page'] == 'item'  &&  Item_MasterService::isDurable($command['use']['uitem']['category']))
                $swfReturn = $this->processEquipChange($leads, $command['use']);
            else
                $swfReturn = $this->processItem($leads, $command['use']);
        }

        // 移動もしない完全待機の場合は遅延を追加。
        if(!$command)
            $leads[] = 'DELAY 300';

        // フェーズを移してリターン。
        $this->phaseNext($leads);
        return $swfReturn;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された暗幕のオープンを処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param string        オープンする暗幕名
     * @param object        オープンしたユニット
     */
    protected function openCurtain(&$leads, $name, $openerUnit) {

        // 暗幕を取り除く。
        $leads = array_merge($leads, $this->map->removeMat($name));

        // 指定された暗幕をトリガとするギミックがないかチェック。
        $this->checkGimmickByCondition('curtain', $name, $openerUnit);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットのバトル挑戦を処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param object        バトルを仕掛けられたユニット。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function processAssault(&$leads, $defender) {
        $module = Controller::getInstance()->getContext()->getModuleName();

        $challenger = $this->getUnit();

        // どちらかが手動キャラの場合はメインバトル
        if($challenger->getProperty('act_brain') == 'manual'  ||  $defender->getProperty('act_brain') == 'manual') {

            // シーンをバトルに変更。
            $this->state['scene'] = 'battle';
            $this->state['scene_id'] = $defender->getNo();
            $this->state['scene_trigger'] = $this->state['act_unit'];

            // battle_logレコードを作成する。
            $battleId = $this->makeMainBattle();

            //戦闘時は向き合わせる
            $align = $this->getFaceToFace($leads, $challenger, $defender);

            //戦闘へ行くサウンドを鳴らす
            $leads[] = 'SEPLY ' . 'se_gotoquest';
            //演出
            $leads[] = sprintf('BTEFF %03d %s', $challenger->getNo(), $align[0]);
            $leads[] = 'DELAY 1000';

            // バトルフラッシュへの転送指示を出す。
            $leads[] = 'TRANS ' . Common::genContainerUrl($module, 'Battle', array('battleId'=>$battleId), true);
            return true;

        // 両方とも自動キャラの場合はスキップバトル
        }else {

            $this->processSkipBattle($leads, $challenger, $defender);
            return false;
        }
    }

    /*
     * unit1とunit2が向き合うコマンドを出す。
     * 隣接していない場合は向き合わないでいい。
     * 0:正面 1:左 2:右 3:後ろ
    */
    public function getFaceToFace(&$leads, $unit1, $unit2) {
        $c_pos = $unit1->getPos();
        $d_pos = $unit2->getPos();

        //両方同じX座標にいる場合
        if($c_pos[0] == $d_pos[0]){
            //挑戦者の方が1つ下にいるなら・・
            if($c_pos[1] == ($d_pos[1] + 1)){
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 3);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 0);
                return array(3,0);
            //挑戦者の方が1つ上にいるなら・・
            }else if(($c_pos[1] + 1) == $d_pos[1] ){
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 0);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 3);
                return array(0,3);
            }
        //両方同じY座標にいる場合
        }else if($c_pos[1] == $d_pos[1]){
            //挑戦者の方が1つ右にいるなら・・
            if($c_pos[0] == ($d_pos[0] + 1)){
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 1);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 2);
                return array(1,2);
            //挑戦者の方が1つ左にいるなら・・
            }else if(($c_pos[0] + 1) == $d_pos[0]){
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 2);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 1);
                return array(2,1);
            }
        }
    }

    /*
     * unit1とunit2が向き合うコマンドを出す。
     * 隣接していない場合でもなんとなく向き合うようにする。
     * 0:正面 1:左 2:右 3:後ろ
    */
    public function getFaceToFace2(&$leads, $unit1, $unit2) {
        $c_pos = $unit1->getPos();
        $d_pos = $unit2->getPos();

        //両方同じY座標にいる場合
        if($c_pos[1] == $d_pos[1]){
            //avatarの方が右にいるなら・・
            if($c_pos[0] > $d_pos[0]){
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 1);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 2);
            }else{
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 2);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 1);
            }                        
        }else{
            //avatarの方が下にいるなら・・
            if($c_pos[1] > $d_pos[1]){
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 3);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 0);
            }else{
                $leads[] = sprintf('UALGN %03d %s', $unit1->getNo(), 0);
                $leads[] = sprintf('UALGN %03d %s', $unit2->getNo(), 3);
            }
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備の変更を処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array         ユニットコマンドの "use" キーの値。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function processEquipChange(&$leads, $use) {

        // ユニットに装備を変更させる。
        $changeLeads = $this->getUnit()->changeEquipment($use['slot']);

        // 成否によって、メッセージを発行。
        if($changeLeads) {
            $leads = array_merge($leads, $changeLeads);
            $leads[] = 'NOTIF ' . AppUtil::getText("sphere_text_change_equip");
        }else {
            $leads[] = 'NOTIF ' . AppUtil::getText("sphere_text_not_change_equip");
        }

        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムの使用を処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array         ユニットコマンドの "use" キーの値。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function processItem(&$leads, $use) {

        // アイテム使用の効果を処理する。
        $leads[] = sprintf('FOCUS %03d', $this->getUnit()->getNo());
        $this->fireItem($leads, $use['uitem'], $use['to'], $this->getUnit());

        // 装備でなくアイテムの場合は消費する。
        // ユニットデータから該当のアイテムを消去。それをSWFに伝えるための指揮を追加。
        if($use['page'] == 'item')
            $leads[] = $this->getUnit()->lostItem($use['slot']);

        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたアイテムが、指定された座標へ使用されたときの処理を行う。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array         Item_MasterService、あるいは User_ItemService から取得したレコード。
     * @param array         使用先の座標。
     * @param object        アイテムを使ったユニット。トリガーになったユニットがいない場合はnull。
     */
    public function fireItem(&$leads, $item, $to, $firer = null) {

        // 効果範囲になる座標をすべて取得。
        $range = $this->map->getMovables($to, $item['item_spread']);

        // ユニットの所在マップを取得。
        $unitMap = $this->map->getUnitMap();

        // 効果範囲になっているマスを一つずつ処理する。
        $poses = '';
        $exposeUnits = array();
        foreach($range as $y => $line) {
            foreach($line as $x => $dummy) {

                // エフェクトをセットする座標を列挙する文字列を作成していく。
                $poses .= sprintf('%02d%02d', $x, $y);

                // そこにユニットがいる場合は覚えておく。
                if( isset($unitMap[$y][$x]) )
                    $exposeUnits[] = $unitMap[$y][$x];
            }
        }

        // 効果の解説指揮
        $leads[] = 'IPRET ' . $item['item_name'];
        $leads[] = 'DELAY 500';

        // 地形破壊効果がある場合は地形を変更する。
        if($item['item_flags'] & Item_MasterService::DESTRUCT) {
            $region = $this->map->getMovables($to, $item['item_spread']);
            $leads = array_merge($leads, $this->map->changeSquareOnRegion($region, $this->destructedId));
        }

        // 振動つきの場合は振動をかける。
        if($item['item_flags'] & Item_MasterService::VIB_EFFECT)
            $leads[] = 'VIBRA 03';

        // 効果範囲にエフェクトを再生する指揮を発行
        $leads[] = sprintf('EFFEC %s %s', $this->getItemVfx($item), $poses);

        // 振動つきの場合は振動を止める。
        if($item['item_flags'] & Item_MasterService::VIB_EFFECT)
            $leads[] = 'VIBRA 00';

        // 効果対象になったユニットに効果をもたらす。
        foreach($exposeUnits as $unit)
            $unit->exposeItem($leads, $item, $firer);

        // 解説表示の終了指揮をセットする。
        $leads[] = 'IPRET';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの行動終了フェーズを処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressAfterComm(&$leads) {

        // ギミックをチェック。
        $this->checkGimmickByUnit($this->getUnit(), !isset($this->state['command']['move']));

        // ユニットに行動終了フェーズであることを通知。
        $this->getUnit()->afterCommand($leads);

        // 次のフェーズへ。
        $this->phaseNext($leads);
        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットのターンエンドフェーズを処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressTurnEnd(&$leads) {

        // 基底で特にすることはない。次のフェーズへ。
        $this->phaseNext($leads);
        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユニットを消去する。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param int       ユニット番号。
     * @param string    退出理由。標準では、"collapse":死亡、"room_exit":撤退・逃亡 のいずれか。
     * @param object    退出のきっかけになったユニット。いない場合は null。
     *                  退出したユニットではないことに注意。
     * @return object   退出したユニット。なんらかの理由で退出させなかった場合はnull。
     *                  このユニットはすでにスフィアから削除されていることに注意。
     */
    public function removeUnit(&$leads, $unitNo, $reason, $triggerUnit = null) {

        // 消去しようとしているユニットを取得。
        $unit = $this->units[$unitNo];

        // ユニットに "early_gimmick", "trigger_gimmick" が設定されている場合は起動。
        $this->kickGimmick($leads, $unit->getProperty('early_gimmick'), $triggerUnit, true);
        $this->kickGimmick($leads, $unit->getProperty('trigger_gimmick'), $triggerUnit);

        // ユニットを削除。
        unset($this->units[$unitNo]);

        // スフィアSWFにユニット退出の指揮を送る。
        $leads[] = sprintf('UEXIT %03d collap', $unitNo);

        // 主人公ユニットである場合はスフィアクローズ。
        if($unit->getCode() == 'avatar') {
            $event = array(
                'type' => 'close',
                'result' => Sphere_InfoService::FAILURE,
            );
            $this->pushStateEvent($event, true);
        }

        // ユニット削除によるギミック発動がないかチェック。
        if( $unit->getCode() )
            $this->checkGimmickByCondition('unit_exit', $unit->getCode(), $triggerUnit);

        // 主人公側所属でないならば、打倒カウントアップ。
        if($unit->getUnion() != 1) {

            $this->state['termination']++;
            $this->state['termination_all']++;

            // 打倒数によるギミック発動がないかチェック。
            $this->checkGimmickByCondition('termination', $this->state['termination'], $triggerUnit);
        }

        // 削除したユニットをリターン。
        return $unit;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * スフィアの終了処理を行う。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param int           結果コード。Sphere_InfoServiceの定数を使用する。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressClose(&$leads, $resultCode) {

        // スフィアの終了処理。
        Service::create('Sphere_Info')->closeSphere($this->info['sphere_id'], $resultCode);
        $this->info['result'] = $resultCode;

        // クエストを成功終了しておりミッションがあるなら、達成をチェック。
        if($this->state['mission_exists'] &&  ($resultCode == Sphere_InfoService::SUCCESS  ||  $resultCode == Sphere_InfoService::ESCAPE)) {
            if( $this->checkAchievement($resultCode) )
                $this->progressAchievement($leads);
        }

        $module = Controller::getInstance()->getContext()->getModuleName();

        // フィールドクエスト終了画面へ遷移させる指揮を発行。
        $leads[] = 'TRANS ' . Common::genContainerUrl(
            $module, 'FieldEnd', array('sphereId'=>$this->info['sphere_id']), true
        );

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ミッション達成時の報酬付与を行う。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     */
    protected function progressAchievement(&$leads) {

        // ミッションを達成したマークをつける。
        $this->state['mission_achieve'] = true;

        // 基底の実装は、メンバ変数 missionReward で設定された分だけお金を付与する。
        Service::create('User_Info')->plusValue($this->info['user_id'], array(
            'gold' => $this->missionReward,
        ));

        // 達成回数カウントアップ。
        Service::create('Flag_Log')->countUp(Flag_LogService::MISSION, $this->info['user_id'], $this->info['quest_id']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ターンを次のフェーズへ移す。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     */
    protected function phaseNext(&$leads) {

        switch($this->state['act_phase']) {
            case 'precomm':
                $this->state['act_phase'] = 'command';
                break;
            case 'command':
                $this->state['act_phase'] = 'aftercomm';
                break;
            case 'aftercomm':
                $this->state['act_phase'] = 'turnend';
                break;
            case 'turnend':
            default:
                $this->turnNext($leads);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ターンを次のユニットに回す。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     */
    protected function turnNext(&$leads) {

        // 次のユニットへ。
        $this->state['act_unit'] = $this->getNextUnit();
        $this->state['act_phase'] = 'precomm';
        $turnUnit = $this->getUnit();

        // 主人公ユニットにターンが回ってきている場合は全体ターン数を+1
        if($turnUnit->getCode() == 'avatar')
            $this->rotateNext($leads);

        // ユニットの経過ターン数を+1。
        $turnUnit->plusTurn();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ローテーション(全体ターン数)を+1する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     */
    protected function rotateNext(&$leads) {

        $this->state['rotation']++;
        $this->state['rotation_all']++;

        // ローテーションによるギミック発動がないかチェックする。
        $this->checkGimmickByCondition('rotation', $this->state['rotation']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 次にターンが回るユニットの番号を返す。
     */
    protected function getNextUnit() {

        // ユニット番号の一覧を取得。昇順に並べる。
        $nos = array_keys($this->units);
        sort($nos, SORT_NUMERIC);

        // 現在のユニット番号より後ろで、一番前方にあるものを次のユニットとする。
        foreach($nos as $unitNo) {
            if($unitNo > $this->state['act_unit'])
                return $unitNo;
        }

        // 一番最後のユニットが現在のユニットである場合は、先頭に戻る。
        return reset($nos);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * クエストを成功終了しておりミッションがある場合に、達成したかどうかを返す。
     *
     * @param int       結果コード。Sphere_InfoServiceの定数を使用する。
     * @return bool     達成しているならtrue、していないならfalse。
     */
    protected function checkAchievement($resultCode) {

        // 基底ではミッションを定めていないので、常にミッション失敗とする。
        // ミッションを定めるクエストではオーバーライドする。
        return false;
    }


    // ユーティリティ系のメソッド。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたステートイベントをキューに追加する。
     *
     * @param array     ステートイベント
     * @param bool      キューの先頭に追加するなら true を指定する。
     */
    public function pushStateEvent($event, $first = false) {

        if($first)
            array_unshift($this->state['queue'], $event);
        else
            $this->state['queue'][] = $event;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 特定の条件によるギミック発動がないかチェックする。
     *
     * @param string    条件の種類。ギミックの "trigger" キーの値。
     * @param mixed     条件の値。
     * @param object    条件を成立させたユニットがいるなら指定する。
     *
     * 例) "trigger"キーが "rotation"、"rotation"キーが 3 のギミックをチェックする。
     *         $this->checkGimmickByCondition('rotation', 3);
     */
    protected function checkGimmickByCondition($trigger, $value, $unit = null) {

        // ギミックを一つずつ見ていく。
        foreach($this->state['gimmicks'] as $name => $gimmick) {

            // 指定された要素をトリガとしないならスキップ
            if(!isset($gimmick['trigger'])  ||  $gimmick['trigger'] != $trigger)
                continue;

            // 対象の値ならイベントキューにギミックの発動をセット。
            // 指定のキーをギミックが持っていない場合もある。その場合はいかなる場合も起動しない。
            // 下記のコードはそれに対応していることに留意。
            if($gimmick[$trigger] == '*'  ||  in_array($value, (array)$gimmick[$trigger])) {
                $this->pushStateEvent(array(
                    'type' => 'gimmick',
                    'name' => $name,
                    'trigger' => $unit ? $unit->getNo() : 0,
                ));
            }
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの進入によるギミック発動がないかチェックする。
     *
     * @param object    進入しているかもしれないユニットのインスタンス。
     * @param bool      ユニットが移動を行わず留まっているならtrue。
     */
    protected function checkGimmickByUnit($unit, $stayPos = false) {

        // 対象ユニットがいる座標にあるギミックのインデックスをすべて取得。
        $gimmickNames = $this->map->findOn($unit->getPos(), $this->state['gimmicks']);

        // 対象ユニットかどうか確認してしていく。
        foreach($gimmickNames as $name) {

            $gimmick = $this->state['gimmicks'][$name];

            // ユニットが留まっている場合は、"always" フラグがないギミックは無視する。
            if($stayPos && empty($gimmick['always']))
                continue;

            // 対象ユニットでないなら次へ。
            switch(isset($gimmick['trigger']) ? $gimmick['trigger'] : '') {
                case 'hero':
                    if($unit->getCode() != 'avatar')
                        continue 2;
                    break;
                case 'player':
                    if( !$unit->getProperty('player_owner') )
                        continue 2;
                    break;
                case 'all':
                    break;
                case 'unit_into':
                    if( !in_array($unit->getCode(), (array)$gimmick['unit_into']) )
                        continue 2;
                    break;
                default:
                    continue 2;
            }

            // イベントキューにギミック発動をセット。
            $this->pushStateEvent(array(
                'type' => 'gimmick',
                'name' => $name,
                'trigger' => $unit->getNo(),
            ));
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に与えられたアイテムの視覚効果名を返す。
     *
     * @param array     Item_MasterService、あるいは User_ItemService から取得したレコード。
     */
    protected function getItemVfx($item) {

        // 4文字でないといけないことに注意。
        static $effects = array('recv', 'bomb', '', 'sprk', 'shck', 'migt');

        $effectNo = ($item['item_type'] == Item_MasterService::RECV_HP) ? 0 : $item['item_vfx'];

        return isset($effects[$effectNo]) ? $effects[$effectNo] : 'unknown';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で受け取った指揮に含まれる埋め込みコードを実際に使用されている値に置き換える。
     * 具体的には以下の処理を行う。
     *     "%xxx%" という文字列を、"xxx" というコードをもつユニットの番号に置き換える。
     *     "[NAME]" という文字列を、プレイヤーアバタの名前に置き換える
     *     RPBG1 のチップ番号を、FLASHが使用している番号に置き換える。
     *
     * @param array     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return array    代替文字列をユニット番号に置き換えたもの。
     */
    protected function replaceEmbedCode($leads) {

        // ユニットのコードと番号の対応表を取得する。
        $map = array();
        foreach($this->getUnitCodeMap() as $code => $no)
            $map["%{$code}%"] = sprintf('%03d', $no);

        $_leads = array();

        // 置き換え。
        foreach($leads as $lead) {

            // "[NAME]" が含まれており、まだプレイヤーアバタの名前を取得していないなら取得しておく。
            if(strpos($lead, '[NAME]') !== false  &&  !isset($map['[NAME]'])) {
                $avatar = Service::create('Character_Info')->needAvatar($this->info['user_id']);
                $map['[NAME]'] = Text_LogService::get($avatar['name_id']);
            }

            // "%xxx%" と "[NAME]" の置き換え。
            $lead = strtr($lead, $map);

            // あとはコマンドごとに...
            switch( substr($lead, 0, 5) ) {

                // RPBG1 コマンドの場合はチップ番号を実際の番号に置き換える。
                case 'RPBG1':

                    $tipNo = (int)substr($lead, 13);
                    $swfNo = $this->map->getSwfTipNo($tipNo);

                    $lead = substr($lead, 0, 12) . sprintf('%04d', $swfNo);
                    $_leads[] = $lead;
                    break;
                case 'AALGN':
                    $align = explode(" ", $lead);
                    $unit1 = $this->getUnit((int)$align[1]);
                    $unit2 = $this->getUnit((int)$align[2]);
                    //leadは初期化してコマンド書き換え
                    $lead = array();
                    //向き合わせる
                    $this->getFaceToFace2($_leads, $unit1, $unit2);
                    break;
                default:
                    $_leads[] = $lead;
            }

        }

        // リターン。
        return $_leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で受け取った指揮から "NOTIF", "LINES", "SPEAK" などのユーザにテキストを見せるコマンドを
     * 抽出して、それを文字列にまとめて返す。
     *
     * @param array     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return string   テキストを見せるコマンドをまとめた文字列。
     */
    protected function extractText($leads) {

        // 戻り値初期化。
        $result = '';

        // 渡された指揮を一つずつ見ていく。
        foreach($leads as $lead) {

            // テキスト系のコマンドからテキストを抽出。
            switch(substr($lead, 0, 5)) {
                case 'NOTIF':
                    $text = substr($lead, 6);
                    break;
                case 'LINES':
                    $text = substr($lead, 10);
                    break;
                case 'SPEAK':
                    $text = substr(strstr(substr($lead, 10), ' '), 1);
                    break;
                default:
                    continue 2;
            }

            // 戻り値に追加。
            $result .= $text . "\n\n";
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * クエストの実行主のユーザに、引数で指定されたアイテムを付与する。
     *
     * @param int       アイテムID
     * @param object    アイテム欄への追加を受けるSphereUnitオブジェクト。
     *                  指定しなかった場合、ユニットアイテム欄への追加は行われない。
     * @param bool      true を指定すると、アイテム入手をユーザに伝える指揮を発行しない。
     * @return array    アイテムの入手をSWFに伝えるのに必要な指揮の配列。
     */
    protected function gainTreasure($itemId, $unit = null, $quiet = false) {

        $uitemId = Service::create('User_Item')->gainItem($this->info['user_id'], $itemId);

        return $this->processTreasure($uitemId, $unit, $quiet);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザ所持アイテムをトレジャーとして処理する。
     *
     * @param int       user_item_id
     * @param object    アイテム欄への追加を受けるSphereUnitオブジェクト。
     *                  指定しなかった場合、ユニットアイテム欄への追加は行われない。
     * @param bool      true を指定すると、アイテム入手をユーザに伝える指揮を発行しない。
     * @return array    アイテムの入手をSWFに伝えるのに必要な指揮の配列。
     */
    protected function processTreasure($uitemId, $unit = null, $quiet = false) {

        $leads = array();

        // アイテムの情報を取得。
        $uitem = Service::create('User_Item')->needRecord($uitemId);

        // クエスト中に手に入れたアイテムとして記憶。
        $this->state['treasures'][] = $uitem['item_id'];

        // 指定されたユニットがプレイヤー配下のユニットであり、指定されたアイテムがクエスト中に使用可能
        // あるいは装備品である場合は、ユニットに所持させる。
        $carryLeads = array();
        if($unit  &&  $unit->getProperty('player_owner')) {
            if( in_array($uitem['item_type'], Item_MasterService::$ON_FIELD)  ||  Item_MasterService::isDurable($uitem['category']) ) {
                $carryLeads = $unit->supplyItem($uitemId);
                $leads = array_merge($leads, $carryLeads);
            }
        }

        // アイテム入手のメッセージを作成。
        if(!$quiet) {

            // すぐにアイテム欄に所持しなかったのなら、「ストックへ」のメッセージも追加。
            $lead = sprintf(AppUtil::getText("sphere_text_itemget_message"), $uitem['item_name']);
            if(!$carryLeads)
                $lead .= "\n" . AppUtil::getText("sphere_text_sendstock");

            $leads[] = $lead;
        }

        // リターン。
        return $leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザ所持アイテムを、指定されたユニットに付与する。
     * gainTreasure() と違ってこのクエスト限定のアイテムなので、user_itemへの追加を行わない。
     *
     * @param int       ユーザアイテムID
     * @param object    アイテム欄への追加を受けるSphereUnitオブジェクト。
     * @param bool      true を指定すると、アイテム入手をユーザに伝える指揮を発行しない。
     * @return array    アイテムの入手をSWFに伝えるのに必要な指揮の配列。
     */
    protected function gainAceCard($uitemId, $unit, $quiet = false) {

        // 指定されたユニットにアイテム追加。
        $leads = $unit->supplyItem($uitemId);

        // アイテム入手のメッセージを作成。
        if(!$quiet) {
            $uitem = Service::create('User_Item')->needRecord($uitemId);
            $leads[] = sprintf(AppUtil::getText("sphere_text_itemget_message_only"), $uitem['item_name']);
        }

        // リターン。
        return $leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * scene_id, scene_trigger にセットされた値でbattle_logを作成する。
     *
     * @return int      作成したbattle_logのbattle_id
     */
    protected function makeMainBattle() {

        // 仕掛けた側と仕掛けられた側のcharacter_infoを取得。
        $challenger = $this->getUnit( $this->state['scene_trigger'] );
        $defender = $this->getUnit( $this->state['scene_id'] );

        // プレイヤーコントロールユニットは防衛側かどうかを取得。
        $defenceSide = ($challenger->getProperty('act_brain') != 'manual'  &&  $defender->getProperty('act_brain') == 'manual');

        // バトルレコードの作成。
        $battleUtil = new FieldBattleUtil();
        return $battleUtil->createBattle(array(
            'challenger' => $challenger->getAllProperty(),
            'defender' => $defender->getAllProperty(),
            'player_id' => $this->info['user_id'],
            'side_reverse' => $defenceSide,
            'relate_id' => $this->info['sphere_id'],
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * スキップバトルの処理を行う。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param object        バトルを仕掛けたユニット。
     * @param object        バトルを仕掛けられたユニット。
     */
    protected function processSkipBattle(&$leads, $challenger, $defender) {

        // 省略バトルでダメージを計算する。
        $damage = FieldBattleUtil::omissionBattle($challenger->getAllProperty(), $defender->getAllProperty());

        // バトル結果をイベントとしてキュー。
        $this->pushStateEvent(array(
            'type' => 'battle2',
            'regular' => false,
            'challenger' => $challenger->getNo(),
            'defender' => $defender->getNo(),
            'total' => $damage,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * スフィアの状態を保存するためのデータ構造を返す。
     */
    protected function getRecord() {

        // 基本的には $this->info がレコードの形に近い。
        $record = $this->info;

        // "state" キーは参照になっているのでこれを解除する。
        unset($record['state']);
        $record['state'] = $this->state;

        // ユニット配列を保存可能なデータに変換する。
        $record['state']['units'] = array();
        foreach($this->units as $unit)
            $record['state']['units'][ $unit->getNo() ] = $unit->getData();

        // マップデータを structure, mats, maptips, ornaments キーに変換する。
        $record['state']['structure'] = $this->map->getStructure();
        $record['state']['background'] = $this->map->getBackground();
        $record['state']['overlayer1'] = $this->map->getOverlayer1();
        $record['state']['overlayer2'] = $this->map->getOverlayer2();
        $record['state']['cover'] = $this->map->getCover();
        $record['state']['head'] = $this->map->getHead();
        $record['state']['left'] = $this->map->getLeft();
        $record['state']['right'] = $this->map->getRight();
        $record['state']['foot'] = $this->map->getFoot();
        $record['state']['maptips'] = $this->map->getMapTips();
        $record['state']['mats'] = $this->map->getMats();
        $record['state']['ornaments'] = $this->map->getOrnaments();

        // リターン。
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 保持しているスフィアの状態をDBに保存する。
     *
     * @param array     ここに指定した状態から変化がある場合のみ保存する。
     *                  省略時は無条件で保存。
     * @return bool     保存したならtrue、しなかったのならfalse。
     */
    protected function save($ifChanged = null) {

        // 現在の状態を保存可能なレコードの形で取得。
        $record = $this->getRecord();

        // 引数が指定されていて、それが現在の状態と同じなら保存しない。
        if($ifChanged  &&  $ifChanged == $record)
            return false;

        $svc = new Sphere_InfoService();

        // リビジョン+1。
        $record['revision']++;
        $this->info['revision'] = $record['revision'];

        // 保存。まだスフィアIDが決まっていない場合。
        if(!$record['sphere_id']) {

            // INSERT。発行されたスフィアIDを取得。
            unset($record['sphere_id']);
            $sphereId = $svc->insertRecord($record, true);
            $this->info['sphere_id'] = $sphereId;

        // スフィアIDが決まっている場合。
        }else {
            $svc->updateState(
                $record['sphere_id'], $record['state'], $record['revision']
            );
        }

        // 保存したことを表す値でリターン。
        return true;
    }
}
