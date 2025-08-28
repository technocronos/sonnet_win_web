<?php

/**
 * ドラマAPIを返すアクションの基底クラス。
 * SmfBaseActionから派生しているが、doExecute() はすでにオーバーライドしているので、
 * 代わりに onExecute() をオーバーライドすること。
 */
abstract class ApiDramaBaseAction extends SmfBaseAction {

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
    protected function doExecute($params) {
        // とりあえず、onExecute() を呼ぶ。
        $array = $this->onExecute();

        if($this->dramaId != null){

            // 指定されたドラマを取得。
            $drama = Service::create('Drama_Master')->needRecord($this->dramaId);

            //翻訳テキストの方から取得する
            $drama['flow'] = AppUtil::getText("drama_master_flow_" . $drama['drama_id']);

            // フローをFLASMアセンブラに置き換える。
            $this->flowCompile($drama['flow']);

            //背景画像
            $this->replaceStrings["BG1"] = pathinfo($drama["bg1_path"], PATHINFO_FILENAME);
            $this->replaceStrings["BG2"] = pathinfo($drama["bg2_path"], PATHINFO_FILENAME);
            $this->replaceStrings["BG3"] = pathinfo($drama["bg3_path"], PATHINFO_FILENAME);

            $array["drama"] = $this->replaceStrings;

        }

        return $array;

        //ボイスはそのドラマに必要なものだけを読み込む
        //ボイスの命名規約は v_ドラマID_連番 になる。
/*
        foreach($this->web_audio_list as $key => $val){
            $voicestr = "v_" . $this->dramaId . "_";

            if(strpos($key, $voicestr) === 0){
                $this->use_web_audio[] = $key;
            }
        }
*/

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたフローを解析して準備を整える。
     * 必要ならオーバーライドする。
     */
    protected function flowCompile($flow) {

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
            $flasm[] = $line;

            // 行数カウントアップ
            $i++;
        }

        $this->replaceStrings["flow"] = $flasm;

        foreach($xSpeakers as $key=>$value)
                $this->replaceStrings["speakers"][] = $key . " " . $value;

        // !XSPEAKER の種類数チェック。
        if(count($xSpeakers) > self::XSPEAKER_SLOTS)
            throw new MojaviException('!XSPEAKER の種類数が多すぎる');

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

        // リターン。
        return $name;
    }
}
