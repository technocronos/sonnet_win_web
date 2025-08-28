<?php

/**
 * 各プラットフォームのAPIコールを行うクラスの基底クラス。
 * 直接取り扱うのではなく、PlatfromApi を通じて呼び出す。
 */
abstract class PlatformCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームのユーザIDから内部で使用するユーザIDを得る。
     * 引数・戻り値仕様は PlatformApi::getInternalUid と同じ。
     */
    public function getInternalUid($platformUid) {

        // 普通、プラットフォームのユーザIDは数値なので、基底としてはそのまま使用する実装。
        return (int)$platformUid;
    }

    /**
     * 内部で使用するユーザIDからプラットフォームのユーザIDを得る。
     * 引数・戻り値仕様は PlatformApi::getPlatformUid と同じ。
     */
    public function getPlatformUid($internalUid) {

        // 普通、プラットフォームのユーザIDは数値なので、基底としてはそのまま使用する実装。
        return (string)$internalUid;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームのAPIのURLを返す。
     */
    public function getApiUrl() {

        return PLATFORM_API_URL;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたIDのプロフ情報をコンテナサーバから取得する。
     * 引数・戻り値仕様は PlatformApi::queryProfile と同じ。
     */
    public function queryProfile($platformUid, $queryFields = '') {

        // 取得したいプロフ項目が複数指定されている場合はカンマ区切りの文字列にする。
        if( is_array($queryFields) )
            $queryFields = implode(',', $queryFields);

        // APIのURIを取得。
        $ifUri = PLATFORM_API_URL . sprintf(
              '/people/%s/@self?fields=%s'
            , $platformUid, rawurlencode($queryFields)
        );

        // OAuthリクエスト。未インストールユーザの情報を取得しようとしたとき、モバゲ＆グリーは400を、
        // ミクシィは403を返してくる。
        $result = MyOauth::get($ifUri, array(400, 403));
        if(!$result)
            return null;

        // プラットフォームによって "entry" だったり "person" だったり。
        return $result['entry'] ?: $result['person'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザのフレンドリストをプラットフォームから取得する。
     * 引数・戻り値仕様は PlatformApi::queryFriendList と同じ。
     */
    public function queryFriendList($userId = '@me', $queryFields = '') {

        // 取得したいプロフ項目が複数指定されている場合はカンマ区切りの文字列にする。
        if( is_array($queryFields) )
            $queryFields = implode(',', $queryFields);

        // APIのURIを取得。
        $ifUri = PLATFORM_API_URL . sprintf(
              '/people/%s/@friends?fields=%s&count=100&filterBy=hasApp&filterOp=equals&filterValue=true'
            , $userId, rawurlencode($queryFields)
        );

        // OAuthリクエスト。
        $result = MyOauth::get($ifUri);

        // リターン。
        return $result['entry'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたIDのサムネイルURLをプラットフォームから取得する。
     * 引数・戻り値仕様は PlatformApi::queryThumbnail と同じだが、第一引数は必ず配列であり、
     * プラットフォームユーザIDに変換されている。
     * 戻り値も配列で返すこと。取得できなかったIDは含める必要はない。
     */
    abstract public function queryThumbnail($userIds, $size = 'medium');


    //-----------------------------------------------------------------------------------------------------
    /**
     * ビューアになっているユーザのアクティビティを送信する。
     * 引数・戻り値仕様は PlatformApi::postActivity と同じ。ただし、参照URLの省略はない。
     */
    public function postActivity($title, $bodyUrl) {

        // APIのURIを取得。
        $ifUri = PLATFORM_API_URL . '/activities/@me/@self/@app';

        // POSTするデータを作成。
        $postData = array();
        $postData['title'] = $title;
        $postData['url'] = $bodyUrl;

        // OAuthリクエスト。
        // 短い間隔でアクティビティを送信すると503が返るようなので、これを無視するようにする。
        MyOauth::post($ifUri, $postData, 503);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された文字列にNGワードが含まれているかどうかをチェックする。
     * 引数・戻り値仕様は PlatformApi::checkNgWord と同じ。
     */
    public function checkNgWord($string) {

        // 基底の実装としては、NGWord API はないものとして、常にtrueを返すようにする。
        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたIDのユーザがブラックリスト登録されているかどうかを返す。
     * ※どちらかのユーザがアプリをインストールしていない場合は、登録の有無に関わらずfalseが返る。
     * 引数・戻り値仕様は PlatformApi::isForbidden と同じ。
     */
    abstract public function isForbidden($subject, $object);


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームに課金準備を行わせて、そのレスポンスを返す。
     * 引数・戻り値仕様は PlatformApi::readyPayment と同じだが、省略されたパラメータには値が補われている。
     */
    abstract public function readyPayment($params);

    /**
     * プラットフォームの決済結果通知を解析して、正規化した結果を返す。
     * 引数・戻り値仕様は PlatformApi::parsePayment と同じ。
     */
    abstract public function parsePayment();

    /**
     * 決済画面からユーザが戻ってきていると思われるかどうかと、その場合のpayment_idを取得する。
     *
     * @return int      決済画面からユーザが戻ってきているわけではないならnull、
     *                  戻ってきている可能性があるならそのpayment_id。
     */
    public function isPaymentBack() {

        return empty($_GET['payment_id']) ? null : $_GET['payment_id'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームの招待画面に遷移するためのURLを取得する。
     * 引数・戻り値仕様は PlatformApi::getInvitationUrl と同じ。
     */
    public function getInvitationUrl($params) {

        return sprintf('invite:friends?url=%s&subject=%s&body=%s'
            , urlencode($params['finish'])
            , urlencode( Common::adaptString($params['subject']) )
            , urlencode( Common::adaptString($params['body']) )
        );
    }

    /**
     * プラットフォームの友達招待結果を解析して、正規化した結果を返す。
     * 引数・戻り値仕様は PlatformApi::parseInvitation と同じだが、戻り値に含むユーザIDは
     * プラットフォームユーザIDとする。
     */
    public function parseInvitation() {

        // GET変数 invite_member がないならIDなし。
        if( !isset($_GET['invite_member']) )
            return array();

        // GET変数 invite_member に招待されたIDがカンマで区切られて列挙されている。
        $ids = explode(',', $_GET['invite_member']);

        // 一応、IDの妥当性をチェック。
        foreach($ids as $index => $id) {
            if(strlen($id) == 0)
                unset($ids[$index]);
        }

        return $ids;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームからのライフサイクルイベントの通知を解析して、対象になっているユーザIDを返す。
     * 引数・戻り値仕様は PlatformApi::parseLifeCycleIds と同じだが、戻り値に含むユーザIDは
     * プラットフォームユーザIDとする。
     */
    public function parseLifeCycleIds() {

        // 複数IDは、次のようにPHPでは解析しづらい形で伝えられるので、手動で解析する。
        //     http://www.example.com/addapp?opensocial_app_id=A&eventtype=event.addapp&id=101&id=102&id=103

        // クエリストリングの解析結果を表す配列 $params を初期化。
        $params = array();

        // まず "&" で分割して、各ペアを一つずつ処理する。
        foreach(explode('&', $_SERVER["QUERY_STRING"]) as $pair) {

            // "=" の位置を検出。なかったら位置 0 とする。
            $separator = (int)strpos($pair, '=');

            // パラメータ名と値を取得。
            $name =  urldecode( substr($pair, 0, $separator) );
            $value = urldecode( substr($pair, $separator + 1) );

            // はじめて登場するパラメータなら $params に追加。
            if( !array_key_exists($name, $params) ) {
                $params[$name] = $value;

            // すでに登場している場合...
            }else {

                // 値が配列になっていない場合は配列に変換
                if( !is_array($params[$name]) )
                    $params[$name] = array($params[$name]);

                // 序数として追加していく。
                $params[$name][] = $value;
            }
        }

        // パラメータ "id" で登場している値をリターン。
        // ないはずないけど、この一文でなかった場合はカラ配列になることに留意。
        return (array)$params['id'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザ入力されたテキストを送信する。
     * 引数・戻り値仕様は PlatformApi::postText と同じだが、引数の省略にはすでに対処されている。
     */
    abstract public function postText($text, $writerId, $ownerId);

    /**
     * IDを指定して、テキストを取得する。
     * 引数・戻り値仕様は PlatformApi::getText と同じだが、第一引数は必ず配列になっている。
     * 戻り値も配列で返すこと。エラー時はそのままthrowして良い。
     */
    abstract public function getText($textIds);

    /**
     * IDを指定して、テキストを削除する。
     * 引数・戻り値仕様は PlatformApi::deleteText と同じだが、第一引数は必ず配列になっている。
     */
    abstract public function deleteText($textIds);


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザへメッセージを送信する。
     * 引数・戻り値仕様は PlatformApi::sendMessage と同じだが、宛先ユーザIDは配列に統一され、URLの省略は
     * ない。
     */
    abstract public function sendMessage($recipients, $body, $title, $url);


    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームのユーザ投稿記事へのリンクを返す。
     * 引数・戻り値仕様は PlatformApi::getArticleFormHead と同じ。
     */
    abstract public function getArticleFormHead($returnUrl, $body);
}
