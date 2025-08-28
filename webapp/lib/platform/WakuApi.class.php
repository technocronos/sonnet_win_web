<?php

/**
 * ワクプラAPIへのアクセスを提供するクラス。
 */
class WakuApi extends PlatformCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * queryProfileをオーバーライド。
     */
    public function queryProfile($platformUid, $queryFields = '') {

        // バッチモードではリクエストできない。
        if(!$_REQUEST['opensocial_owner_id'])
            return null;

        return parent::queryProfile($platformUid, $queryFields);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * queryThumbnailを実装。
     * ユーザサムネイルサービスを利用して、API問い合わせなしで高速に返す。
     */
    public function queryThumbnail($userIds, $size = 'medium') {

        // 戻り値初期化
        $result = array();

        // アプリ指定のサイズを、GREE API におけるサイズに変換する。
        $sizeOnContainer = ($size == 'medium') ? 'normal' : 'large';

        // ワクプラにはユーザーサムネイルサービスという便利なものがあるので、それを利用する。
        foreach($userIds as $id)
            $result[$id] = sprintf('thumbnail:show?member=%s&size=%s&adjust_vga=on&guid=ON', $id, $sizeOnContainer);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * postActivityをオーバーライド。
     */
    public function postActivity($title, $bodyUrl) {

        // APIのURIを取得。
        $ifUri = PLATFORM_API_URL.'/activities/@me/@self/@app';

        // POSTするデータを作成。
        $postData = array();
        $postData['title'] = mb_strimwidth($title, 0, 21, '');
        $postData['body'] = $title;

        // OAuthリクエスト。
        // 短い間隔でアクティビティを送信すると503が返るようなので、これを無視するようにする。
        MyOauth::post($ifUri, $postData, 503);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * checkNgWordをオーバーライド。
     */
    public function checkNgWord($string) {

        // OAuthリクエスト。
        $result = MyOauth::post(
            PLATFORM_API_URL.'/ngword?_method=check',
            array('data' => $string)
        );

        // OKがどうかをリターン。
        return isset($result['ngword']['valid']) ? $result['ngword']['valid'] : true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * isForbiddenを実装。
     */
    public function isForbidden($subject, $object) {

        // OAuthインターフェースのURIを取得。
        $ifUri = PLATFORM_API_URL . "/blacklist/{$subject}/@all/{$object}?fields=targetId";

        // OAuthリクエスト。登録されていない場合に404が、$subjectがアプリをインストールしていない場合に
        // 400が返ってくるのでこれを無視する。
        $result = MyOauth::get($ifUri, array('400', '404'));

        // ブラックリストがどうかをリターン。
        return !empty($result['blacklist']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * readyPaymentを実装。
     */
    public function readyPayment($params) {

        // OAuthインターフェースのURIを取得。
        $ifUri = PLATFORM_API_URL . '/payment/@me/@self/@app';
        if($params['currency'] == 'service')
            $ifUri = str_replace('/payment/', '/payment_service/', $ifUri);

        // POSTするデータを作成。
        $postData = array();
        $postData['callbackUrl'] = $params['callback'];
        $postData['finishUrl'] = $params['finish'];
        $postData['entry'] = array();
        $postData['entry'][] = array(
            'itemId' => $params['item_id'],
            'name' => $params['item_name'],
            'unitPrice' => $params['unit_price'],
            'amount' => $params['amount'],
            'imageUrl' => $params['image_url'],
            'description' => $params['description'],
        );

        // OAuthリクエスト。
        $result = MyOauth::post($ifUri, $postData);

        // レスポンスデータの構造はこんな感じ。
        // array(1) {
        //   ["payment"]=>
        //   array(10) {
        //     ["finishUrl"]=>
        //     string(131) "http://test.waku.jyoshimecha.com/index.php?sessId=OwxfUaFd8ApKx4i406CI5pfWgQxvitXE&reason=coin&module=User&action=ItemGet&anti_orc="
        //     ["entry"]=>
        //     array(1) {
        //       [0]=>
        //       array(7) {
        //         ["itemId"]=>
        //         string(4) "2101"
        //         ["amount"]=>
        //         string(1) "1"
        //         ["imageUrl"]=>
        //         string(50) "http://test.waku.jyoshimecha.com/img/item/2101.gif"
        //         ["paymentId"]=>
        //         string(36) "B394ACF9-5410-11E4-8184-00224D672507"
        //         ["name"]=>
        //         string(21) "回復ﾄﾞﾘﾝｸ"
        //         ["unitPrice"]=>
        //         string(3) "100"
        //         ["description"]=>
        //         string(0) ""
        //       }
        //     }
        //     ["status"]=>
        //     string(1) "0"
        //     ["userId"]=>
        //     string(18) "wakupl.com:8232264"
        //     ["endpointUrl"]=>
        //     string(89) "http://sb.wakupl.com/m/app/payment/?id=B394ACF9-5410-11E4-8184-00224D672507&app_id=100370"
        //     ["published"]=>
        //     string(19) "2014-10-15T02:12:23"
        //     ["appId"]=>
        //     string(6) "100370"
        //     ["callbackUrl"]=>
        //     string(120) "http://test.waku.jyoshimecha.com/index.php?sessId=OwxfUaFd8ApKx4i406CI5pfWgQxvitXE&module=Event&action=BuyItem&anti_orc="
        //     ["updated"]=>
        //     string(19) "2014-10-15T02:12:23"
        //     ["id"]=>
        //     string(36) "B394ACF9-5410-11E4-8184-00224D672507"
        //   }

        // レスポンスに payment キーが含まれていなかったらエラー。
        if(empty($result['payment']))
            throw new MojaviException('レスポンスに payment キーが含まれていない');

        // リターン。
        return array(
            'paymentId' => $result['payment']['entry'][0]['paymentId'],
            'transactionUrl' => $result['payment']['endpointUrl'],
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

        if( !isset($_GET['payment_id']) )
            throw new MojaviException('決済結果通知に payment_id パラメータがない');

        // 結果通知に含まれるデータを抽出。
        $data = Common::cutRefArray($_GET);
        unset($data['module'], $data['action']);

        return array(
            'result' => ($data['status'] == '10') ? 'ok' : 'unknown',
            'paymentId' => $data['payment_id'],
            'data' => $data,
        );
    }


    //-----------------------------------------------------------------------------------------------------
    // テキスト監査API郡

    /**
     * postTextを実装。
     */
    public function postText($text, $writerId, $ownerId) {

        // POST内容を作成。
        // ownerIdは指定できなさそう(と言うか必要ない)なので、無視する。
        $postData = array();
        $postData['data'] = $text;

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL.'/textdata/@app';

        // リクエスト実行。
        $result = MyOauth::post($ifUri, $postData);

        // 返ってきたIDをリターン。
        if(empty($result['entry'][0]['textDataId']))
            throw new MojaviException('レスポンスにテキストデータIDが含まれていない');
        else
            return $result['entry'][0]['textDataId'];
    }

    /**
     * getTextを実装。
     */
    public function getText($textIds) {

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL . sprintf('/textdata/@app/%s', implode(';', $textIds));

        // リクエスト実行。
        $result = MyOauth::get($ifUri, '404');

        // 戻り値作成。
        $return = array();
        foreach((array)$result['entry'] as $record)
            $return[ $record['textDataId'] ] = $record['data'];

        // リターン。
        return $return;
    }

    /**
     * deleteTextを実装。
     */
    public function deleteText($textIds) {

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL . sprintf('/textdata/@app/%s', implode(';', $textIds));

        // リクエスト実行。
        $result = MyOauth::delete($ifUri, '404');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * sendMessage()を実装
     */
    public function sendMessage($recipients, $body, $title, $url) {

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL . '/messages/@me/@outbox';

        // 半角38文字までなので切り詰める。コレをやっとかないとエラーで返しやがる…
        $body = mb_strimwidth($body, 0, 38, '..', 'UTF-8');

        // リクエストボディの雛形を作成。
        $post = array(
            'type' => 'NOTIFICATION',
            'recipients' => array(),
            'title' => $body,
            'urls' => array( array('type'=>'mobile', 'value'=>$url) ),
        );

        // モバゲはメッセージの複数宛先に対応していないため、一回ずつ呼び出す必要がある。
        foreach($recipients as $recipient) {

            $post['recipients'][0] = $recipient;

            // リクエスト実行。
            // 連続送信すると503、不明な宛先に送信すると400になるので、これを無視する。
            $result = MyOauth::post($ifUri, $post, array(503, 400));
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getArticleFormHead()を実装
     */
    public function getArticleFormHead($returnUrl, $body) {

        $returnUrl = htmlspecialchars($returnUrl, ENT_QUOTES);
        $subject = htmlspecialchars(SITE_NAME, ENT_QUOTES);
        $body = htmlspecialchars($body, ENT_QUOTES);

        return <<<HDOC
<form action="diary:self" method="post">
  <input type="hidden" name="url" value="{$returnUrl}" />
  <input type="hidden" name="subject" value="{$subject}" />
  <input type="hidden" name="body" value="{$body}" />
HDOC;
    }


    // 親クラスにはない独自のメンバ。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * アクセス中のユーザがデイリーのサービスポイント支給を受けているかどうか。
     *
     * @return bool     受けているならtrue、受けていないならfalse。
     */
    public function dispensedServiceToday() {

        $ifUri = PLATFORM_API_URL . '/bonus/@me/@login';
        $response = MyOauth::get($ifUri);

        return (bool)$response['is_taken'];
    }
}
