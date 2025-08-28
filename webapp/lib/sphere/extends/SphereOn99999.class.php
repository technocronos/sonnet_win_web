<?php

/**
 * モンスターの洞窟の特殊処理を記述する
 */
class SphereOn99999 extends SphereCommon {

    // 洞窟のリピート回数。1000階までは対応していないので注意。
    const REPEAT_COUNT = 3;

    //-----------------------------------------------------------------------------------------------------
    /**
     * getSummary()をオーバーライド。
     * 到達階数を表す "attain_stair" を追加する。
     */
    public function getSummary() {

        $summary = parent::getSummary();

        $summary['attain_stair'] = $this->state['current_room'];

        return $summary;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * createState()をオーバーライド。
     * "start" のルームは "1"、理由 "start", "warp" は "ahead" と解釈する。
     */
    protected function createState($roomName, $enterUnits, $reason) {

        if($roomName == 'start')
            $roomName = '1';

        if($reason == 'start'  ||  $reason == 'warp')
            $reason = 'ahead';

        return parent::createState($roomName, $enterUnits, $reason);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * getRoomDefinition()をオーバーライド。完全にカスタムする。
     */
    protected function getRoomDefinition($data, $roomName) {

        // roomsに該当のキーがあるならそれを返す。
        $room = $data['rooms'][$roomName];
        if($room)
            return $room;

        //100階以上は繰り返す場合
        if(self::REPEAT_COUNT > 1){
            for($i=0;$i <= self::REPEAT_COUNT * 100 ;$i++){

                //100階以下は処理しない
                if($i <= 100)
                  continue;

                //200階、300階のような場合は0階は無いので・・
                if($i % 100 == 0)
                    $j = ((int)($i/100) - 1) * 100;
                else
                    $j = (int)($i/100) * 100;

                //そのルームが無いが-100したらある場合、-100の部屋をコピー
                if(!$data['x_stairs'][$i] && $data['x_stairs'][$i - $j]){
                    $data['x_stairs'][$i] = $data['x_stairs'][$i - $j];
                }
            }
        }

        // 最上階から上に上がった場合。
        if($roomName > count($data['x_stairs']))
            return $this->setupUnderConstruction($roomName);

        // 定義テンプレートを取得。
        $room = $data['x_templates']['normal'];
        $room['units'] = array();

        // 基本的なギミックの設定。
        $this->setupGimmicks($room, $roomName, count($data['x_stairs']));

        // 出現モンスターの設定。
        $this->setupEnemies($room, $data['x_stairs'][$roomName], $data['x_appear_poses']);

        // リターン。
        return $room;
    }

    /**
     * getRoomDefinition() のヘルパ。基本的なギミックの設定をする。
     */
    private function setupGimmicks(&$room, $stairNo, $deadEnd) {

        // 次のフロアに進むギミックを設定。
        $room['gimmicks']['ahead']['room'] = $stairNo + 1;

        // 前のフロアに戻るギミックを設定。
        if($stairNo == 1)
            $room['gimmicks']['back']['type'] = 'escape';
        else
            $room['gimmicks']['back']['room'] = $stairNo - 1;

        // ワープのギミックを処理する。階1なら自分のレベルに応じた階層へワープ。
        if($stairNo == 1) {
            $avatar = Service::create('Character_Info')->needAvatar($this->info['user_id']);
            $room['gimmicks']['warp']['room'] = min($avatar['level'], $deadEnd);

        // 階1でないならワープはナシ。
        }else {
            unset($room['gimmicks']['warp']);
        }

        // 階数表示のギミックをセット。
        $room['gimmicks']['show_stair'] = array(
            'trigger' => 'rotation',
            'rotation' => 1,
            'type' => 'lead',
            'leads' => array(sprintf("NOTIF " . AppUtil::getText("sphere_99999_now_step"), $stairNo)),
        );

        // "ahead" で初めて1階に来た場合は説明を出す。
        if($stairNo == 1  &&  $this->state['try_count'] == 1) {
            $room['gimmicks']['explain'] = array(
                'condition' => array('reason'=>'ahead'),
                'trigger' => 'rotation',
                'rotation' => 1,
                'type' => 'lead',
                'leads' => $this->replaceEmbedCode(AppUtil::getTexts("sphere_99999_explain")),
            );
        }

        //曜日クエストの情報を得る
        $reword_result = FieldBattle99999Util::getRewordDay();

        if($reword_result['str'] != ''){
            //曜日クエストの内容がある場合は最初の階だけ表示
            if($stairNo == 1 ){
                $room['gimmicks']['show_stair']['leads'] = array(sprintf("NOTIF " . AppUtil::getText("sphere_99999_now_step"), $stairNo), "NOTIF " . $reword_result['str']);
            }
        }

    }

    /**
     * getRoomDefinition() のヘルパ。出現モンスターの設定をする。
     */
    private function setupEnemies(&$room, $stair, $appearPoses) {

        // レアモンスター遭遇率上昇アイテムを使用していないかチェック。
        $avatarId = Service::create('Character_Info')->needAvatarId($this->info['user_id']);
        $attract = Service::create('Character_Effect')->getEffectValue($avatarId, Character_EffectService::TYPE_ATTRACT);

        //曜日クエストの情報を得る
        $reword_result = FieldBattle99999Util::getRewordDay();

        //エフェクトがかかってる場合は意味ないのでスキップ。
        if($attract < $reword_result['attract']){
            if($reword_result['reword']){
                if($reword_result['reword'] == FieldBattle99999Util::REAR_ENCOUNT_GAIN_DAY)
                    $attract = $reword_result['attract'];
            }
        }

        // レアモンスターの出現率を決定する。キーはrare_level。
        switch($attract) {
            case 1:
                $rates = array(2 =>Item_MasterService::SRARE_ENCOUNT_LV1 * 0.01, 1 => Item_MasterService::RARE_ENCOUNT_LV1 * 0.01);
                break;
            case 2:
                $rates = array(2 =>Item_MasterService::SRARE_ENCOUNT_LV2 * 0.01, 1 => Item_MasterService::RARE_ENCOUNT_LV2 * 0.01);
                break;
            case 3:
                $rates = array(2 =>Item_MasterService::SRARE_ENCOUNT_LV3 * 0.01, 1 => Item_MasterService::RARE_ENCOUNT_LV3 * 0.01);
                break;
            default:
                $rates = array(2 => Item_MasterService::SRARE_ENCOUNT * 0.01, 1 => Item_MasterService::RARE_ENCOUNT * 0.01);
        }

        // 階の定義に "enemies" キーがないなら作成しておく。
        if( !isset($stair['enemies']) )
            $stair['enemies'] = array();

        // "rares" を処理する
        if($stair['rares']) {
            foreach($stair['rares'] as $rare) {

                // 出現判定。出現しないなら次へ。
                if( (int)($rates[ $rare['rare_level'] ]*10000) < mt_rand(1, 10000) )
                    continue;

                // "enemies" の最後のエントリをレアモンスターに置き換える。
                // ただし、"rare_level" キーは不要で、"turn" キーは引き継ぐ必要がある。
                //unset($rare['rare_level']);
                $replace = array_pop($stair['enemies']);
                $rare['turn'] = $replace['turn'];
                $stair['enemies'][] = $rare;

                break;
            }
        }

        $roomName = $room['gimmicks']['ahead']['room'] -1;

        // "enemies" を処理する。
        foreach($stair['enemies'] as $enemy) {

            // レア度を取得。
            $rare_level = $enemy['rare_level'];
            unset($enemy['rare_level']);

            // 出現ターン数を取得。
            $turn = $enemy['turn'];
            unset($enemy['turn']);

            // 出現位置が決められていないなら決定。
            if(!$enemy['pos']) {
                $poses = $appearPoses[$turn];
                $enemy['pos'] = $poses[ array_rand($poses) ];
            }

            //100階以上を繰り返す場合で100階以上になった場合
            if(self::REPEAT_COUNT > 1 && $roomName > 100 && $roomName <= self::REPEAT_COUNT * 100){
                //200階、300階のような場合は0階は無いので・・
                if($roomName % 100 == 0)
                    $j = ((int)($roomName / 100) - 1);
                else
                    $j = (int)($roomName / 100);

                //敵のレベルを合わせる
                $enemy['add_level'] = 100 * $j;
            }

            if($rare_level == 1){
                $enemy['bgm'] = "bgm_bossbattle";
            }else if($rare_level == 2){
                $enemy['bgm'] = "bgm_bigboss";
            }

            // ギミックで出現させる。
            $room['gimmicks'][] = array(
                "trigger" => "rotation",
                "rotation" => $turn,
                "type" => "unit",
                "unit" => $enemy,
            );
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 最上階のさらに奥に進んだ場合の階を定義する。
     */
    protected function setupUnderConstruction($stair) {

        $data = Service::create('Field_Master')->needRecord('99999_end');

        $data['gimmicks']['back']['room'] = $stair - 1;

        return $data;
    }
}
