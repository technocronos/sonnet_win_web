<?php

/**
 * ゲソてんへのアクセスを提供するクラス。
 */
class GesoApi extends PlatformCommon {

    const MEDAL_EVENT_TYPE = 11;            // 11固定

    //-----------------------------------------------------------------------------------------------------
    /**
     * queryThumbnailを実装。
     */
    public function queryThumbnail($userIds, $size = 'medium') {

#         // 戻り値初期化
#         $result = array();
#
#         // アプリ指定のサイズを、GREE API におけるサイズに変換する。
#         $sizeOnContainer = ($size == 'medium') ? 'normal' : 'large';
#
#         // GREEにはユーザーサムネイルサービスという便利なものがあるので、それを利用する。
#         foreach($userIds as $id)
#             $result[$id] = sprintf('thumbnail:show?user_id=%s&size=%s&adjust_vga=on', $id, $sizeOnContainer);
#
#         // リターン。
#         return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * isForbiddenを実装。
     */
    public function isForbidden($subject, $object) {

        return false;
#         // OAuthインターフェースのURIを取得。
#         $ifUri = PLATFORM_API_URL . "/ignorelist/{$subject}/@all/{$object}";
#
#         // OAuthリクエスト。
#         $result = MyOauth::get($ifUri, '404');
#
#         // ブラックリストがどうかをリターン。
#         return !empty($result['entry']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * readyPaymentを実装。
     * ゲソてんは、普通はプラットフォームが表示する決済確認画面をSAPが担当して出力するという驚愕仕様。従って、決済確定APIというものが別にある。
     * やろうと思えばトップページにアクセスしただけのユーザに無確認でアイテムを売りつけることが出来る。まあ実際の支払いの前に超クレームが来て
     * 発覚するだろうが…なかなか…合理的ぃ？
     */
    public function readyPayment($params) {

        // エラーチェック。
        if( empty($_REQUEST['opensocial_owner_id']) )
            throw new MojaviException('opensocial_owner_idがない');

        // callback パラメータのクエリ部分を抽出して変数 $callback に格納する。ただし、確定出来るパラメータは不要。
        // "=" や "&" があるとOauthリクエストするときにURLエンコードされるせいかどうも署名に失敗する。ので、URLエンコードされない文字に変換する。
        $components = explode('?', $params['callback']);
        parse_str($components[1], $callback);
        $dataId = $callback["dataId"];        //ついでにdataId抜き取っておく
        unset($callback['oauth'], $callback['module']);
        $callback = str_replace(['=','&'], ['-','_'], http_build_query($callback));

        // 同様に、finish パラメータ。
        $components = explode('?', $params['finish']);
        parse_str($components[1], $finish);
        unset($finish['oauth']);
        $finish = http_build_query($finish);

        // イメージURLの先頭固定部分を削除しておく。
        $imgurl = str_replace('http://'.$_SERVER['HTTP_HOST'].'/img/', '', $params['image_url']);

        // OAuthインターフェースのURIを取得。
        // ここの {itemId} の部分、つまり $callback を当てている部分だが、決済確認画面にも渡しているのでここにセットする必要はなかったりする。まあ決済確定APIのときと合わせておくかというところ。
        $path = sprintf('/billing/%s/%s/%s/%s/%s', $_REQUEST['opensocial_owner_id'], APP_ID, $callback, $params['amount'], $params['unit_price']*$params['amount']);
        $ifUri = PLATFORM_API_URL . $path;

        // OAuthリクエスト。
        $result = MyOauth::get($ifUri);

        // ゲソてんからのレスポンスのデータ構造はこんな感じ。
        //      array(1) {
        //        ["entry"]=>
        //        array(5) {
        //          ["itemId"]=>
        //          string(3) "100"
        //          ["orderId"]=>
        //          string(36) "da2b96e4-50fd-43eb-973c-e98d4dc659b1"
        //          ["message"]=>
        //          string(15) "コイン不足"
        //          ["responseCode"]=>
        //          string(2) "01"
        //          ["coin"]=>
        //          int(0)
        //        }
        //      }

        // 決済確認ページへのリダイレクトURLを生成する。普通はプラットフォームが準備する画面なのだが、こっちで用意しないといかん。
        $params = array(
            'order' => $result['entry']['orderId'],
            'code' => $result['entry']['responseCode'],
            'callback' => $callback,
            'finish' => $finish,
            'name' => $params['item_name'] . ($params['amount'] >= 2 ? $params['amount'].'個' : ''),
            'img' => $imgurl,
            'price' => $params['unit_price']*$params['amount'],
            'dataId' => $dataId,
        );
        $url = Common::genURL('User', 'PlatformPayment', $params);

        // 以下のキーを含む連想配列。
        return array(
            'paymentId' => $result['entry']['orderId'], // プラットフォームが発行した決済ID
            'transactionUrl' => $url,                   // プラットフォームが示している決済画面用URL
            'response' => $result,                      // プラットフォームから返されたレスポンスデータ
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 決済確定APIをコールする。
     *
     * @param   order_id
     * @param   item_id
     */
    public function settlePayment($orderId, $callback) {

        // エラーチェック。
        if( empty($_REQUEST['opensocial_owner_id']) )
            throw new MojaviException('opensocial_owner_idがない');

        // OAuthインターフェースのURIを取得。
        $path = sprintf('/billing/%s/%s/%s', $_REQUEST['opensocial_owner_id'], APP_ID, urlencode($callback));
        $ifUri = PLATFORM_API_URL . $path;

        $payment = Service::create('Payment_Log')->getRecord($orderId);

        // エラーチェック。
        if( !$payment )
            throw new MojaviException('paymentがない');


        if($payment["item_type"] == "GC"){
            $item = Service::create('Gacha_Master')->needRecord($payment['item_id']);
            $item_name = $item["gacha_name"];
        }else{
            $item = Service::create('Item_Master')->needRecord($payment['item_id']);
            $item_name = $item["item_name"];
        }

        // アイテムで、汎用攻撃アイコンを使用する場合は "att"。それ以外は先頭0詰めのID5桁。
        if(3000 <= $payment['item_id']  &&  $payment['item_id'] <= 3999)
            $image_url = sprintf('%simg/item/%s.gif', APP_WEB_ROOT, "att");
        else
            $image_url = sprintf('%simg/item/%05d.gif', APP_WEB_ROOT, $payment['item_id']);

        // リクエスト実行してレスポンスをリターン。
        $post = array(
            'orderId'=>$orderId,
            'amount'=>$payment["amount"],
            'item' => array(
                'itemName'=>$item_name,
                'imageUrl'=>$image_url,
                'itemPrice'=>$payment["unit_price"],
                'itemId'=>$payment["item_id"],
            ),
            'totalPrice' => $payment["unit_price"] * $payment["amount"],
        );
//Common::varLog($post);
        return MyOauth::post($ifUri, $post);

        // レスポンスはこんな感じ。
        # array(1) {
        #   ["entry"]=> array(7) {
        #     ["itemId"]=>          string(3) "123"
        #     ["appResponseCode"]=> string(2) "ok"
        #     ["orderId"]=>         string(36) "9ca32eb9-2326-4381-be27-85a816d53baa"
        #     ["message"]=>         string(7) "SUCCESS"
        #     ["appMessage"]=>      string(12) "no problem!!"
        #     ["coin"]=>            int(89200)
        #     ["responseCode"]=>    string(2) "00"
        #   }
        # }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * parsePaymentを実装。
     */
    public function parsePayment() {

        // 必要なパラメータがないならエラー。
        if( !isset($_POST['order_id']) )
            throw new MojaviException('決済結果通知に order_id パラメータがない');

        // リターン。
        return array(
            'result' => 'ok',
            'paymentId' => $_POST['order_id'],
            'data' => $_POST,
        );
    }


#     //-----------------------------------------------------------------------------------------------------
#     /**
#      * getInvitationUrlをオーバーライド。
#      */
#     public function getInvitationUrl($params) {
#
#         return sprintf('invite:friends?callbackurl=%s&subject=%s&body=%s'
#             , urlencode($params['finish'])
#             , urlencode( Common::adaptString($params['subject']) )
#             , urlencode( Common::adaptString($params['body']) )
#         );
#     }
#
#
#     //-----------------------------------------------------------------------------------------------------
#     /**
#      * parseLifeCycleIdsをオーバーライド。
#      */
#     public function parseLifeCycleIds() {
#
#         // URLサンプルはこんな感じ。複数IDの場合はカンマ区切り。
#         //     http://example.com/add?eventtype=event.addapp&opensocial_app_id=1&id=101,102,103&invite_from_id=1
#
#         return strlen($_REQUEST['id']) ? explode(',', $_REQUEST['id']) : array();
#     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * ゲソてん用プロモーションの一貫で、「1日1回かんたんなミッション」（無料ガチャを回す等）をやることによって、ポイントサイトのスタンプがもらえる
      * type=11,
      * 戻り値：00:成功、それ以外は失敗　message：エラー発生時にそのエラー内容を送信
      */
     public function postMedalEvent($value, $type) {

        // OAuthインターフェースのURIを取得。
        $path = "/event";
        $ifUri = PLATFORM_API_URL . $path;

        // リクエスト実行してレスポンスをリターン。
        $post = array("type"=>$type, "value"=>$value);
        return MyOauth::post($ifUri, $post);
     }

    //-----------------------------------------------------------------------------------------------------
    // テキスト監査API郡
    // ゲソてんにはテキスト監査がない。

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

        throw new MojaviException('ゲソてんにテキスト監査はない。');
    }

    /**
     * deleteTextを実装。
     */
    public function deleteText($textIds) {
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * sendMessage()を実装
     */
    public function sendMessage($recipients, $body, $title, $url) {

#         // プロキシモードではこの機能は利用できない。
#         if($_REQUEST['opensocial_owner_id'])
#             return;
#
#         // 宛先の最大件数は20まで。
#         if(count($recipients) > 20)
#             throw new MojaviException('GREEメッセージ送信の宛先は20までです。');
#
#         // APIのURIを決定。
#         $ifUri = PLATFORM_API_URL . '/messages/@me/@outbox';
#
#         $post = array(
#             'recipients' => $recipients,
#             'title' => $title,
#             'body' => $body,
#             'urls' => array( array('value'=>$url) ),
#         );
#
#         // リクエスト実行。
#         // 同一ユーザにBatchタイプで連続送信すると503になるので、これを無視する。
#         $result = MyOauth::post($ifUri, $post, 503);
#
#         // 複数宛先での戻り値はこんな感じ。
#         //     array(1) {
#         //       ["entry"]=>
#         //       string(0) ""
#         //     }
#         // 不明な宛先があってもこんな感じなので、何の参考にもならない...
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getArticleFormHead()を実装
     */
    public function getArticleFormHead($returnUrl, $body) {

#         // 本文の末尾にアプリへのURLを追加する。
#         if($body)
#             $body .= ' ' . PLATFORM_GADGET_URL;
#
#         $returnUrl = htmlspecialchars($returnUrl, ENT_QUOTES);
#         $body = htmlspecialchars($body, ENT_QUOTES);
#
#         return <<<HDOC
# <form action="mood:send" method="post">
#   <input type="hidden" name="callbackurl" value="{$returnUrl}" />
#   <input type="hidden" name="body" value="{$body}" />
# HDOC;
    }
}
