<?php

/**
 * SNSゲーム特有の共通処理を収めているクラス。
 */
Class AppUtil {

    // ログイン回(日)数と、ボーナスの対応表
    public static $LOGIN_BONUS = array(
//          1 => array('item'=>1902),     2 => array('item'=>1901),      3 => array('item'=>1905),
//          4 => array('item'=>1911),     5 => array('item'=>1911),      6 => array('item'=>1911),
    );
    // 同、xx回目以降のループ
    public static $LOGIN_BONUS_EXT = array(
//         1 => array('item'=>1911),       2 => array('item'=>1911),     3 => array('item'=>1911),
//         4 => array('item'=>1911),       5 => array('item'=>1911),
    );

    // 一定の休眠期間を経てのアクセス時の特典
    public static $ABSENCE_BONUS = array(
        array('gold'=>300),     array('item'=>1905),     array('item'=>1902),     array('item'=>1903),
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザ登録を行う。
     *
     * @param int       プラットフォームのユーザID
     * @return array    作成したユーザレコード。
     */
    public static function registerUser($containerUserId) {

        $userSvc = new User_InfoService();

        // ユーザレコード作成。
        $userSvc->insertRecord(array('platform_uid'=>$containerUserId));

        // 作成したレコードを取得。
        $user = $userSvc->getRecordByPuid($containerUserId);

        // アクティビティを飛ばす。
        PlatformApi::postActivity(ACTIVITY_GAME_START);

        // 作成したレコードを返す。
        return $user;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ログインボーナスの付与を行う。
     *
     * @param int       ユーザID。
     * @return array    次のキーを持つ配列。
     *                      tick    ログインカウントを更新したかどうか。
     *                              (本日のカウントがされているなら更新されない)
     *                      count   更新後のログイン日数カウント
     *                      bonus   ログインボーナスの中身について
     *                          item    アイテムレコード。与えられていない場合はnull。
     *                          gold    お金。与えられていない場合は0。
     */
    public static function gainLoginBonus($userId) {

        // ログインカウントを刻む。返った値は戻り値の一部として構成。
        $result = Service::create('User_Property')->tickLoginCount($userId);

        // ログインカウントが更新されている場合は特典付与処理。
        if($result['tick']) {
            $result['bonus'] = self::gainCountBonus($userId, $result['count'], self::$LOGIN_BONUS, self::$LOGIN_BONUS_EXT);


        // ログインカウントが更新されていない場合はログインボーナスはない。
        }else {
            $result['bonus'] = array('item'=>null, 'gold'=>0);
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 課金決済準備をする。
     *
     * @param array     以下のキーを持つ配列。
     *                      item_type       購入しようとしているアイテム種別。payment_log.item_typeの値。
     *                      item_id         購入しようとしているアイテムID／ガチャID
     *                      item_name       購入しようとしているアイテム名／ガチャ名
     *                      unit_price      単価
     *                      description     商品説明
     *                      amount          数量。省略時は1
     *                      image_url       イメージ画像のURL。省略時はitem_type, item_id から推測される。
     *                      backto          戻り先URLについて、現在アクセス中のURLから変更したい
     *                                      GETパラメータがあればここで指定する。
     *                                      戻り先URLは、プラットフォームの決済画面でキャンセルボタンを
     *                                      押したとき、あるいは、購入結果ページで「戻る」リンクを
     *                                      クリックしたときに遷移する。
     *                      finish_param    決済完了画面に渡したいデータがあれば指定する。
     * @return string   決済画面のURL
     */
    public static function readyPayment($readyData) {

        // エラーチェック。
        if( empty($_REQUEST['opensocial_owner_id']) )
            throw new MojaviException('opensocial_owner_idがない');

        // 数量省略時は1。
        if(!isset($readyData['amount']))
            $readyData['amount'] = 1;

        // 数量省略時は1。
        if(!isset($readyData['count']))
            $readyData['count'] = 1;

        // イメージURLが省略されている場合は、item_typeとitem_idで割り出す。
        if(!isset($readyData['image_url'])) {
            if($readyData['item_type'] == 'IT'){
                // アイテムで、汎用攻撃アイコンを使用する場合は "att"。それ以外は先頭0詰めのID5桁。
                if(3000 <= $readyData['item_id']  &&  $readyData['item_id'] <= 3999)
                    $readyData['image_url'] = sprintf('%simg/item/%s.gif', APP_WEB_ROOT, "att");
                else
                    $readyData['image_url'] = sprintf('%simg/item/%05d.gif', APP_WEB_ROOT, $readyData['item_id']);
            }else{
                $readyData['image_url'] = APP_WEB_ROOT.'img/parts/try_gacha.gif';
            }
        }

        // 戻り先URLをシリアル化したものを取得。
        $backto = ViewUtil::serializeBackto(empty($readyData['backto']) ? array() : $readyData['backto']);

        //sp版はこっち
        if(isset($readyData['backto_sp']))
            $backto = $readyData['backto_sp'];

        // 決済処理と完了画面でデータを共有するためのデータストアを作成する。
        // 長すぎてfinishパラメータに含めることが出来ないbacktoの値などを入れておく。
        $data = array();
        $data['backto'] = $backto;
        $data['coin'] = '1';
        $data['count'] = $readyData["count"];
        $data += empty($readyData['finish_param']) ? array() : $readyData['finish_param'];
        $sessId = Service::create('Mini_Session')->setData($data);

        // プラットフォームに渡す、"callback" パラメータを作成。
        if($readyData['item_type'] == 'IT')
            $readyData['callback'] = Common::genURL('Event', 'BuyItem', array('dataId'=>$sessId));
        else
            $readyData['callback'] = Common::genURL('Event', 'DrawGacha', array('dataId'=>$sessId));

        // プラットフォームに渡す、"finish" パラメータを作成。
        if(!isset($readyData['finish']))
            $readyData['finish'] = Common::genURL('User', 'ItemGet', array('dataId'=>$sessId));
        else
            $readyData['finish'] = Common::genURL($readyData['finish']['module'], $readyData['finish']['action'], array('dataId'=>$sessId), true);

        // 決済サーバと通信。決済開始のデータを受け取る。
        unset($readyData['backto'], $readyData['finish_param'], $readyData['backto_sp']);
        $paymentData = PlatformApi::readyPayment($readyData);

        // 決済データをDBに保存。
        $paymentSvc = new Payment_LogService();
        $paymentSvc->insertRecord(array(
            'payment_id' => $paymentData['paymentId'],
            'user_id' => PlatformApi::getInternalUid($_REQUEST['opensocial_owner_id']),
            'item_type' => $readyData['item_type'],
            'item_id' => $readyData['item_id'],
            'amount' => $readyData['amount'],
            'unit_price' => $readyData['unit_price'],
            'ready_data' => json_encode($paymentData),
        ));

        // 決済画面用のURLをリターン。
        return $paymentData['transactionUrl'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された結果セットに含まれるユーザID列を参照して、以下の擬似列を追加する。
     *     thumbnail_url    プラットフォームのユーザ画像URL
     *
     * @param array     処理対象の結果セットを表す２次元配列。結果もこの配列に直接返される。
     *                  第二引数で指定する列にユーザIDが格納されていること。
     * @param string    ユーザIDが格納されている列名
     */
    public static function embedUserThumbnail(&$resultset, $userIdColumn = 'user_id') {

        // ユーザIDの一覧を取得する。
        $userIds = array_unique( ResultsetUtil::colValues($resultset, $userIdColumn) );

        // サムネイルURL問い合わせ。
        $urls = Service::create('User_Thumbnail')->getThumbnailsIn($userIds);

        // 指定された結果セットのレコードに擬似列を追加する。
        foreach($resultset as &$record)
            $record['thumbnail_url'] = $urls[ $record[$userIdColumn] ];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * embedUserThumbnail() と同じだが、以下の擬似列も追加する。
     *     short_user_name  省略ユーザ名
     *     full_user_name   ユーザ名
     */
    public static function embedUserFace(&$resultset, $userIdColumn = 'user_id') {

        // ユーザIDの一覧を取得する。
        $userIds = array_unique( ResultsetUtil::colValues($resultset, $userIdColumn) );

        // サムネイルURL問い合わせ。
        $urls = Service::create('User_Thumbnail')->getThumbnailsIn($userIds);

        // ユーザ情報問い合わせ。
        $users = Service::create('User_Info')->getRecordsIn($userIds);

        // 指定された結果セットのレコードに擬似列を追加する。
        foreach($resultset as &$record) {
            $record['thumbnail_url'] = $urls[ $record[$userIdColumn] ];
            $record['short_user_name'] = $users[ $record[$userIdColumn] ]['short_name'];
            $record['full_user_name'] = $users[ $record[$userIdColumn] ]['name'];
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
    消費アイテムの効果を表示するテンプレート

    パラメータ)
        item    以下のキーを含む配列。
                    item_type
                    item_value
                    item_limitation
     */
    public function itemEffectStr($item) {
        $str= "";

        if($item["item_type"] == Item_MasterService::RECV_HP){
            $str =  str_replace("{0}", $item["item_value"], AppUtil::getText("ITEMEFFECT_RECV_HP1")) . " ";
            $str .= str_replace("{0}", $item["item_limitation"], AppUtil::getText("ITEMEFFECT_RECV_HP2")) . " ";
            $str .= str_replace("{0}", ($item["item_spread"] + 1), AppUtil::getText("ITEMEFFECT_RECV_HP3")) . " ";
        }else if($item["item_type"] == Item_MasterService::RECV_AP){
            $str = str_replace("{0}", $item["item_value"], AppUtil::getText("ITEMEFFECT_RECV_AP"));
        }else if($item["item_type"] == Item_MasterService::RECV_MP){
            $str = str_replace("{0}", $item["item_value"], AppUtil::getText("ITEMEFFECT_RECV_MP"));
        }else if($item["item_type"] == Item_MasterService::INCR_PARAM){
            $str = str_replace(array("{0}", "{1}"), array($item["item_value"],$item["item_limitation"]) , AppUtil::getText("ITEMEFFECT_INCR_PARAM"));
        }else if($item["item_type"] == Item_MasterService::DECR_PARAM){
            $str  = str_replace(array("{0}", "{1}", "{2}"), array($item["item_value"],(Character_InfoService::HP_SCALE * $item["item_value"]), ($item["item_value"] * 8)) , AppUtil::getText("ITEMEFFECT_DECR_PARAM"));
        }else if($item["item_type"] == Item_MasterService::INCR_EXP){
            $str  = str_replace(array("{0}", "{1}"), array($item["item_limitation"],$item["item_value"]) , AppUtil::getText("ITEMEFFECT_INCR_EXP") . "\n" . AppUtil::getText("ITEMEFFECT_NOTICE_MENTE"));
        }else if($item["item_type"] == Item_MasterService::REPAIRE){
            $str = str_replace("{0}", $item["item_value"], AppUtil::getText("ITEMEFFECT_REPAIRE"));
        }else if($item["item_type"] == Item_MasterService::TACT_ATT){
            $str = str_replace("{0}", $item["item_value"], AppUtil::getText("ITEMEFFECT_TACT_ATT1")) . " ";
            $str .= str_replace("{0}", $item["item_limitation"], AppUtil::getText("ITEMEFFECT_TACT_ATT2")) . " ";
            $str .= str_replace("{0}", ($item["item_spread"] + 1), AppUtil::getText("ITEMEFFECT_TACT_ATT3")) . " ";
        }else if($item["item_type"] == Item_MasterService::ATTRACT){
            if($item["item_value"] == 2)
                $str  = str_replace("{0}", $item["item_limitation"], AppUtil::getText("ITEMEFFECT_ATTRACT2"));
            else
                $str  = str_replace("{0}", $item["item_limitation"], AppUtil::getText("ITEMEFFECT_ATTRACT1"));

                $str .= "\n" . AppUtil::getText("ITEMEFFECT_NOTICE_MENTE");
        }else if($item["item_type"] == Item_MasterService::DTECH_UPPER){
            if($item["item_value"] == 2)
                $str  = str_replace(array("{0}", "{1}"), array($item["item_limitation"],Item_MasterService::DTECH_UPPER_INVOKE) , AppUtil::getText("ITEMEFFECT_DTECH_UPPER2"));
            else
                $str  = str_replace(array("{0}", "{1}"), array($item["item_limitation"],Item_MasterService::DTECH_UPPER_INVOKE) , AppUtil::getText("ITEMEFFECT_DTECH_UPPER1"));

                $str .=  "\n" . AppUtil::getText("ITEMEFFECT_NOTICE_MENTE"). "\n" . AppUtil::getText("ITEMEFFECT_NOTICE_GRADE");
        }else if($item["item_type"] == Item_MasterService::CONTINUE_BATTLE){
            $str  = AppUtil::getText("ITEMEFFECT_NOTICE_NO_CONTENUE") . "\n";
            $str .= AppUtil::getText("ITEMEFFECT_NOTICE_CONTENUE_COUNT") . "\n";
            $str .= AppUtil::getText("ITEMEFFECT_NOTICE_CONTENUE_STAR") . "\n";

        }

        return $str;
    }

    // privateメソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * あるカウントに応じて、お金やアイテムを付与するような処理を行う。
     *
     * 例1) カウント1でお金10、カウント2でアイテム(ID:10002), カウント3でお金30を与える。
     *
     *     $bonusTable = array(
     *         1 => array('gold'=>10),
     *         2 => array('item'=>10002),
     *         3 => array('gold'=>30),
     *     );
     *     AppUtil::gainCountBonus($userId, $count, $bonusTable);
     *
     * 例2) カウント1から、[お金40]⇒[お金50]⇒[アイテム10003]⇒[お金40]⇒... のようにループして与える。
     *
     *     $loopTable = array(
     *         1 => array('gold'=>40),
     *         2 => array('gold'=>50),
     *         3 => array('item'=>10003),
     *     );
     *     AppUtil::gainCountBonus($userId, $count, null, $loopTable);
     *
     * 例3) カウント1でお金10、カウント2は何もナシ。
     *      カウント3以降は、[お金30]⇒[お金40]⇒[アイテム10003]⇒[お金30]⇒... のようにループして与える
     *
     *     $bonusTable = array(
     *         1 => array('gold'=>10),
     *         2 => null,
     *     );
     *     $loopTable = array(
     *         1 => array('gold'=>30),
     *         2 => array('gold'=>40),
     *         3 => array('item'=>10003),
     *     );
     *     AppUtil::gainCountBonus($userId, $count, $bonusTable, $loopTable);
     *
     * @param int       付与対象のユーザID
     * @param int       カウントの値。
     * @param array     カウント1からのボーナス対応表。必ず1から始まる序数配列にすること。
     * @param array     カウント1からのボーナス対応表。必ず1から始まる序数配列にすること。
     * @return array    与えられたボーナスの内容。次のキーを持つ配列。
     *                      item    与えられたアイテムレコード。与えられていない場合はnull。
     *                      gold    与えられたお金。与えられていない場合は0。
     */
    public static function gainCountBonus($userId, $referNum, $bonusTable, $exhaustTable = null) {

        // 引数正規化
        if( !is_array($bonusTable) ) $bonusTable = array();

        // 参照値に応じて、ボーナステーブルから内容を取得。変数 $bonus に格納する。
        // まずは初期化。
        $bonus = null;

        // 通常テーブルから参照。
        if($referNum <= count($bonusTable)) {
            $bonus = ($referNum > 0) ? $bonusTable[ $referNum ] : null;

        // 参照値が通常テーブルに格納されている数を超過している場合は超過テーブルから参照。
        }else if(is_array($exhaustTable)  &&  count($exhaustTable) > 0) {

            // 参照値が通常テーブルに格納されている数からどのくらい超過しているかを取得。
            $exhaustNum = $referNum - count($bonusTable);

            // その値が超過テーブルの要素数をも上回っている場合はループするようにする。
            $exhaustNum = $exhaustNum % count($exhaustTable);
            if($exhaustNum == 0) $exhaustNum = count($exhaustTable);

            // 超過テーブルから要素を取得。
            $bonus = $exhaustTable[ $exhaustNum ];
        }

        // 後続の処理のために、$bonusの要素内容を統一する。
        if(!$bonus) $bonus = array();
        if(empty($bonus['item'])) $bonus['item'] = null;
        if(empty($bonus['gold'])) $bonus['gold'] = 0;

        // アイテムボーナス付与。
        if($bonus['item']) {

            // アイテム付与。
            $userItemSvc = new User_ItemService();
            $userItemSvc->gainItem($userId, $bonus['item']);

            // 付与したアイテムの情報を戻り値へ。
            $itemSvc = new Item_MasterService();
            $bonus['item'] = $itemSvc->needRecord($bonus['item']);
        }

        // お金付与。
        if($bonus['gold']) {

            // お金付与。
            $userSvc = new User_InfoService();
            $userSvc->plusValue($userId, array('gold'=>$bonus['gold']));

            // 付与したお金の情報を戻り値へ。
            $bonus['gold'] = $bonus['gold'];
        }

        // リターン。
        return $bonus;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * getTextで取得した文字を配列にして改行エスケープを解除して返す。
     *
     * @param string    文字列
     */
    public static function getTexts($symbol, $replace = null, $replaced = null){
        $string = self::getText($symbol, false, $replace, $replaced);

        if($string == null)
            return array();

        return str_replace('\n', PHP_EOL, preg_split("/\r\n|\n|\r/",$string));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 文字を取得する
     *
     * @param string    シンボル
     * @param bool      改行文字を改行に変換するかどうか
     * @param array     置換する文字
     * @param array     置換文字
     */
    public static function getText($symbol, $is_new_line = true, $replace = null, $replaced = null){
        $string = null;

        $row = Service::create('Text_Master')->getSymbol($symbol);

        if($row != null){
            $lang = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : 0;

            if($lang == 0){
                $string = $row["ja"];
            }else{
                $string = $row["en"];
            }
        }

        if($string == null || $string == ""){
            return null;
        }

        if($replace != null){
            $string = str_replace($replace, $replaced, $string);
        }

        if($is_new_line){
            $string = str_replace('\n', PHP_EOL, $string);
        }

        return $string;
    }

}
