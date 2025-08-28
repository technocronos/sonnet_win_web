<?php

/**
 * ドラマFLASHを返すアクションの基底クラス。
 * SwfBaseActionから派生しているが、doExecute() はすでにオーバーライドしているので、
 * 代わりに onExecute() をオーバーライドすること。
 */
abstract class DramaBaseAction extends SwfBaseAction {

    // !XSPEAKER を同時に使用できる最大数
    const XSPEAKER_SLOTS = 7;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 派生クラスでオーバーライドして、ドラマがリクエストされたときの処理をしたり、以下の情報を
     * セットアップする。
     *     ・ドラマID       セットアップするドラマのIDを $this->dramaId に設定する。
     *     ・戻り先URL      ドラマ終了時のURLを $this->endTo に設定する。
     */
    abstract protected function onExecute();


    //-----------------------------------------------------------------------------------------------------
    /**
     * doExecuteをオーバーライド。
     */
    protected function doExecute() {
        // とりあえず、onExecute() を呼ぶ。
        $this->onExecute();

        // 元になるSWFファイルのベース名を設定。
        $this->swfName = 'drama';

        // 指定されたドラマを取得。
        $drama = Service::create('Drama_Master')->needRecord($this->dramaId);

        //翻訳テキストの方から取得する
        $drama['flow'] = AppUtil::getText("drama_master_flow_" . $drama['drama_id']);

        // フローをFLASMアセンブラに置き換える。
        $this->flowCompile($drama['flow']);

        // ドラマ終了時に遷移するURLをセット。
        $this->replaceStrings['urlOnEnd'] = $this->endTo;

        // 背景差し替え
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
            $this->replaceStrings["bg1"] = "";
            $this->replaceStrings["bg2"] = "";
            $this->replaceStrings["bg3"] = "";

            $drama_path = 'img/drama/' . (Common::isTablet() == "tablet" ? "tablet/" : "");

            $this->replaceStrings["bg1"] = APP_WEB_ROOT . $drama_path . $drama['bg1_path'] . "?filetime=" . filemtime(MO_HTDOCS . "/" . $drama_path . $drama['bg1_path']);
            if($drama['bg2_path'])  $this->replaceStrings["bg2"] = APP_WEB_ROOT . $drama_path . $drama['bg2_path']."?filetime=" . filemtime(MO_HTDOCS . "/" . $drama_path . $drama['bg2_path']);
            if($drama['bg3_path'])  $this->replaceStrings["bg3"] = APP_WEB_ROOT . $drama_path . $drama['bg3_path']."?filetime=" . filemtime(MO_HTDOCS . "/" . $drama_path . $drama['bg3_path']);
        }else{
            $this->replaceImages[3] = IMG_RESOURCE_DIR . '/drama/' . $drama['bg1_path'];
            if($drama['bg2_path'])  $this->replaceImages[5] = IMG_RESOURCE_DIR.'/drama/'.$drama['bg2_path'];
            if($drama['bg3_path'])  $this->replaceImages[7] = IMG_RESOURCE_DIR.'/drama/'.$drama['bg3_path'];
        }
//Common::varLog($this->replaceStrings);
        //サウンド設定。バトルはweb_audio_apiのみを使う
        $this->use_web_audio = array(
            //"bgm_battle",
            "se_btn",
            "se_explosionlong",
            "se_hit",
            "se_repair",
        );

        //ボイスはそのドラマに必要なものだけを読み込む
        //ボイスの命名規約は v_ドラマID_連番 になる。
        //swfbaseの$this->web_audio_listにボイスを追加すること。
        foreach($this->web_audio_list as $key => $val){
            $voicestr = "v_" . $this->dramaId . "_";

            if(strpos($key, $voicestr) === 0){
                $this->use_web_audio[] = $key;
            }
        }

        //BGMはaudioタグを使う
        $this->use_audio_tag = array(
            "bgm_mute",
            "bgm_home",
            "bgm_registance",
            "bgm_battle",
            "bgm_bossbattle",
            "bgm_op",
            "bgm_quest_horror",
            "bgm_dungeon",
            "bgm_bright",
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたフローを解析して準備を整える。
     * 必要ならオーバーライドする。
     */
    protected function flowCompile($flow) {
		static $XSPEAKER_IMAGE_IDS;

        // !XSPEAKERコマンドで使用するグラフィックのSWF上でのID。
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
            $this->replaceStrings["xsImage1"] = $info['graphicPath'];
            $this->replaceStrings["xsImage2"] = $info['graphicPath'];
            $this->replaceStrings["xsImage3"] = $info['graphicPath'];
            $this->replaceStrings["xsImage4"] = $info['graphicPath'];
            $this->replaceStrings["xsImage5"] = $info['graphicPath'];
            $this->replaceStrings["xsImage6"] = $info['graphicPath'];
            $this->replaceStrings["xsImage7"] = $info['graphicPath'];
        }else{
            $XSPEAKER_IMAGE_IDS = array(
                1=>18, 2=>20, 3=>22, 4=>24, 5=>26, 6=>28, 7=>30,
            );
        }

        // フローの最初を必ず!PAGEでタップしてからスタートする
        //$flow = "!PAGE\n" . $flow;

        // フローの末尾に "!FLOWEND" コマンドを追加しておく。また改行コードを "\n" に統一する。
        $flow .= "\n!FLOWEND";
        $flow = str_replace("\r\n", "\n", $flow);

        // プレイヤーアバターの名前を取得しておく。
        $avatar = Service::create('Character_Info')->getAvatar($this->user_id);
        $avatarName = $avatar ? Text_LogService::get($avatar['name_id']) : '主人公';

        // フローを一つずつ見て、FLASMアセンブラに変換していくとともに、
        // !XSPEAKER コマンドに関する処理を行う。
        $i = 1;
        $flasm = array();
        $xSpeakers = array();
        foreach(explode("\n", $flow) as $line) {

            // !XSPEAKER コマンドを見つけた場合は、スピーカーコードネームに対応する情報を取得しておく。
            if(substr($line, 0, 9) == '!XSPEAKER') {
                list($dummy, $codeName) = explode(' ', $line);
                if(!array_key_exists($codeName, $xSpeakers))
                    $xSpeakers[$codeName] = $this->getXSpeakerInfo($codeName, $avatarName);
            }

            // [NAME]をユーザ名に置き換える
            $line = str_replace('[NAME]', $avatarName, $line);

            // 配列 $flasm に格納していく。
            $flasm[$i] = $line;

            // 行数カウントアップ
            $i++;
        }

        $this->arrayToFlasm('flow', $flasm);

        // !XSPEAKER の種類数チェック。
        if(count($xSpeakers) > self::XSPEAKER_SLOTS)
            throw new MojaviException('!XSPEAKER の種類数が多すぎる');

        // !XSPEAKER に関する情報をflmファイルへセットする。
        reset($xSpeakers);
        for($i = 1 ; $i <= self::XSPEAKER_SLOTS ; $i++) {

            list($codeName, $info) = each($xSpeakers);

            // !XSPEAKER に関する変数をセット
            $this->replaceStrings["xsCodeName{$i}"] = $codeName;
            $this->replaceStrings["xsName{$i}"] =     $info ? $info['name'] : '';

            // !XSPEAKER のグラフィックを差し替え
            if($info){
                if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
                    $this->replaceStrings["xsImage" . $i] = $info['graphicPath'];
                }else{
                    $this->replaceImages[ $XSPEAKER_IMAGE_IDS[$i] ] = $info['graphicPath'];
                }
            }
        }
   }


    //-----------------------------------------------------------------------------------------------------
    /**
     * !XSPEAKER コマンドのコードネームを受け取って、それに関する情報を返す。
     * 戻り値の配列には次のキーが含まれる。
     *     name             名前
     *     graphicPath      グラフィックへのパス
     */
    private function getXSpeakerInfo($codeName, $avatarName) {

        // コードネームに対応するデフォルトの名前の定義。
        $NAMES = array(
            'shisyou' => AppUtil::getText("DRAMA_CODE_shisyou"), 
            'navi' => AppUtil::getText("DRAMA_CODE_navi"), 
            'abege' => AppUtil::getText("DRAMA_CODE_abege"), 
            'elena' => AppUtil::getText("DRAMA_CODE_elena"), 
            'noel' => AppUtil::getText("DRAMA_CODE_noel"),
            'gebal' => AppUtil::getText("DRAMA_CODE_gebal"),
            'layla' => AppUtil::getText("DRAMA_CODE_layla"),
            'dwarf' => AppUtil::getText("DRAMA_CODE_dwarf"),
            'boy' => AppUtil::getText("DRAMA_CODE_boy"), 
            'girl' => AppUtil::getText("DRAMA_CODE_girl"), 
            'brother' => AppUtil::getText("DRAMA_CODE_brother"), 
            'miss' => AppUtil::getText("DRAMA_CODE_miss"), 
            'mister' => AppUtil::getText("DRAMA_CODE_mister"), 
            'mrs' => AppUtil::getText("DRAMA_CODE_mrs"),
            'grandpa' => AppUtil::getText("DRAMA_CODE_grandpa"), 
            'grandma' => AppUtil::getText("DRAMA_CODE_grandma"), 
            'servant' => AppUtil::getText("DRAMA_CODE_servant"), 
            'woden' => AppUtil::getText("DRAMA_CODE_woden"), 
            'sontyo' => AppUtil::getText("DRAMA_CODE_sontyo"), 
            'shilf' => AppUtil::getText("DRAMA_CODE_shilf"),
        );

        // 名前の取得。
        $name = ($codeName == 'hero') ? $avatarName : $NAMES[$codeName];

        // グラフィックへのパスを取得。
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
            $graphPath = APP_WEB_ROOT . 'img/drama/chara/' . $codeName . '.png' . "?filetime=" . filemtime(MO_HTDOCS . '/img/drama/chara/' . $codeName . '.png');
        }else{
            $graphPath = IMG_RESOURCE_DIR . '/drama/chara/' . $codeName . '.png' . filemtime(MO_HTDOCS . '/drama/drama/' . $codeName . '.png');
        }

        // リターン。
        return array(
            'name' => $name,
            'graphicPath' => $graphPath,
        );
    }
}
