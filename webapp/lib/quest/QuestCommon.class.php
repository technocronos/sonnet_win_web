<?php

/**
 * クエストに関する処理を収める基底クラス
 */
abstract class QuestCommon {

    // public静的メンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのユーザが実行できるクエストの一覧を返す。
     *
     * @param int       ユーザID
     * @return array    Quest_MasterServiceで取得できる quest_master レコードの一覧。
     */
    public static function getExecutableList($userId, $place_id = null) {

        $flagSvc = new Flag_LogService();

        // 戻り値初期化。
        $result = array();

        // ユーザの情報を取得。
        $user = Service::create('User_Info')->needRecord($userId);

        if(is_null($place_id)){
            $placeCheck = true;
            $place_id = $user['place_id'];
        }else{
            $placeCheck = false;
        }

        // ユーザがいる場所のクエストの一覧を取得。
        $quests = Service::create('Quest_Master')->onPlace(
            array($place_id)
        );

        // 一つ一つチェックして、実行可能なもののみを戻り値へ。
        foreach($quests as $quest) {

            $questObj = self::factory($quest['quest_id'], $userId);

            if( $questObj->isExecutable($placeCheck) ){

    		        // 初めてプレイするクエストかどうかを取得。
    		        if( 0 == $flagSvc->getValue(Flag_LogService::TRY_COUNT, $userId, $quest['quest_id']) ){
          					//未実行
          					$questObj->quest["status"] = 1;
        				}else if($flagSvc->getValue(Flag_LogService::CLEAR, $userId, $quest['quest_id']) > 0){
          					//クリア済み
          					$questObj->quest["status"] = 3;
        				}else{
          					//挑戦中
          					$questObj->quest["status"] = 2;
        				}
                $result[] = $questObj->quest;
      			}
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのクエストの処理を行うオブジェクトを返す。
     *
     * @param int       クエストID
     * @param int       ユーザID
     * @return object   寸劇クエストの場合は DramaQuest、フィールドクエストの場合は FieldQuestから
     *                  派生したクラス。
     */
    public static function factory($questId, $userId) {

        // 派生クラスの名前を取得。
        $customClassName = 'Quest' . sprintf('%05d', $questId);

        // 派生クラスが定義されているファイルパスを取得。
        $customFile = dirname(__FILE__).'/extends/'.$customClassName.'.class.php';

        // そのファイルがあるならインクルードして、派生クラスインスタンスを返す。
        if(file_exists($customFile)) {
            require_once($customFile);
            return new $customClassName($questId, $userId);

        // ファイルがないなら、クエストタイプにしたがってQuestCommonインスタンスを返す。
        }else {

            $quest = Service::create('Quest_Master')->needRecord($questId);

            // クエストの種類によって処理は異なる。
            switch($quest['type']) {
                case 'DRM':
                    return new DramaQuest($questId, $userId);
                case 'FLD':
                    return new FieldQuest($questId, $userId);
                default:
                    throw new MojaviException('未定義のクエストタイプです');
            }
        }
    }


    // publicメンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのユーザが、クエストを実行できるかどうかをチェックする。
     * チェックするのは必要なフラグなどであり、行動ptの不足など流動的な条件はチェックしないことに留意。
     *
     * @return bool     実行できるならtrue、できないならfalse。
     */
    public function isExecutable($placeCheck = true) {

        $flagSvc = new Flag_LogService();

        // ユーザの情報を取得。
        $user = Service::create('User_Info')->needRecord($this->userId);

        if($placeCheck){
            // 地点が違うなら不可。
            if($this->quest['place_id'] != 0  &&  $this->quest['place_id'] != 98  &&  $user['place_id'] != $this->quest['place_id'])
                return false;
        }

        // チュートリアル中は、初期クエスト以外不可。
        if($user['tutorial_step'] < User_InfoService::TUTORIAL_END)
            return false;

        // 繰り返し実行不能で、すでにクリアしたことのあるクエストは不可。
        if( !$this->quest['repeatable']  &&  $flagSvc->getValue(Flag_LogService::CLEAR, $this->userId, $this->quest['quest_id']) )
            return false;

        // 条件マスタで、クエストオープンについて定められた値をチェック。
        $openValue = Service::create('Condition_Master')->getValue(
            Condition_MasterService::QUEST_OPEN,
            $this->quest['quest_id'],
            $this->userId
        );

        // クエストオープンについての定めがあり、ユーザにおける値が偽に評価されるものの場合は
        // クエスト不可。
        if($openValue !== false  &&  !$openValue)
            return false;

        //開始、終了日時チェック
        if($this->quest['start_date'] != NULL){
            if(strtotime($this->quest['start_date']) <= time() && strtotime($this->quest['end_date']) > time()){

            }else{
                return false;

            }
        }

        // ここまで来たらOK。
        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * クエストを開始したときの処理を行う。
     */
    public function startQuest() {

        // 実行回数をカウントアップする。
        Service::create('Flag_Log')->countUp(Flag_LogService::TRY_COUNT, $this->userId, $this->quest['quest_id']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * クエストを終了したときの処理を行う。
     *
     * @param bool      成功終了ならtrue、失敗ならfalse
     * @param mixed     派生クラスで使用する。
     */
    public function endQuest($success, $code) {

        // 成功ならクエストクリアのフラグをONに。
        if($success)
            Service::create('Flag_Log')->countUp(Flag_LogService::CLEAR, $this->userId, $this->quest['quest_id']);
    }


    // protectedメンバ
    //=====================================================================================================

    // quest_masterレコード
    protected $quest;

    // クエストを実行するユーザのID
    protected $userId;


    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     *
     * @param int       クエストID
     * @param int       ユーザID
     */
    protected function __construct($questId, $userId) {

        $this->quest = Service::create('Quest_Master')->needRecord($questId);
        $this->userId = $userId;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * このクエストをクリアしたことがあるかどうかを返す。
     *
     * @param bool  クリアしたことがあるならtrue、ないならfalse。
     */
    protected function isCleared() {

        return Service::create('Flag_Log')->getValue(
            Flag_LogService::CLEAR, $this->userId, $this->quest['quest_id']
        );
    }
}
