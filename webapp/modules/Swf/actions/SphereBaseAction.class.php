<?php

/**
 * スフィアFLASHを返すアクションの基底クラス。
 * SwfBaseActionから派生しているが、doExecute() はすでにオーバーライドしているので、
 * 代わりに onExecute() をオーバーライドすること。
 */
abstract class SphereBaseAction extends SwfBaseAction {

     //-----------------------------------------------------------------------------------------------------
     /**
      * 派生クラスでオーバーライドして、スフィアがリクエストされたときの処理をしたり、以下の情報を
      * セットアップする。
      *      ・スフィア              ロードするスフィアを $this->record に設定する。
      *      ・replaceStrings      flmファイルの置き換え部分のうち、以下をセットする。
      *                                        suspUrl            中断時URL
      *                                        reloadUrl         リロード時URL
      *                                        transmitUrl      コマンド送信時URL
      *                                        apShortUrl        行動pt不足時のURL
      *                                        readonly          閲覧モードかどうか
      *      ・再開方法              閲覧モードでない場合に、$this->reopenMethod に "continue":続行 か
      *                                  "resume":再開 をセットする。
      */
     abstract protected function onExecute();


     //-----------------------------------------------------------------------------------------------------
     /**
      * doExecuteをオーバーライド。
      */
     protected function doExecute() {

          // とりあえず、onExecute() を呼ぶ。
          $this->onExecute();

          //$this->setAttribute('safe_mode', "auto");

          // 元になるSWFファイルのベース名を設定。
          $this->swfName = 'sphere';

          // スフィアを制御するオブジェクトを作成。
          $sphere = SphereCommon::load($this->record);

          $this->buildSphereBg();

          // マップ構成をSWFにセット。
          $this->buildMap($sphere);

          // ユニット情報をSWFにセット。
          $this->buildUnits($sphere);

          // アイテムマスタをSWFにセット。
          $this->buildItemMaster($sphere);

          // 置物情報をSWFにセット。
          $this->buildOrnaments($sphere);

          // スフィア作成主の情報を取得。
          $user = Service::create('User_Info')->needRecord($this->record['user_id']);

          // その他の情報をセット。
          $this->replaceStrings['revision'] = $this->record['revision'];
          $this->replaceStrings['actionPt'] = (int)$user['action_pt'];
          $this->replaceStrings['consumePt'] = Service::create('Quest_Master')->getConsumePt($this->record['quest_id']);

          //消費ポイント0のクエストは簡単操作モードにする
          if($this->replaceStrings['consumePt'] > 0)
              $this->replaceStrings['EASY_MODE'] = 0;
          else
              $this->replaceStrings['EASY_MODE'] = 1;

  	    	//システム文言
      		$this->replaceStrings['ERROR_RELOAD'] = "ｴﾗｰのためリロードします\nボタンを押してください。code:";
      		$this->replaceStrings['ERROR_NO_ACTIONPT'] = "行動ptが不足しています";

      		$this->replaceStrings['TRANS_OTHER_SCENE'] = "別シーンに移ります\nボタンを押してください";

      		$this->replaceStrings['SHOWWND_PUSH_BUTTON'] =  "ボタンを押してください";
      		$this->replaceStrings['SHOWWND_IN_TRANS'] = "通信中です...";
    	  	$this->replaceStrings['SHOWWND_FAIL_SEND_CMD'] = "コマンド送信に失敗しました｡\nボタンを押してください";
      		$this->replaceStrings['SHOWWND_FAIL_TRANS'] = "画面遷移に失敗しました\nもう一度ボタンを押してみてください";

      		$this->replaceStrings['SHOWWND_RELOAD_FOR_LIMIT'] = "容量制限が近いためリロードします\nボタンを押してください";

      		$this->replaceStrings['_STRING_MENU'] = "メニュー";
      		$this->replaceStrings['_STRING_CANCEL'] = "キャンセル";
      		$this->replaceStrings['_STRING_ENTER'] = "決定";
      		$this->replaceStrings['_STRING_DETAIL'] = "詳細";

      		$this->replaceStrings['STR_CMD_MOVE'] = "移動";
      		$this->replaceStrings['STR_CMD_WAIT'] = "待機";
      		$this->replaceStrings['STR_CMD_ATACK'] = "攻撃";
      		$this->replaceStrings['STR_CMD_ITEM'] = "アイテム";
      		$this->replaceStrings['STR_CMD_STOP'] = "中断";
      		$this->replaceStrings['STR_CMD_OK'] = "OK";
      		$this->replaceStrings['STR_CMD_CANCEL'] = "キャンセル";

      		$this->replaceStrings['STR_CAPTION_ITEM'] = "アイテム";
      		$this->replaceStrings['STR_CAPTION_EQUIP'] = "装備";

      		$this->replaceStrings['STR_BEFORE'] = "直前のできごと";
      		$this->replaceStrings['STR_ACTION_PT'] = "行動pt";

      		$this->replaceStrings['STR_CONFIRM_CHANGE_EQP'] = "装備を変更しますか？";

          // 閲覧モードでないなら、再開コマンドを処理する。
          if( !$this->replaceStrings['readonly'] )
                $this->reopenSphere($sphere, $this->record['revision']);
          else
                $this->replaceStrings['preLd'] = '';

          //overlayを設定する
          $overlay = array("none"=>0,"cave"=>1, "crowd"=> 2);

          $this->replaceStrings["OVERLAY"] = $overlay[$this->record["state"]["overlay"]];

          if(Common::isTablet() != "tablet")
              $this->replaceStrings["BOTTOM_MARGIN"] = 2;
          else
              $this->replaceStrings["BOTTOM_MARGIN"] = 6;

     }

      //現在未使用
     private function buildSphereBg() {
        if(isset($this->record["state"]["sphere_bg"])){
            $this->replaceStrings["SPHERE_BG_USE"] = 1;
            $this->replaceImages[6] = IMG_RESOURCE_DIR . "/sphereBg/" . $this->record["state"]["sphere_bg"] . ".jpg";

            $file = getimagesize($this->replaceImages[6]);

            $this->replaceStrings["SPHERE_BG_WIDTH"] = $file[0];
            $this->replaceStrings["SPHERE_BG_HEIGHT"] = $file[1];

        }
    }

     //-----------------------------------------------------------------------------------------------------
     /**
      * マップ構成をSWFにセットする。
      */
     private function buildMap($sphere) {

          // ユニットグラフィックを差し替える。
          $imageId = array(
                1 =>  9, 
                2 =>  12,
                3 =>  15,
                4 =>  18,
                5 =>  21,
                6 =>  24,
                7 =>  27,
                8 =>  30,
                9 =>  33,
                10 => 36,
                11 => 39,
                12 => 42,
                13 => 45,
                14 => 48,
                15 => 51,
                16 => 54,
                17 => 57,
                18 => 60,
                19 => 63,
                20 => 66,
                21 => 69,
                22 => 72,
                23 => 75,
                24 => 78,
                25 => 81,
                26 => 84,
                27 => 87,
                28 => 90,
                29 => 93,
                30 => 96,
                31 => 99,
                32 => 102,
                33 => 105,
                34 => 108,
                35 => 111,
                36 => 114,
                37 => 117,
                38 => 120,
                39 => 123,
                40 => 126,
                41 => 129,
                42 => 132,
                43 => 135,
                44 => 138,
                45 => 141,
                46 => 144,
                47 => 147,
                48 => 150,
                49 => 153,
                50 => 156,
                51 => 159,
                52 => 162,
                53 => 165,
                54 => 168,
                55 => 171,
                56 => 174,
                57 => 177,
                58 => 180,
                59 => 183,
                60 => 186,
                61 => 189,
                62 => 192,
                63 => 195,
                64 => 198,
                65 => 201,
                66 => 204,
                67 => 207,
                68 => 210,
                69 => 213,
                70 => 216
          );
          // スフィアのマップを取得。
          $map = $sphere->getMap();

          // SWFにマップの構成を伝える配列を初期化。
          $structSpecs = array();

          // マップ構成を横一行ずつ見ていく。
          $structure = $map->getStructure();

          $height = count($structure);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($structure[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%02d', $structure[$y][$x]);
                }

                $structSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->arrayToFlasm('struct', $structSpecs);
          $this->replaceStrings['structWidth'] = $width;
          $this->replaceStrings['structHeight'] = $height;
          $this->replaceStrings['structWid'] = $width;
          $this->replaceStrings['structHei'] = $height;


          // SWFにマップの構成を伝える配列を初期化。。
          $headSpecs = array();

          // マップ(head)構成を横一行ずつ見ていく。
          $head = $map->getHead();

          $height = count($head);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($head[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%02d', $head[$y][$x]);
                }

                $headSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->arrayToFlasm('structhead', $headSpecs);
          $this->replaceStrings['headWid'] = $width;
          $this->replaceStrings['headHei'] = $height;

          // SWFにマップの構成を伝える配列を初期化。。
          $leftSpecs = array();

          // マップ(left)構成を横一行ずつ見ていく。
          $left = $map->getLeft();

          $height = count($left);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($left[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%02d', $left[$y][$x]);
                }

                $leftSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->arrayToFlasm('structleft', $leftSpecs);
          $this->replaceStrings['leftWid'] = $width;
          $this->replaceStrings['leftHei'] = $height;


          // SWFにマップの構成を伝える配列を初期化。。
          $rightSpecs = array();

          // マップ(right)構成を横一行ずつ見ていく。
          $right = $map->getRight();

          $height = count($right);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($right[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%02d', $right[$y][$x]);
                }

                $rightSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->arrayToFlasm('structright', $rightSpecs);
          $this->replaceStrings['rightWid'] = $width;
          $this->replaceStrings['rightHei'] = $height;

          // SWFにマップの構成を伝える配列を初期化。。
          $footSpecs = array();

          // マップ(foot)構成を横一行ずつ見ていく。
          $foot = $map->getFoot();

          $height = count($foot);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($foot[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%02d', $foot[$y][$x]);
                }

                $footSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->arrayToFlasm('structfoot', $footSpecs);
          $this->replaceStrings['footWid'] = $width;
          $this->replaceStrings['footHei'] = $height;


          // 各マップチップのマスタを表す配列を初期化。
          $tipSpecs = array();

          $maptip_path = "maptip";
          if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone")
              $maptip_path = "maptip";
              
          // マップに存在するチップを一つずつ処理する。
          $mapTips = $map->getMapTips();
          foreach($mapTips as $index => $tip) {

                // 移動コストを取得。
                $tipSpecs[$index] = $tip['cost'];

                //ガラケの場合はswfeditorで差し替え
                if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
                    // マップチップイメージの差し替え
                    $this->replaceImages[9 + ($index-1)*2] =
                         IMG_RESOURCE_DIR . sprintf('/'.$maptip_path.'/%04d.png', $tip['graph_no']);
                }else{
                    // マップチップイメージの差し替え。pexでやるつもりだったがパフォーマンスが悪化したためやっぱりこちらで・・
                    $this->replaceImages[$imageId[$index]] =
                         IMG_RESOURCE_DIR . sprintf('/'.$maptip_path.'/%04d.png', $tip['graph_no']);
                }
          }

          // チップマスタをSWFにセット。
          $this->arrayToFlasm('tip', $tipSpecs);

          // 敷物情報をSWFにセット。
          $this->arrayToFlasm('mat', $map->getMatSpecs());
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * ユニット構成をSWFにセットする。
      */
     private function buildUnits($sphere) {

          // スフィアに存在するユニットの一覧を取得。
          $units = $sphere->getUnits();

          // SWF用ユニット情報を初期化。
          $unitsOnSwf = array();

          // スフィア上に存在するユニットを一つずつ見ていく。
          foreach($units as $unitNo => $unit)
                $unitsOnSwf[$unitNo] = $unit->getUnitSpecs();

          // FLASMを通してSWFにセット。
          $this->arrayToFlasm('unit', $unitsOnSwf);
          $this->replaceStrings['unitNum'] = max(array_keys($units));

          // ユニットグラフィックを差し替える。
          $imageId = array(
                1 => array(313, 316, 319, 322, 325, 328, 331, 334)
              , 2 => array(337, 340, 343, 346, 349, 352, 355, 358)
              , 3 => array(361, 364, 367, 370, 373, 376, 379, 382)
              , 4 => array(385, 388, 391, 394, 397, 400, 403, 406)
              , 5 => array(409, 412, 415, 418, 421, 424, 427, 430)
              , 6 => array(433, 436, 439, 442, 445, 448, 451, 454)
              , 7 => array(457, 460, 463, 466, 469, 472, 475, 478),
          );
          foreach($sphere->getUnitIcons() as $iconName => $slotNo) {
                $this->replaceImages[ $imageId[$slotNo][0] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_0_1.png";
                $this->replaceImages[ $imageId[$slotNo][1] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_0_2.png";
                $this->replaceImages[ $imageId[$slotNo][2] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_1_1.png";
                $this->replaceImages[ $imageId[$slotNo][3] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_1_2.png";
                $this->replaceImages[ $imageId[$slotNo][4] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_2_1.png";
                $this->replaceImages[ $imageId[$slotNo][5] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_2_2.png";
                $this->replaceImages[ $imageId[$slotNo][6] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_3_1.png";
                $this->replaceImages[ $imageId[$slotNo][7] ] =
                     IMG_RESOURCE_DIR . "/unitTip/{$iconName}_3_2.png";
          }
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * アイテムマスタをSWFにセットする。
      */
     private function buildItemMaster($sphere) {

          $itemTable = $sphere->getItemTable();

          // スフィアのアイテムマップにある user_item レコードをすべてロードして、
          // user_item_id をキー、item_id を値とする配列を取得する。
          $uitems = Service::create('User_Item')->getRecordsIn(array_keys($itemTable), false);
          $uitems = ResultsetUtil::colValues($uitems, 'item_id', 'user_item_id');

          // アイテムマップにあるアイテムレコードをすべてロード。
          $items = Service::create('Item_Master')->getRecordsIn($uitems);

          // アイテムマップを一つずつ見ていく。
          $master = array();
          foreach($itemTable as $uitemId => $itemNo) {

                // item_master のレコードを取得。
                $item = $items[ $uitems[$uitemId] ];

                // SWFに渡すアイテム情報を作成。
                $master[$itemNo] = $sphere->getItemDataSpec($item);
          }

          // SWFにセット。
          $this->arrayToFlasm('item', $master);
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * 置物構成をSWFにセットする。
      */
     private function buildOrnaments($sphere) {

          // SWF用置物情報を初期化。
          $orns = array();

          // スフィア上に存在する置物を一つずつ見ていく。
          $indexMax = 0;
          foreach($sphere->getMap()->getOrnaments() as $index => $orn) {

                // 置物のtypeに対応するSWFでの番号を取得。
                if( isset(SphereMap::$TYPE_NO[ $orn['type'] ]) )
                     $typeNo = SphereMap::$TYPE_NO[ $orn['type'] ];
                else
                     throw new MojaviException('対応していない置物タイプです');

                // 追加。
                $orns[$index] = sprintf('%02d %02d %02d', $typeNo, $orn['pos'][0], $orn['pos'][1]);
                $indexMax = max($indexMax, $index);
          }

          // FLASMを通してSWFにセット。
          $this->arrayToFlasm('orn', $orns);
          $this->replaceStrings['ornNum'] = $indexMax;
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * スフィアを再開するときの最初の再開コマンドを処理する。
      */
     private function reopenSphere($sphere, $revision) {

          // エラーチェック
          if( !$this->reopenMethod )
                throw new MojaviException('reopenMethod がセットされていない');

          // スフィアへ再開コマンドを送信。
          $leads = $sphere->command( array('reopen'=>$this->reopenMethod, 'rev'=>$revision) );

          // 返ってきた指揮内容をSWFにセットする。
          $this->arrayToFlasm('preLd', $leads);

          // テスト環境の場合はログに残す。
          if(ENVIRONMENT_TYPE == 'test') {
                $output = str_repeat('-', 80) . "\n" . print_r($leads, true);
                file_put_contents(MO_LOG_DIR.'/sphere_command.log', $output, FILE_APPEND);
          }
     }
}
