<?php

/**
 * フィールドルームを見るアクション。
 * デバックメニュー。
 */
class EditRoomAction extends AdminBaseAction {

    public function execute() {

        define('MAPTIP_DIR', IMG_RESOURCE_DIR . '/maptip');

        // マップチップグラフィックを返すように指定されているなら...
        if(isset($_GET['tip'])) {
            header('Content-Type: image/png');
            readfile( MAPTIP_DIR . sprintf('/%04d.png', $_GET['tip']) );
            return View::NONE;
        }

        // 保存フォームが送信されているならそれ用の処理。制御は戻ってこない。
        if($_POST)
            $this->processSave();

        // id が指定されているなら...
        if(isset($_GET['id'])) {

            $roomSvc = new Room_MasterService();

            $this->setAttribute('category', Square_MasterService::$CATEGORY);
            // マップチップの一覧を取得。
            $this->setAttribute('tips', $this->getTips());

            // 指定されたルームを取得。
            $record = $roomSvc->getRecord($_GET['id']);
            if($record) {
                $structure = $roomSvc->parseStructure($record['structure']);
                $cover = $roomSvc->parseStructure($record['cover']);
                $background = $roomSvc->parseStructure($record['background']);
                $overlayer1 = $roomSvc->parseStructure($record['overlayer1']);
                $overlayer2 = $roomSvc->parseStructure($record['overlayer2']);

                $head = $roomSvc->parseStructure($record['structure_head']);
                $left = $roomSvc->parseStructure($record['structure_left']);
                $right = $roomSvc->parseStructure($record['structure_right']);
                $foot = $roomSvc->parseStructure($record['structure_foot']);

                $i = 0;
                foreach($structure as $st){
                    foreach($st as $key => $value){
                        $structure[$i][$key] = array("id" => $value, "edit"=>"structure");
                    }
                    $i++;
                }

                $i = 0;
                foreach($background as $st){
                    foreach($st as $key => $value){
                        $background[$i][$key] = array("id" => $value, "edit"=>"background");
                    }
                    $i++;
                }

                $i = 0;
                foreach($overlayer1 as $st){
                    foreach($st as $key => $value){
                        $overlayer1[$i][$key] = array("id" => $value, "edit"=>"overlayer1");
                    }
                    $i++;
                }

                $i = 0;
                foreach($overlayer2 as $st){
                    foreach($st as $key => $value){
                        $overlayer2[$i][$key] = array("id" => $value, "edit"=>"overlayer2");
                    }
                    $i++;
                }

                $i = 0;
                foreach($cover as $st){
                    foreach($st as $key => $value){
                        $cover[$i][$key] = array("id" => $value, "edit"=>"cover");
                    }
                    $i++;
                }

                $i = 0;
                foreach($head as $h){
                    foreach($h as $key => $value){
                        $head[$i][$key] = array("id" => $value, "edit"=>"head");
                    }
                    $i++;
                }
                $i = 0;
                foreach($left as $l){
                    foreach($l as $key => $value){
                        $left[$i][$key] = array("id" => $value, "edit"=>"left");
                    }
                    $i++;
                }
                $i = 0;
                foreach($right as $l){
                    foreach($l as $key => $value){
                        $right[$i][$key] = array("id" => $value, "edit"=>"right");
                    }
                    $i++;
                }
                $i = 0;
                foreach($foot as $l){
                    foreach($l as $key => $value){
                        $foot[$i][$key] = array("id" => $value, "edit"=>"foot");
                    }
                    $i++;
                }

                if($head[0] && $left[0] && $right[0] && $foot[0]){
                    $i = 0;

                    foreach($head as $h){
                        foreach($h as $key => $value){
                            $room[$i][$key] = $value;
                        }
                        $i++;
                    }

                    $i = count($head);
                    foreach($left as $l){
                        foreach($l as $key => $value){
                            $room[$i][$key] = $value;
                        }
                        $i++;
                    }

                    $i = count($head);
                    foreach($structure as $st){
                        foreach($st as $key => $value){
                            if(!$left){
                                $room[$i][0] = 9999;
                                $room[$i][1] = 9999;
                            }
                            $room[$i][$key + count($left[0])] = $value;
                        }
                        $i++;
                    }

                    $i = count($head);
                    if($right[0]){
                        foreach($right as $r){
                            foreach($r as $key => $value){
                                $room[$i][$key + count($left[0]) + count($structure[0])] = $value;
                            }
                            $i++;
                        }
                    }
                    
                    $i = count($room);
                    if($foot[0]){
                        foreach($foot as $f){
                            foreach($f as $key => $value){
                                $room[$i][$key] = $value;
                            }
                            $i++;
                        }
                    }

                    $this->setAttribute( 'head_count', count($head) );
                    $this->setAttribute( 'left_count', count($left[0]) );

                }else{
                    $room = $structure;
                    $this->setAttribute( 'head_count', 0 );
                    $this->setAttribute( 'left_count', 0 );
                }

                $this->setAttribute( 'room', $room );

                $this->setAttribute( 'structure', $structure );
                $this->setAttribute( 'cover', $cover );
                $this->setAttribute( 'background', $background );
                $this->setAttribute( 'overlayer1', $overlayer1 );
                $this->setAttribute( 'overlayer2', $overlayer2 );

                $this->setAttribute( 'head', $head );
                $this->setAttribute( 'left', $left );
                $this->setAttribute( 'right', $right );
                $this->setAttribute( 'foot', $foot );

                $this->setAttribute( 'mats', $record['mats'] );

                $this->setAttribute( 'tip_count', $this->useMaptipCount());
                $this->setAttribute( 'max_tip_count', SphereMap::TIP_MAX_VARIETIES);
                $this->setAttribute( 'show_structure', $_GET['show_structure'] );
                $this->setAttribute( 'show_cover', $_GET['show_cover'] );
                $this->setAttribute( 'show_background', $_GET['show_background'] );
                $this->setAttribute( 'show_overlayer1', $_GET['show_overlayer1'] );
                $this->setAttribute( 'show_overlayer2', $_GET['show_overlayer2'] );

            }
        }else{
            $this->setAttribute( 'show_structure', true );
            $this->setAttribute( 'show_cover', true );
            $this->setAttribute( 'show_background', true );
            $this->setAttribute( 'show_overlayer1', true );
            $this->setAttribute( 'show_overlayer2', true );
        }

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * マップチップの一覧を返す。
     */
    private function getTips() {

        // 全レコード取得。
        //$records = Service::create('Square_Master')->getAllRecords();
        $fromId = 0;
        $toId = 9999;
        $category = null;

        foreach(Square_MasterService::$CATEGORY as $key=>$value){
            if($_GET[$key] != ""){
                $category[] = $key;
            }
        }

        if(isset($_GET["fromId"]) && $_GET["fromId"] > 0)
            $fromId = $_GET["fromId"];

        if(isset($_GET["toId"]) && $_GET["toId"] > 0)
            $toId = $_GET["toId"];

        $records = Service::create('Square_Master')->selectSquare($fromId, $toId, $category);

        // 一つずつ見ていく。
        $resultset = array();
        foreach($records as &$record) {

            // 擬似列 "tip_no" を埋め込む
            $record['tip_no'] = sprintf('%04d', $record['square_id']);

            // レイアウト上、何段目に配置するかによって振り分けていく。
            $resultset[ (int)($record['square_id'] / 100) ][] = $record;
        }

        // リターン。
        return $resultset;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 保存フォームの送信を処理する。
     */
    private function processSave() {

        $err = "";

        $data_background = trim($_POST['data_background']);
        $data_overlayer1 = trim($_POST['data_overlayer1']);
        $data_overlayer2 = trim($_POST['data_overlayer2']);
        $data_cover = trim($_POST['data_cover']);
        $data_head = trim($_POST['data_head']);
        $data_left = trim($_POST['data_left']);
        $data_right = trim($_POST['data_right']);
        $data_foot = trim($_POST['data_foot']);

        $data = Service::create('Room_Master')->parseStructure($_POST['data']);

        $background = Service::create('Room_Master')->parseStructure($data_background);
        $overlayer1 = Service::create('Room_Master')->parseStructure($data_overlayer1);
        $overlayer2 = Service::create('Room_Master')->parseStructure($data_overlayer2);
        $cover = Service::create('Room_Master')->parseStructure($data_cover);
        $head = Service::create('Room_Master')->parseStructure($data_head);
        $left = Service::create('Room_Master')->parseStructure($data_left);
        $right = Service::create('Room_Master')->parseStructure($data_right);
        $foot = Service::create('Room_Master')->parseStructure($data_foot);

        if(!empty($data_head)){
            //ヘッダの横幅チェック
            if((count($data[0]) + count($left[0]) + count($right[0])) != count($head[0])) 
                $err = "ヘッダーは" . (count($data[0]) + count($left[0]) + count($right[0])) . "列必要です";
        }

        if(!empty($data_left)){
            if(count($data) != count($left))
                $err = "左オビは" . count($data) . "行必要です";
        }

        if(!empty($data_right)){
            if(count($data) != count($right))
                $err = "右オビは" . count($data) . "行必要です";
        }

        if(!empty($data_foot)){
            if((count($data[0]) + count($left[0]) + count($right[0])) != count($foot[0])) 
                $err = "フッターは" . (count($data[0]) + count($left[0]) + count($right[0])) . "列必要です";
        }

        if($err == ""){
            // 保存。
            Service::create('Room_Master')->save($_GET['id'], $_POST['data'], $_POST['data_background'], $_POST['data_overlayer1'], $_POST['data_overlayer2'], $_POST['data_cover'], $_POST['mats'], $_POST['data_head'], $_POST['data_left'], $_POST['data_right'], $_POST['data_foot']);

            // リダイレクトでブラウザ履歴に残らないようにする。
            Common::redirect(array('_self'=>true));
        }else{
            print_r($err);
            exit;
        }
    }

    private function useMaptipCount(){
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
        $room = Service::create('Room_Master')->getRoom($_GET['id'], $background, $overlayer1, $overlayer2, $cover, $mats, $head, $left, $right, $foot);

        // 定義されているチップ番号を内部で使用する番号に変換するので、その変換表を初期化する。
        $transTable = array();
        $internalNo = 1;

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

        return count($transTable);

    }

}
