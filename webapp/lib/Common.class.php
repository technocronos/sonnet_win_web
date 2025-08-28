<?php

require_once(MO_MPC_DIR . '/MobilePictogramConverter.php');

/**
 * 共通のユーティリティ関数を定義しているクラス。
 */
class Common {

     //-----------------------------------------------------------------------------------------------------
     /**
      * WebController::genURL のカスタム版。以下の点が異なる。
      *      ・引数仕様を変更
      *      ・戻り値を絶対URLとした
      *      ・モジュール名が省略されている場合は現在のモジュール名を使用する
      *      ・module=User&action=Main の場合に、userIdパラメータを付けるようにした。
      *         理由:メイン画面でブックマークを作成して他者に見せようとするユーザが多いので、
      *                期待されるように動作するために必要になる。
      *
      * @param string     モジュール名
      * @param string     アクション名
      * @param array      付加するパラメーター。第1、第2引数を省略して、これを第1とすることもできる。
      *                        以下のキーは特殊な意味で扱われる。
      *                             _self         trueにすると、現在のURLからパラメータが取得される。
      *                                             ただし、"result", "sign" キーは引き継がない。
      *                             _backto      trueにすると、現在のURLからbacktoパラメータが生成される。
      *                                             ただし、"result", "sign" キーは引き継がない。
      *                             _nocache     trueにすると、キャッシュが表示されないように、
      *                                             URLの末尾にタイムスタンプを埋め込む。
      *                             _sign         trueにすると、簡易的なURL捏造対策のパラメータを追加する。
      *                                             CSRF対策や、コンテナを経由せず直接リクエストする場合のOauth回避に
      *                                             使用する。
      *                                             値を検証するときはvalidateUrlSignメソッドを使う。
      *                                             ここでいうURL捏造対策とは...
      *                                             ・同じアクションへ他人がアクセスするときのURLの類推
      *                                             ・自分が別のアクションへ行く場合のURLの類推
      *                                             に対する対策のことを言う。パラメータ改ざん防止や
      *                                             ワンタイムトークンのような効果はないことに注意。
      * @return string    生成したURL
      */
     public static function genURL($modName, $actName = null, $opt = array()) {

        $params = self::normalizeUrlParams($modName, $actName, $opt);

        //APIの場合 scene=Home&id=1000 のように&で連結する。sceneは遷移するシーン名で必須パラメータ。
        if($params['module'] == 'Api'){
            $array = $opt;
            $array["scene"] = $actName;

            return http_build_query($array);
        }else{

            $query = '?' . http_build_query($params);

            // リターン。
            return APP_CONTROLLER . $query;
        }
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * genURL と同じだが、コンテナ経由のURLを返す点が異なる。
      * 第4引数は絶対URLで返すかどうか。
      */
     public static function genContainerURL($modName, $actName = null, $opt = null, $absolute = false) {

        $url = self::genUrl($modName, $actName, $opt);

        return self::adaptUrl($url, $absolute);
     }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に受け取ったURLを、環境に応じてコンテナ経由のURLに変換する。
     *
     * @param string    コンテナ経由でないURL
     * @param bool      絶対URLで返すかどうか
     */
    public static function adaptUrl($url, $absolute = false) {

        // ユーザ画面で、IFRAME で実行されていないならコンテナ経由にする。
        if(
                Controller::getInstance()->getContext()->getModuleName() != 'Admin'
            &&  !(FPhoneUtil::getCarrier() == 'pc'  &&  in_array(PLATFORM_TYPE, array('gree', 'hill', 'niji', 'geso', 'nati')))
        ) {
            $url = self::viaContainer($url, $absolute);
        }

        return $url;
    }

     //-----------------------------------------------------------------------------------------------------
     /**
      * HTTPリダイレクトを行う。引数仕様はgenURLと同じ。
      */
     public static function redirect($modName, $actName = null, $opt = array()) {
          Controller::getInstance()->redirect( self::genURL($modName, $actName, $opt) );
     }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に受け取ったURLを、コンテナ経由のURLに変換する。
     *
     * @param string    変換したいURL
     * @param bool      絶対URLで返すかどうか
     * @param string    携帯版コンテナのURLなら "mobile"、PC版コンテナなら "pc"、アクセス中のユーザに
     *                  従うなら "auto"。
     * @return string   コンテナ経由のURL
     */
    public static function viaContainer($url, $absolute = false, $type = 'auto') {

        $path = '';
        $params = array();

        // 携帯版のコンテナかPC版のコンテナかを決定。
        if($type == 'auto')
            $type = (FPhoneUtil::getCarrier() == 'pc') ? 'pc' : 'mobile';

        // 絶対パスが望まれているならコンテナのURLを取得。
        if($absolute)
            $path = ($type == 'pc') ? CONTAINER_URL_PC : CONTAINER_URL_MOBILE;

        // コンテナURLにおけるクエリパラメータ作成。ケータイ版なら "guid" パラメータが付く。
        if($type == 'mobile')
            $params['guid'] = 'ON';

        // Flashからの戻り時、WAKU+で wakuwaku_access_token ってのを付けないといけないんだって。
        if($_GET['wakuwaku_access_token'])
            $params['wakuwaku_access_token'] = $_GET['wakuwaku_access_token'];

        // 同様に、アプリヒルズで AHSID。
        // ただ、こいつは無条件に付けてるとPOSTフォームで「不正なパラメータ」とかぬかしやがる…でもFlashには
        // 必要。ってことで、回避策として $absolute フラグをチェックしている。
        if($_GET['AHSID']  &&  $absolute)
            $params['AHSID'] = $_GET['AHSID'];

        // "url" パラメータ。ただしユーザトップページの場合はカラにするべき。
        if($url  &&  $url != APP_CONTROLLER)
            $params['url'] = $url;

        // コンテナURLにおけるクエリ文字列を作成。
        if($params)
            $query = '?' . http_build_query($params);

        // 戻り値を作成。
        $result = $path . $query;

        // リターン…と言いたいところだが、完全にカラ文字になる可能性があるので、その場合は "?" 一文字とする。
        return $result ?: '?';
    }


     //-----------------------------------------------------------------------------------------------------
     /**
      * 三つの配列をマージする
      *
      * @param $array1
      * @param $array2
      * @param $array3
      * @param array
      */
     function margeTreeArray($array1, $array2, $array3)
     {
          if(!empty($array1))
          {
                $errorMessage = $array1;
          }

          if(!empty($array2))
          {
                if(!empty($errorMessage))
                {
                     $errorMessage = array_merge($errorMessage, $array2);
                }
                else
                {
                     $errorMessage = $array2;
                }
          }

          if(!empty($array3))
          {
                if(!empty($errorMessage))
                {
                     $errorMessage = array_merge($errorMessage, $array3);
                }
                else
                {
                     $errorMessage = $array3;
                }
          }

          return $errorMessage;
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * 引数に指定された、参照を格納している配列の値をコピーした配列を作成する。
      *
      * mojavi はその基盤上の処理でなぜか、$_GET, $_POST の要素をすべて参照に置き換えている。
      * なので...
      *      $var = $_GET;
      * などとし、$varの要素を書き換えると $_GET の中身も一緒に変わってしまう。
      * この挙動から解放される必要があるときに使用する。
      */
     public static function cutRefArray($target) {

          $result = array();

          foreach($target as $key => $value)
                $result[$key] = $value;

          return $result;
     }



     //-----------------------------------------------------------------------------------------------------
     /**
      * a～z、A～Z、0～9 からなるランダムな文字列を生成する。
      *
      * @param int $length 文字列の長さ
      */
     public static function createRandomString($length = 24) {

          // 戻り値を初期化。
          $result = "";

          // 内部的には24文字しか生成できないため、それより長い文字数を指定された場合は再帰呼び出しで処理する。
          while(24 < $length) {
                $result .= self::createRandomString(24);
                $length -= 24;
          }

          // なるべく一意になるような適当なバイナリハッシュを取得。
          $hash = sha1( $_SERVER["REMOTE_ADDR"] . $_SERVER["SERVER_ADDR"] . uniqid(), true );

          // 先頭18バイトをBASE64エンコード。
          $hash_string = base64_encode( substr($hash, 0, 18) );

          // "+" か "/" が含まれている可能性があるので、適当な文字に置き換える。
          $hash_string = str_replace( array('+', '/'), 'x', $hash_string );

          // 戻り値として連結。
          $result .= substr($hash_string, 0, $length);

          // リターン。
          return $result;
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * ユーザエージェントのキャリア種別を返す。
      * "docomo", "au", "softbank", "pc" のいずれか。
      */
     public static function getCarrier() {

          static $result;

          // まだ調べてないなら調べる。
          if(!$result) {
                switch(true) {
                     case preg_match("/^DoCoMo/", $_SERVER['HTTP_USER_AGENT']):
                          $result = 'docomo';
                          break;
                     case preg_match("/^J-PHONE|^Vodafone|^SoftBank|^Semulator|^Vemulator/", $_SERVER['HTTP_USER_AGENT']):
                          $result = 'softbank';
                          break;
                     case preg_match("/^UP.Browser|^KDDI/", $_SERVER['HTTP_USER_AGENT']):
                          $result = 'au';
                          break;
                     case preg_match("/iPhone|iPad|iPod|CFNetwork|Macintosh/", $_SERVER['HTTP_USER_AGENT']):
                          $result = 'iphone';
                          break;
                     case preg_match("/Android|^dream|CUPCAKE|blackberry9500|blackberry9530|blackberry9520|blackberry9550|blackberry9800|webOS|incognito|webmate/", $_SERVER['HTTP_USER_AGENT']):
                          $result = 'android';
                          break;
                     default:
                          $result = 'pc';
                }
          }

          // リターン。
          return $result;
     }

     //-----------------------------------------------------------------------------------------------------
     /**
      * タブレット端末かどうかを返す
      */
     public static function isTablet() {

          $ua = $_SERVER['HTTP_USER_AGENT'];

          if(strpos($ua, 'iPhone') || (strpos($ua, 'Android') && strpos($ua, 'Mobile'))){
              return 'phone';
          }else if(strpos($ua, 'iPad') || strpos($ua, 'Android')){
              return 'tablet';
          }else{
              return 'pc';
          }
     }

     //-----------------------------------------------------------------------------------------------------
     /**
      * 引数で指定されたキャリアの文字コードを返す。
      *
      * @param string     ユーザエージェントのキャリア種別。Common::getCarrier()の戻り値。
      *                        省略した場合は現在アクセス中のユーザから取得する。
      * @return string    文字コード。"Shift_JIS" か "UTF-8" のいずれか。
      */
     public static function getEncoding($carrier = null) {

          // 引数省略時は現在アクセス中のユーザから取得。
          if(!$carrier)
                $carrier = self::getCarrier();

          // smart の場合はとにかくUTF-8(GREE,mobage)
        if($carrier == "iphone" || $carrier == "android")
                     return 'UTF-8';

          // mixi の場合はとにかくSJIS
          if(PLATFORM_TYPE == 'mixi')
                return 'Shift_JIS';

          // 普通は docomo, au が SJIS で、softbank, pc が UTF-8。
          switch($carrier) {
                case 'docomo':
                case 'au':
                     return 'Shift_JIS';
                case 'softbank':
                case 'pc':
                     return 'UTF-8';
          }
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * 指定された文字列をユーザエージェントに合わせて文字コード変換・絵文字変換する。
      *
      * @param string     変換したい文字列。UTF8で絵文字はドコモのものであること
      * @param string     目的種別。"web":WEBページで利用  か  "swf":FLASHで利用  のどちらか
      * @param string     変換後の文字列。
      */
     public static function adaptString($string, $purpose = 'web') {

//$string = "やったー";

          // ユーザエージェントのキャリアを取得。
          $carrier = self::getCarrier();

          // 文字コードを決定。FLASHで利用する場合はとにかく SJIS。
          if($purpose == 'swf'){
                $charset = 'Shift_JIS';
          }else if($purpose == 'web'){
                $charset = Common::getEncoding($carrier);
          }else if($purpose == 'trans_html5'){
                $charset = Common::getEncoding($carrier);

                //androidは絵文字削る（暫定対応）
                if(self::getCarrier() == 'android' || self::getCarrier() == 'iphone')
                     $string = self::replaceEmoji($string, '');

                //後はwebと同様に処理する
                $purpose = 'web';
          }else if($purpose == 'html5'){
            //pexでSWFをHTML5に変換して埋め込む場合。
                $charset = 'Shift_JIS';

                //androidは絵文字削る（暫定対応）
                if(self::getCarrier() == 'android' || self::getCarrier() == 'iphone')
                     $string = self::replaceEmoji($string, '');
              }

          // 絵文字種別を決定。
          switch($carrier) {
                case 'softbank':     $pictType = 'SOFTBANK';      break;
                case 'au':             $pictType = 'EZWEB';          break;
                case 'iphone':        $pictType = 'IPHONE';          break;
                case 'android':      $pictType = 'IPHONE';          break;
                default:                $pictType = 'FOMA';
          }

          // 文字コードをSJISにするなら変換。
          if($charset == 'Shift_JIS')
                $string = mb_convert_encoding($string, 'SJIS-WIN', 'UTF-8');

          // 絵文字をドコモ以外にするなら変換。
          if($pictType != 'FOMA') {
                $mpcCharset = ($charset == 'Shift_JIS') ? 'SJIS' : 'UTF-8';
                if(self::getCarrier() == 'android' && $purpose == 'web'){
                     $mpc = MobilePictogramConverter::factory($string, 'FOMA', $mpcCharset, 'RAW', $purpose, 'http://'. $_SERVER["HTTP_HOST"] . '/');
                     $string = $mpc->Convert($pictType, 'IMG');
                }else{
                     $mpc = MobilePictogramConverter::factory($string, 'FOMA', $mpcCharset, 'RAW', $purpose);
//print_r($mpc);
                     $string = $mpc->Convert($pictType);
                }
//Common::varDump($string);
          }

          // リターン。
          return $string;
     }

     //---------------------------------------------------------------------------------------------------------
     /**
      * 引数に指定されたUTF-8の文字列に、携帯の絵文字が含まれているかどうかを返す。
      */
     public static function emojiExists($val) {

          // UTF32BEに変換。
          $utf32 = mb_convert_encoding($val, 'UTF-32BE', 'UTF-8');

          // 一文字ずつ見ていく。
          for($i = 0 ; $i < strlen($utf32) ; $i += 4) {

                // UNICODE番号を得る。
                sscanf(bin2hex(substr($utf32, $i, 4)), '%x', $unicode);

                // 絵文字だったらtrueでリターン。
                if(0xE001 <= $unicode  &&  $unicode <= 0xE05A) return true;
                if(0xE101 <= $unicode  &&  $unicode <= 0xE15A) return true;
                if(0xE201 <= $unicode  &&  $unicode <= 0xE25A) return true;
                if(0xE301 <= $unicode  &&  $unicode <= 0xE34D) return true;
                if(0xE401 <= $unicode  &&  $unicode <= 0xE44C) return true;
                if(0xE468 <= $unicode  &&  $unicode <= 0xE5DF) return true;
                if(0xE63E <= $unicode  &&  $unicode <= 0xE6A5) return true;
                if(0xE6AC <= $unicode  &&  $unicode <= 0xE6AE) return true;
                if(0xE6B1 <= $unicode  &&  $unicode <= 0xE6B3) return true;
                if(0xE6B7 <= $unicode  &&  $unicode <= 0xE6BA) return true;
                if(0xE6CE <= $unicode  &&  $unicode <= 0xE757) return true;
                if(0xEA80 <= $unicode  &&  $unicode <= 0xEB88) return true;
          }

          // ここまで来るのは絵文字がなかったため。falseリターン。
          return false;
     }

     //---------------------------------------------------------------------------------------------------------
     /**
      * 引数に指定されたUTF-8の文字列から、携帯絵文字を空白などに置き換えた文字列を返す。
      */
     public static function replaceEmoji($string, $replace = '　') {

          // UTF32BEに変換。
          $string =  mb_convert_encoding($string, 'UTF-32BE', 'UTF-8');
          $replace = mb_convert_encoding($replace, 'UTF-32BE', 'UTF-8');

          // 戻り値初期化。
          $result = '';

          // 一文字ずつ見ていく。
          for($i = 0 ; $i < strlen($string) ; $i += 4) {

                // UNICODE番号を得る。
                sscanf(bin2hex(substr($string, $i, 4)), '%x', $unicode);

                // 絵文字だったら置き換える。
                if(
                          (0xE001 <= $unicode  &&  $unicode <= 0xE05A)
                     ||  (0xE101 <= $unicode  &&  $unicode <= 0xE15A)
                     ||  (0xE201 <= $unicode  &&  $unicode <= 0xE25A)
                     ||  (0xE301 <= $unicode  &&  $unicode <= 0xE34D)
                     ||  (0xE401 <= $unicode  &&  $unicode <= 0xE44C)
                     ||  (0xE468 <= $unicode  &&  $unicode <= 0xE5DF)
                     ||  (0xE63E <= $unicode  &&  $unicode <= 0xE6A5)
                     ||  (0xE6AC <= $unicode  &&  $unicode <= 0xE6AE)
                     ||  (0xE6B1 <= $unicode  &&  $unicode <= 0xE6B3)
                     ||  (0xE6B7 <= $unicode  &&  $unicode <= 0xE6BA)
                     ||  (0xE6CE <= $unicode  &&  $unicode <= 0xE757)
                     ||  (0xEA80 <= $unicode  &&  $unicode <= 0xEB88)
                ) {
                     $result .= $replace;

                // 絵文字でないならそのまま戻り値に追加。
                }else {
                     $result .= substr($string, $i, 4);
                }
          }

          // UTF-8 に戻してリターン。
          return mb_convert_encoding($result, 'UTF-8', 'UTF-32BE');
     }

     //-----------------------------------------------------------------------------------------------------
     /**
      * [廃止予定] 代わりに AppUtil::embedUserFace を利用されたい。
      * 指定された結果セットに "thumbnail_url" 列を追加して、そこにプラットフォームのユーザ画像URLを
      * 格納する。
      *
      * @param array      処理対象の結果セットを表す２次元配列。結果もこの配列に直接返される。
      *                        第二引数で指定する列にユーザIDが格納されていること。
      * @param string     ユーザIDが格納されている列名
      * @param string     取得したアバターURLを格納する列名。省略時は "thumbnail_url"。
      */
     public static function embedThumbnailColumn(&$resultset, $userIdColumn = 'user_id', $avatarColumn = 'thumbnail_url') {

          // ユーザIDの一覧を取得する。
          $userIds = array_unique( ResultsetUtil::colValues($resultset, $userIdColumn) );

          // サムネイルURL問い合わせ。
          $urls = Service::create('User_Thumbnail')->getThumbnailsIn($userIds);

          // 指定された結果セットのレコードを見ていって、アバターURLを埋め込んでいく。
          foreach($resultset as &$record)
                $record[$avatarColumn] = $urls[ $record[$userIdColumn] ];
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * ユーザの自由入力をチェックする。
      *
      * @param string     入力された値。正規化(両端の空白を削除等)した結果もココに返される。
      * @param array      チェック内容を定義する配列。以下のキーのうち、必要なものをセットしておくこと。
      *                             required     必須かどうか。省略時はtrue。
      *                             length        最大文字長。半角換算でセット。省略時はチェックしない。
      * @param int         エラーメッセージタイプ。省略時は1。
      * @return string    エラーメッセージ。エラーがない場合はカラ文字列。
      */
     public static function validateInput(&$input, $check = array(), $msgType = 1) {

          // エラーメッセージを策定
          static $MSG = array(
                1 => array(
                     'required' => 'なんも入力されてないのだ',
                     'length' => '長すぎなのだ｡全角%d(半角%d)文字までにするのだ',
                     'ng' => 'NGﾜｰﾄﾞがあるらしいのだ',
                ),
                2 => array(
                     'required' => '入力してください｡',
                     'length' => '全角%d(半角%d)文字までにしてください｡',
                     'ng' => 'NGワードが含まれています｡',
                ),
          );

          // 使用するメッセージセットを取得。
          $msgSet = &$MSG[$msgType];

          // チェック内容のデフォルト値をセット。
          $check += array('required'=>true, 'length'=>0);

          // 両端の空白を削除。
          $input = trim($input);

          // 入力されていない場合。必須ならエラー。任意入力ならOK。
          if(strlen($input) == 0)
                return $check['required'] ? $msgSet['required'] : '';

          // 長さチェック。
          if($check['length']  &&  mb_strwidth($input, 'UTF-8') > $check['length'])
                return sprintf($msgSet['length'], (int)($check['length']/2), $check['length']);

          // NGワードチェック。
          if(!PlatformApi::checkNgWord($input))
                return $msgSet['ng'];

          // ここまで来ればOK。
          return '';
     }


     //-----------------------------------------------------------------------------------------------------
     /**
      * Common::genUrl() の "_sign" で説明しているキーの値の妥当性をチェックする。
      *
      * @return bool      妥当ならtrue、妥当でないならfalse。
      */
     public static function validateSign() {

          $context = Controller::getInstance()->getContext();

          $value = self::getUrlSign($context->getModuleName(), $context->getActionName());

          // ここまでくればOK。
          return $value == $_REQUEST['sign'];
     }

     /**
      * Common::genUrl() の "_sign" で説明しているキーの値を取得する。
      * 引数には、検証したいモジュール名とアクション名を指定する。
      */
     private static function getUrlSign($module, $action) {

          // 穴を探せばいろいろありそうな実装だけど、簡易的なものだし、これでヨシとする。

          // シークレットを決める。
          $secret = 'faj;weifa3q$#Q"#$FRFfaefa#"%4q354t53t5#$';

          // 検証に使用するパラメータを集める。
          $params = array(
                'module' => $module, 'action' => $action,
                'opensocial_app_id' => $_REQUEST['opensocial_app_id'],
                'opensocial_owner_id' => $_REQUEST['opensocial_owner_id']
          );

          // 値を連結してシークレットをつなげてキーとする。
          $key = http_build_query($params) . $secret;

          // キーをハッシュして完成。
          return sha1($key);
     }


     //---------------------------------------------------------------------------------------------------------
     /**
      * デバック用。
      * "<html><body>" と "</body></html>" で挟んだ上で、var_dump を行い、exitする。
      */
     public static function varDump($var) {

          echo '<html><body><pre>';
          $args = func_get_args();
          call_user_func_array('var_dump', $args);
          echo '</pre></body></html>';
          exit();
     }

    //print_rのラッパ。exitしない
     public static function pr($str) {
        print_r("<html><body>");
        print_r($str);
        print_r("</body></html>");
    }

     /**
      * デバック用。引数に指定した値をログファイルに書き込む。
      */
     public static function varLog($var, $continue = true) {

          global $logger;

          if($logger) {

                ob_start();
                var_dump($var);
                $output = ob_get_contents();
                ob_end_clean();

                file_put_contents(MO_LOG_DIR.'/debug.log', date("Y-m-d H:i:s") . ":" . $output, FILE_APPEND);
          }

          if(!$continue)
                exit();
     }


    /**
     * ----------------------------------------------------------
     * getCurrentTime()
     * 現在時間を取得する
     * ----------------------------------------------------------
     */
    public static function getCurrentTime() {
      $dt = new DateTime();
      $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
     
      return $dt->format('Y-m-d H:i:s');
    }


    /*
     * 第n w 曜日の日付を求める
     * @param <integer> $year    年
     * @param <integer> $month   月
     * @param <integer> $no      第 n 週
     * @param <integer> $week    0 (日曜)から 6 (土曜)
     * @return <DateTime>
     */
    public static function getDateFromWeekInfo($year, $month, $no, $week)
    {
        // 最初の一週間分の曜日を求める
        // 1日の曜日を求める
     
        $date = new DateTime(date("Ymd"));

        /*
         * 以下の記述は PHP 5.3.0 以降で可能
         * それ以前の PHP では setDate が成功した場合は null がリターンされるので、
         * 2行に分けて $date->format('w'); のように記述すること
         */
        // 指定年月の１日の曜日を抽出する
        $first_week = $date->setDate($year, $month, 1)
                           ->format('w'); // 0 (日曜)から 6 (土曜) を取得する
     
        // 1日の曜日の指定週の日付を求める
        $day = ($no - 1) * 7 + 1;
     
        // 指定曜日と1日の曜日の差分（日数）を求め、指定の日付を計算する
        $diff = $week - $first_week;
        if($diff < 0) { 
            $day += $diff + 7; // 1日の曜日より前の曜日の場合 
        } else { 
            $day += $diff; // 1日の曜日より後の曜日の場合 
        } 
        // 組み立てた日付が月の最終日（日数）よりも大きい場合は false リターン 
        if($date->format('t') < $day) { return false; } 
         
        // 前述のとおり、PHP 5.3.0 以降での記述 
        return $date->setDate($year, $month, $day);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * Common::genUrl() の "_token" で説明しているキーの値の妥当性をチェックする。
     *
     * @param array     妥当でない場合にリダイレクトする先のURLを表すパラメータ。
     *                  省略時はメニューに飛ばされる。
     */
    public static function checkToken($urlParams = null) {

        // 非ユーザモジュールではチェックできない
        if(!$_REQUEST['opensocial_owner_id'])
            throw new MojaviException('非ユーザモジュールでトークンをチェックしようとした');

        // プラットフォームユーザIDから内部ユーザIDを取得。
        $userId = PlatformApi::getInternalUid($_REQUEST['opensocial_owner_id']);

        // トークンを処理する。不正だったらリダイレクト。
        if( !UserToken::call('punchToken', $userId, $_GET['token']) ) {

            if(!$urlParams)
                $urlParams = array('module'=>'User', 'action'=>'Menu');

            Common::redirect($urlParams);
        }
    }

     // private
     //=====================================================================================================

     //-----------------------------------------------------------------------------------------------------
     /**
      * genURL や genContainerURL の引数正規化の作業を行う。
      */
     private static function normalizeUrlParams($modName, $actName = null, $params = array()) {

          // 第1引数が配列になっている場合は、第1、第2引数が省略されているものとして扱う。
          if( is_array($modName) ) {
                $params = $modName;
                $modName = null;
                $actName = null;
          }

          // 互換性の維持
          if(is_null($params)) $params = array();

          // 第1、第2引数をパラメータ配列にマージ。
          if( !is_null($modName) )     $params['module'] = $modName;
          if( !is_null($actName) )     $params['action'] = $actName;

          // _selfを処理。
          if( !empty($params['_self']) ) {
                unset($params['_self']);

                $self = Common::cutRefArray($_GET);
                unset(
                     $self['opensocial_app_id'], $self['opensocial_owner_id'], $self['opensocial_viewer_id'],
                     $self['result'], $self['sign']
                );

                $params += $self;
          }

          // oauth が指定されていない場合は $_GET から取得。
          if( !array_key_exists('oauth', $params)  &&  $_GET['oauth'] )
              $params['oauth'] = $_GET['oauth'];

          // ver が指定されていない場合は $_GET から取得。
          if( !array_key_exists('ver', $params)  &&  $_GET['ver'] )
              $params['ver'] = $_GET['ver'];

          // モジュール名が省略されている場合は現在のモジュール名を使用
          if(empty($params['module']))
                $params['module'] = Controller::getInstance()->getContext()->getModuleName();

          // アクション名が省略されている場合は "Index"。
          if(empty($params['action']))
                $params['action'] = 'Index';

          // module=User&action=Main の場合は module=Swf&action=Main に差し替える
          if($params['module'] == 'User'  &&  $params['action'] == 'Main')
                $params['module'] = 'Swf';

          // module=User&action=Help&id=other-link の場合は、モバゲのブクマをされたときに
          // 正しく処理できるように userId パラメータを追加しておく。
          if($params['module'] == 'User'  &&  $params['action'] == 'Help'  &&  isset($params['id'])  &&  $params['id'] == 'other-link'  &&  empty($params['userId']))
                $params['userId'] = isset($_REQUEST['opensocial_owner_id']) ? PlatformApi::getInternalUid($_REQUEST['opensocial_owner_id']) : '';

          // _backto を処理
          if( !empty($params['_backto']) ) {
                $params['backto'] = ViewUtil::serializeBackto();
                unset($params['_backto']);
          }

          // _nocache を処理
          if( !empty($params['_nocache']) ) {
                $params['_nc'] = time();
                unset($params['_nocache']);
          }

          //ワクプラのガラケーは全部nocache扱い
          if(!(FPhoneUtil::getCarrier() == 'pc' ) && PLATFORM_TYPE == "waku"){
                $params['_nc'] = time();
                unset($params['_nocache']);
          }

          // _sign を処理
          if( !empty($params['_sign']) ) {
                $params['sign'] = self::getUrlSign($params['module'], $params['action']);
                unset($params['_sign']);
          }

          // _token を処理
          if( !empty($params['_token']) ) {
              if($_REQUEST['opensocial_owner_id']) {
                  $userId = PlatformApi::getInternalUid($_REQUEST['opensocial_owner_id']);
                  $params['token'] = UserToken::call('getCurrentToken', $userId);
              }
              unset($params['_token']);
          }

          // module=Swf のとき、WAKU+では戻ってくるときの wakuwaku_access_token を得るために signed=1 が必要になる。
          if(PLATFORM_TYPE=='waku'  &&  $params['module'] == 'Swf')
                $params['signed'] = '1';

          // リターン。
          return $params;
     }
}
