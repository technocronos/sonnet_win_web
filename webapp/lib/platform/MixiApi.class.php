<?php

/**
 * Mixi API へのアクセスを提供するクラス。
 */
class MixiApi extends PlatformCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * getInternalUid()をオーバーライド。
     */
    public function getInternalUid($platformUid) {

        // mixi は英数字混じりのユーザIDを使用しているので、ハッシュ化して32ビットの数値にする。
        $id = unpack('N', substr(sha1($platformUid, true), 0, 4));

        // …でもマイナスになるのはマズいので、先頭ビットを落としておく。
        return $id[1] & 0x7FFFFFFF;
    }

    /**
     * getPlatformUid()をオーバーライド。
     */
    public function getPlatformUid($internalUid) {

        // ユーザレコードから取得。なかったらエラー。
        $ret = Service::create('User_Info')->getPlatformUid($internalUid);
        if($ret === false)
            throw new MojaviException('ユーザレコードがないため変換できません。');

        return $ret;
    }


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

        // 戻り値初期化。
        $result = array();

        // 指定されたユーザIDを一つずつ処理する。
        foreach($userIds as $id) {

            // 指定のユーザのサムネイルURLを取得。取得できない場合もあることに注意。
            $profile = $this->queryProfile($id, 'thumbnailUrl');
            $url = $profile['thumbnailUrl'];

            // サイズ "medium" の場合は取得したURLを一部変更して、目的のURLとする。
            if($url  &&  $size == 'medium') {
                if( preg_match('#(.*?)/noimage_member\d+\.(\w+)$#', $url, $matches) )
                    $url = sprintf('%s/noimage_member40.%s', $matches[1], $matches[2]);
                else
                    $url = preg_replace('#\w\.(\w+)$#', 'm.$1', $url);
            }

            // 戻り値に格納。
            $result[$id] = $url;
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * queryFriendList() をオーバーライド。
     * まだ未検証だし、ユーザIDの問題も考慮していないので、未実装とする。
     */
    public function queryFriendList($userId = '@me', $queryFields = '') {

        throw new MojaviException('まだ未実装');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * postActivity() をオーバーライド。
     * 基本的には基底の実装と同じだが、細かく違いがある。
     */
    public function postActivity($title, $bodyUrl) {

        // APIのURIを取得。
        $ifUri = PLATFORM_API_URL . '/activities/@me/@self/@app';

        // POSTするデータを作成。
        $postData = array();
        $postData['title'] = $title;
        $postData['mobileUrl'] = Common::viaContainer($bodyUrl, true);

        // OAuthリクエスト。
        // 短い間隔でアクティビティを送信すると503が返るようなので、これを無視するようにする。
        // また、ミクシィはなぜか403を返してくるときがある(ユーザがアクティビティを拒否するような設定を
        // している？)ので、これも無視するようにする。
        $ret = MyOauth::post($ifUri, $postData, array(503, 403));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * isForbiddenを実装。
     */
    public function isForbidden($subject, $object) {

        // mixi にはこの機能ないみたいなので、常にfalse を返す。
        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * readyPaymentを実装。
     */
    public function readyPayment($params) {

        // OAuthインターフェースのURIを取得。
        $ifUri = 'http://api.mixi-platform.com/atom/mobile/point/@me?xoauth_requestor_id=' . $_REQUEST['opensocial_owner_id'];

        // mixi の決済通知とユーザ戻り先URLは、クエリパラメータ使用不可という糞仕様なので、
        // 独自拡張を利用するURLに変換する。独自拡張については /index.php を参照。
        $transFrom = array('?', '&', '=');
        $params['callback'] = str_replace($transFrom, '/', $params['callback']);
        $params['finish']   = str_replace($transFrom, '/', $params['finish']);

        // 送信するatomデータのテンプレート。先頭に改行やスペースが入るとダメなので注意。
        $atom = '<?xml version="1.0" encoding="utf-8" ?>
          <entry xmlns="http://www.w3.org/2005/Atom" xmlns:app="http://www.w3.org/2007/app" xmlns:point="http://mixi.jp/atom/ns#point">
            <title />
            <id />
            <updated />
            <author><name /></author>
            <content type="text/xml">
              <point:url callback_url="%s" finish_url="%s" />
              <point:items>
                <point:item id="%s" name="%s" point="%d" />
              </point:items>
              %s
            </content>
          </entry>
        ';

        // アイテム名を決定。複数買いなら個数をアイテム名に入れる。
        $itemName = $params['item_name'];
        if($params['amount'] >= 2)
            $itemName .= $params['amount'] . '個';

        // 値が必要な部分に値を入れる。
        $atom = sprintf($atom
            , htmlspecialchars($params['callback'], ENT_QUOTES)
            , htmlspecialchars($params['finish'], ENT_QUOTES)
            , htmlspecialchars($params['item_id'], ENT_QUOTES)
            , htmlspecialchars($itemName, ENT_QUOTES)
            , $params['unit_price'] * $params['amount']
            , (ENVIRONMENT_TYPE == 'test') ? '<point:status is_test="true" />' : ''
        );

        // atom データを送信して返されるデータを取得。
        $response = MyOauth::atom($ifUri, $atom);
        $res = new SimpleXMLElement($response);

        // 応答例)
        //     <entry xmlns="http://www.w3.org/2005/Atom">
        //       <title />
        //       <id>ポイント決済コード</id>
        //       <updated>ポイント決済情報作成日時(UTC)</updated>
        //       <author />
        //       <content />
        //       <link rel="related" href="mixiモバイルポイントインターフェースURL"/>
        //     </entry>

        // リターン。
        return array(
            'paymentId' => (string)$res->id,
            'transactionUrl' => (string)$res->link['href'],
            'response' => $response,
        );
    }


    /**
     * parsePaymentを実装。
     */
    public function parsePayment() {

        // 必要なパラメータがないならエラー。
        if( !isset($_GET['status']) )
            throw new MojaviException('決済結果通知に status パラメータがない');

        if( !isset($_GET['point_code']) )
            throw new MojaviException('決済結果通知に point_code パラメータがない');

        // 結果通知に含まれるデータを抽出。
        $data = Common::cutRefArray($_GET);
        unset($data['module'], $data['action']);

        // statusパラメータから、結果コードを取得。
        switch($data['status']) {
            case '10':  $code = 'ok';       break;
            case '20':  $code = 'cancel';   break;
            default:    $code = 'unknown';
        }

        return array(
            'result' => $code,
            'paymentId' => $data['point_code'],
            'data' => $data,
        );
    }


    /**
     * isPaymentBack() をオーバーライド
     */
    public function isPaymentBack() {

        return empty($_GET['point_code']) ? null : $_GET['point_code'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getInvitationUrlをオーバーライド
     */
    public function getInvitationUrl($params) {

        return sprintf('invite:friends?guid=ON&callback=%s', urlencode($params['finish']));
    }


    //-----------------------------------------------------------------------------------------------------
    // テキスト監査API郡

    /**
     * postTextを実装。
     */
    public function postText($text, $writerId, $ownerId) {

        // mixi にこの機能はない。
        return '';
    }

    /**
     * getTextを実装。
     */
    public function getText($textIds) {

        throw new MojaviException('mixiにテキスト監査の機能はありません。');
    }

    /**
     * deleteTextを実装。
     */
    public function deleteText($textIds) {

        // mixi にこの機能はない。
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * sendMessage()を実装
     */
    public function sendMessage($recipients, $body, $title, $url) {

        // mixi にこの機能はない。
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getArticleFormHead()を実装
     */
    public function getArticleFormHead($returnUrl, $body) {

        $returnUrl = htmlspecialchars(urlencode($returnUrl), ENT_QUOTES);
        $body = htmlspecialchars($body, ENT_QUOTES);
        $topUrl = htmlspecialchars(PLATFORM_GADGET_URL, ENT_QUOTES);

        return <<<HDOC
<form action="update:status?callback={$returnUrl}&guid=ON" method="post">
  <input type="hidden" name="mobileUrl" value="{$topUrl}" />
  <input type="text" name="body" value="{$body}" />
HDOC;
    }
}
