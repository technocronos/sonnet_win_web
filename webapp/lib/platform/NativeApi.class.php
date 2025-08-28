<?php

/**
 * API無しの場合のクラス。ネイティブでのWEBVIEW想定。
 */
class NativeApi extends PlatformCommon {

    //appstore、googleplayのカタログ情報
    const STORE_CATALOG = array(
            "coin_120" => 160,
            "coin_720" => 1010,
            "coin_1400" => 1938,
            "coin_4200" => 5768,
            "coin_6800" => 9152,
            "coin_11800" => 16590,
    );

    //-----------------------------------------------------------------------------------------------------
    /**
     * プラットフォームのユーザIDから内部で使用するユーザIDを得る。
     * 引数・戻り値仕様は PlatformApi::getInternalUid と同じ。
     */
    public function getInternalUid($platformUid) {

        // 英数字混じりのユーザIDを使用しているので、ハッシュ化して32ビットの数値にする。
        $id = unpack('N', substr(sha1($platformUid, true), 0, 4));

        // …でもマイナスになるのはマズいので、先頭ビットを落としておく。
        return $id[1] & 0x7FFFFFFF;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * queryProfileを実装。
     */
    public function queryProfile($platformUid = '@me', $queryFields = '') {
        return null;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * queryThumbnailを実装。
     */
    public function queryThumbnail($userIds, $size = 'medium') {
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * isForbiddenを実装。
     */
    public function isForbidden($subject, $object) {
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * readyPaymentを実装。
     */
    public function readyPayment($params) {
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * parsePaymentを実装。
     */
    public function parsePayment() {

Common::varLog("NativeApi::parsePayment run.. user=" . $_REQUEST['opensocial_owner_id']);

        // エラーチェック。
        if( empty($_REQUEST['opensocial_owner_id']) )
            throw new MojaviException('opensocial_owner_idがない');

        $uSvc = new User_InfoService();
        $user = $uSvc->getRecordByPuid($_REQUEST['opensocial_owner_id']);
//Common::varLog($user);
        // ユーザーがいないならエラー。
        if(is_null($user))
            throw new MojaviException('ユーザーがいない。opensocial_owner_id=' . $_REQUEST['opensocial_owner_id']);

        // 必要なパラメータがないならエラー。
        if( !isset($_POST['receipt']) )
            throw new MojaviException('決済結果通知に receipt パラメータがない');

Common::varLog("Carrier=" . Common::getCarrier());

        if(Common::getCarrier() == "android"){

            // 1. 署名はBase64エンコードされていることを想定
            $receipt   = $_POST['receipt'];
            $signature = base64_decode($_POST['signature']);

            $signed_data = $receipt;

/*
            $obj = json_decode($receipt);
            $obj2 = json_decode($obj->Payload);

            $signed_data = $obj2->json;
            $signature = base64_decode($obj2->signature);
*/

            // 2. レシートの検証
            $public_key      = file_get_contents(MO_BASE_DIR . '/resources/certificates/public.pem');
            $public_key_id   = openssl_get_publickey($public_key);
            $result = (int)openssl_verify($signed_data, $signature, $public_key_id);

            if ($result === 0) {
                //throw new MojaviException('署名が正しくありません');
Common::varLog('署名が正しくありません');
            } else if ($result === -1) {
                //throw new MojaviException('署名の検証でエラーが発生しました');
Common::varLog('署名の検証でエラーが発生しました');
            } else if ($result === 1) {
                $result = "ok";
Common::varLog('署名の検証OK');
            }

            // キーをメモリから解放 
            openssl_free_key($public_key_id);

            // 3. Developer Payloadの確認(Play Billing LibraryからdeveloperPayloadは非推奨)
/*
            $user_identifier = 'sonnet';
            $signed_data = json_decode($signed_data);

            if ($signed_data->developerPayload !== $user_identifier) {
                $result = -2;
                //throw new MojaviException('Developer Payloadが正しくありません');
            }
*/

            $transaction_id =  $_POST['order_id'];
            $product_id = $_POST['product_id'];

Common::varLog("product_id = " . $product_id);

        }else if(Common::getCarrier() == "iphone"){
            $receipt   = $_POST['receipt'];

            $obj = json_decode($receipt);

            $signed_data = $obj->Payload;
            $transaction_id = $obj->TransactionID;

Common::varLog("transaction_id = " . $transaction_id);

            $postData = json_encode(
                array('receipt-data' => $signed_data)
            );

            //apple問い合わせ
            $response = $this->post(IOS_VERIFY_URL, $postData);

Common::varLog($response);

            // 検証成功の場合
            if ($response->status == 0) {
                // 決済毎に transaction_id がユニークになるのでこれを控え、
                // 同じレシートで複数回リクエストがあったら無視するように。
                $result = "ok";
                //複数ある場合は同じトランザクションIDのものを使用する
                foreach($response->receipt->in_app as $in_app){
                    if($in_app->transaction_id == $transaction_id)
                        $product_id = $in_app->product_id;
                }

Common::varLog("product_id = " . $product_id);

            }else{
                $result = $response->status;
                $product_id = "coin_120"; //ダミー
            }
        }

        //okならpayment_logを作成する。
        // 決済データをDBに保存。
        $paymentSvc = new Payment_LogService();

        // 決済データの存在チェック
        $payment = Service::create('Payment_Log')->getRecord($transaction_id);

        if($payment == null){
Common::varLog("payment data insert start..");

            //$unit_price = str_replace("coin_", "" , $product_id);

            $unit_price = NativeApi::STORE_CATALOG[$product_id];

Common::varLog(NativeApi::STORE_CATALOG);
Common::varLog("product_id=" . $product_id);
Common::varLog("unit_price=" . $unit_price);

            $paymentSvc->insertRecord(array(
                'payment_id' => $transaction_id,
                'user_id' => $user["user_id"],
                'item_type' => "IT",
                'item_id' => COIN_ITEM_ID, //その商品の商品 ID
                'amount' => 1,
                'unit_price' => $unit_price,
                'ready_data' => $receipt,
            ));
        }

Common::varLog("NativeApi::parsePayment end..");

        // リターン。
        return array(
            'result' => $result,
            'paymentId' => $transaction_id,
            'data' => $receipt,
        );
    }


    public function test(){



    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * POSTする。
     */
    private function post($endpoint_url, $postData)
    {
        $ch = curl_init($endpoint_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = json_decode(curl_exec($ch));

        //エラーの場合、どうしようもないので終了
        if (curl_errno($ch)) { 
            $error = curl_error($ch); 
            throw new MojaviException('curlでエラーが返されました。error=' . $error);
        }

        curl_close($ch);
        return $response;
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
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * parseLifeCycleIdsをオーバーライド。
     */
    public function parseLifeCycleIds() {
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    // テキスト監査API郡

    /**
     * postTextを実装。
     */
    public function postText($text, $writerId, $ownerId) {
        return "";
    }

    /**
     * getTextを実装。
     */
    public function getText($textIds) {
        return null;
    }

    /**
     * deleteTextを実装。
     */
    public function deleteText($textIds) {
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * sendMessage()を実装
     */
    public function sendMessage($recipients, $body, $title, $url) {
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getArticleFormHead()を実装
     */
    public function getArticleFormHead($returnUrl, $body) {
        return null;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * postActivity()を実装
     */
    public function postActivity($title, $bodyUrl = null) {
        return null;
    }
}
