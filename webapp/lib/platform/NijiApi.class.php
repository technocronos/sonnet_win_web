<?php

/**
 * にじよめAPIへのアクセスを提供するクラス。
 */
class NijiApi extends PlatformCommon {

    //-----------------------------------------------------------------------------------------------------
    public function queryProfile($platformUid) {

        $result = parent::queryProfile($platformUid);

        // なぜかワンクッション入ってる…
        if($result)
            $result = $result[0];

        return $result;
    }

    //-----------------------------------------------------------------------------------------------------
    public function queryThumbnail($userIds, $size = 'medium') {

        // 戻り値初期化。
        $result = array();

        // 指定されたユーザIDを一つずつ処理する。
        foreach($userIds as $id) {

            // 指定のユーザのサムネイルURLを取得。取得できない場合もあることに注意。
            $profile = $this->queryProfile($id);
            $url = $profile['thumbnailUrl'];

            // 戻り値に格納。
            $result[$id] = $url;
        }

        // リターン。
        return $result;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * queryUniquenessを実装。
     */
    public function queryUniqueness($platformUid) {

        $prof = PlatformApi::queryProfile($platformUid);
        if(!$prof)
            return null;

        return ($prof['userGrade'] >= 3) ? 1 : 0;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * isForbiddenを実装。
     */
    public function isForbidden($subject, $object) {

        // OAuthインターフェースのURIを取得。
        $ifUri = $this->getApiUrl() . "/ignorelist/{$subject}/@all/{$object}";

        // OAuthリクエスト。
        $result = MyOauth::get($ifUri, '404');

        // ブラックリストがどうかをリターン。
        return !empty($result['entry']);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * readyPaymentを実装。
     */
    public function readyPayment($params) {

        // OAuthインターフェースのURIを取得。
        $ifUri = $this->getApiUrl() . '/payment/@me/@self/@app';

        // POSTするデータを作成。
        $postData = array();
        $postData['callbackUrl'] = $params['callback'];
        $postData['finishPageUrl'] = $params['finish'];
        $postData['message'] = "";
        $postData['paymentItems'] = array(0=>array());
        $postData['paymentItems'][0]['itemId'] = $params['item_id'];
        $postData['paymentItems'][0]['itemName'] = $params['item_name'];
        $postData['paymentItems'][0]['unitPrice'] = $params['unit_price'];
        $postData['paymentItems'][0]['quantity'] = $params['amount'];
        $postData['paymentItems'][0]['imageUrl'] = $params['image_url'];
        $postData['paymentItems'][0]['description'] = $params['description'] ?: '　';   // 説明が空だとエラーになるのでお茶を濁す。

        // OAuthリクエスト。
        $result = MyOauth::post($ifUri, $postData);

        // にじよめからのレスポンスのデータ構造はこんな感じ。

        // レスポンスに entry キーが含まれていなかったらエラー。
        if(empty($result['entry']))
            throw new MojaviException('レスポンスに entry キーが含まれていない');

        // リターン。
        return array(
            'paymentId' => $result['entry']['paymentId'],
            'transactionUrl' => $result['entry']['transactionUrl'],
            'response' => $result,
        );
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * parsePaymentを実装。
     */
    public function parsePayment() {

        // 必要なパラメータがないならエラー。
        if( !isset($_GET['status']) )
            throw new MojaviException('決済結果通知に status パラメータがない');

        if( !isset($_GET['paymentId']) )
            throw new MojaviException('決済結果通知に paymentId パラメータがない');

        // 結果通知に含まれるデータを抽出。
        $data = Common::cutRefArray($_GET);
        unset($data['module'], $data['action']);
        $result['data'] = $data;

        // statusパラメータから、結果コードを取得。
        if($data['status'] == '2')      $code = 'ok';
        else if($data['status'] == '3') $code = 'cancel';
        else                            $code = 'unknown';

        // リターン。
        return array(
            'result' => $code,
            'paymentId' => $data['paymentId'],
            'data' => $data,
        );
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * isPaymentBackをオーバーライド。
     */
    public function isPaymentBack() {

        return empty($_GET['paymentId']) ? null : $_GET['paymentId'];
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * getInvitationUrlを実装。
     */
    public function getInvitationUrl($params) {

        return sprintf("javascript:nijiyome_invite(%s, %s)"
            , json_encode($params['body'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            , json_encode($params['finish'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * parseLifeCycleIdsをオーバーライド。
     */
    public function parseLifeCycleIds() {

        // URLサンプルはこんな感じ。複数IDの場合はカンマ区切り。
        //     http://example.com/add?eventtype=event.addapp&opensocial_app_id=1&id=101,102,103&invite_from_id=1

        return strlen($_REQUEST['id']) ? explode(',', $_REQUEST['id']) : array();
    }

    //-----------------------------------------------------------------------------------------------------
    // テキスト監査API郡
    // にじよめにはテキスト監査がない。

    /**
     * postTextを実装。
     */
    public function postText($type, $text, $ownerId) {

        return '';
    }

    /**
     * getTextを実装。
     */
    public function getText($textIds) {

        throw new MojaviException('にじよめにテキスト監査はない。');
    }

    /**
     * deleteTextを実装。
     */
    public function deleteText($textIds) {
    }


    //-----------------------------------------------------------------------------------------------------
    public function postActivity($title, $bodyUrl) {

        // アクティビティ送信機能はないと思われる。
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * getArticleFormHead()を実装
     */
    public function getArticleFormHead($returnUrl, $body) {

        // この機能はないと思われる。
        return '';
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * sendMessage()を実装
     */
    public function sendMessage($recipients, $body, $title, $url) {

        // プロキシモードではこの機能は利用できない。
        if($_REQUEST['opensocial_owner_id'])
            return;

        // 宛先の最大件数は20まで。
        if(count($recipients) > 20)
            throw new MojaviException('GREEメッセージ送信の宛先は20までです。');

        // APIのURIを決定。
        $ifUri = $this->getApiUrl() . '/messages/@me/@outbox';

        $post = array(
            'recipients' => $recipients,
            'title' => $title,
            'body' => $body,
            'urls' => array( array('value'=>$url) ),
        );

        // リクエスト実行。
        // 同一ユーザにBatchタイプで連続送信すると503になるので、これを無視する。
        $result = MyOauth::post($ifUri, $post, 503);

        // 複数宛先での戻り値はこんな感じ。
        //     array(1) {
        //       ["entry"]=>
        //       string(0) ""
        //     }
        // 不明な宛先があってもこんな感じなので、何の参考にもならない...
    }
}
