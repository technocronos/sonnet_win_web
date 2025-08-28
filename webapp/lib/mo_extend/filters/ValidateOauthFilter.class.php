<?php

/**
 * OAuth署名されたリクエストかどうかチェックするフィルタ。
 *
 * フィルタパラメータ)
 *     except           アクション名がここに挙げられている名前に一致するならoauthパラメータを検証しない。
 *     use_oauth_lane   明示的に "off" をセットすると、oauthパラメータをセッションに保存／復元しない。
 *     mixi_fix         アクション名がここに挙げられている名前に一致する場合、リクエストパラメータに
 *                      "sign" が含まれていて、その値が正しいなら OAuth 検証しない。
 *                      mixiで、Flash の loadVariables 先のURLをコンテナ経由にできないため、必要になる。
 */
class ValidateOauthFilter extends Filter {

    //-----------------------------------------------------------------------------------------------------
    /**
     * initialize() をオーバーライド。アクションの initialize より先に OAuth 関連の処理を行わなければ
     * ならないため、execute() ではなく initialize() で行う。
     */
    public function initialize($context, $parameters = null) {

        if( !parent::initialize($context, $parameters) )
            return false;

        // OAuthリクエストチェック。エラー時、制御は戻ってこない。
        $this->validateOauth();

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    public function execute($filterChain) {

        // 次のフィルタへ。
        $filterChain->execute();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * OAuthリクエストの署名チェックと、エラー時の処理を行う。
     * エラー時は制御を戻さない。
     */
    private function validateOauth() {

        // パラメータ正規化。
        if( is_null($this->getParameter('use_oauth_lane')) )
            $this->setParameter('use_oauth_lane', true);

        // スマホ対応。スマホでは OAuth パラメータは Authorization ヘッダではなく、クエリパラメータとして
        // (初回のみに)渡される。しかし、後続の処理は Authorization ヘッダを見ているので、Authorization に
        // 載せ替える。
        if($_REQUEST['oauth_signature'])
            self::moveOauthParams();

        // スマホ対応。$_GET に "oauth" キーがあって正当な値を保持しているなら OAuth 認証はパスして、
        // opensocial_owner_id などをこちらでセットする。仕組みは後述。
        if( $this->getParameter('use_oauth_lane')  &&  self::checkOauthLane($_GET['oauth']) )
            return;

        // フィルタパラメータ "mixi_fix" を処理。
        $fixActions = $this->getParameter('mixi_fix') ?: array();
        if(in_array($this->getContext()->getActionName(), (array)$fixActions)  &&  $_REQUEST['sign']) {
            Common::validateSign();
            return;
        }

        // 署名チェック。OKなら...
        $mess = MyOauth::validate();

        // ツタヤでShift_JIS端末なら署名チェック通ったことにする。
        // どうもShift_JISでのPOST送信時、絵文字が含まれていると署名が一致しない。MyOauthのほうにも書いてるが
        // ツタヤはUTF8変換してから署名するなどアホ連発なので何かバグがあるのだろう…
        if(PLATFORM_TYPE=='tuta'  &&  FPhoneUtil::getEncoding() == 'Shift_JIS')
            $mess = '';

        if(!$mess) {

            // スマホで、アプリ画面をインラインフレームで表示するようなプラットフォームの場合、OAuth
            // パラメータはセッションの初回リクエストのみなので、このときのパラメータをセッションデータと
            // して保存しておく必要がある。
            // 普通は $_SESSION を使うところだが、携帯対応で MiniSession を実装しているのでそちらと
            // クッキーを使う。
            // …つもりだったが、ブラウザ設定でサードパーティクッキーが制限されているとクッキーをセット
            // できないという問題があるので、$_GETで受け渡す。
            // 受け渡し部分は Common::normalizeUrlParams() を参照。
            if($this->getParameter('use_oauth_lane')  &&  FPhoneUtil::getCarrier() == 'pc'  &&  in_array(PLATFORM_TYPE, array('gree', 'hill', 'niji', 'nati')))
                $_GET['oauth'] = self::createOauthLane();

            // リターン。
            return;
        }

        // ここまできたら検証NG。

        // 検証NGならこれらの値は信用できない。
        $_REQUEST['opensocial_app_id'] = $_GET['opensocial_app_id'] = $_POST['opensocial_app_id'] = null;
        $_REQUEST['opensocial_viewer_id'] = $_GET['opensocial_viewer_id'] = $_POST['opensocial_viewer_id'] = null;
        $_REQUEST['opensocial_owner_id'] = $_GET['opensocial_owner_id'] = $_POST['opensocial_owner_id'] = null;

        // フィルタパラメータで除外アクションとされているならNGでもOK
        $exceptActions = $this->getParameter('except') ?: array();
        if(in_array($this->getContext()->getActionName(), (array)$exceptActions))
            return;

        // ログ内容を作成。
        $logging = array(
            date('Y/m/d H:i:s'),
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['HTTP_HOST'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT'],
            $_SERVER['HTTP_X_FORWARDED_FOR'],
            $_SERVER['HTTP_AUTHORIZATION'],
            file_get_contents('php://input'),
            $mess,
        );
        $logging = implode("\t", $logging) . "\n";

        // ログ記録。
        $fileName = sprintf('%s/oauth_%s.log', MO_LOG_DIR, date('Ymd'));
        file_put_contents($fileName, $logging, FILE_APPEND);

//Common::varDump($_GET["action"]);

        // 専用のアクションへリダイレクト。
        if($_GET["action"] != 'SessionExpired')
            Common::redirect('User', 'SessionExpired');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * GETパラメータに含まれているOAuth関連パラメータを Authorization ヘッダとして載せ替える。
     */
    private static function moveOauthParams() {

        // Authorization ヘッダの固定部分を作成。
        $_SERVER['HTTP_AUTHORIZATION'] = 'OAuth realm=""';

        // Oauthパラメータを一つずつ載せ替える。
        foreach(MyOauth::$OAUTH_PARAMS as $param) {

            // mixi様だけはGETでなくPOSTで送ってくる(クソ)仕様なので、$_REQUESTから取る。
            // これが脆弱性にはならないだろう。このあとのOauthチェックで引っかかるはず…
            $_SERVER['HTTP_AUTHORIZATION'] .= sprintf(',%s="%s"', $param, urlencode($_REQUEST[$param]));

            // 自画面遷移のURLや、トップ画面に戻るURLの生成などで問題になるため、$_GETからOauth関連の値を削除する。
            unset($_GET[$param]);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * OAuthで渡されたユーザIDなどのパラメータをセッションに保存する。戻り値はセッションID。
     */
    private static function createOauthLane() {

        return Service::create('Mini_Session')->setData(array(
            'user_id' => $_REQUEST['opensocial_owner_id'],
            'app_id' => $_REQUEST['opensocial_app_id'],
            'authorization' => $_SERVER['HTTP_AUTHORIZATION'],
            'timestamp' => time(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたIDのセッションにOAuthで渡されたパラメータが入っている場合は、そこから各種値を復元する。
     * 戻り値は復元に成功したかどうか。
     */
    private static function checkOauthLane($oauthId) {

        // 指定のIDがないならば復元不能。
        if(!$oauthId)
            return false;

        // 指定のIDのセッションデータを取得。
        $session = Service::create('Mini_Session')->getData($oauthId);

        if(PLATFORM_TYPE !='nati' ){
            // セッションデータがない、あるいはデータが違う場合は復元不能。
            if(!$session  ||  !$session['authorization'])
                return false;

            // 作成から時間が経ちすぎている、ユーザエージェントが違う、IPが離れすぎている場合は復元不能。
            // 最後の条件はちょっと気になる。3Gネットワークでの一連のアクセスは、IPが近いと断定していいのか
            // どうか…
            if(
                    ($session['timestamp'] + 24*60*60 < time()
                ||  $session['user_agent'] != $_SERVER['HTTP_USER_AGENT'] )
            ) {
                return false;
            }
        }else{
            //nativeはとりあえずsessionが無い場合のみ
            if(!$session)
                return false;
        }

        // ここまでくればOK。セッションからOauth関連の値を復元する。
        $_REQUEST['opensocial_app_id'] = $session['app_id'];
        $_REQUEST['opensocial_viewer_id'] = $session['user_id'];
        $_REQUEST['opensocial_owner_id'] = $session['user_id'];
        $_SERVER['HTTP_AUTHORIZATION'] = $session['authorization'];

        // 成功を表すリターン。
        return true;
    }
}
