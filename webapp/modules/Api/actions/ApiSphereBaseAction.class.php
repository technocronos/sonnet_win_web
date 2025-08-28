<?php

/**
 * スフィアFLASHを返すアクションの基底クラス。
 * SwfBaseActionから派生しているが、doExecute() はすでにオーバーライドしているので、
 * 代わりに onExecute() をオーバーライドすること。
 */
abstract class ApiSphereBaseAction extends SmfBaseAction {

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
     protected function doExecute($params) {

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
      		$this->replaceStrings['ERROR_RELOAD'] = AppUtil::getText("SPHERE_ERROR_RELOAD");
      		$this->replaceStrings['ERROR_NO_ACTIONPT'] = AppUtil::getText("SPHERE_ERROR_NO_ACTIONPT");

      		$this->replaceStrings['TRANS_OTHER_SCENE'] = AppUtil::getText("SPHERE_TRANS_OTHER_SCENE");

      		$this->replaceStrings['SHOWWND_PUSH_BUTTON'] =  AppUtil::getText("SPHERE_SHOWWND_PUSH_BUTTON");
      		$this->replaceStrings['SHOWWND_IN_TRANS'] = AppUtil::getText("SPHERE_SHOWWND_IN_TRANS");
    	  	$this->replaceStrings['SHOWWND_FAIL_SEND_CMD'] = AppUtil::getText("SPHERE_SHOWWND_FAIL_SEND_CMD");
      		$this->replaceStrings['SHOWWND_FAIL_TRANS'] = AppUtil::getText("SPHERE_SHOWWND_FAIL_TRANS");

      		$this->replaceStrings['SHOWWND_RELOAD_FOR_LIMIT'] = AppUtil::getText("SPHERE_SHOWWND_RELOAD_FOR_LIMIT");

      		$this->replaceStrings['_STRING_MENU'] = AppUtil::getText("SPHERE_STRING_MENU");
      		$this->replaceStrings['_STRING_CANCEL'] = AppUtil::getText("SPHERE_STRING_CANCEL");
      		$this->replaceStrings['_STRING_ENTER'] = AppUtil::getText("SPHERE_STRING_ENTER");
      		$this->replaceStrings['_STRING_DETAIL'] = AppUtil::getText("SPHERE_STRING_DETAIL");

      		$this->replaceStrings['STR_CMD_MOVE'] = AppUtil::getText("SPHERE_STR_CMD_MOVE");
      		$this->replaceStrings['STR_CMD_WAIT'] = AppUtil::getText("SPHERE_STR_CMD_WAIT");
      		$this->replaceStrings['STR_CMD_ATACK'] = AppUtil::getText("SPHERE_STR_CMD_ATACK");
      		$this->replaceStrings['STR_CMD_ITEM'] = AppUtil::getText("SPHERE_STR_CMD_ITEM");
      		$this->replaceStrings['STR_CMD_STOP'] = AppUtil::getText("SPHERE_STR_CMD_STOP");
      		$this->replaceStrings['STR_CMD_OK'] = AppUtil::getText("SPHERE_STR_CMD_OK");
      		$this->replaceStrings['STR_CMD_CANCEL'] = AppUtil::getText("SPHERE_STR_CMD_CANCEL");

      		$this->replaceStrings['STR_CAPTION_ITEM'] = AppUtil::getText("SPHERE_STR_CAPTION_ITEM");
      		$this->replaceStrings['STR_CAPTION_EQUIP'] = AppUtil::getText("SPHERE_STR_CAPTION_EQUIP");

      		$this->replaceStrings['STR_BEFORE'] = AppUtil::getText("SPHERE_STR_BEFORE");
      		$this->replaceStrings['STR_ACTION_PT'] = AppUtil::getText("SPHERE_STR_ACTION_PT");

      		$this->replaceStrings['STR_CONFIRM_CHANGE_EQP'] = AppUtil::getText("SPHERE_STR_CONFIRM_CHANGE_EQP");

          // 閲覧モードでないなら、再開コマンドを処理する。
          if( !$this->replaceStrings['readonly_flg'] )
                $this->reopenSphere($sphere, $this->record['revision']);
          else
                $this->replaceStrings['preLd'] = '';

          //environmentを設定する
          $this->replaceStrings["environment"] = $this->record["state"]["environment"];

          if(Common::isTablet() != "tablet")
              $this->replaceStrings["BOTTOM_MARGIN"] = 2;
          else
              $this->replaceStrings["BOTTOM_MARGIN"] = 6;


          $this->replaceStrings["result"] = "ok";
          return $this->replaceStrings;
     }

    //現在未使用
     private function buildSphereBg() {
        if(isset($this->record["state"]["sphere_bg"])){
            $this->replaceStrings["sphere_bg"] = $this->record["state"]["sphere_bg"];
        }
    }

     //-----------------------------------------------------------------------------------------------------
     /**
      * マップ構成をSWFにセットする。
      */
     private function buildMap($sphere) {

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
                     $line .= sprintf('%04d', $structure[$y][$x]);
                }

                $structSpecs[] = $line;
          }

          $this->replaceStrings['structs'] = $structSpecs;
          $this->replaceStrings['structWidth'] = $width;
          $this->replaceStrings['structHeight'] = $height;
          $this->replaceStrings['structWid'] = $width;
          $this->replaceStrings['structHei'] = $height;

          // SWFにマップの構成を伝える配列を初期化。。
          $backgroundSpecs = array();

          // マップ(background)構成を横一行ずつ見ていく。
          $background = $map->getBackground();

          $height = count($background);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($background[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%04d', $background[$y][$x]);
                }

                $backgroundSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structbackground'] = $backgroundSpecs;
          $this->replaceStrings['backgroundWid'] = $width;
          $this->replaceStrings['backgroundHei'] = $height;

          // SWFにマップの構成を伝える配列を初期化。。
          $overlayer1Specs = array();

          // マップ(overlayer1)構成を横一行ずつ見ていく。
          $overlayer1 = $map->getOverlayer1();

          $height = count($overlayer1);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($overlayer1[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%04d', $overlayer1[$y][$x]);
                }

                $overlayer1Specs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structoverlayer1'] = $overlayer1Specs;
          $this->replaceStrings['overlayer1Wid'] = $width;
          $this->replaceStrings['overlayer1Hei'] = $height;

          // SWFにマップの構成を伝える配列を初期化。。
          $overlayer2Specs = array();

          // マップ(overlayer2)構成を横一行ずつ見ていく。
          $overlayer2 = $map->getOverlayer2();

          $height = count($overlayer2);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($overlayer2[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%04d', $overlayer2[$y][$x]);
                }

                $overlayer2Specs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structoverlayer2'] = $overlayer2Specs;
          $this->replaceStrings['overlayer2Wid'] = $width;
          $this->replaceStrings['overlayer2Hei'] = $height;

          // SWFにマップの構成を伝える配列を初期化。
          $coverSpecs = array();

          // マップ(cover)構成を横一行ずつ見ていく。
          $cover = $map->getCover();

          $height = count($cover);
          for($y = 0 ; $y < $height ; $y++) {

                // SWFで使う文字列に変換していく。
                $line = '';
                $width = count($cover[$y]);
                for($x = 0 ; $x < $width ; $x++) {
                     $line .= sprintf('%04d', $cover[$y][$x]);
                }

                $coverSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structcover'] = $coverSpecs;
          $this->replaceStrings['coverWid'] = $width;
          $this->replaceStrings['coverHei'] = $height;

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
                     $line .= sprintf('%04d', $head[$y][$x]);
                }

                $headSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structhead'] = $headSpecs;
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
                     $line .= sprintf('%04d', $left[$y][$x]);
                }

                $leftSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structleft'] = $leftSpecs;
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
                     $line .= sprintf('%04d', $right[$y][$x]);
                }

                $rightSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structright'] = $rightSpecs;
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
                     $line .= sprintf('%04d', $foot[$y][$x]);
                }

                $footSpecs[] = $line;
          }

          // 変換したマップ構成をSWFにセット。
          $this->replaceStrings['structfoot'] = $footSpecs;
          $this->replaceStrings['footWid'] = $width;
          $this->replaceStrings['footHei'] = $height;


          // 各マップチップのマスタを表す配列を初期化。
          $tipSpecs = array();

          $maptip_path = "maptip";
              
          // マップに存在するチップを一つずつ処理する。
          $mapTips = $map->getMapTips();
          foreach($mapTips as $index => $tip) {

                // 移動コストを取得。
                $tipSpecs[$index] = $tip['cost'];

                $tipIds[$index] = sprintf('%04d', $tip['graph_no']);

          }

          // チップマスタをSWFにセット。
          $this->replaceStrings['tip'] = $tipSpecs;
          $this->replaceStrings['tipId'] = $tipIds;

          // 敷物情報をSWFにセット。
          $this->replaceStrings['mat'] = $map->getMatSpecs();
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
          $this->replaceStrings['unit'] = $unitsOnSwf;
          $this->replaceStrings['unitNum'] = max(array_keys($units));

          foreach($sphere->getUnitIcons() as $iconName => $slotNo) {
                $this->replaceStrings["unitIcon"][ $slotNo] = $iconName;
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
          $this->replaceStrings['item'] = $master;
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
          $this->replaceStrings['orn'] = $orns;
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
          $this->replaceStrings['preLd'] = $leads;

          // テスト環境の場合はログに残す。
          if(ENVIRONMENT_TYPE == 'test') {
                $output = str_repeat('-', 80) . "\n" . print_r($leads, true);
                file_put_contents(MO_LOG_DIR.'/sphere_command.log', $output, FILE_APPEND);
          }
     }
}
