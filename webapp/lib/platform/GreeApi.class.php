<?php

/**
 * グリーAPIへのアクセスを提供するクラス。
 */
class GreeApi extends PlatformCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * queryThumbnailを実装。
     */
    public function queryThumbnail($userIds, $size = 'medium') {

        // 戻り値初期化
        $result = array();

        // アプリ指定のサイズを、GREE API におけるサイズに変換する。
        $sizeOnContainer = ($size == 'medium') ? 'normal' : 'large';

        // GREEにはユーザーサムネイルサービスという便利なものがあるので、それを利用する。
        foreach($userIds as $id)
            $result[$id] = sprintf('thumbnail:show?user_id=%s&size=%s&adjust_vga=on', $id, $sizeOnContainer);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * isForbiddenを実装。
     */
    public function isForbidden($subject, $object) {

        // OAuthインターフェースのURIを取得。
        $ifUri = PLATFORM_API_URL . "/ignorelist/{$subject}/@all/{$object}";

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
        $ifUri = PLATFORM_API_URL . '/payment/@me/@self/@app';

        // 本番の場合はhttpsにする。
        if(ENVIRONMENT_TYPE == 'prod')
            $ifUri = str_replace('http://', 'https://', $ifUri);

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
        $postData['paymentItems'][0]['description'] = $params['description'];

        // OAuthリクエスト。
        $result = MyOauth::post($ifUri, $postData);

        // GREEからのレスポンスのデータ構造はこんな感じ。
        //     array(1) {
        //       ["entry"]=>
        //       array(1) {
        //         [0]=>
        //         array(4) {
        //           ["paymentId"]=>
        //           string(38) "002675-0000010960-20101224132208588984"
        //           ["status"]=>
        //           string(1) "1"
        //           ["transactionUrl"]=>
        //           string(173) "http://coin-sb.gree.jp/?mode=payment&act=confirm&app_id=2675&payment_id=002675-0000010960-20101224132208588984&gree_auth_key=74658079e9515f6b68d1f050c5aef2abc65161ff&guid=ON"
        //           ["orderedTime"]=>
        //           string(19) "2010-12-24 13:22:08"
        //         }
        //       }
        //     }

        // レスポンスに entry キーが含まれていなかったらエラー。
        if(empty($result['entry']))
            throw new MojaviException('レスポンスに entry キーが含まれていない');

        // リターン。
        return array(
            'paymentId' => $result['entry'][0]['paymentId'],
            'transactionUrl' => $result['entry'][0]['transactionUrl'],
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
     * getInvitationUrlをオーバーライド。
     */
    public function getInvitationUrl($params) {

        return sprintf('invite:friends?callbackurl=%s&subject=%s&body=%s'
            , urlencode($params['finish'])
            , urlencode( Common::adaptString($params['subject']) )
            , urlencode( Common::adaptString($params['body']) )
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

    /**
     * postTextを実装。
     */
    public function postText($text, $writerId, $ownerId) {

        // POST内容を作成。
        // GREEではownerIdは指定できなさそうなので、無視する。
        $postData = array();
        $postData['data'] = $text;
        if( !is_null($writerId) )   $postData['authorId'] = $writerId;

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL . sprintf('/inspection/@app');

        // リクエスト実行。
        $result = MyOauth::post($ifUri, $postData);

        // 返ってきたIDをリターン。
        // 返ってきたIDをリターン。
        if(empty($result['entry'][0]['textId']))
            throw new MojaviException('レスポンスにテキストデータIDが含まれていない');
        else
            return $result['entry'][0]['textId'];
    }

    /**
     * getTextを実装。
     */
    public function getText($textIds) {

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL . sprintf('/inspection/@app/%s', implode(',', $textIds));

        // リクエスト実行。
        $result = MyOauth::get($ifUri, '404');

        // レスポンスをテキストデータの配列に統一。
        $result = isset($result['entry']) ? $result['entry'] : array();

        // 戻り値作成。
        $return = array();
        foreach($result as $record) {
            $return[ $record['textId'] ] = isset($record['data']) ? $record['data'] : null;
        }

        // リターン。
        return $return;
    }

    /**
     * deleteTextを実装。
     */
    public function deleteText($textIds) {

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL . sprintf('/inspection/@app/%s', implode(',', $textIds));

        // リクエスト実行。
        $result = MyOauth::delete($ifUri, '404');
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
        $ifUri = PLATFORM_API_URL . '/messages/@me/@outbox';

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


    //-----------------------------------------------------------------------------------------------------
    /**
     * getArticleFormHead()を実装
     */
    public function getArticleFormHead($returnUrl, $body) {

        // 本文の末尾にアプリへのURLを追加する。
        if($body)
            $body .= ' ' . PLATFORM_GADGET_URL;

        $returnUrl = htmlspecialchars($returnUrl, ENT_QUOTES);
        $body = htmlspecialchars($body, ENT_QUOTES);

        return <<<HDOC
<form action="mood:send" method="post">
  <input type="hidden" name="callbackurl" value="{$returnUrl}" />
  <input type="hidden" name="body" value="{$body}" />
HDOC;
    }
}
