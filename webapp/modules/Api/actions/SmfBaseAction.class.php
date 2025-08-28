<?php

/**
 * APIを処理するアクションの基底クラス。
 */
abstract class SmfBaseAction extends BaseAction {

    // ユーザ登録していなくてもアクセス可能なアクションかどうか。
    // 必要なら派生クラスでオーバーライドする。
    protected $guestAccess = false;
    public $lang = 0;

    //-----------------------------------------------------------------------------------------------------
    /**
     * initializeメソッドオーバーライド。
     */
    public function initialize ($context) {

        if( !parent::initialize($context) )
            return false;

        // 時間経過処理＆ユーザレコード取得。
        $userSvc = new User_InfoService();
        $this->userInfo = $userSvc->affectByTime($_REQUEST["opensocial_owner_id"]);

        // "user_id" 列の値をuser_idプロパティに格納する。
        $this->user_id = $this->userInfo ? $this->userInfo['user_id'] : null;

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * execute内で呼ばれる。派生クラスでオーバーライドして、各自の処理を記述する。
     *
     * @params array    Flash から loadVariables で送信されている GET パラメータ
     * @return array    Flashに返答する値を配列で。
     */
    abstract protected function doExecute($params);


    //-----------------------------------------------------------------------------------------------------
    /**
     * execute()をオーバーライド。基本となる処理を行う。
     */
    public function execute() {

        if(!$this->userInfo  &&  !$this->guestAccess){
            // まだ登録されていなくて、guestAccessプロパティがOFFになっているアクションの場合
            $resValues['result'] = 'error';
            $resValues['err_code'] = 'error_no_regist_access';
        }else{

            $params = Common::cutRefArray($_GET);
            $ver = (int)$params["ver"];
            $this->lang = (int)$params["lang"];

//Common::varLog($_SERVER['HTTP_USER_AGENT']);
            if((Common::getCarrier() == "android" && ANDROID_VER > $ver) || (Common::getCarrier() == "iphone" && IOS_VER > $ver)){
                //バージョンが合っていない
                $resValues['result'] = 'error';
                $resValues['err_code'] = 'error_unmatch_ver';
            }else if((Common::getCarrier() == "android" && ANDROID_VER < $ver) || (Common::getCarrier() == "iphone" && IOS_VER < $ver)){
                //サーバのバージョンが低い場合は申請中
                $resValues['result'] = 'error';
                $resValues['err_code'] = 'error_in_apply';
            }else if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
                $resValues['result'] = 'error';
                $resValues['err_code'] = 'error_unsupported_carrier';
            }else{
                // 子クラス個別の処理を行って、返す値を取得。
                $resValues = $this->doExecute( $params );
            }
/*
            if(Common::getCarrier() == "android")
                $resValues["resource_hash"] = RESOURCE_HASH_ANDROID;
            else if(Common::getCarrier() == "iphone")
                $resValues["resource_hash"] = RESOURCE_HASH_IOS;
*/
        }

        // 作成したswfを出力。
        $this->respond($resValues);

        return View::NONE;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された配列をSWFに渡すためのFLASMコードを生成する。
     *
     * 例えばつぎのように呼び出すと...
     *
     *     $array = array(
     *         'あああああ',
     *         'いいいい',
     *         'ABC' => 'ううううう',
     *         array('aaa'=>'AAA', 'bbb'=>'BBB'),
     *     );
     *     $this->arrayToFlasm('test', $array);
     *
     * $this->replaceStrings['test'] に次のような文字列がセットされる。
     *
     *     push 'test0'
     *     push 'あああああ'
     *     setVariable
     *     push 'test1'
     *     push 'いいいい'
     *     setVariable
     *     push 'testABC'
     *     push 'ううううう'
     *     setVariable
     *     push 'test2aaa'
     *     push 'AAA'
     *     setVariable
     *     push 'test2bbb'
     *     push 'BBB'
     *     setVariable
     *
     * @param string    SWFの変数名、兼、flmファイル上での置換名
     * @param array     SWFに渡したい配列
     */
    protected function arrayToFlasm($swfVarName, $array) {

        // FLASMコード初期化。
        $flasmCode = '';

        // FLASMアセンブラに変換する。
        foreach($array as $index => $value) {

            // 値が配列になっている場合。
            if(is_array($value)) {
                foreach($value as $name => $deepVal) {
                    $flasmCode .= "    push '{$swfVarName}{$index}{$name}'\n"
                               . sprintf("    push '%s'\n", $deepVal)
                               . "    setVariable\n";
                }

            // 値がスカラー値の場合。
            }else {
                $flasmCode .= "    push '{$swfVarName}{$index}'\n"
                           . sprintf("    push '%s'\n", $value)
                           . "    setVariable\n";
            }
        }

        // $this->replaceStringsにセット。
        $this->replaceStrings[$swfVarName] = $flasmCode;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * Jsonで返答を行う。
     *
     */
    public function getTutorialInfo($array){
        $array['tutorial_step'] = $this->userInfo['tutorial_step'];

        // 以下のチュートリアルステップは別アクションに飛ばす。
        switch($this->userInfo['tutorial_step']) {
            // HOMEアクションへ
            case User_InfoService::TUTORIAL_MAINMENU:       // メインメニュー案内
            case User_InfoService::TUTORIAL_FIELD:          // ファーストクエスト中
            case User_InfoService::TUTORIAL_STATUS:         // ステータス案内
            case User_InfoService::TUTORIAL_SHOPPING:       // ショップ案内
            case User_InfoService::TUTORIAL_RIVAL:          // 対戦案内
            case User_InfoService::TUTORIAL_GACHA:          // ガチャ案内
            case User_InfoService::TUTORIAL_EQUIP:          // 装備案内
            case User_InfoService::TUTORIAL_END:            // すでにチュートリアルが終わっている
                $array['nextscene'] = 'Home';
                return $array;
        }

        // ガチャへ
        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_GACHA){
            $array['nextscene'] = 'Gacha';
                return $array;
        }

        // ドラマIDの設定。
        $this->dramaId = constant('Drama_MasterService::TUTORIAL' . $this->userInfo['tutorial_step']);
        $array['dramaId'] = $this->dramaId;

        // 戻り先の設定。
        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_BATTLE){
            $array['nextscene'] = 'Battle';
        }else{

            $array['nextscene'] = 'Tutorial';
        }

        return $array;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * Jsonで返答を行う。
     *
     */
    protected function respond($resData) {
        header("Content-Type: application/json; charset=utf-8");
        header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        echo json_encode($resData);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * リダイレクトを行う。APIはajaxでリクエストされるため通常のリダイレクトがおかしくなるためこちらを使う。
     *
     */
    protected function redirect($controller, $action, $opt = array()) {
        $array["redirectURL"] = Common::genContainerUrl($controller, $action, $opt);

        header("Content-Type: application/json; charset=utf-8");
        header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        echo json_encode($array);
        exit;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * userInfoプロパティのユーザレコードを読み込みなおす。
     * アクション内でユーザレコードを変更し、変更後のレコードを参照する必要があるときに呼び出す。
     */
    public function reloadUser() {
        $this->userInfo = Service::create('User_Info')->needRecord($this->user_id);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの画像構成(画像を作成するのに必要な最低限の情報)を返す。
     *
     * @param array     Character_InfoService::getExRecord で取得したキャラクター情報。
     * @return array    画像構成を格納した序数配列。
     *                  第0要素にraceが、第1以降の要素には画像を構成するアイテムIDが入る。
     *                  第1以降の各要素の意味は race によって異なる。
     */
    public function getFormation($chara) {


        // 装備なしの状態での装備アイテムIDを取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $equipGraphs = ResultsetUtil::colValues($mounts, 'default_id', 'mount_id');

        // 装備しているものがある場合はそのアイテムIDで上書き。
        foreach($chara['equip'] as $mountId => $uitem)
            $equipGraphs[$mountId] = $uitem['item_id'];

        // 種族によって切り替える。
        switch($chara['race']) {

            case 'PLA':
                $headId = $equipGraphs[Mount_MasterService::PLAYER_HEAD];
                $bodyId = $equipGraphs[Mount_MasterService::PLAYER_BODY];
                $weaponId = $equipGraphs[Mount_MasterService::PLAYER_WEAPON];
                $shieldId = $equipGraphs[Mount_MasterService::PLAYER_SHIELD];
                return array('PLA', $weaponId, $bodyId, $headId, $shieldId);

            case 'MOB':
                return array('MOB', $chara['graphic_id']);
        }
    }
}
