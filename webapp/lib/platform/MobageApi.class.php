<?php

/**
 * モバゲAPIへのアクセスを提供するクラス。
 */
class MobageApi extends PlatformCommon {

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
     */
    public function queryThumbnail($userIds, $size = 'medium') {

        // APIのURIを取得。
        $joinedUserId = implode(';', $userIds);
        $ifUri = PLATFORM_API_URL . sprintf(
              '/avatar/%s/@self/size=%s;view=upper'
            , $joinedUserId, rawurlencode($size)
        );

        // OAuthリクエスト。
        $result = MyOauth::get($ifUri, array('401','404'));

        // 結果の形式を統一する。"entry"キーに必ずアバターデータの配列があるようにする。
        if( !isset($result['entry']) ) {
            if( isset($result["avatar"]) ) {
                $id = reset($userIds);
                $result['entry'] = array("dummy:{$id}" => $result["avatar"]);
            }else {
                $result['entry'] = array();
            }
        }

        // 結果から、アバターURLを抽出。[ユーザID] ⇒ [URL] の形式にする。
        $urls = array();
        foreach($result['entry'] as $id => $data) {
            $urls[ substr(strstr($id, ':'), 1) ] = $data['url'];
        }

        // リターン。
        return $urls;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * postActivityをオーバーライド。
     */
    public function postActivity($title, $bodyUrl) {

        // 半角42文字までなので切り詰める。コレをやっとかないとエラーで返しやがる…
        $title = mb_strimwidth($title, 0, 42, '..', 'UTF-8');

        // あとは基底に任せる。
        return parent::postActivity($title, $bodyUrl);
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

        // OAuthリクエスト。
        $result = MyOauth::get($ifUri, '404');

        // ブラックリストがどうかをリターン。
        return !empty($result['totalResults']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * readyPaymentを実装。
     */
    public function readyPayment($params) {

        // OAuthインターフェースのURIを取得。
        $ifUri = PLATFORM_API_URL . '/payment/@me/@self/@app';

        // POSTするデータを作成。
        $postData = array();
        $postData['callbackUrl'] = $params['callback'];
        $postData['finishUrl'] = $params['finish'];
        $postData['entry'] = array();
        $postData['entry']['itemId'] = $params['item_id'];
        $postData['entry']['name'] = $params['item_name'];
        $postData['entry']['unitPrice'] = $params['unit_price'];
        $postData['entry']['amount'] = $params['amount'];
        $postData['entry']['imageUrl'] = $params['image_url'];
        $postData['entry']['description'] = $params['description'];

        // OAuthリクエスト。
        $result = MyOauth::post($ifUri, $postData);

        // モバゲからのレスポンスデータの構造はこんな感じ。
        //     array(4) {
        //       ["payment"]=>
        //       array(10) {
        //         ["finishUrl"]=>
        //         string(59) "http://tmdtest.linno.jp/?module=User&action=PaymentOk_GACHA"
        //         ["entry"]=>
        //         array(1) {
        //           [0]=>
        //           array(7) {
        //             ["itemId"]=>
        //             string(7) "9000001"
        //             ["imageUrl"]=>
        //             string(0) ""
        //             ["amount"]=>
        //             int(1)
        //             ["name"]=>
        //             string(33) "鍛冶屋LV1　レア刀ガチャ"
        //             ["paymentId"]=>
        //             string(36) "B89B5C37-8426-3BA0-AEE1-849E1B8F21BF"
        //             ["unitPrice"]=>
        //             string(3) "100"
        //             ["description"]=>
        //             string(0) ""
        //           }
        //         }
        //         ["status"]=>
        //         int(0)
        //         ["userId"]=>
        //         string(16) "sb.mbga.jp:19574"
        //         ["endpointUrl"]=>
        //         string(72) "http://sb.mbga.jp/_pf_pay_confirm?p=B89B5C37-8426-3BA0-AEE1-849E1B8F21BF"
        //         ["published"]=>
        //         string(19) "2010-12-24T04:24:08"
        //         ["callbackUrl"]=>
        //         string(52) "http://tmdtest.linno.jp/musou/paymentcheck_gacha.php"
        //         ["appId"]=>
        //         int(12001334)
        //         ["updated"]=>
        //         string(19) "2010-12-24T04:24:08"
        //         ["id"]=>
        //         string(36) "B89B5C37-8426-3BA0-AEE1-849E1B8F21BF"
        //       }
        //       ["startIndex"]=>
        //       int(1)
        //       ["itemsPerPage"]=>
        //       int(1)
        //       ["totalResults"]=>
        //       int(1)
        //     }

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
    //
    // モバゲでこれらのAPIを利用する場合はTextDataGroupを作成しておかなければならない。
    // TextDataGroup はひとつのアプリで5つまで作成できて、テキストを提出／取得／削除するときに
    // 都度 TextDataGroup を指定する仕様になっている。
    // しかしグループを使い分けるのはとても面倒くさいので、"message" というグループのみを使用するという
    // 前提で設計している。
    //
    // したがって、アプリを動かす前に "message" というグループを作成しておく必要がある。
    // 下記のコードで TextDataGroup の作成・削除・確認ができる。
    // 注意) Trustedモデルでないと受け付けられないので、モバゲを通さずに直接リクエストすること。
    //
    //     $api = new MobageApi();
    //
    //     // 'message' というグループを作成。
    //     $api->createTextGroup('message');
    //
    //     // 'message' というグループを削除。
    //     // $api->deleteTextGroup('message');
    //
    //     // 現在あるグループを確認。
    //     var_dump( $api->getTextGroups() );
    //
    // ※もし複数のグループを同時に使用できるように設計変更するなら、PlatformApi::postText でグループを
    //   指定する引数を増やすしかないと思う。このとき、MobageApi::postText でリターンするIDに細工をして、
    //   取得／削除においてはIDのみでグループを割り出せるような仕組みを施したほうが良いと思われる。

    /**
     * postTextを実装。
     */
    public function postText($text, $writerId, $ownerId) {

        // POST内容を作成。
        $postData = array();
        $postData['data'] = $text;
        if( !is_null($ownerId) )    $postData['ownerId'] = $ownerId;
        if( !is_null($writerId) )   $postData['writerId'] = $writerId;

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL . '/textdata/@app/message/@all';

        // リクエスト実行。
        $result = MyOauth::post($ifUri, $postData);

        // 返ってきたIDをリターン。
        if(empty($result['textData']['id']))
            throw new MojaviException('レスポンスにテキストデータIDが含まれていない');
        else
            return $result['textData']['id'];
    }

    /**
     * getTextを実装。
     */
    public function getText($textIds) {

        // APIのURIを決定。
        $ifUri = PLATFORM_API_URL
               . sprintf('/textdata/@app/message/@all/%s?count=1000&fields=id,data', implode(';', $textIds));

        // リクエスト実行。
        $result = MyOauth::get($ifUri, '404');

        // レスポンスをテキストデータの配列に統一。
        if(!$result) {
            $result = array();
        }else if( isset($result['entry']) ) {
            $result = $result['entry'];
        }else if( isset($result['textData']) ) {
            $result = array($result['textData']);
        }else {
            $result = array();
        }

        // 戻り値作成。
        $return = array();
        foreach($result as $record) {
            $return[ $record['id'] ] = $record['data'];
        }

        // リターン。
        return $return;
    }

    /**
     * deleteTextを実装。
     */
    public function deleteText($textIds) {

        // モバゲはID複数指定での削除に対応していない...
        foreach($textIds as $textId) {

            // APIのURIを決定。
            $ifUri = PLATFORM_API_URL . "/textdata/@app/message/@all/{$textId}";

            // リクエスト実行。
            MyOauth::delete($ifUri, '404');
        }
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
    // テキストグループ管理用API。

    /**
     * テキストグループの一覧を取得する。
     */
    public function getTextGroups() {
        return MyOauth::get(PLATFORM_API_URL . '/textdata/@app/@all');
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * テキストグループを作成する。
     */
    public function createTextGroup($groupName) {

        return MyOauth::post(PLATFORM_API_URL . '/textdata/@app/@all', array(
            'name' => $groupName,
        ));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * テキストグループを削除する。
     */
    public function deleteTextGroup($groupName) {
        return MyOauth::delete(PLATFORM_API_URL . sprintf('/textdata/@app/%s/@self', $groupName));
    }
}
