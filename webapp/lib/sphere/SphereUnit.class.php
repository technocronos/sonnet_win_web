<?php

/**
 * SphereCommon付属のクラス。
 * スフィアのユニットに関するメソッドを提供する。
 */
class SphereUnit {

    // 主人公ユニットの標準的な移動力
    const HERO_MOVE_POW = 40;


    // 静的メンバ。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたcharacter_infoからユニット情報を作成する。
     *
     * @param int       キャラクタID
     * @param array     持っていくユーザ所持アイテムのID
     * @return array    ユニットの情報を持った連想配列。
     *                  ユニットオブジェクトではなくて、まだデータの段階であることに注意。
     */
    public static function makeUnitData($charaId, $uitemIds) {

        $chara = Service::create('Character_Info')->needRecord($charaId);

        // 所持アイテムIDのカラの値を 0 に統一する。
        foreach($uitemIds as &$id)
            if(!$id) $id = 0;

        return array(
            'code' => '',
            'name' => Text_LogService::get($chara['name_id']),
            'union' => 1,
            'icon' => 'avatar',
            'move_pow' => self::HERO_MOVE_POW,
            'act_brain' => 'manual',
            'battle_brain' => 50,
            'player_owner' => true,
            'room_takeover' => true,
            'character_id' => $charaId,
            'hp' => (int)$chara['hp_max'],
            'items' => $uitemIds,
            'turn' => 0,
            'transcend_adapt' => false,
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フィールドマスタのユニット定義からユニットを作成する。
     *
     * @param array     フィールドマスタのユニット定義
     * @param object    このユニットの所属先になっている SphereCommon インスタンス。
     * @return object   このクラス、あるいはそこから派生したクラスのオブジェクト。
     */
    public static function createDefineUnit($define, $sphere) {

        // キャラクターレコードを取得。
        $chara = Service::create('Character_Info')->needRecord($define['character_id']);

        // ユニットとして必要な情報のうち、ユニット定義には存在しないかもしれないものを作成。
        $default = array();
        $default['code'] = '';
        $default['name'] = Text_LogService::get($chara['name_id']);
        $default['union'] = 2;
        $default['icon'] = 'shadow';
        $default['act_brain'] = 'generic';
        $default['player_owner'] = false;
        $default['room_takeover'] = false;
        $default['hp_max'] = (int)$chara['hp_max'];     // 定義ユニットの hp_max がクエスト中に変化するとは考えにくいので、$this->dataにとっておくようにする。
        $default['sequip'] = array();                   // 定義ユニットは通常character_equipmentに装備を持っていないので、いちいちDBを見に行かないようにする。
        $default['items'] = array();
        $default['turn'] = 0;
        $default['transcend_adapt'] = true;

        // フィールド用追加情報を取得。
        $plus = Service::create('Unit_Master')->getInfo($define['character_id']);

        // 基本的には定義されている値をそのまま使う。
        $data = $define + $plus + $default;

        // level と hp-max に対してレベル補正をかける。
        if($data['transcend_adapt']) {
            $transcendLevel = $sphere->getTranscendLevel();
            $data['level'] = $chara['level'] + $transcendLevel;
            $data['hp_max'] += (int)($transcendLevel * 2.88);
        }

        // level と hp-max に対してレベル補正をかける。add_level用
        if($data['add_level']) {
            $data['level'] += (int)$data['add_level'];
            $data['hp_max'] += (int)($data['add_level'] * 2.88);
		}

        // この時点でまだHPが決まっていないなら、HP-MAXからとる。
        if( is_null($data['hp']) )
            $data['hp'] = $data['hp_max'];

        // リターン
        return self::load($data, $sphere);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットデータを直接受け取って、このクラスのインスタンスを作成する。
     *
     * @param array     このユニットに関する情報を収めた連想配列。
     * @param object    このユニットの所属先になっている SphereCommon インスタンス。
     * @return object   このクラス、あるいはそこから派生したクラスのオブジェクト。
     */
    public static function load($data, $sphere) {

        $className = self::getClassName($data);

        return new $className($data, $sphere);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトル時に、プレイヤーがこのユニットと対峙したときのナビの台詞セットを返す。
     *
     * @param array     このユニットのバトルデータを収めた連想配列。
     * @return array    ナビの台詞セット。BattleCommon::getLineSet() と同様。
     *                  特に特殊な台詞をセットしないならnull。
     */
    public static function getBattleLines($battleData) {

        $className = self::getClassName($battleData);

        return $className::defineBattleLines($battleData);
    }

    /**
     * ナビの台詞セットをカスタムしたいときにオーバーライドする。
     */
    protected static function defineBattleLines($battleData) {

        return null;
    }


    // 参照系のメソッド
    //=====================================================================================================

    /**
     * ユニットの番号を返す。
     */
    public function getNo() {

        return $this->data['no'];
    }

    /**
     * ユニットのコードネームを返す。
     */
    public function getCode() {

        return $this->data['code'];
    }

    /**
     * ユニットの所属番号を返す。
     */
    public function getUnion() {

        return $this->data['union'];
    }

    /**
     * ユニットの現在の座標を返す。
     */
    public function getPos() {

        return $this->data['pos'];
    }

    /**
     * ユニットの現在の向きを返す。
     */
    public function getAlign() {

        return $this->data['align'];
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたプロパティの値を返す。
     * ほとんどすべてのプロパティが一度に必要な場合は getAllProperty() を使用する。
     *
     * @param string    プロパティの名前。
     * @return mixed    指定されたプロパティの値。
     */
    public function getProperty($propName) {

        // $this->dataにあるならそこから。
        if( array_key_exists($propName, $this->data) )
            return $this->data[$propName];

        // ないなら、適切な場所から取得する。
        switch(true) {

            // "sequip" の場合。
            case $propName == 'sequip':
                return $this->getEquipPage();

            // character_info の列、および "name"。
            case in_array($propName, array('character_id', 'user_id', 'entry', 'race', 'name_id', 'graphic_id', 'grade_id', 'grade_pt', 'sally_sphere', 'exp', 'param_seed', 'hp', 'hp_max', 'attack1', 'attack2', 'attack3', 'defence1', 'defence2', 'defence3', 'defenceX', 'speed', 'death_count', 'last_affected', 'create_at', 'level', 'name')):

                $chara = Service::create('Character_Info')->needRecord($this->data['character_id']);

                if($propName == 'name')
                    return Text_LogService::get($chara['name_id']);
                else
                    return $chara[$propName];

            // equip_xxx系、total_xxx系、および "equip"
            case strpos($propName, 'equip_') === 0:
            case strpos($propName, 'total_') === 0:
            case $propName == 'equip':

                $props = $this->getAllProperty();
                return $props[$propName];

            // それ以外は値なし。
            default:
                return null;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * このユニットのすべてのプロパティを返す。
     *
     * @return array    ユニットのすべてのプロパティを格納した配列。
     */
    public function getAllProperty() {

        // キャッシュがないなら取得。
        if(!$this->allProps) {

            // character_info からレコードを取得。
            $chara = Service::create('Character_Info')->needExRecord($this->data['character_id']);

            // ユニット情報とキャラクタ情報を合成。
            $this->allProps = $this->data + $chara;

            // "sequip" を取得。
            $this->allProps['sequip'] = $this->getEquipPage($chara['race'], $chara['equip']);

            // レベル調整を行う。
            if($this->allProps['transcend_adapt']) {
                $revise = (int)($this->sphere->getTranscendLevel() * 1.5);
                $this->allProps['total_attack1'] += $revise;
                $this->allProps['total_attack2'] += $revise;
                $this->allProps['total_attack3'] += $revise;
                $this->allProps['total_defence1'] += $revise;
                $this->allProps['total_defence2'] += $revise;
                $this->allProps['total_defence3'] += $revise;
                $this->allProps['total_speed'] += $revise;
            }

            // 敵レベル調整を行う。add_level用
            if($this->allProps['add_level']) {
                $revise = (int)($this->allProps['add_level'] * 1.5);
                $this->allProps['total_attack1'] += $revise;
                $this->allProps['total_attack2'] += $revise;
                $this->allProps['total_attack3'] += $revise;
                $this->allProps['total_defence1'] += $revise;
                $this->allProps['total_defence2'] += $revise;
                $this->allProps['total_defence3'] += $revise;
                $this->allProps['total_speed'] += $revise;
            }

        }

        // キャッシュしてリターン。
        return $this->allProps;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * データベースへの保存などで使えるように、このユニットに関する情報を収めた配列を返す。
     *
     * @return array    このユニットに関する情報を収めた連想配列。
     */
    public function getData() {

        return $this->data;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * このユニットのデータをSWFに伝えるときの表現を取得する。
     *
     * @return array    SWFに伝えるときの文字列を収めた配列
     */
    public function getUnitSpecs() {

        $icons = $this->sphere->getUnitIcons();

        // ユニットデータをすべて取得。
        $props = $this->getAllProperty();

        // SWF用ユニット情報を作成。
        $result = array();
        $result['Name'] = $props['name'];
        $result['X'] = $props['pos'][0];
        $result['Y'] = $props['pos'][1];
        $result['Info'] = sprintf('%02d %02d %03d %02d',
            $icons[ $props['icon'] ],
            $props['union'],
            $props['move_pow'],
            $props['align']
        );
        $result['Status'] = sprintf('%04d %05d %05d %04d %04d %04d %04d %04d %04d %04d %04d',
            $props['level'],
            $props['hp'],
            $props['hp_max'],
            $props['total_attack1'],
            $props['total_attack2'],
            $props['total_attack3'],
            $props['total_defence1'],
            $props['total_defence2'],
            $props['total_defence3'],
            $props['total_speed'],
            $props['total_defenceX']
        );
        $result['Item'] = $this->getItemPageSpec($props['items']);
        $result['Eqp'] =  $this->getItemPageSpec($props['sequip']);

        return $result;
    }


    // プロパティの変更等
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの座標をセットする。
     *
     * @param int   X座標
     * @param int   Y座標
     * ※第一引数に座標を表す配列で指定することもできる。
     */
    public function setPos($pos, $y = 0) {

        if( !is_array($pos) )
            $pos = array($pos, $y);

        $this->data['pos'] = $pos;
        $this->allProps = null;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの向きをセットする。
     *
     * @param int   向き 0:正面 1:左 2:右 3:背面
     * ※第一引数に座標を表す配列で指定することもできる。
     */
    public function setAlign($align = 0) {

        $this->data['align'] = $align;
        $this->allProps = null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの番号をセットする。
     *
     * @param int   ユニット番号
     */
    public function setNo($unitNo) {

        $this->data['no'] = $unitNo;
        $this->allProps = null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 行動思考パターンを変更する。
     *
     * @param string    行動思考パターン
     */
    public function changeBrain($brain) {

        $this->data['act_brain'] = $brain;
        $this->allProps = null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * その他のプロパティの値をセットする。
     * ※値を書き換えれば済むような単純なものに限る。
     *
     * @param string    プロパティの名前。
     */
    public function setProperty($propName, $value) {

        switch($propName) {
            case 'act_brain':
                $this->changeBrain($value);
                break;
            default:
                $this->data[$propName] = $value;
        }

        $this->allProps = null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの経過ターン数を+1する。
     */
    public function plusTurn() {

        $this->data['turn']++;
        $this->allProps = null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたアイテムを所持させる。アイテム欄が一杯の場合は欄を拡張して追加する。
     *
     * @param int       ユーザアイテムID
     * @return array    アイテム所持をSWFに伝えるのに必要な指揮の配列。
     */
    public function supplyItem($uitemId) {

        // プロパティキャッシュをクリア
        $this->allProps = null;

        // 戻り値初期化とともに、スフィアのアイテムマスタに追加。
        $leads = $this->sphere->addItem($uitemId);

        // アイテムスロットの空きを探して、そこへの参照を変数 $itemSlot に格納する。
        foreach($this->data['items'] as &$itemSlot) {
            if(!$itemSlot)
                break;
        }

        // ここで変数 $itemSlot に何か入っているのは、アイテム欄が一杯であるため。欄を追加して、そこへの
        // 参照を取得する。
        if(!$this->data['items']  ||  $itemSlot) {
            $addIndex = count($this->data['items']);
            $this->data['items'][$addIndex] = 0;
            $itemSlot = &$this->data['items'][$addIndex];
        }

        // アイテムを格納。
        $itemSlot = $uitemId;

        // アイテム欄を更新する指揮を発行
        $leads[] = sprintf('UITEM %03d %s', $this->data['no'], $this->getItemPageSpec($this->data['items']));

        // リターン
        return $leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたスロットにあるアイテムを削除する。
     *
     * @param int       アイテムスロット番号
     * @return string   ユニットのアイテムスロットの新しい状態をSWFに伝えるための指揮コマンド。
     */
    public function lostItem($slotNo) {

        $this->allProps = null;

        // 失ったアイテムのユーザアイテムIDを取得。
        $uitemId = $this->data['items'][$slotNo];

        // システムレコードでないなら、user_item テーブルから削除
        if($uitemId > 0)
            Service::create('User_Item')->consumeItem($uitemId);

        // スフィア上でのアイテム欄から削除。
        $this->data['items'][$slotNo] = 0;
        return sprintf('UITEM %03d %s', $this->data['no'], $this->getItemPageSpec($this->data['items']));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたスロットにあるアイテムに装備変更する。
     *
     * @param int       アイテムスロット番号
     * @return string   ユニットの装備変更をSWFに伝えるための指揮コマンド。
     *                  null は装備変更できなかったことを表す。
     */
    public function changeEquipment($slotNo) {

        $equipSvc = new Character_EquipmentService();

        $this->allProps = null;

        // sequip をユニットデータで上書きしているものには対応していない。
//        if( isset($this->data['sequip']) )
//            throw new MojaviException('sequip を上書きしているユニットの装備を変更しようとした');

//主人公をクエ内でワープさせたときに装備変更できなくなる。
//主人公ユニット以外装備変更できないような念のための処置っぽいと思ったのでコメントアウト

        // 装備しようとしているアイテムを取得。
        $uitem = Service::create('User_Item')->needRecord($this->data['items'][$slotNo]);

        // 種族を取得。
        $race = $this->getProperty('race');

        // どこに装備されるものなのかを取得。
        $mountId = Service::create('Equippable_Master')->getMount($race, $uitem['item_id']);

        // 装備できないものである場合はそれを表すリターン。
        if(!$mountId)
            return null;

        // 今装備しているもののuser_idを取得。装備していないなら 0 とする。
        $current = $equipSvc->getRecord($this->data['character_id'], $mountId);
        $currentId = $current ? $current['user_item_id'] : 0;

        // 今装備しているものを使用スロットに移動。
        $this->data['items'][$slotNo] = $currentId;

        // 装備変更。
        $equipSvc->changeEquipment($this->data['character_id'], $mountId, $uitem['user_item_id']);

        // 該当ユニットの装備欄とアイテム欄を更新する指揮を発行。
        return array(
            sprintf( 'UITEM %03d %s', $this->data['no'], $this->getItemPageSpec($this->data['items']) ),
            sprintf( 'UEQIP %03d %s', $this->data['no'], $this->getItemPageSpec($this->getEquipPage($race)) ),
        );
    }


    // ターン、イベントの処理
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザが送信したコマンドをこのユニットが実行できるのか検証して、ユニットコマンドに変換する。
     *
     * @param array     ユーザが送信したコマンド兼、ユニットコマンド格納先。
     *                  SphereCommon::checkCommand() と同様。
     * @return string   エラーがある場合はそのエラーコード。妥当な場合はカラ文字列。
     */
    public function checkCommand(&$command) {

        $map = $this->sphere->getMap();

        // ユニットコマンド初期化。
        $unitComm = array();

        // プレイヤーコントロールのユニットにコマンドが送られてきているかチェック。
        if($this->data['act_brain'] != 'manual')
            return 'sys';

        // 移動後の座標、ターゲットの座標を取得。
        $moveTo = array($command['moveX'], $command['moveY']);
        $useTo = array($command['useX'], $command['useY']);

        // 移動しているならチェック。
        if($this->data['pos'] != $moveTo) {

            // 移動先を表す座標をセット。
            $unitComm['move']['to'] = $moveTo;

            // 現在の場所から移動先へのルートを取得。
            $moveCost = $map->getRoute($this, $moveTo, $unitComm['move']['path']);

            // 移動先が範囲内かチェック。
            if($this->data['move_pow'] < $moveCost)
                return 'move';

            // 移動先に自分以外のユニットがいないかチェック。
            $unit = $map->findUnitOn($moveTo);
            if($unit  &&  $unit !== $this)
                return 'move';
        }

        // 行動の有効範囲を変数 $range に取得。
        // 有効範囲のない行動なら -1 とする。
        switch($command['act']) {

            // 攻撃
            case 'att':

                // 攻撃対象のマスに存在するユニットを取得。
                $unit = $map->findUnitOn($useTo);

                // ユニットがいないならエラー。
                if(!$unit)
                    return 'att';

                // いるけど、同じ勢力のユニットならエラー。
                if($this->data['union'] == $unit->data['union'])
                    return 'att';

                // ここまでくればOK。後続のチェックへ。
                $range = 1;
                $unitComm['attack'] = $unit->getNo();
                break;

            // 待機
            case 'wait':
                $range = -1;
                break;

            // アイテム使用
            case 'item':

                $unitComm['use'] = array();
                $unitComm['use']['page'] = ($command['slot'] < 8) ? 'equip' : 'item';
                $unitComm['use']['slot'] = ($command['slot'] < 8) ? $command['slot'] : $command['slot'] - 8;
                $unitComm['use']['to'] = $useTo;

                // 消費アイテム欄か装備欄か、選択対象のリストを取得する。
                if($unitComm['use']['page'] == 'equip')
                    $itemSlots = $this->getProperty('sequip');
                else
                    $itemSlots = $this->data['items'];

                // 使用しようとしている user_item_id を取得。
                $uitemId = isset($itemSlots[ $unitComm['use']['slot'] ]) ? $itemSlots[ $unitComm['use']['slot'] ] : null;
                if(!$uitemId)
                    return 'item';

                // 使用しようとしている user_item レコードを取得。
                $uitem = Service::create('User_Item')->needRecord($uitemId);
                $unitComm['use']['uitem'] = $uitem;

                // 有効範囲を取得。ただし、消費アイテムから装備を選択している場合は使うわけではない。
                if($unitComm['use']['page'] == 'item'  &&  Item_MasterService::isDurable($uitem['category']))
                    $range = -1;
                else
                    $range = $uitem['item_limitation'];

                break;
        }

        // 有効範囲のある行動である場合。
        if($range >= 0) {

            // 移動先から使用先までのコストを取得。
            $rangeCost = $map->getManhattanDist($moveTo, $useTo);

            // 有効範囲内に収まっているかチェック。
            if($range < $rangeCost)
                return 'use';
        }

        // ここまでくればOK。
        $command = $unitComm;
        return '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットの行動を決定するタイミングでコールされる。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return array    決定したコマンドを格納する配列。SphereCommon::checkCommand() と同様。
     *                  ただし、ユーザコントロールユニットの場合はnull。
     */
    public function decideCommand(&$leads) {

        // ユーザコントロールユニットの場合はnullを返す。
        if($this->data['act_brain'] == 'manual')
            return null;

        // それ以外の場合はブレインタイプに該当するメソッドをコールして決定する。
        $this->brainFlash = array();
        $methodName = 'brain' . ucfirst($this->data['act_brain']);
        $command = $this->$methodName();

        // 移動していないなら "move" キーは削除する。
        if( isset($command['move'])  &&  $command['move']['to'] == $this->data['pos'])
            unset($command['move']);

        // リターン
        return $command;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 行動終了フェーズのタイミングで呼ばれる。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     */
    public function afterCommand(&$leads) {

        // 基底の実装では特に何もしない。
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ステートイベントを処理する。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array     イベントの内容。次のキーを持つ配列。
     *                      name        イベント名
     *                      reason      イベントのサブコード
     *                      trigger     イベントの原因になったユニットがいる場合に、その番号。
     *                                  ユニットによらないなら省略、あるいは0。
     * @return bool     SWFへのリターンが発生したかどうか。
     */
    public function event(&$leads, $event) {

        switch($event['name']) {

            // ユニットの退場
            case 'exit':

                // スフィアから自身を削除。
                $this->sphere->removeUnit(
                    $leads, $this->data['no'], $event['reason'], $this->sphere->getUnit($event['trigger'])
                );

                return false;
        }

        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットに引数で指定したアイテム効果を与える。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array     item_master.item_id, item_type, item_value を格納している配列。
     * @param object    アイテムを使ったユニット。トリガーになったユニットがいない場合はnull。
     */
    public function exposeItem(&$leads, $item, $trigger = null) {

        switch($item['item_type']) {

            // HP回復
            case Item_MasterService::RECV_HP:
                $this->recoverHp($leads, $item['item_value'], $trigger);
                break;

            // ダメージ
            case Item_MasterService::TACT_ATT:

                // ダメージを取得。
                $damage = $item['item_value'] - $this->getProperty('total_defenceX');
                if($damage < 0)  $damage = 0;

                // 処理
                $this->damageHp($leads, $damage, $trigger, $item['item_id']);
                break;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットのHPを回復する。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param int       回復値。
     * @param object    回復をもたらしたユニット。トリガーになったユニットがいない場合はnull。
     */
    public function recoverHp(&$leads, $recover, $trigger = null) {

        // 最大HPを取得。
        $hpMax = $this->getProperty('hp_max');

        // HPを増やす。
        $this->data['hp'] += $recover;
        if($this->data['hp'] > $hpMax)   $this->data['hp'] = $hpMax;
        $this->allProps = null;

        // 回復エフェクトを指揮する。
        $leads[] = sprintf('RECOV %03d %d', $this->data['no'], $recover);
        $leads[] = sprintf('UVALS %03d hp %05d', $this->data['no'], $this->data['hp']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットのHPにダメージを与える。
     *
     * @param reference スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param int       ダメージ値。
     * @param object    ダメージをもたらしたユニット。トリガーになったユニットがいない場合はnull。
     * @param int       ダメージをもたらした手段。アイテムの場合はアイテムID。バトルの場合は
     *                  -1:メインバトル か、-2:省略バトル。
     *                  特に手段のないダメージ(イベントなどによる特殊なダメージ)の場合は null。
     */
    public function damageHp(&$leads, $damage, $trigger = null, $means = null) {

        // HPを減らす。
        $this->data['hp'] -= $damage;
        if($this->data['hp'] < 0)   $this->data['hp'] = 0;
        $this->allProps = null;

        //もし相手がいるなら・・
        if(!is_null($trigger)){
            //向き合う
            $this->sphere->getFaceToFace($leads, $this, $trigger);
        }
        // ダメージエフェクトを指揮する。
        $leads[] = sprintf('DAMAG %03d %d', $this->data['no'], $damage);
        $leads[] = sprintf('UVALS %03d hp %05d', $this->data['no'], $this->data['hp']);

        // 0になったら打倒メソッドを呼ぶ。
        if($this->data['hp'] == 0)
            $this->collapse($trigger, $means);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユニットのHPが0になったタイミングで呼ばれる。
     *
     * @param object    ダメージをもたらしたユニット。トリガーになったユニットがいない場合はnull。
     * @param int       ダメージをもたらした手段。アイテムの場合はアイテムID。
     *                  バトルの場合は -1:メインバトル か、-2:省略バトル。
     *                  特に手段のないダメージ(イベントなどによる特殊なダメージ)の場合は null。
     */
    public function collapse($trigger = null, $means = null) {

        // ユニット退出のイベントを作成。
        $event = array(
            'type' => 'unit',
            'no' => $this->data['no'],
            'name' => 'exit',
            'reason' => 'collapse',
            'trigger' => $trigger ? $trigger->getNo() : 0
        );

        // キューへ。主人公ユニットが死んだ場合はイベントキューの先頭にプッシュする。
        $this->sphere->pushStateEvent($event, ($this->data['code'] == 'avatar'));

        // プレイヤー配下のユニットが死んだ場合は死亡回数をカウントアップする。
        if($this->data['player_owner']) {
            Service::create('Character_Info')->plusValue($this->data['character_id'], array(
                'death_count' => +1,
            ));
        }

        // 打倒したユニットがプレイヤー配下のキャラで、ダメージ手段がメインバトル以外の場合はリワードを処理する。
        if($trigger  &&  $trigger->getProperty('player_owner')  &&  isset($means)  &&  $means != -1) {
            // 経験値とお金を計算。
            if($this->sphere->getQuestId() != '99999'){
    	          $reward = FieldBattleUtil::getFieldReward($this->getAllProperty(), $trigger->getAllProperty());
            }else{
                $reward = FieldBattle99999Util::getFieldReward($this->getAllProperty(), $trigger->getAllProperty());
            }
            // 付与処理。
            $growth = Service::create('Character_Info')->gainExp($trigger->getProperty('character_id'), $reward['exp']);
            Service::create('User_Info')->plusValue($trigger->getProperty('user_id'), array('gold'=>$reward['gold']));

            // 経験値は実際の取得分を反映しておく。
            $reward['exp'] = $growth['after']['exp'] - $growth['before']['exp'];

            // 取得した経験値とマグナを表示するテキストを作成。
            $mess = sprintf(AppUtil::getText("sphere_text_get_exp_god"), $reward['exp'], $reward['gold']);

            // レベルアップしてるならそれも追加。
            if($growth['before']['level'] < $growth['after']['level'])
                $mess .= "\n" . str_replace("{0}", $growth['after']['level'], AppUtil::getText("sphere_text_levelup"));

            //レイドダンジョン中なら・・
            $user_id = $trigger->getProperty('user_id');
            $mon_character_id = $this->getProperty('character_id');

            $res = FieldBattleUtil::getRaidDungeonResult($this->sphere->getQuestId(), $user_id, $mon_character_id);
            if($res["get_raid_point"] > 0){
                $mess .= "\n" . str_replace("{0}", $res["get_raid_point"], AppUtil::getText("sphere_text_get_raidpoint"));
            }

            if($res["get_nft"]){
                $mess .= "\n" . AppUtil::getText("sphere_text_get_monster_nft");
            }

            // ステートイベントを使って表示を行う。
            $this->sphere->pushStateEvent(array('type'=>'lead', 'leads'=> array('NOTIF '.$mess)));

        }
    }


    // protectedメンバ。
    //=====================================================================================================

    // このユニットに関する情報を収めた連想配列。
    protected $data;

    // このユニットの所属先になっている SphereCommon インスタンス。
    protected $sphere;

    // getAllProperty() のキャッシュ。
    protected $allProps;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユニットデータから、そのユニットを扱うクラスの名前を返す。
     * 定義ファイルのインクルードも行う。
     *
     * @param array     ユニットに関する情報を収めた連想配列
     * @return string   ユニットを扱うクラスの名前
     */
    protected static function getClassName($data) {

        // ユーティリティの種類が「標準」ならば。
        if( empty($data['unit_class']) ) {
            $className = __CLASS__;

        // 非標準なら...
        }else {

            // クラス名を取得。
            $className = __CLASS__ . $data['unit_class'];

            // クラスファイルインクルード
            require_once(dirname(__FILE__).'/extends/'.$className.'.class.php');
        }

        // リターン。
        return $className;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     *
     * @param array     このユニットに関する情報を収めた連想配列。
     * @param object    このユニットの所属先になっている SphereCommon インスタンス。
     */
    protected function __construct($data, $sphere) {

        $this->data = $data;
        $this->sphere = $sphere;

        // 下位互換を保つ。
        if($this->data['act_brain'] == 'attack')
            $this->data['act_brain'] = 'generic';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたアイテムページの内容をSWFに伝えるための文字列を返す。
     *
     * @param array     アイテムスロット。スロット番号をキー、ユーザアイテムIDを値とする配列。
     * @return string   SWFに伝えるための文字列
     */
    protected function getItemPageSpec($page) {

        // ユーザアイテムIDとSWFでのアイテム番号との対応表を取得。
        $itemTable = $this->sphere->getItemTable();

        // 戻り値初期化。
        $result = '';

        // アイテムスロットを一つずつ見ていく。
        // foreachを使っていないのは、キーが連番になっていない場合に備えるため。
        if(count($page) > 0) {
            $maxKey = max(array_keys($page));
            for($i = 0 ; $i <= $maxKey ; $i++) {

                // ユーザアイテムIDを取得。
                $uitemId = isset($page[$i]) ? $page[$i] : 0;

                // 0詰め3桁でSWFアイテム番号を列挙していく。
                $result .= sprintf('%03d ', isset($itemTable[$uitemId]) ? $itemTable[$uitemId] : 0);
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * スフィア上における装備ページを返す。
     *
     * @param string    省略可能。キャラクタの種族。呼び出し側ですでに取得しているなら指定する。
     * @param array     同じく、Character_EquipmentService::getEquipments() で取得したキャラクタの装備。
     * @return array    装備スロット番号をキー、装備アイテムのユーザアイテムIDを値とする配列。
     */
    protected function getEquipPage($race = '', $equips = null) {

        // ユニットプロパティで "sequip" を上書きしているなら、そこから返す。
        if($this->data['sequip'])
            return $this->data['sequip'];

        // 引数が省略されていたら取得。
        if(!$race)
            $race = $this->getProperty('race');

        // 同じく。
        if( is_null($equips) )
            $equips = Service::create('Character_Equipment')->getEquipments($this->data['character_id']);

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($race);

        // 装備箇所を一つずつ処理する。
        $page = array();
        foreach($mounts as $mount) {

            // そこに装備しているuser_itemレコードを取得。
            $equip = isset($equips[ $mount['mount_id'] ]) ? $equips[ $mount['mount_id'] ] : null;

            // 装備箇所で定義されている番号のスロットに、該当装備のユーザアイテムIDを格納する。
            $page[ $mount['slot_no'] ] = $equip ? $equip['user_item_id'] : 0;
        }

        // リターン。
        return $page;
    }


    // 思考ルーチン系のメソッド。protectedメンバ。
    //=====================================================================================================

    // 思考系ルーチンでよく使うデータのキャッシュ。思考を開始するdecideCommand()で必ずクリアされる。
    // requireBrainFlash() のコールでデータを要求してから参照する。以下のキーがある。
    //      unit_map        ユニット所在マップ。SphereMap::getUnitMap() の戻り値
    //      movables        このユニットが移動可能な範囲。SphereMap::getMovables() の戻り値
    //      assault         移動可能な範囲に直接攻撃が可能なマスがある場合に、それを行うコマンドの一覧。
    //      item_use        移動後に、適当なアイテム使用が可能なら、その一覧。
    //                      1次キーが移動後Y座標、2次キーが移動後X座標、値はその座標で行使できるアイテムの
    //                      使用情報(ユニットコマンド "use" キー)の一覧。
    //      route_to_enemy  自分とは違う所属のユニットへの経路とコスト。
    //                      キーはユニット番号、値は "path", "cost" を収録している配列。
    protected $brainFlash;


    //-----------------------------------------------------------------------------------------------------
    /**
     * "generic" 型行動決定ルーチン。最も一般的なもの。
     *
     * @return array    ユニットコマンド。
     */
    protected function brainGeneric() {

        // 現在の場所から回復アイテム使用が可能ならそうする。
        $use = $this->thinkItem(Item_MasterService::RECV_HP);
        if($use)
            return array('use'=>$use);

        // フラグ "brain_item_orient" がONならば...
        if($this->data['brain_item_orient']) {

            // 到達可能な領域すべてにおいてアイテムの使用を考えて、適切な場所があるならそうする。
            $this->requireBrainFlash('movables');
            $command = $this->thinkItemOnRegion(0, $this->brainFlash['movables']);
            if($command)
                return $command;
        }

        // 直接攻撃が可能ならばそうする。
        $this->requireBrainFlash('assault');
        if($this->brainFlash['assault'])
            return $this->brainFlash['assault'][0];

        // 直接攻撃可能なポイントにいる最も近い敵への接近を行う。
        $command = $this->thinkApproach('nearest');
        return $command ?: array();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "target" 型行動決定ルーチン。"target_unit" あるいは "target_union" で設定されたユニットを
     * 狙うように行動する。
     * 両方指定されている場合は target_unit => target_union の順で処理される。
     *
     * @return array    ユニットコマンド。
     */
    protected function brainTarget() {

        // 現在の場所から回復アイテム使用が可能ならそうする。
        $use = $this->thinkItem(Item_MasterService::RECV_HP);
        if($use)
            return array('use'=>$use);

        // 直接攻撃が可能な一覧を取得する。
        $this->requireBrainFlash('assault');

        // "target_unit" が設定されている場合。
        if($this->data['target_unit']) {

            // 指定のユニットに直接攻撃が可能ならばそうする。
            foreach($this->brainFlash['assault'] as $command) {
                if( $this->sphere->getUnit($command['attack'])->getCode() == $this->data['target_unit'] )
                    return $command;
            }

            // 指定のユニットに到達可能な経路があるならそれを行く。
            $command = $this->thinkApproach('unit', $this->data['target_unit']);
            if($command)
                return $command;
        }

        // "target_union" が設定されている場合。
        if($this->data['target_union']) {

            // 指定の所属ユニットに直接攻撃が可能ならばそうする。
            foreach($this->brainFlash['assault'] as $command) {
                if( $this->sphere->getUnit($command['attack'])->getUnion() == $this->data['target_union'] )
                    return $command;
            }

            // 指定の所属ユニットに到達可能な経路があるならそれを行く。
            $command = $this->thinkApproach('union', $this->data['target_union']);
            if($command)
                return $command;
        }

        // ここまで来ても何も決まらないようであれば通常ルーチンで考える。
        return $this->brainGeneric();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "rest" 型行動決定ルーチン。今の場所から動かない。
     *
     * @return array    ユニットコマンド。
     */
    protected function brainRest() {

        // アイテムの使用が可能ならそうする。
        $use = $this->thinkItem();
        if($use)
            return array('use'=>$use);

        // 攻撃が可能ならそうする。
        $assaultNos = $this->thinkAssault($this->data['pos']);
        if($assaultNos)
            return array('attack'=>$assaultNos[0]);

        // ここまで来たら何もしない。
        return array();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "destine" 型行動決定ルーチン。"destine_pos" で指定された座標を目指す。
     *
     * @return array    ユニットコマンド。
     */
    protected function brainDestine() {

        // 戻り値初期化。
        $command = array();

        // 設定されている目的地までの経路を取得。
        $this->sphere->getMap()->getRoute($this, $this->data['destine_pos'], $route);

        // 経路を、行けるところまで行く。
        $move = $this->thinkWalk($route);
        $command['move'] = $move;

        // 行き着いた先で回復アイテム使用が可能ならそうする。
        $use = $this->thinkItem(Item_MasterService::RECV_HP, $move['to']);
        if($use) {
            $command['use'] = $use;
            return $command;
        }

        // 行き着いた先で攻撃が可能ならそうする。
        $assaultNos = $this->thinkAssault($move['to']);
        if($assaultNos) {
            $command['attack'] = $assaultNos[0];
            return $command;
        }

        // 行き着いた先でその他アイテムの使用が可能ならそうする。
        $use = $this->thinkItem(0, $move['to']);
        if($use) {
            $command['use'] = $use;
            return $command;
        }

        // リターン。
        return $command;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "keep" 型行動決定ルーチン。"keep_pos" で指定された座標周辺を保持する。
     *
     * @return array    ユニットコマンド。
     */
    protected function brainKeep() {

        $map = $this->sphere->getMap();

        // "keep_pos" で指定された保持領域を取得。
        $keepRegion = $map->getMovables($this->data['keep_pos'], 4);

        // 現在の場所が保持領域で、回復アイテムの使用が適切ならそうする。
        if( $map->inRegion($this->data['pos'], $keepRegion) ) {
            $use = $this->thinkItem(Item_MasterService::RECV_HP);
            if($use)
                return array('use'=>$use);
        }

        // 保持領域で直接攻撃が可能なポイントがあるならばそうする。
        $this->requireBrainFlash('assault');
        foreach($this->brainFlash['assault'] as $command) {
            if( $map->inRegion($command['move']['to'] , $keepRegion) )
                return $command;
        }

        // 到達可能な保持領域を取得。
        $this->requireBrainFlash('movables');
        $goodRegion = $map->intersectRegion($this->brainFlash['movables'], $keepRegion);

        // 到達可能な保持領域すべてにおいてアイテムの使用を考えて、適切な使用場所があるならばそうする。
        $command = $this->thinkItemOnRegion(0, $goodRegion);
        if($command)
            return $command;

        // いずれも不可なら、保持領域の中心へ向けて移動する。
        $map->getRoute($this, $this->data['keep_pos'], $route);
        return array('move' => $this->thinkWalk($route));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "guard" 型行動決定ルーチン。"guard_unit" で指定されたユニットを護衛する。
     *
     * @return array    ユニットコマンド。
     */
    protected function brainGuard() {

        $map = $this->sphere->getMap();

        // 護衛対象のユニットを取得。いない場合は "generic" に譲る。
        $guardee = $this->sphere->getUnitByCode($this->data['guard_unit']);
        if(!$guardee)
            return $this->brainGeneric();

        // 護衛対象ユニットの保持領域を取得。
        $keepRegion = $map->getMovables($guardee->getPos(), 3);

        // 現在の場所が保持領域で、回復アイテムの使用が適切ならそうする。
        if( $map->inRegion($this->data['pos'], $keepRegion) ) {
            $use = $this->thinkItem(Item_MasterService::RECV_HP);
            if($use)
                return array('use'=>$use);
        }

        // 保持領域で直接攻撃が可能なポイントがあるならばそうする。
        $this->requireBrainFlash('assault');
        foreach($this->brainFlash['assault'] as $command) {
            if( $map->inRegion($command['move']['to'] , $keepRegion) )
                return $command;
        }

        // 到達可能な保持領域を取得。
        $this->requireBrainFlash('movables');
        $goodRegion = $map->intersectRegion($this->brainFlash['movables'], $keepRegion);

        // 到達可能な保持領域すべてにおいてアイテムの使用を考えて、適切な使用場所があるならばそうする。
        $command = $this->thinkItemOnRegion(0, $goodRegion);
        if($command)
            return $command;

        // いずれも不可なら、護衛ユニットへ向けて移動する。
        $map->getRoute($this, $guardee->getPos(), $route);
        return array('move' => $this->thinkWalk($route));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * メンバ変数 $brainFlash に、引数で指定されたデータをセットする。
     *
     * @param string    データのキー。メンバ変数 $brainFlash のコメントを参照。
     * @param mixed     データの指定に使うサブキー。必要ならば使う。
     *                  "item_use" の場合は自分がいると仮定する座標。
     */
    protected function requireBrainFlash($type, $arg1 = null) {

        switch($type) {

            case "unit_map":

                if( isset($this->brainFlash['unit_map']) )
                    break;

                $this->brainFlash['unit_map'] = $this->sphere->getMap()->getUnitMap();
                break;

            case "movables":

                if( isset($this->brainFlash['movables']) )
                    break;

                $this->brainFlash['movables'] = $this->sphere->getMap()->getMovables(
                    $this->getPos(), $this->data['move_pow'], $this
                );
                break;

            case "assault":

                if( isset($this->brainFlash['assault']) )
                    break;

                $this->requireBrainFlash('movables');
                $this->requireBrainFlash('unit_map');

                // 移動可能なマスを一つずつ評価していく。
                $this->brainFlash['assault'] = array();
                foreach($this->brainFlash['movables'] as $y => $line) {
                    foreach($line as $x => $dummy) {

                        // 他のユニットがいてそこへは移動できないならスキップ。
                        if( isset($this->brainFlash['unit_map'][$y][$x])  &&  $this->brainFlash['unit_map'][$y][$x] !== $this)
                            continue;

                        // その場所から狙えるユニットの一覧を取得。そのユニットを狙うコマンドを作成する。
                        $targetNos = $this->thinkAssault(array($x, $y));
                        foreach($targetNos as $targetNo) {
                            $command = array();
                            $command['move']['to'] = array($x, $y);
                            $command['attack'] = $targetNo;
                            $this->brainFlash['assault'][] = $command;
                        }
                    }
                }

                break;

            case 'item_use':

                // 引数が省略されている場合は補う。
                if(!$arg1)
                    $arg1 = $this->getPos();

                // すでにキャッシュされているなら処理しない。
                if( isset($this->brainFlash['item_use'][ $arg1[1] ][ $arg1[0] ]) )
                    break;

                // ユニットマップを取得。現在の座標から使用するならそのままだが...
                if($arg1 == $this->getPos()) {
                    $this->requireBrainFlash('unit_map');
                    $unitMap = $this->brainFlash['unit_map'];

                // 移動してからの使用を考えているなら自分の座標を仮に動かして作成する。
                }else {
                    $orgPos = $this->getPos();
                    $this->setPos($arg1);
                    $unitMap = $this->sphere->getMap()->getUnitMap();
                    $this->setPos($orgPos);
                }

                // 装備欄⇒アイテム欄で処理する。
                $uitemSvc = new User_ItemService();
                $uses = array();
                for($i = 0 ; $i < 2 ; $i++) {

                    $page = $i ? 'sequip' : 'items';

                    // アイテムを一つずつ見ていく。
                    // 同じアイテムを何度も思考しないように、array_unique() を使用する。
                    // array_uniqueはキーを保持することに留意。
                    foreach(array_unique($this->getProperty($page)) as $slot => $uitemId) {

                        if(!$uitemId)
                            continue;

                        // アイテム情報を取得。
                        $uitem = $uitemSvc->needRecord($uitemId);

                        // それが使用できるか考える。
                        $useTo = $this->thinkItemUseTo($uitem, $arg1, $unitMap);

                        // できると判断したならそれをキャッシュする。
                        if($useTo)
                            $uses[] = array('to'=>$useTo, 'page'=>($i ? 'equip' : 'item'), 'slot'=>$slot, 'uitem'=>$uitem);
                    }
                }

                $this->brainFlash['item_use'][ $arg1[1] ][ $arg1[0] ] = $uses;
                break;

            case 'route_to_enemy':

                // すでにキャッシュされているなら処理しない。
                if( isset($this->brainFlash['route_to_enemy']) )
                    break;

                $map = $this->sphere->getMap();

                // ユニットを一つずつ見ていく。
                $this->brainFlash['route_to_enemy'] = array();
                foreach($this->sphere->getUnits() as $unit) {

                    // 同じ所属のユニットはスキップ。
                    if($unit->data['union'] == $this->data['union'])
                        continue;

                    // そのユニットへの経路と移動コストを取得。
                    $cost = $map->getRoute($this, $unit->getPos(), $path);

                    // brainFlashに格納していく。
                    $this->brainFlash['route_to_enemy'][ $unit->getNo() ] = array(
                        'cost'=>$cost, 'path'=>$path
                    );
                }

                break;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムの使用を判断し、適切な使用がある場合はその使用情報を返す。
     *
     * @param int       アイテム種別を制限する場合は指定する。0を指定した場合はすべて。
     * @param array     ここで指定された座標に自分がいるものと仮定する。移動後のアイテム使用を考える
     *                  場合などで使う。省略すると現在いる場所が使用される。
     * @return array    ユニットコマンドの "use" キーに該当する値。適切な使用がない場合はnull。
     */
    protected function thinkItem($type = 0, $point = null) {

        if(!$point)
            $point = $this->getPos();

        // 指定された座標での使用情報を取得。
        $this->requireBrainFlash('item_use', $point);
        $uses = $this->brainFlash['item_use'][ $point[1] ][ $point[0] ];

        // 一つもないならnullリターン。
        if(!$uses)
            return null;

        // 種別が制限されていない場合、まず回復系から考える。
        if($type == 0) {

            $use = $this->thinkItem(Item_MasterService::RECV_HP, $point);
            if($use)
                return $use;

            // "brain_noattack" が指定されている場合は回復しか考えない。
            if($this->data['brain_noattack'])
                return null;
        }

        // 使用情報を一つずつ見ていく。
        foreach($uses as $use) {

            // 思考対象のアイテムでないならスキップ
            if($type  &&  $use['uitem']['item_type'] != $type)
                continue;

            // 最初に見つかった使用情報を返す。
            return $use;
        }

        // ここまで来るのは条件を満たす使用がないから。
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された領域に含まれるすべてのマスにおいて、アイテムの使用を判断する。
     *
     * @param int       アイテム種別を制限する場合は指定する。0を指定した場合はすべて。
     * @param array     移動可能な領域。SphereMap::getMovables() の戻り値。
     * @return array    ユニットコマンド。適切な使用がない場合はnull。
     */
    protected function thinkItemOnRegion($type, $region) {

        $this->requireBrainFlash('unit_map');

        // 指定された領域に含まれるマスを一つずつ見ていく。
        foreach($region as $y => $line) {
            foreach($line as $x => $dummy) {

                // 他のユニットがいてそこへは移動できないならスキップ。
                if( isset($this->brainFlash['unit_map'][$y][$x])  &&  $this->brainFlash['unit_map'][$y][$x] !== $this)
                    continue;

                $pos = array($x, $y);

                // 適当なアイテム使用箇所があるならそれを行うコマンドを返す。
                $use = $this->thinkItem($type, $pos);
                if($use) {
                    return array(
                        'move' => array('to'=>$pos),
                        'use' =>  $use,
                    );
                }
            }
        }

        // ここまで来るのは条件を満たすアイテム使用がないから。
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユニットに接近するコマンドを考える。
     * 接近後、アイテムの使用ができるかどうかも考える。
     *
     * @param string    ユニットの指定。以下のいずれか。
     *                      nearest     最も近い敵ユニット。
     *                      unit        第二引数で指定したcode値をもつユニット。
     *                      union       第二引数で指定した所属のユニットのうち最も近いもの。
     * @param mixed     ユニットの指定に使うサブキー
     * @return array    ユニットコマンド。指定のユニットに到達可能な経路がない場合は null
     */
    protected function thinkApproach($target, $sub = null, $skipUnitNo = null) {

        // 目標ユニットが指定されている場合。
        if($target == 'unit') {

            // そのユニットを取得。いない場合は何もできない。
            $targetUnit = $this->sphere->getUnitByCode($sub);
            if(!$targetUnit)
                return null;

            // そのユニットへの経路が載っている一覧を変数 $routes に取得。
            if( $targetUnit->getUnion() == $this->getUnion() ) {
                throw new MojaviException('自ユニオンをターゲットにするのはまだ未対応');
            }else {
                $this->requireBrainFlash('route_to_enemy');
                $routes = $this->brainFlash['route_to_enemy'];
            }

            // そのユニットへの経路を取得。
            $route = $routes[ $targetUnit->getNo() ];

        // 目標ユニットが明確に指定されていない場合。
        }else {

            // 経路一覧を変数 $routes に取得。自ユニオンを目指すわけではない場合は、
            // 敵ユニットへの経路一覧。
            if($target == 'union'  &&  $sub == $this->getUnion()) {
                throw new MojaviException('自ユニオンをターゲットにするのはまだ未対応');
            }else {
                $this->requireBrainFlash('route_to_enemy');
                $routes = $this->brainFlash['route_to_enemy'];
            }

            // ユニットへの経路を一つずつみていく。
            $route = null;
            foreach($routes as $unitNo => $rt) {

                // 所属が指定されている場合に、指定の所属でないユニットはスキップ。
                if($target == 'union'  &&  $this->sphere->getUnit($unitNo)->getUnion() != $sub)
                    continue;

                if($skipUnitNo != null && $unitNo == $skipUnitNo)
                    continue;

                // 現在の最近傍ユニットよりも近いなら切り替える。
                if(!$route  ||  $rt['cost'] < $route['cost'])
                    $route = $rt;
            }
        }

        // 経路がない、あるいは到達不可能な場合はnullを返す。
        if(!$route  ||  $route['cost'] == 0x7FFFFFFF)
            return null;

        // 行ける範囲で経路を行く。
        $command = array();
        $command['move'] = $this->thinkWalk($route['path']);

        // 接近後の場所でアイテム使用を考える。
        $use = $this->thinkItem(0, $command['move']['to']);
        if($use)
            $command['use'] = $use;

        // リターン。
        return $command;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 今いる場所から、引数に指定された経路を行くとして、どこまで行けるかを調べる。
     *
     * @param array     経路。
     * @return array    ユニットコマンドの "move" キーに該当する値。
     */
    protected function thinkWalk($route) {

        $map = $this->sphere->getMap();
        $this->requireBrainFlash('unit_map');

        // 移動可能な範囲を取得。
        $this->requireBrainFlash('movables');
        $movables = $this->brainFlash['movables'];

        // 経路をたどって行けるところまで行く。
        while(true)  {

            // 移動可能な範囲で経路を行く。
            $path = $route;
            $toPoint = $map->walk($this->data['pos'], $path, $movables);

            // そこに別のユニットがいるかどうかチェック。
            // いない、あるいは自分ならOK。
            $unit = $map->findUnitOn($toPoint, $this->brainFlash['unit_map']);
            if(!$unit  ||  $unit === $this)
                break;

            // 他のユニットがいるなら、そこへは移動できない。移動可能なマスから削除してリトライ。
            unset($movables[$toPoint[1]][$toPoint[0]]);
        }

        // 移動先をセットしたコマンドをリターン。
        return array(
            'to' => $toPoint,
            'path' => $path,
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された座標において、攻撃可能なユニットがいないかチェックして、
     * いれば、そのユニットの番号の一覧を返す。
     *
     * @param array     調べたい座標。
     * @return array    攻撃可能なユニットがいる場合はその番号の配列。いない場合はカラの配列。
     */
    protected function thinkAssault($pos) {

        // brain_noattack が指定されている場合は攻撃しない。
        if($this->data['brain_noattack'])
            return array();

        // 必要な情報を取得。
        $map = $this->sphere->getMap();
        $this->requireBrainFlash('unit_map');

        // 戻り値初期化。
        $result = array();

        // 隣接マスを取得。一つずつ見ていく。
        $neighbors = $map->getNeighbors($pos);
        foreach($neighbors as $nei) {

            $unit = $this->brainFlash['unit_map'][$nei[1]][$nei[0]];

            // ユニットがいないならスキップ。
            if(!$unit)
                continue;

            // いるけど、同じ所属ならスキップ。
            if($unit->getUnion() == $this->data['union'])
                continue;

            // ここまで来たらそのユニットを攻撃できる。
            $result[] = $unit->getNo();
        }

        // リターン
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたアイテムを使用するとしたらどの座標へ使用するのが適切かを考える。
     *
     * @param array     ユーザアイテムレコード
     * @param array     自分がいると考える座標
     * @param array     ユニット所在マップ
     * @return array    最も適切な使用座標。適切でないならnull
     */
    protected function thinkItemUseTo($uitem, $point, $unitMap) {

        $map = $this->sphere->getMap();

        // 使用可能な座標を取得。
        $range = $map->getMovables($point, $uitem['item_limitation']);

        // 初期化。
        $currentScore = 0;
        $result = null;

        // 使用可能な座標を一つずつ見ていく。
        foreach($range as $y => $line) {
            foreach($line as $x => $dummy) {

                $to = array($x, $y);

                // スコアを初期化。
                $effectScore = 0;

                // 効果が及ぶ座標をすべて取得。
                $spread = $map->getMovables($to, $uitem['item_spread']);

                // 効果が及ぶ座標を一つずつ見て、スコアを計算していく。
                foreach($spread as $sy => $sline) {
                    foreach($sline as $sx => $dummy) {

                        // ユニットがいないならスコア 0。
                        $unit = $unitMap[$sy][$sx];
                        if(!$unit)
                            continue;

                        // ユニットがいたなら、スコアを計算。
                        $score = $this->getEffectScore($uitem, $unitMap[$sy][$sx]);

                        // スコアを加算。
                        $effectScore += $score;
                    }
                }

                // 現在の候補よりもスコアが高いなら変更する。
                if($effectScore > $currentScore) {
                    $result = $to;
                    $currentScore = $effectScore;
                }
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたアイテム効果が、指定されたユニットに及んだ場合の評価を返す。
     *
     * @param array     ユーザアイテムレコード
     * @param object    対象ユニット
     * @return int      評価スコア
     */
    protected function getEffectScore($uitem, $unit) {

        switch($uitem['item_type']) {

            // 回復アイテムの場合。
            case Item_MasterService::RECV_HP:

                // 回復量をそのままスコアに。
                $score = min($uitem['item_value'], $unit->getProperty('hp_max') - $unit->getProperty('hp'));

                // ただし、それが敵ユニットならマイナススコア。
                if($unit->getUnion() != $this->data['union'])
                    $score *= -1;

                // 味方ユニットだけど、そのユニットのダメージが25%以下ならスコアゼロ
                else if($unit->getProperty('hp') / $unit->getProperty('hp_max') > 0.75)
                    $score = 0;

                return $score;

            // 攻撃アイテムの場合。
            case Item_MasterService::TACT_ATT:

                // 与えられるダメージ量がそのままスコア
                $score = $uitem['item_value'] - $unit->getProperty('total_defenceX');

                // ただし、それが味方ユニットならマイナススコア。
                if($unit->getUnion() == $this->data['union'])
                    $score *= -1;

                return $score;

            // よく分からないアイテムは評価できない。
            default:
                return 0;
        }
    }
}
