<?php

/**
 * SphereCommon付属のクラス。
 * SphereCommon::state の要素、
 * マップ上の経路や座標に関するメソッドを提供する。
 */
class SphereMap {

    // 一つのルームで定義できるマップチップの最大種類数
    const TIP_MAX_VARIETIES = 500;

    // 置物のtype値の、SWFでの番号のマップ
    static $TYPE_NO = array(
          'twinkle' => 1,      'goto' => 2,          'goto2' => 3,    'curious' => 4, 'escape' => 5,
          'ap_circle' => 6,    'hp_circle' => 7,      'torch' => 8,      'torch2' => 9,      'cristaltower' => 10,
          'street_light' => 11,    'bench1' => 12,      'watar_fall' => 13, 'gate' => 14, 'gate_open' => 15, 'gate_opened' => 16,
          'box1' => 17,    'dirt1' => 18, 'atras' => 19, 'bubble' => 20 , 'fungi' => 21 , 'fungi2' => 22, 'lamp' => 23, 'tanpopo' => 24,
          'blueflower' => 25, 'susuki' => 26, 'lamp2' => 27,'floor' => 28, 'moon' => 29, 'goto3' => 31,'mark1' => 32,'mark2' => 33,
          'egg' => 34, 'egg_birth' => 35, 'whitehall' => 36,  'whitehallout' => 37, 
    );

    // 静的メンバ。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたタイプでこのクラスのインスタンスを作成する。
     *
     * @param string    マップクラスを決める文字列。
     * @param object    このマップの所属先になっている SphereCommon インスタンス。
     * @return object   このクラス、あるいはそこから派生したクラスのオブジェクト。
     */
    public static function factory($className, $sphere) {

        // ユーティリティの種類が「標準」ならば。
        if(!$className) {
            $className = __CLASS__;

        // 非標準なら...
        }else {

            // クラス名を取得。
            $className = __CLASS__ . $className;

            // クラスファイルインクルード
            require_once(dirname(__FILE__).'/extends/'.$className.'.class.php');
        }

        // インスタンスを作成してリターン。
        return new $className($sphere);
    }


    // 初期化、操作系メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * フィールドマスタのルーム定義から、このインスタンスを初期化する。
     *
     * @param string    ルーム名
     * @param array     ルーム定義
     * @param string    理由を表す文字列。通常、クエスト開始なら "start" が、画面遷移した場合は遷移を
     *                  引き起こしたギミックの名前が入る。
     */
    public function initStructure($roomName, &$roomInfo, $reason) {

        // 初期化。
        $this->structure = array();
        $this->mapTips = array();
        $this->background = array();
        $this->overlayer1 = array();
        $this->overlayer2 = array();
        $this->cover = array();
        $this->head = array();
        $this->left = array();
        $this->right = array();
        $this->foot = array();

        // ルーム構造をロード。
        $room = Service::create('Room_Master')->getRoom($roomInfo['id'], $background, $overlayer1, $overlayer2, $cover, $mats, $head, $left, $right, $foot);

        // 定義されているチップ番号を内部で使用する番号に変換するので、その変換表を初期化する。
        $transTable = array();
        $internalNo = 1;

        // "extra_maptips" で定義されているチップを無条件で追加する。
        if($roomInfo['extra_maptips']) {
            foreach($roomInfo['extra_maptips'] as $squareNo)
                $transTable[$squareNo] = $internalNo++;
        }

        // ルーム構造を一行ずつ見ていく。
        foreach($room as $y => $line) {

            // 行初期化。
            $this->structure[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->structure[$y][$x] = $transTable[$squareNo];
            }
        }

        // cover構造を一行ずつ見ていく。
        foreach($cover as $y => $line) {

            // 行初期化。
            $this->cover[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->cover[$y][$x] = $transTable[$squareNo];
            }
        }

        // background構造を一行ずつ見ていく。
        foreach($background as $y => $line) {

            // 行初期化。
            $this->background[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->background[$y][$x] = $transTable[$squareNo];
            }
        }

        // overlayer1構造を一行ずつ見ていく。
        foreach($overlayer1 as $y => $line) {

            // 行初期化。
            $this->overlayer1[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->overlayer1[$y][$x] = $transTable[$squareNo];
            }
        }

        // overlayer2構造を一行ずつ見ていく。
        foreach($overlayer2 as $y => $line) {

            // 行初期化。
            $this->overlayer2[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->overlayer2[$y][$x] = $transTable[$squareNo];
            }
        }

        // head構造を一行ずつ見ていく。
        foreach($head as $y => $line) {

            // 行初期化。
            $this->head[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->head[$y][$x] = $transTable[$squareNo];
            }
        }

        // left構造を一行ずつ見ていく。
        foreach($left as $y => $line) {

            // 行初期化。
            $this->left[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->left[$y][$x] = $transTable[$squareNo];
            }
        }

        // right構造を一行ずつ見ていく。
        foreach($right as $y => $line) {

            // 行初期化。
            $this->right[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->right[$y][$x] = $transTable[$squareNo];
            }
        }

        // foot構造を一行ずつ見ていく。
        foreach($foot as $y => $line) {

            // 行初期化。
            $this->foot[$y] = array();

            // 一列ずつ見ていく。
            foreach($line as $x => $squareNo) {

                // 初めて出てくるチップなら変換表に追加。
                if(!array_key_exists($squareNo, $transTable))
                    $transTable[$squareNo] = $internalNo++;

                // 内部で使用する番号で構造を作成していく。
                $this->foot[$y][$x] = $transTable[$squareNo];
            }
        }

        // 登場したマップチップの最大数をチェック。
        if(self::TIP_MAX_VARIETIES < count($transTable))
            throw new MojaviException('マップチップの種類数が最大を超えています。' . count($transTable) . "個");

        // 登場したマップチップの情報を取得。
        $tipNos = array_keys($transTable);
        $squares = Service::create('Square_Master')->getRecordsIn($tipNos);

        // マップチップのマスタを作成。
        foreach($transTable as $squareNo => $internalNo) {
            $this->mapTips[$internalNo] = array(
                'graph_no' => $squareNo,
                'cost' => $squares[$squareNo]['cost'],
                'cost_aquatic' => $squares[$squareNo]['cost_aquatic'],
                'cost_amphibia' => $squares[$squareNo]['cost_amphibia'],
            );
        }

        // 敷物をメンバ変数に保持。
        $this->mats = $mats;

        // 定義されている置物を設置する。
        $this->ornaments = array();
        if(isset($roomInfo['ornaments'])) {
            foreach($roomInfo['ornaments'] as $orn)
                $this->addOrnament($orn);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * マップデータを受け取って、このインスタンスを初期化する。
     *
     * @param array     sphere_info テーブルの state.structure
     * @param array     sphere_info テーブルの state.maptips
     * @param array     sphere_info テーブルの state.mats
     * @param array     sphere_info テーブルの state.ornaments
     */
    public function loadStructure($structure, $mapTips, $mats, $ornaments, $background, $overlayer1, $overlayer2, $cover, $head, $left, $right, $foot) {

        $this->structure = $structure;
        $this->background = $background;
        $this->overlayer1 = $overlayer1;
        $this->overlayer2 = $overlayer2;
        $this->cover = $cover;
        $this->head = $head;
        $this->left = $left;
        $this->right = $right;
        $this->foot = $foot;
        $this->mats = $mats;
        $this->mapTips = $mapTips;
        $this->ornaments = $ornaments;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された置物をこのマップに追加する。
     *
     * @param array     置物の情報。フィールド情報で定義されているもの。
     * @param int       置物番号。
     */
    public function addOrnament($orn) {

        // 番号を決定。現在のキー番号最大+1。
        $ornNo = (count($this->ornaments) == 0) ? 1 : max(array_keys($this->ornaments)) + 1;

        // スフィアに配置。
        $this->ornaments[$ornNo] = $orn;

        // リターン。
        return $ornNo;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたNOの置物を入れ替える。この前に必ず
     *
     * @param array     置物の情報。フィールド情報で定義されているもの。
     * @param int       置物番号。
     */
    public function changeOrnament(&$leads, $ornNo, $orn) {
        //削除
        $leads[] = $this->removeOrnament($ornNo);

        // スフィアに配置。
        $this->ornaments[$ornNo] = $orn;

        //指揮を作成
        $leads[] = sprintf('ORNAM %03d %02d %02d %02d', $ornNo, self::$TYPE_NO[$orn["type"]], $orn["pos"][0], $orn["pos"][1]);

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された番号の置物を削除する。
     *
     * @param int       置物番号。
     * @return string   置物削除をSWFに伝える指揮。
     */
    public function removeOrnament($ornNo) {

        unset($this->ornaments[$ornNo]);

        return sprintf('ORNAM %03d', $ornNo);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された敷物を削除する。
     *
     * @param string    敷物の名前。
     * @return array    敷物削除をSWFに伝える指揮の配列。
     */
    public function removeMat($name) {

        // 指定された敷物を取得＆削除。
        $curtain = $this->mats['curtain'][$name];
        unset($this->mats['curtain'][$name]);

        // 削除した後の、敷物状態を取得。
        $specs = $this->getMatSpecs();

        // 削除された敷物が存在していた行だけをSWFへ伝える。
        $result = array();
        for($y = $curtain['pos'][1] ; $y <= $curtain['rb'][1] ; $y++)
            $result[] = sprintf('RPMAT %02d %s', $y, $specs[$y]);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された座標の地形を変更する。
     *
     * @param array     変更する座標。
     * @param int       変更後の地形ID
     * @return string   地形変更をSWFに伝える指揮。
     */
    public function changeSquare($pos, $squareId) {

        // 地形IDから内部地形番号を取得。
        $tipNo = $this->getSwfTipNo($squareId);
        if(!$tipNo)
            throw new MojaviException('読み込まれていない地形チップに変更しようとした');

        // データ上の地形を変更。
        $this->structure[ $pos[1] ][ $pos[0] ] = $tipNo;

        // SWFにそれを伝える指揮を返す。
        return sprintf('RPBG1 %02d %02d %04d', $pos[0], $pos[1], $tipNo);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された領域に含まれるすべての座標の地形を変更する。
     *
     * @param array     変更する領域。getMovables() の戻り値のような形式。
     * @param int       変更後の地形ID
     * @return array    地形変更をSWFに伝える指揮の配列。
     */
    public function changeSquareOnRegion($region, $squareId) {

        $leads = array();

        // 指定された領域に含まれるマスを一つずつ変更していく。
        foreach($region as $y => $line) {
            foreach($line as $x => $dummy) {
                $leads[] = $this->changeSquare(array($x,$y), $squareId);
            }
        }

        return $leads;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された矩形に含まれるすべての座標の地形を変更する。
     *
     * @param int       変更後の地形ID
     * @param array     変更する左上座標。
     * @param array     変更する右下座標。
     * @param array     指定された矩形にマスクを適用したい場合はそのマスク。
     * @return array    地形変更をSWFに伝える指揮の配列。
     */
    public function changeSquareOnRect($squareId, $pos, $rb, $mask = null) {

        throw new MojaviException('未実装');
    }


    // 参照系メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * マップの構造を返す。
     * sphere_info テーブルの state.structure に該当する値。
     */
    public function getStructure() {
        return $this->structure;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(background)の構造を返す。
     */
    public function getBackground() {
        return $this->background;
    }
    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(overlayer1)の構造を返す。
     */
    public function getOverlayer1() {
        return $this->overlayer1;
    }
    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(overlayer2)の構造を返す。
     */
    public function getOverlayer2() {
        return $this->overlayer2;
    }
    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(cover)の構造を返す。
     */
    public function getCover() {
        return $this->cover;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(head)の構造を返す。
     */
    public function getHead() {
        return $this->head;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(left)の構造を返す。
     */
    public function getLeft() {
        return $this->left;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(right)の構造を返す。
     */
    public function getRight() {
        return $this->right;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * マップ(foot)の構造を返す。
     */
    public function getFoot() {
        return $this->foot;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * マップの敷物一覧を返す。
     * sphere_info テーブルの state.mats に該当する値。
     */
    public function getMats() {
        return $this->mats;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * マップチップのマスタを返す。
     * sphere_info テーブルの state.maptips に該当する値。
     */
    public function getMapTips() {
        return $this->mapTips;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * このスフィアに存在する置物の一覧を返す。
     */
    public function getOrnaments() {
        return $this->ornaments;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された座標の移動コストを返す。
     *
     * @param int       X座標
     * @param int       Y座標
     * @param object    移動するユニット。特に指定せず標準コストを取得する場合はnull。
     */
    public function getCost($x, $y, $unit = null) {

        $pattern = $unit ? $unit->getProperty('mobility') : '';
        if($pattern)  $pattern = '_'.$pattern;

        //structureからコストを得る
        $cost = $this->mapTips[ $this->structure[$y][$x] ]["cost{$pattern}"];

        //overlayer1からコストを得る。ある場合は上書きとする。
        if($this->overlayer1[$y][$x] != "" && $this->mapTips[ $this->overlayer1[$y][$x]]["graph_no"] > 0){
            $cost = $this->mapTips[ $this->overlayer1[$y][$x] ]["cost{$pattern}"];
        }

        //overlayer2からコストを得る。ある場合は上書きとする。
        if($this->overlayer2[$y][$x] != "" && $this->mapTips[ $this->overlayer2[$y][$x]]["graph_no"] > 0){
            $cost = $this->mapTips[ $this->overlayer2[$y][$x] ]["cost{$pattern}"];
        }

        return $cost;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * どの座標にどのユニットが所在しているかを表すマップ(配列)を作成する。
     *
     * @return array    1次元をY、2次元をX座標とする配列。
     *                  値はユニット。どのユニットも所在していない要素は作成されない。
     */
    public function getUnitMap() {

        $map = array();

        // ユニットをひとつずつ見ていく。
        foreach($this->sphere->getUnits() as $unit) {
            $pos = $unit->getPos();
            $map[ $pos[1] ][ $pos[0] ] = $unit;
        }

        return $map;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 敷物の分布をSWFに伝えるための文字列を返す。
     *
     * @return array    敷物の分布をSWFに伝えるための文字列の配列。
     */
    public function getMatSpecs() {

        // まずはすべて "0" で埋めた分布図を作成。
        $line = str_repeat('0', count($this->structure[0]));
        $matLines = array_fill(0, count($this->structure), $line);

        // 敷物を一つずつ見ていく。
        foreach($this->mats as $type => $mats) {

            // 現在、敷物は暗幕しかない。それ以外は対応しない。
            if($type != 'curtain')
                continue;

            foreach($mats as $mat) {

                // 敷物の範囲内をすべて暗幕を表す "1" に置き換える。
                for($y = $mat['pos'][1] ; $y <= $mat['rb'][1] ; $y++) {
                    for($x = $mat['pos'][0] ; $x <= $mat['rb'][0] ; $x++) {

                        // "mask" キーがある場合、"0"の地点はスキップする。
                        if( isset($mat['mask']) ) {

                            $innerX = $x - $mat['pos'][0];
                            $innerY = $y - $mat['pos'][1];
                            if($mat['mask'][$innerY]{$innerX} == '0')
                                continue;
                        }

                        $matLines[$y]{$x} = '1';
                    }
                }
            }
        }

        // リターン。
        return $matLines;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された地形チップの、FLASH上における番号を返す。
     *
     * @param int   地形番号
     * @return int  FLASH上における番号。指定の地形チップを現在使用していない場合は 0 が返る。
     */
    public function getSwfTipNo($squareNo) {

        foreach($this->mapTips as $internalNo => $data) {

            if($data['graph_no'] == $squareNo)
                return $internalNo;
        }

        return 0;
    }


    // 調査・計算系メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標に位置しているユニットを返す。
     *
     * @param array     座標。第0要素にX、第1要素にYを格納している配列。
     * @param array     getUnitMap()で取得したユニット所在マップがある場合はここに渡す。
     *                  少し処理が速くなる。
     * @return object   指定された座標に位置しているユニット。
     *                  ユニットがいない場合は null。
     */
    public function findUnitOn($point, $unitMap = null) {

        // ユニット所在マップを取得。
        if(is_null($unitMap))
            $unitMap = $this->getUnitMap();

        // リターン。
        return isset($unitMap[$point[1]][$point[0]]) ? $unitMap[$point[1]][$point[0]] : null;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標に位置している暗幕の名前を返す。
     *
     * @param array     座標。第0要素にX、第1要素にYを格納している配列。
     * @return array    指定された座標に位置している暗幕の名前の配列。
     */
    public function findCurtainOn($point) {

        if( !isset($this->mats['curtain']) )
            return array();

        return $this->findOn($point, $this->mats['curtain']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標に位置しているオブジェクトを返す。
     *
     * @param array     座標。
     * @param array     オブジェクトの配列。キー"pos" を持っていないものは無視される。
     *                  キー"rb", "mask" を持っている場合はそれも加味される。
     * @return mixed    指定された座標に位置しているオブジェクトのインデックス値の配列。
     *                  何もない場合はカラ配列。
     */
    public static function findOn($point, &$array) {

        $result = array();

        // 検査対象を一つずつ見ていく。
        foreach($array as $index => $material) {

            // キー"pos" を持っていないものは無視。
            if( !array_key_exists('pos', $material) )
                continue;

            // 対象に当たっているならヒット。
            if( self::isHit($point, $material) )
                $result[] = $index;
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標が、指定された対象物に当たっているかどうかを返す。
     *
     * @param array     調べたい座標。
     * @param array     対象物。少なくともキー "pos" を持っていること。
     *                  "rb", "mask" を持っている場合はそれも加味される。
     * @return bool     当たっているなら true、当たっていないなら false。
     */
    public static function isHit($point, $aim) {

        // キー "rb" がないなら1点のみで当たり判定する。
        if( !isset($aim['rb']) )
            return ($point[0] == $aim['pos'][0]  &&  $point[1] == $aim['pos'][1]);

        // 対象よりも左・上に位置しているしているなら当たってない。
        if($point[0] < $aim['pos'][0]  ||  $point[1] < $aim['pos'][1])
            return false;

        // 対象よりも右・下に位置しているしているなら当たってない。
        if($aim['rb'][0] < $point[0]  ||  $aim['rb'][1] < $point[1])
            return false;

        // キー "mask" を持っている場合、"0"の地点はヒットしない。
        if( isset($aim['mask']) ) {

            $innerX = $point[0] - $aim['pos'][0];
            $innerY = $point[1] - $aim['pos'][1];
            if( !$aim['mask'][$innerY]{$innerX} )
                return false;
        }

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標から座標へのマンハッタン距離を返す。
     */
    public function getManhattanDist($from, $to) {

        return abs($to[0] - $from[0]) + abs($to[1] - $from[1]);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標の隣の座標を配列で返す。「すぐ隣でなく、2マス隣」などの指定も可能。
     *
     * @param array     基準の座標
     * @param int       基準からいくつ離れたマスが必要か。
     * @return array    指定された座標から指定の距離をもつ座標の配列。
     *                  マップの範囲を超えるマスは返されない。
     *
     * 例1) 次の位置を基準にコールすると...
     *         □□□□□□□□
     *         □□□■□□□□
     *         □□□□□□□□
     *         □□□□□□□□
     *      次の座標の配列が返る。
     *         □□□■□□□□
     *         □□■□■□□□
     *         □□□■□□□□
     *         □□□□□□□□
     * 例2) 距離2でコールすると、次の座標の配列が返る。
     *         □□■□■□□□
     *         □■□□□■□□
     *         □□■□■□□□
     *         □□□■□□□□
     */
    public function getNeighbors($point, $dist = 1) {

        // 戻り値初期化。
        $result = array();

        // X座標で左から追加していく。
        //                          □□□□□□□□                □□■□□□□□
        // 上の例2で説明すると、まず□■□□□□□□を追加して、次に□■□□□□□□を追加していく感じ。
        //                          □□□□□□□□                □□■□□□□□
        //                          □□□□□□□□                □□□□□□□□
        for($xDist = -1 * $dist ; $xDist <= $dist ; $xDist++) {

            // Y軸における、基準点からの距離を取得。
            $yDist = $dist - abs($xDist);

            // まず下側を追加。次に上側を追加。
            $result[] = array($point[0] + $xDist, $point[1] + $yDist);
            if($yDist > 0)
                $result[] = array($point[0] + $xDist, $point[1] - $yDist);
        }

        // マップの範囲を超えるマスを削除。
        foreach($result as $index => $pos) {
            if($pos[0] < 0  ||  $pos[0] >= count($this->structure[0])  ||  $pos[1] < 0  ||  $pos[1] >= count($this->structure))
                unset($result[$index]);
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標の周りでフリー(どのユニットもいない)になっているマスのうち、近いものを返す。
     *
     * @param array     フリーかどうかしらべたいマス。
     * @param object    進入可能を調べるために使用するユニット。
     *                  指定しなかった場合、進入可能かどうかは考慮されない。
     *                  指定した場合でも、第一引数に指定した座標だけは考慮されない。
     * @return array    指定されたマス、あるいは、そこがフリーでないなら、近くでフリーの座標。
     */
    public function getFreePoint($pos, $unit = null) {

        // ユニットの所在マップを取得。
        $unitMap = $this->getUnitMap();

        // 指定された座標がフリーならそのまま返す。
        if( !isset($unitMap[$pos[1]][$pos[0]]) )
            return $pos;

        // 距離を離しながらフリーポイントを見つけるまでループ。一応セーフティーをかけておく。
        for($i = 1 ; $i <= 8 ; $i++) {

            // 隣接座標を取得。
            $neighbors = $this->getNeighbors($pos, $i);

            // フリーな場所を見つけたらをそれをリターン。
            foreach($neighbors as $nei) {
                if( (!$unit  ||  $this->getCost($nei[0], $nei[1], $unit) < 9990)  &&  !isset($unitMap[$nei[1]][$nei[0]]) )
                    return $nei;
            }
        }

        // ここまでくるのはエラー。
        throw new MojaviException('隣接フリーポイントを8回試行しても見つけられなかった');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標から移動可能なマスを列挙する。
     *
     * @param array     移動元の座標。
     * @param int       移動力
     * @param object    移動するユニットを表す SphereUnit オブジェクト。
     *                  効果範囲などの、地形コストやZOCを無視するものはNULLを指定する。
     * @return array    第1次元にY座標、第2次元にX座標を格納している配列。
     *                  要素があれば移動可能、要素がないのは移動不可能であることを表す。
     */
    public function getMovables($from, $movePow, $unit = null) {

        // 戻り値初期化。
        $movables = array();

        // ZOC 判定の必要がある場合はユニット所在マップを取得しておく。
        $unitMap = $unit ? $this->getUnitMap() : null;

        // 移動元の座標をマークして、あとは再帰的に処理する。
        $this->markMovables($movables, $from, $movePow, $unit, $unitMap);

        return $movables;
    }

    /**
     * getMovablesのヘルパ。
     * 指定された座標をマークして、さらに隣接マスに踏み込めるなら再帰してマークしていく。
     */
    private function markMovables(&$movables, $point, $pow, $unit, $unitMap) {

        // 指定されたマスをマーク。移動力残余を入れておく。
        $movables[ $point[1] ][ $point[0] ] = $pow;

        // これ以上移動できないなら隣接マスを調べる必要はない。
        if($pow <= 0)
            return;

        // 隣接マスを列挙。
        $neighbors = $this->getNeighbors($point);

        // 隣接マスを一つずつ処理する。
        foreach($neighbors as $nei) {

            // 隣接マスの移動コストを取得。
            $cost = $unit ? $this->getCost($nei[0], $nei[1], $unit) : 1;

            // 踏み込めないならマークできない。
            if($pow < $cost)
                continue;

            // すでにマークしたことがある場合、より大きな移動力残余で踏み込めないなら
            // 新たにマークしない。
            if(isset($movables[$nei[1]][$nei[0]])  &&  $movables[$nei[1]][$nei[0]] >= $pow - $cost)
                continue;

            // ZOCの判定の必要がある場合。
            if($unit) {

                // 踏み込もうとしている場所にユニットがいないかチェック。
                // いる場合に、そのユニットの所属が移動者と違う場合は踏み込めない。
                $findUnit = $this->findUnitOn($nei, $unitMap);
                if($findUnit  &&  $unit->getUnion() != $findUnit->getUnion())
                    continue;
            }

            // ここまでくればマーク可能。
            $this->markMovables($movables, $nei, $pow - $cost, $unit, $unitMap);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標が指定された領域に入っているかどうかを返す。
     *
     * @param array     領域に入っているか調べたい座標。
     * @param array     領域。getMovables() の戻り値。
     * @return bool     入っているならtrue、入ってないなら false。
     */
    public static function inRegion($point, $region) {

        return isset($region[ $point[1] ][ $point[0] ]);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された二つの領域のうち、交差している部分のみを表す領域を返す。
     *
     * @param array     領域1。getMovables() の戻り値。
     * @param array     領域2。同じく。
     * @return array    領域1,2のうち、交差している部分のみを表す領域。
     */
    public static function intersectRegion($region1, $region2) {

        $result = array();

        foreach($region1 as $y => $line) {
            foreach($line as $x => $dummy) {
                if( self::inRegion(array($x, $y), $region2) )
                    $result[$y][$x] = 1;
            }
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標から別の座標までの最短経路とコストを求める。
     *
     * @param object    移動するユニットを表す SphereUnit オブジェクト。
     *                  効果範囲などの、地形コストやZOCを無視するものはgetManhattanDist()で代用すること。
     * @param array     移動先の座標。
     * @param reference ここで指定した変数に経路が格納される。
     *                  経路は文字列で、1マスの移動が一文字で表現されている。
     *                  文字は "2":上, "4":左, "6":右, "8":下 のいずれか。
     *                      例) 66622248    右右右上上上左下
     *                  到達不能な場合でも、マンハッタン距離が最も小さくなる座標までの経路が格納される。
     * @return int      最短経路の移動コスト。隣接座標までは行けるが目標座標に踏み込めない場合は
     *                  9990以上の値。隣接座標に行くことも出来ない場合は0x7FFFFFFF。
     */
    public function getRoute($unit, $to, &$route = null) {

        // 実装は「A*」。用語等はインターネットを参照。

        // 初期化。
        $from = $unit->getPos();
        $route = '';

        // 移動元と先が同じという特殊ケースを処理する。
        if($from == $to)
            return 0;

        // ユニット所在マップを取得しておく。
        $unitMap = $this->getUnitMap();

        // オープンしたマスの情報を保持する配列。第1次元にY、第2次元にX座標の値を格納する。
        $map = array();

        // 現在オープンしているマスのリスト。$mapの要素への参照の配列。
        $opens = array();

        // 移動元を無条件にオープンする。
        $map[ $from[1] ][ $from[0] ] = array(
            'pos' => $from,
            'prev' => null,
            'cost' => 0,
            'heur' => $this->getManhattanDist($from, $to) * 10,
        );
        $opens[] = &$map[ $from[1] ][ $from[0] ];

        // 最も肉薄したマスを保持する変数を初期化。
        $nearest = $map[ $from[1] ][ $from[0] ];

        // ゴールにたどり着くまでループ。
        while(true) {

            // オープンマスがなくなってしまったら到達はできない。
            if(count($opens) == 0) {

                // 最も肉薄したマスを目標とする。
                $focusSq = $nearest;

                // 到達できないことをあらわすコストをセット。
                $cost = 0x7FFFFFFF;

                // ループを抜ける。
                break;
            }

            // オープンリストから最もコストが低くて、後に追加されたものを取得。
            $focus = null;
            foreach($opens as $index => $sq) {
                if(
                        is_null($focus)
                    ||  $focus['cost'] + $focus['heur']  >=  $sq['cost'] + $sq['heur']
                ) {
                    $focus = $sq;
                    $focusIndex = $index;
                }
            }

            // 取得したマスをクローズ。(オープンリストから削除)
            unset($opens[$focusIndex]);

            // 隣接する４マスを取得。
            $neighbors = $this->getNeighbors($focus['pos']);

            // 最短経路が複数ある場合になるべく散らばるように、たまに順序を変える。
            if($unit->getNo() % 2)
                $neighbors = array_reverse($neighbors);

            // 隣接マスを一つずつ処理する。
            foreach($neighbors as $nei) {

                // オープン。ゴールにたどり着いたら...
                if( $this->aStarSquareOpen($map, $opens, $nearest, $focus, $nei, $to, $unit, $unitMap) ) {

                    // ゴールのマスを取得。
                    $focusSq = $map[ $to[1] ][ $to[0] ];

                    // 算出された移動コストをリターン用に取っておく。
                    $cost = $focusSq['cost'];

                    // ループを抜ける。
                    break 2;
                }
            }
        }

        // 目標マスから親マスをたどって、経路を逆順で作成していく。
        while($focusSq['prev']) {

            $path = 5;
            $path +=  $focusSq['pos'][0] - $focusSq['prev'][0];
            $path += ($focusSq['pos'][1] - $focusSq['prev'][1]) * 3;

            $route .= $path;

            $focusSq = $map[ $focusSq['prev'][1] ][ $focusSq['prev'][0] ];
        }

        // 逆順で作成したルートをひっくり返して正順にする。
        $route = strrev($route);

        // 移動コストをリターン。
        return $cost;
    }

    /**
     * getRouteのヘルパ関数。
     * 指定されたマスがオープンできるかどうか調べて、できるならオープンする。
     * 戻り値はそこがゴールだったかどうか。
     */
    private function aStarSquareOpen(&$map, &$opens, &$nearest, $prevSq, $pos, $goal, $unit, $unitMap) {

        // 踏み込もうとしているマスの移動コストを取得。
        $cost = $this->getCost($pos[0], $pos[1], $unit);

        // すでにオープンしたことがある場合、それよりも低いコストで踏み込めないなら再オープンしない。
        $opened = $map[$pos[1]][$pos[0]];
        if($opened) {
            if($opened['cost'] <= $prevSq['cost'] + $cost)
                return false;
        }

        // オープンするかどうか判断する。ただし、到達点である場合はオープンする。
        if($pos != $goal) {

            // 踏み込めないマスならオープンしない。
            if($cost >= 9990)
                return false;

            // 踏み込もうとしている場所にユニットがいないかチェック。
            $findUnit = $this->findUnitOn($pos, $unitMap);

            // いる場合に、そのユニットの所属が移動者と違う場合は踏み込めない。
            if($findUnit  &&  $unit->getUnion() != $findUnit->getUnion())
                return false;
        }

        // ここまで来たらオープンする。
        $map[$pos[1]][$pos[0]] = array(
            'pos' => $pos,
            'prev' => $prevSq['pos'],
            'cost' => $prevSq['cost'] + $cost,
            'heur' => $this->getManhattanDist($pos, $goal) * 10,
        );
        $opens[] = &$map[$pos[1]][$pos[0]];

        // 現在の最近傍マスよりもさらに肉薄したなら入れ替える。
        if($map[$pos[1]][$pos[0]]['heur'] < $nearest['heur'])
            $nearest = $map[$pos[1]][$pos[0]];

        // 踏み込んだマスはゴールかどうかを返す。
        return ($pos == $goal);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標から、指定された経路をたどった結果、どこの座標に行き着くかを返す。
     *
     * @param array     開始点座標
     * @param string    経路。getRoute() で取得するもの。
     *                  $movablesを指定した場合、ここで指定した変数に、移動可能範囲までにカットされた
     *                  経路が格納される。
     * @param array     省略可能。移動可能範囲。getMovables() で取得するもの。
     *                  指定した場合は、この範囲内で行けるところまでに制限される。
     * @return array    行き着く座標。
     */
    public function walk($from, &$path, $movables = null) {

        // 開始点から始める。
        $point = $from;

        // 経路を一つずつたどっていく。
        for($i = 0 ; $i < strlen($path) ; $i++) {

            // 一つ進む前の座標をとっておく。
            $prev = $point;

            // 経路から次の方向を取得。
            $dir = $path{$i};

            // 一つ進む。
            $point[0] += ($dir-5) % 3;
            $point[1] += (int)(($dir-5) / 3);

            // 引数 $movables が指定されている場合に、そこが範囲外でないか調べる。
            // 範囲外ならリターン。
            if($movables  &&  !isset($movables[ $point[1] ][ $point[0] ]) ) {
                $path = substr($path, 0, $i);
                return $prev;
            }
        }

        // 到着点をリターン。
        return $point;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された座標から座標までの、障害物を無視した経路を求める。
     *
     * @param array     移動元の座標。
     * @param array     移動先の座標。
     * @param string    最初に横から合わせるなら "x"、縦から合わせるなら "y" を指定する。
     * @return string   障害物を無視した経路。
     */
    public function getThroughRoute($from, $to, $axis = 'y') {

        $dist = $to[0] - $from[0];
        $xRoute = str_repeat( ($dist > 0) ? '6' : '4', abs($dist) );

        $dist = $to[1] - $from[1];
        $yRoute = str_repeat( ($dist > 0) ? '8' : '2', abs($dist) );

        return ($axis == 'x') ? $xRoute.$yRoute : $yRoute.$xRoute;
    }


    // protectedメンバ。
    //=====================================================================================================

    // このマップの所属先になっている SphereCommon インスタンス。
    protected $sphere;

    // sphere_info テーブルの state.structure, state.maptips, state.mats, state.ornaments。
    protected $structure;
    protected $background;
    protected $overlayer1;
    protected $overlayer2;
    protected $cover;
    protected $head;
    protected $left;
    protected $right;
    protected $foot;
    protected $mapTips;
    protected $mats;
    protected $ornaments;


    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     *
     * @param object    このマップの所属先になっている SphereCommon インスタンス。
     */
    protected function __construct($sphere) {

        $this->sphere = $sphere;
    }
}
