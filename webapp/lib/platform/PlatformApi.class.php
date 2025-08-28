<?php

$className = PLATFORM_API_CLASS;
PlatformApi::$apiObject = new $className();

/**
 * プラットフォームのAPIにアクセスするためのユーティリティクラス。
 * 含まれているメソッドでユーザIDについての言及が頻出するが、特に断りがない場合は内部ユーザIDを指している。
 */
class PlatformApi {

    // 各プラットフォームのAPIコールを実装しているクラスオブジェクト。
    public static $apiObject;


    //-----------------------------------------------------------------------------------------------------
    // システム内部でのユーザIDと、プラットフォームのユーザIDの変換API郡

    /**
     * プラットフォームのユーザIDから内部で使用するユーザIDを得る。
     *
     * @param string    プラットフォームのユーザID。
     * @return int      内部で使用するユーザID
     */
    public static function getInternalUid($platformUid) {

        return self::$apiObject->getInternalUid($platformUid);
    }

    /**
     * 内部で使用するユーザIDからプラットフォームのユーザIDを得る。
     *
     * @param int       内部で使用するユーザID
     * @return string   プラットフォームのユーザID
     */
    public static function getPlatformUid($internalUid) {

        return self::$apiObject->getPlatformUid($internalUid);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたIDのプロフ情報をプラットフォームから取得する。
     * ビューアのプロフを取得する場合は "@me" を指定することもできる。
     *
     * 例)
     *     ・ビューアのプロフを取得。
     *         $prof = PlatformApi::queryProfile('@me');
     *
     *     ・ビューアのnicknameのみを取得。
     *         $prof = PlatformApi::queryProfile('@me', 'nickname');
     *
     *     ・ID:11000 のユーザの thumbnailUrl, nickname, profileUrl を取得。
     *         $prof = PlatformApi::queryProfile('11000', array('thumbnailUrl', 'nickname', 'profileUrl'));
     *
     * @param string    プロフを取得したいユーザのID、または "@me"
     *                  内部のユーザIDではなくプラットフォーム上でのIDを使うので注意
     * @param mixed     取得したいプロフ項目。配列で複数指定することも可能。
     * @return array    得られたプロフ情報。取得できなかった場合はnull。
     */
    public static function queryProfile($platformUid = '@me', $queryFields = '') {

        // 問い合わせ。
        try {

            return self::$apiObject->queryProfile($platformUid, $queryFields);

        // エラーになったらログしてnullリターン。
        }catch(Exception $e) {
            self::logNotice($e, 'プロフィールの取得に失敗。');
            return null;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたIDのユーザのニックネームをプラットフォームから取得する。
     * ビューアのプロフを取得する場合は "@me" を指定することもできる。
     *
     * @param string    ニックネームを取得したいユーザのプラットフォーム上でのID、または "@me"
     *                  内部のユーザIDではなくプラットフォーム上でのIDを使うので注意
     * @return string   得られたニックネーム。取得できなかった場合はnull。
     */
    public static function queryNickname($platformUid = '@me') {

        static $cache;

        // キャッシュにあるならそこから返す。
        if( isset($cache[$platformUid]) )
            return $cache[$platformUid];

        // キャッシュにないなら問い合わせる。
        $prof = self::queryProfile($platformUid, 'nickname');

        // 取得できたならキャッシュへ。取得できなかったらnullリターン。
        if($prof) {
            $cache[$platformUid] = $prof['nickname'];
            return $prof['nickname'];
        }else {
            return null;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザのサムネイル画像URLをプラットフォームから取得する。
     * ビューアのサムネイルを取得する場合は "@me" を指定することもできる。
     *
     * @param mixed     サムネイルを取得したいユーザのID。配列で複数指定も可能(1000まで)。
     * @param string    取得したいサイズ。"medium", "large" のいずれか。
     * @return mixed    取得したサムネイルURL。取得できなかった(アンインストール等)場合はカラ文字。
     *                  ユーザIDを配列で指定している場合は [ユーザID] => [URL] の配列。
     *                  エラーで取得できなかった場合はいずれの場合もnull。
     */
    public static function queryThumbnail($userId, $size = 'medium') {

        // 問い合わせる対象がないなら即リターン。
        if(!$userId)
            return is_array($userId) ? array() : null;

        // 引数を配列に統一。
        $args = (array)$userId;

        // 内部ユーザIDからプラットフォームユーザIDに変換する。
        foreach($args as &$arg)
            $arg = self::getPlatformUid($arg);

        // 問い合わせ。
        try {
            $res = self::$apiObject->queryThumbnail($args, $size);

        // エラーになった場合。
        }catch(Exception $e) {
            self::logNotice($e, 'サムネイル画像の取得に失敗。');
            return null;
        }

        // プラットフォームユーザIDから内部ユーザIDに変換する。
        $result = array();
        foreach($res as $id => $value)
            $result[ self::getInternalUid($id) ] = $value;

        // 取得されていないIDがある場合は補う。
        foreach((array)$userId as $id) {
            if( !isset($result[$id]) )
                $result[$id] = '';
        }

        // 引数を単一IDで指定したか配列で指定したかで、リターンの形式を変える。
        return is_array($userId) ? $result : $result[$userId];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザのフレンドリストをプラットフォームから取得する。
     * ビューアのフレンドを取得する場合は "@me" を指定することもできる。
     * ※このアプリをインストールしていないフレンドは取得しない。
     * ※最大100件まで
     *
     * @param string    フレンドを取得したいユーザのID、または "@me"
     * @param mixed     取得したいプロフ項目。queryProfileと同様。
     * @return array    フレンドの配列。
     */
    public static function queryFriendList($userId = '@me', $queryFields = '') {
        return self::$apiObject->queryFriendList($userId, $queryFields);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ビューアになっているユーザのアクティビティを送信する。
     *
     * @param string    アクティビティのタイトル。
     * @param mixed     アクティビティの参照URL。省略時はユーザの他者ページ。
     */
    public static function postActivity($title, $bodyUrl = null) {

        // URL省略時はユーザの他者ページ。
        if(!$bodyUrl) {
            $bodyUrl = Common::genUrl('User', 'HisPage', array(
                'userId' => self::getInternalUid($_REQUEST["opensocial_owner_id"]),
            ));
        }

        // 送信。
        try {
            self::$apiObject->postActivity($title, $bodyUrl);

        // エラーになっても、ログだけして続行する。
        }catch(Exception $e) {
            self::logNotice($e, 'アクティビティの送信に失敗。');
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された文字列にNGワードが含まれているかどうかをチェックする。
     *
     * @param string    チェックしたい文字列。
     * @return bool     OKならtrue、NGワードか含まれているならfalse。
     */
    public static function checkNgWord($string) {

        // 送信。
        try {
            return self::$apiObject->checkNgWord($string);

        // エラーになっても、ログだけして、trueリターンする。
        }catch(Exception $e) {
            self::logNotice($e, 'NGワード検査に失敗。');
            return true;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたIDのユーザがブラックリスト登録されているかどうかを返す。
     * ※どちらかのユーザがアプリをインストールしていない場合は、登録の有無に関わらずfalseが返る。
     *
     * 例)
     *     ・ID:12345 のユーザが、現在アクセス中のユーザをブラックリストにしているかどうか。
     *         $isForbidden = PlatformApi::isForbidden(12345);
     *
     *     ・ID:987のユーザが、ID:12345 のユーザをブラックリストにしているかどうか。
     *         $isForbidden = PlatformApi::isForbidden(987, 12345);
     *
     * @param string    ブラックリストの管理者となるユーザ。
     * @param string    省略可能。ブラックリストかどうか判断したいユーザ。
     * @return bool     ブラックリストに載っているならtrue、載っていないならfalse。
     */
    public static function isForbidden($subject, $object = '@me') {

        // 本来はここで、プラットフォームユーザIDへの変換を行わなければならないのだが、
        // 問題になるmixiでは、実際は問い合わせを行わない実装なので、今は省略しておく。

        // "@me" への対応。
        if($object == '@me')
            $object = $_REQUEST["opensocial_owner_id"];

        // 送信。
        try {
            return self::$apiObject->isForbidden($subject, $object);

        // エラーになっても、ログだけして、falseリターンする。
        }catch(Exception $e) {
            self::logNotice($e, 'ブラックリスト判定に失敗。');
            return false;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームに課金準備を行わせて、そのレスポンスを返す。
     *
     * @param array     課金しようとしているアイテムの情報を連想配列で。以下のキーを格納すること。
     *                      callback        決済完了時のサーバ間通信用URL
     *                      finish          決済完了後にユーザを飛ばす完了画面
     *                      item_id         購入しようとしているアイテムID
     *                      item_name       購入しようとしているアイテム名
     *                      unit_price      単価
     *                      amount          数量。省略時は1
     *                      description     商品説明
     *                      image_url       イメージ画像のURL。省略可能
     * @return array    以下のキーを含む連想配列。
     *                      paymentId       プラットフォームが発行した決済ID
     *                      transactionUrl  プラットフォームが示している決済画面用URL
     *                      response        プラットフォームから返されたレスポンスデータ
     */
    public static function readyPayment($params) {

        // 省略可能なパラメータを補う。
        $params += array('amount'=>1, 'image_url'=>'');

        return self::$apiObject->readyPayment($params);
    }


    /**
     * プラットフォームの決済結果通知を解析して、正規化した結果を返す。
     * $_GET や $_POST を見て解析するので、引数はない。
     *
     * @return array    以下のキーを含む連想配列。
     *                      result      決済結果の値。以下のいずれか
     *                          ok          決済完了
     *                          cancel      キャンセル
     *                          unknown     未知の値
     *                      paymentId   プラットフォームが発行した決済ID
     *                      data        プラットフォームから送られた決済結果に関するデータ全体。連想配列。
     */
    public static function parsePayment() {

        return self::$apiObject->parsePayment();
    }


    /**
     * 決済画面からユーザが戻ってきている場合(完了画面を表示しようとしているはず)に、
     * 決済の状態を確認する。
     *
     * 決済画面から戻ってきているのに、決済が完了していない場合は例外をスローして処理を止める。
     * 問題なく決済が完了している、あるいは、決済画面から戻っているわけではない場合は何もしない。
     *
     * @return bool     問題なく決済が完了している、あるいは、決済画面から戻っているわけでは
     *                  ない場合はtrue、決済画面でキャンセルが選択されている場合はfalse。
     */
    public static function validatePayment() {

        // 決済画面からユーザが戻ってきているのかどうかと、その場合のpayment_idを取得。
        $paymentId = self::$apiObject->isPaymentBack();

        // 決済画面から戻ってきてないのなら何もしない。
        if(!$paymentId)
            return true;

        // 戻ってきている場合...

        // 決済データの状態を取得。
        $payment = Service::create('Payment_Log')->needRecord($paymentId);

        // 決済の状態ごとに分岐する。
        switch($payment['status']) {
            case Payment_LogService::STATUS_COMPLETE:   return true;
            case Payment_LogService::STATUS_CANCEL:     return false;
            default:                                    throw new MojaviException('購入完了していないのに、決済完了ページに飛んできた。');
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームの招待画面に遷移するためのURLを取得する。
     *
     * @param array     以下のキーを含む連想配列。
     *                      finish          招待の後ユーザを遷移させるURL。
     *                                      プラットフォームを経由しないURLで指定する。
     *                      subject         メッセージ件名。
     *                      body            メッセージ本文。
     * @return string   招待画面に遷移するためのURL。
     */
    public static function getInvitationUrl($params) {

        return self::$apiObject->getInvitationUrl($params);
    }


    /**
     * プラットフォームの友達招待結果を解析して、正規化した結果を返す。
     * $_GET や $_POST を見て解析するので、引数はない。
     *
     * @return array    招待されたユーザIDの配列。
     */
    public static function parseInvitation() {

        $ids = self::$apiObject->parseInvitation();

        // プラットフォームユーザIDを内部ユーザIDに変換する。
        foreach($ids as &$id)
            $id = self::$apiObject->getInternalUid($id);

        return $ids;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームからのライフサイクルイベントの通知を解析して、対象になっているユーザIDを返す。
     * $_GET や $_POST を見て解析するので、引数はない。
     *
     * @return array    対象になっているユーザIDの配列。複数ある場合があるので、必ず配列で返す。
     */
    public static function parseLifeCycleIds() {

        $ids = self::$apiObject->parseLifeCycleIds();

        // プラットフォームユーザIDを内部ユーザIDに変換する。
        foreach($ids as &$id)
            $id = self::$apiObject->getInternalUid($id);

        return $ids;
    }


    //-----------------------------------------------------------------------------------------------------
    // ユーザ入力されたテキストをプラットフォームに監査させるためのAPI郡。
    // モバゲの場合、テキストを投稿する前に、投稿対象となる TextDataGroup を作成しておく必要がある。
    // 詳細はMobageApiのpostText()を参照。

    /**
     * ユーザ入力されたテキストを送信する。
     *
     * @param string    投稿内容
     * @param string    writerId。null可能。省略時は$_REQUEST["opensocial_owner_id"]から取得(あれば)
     * @param string    ownerId。null可能。省略時はwriterIdと同一になる。
     * @return string   投稿したデータに割り当てられたID。
     *                  監査の必要がない(機能がない)プラットフォームの場合はカラ文字が返る。
     */
    public static function postText($text, $writerId = null, $ownerId = null) {

        // 本来はここで、プラットフォームユーザIDへの変換を行わなければならないのだが、
        // 問題になるmixiでは、実際は処理を行わない実装なので、今は省略しておく。

        // writerId が省略されている場合は opensocial_owner_id から取得。
        if( is_null($writerId) && isset($_REQUEST["opensocial_owner_id"]) )
            $writerId = $_REQUEST["opensocial_owner_id"];

        return self::$apiObject->postText($text, $writerId, $ownerId);
    }


    /**
     * IDを指定して、テキストを取得する。
     *
     * @param mixed     取得したいテキストのID。配列で複数指定も可能(1000まで)。
     * @return mixed    取得したテキストの本文。ない、あるいは削除されている場合はカラ文字。
     *                  テキストIDを配列で指定している場合は [テキストID] => [本文] の配列。
     *                  エラーで取得できなかった場合はいずれの場合もnull。
     */
    public static function getText($textId) {

        // 問い合わせる対象がないなら即リターン。
        if(!$textId)
            return is_array($textId) ? array() : null;

        // 引数を配列に統一。
        $args = is_array($textId) ? $textId : array($textId);

        // 問い合わせ。
        try {
            $result = self::$apiObject->getText($args);

        // エラーになった場合。
        }catch(Exception $e) {
            self::logNotice($e, 'テキスト取得に失敗。');
            return null;
        }

        // 取得されていないIDがある場合は補う。
        foreach((array)$textId as $id) {
            if( !isset($result[$id]) )
                $result[$id] = '';
        }

        // 引数を単一IDで指定したか配列で指定したかで、リターンの形式を変える。
        return is_array($textId) ? $result : $result[$textId];
    }


    /**
     * IDを指定して、テキストを削除する。
     *
     * @param mixed     削除したいテキストのID。配列で複数指定も可能(1000まで)。
     * @return mixed    プラットフォームから返されたデータ。
     */
    public static function deleteText($textId) {
        return self::$apiObject->deleteText( (array)$textId );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザへメッセージを送信する。
     *
     * @param array     宛先のユーザID。20件までなら配列での複数指定も可能。
     * @param string    本文。GREEなら半角100文字まで、MOBAGEなら半角38文字まで。
     * @param string    タイトル。GREEなら半角26文字まで。MOBAGEでは無視される。
     * @param string    メッセージのリンク先URL。省略時はトップページのURL
     */
    public static function sendMessage($recipients, $body, $title, $url = null) {

        // 本来はここで、プラットフォームユーザIDへの変換を行わなければならないのだが、
        // 問題になるmixiでは、実際は処理を行わない実装なので、今は省略しておく。

        // URL省略時はトップページ。
        if( is_null($url) )
            $url = APP_WEB_ROOT;

        // 送信。
        try {
            self::$apiObject->sendMessage((array)$recipients, $body, $title, $url);

        // エラーになっても、ログだけして続行する。
        }catch(Exception $e) {
            self::logNotice($e, 'メッセージの送信に失敗。');
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームのユーザ投稿記事(GREEなら「ひとこと」、モバゲなら「日記」、ミクシィなら「ボイス」)
     * へのHTML( <form> )のうち、開始部分を取得する。
     *
     * 戻り値の例)
     *     <form action="....." method="post">
     *       <input type="hidden" name="....." value="......." />
     *       <input type="hidden" name="....." value="......." />
     *
     * @param string    ユーザが投稿を行った後に返ってくるURL
     * @param string    デフォルトの本文。
     * @return string   <form> の開始部分
     */
    public static function getArticleFormHead($returnUrl, $body) {

        return self::$apiObject->getArticleFormHead($returnUrl, $body);
    }


    // privateメソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された例外をログに記録する。
     *
     * @param Exception     エラー時に発生した例外。
     * @param string        ログの冒頭に記述する文字列。
     */
    private static function logNotice($e, $preText) {

        global $logger;

        if($logger) {

            $log = $preText . "処理は続行。: " . $e->getMessage() . "\n"
                 . $e->getTraceAsString() . "\n"
                 . 'PATH: ' . $_SERVER["REQUEST_URI"] . "\n"
                 . 'HEADER: ' . print_r(apache_request_headers(), true)
                 . 'POST: ' . print_r($_POST, true);

            $logger->WARNING($log);
        }
    }
}
