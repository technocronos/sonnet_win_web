<?php

/**
 * OAuthリクエストを行うためのユーティリティ
 */
class MyOauth {

    // oauth関連パラメータ。
    public static $OAUTH_PARAMS = array(
        'oauth_consumer_key',       'oauth_nonce',      'oauth_signature',
        'oauth_signature_method',   'oauth_timestamp',  'oauth_token',
        'oauth_token_secret',       'oauth_version',    'xoauth_signature_publickey'
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたURLへGETメソッドでOAuthリクエストを送信し、得られた結果を返す。
     *
     * @param string    OAuthインターフェースのURL。
     * @param mixed     成功と解釈してよい、200番台以外のステータスコード。配列で複数指定することもできる。
     * @return array    得られた結果をPHPの連想配列にしたもの。
     */
    public static function get($uri, $ignoreStatus = array()) {

        return self::request('GET', $uri, $ignoreStatus);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたURLへ、PUTメソッドでOAuthリクエストを送信する。
     *
     * @param string    OAuthインターフェースのURL。
     * @param array     POSTしたいデータを配列で。
     * @param mixed     成功と解釈してよい、200番台以外のステータスコード。配列で複数指定することもできる。
     * @return array    得られた結果をPHPの連想配列にしたもの。
     */
    public static function put($uri, $postData, $ignoreStatus = array()) {

        return self::request('PUT', $uri, $ignoreStatus, $postData);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたURLへ、POSTメソッドでOAuthリクエストを送信する。
     *
     * @param string    OAuthインターフェースのURL。
     * @param array     POSTしたいデータを配列で。
     * @param mixed     成功と解釈してよい、200番台以外のステータスコード。配列で複数指定することもできる。
     * @return array    得られた結果をPHPの連想配列にしたもの。
     */
    public static function post($uri, $postData, $ignoreStatus = array()) {

        return self::request('POST', $uri, $ignoreStatus, $postData);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたURLへ、DELETEメソッドでOAuthリクエストを送信する。
     *
     * @param string    OAuthインターフェースのURL。
     * @param mixed     成功と解釈してよい、200番台以外のステータスコード。配列で複数指定することもできる。
     * @return array    得られた結果をPHPの連想配列にしたもの。
     */
    public static function delete($uri, $ignoreStatus = array()) {

        return self::request('DELETE', $uri, $ignoreStatus);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたURLへ、Atom Syndication Format のデータをやりとりする。
     * mixi の決済API用。
     *
     * @param string    OAuthインターフェースのURL。
     * @param string    Atom Syndication Format 形式の文字列。
     * @param mixed     成功と解釈してよい、200番台以外のステータスコード。配列で複数指定することもできる。
     * @return string   レスポンスとして返ってきた Atom Syndication Format 形式の文字列。
     */
    public static function atom($uri, $postData, $ignoreStatus = array()) {

        // 引数正規化。配列に統一する。
        if( !is_array($ignoreStatus) )
            $ignoreStatus = $ignoreStatus ? array($ignoreStatus) : array();

        // 指定されたURIへリクエスト。
        $statusCode = self::doRequest($uri, $response, 'POST', $postData, 'application/atom+xml;type=entry');

        // ステータスコードが200番台でない、かつ、無視できないものである場合はエラー。
        if( !(200 <= $statusCode && $statusCode < 300)  &&  !in_array($statusCode, $ignoreStatus) ) {

            // エラーメッセージを作成。
            $errorMessage = sprintf(
                  'OAuthリクエストでエラーが返されました。statusCode: %s, message: %s'
                , $statusCode, $response
            );

            // エラースロー。
            throw new MojaviException($errorMessage);
        }

        // リターン。
        return $response;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 受信したHTTPリクエストのOAuth署名を確認する。
     *
     * @return string   確認できない場合にその理由を記した文字列。正常に確認できた場合はカラ文字列。
     */
    public static function validate() {

        //nativeはとりあえず素通り
        if(PLATFORM_TYPE=='nati' )
            return '';
        // Authorizationがないと検証しようがない。
        if(!isset($_SERVER['HTTP_AUTHORIZATION']))
            return 'リクエストヘッダに Authorization がない。';

        // Authorizationを解析して各値を抽出。
        $fields = self::parseAuthorization();

        // タイムスタンプが10分以上前なのはいくらなんでも古すぎだろう...
        if(!isset($fields['oauth_timestamp'])  ||  $fields['oauth_timestamp'] + 10*60 < time())
            return 'oauth_timestampが古すぎる。あるいはない。';

        // oauth_versionが明示されているなら、1.0しか受け付けない。
        if(isset($fields['oauth_version'])  &&  $fields['oauth_version'] != '1.0')
            return 'oauth_versionの値に対応していない';

        // "application/x-www-form-urlencoded" なPOSTならリクエストボディも取得。
        $typeInfo = self::parseContentType();
        if($typeInfo[0] == 'application/x-www-form-urlencoded')
            $body = file_get_contents('php://input');

        // …のはずなのだが、mixi君は常にPOSTを署名から外す独自(糞)仕様なようなので対応せざるを得ない。
        // しかもフィーチャーフォンだけの話であって、スマホではそのままっていうね…
        if(PLATFORM_TYPE == 'mixi'  &&  FPhoneUtil::getCarrier() != 'pc')
            $body = '';

        // ツタヤのバグ対処。POSTボディにSJIS文字が含まれているとき、それをUTF-8変換した状態でベース
        // ストリングを作成している。ユーザ数えるくらいしかいないくせにね。
        if(PLATFORM_TYPE == 'tuta'  &&  FPhoneUtil::getEncoding() == 'Shift_JIS') {

            // 一度取り出して…
            parse_str($body, $post);

            // Shift_JISからUTF-8に変換して…
            array_walk($post, function(&$v) {
                $v = mb_convert_encoding($v, 'UTF-8', 'SJIS-win');
            });

            // 戻す。
            $body = http_build_query($post);
        }

        // 以降のチェックは、署名方法にしたがって分岐。
        switch($fields['oauth_signature_method']) {
            case 'HMAC-SHA1':
                return self::validateOnSigning($fields, $body);
            case 'RSA-SHA1':
                return self::validateOnPublicKey($fields, $body);
            default:
                return 'oauth_signature_methodの値に対応していない、あるいはない';
        }
    }


    // private メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * OAuthリクエストして、エラー処理、レスポンスの加工等を行う。
     *
     * @param string    リクエストメソッド。"GET" や "POST" 等。
     * @param string    OAuthインターフェースのURL。
     * @param mixed     成功と解釈してよい、200番台以外のステータスコード。配列で複数指定することもできる。
     * @param array     POSTしたいデータを配列で。
     * @return array    レスポンスボディをJSONデコードしたもの。レスポンスボディがない場合はfalse。
     */
    private static function request($method, $uri, $ignoreStatus = array(), $postData = null) {

        // 引数正規化。配列に統一する。
        if( !is_array($ignoreStatus) )
            $ignoreStatus = $ignoreStatus ? array($ignoreStatus) : array();

        // 指定されたURIへリクエスト。
        $statusCode = self::doRequest($uri, $response, $method, $postData, 'application/json');

        // ツタヤは正常でもレスポンスボディが空というアホなインターフェースがあるので認識できるようにする。
        if(!$response)
            $response = sprintf('{"response-code":%s}', $statusCode);

        // レスポンスをJSONデコード。
        $responseObject = (strlen($response) > 0) ? json_decode($response, true) : false;

        // ステータスコードが200番台でない、かつ、無視できないものである場合はエラー。
        if( !(200 <= $statusCode && $statusCode < 300)  &&  !in_array($statusCode, $ignoreStatus) ) {

            // エラーメッセージを作成。
            $errorMessage = sprintf('OAuthリクエストでエラーが返されました。statusCode: %s, message: %s'
                , $statusCode
                , isset($responseObject['Error']['Message']) ? $responseObject['Error']['Message'] : $response
            );

            // エラースロー。
            throw new MojaviException($errorMessage, $statusCode);
        }

        // リターン。
        return $responseObject;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * OAuthリクエストを行う。
     * "Authorization" リクエストヘッダがある場合は Proxy モデルで、ない場合は Trusted モデルを使用する。
     *     ※https://developer.dena.jp/mbga/admin/docs/DeveloperSite_Document_Spec_Flow_PartnerToAPI
     *
     * @param string        OAuthリクエストを行う先のURI。
     * @param reference     レスポンスボディを格納したい変数。
     * @param string        リクエストメソッド。"GET" や "POST" 等。
     * @param mixed         POST時のリクエストボディ。リクエストボディがない場合はnull。
     *                      配列を指定すると、Content-Type にしたがってエンコードされる。
     * @param string        リクエスト時のContent-Type。
     * @return string       HTTPレスポンスコード。
     */
    private static function doRequest($uri, &$response, $method, $postData, $contentType = 'application/x-www-form-urlencoded') {

        // PHPの allow_url_fopen の機能を使ってHTTPリクエストを行う。

        // コンテキストオプションを調整。
        $contextOpts = array();
        $contextOpts['method'] = $method;
        $contextOpts['timeout'] = 3.0;              // タイムアウトを３秒に設定
        $contextOpts['ignore_errors'] = true;       // 200以外のステータスコードでも失敗にしない。
        $contextOpts['follow_location'] = false;    // Locationヘッダで示されたURLをたどらない。コレがないと201のLocationもたどっていく… ←PHP5.4.12で修正された？
        $contextOpts['header'] = array();

        // リクエストボディがある場合はさらに調整。
        if( !is_null($postData) ) {
            $requestBody = self::encodePostData($postData, $contentType);
            $contextOpts['header'][] = 'Content-Type: ' . $contentType;
            $contextOpts['content'] = $requestBody;
        }

        // OAuthリクエストで必要になるAuthorizationヘッダを作成。
        $contextOpts['header'][] = self::makeAuthorization($uri, $method, $requestBody, $contentType);

        // コンテキストオプションを作成。
        $opt = stream_context_create(array('http'=>$contextOpts));

        // モブキャストは xoauth_requestor_id を Authorization ではなくURLパラメータにしろという変態性癖らしい。
        if(PLATFORM_TYPE == 'mobu') {
            $requestorId = rawurlencode($_REQUEST['opensocial_viewer_id'] ?: APP_ID);
            $separator = in_str('?', $uri) ? '&' : '?';
            $uri .= "{$separator}xoauth_requestor_id={$requestorId}";
        }

        // リクエスト。
        // ちなみに、タイムアウトの場合は「failed to open stream: HTTP request failed!」という
        // Warningが発生する。
        $response = file_get_contents($uri, false, $opt);

        // PHPによって、変数 $http_response_header にレスポンスヘッダが格納されているので、
        // そこからステータスコードを取り出してリターン。
        return self::getHttpStatusCode($http_response_header);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * APIサーバへのOAuthリクエストで必要なAuthorizationヘッダを作成して返す。
     *
     * @param string        OAuthリクエストを行う先のURI。
     * @param string        リクエストメソッド。"GET" や "POST" 等。
     * @param string        リクエストホディ。
     * @param string        リクエスト時のContent-Type。
     * @return string       作成したAuthorizationヘッダ
     */
    private static function makeAuthorization($uri, $method, $body, $contentType) {

        // OAuthパラメータを決定。
        $oauthParameters = array(
            'oauth_nonce' =>            uniqid(),
            'oauth_timestamp' =>        time(),
            'oauth_consumer_key' =>     CONSUMER_KEY,
            'xoauth_requestor_id' =>    $_REQUEST['opensocial_viewer_id'] ?: APP_ID,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_version' =>          '1.0',
        );

        // プロキシタイプの場合はOAuthパラメータを追加。
        $tokenSecret = '';
        if($_REQUEST['opensocial_viewer_id']) {

            // Authorizationリクエストヘッダから、各フィールドの値を取得。
            $authorizeFields = self::parseAuthorization();

            // OAuthパラメータを追加。
            $oauthParameters['oauth_token'] = $authorizeFields['oauth_token'];
            $tokenSecret = $authorizeFields['oauth_token_secret'];
        }

        // "application/x-www-form-urlencoded" の場合、OAuth署名を作成するときに
        // リクエストボディも含めなければならない。
        $requiredBody = ($contentType == 'application/x-www-form-urlencoded') ? $body : '';

        // application/atom+xml でリクエストしようとしている場合は、OAuth パラメータに oauth_body_hash を
        // 含める。
        // OAuthの決まりとしては、application/x-www-form-urlencoded 以外の場合のみ、オプションで含める
        // ことになっているのだが( http://oauth.googlecode.com/svn/spec/ext/body_hash/1.0/drafts/4/spec.html )、
        // mixiの決済APIが「いれないとダメ」とホザいているので、このような条件で含める。
        if( 0 === stripos($contentType, 'application/atom+xml') )
            $oauthParameters['oauth_body_hash'] = base64_encode(sha1($body, true));

        // OAuth Signature を作成。
        $signature = self::oauthSign($uri, $method, $oauthParameters, $tokenSecret, $requiredBody);

        // Authorizationヘッダのフィールドを決定。
        $authorizationParams = $oauthParameters + array(
            'realm' =>           '',
            'oauth_signature' => $signature,
        );

        // リクエストするときにこのフィールドは隠さなければならない。
        unset($authorizationParams['oauth_token_secret']);

        // モブキャストは Authorization にこれがあるといかんらしい…代わりにURLパラメータにしろという
        // 弩変態仕様。
        if(PLATFORM_TYPE == 'mobu')
            unset($authorizationParams['xoauth_requestor_id']);

        // 作成したフィールドを「xxx="xxxx"」の形式の文字列に展開していく。
        $authorizationParamsString = array();
        foreach($authorizationParams as $key => $value)
            $authorizationParamsString[] = sprintf('%s="%s"', $key, rawurlencode($value));

        // Authorizationヘッダを作成して、リターン。
        return 'Authorization: OAuth ' . implode(', ', $authorizationParamsString);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "Authorization" リクエストヘッダから、各フィールドを取り出して連想配列として返す。
     * "Authorization" リクエストヘッダがない場合はカラ配列を返す。
     */
    private static function parseAuthorization() {

        // Authorizationヘッダがない場合はエラー。
        if( !$_SERVER['HTTP_AUTHORIZATION'] )
            return array();

        // フィールドをすべて検出する正規表現マッチング。
        // 普通、値は '"' で囲ってあるのだが、ツタヤは囲わないらしい。そのくせ先頭の "realm" だけは空文字を
        // 囲ってある謎仕様…
        if( !preg_match_all('/(\w+)="([^"]+)"/', $_SERVER['HTTP_AUTHORIZATION'], $matches, PREG_SET_ORDER) )
              preg_match_all('/(\w+)=([^,]+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches, PREG_SET_ORDER);

        // 検出したフィールドをすべて連想配列にセット。
        $result = array();
        $decoder = (PLATFORM_TYPE == 'hill') ? 'urldecode' : 'rawurldecode';
        foreach($matches as $match)
            $result[ $match[1] ] = $decoder($match[2]);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * HMAC-SHA1 などのように、こちらでも署名を作成して、提出された署名と一致するかどうかをチェックする
     * タイプの検証を行う。
     *
     * @param array     Authorizationヘッダの各フィールドを配列としたもの。parseAuthorization() の戻り値。
     * @param string    OAuth署名に "application/x-www-form-urlencoded" のリクエストボディが必要な場合は
     *                  リクエストボディをまるごと指定する。
     * @return string   確認できない場合にその理由を記した文字列。正常に確認できた場合はカラ文字列。
     */
    public static function validateOnSigning($fields, $body) {

        // 向こうが提示している署名を取得。
        $showSign = $fields['oauth_signature'];

        // リクエストされているURLを取得。
        $url = $_SERVER["REQUEST_SCHEME"] . '://' . strtolower($_SERVER['HTTP_HOST']) . $_SERVER["REQUEST_URI"];

        // oauth_token_secretがあるなら取得。
        $tokenSecret = isset($fields['oauth_token_secret']) ? $fields['oauth_token_secret'] : '';

        // OAuth署名を作成。
        $mySign = self::oauthSign($url, $_SERVER["REQUEST_METHOD"], $fields, $tokenSecret, $body);

        // 向こうが提示している署名と一致するかどうかを検査。
        if($mySign != $showSign)
            return "oauth_signatureの値が不正 計算値'{$mySign}' 提出値:'{$showSign}'";

        // ここまで来ればOK。
        return '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * RSA-SHA1 などのように、あちらの秘密鍵によって作成された署名を、こちらの公開鍵によってチェックする
     * タイプの検証を行う。
     *
     * @param array     Authorizationヘッダの各フィールドを配列としたもの。parseAuthorization() の戻り値。
     * @param string    OAuth署名に "application/x-www-form-urlencoded" のリクエストボディが必要な場合は
     *                  リクエストボディをまるごと指定する。
     * @return string   確認できない場合にその理由を記した文字列。正常に確認できた場合はカラ文字列。
     */
    private static function validateOnPublicKey($fields, $body) {

        // このリクエストでのOAuthベースストリングを取得。
        $uri = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $baseString = self::getBaseString($uri, $_SERVER["REQUEST_METHOD"], $fields, $body);

        // 提示されたOAuth署名をデコードする。
        $showSign = base64_decode($fields['oauth_signature']);

        // 公開鍵のファイル名を決定。まあアプリヒルズかmixiかだが…好き勝手やってるよコイツらは…
        // だからシェア低いんだよ…
        if(PLATFORM_TYPE == 'hill')
            $cerName = (ENVIRONMENT_TYPE == 'prod') ? 'applihills-prod.cer' : 'applihills-test.cer';
        else
            $cerName = $fields['xoauth_signature_publickey'];

        // 公開鍵を取得。
        $cert = @file_get_contents(RESOURCES_DIR.'/certificates/'.$cerName);
        if(!$cert)
            return "指定された名前の公開鍵({$cerName})がない";

        // 提示された署名が正しいかチェック。
        $opensslKey = openssl_pkey_get_public($cert);
        $ok = openssl_verify($baseString, $showSign, $opensslKey);
        openssl_free_key($opensslKey);

        // 戻り値をチェックしてリターン。
        return ($ok == 1) ? '' : '提示された署名が正しくないか、署名チェックでエラーが発生しました';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された情報からOAuth署名を作成する。
     *
     * @param string    OAuth署名とともに行われるHTTPリクエストのURL
     * @param string    リクエストメソッド。"GET" や "POST" 等。
     * @param array     署名作成に使用するOAuthパラメータ。
     * @param string    OAuth署名キーストリングを生成するときに使用する oauth_token_secret の値。
     * @param string    OAuth署名に "application/x-www-form-urlencoded" のリクエストボディが必要な場合は
     *                  リクエストボディをまるごと指定する。
     * @return string   与えられたパラメータから生成した、OAuth署名。
     */
    public static function oauthSign($uri, $method, $oauthParams, $tokenSecret = '', $body = '') {

        // 対応していないパラメータの場合はエラー。
        if(strtoupper($oauthParams['oauth_signature_method']) != 'HMAC-SHA1')
            throw new MojaviException('oauth_signature_methodは "HMAC-SHA1" しか対応していません。');

        if(isset($oauthParams['oauth_version'])  &&  $oauthParams['oauth_version'] != '1.0')
            throw new MojaviException('oauth_versionは "1.0" しか対応していません。');

        // 署名のベースストリングを作成。
        $baseString = self::getBaseString($uri, $method, $oauthParams, $body);

        // OAuth Signature のキーストリングを作成。
        $keyString = CONSUMER_SECRET.'&'.$tokenSecret;

        // OAuth Signature を作成。
        return base64_encode( hash_hmac('sha1', $baseString, $keyString, true) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された情報からOAuth署名のベースストリングを作成する。
     *
     * @param string    OAuth署名とともに行われるHTTPリクエストのURL
     * @param string    リクエストメソッド。"GET" や "POST" 等。
     * @param array     署名作成に使用するOAuthパラメータ。
     * @param string    OAuth署名に "application/x-www-form-urlencoded" のリクエストボディが必要な場合は
     *                  リクエストボディをまるごと指定する。
     * @return string   与えられたパラメータから生成した、OAuth署名。
     */
    public static function getBaseString($uri, $method, $oauthParams, $body) {

        // ベースストリングに使わない値をカット。
        unset($oauthParams['oauth_signature'], $oauthParams['realm']);

        // URLをパスとクエリストリングに分ける。
        list($urlPath, $queryString) = explode('?', $uri);

        // クエリストリングとPOST値を分解する。
        $queryParams = self::parseString($queryString);
        $bodyParams = self::parseString($body);

        // PC対応。PCでは OAuth パラメータは(初回のみに)クエリパラメータとして渡される(mixiはPOST)。
        // これらは OAuth 検証では使わないのでカットする。
        foreach(MyOauth::$OAUTH_PARAMS as $param) {
            unset($queryParams[$param]);
            unset($bodyParams[$param]);
        }

        // GREEのバグ(?)対処。OAuth規格としては...
        //   http://openid-foundation-japan.github.com/draft-hammer-oauth-10.html
        // の 3.4.1.3 の項を見ると、同名パラメータは上書きなどせずにすべて処理するはずなのだが、
        // GREE、モバゲ、mixiはGETとPOSTに同名パラメータがある場合はPOSTのほうのみを署名に使っているっぽい。
        // ワクプラとツタヤだけやで、正確にやっとんのは…
        if( !in_array(PLATFORM_TYPE, array('waku', 'tuta')) )
            $queryParams = array_diff_key($queryParams, $bodyParams);

        // mixi の決済APIへの対処。GETとOAuthに同名パラメータがある場合は一方だけを使うらしい。
        // 他のプラットフォームは不明…
        $queryParams = array_diff_key($queryParams, $oauthParams);

        // なんか、ツタヤ君はライフサイクルイベントでは "oauth_token" と "oauth_token_secret" は空だから
        // キーごと省略するらしい。そのくせOAuthの署名を計算するときは空白値を持つキーがあるものと
        // しないといけないんだって。アホなんだと思う。
        if(PLATFORM_TYPE == 'tuta'  &&  !$oauthParams['oauth_token']) {
            $oauthParams['oauth_token'] = '';
            $oauthParams['oauth_token_secret'] = '';
        }

        // クエリパラメータ、POSTパラメータ、OAuthパラメータをすべて結合する。
        $requestParams = self::addElements( self::addElements($queryParams, $bodyParams), $oauthParams );

        // パラメータ名順でソート。
        ksort($requestParams, SORT_STRING);

        // パラメータを一つずつ見て、署名のベースストリング第３パートを作成していく。
        $pairs = array();
        foreach($requestParams as $key => $value) {

            // 重複がないパラメータだったならそのまま「パラメータ名=値」に変換。
            if(!is_array($value)) {
                $pairs[] = sprintf('%s=%s', rawurlencode($key), rawurlencode($value));
                continue;
            }

            // 重複があった場合は、さらに値でソート。
            sort($value, SORT_STRING);

            // 同名のすべての値を「パラメータ名=値」に変換する
            foreach($value as $subValue)
                $pairs[] = sprintf('%s=%s', rawurlencode($key), rawurlencode($subValue));
        }

        // 署名のベースストリングを作成。
        return sprintf('%s&%s&%s'
            , $method
            , rawurlencode($urlPath)
            , rawurlencode(implode('&', $pairs))
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された配列を、指定された形式にしたがってエンコードしたものを返す。
     *
     * @param mixed     エンコードしたいデータを保持している配列。
     *                  文字列を指定した場合はそのまま返す。
     * @param string    エンコード形式。
     *                  現在は "application/x-www-form-urlencoded" か "application/json" のいずれか。
     *                  その他の場合はそのまま返す。
     * @return string   エンコードした文字列。
     */
    private static function encodePostData($postData, $contentType) {

        if( !is_array($postData) )
            return $postData;

        switch($contentType) {
            case 'application/x-www-form-urlencoded':
                return http_build_query($postData);
            case 'application/json':
                return json_encode($postData);
            default:
                return $postData;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたHTTPレスポンスヘッダの中からHTTPステータスコードを探して、返す。
     *
     * @param array     レスポンスヘッダの配列。
     * @return int      HTTPステータスコード。見つからなかった場合は0。
     */
    public static function getHttpStatusCode($headers) {

        // ヘッダを一つずつ見ていく。
        foreach($headers as $header) {

            // 「HTTP/1.0 200 OK」のようになっているものを正規表現で検出。
            // 見つけたらステータスコードの部分を返す。
            if( preg_match('#^HTTP/[\d\.]+\s+(\d+)\s+.*?$#i', $header, $matches) )
                return (int)$matches[1];
        }

        // ここまで来るのは見つからなかったから。
        return 0;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "Content-Type" HTTPヘッダから、各フィールドを取り出して連想配列として返す。
     * "Content-Type" リクエストヘッダがない場合はカラ配列を返す。
     *
     * 例)
     *      たとえば application/x-www-form-urlencoded; Charset=shift_jis ならば、次のような配列を返す。
     *
     *      [0] => "application/x-www-form-urlencoded"
     *      ["charaset"] => "shift_jis"
     *
     *      ※すべて小文字に変換されることに注意。
     */
    private static function parseContentType() {

        // CONTENT_TYPE ヘッダがない場合はエラー。
        if( !$_SERVER['CONTENT_TYPE'] )
            return array();

        // 値を ";" で区切って、各フィールドを取得する。
        $fields = explode(';', $_SERVER['CONTENT_TYPE']);

        // 戻り値となる配列 $result を初期化して、フィールドを一つずつ見ていく。
        $result = array();
        foreach($fields as $field) {

            // xxx=yyyyy となっているなら xxx をキーとして yyyyy を戻り値に格納する。
            if( preg_match('/^\s*(\w+)\s*=\s*(.*?)\s*$/', $field, $match) )
                $result[ strtolower($match[1]) ] = strtolower($match[2]);

            // そうでない場合は序数要素として格納。
            else
                $result[] = strtolower(trim($field));
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * x-www-form-urlencodedな文字列(a=2&b=3 みたいなやつ)を受け取って、キー名と値に分解して返す。
     * 同じキー名で複数の値がある場合は、値部分は序数配列で返る。
     *
     * 例) a=1&b=2&b=3 を渡すと、array('a'=>1, 'b'=>array(2, 3)) が返る。
     *
     * ※parse_strでも出来そうだが、重複があった場合の挙動や、"a[]" みたいな特殊なキー名の処理が異なる。
     */
    private static function parseString($formEncoded) {

        // 引数がカラなら戻り値もカラ。
        if(0 == strlen($formEncoded))
            return array();

        // 戻り値初期化。
        $result = array();

        // "&" で区切られた名前と値のをペアを一つずつ見ていく。
        foreach(explode('&', $formEncoded) as $pair) {

            // 最初の "=" の位置を検出。
            $separatorPos = strpos($pair, '=');

            // 名前と値に分解。"=" がなかったら全体が名前になる。
            if($separatorPos === false) {
                $name = $pair;
                $value = '';
            }else {
                $name =  substr($pair, 0, $separatorPos);
                $value = substr($pair, $separatorPos + 1);
            }

            // 名前と値をURLデコードする。
            $name = urldecode($name);
            $value = urldecode($value);

            // 戻り値に追加。同じ名前がすでにある場合は配列に変換する。
            if(array_key_exists($name, $result)) {
                if(!is_array($result[$name]))
                    $result[$name] = array($result[$name]);
                $result[$name][] = $value;
            }else {
                $result[$name] = $value;
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された二つの配列をマージした結果を返す。
     * PHP標準の array_merge とは違い、両者に同じキー名が存在する場合は両者の値の配列にする。
     */
    private static function addElements($array1, $array2) {

        // 変数 $array1 を戻り値として、$array2 の要素を追加していく。

        // $array2 の要素を一つずつ処理する。
        foreach($array2 as $key => $value) {

            // 同名要素が既に存在する場合は配列に変換する。
            if(array_key_exists($key, $array1)) {
                if(!is_array($array1[$key]))
                    $array1[$key] = array($array1[$key]);
                $array1[$key] = array_merge($array1[$key], (array)$value);
            }else {
                $array1[$key] = $value;
            }
        }

        // リターン。
        return $array1;
    }
}
