<?php

require_once MO_BASE_DIR.'/framework/HTML_Emoji/Emoji.php';

/**
 * フィーチャーフォンにまつわる処理を収めているクラス。
 */
class FPhoneUtil {

    //-----------------------------------------------------------------------------------------------------
    /**
     * User-Agent からキャリア種別を返す。
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
                default:
                    $result = 'pc';
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * User-Agent からブラウザ種別を返す。
     *
     * 現在は、以下のいずれかを返す。
     *     feature      フィーチャーフォン
     *     android4h    Android 標準ブラウザ4以上
     *     android3l    Android 標準ブラウザ3以下
     *     safari       Safari
     *     other        その他
     */
    public static function getBrowser() {

        static $result;

        // まだ調べてないなら調べる。
        if(!$result) {

            if(strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') !== false) {

                if(strpos($_SERVER["HTTP_USER_AGENT"], 'Android') !== false) {

                    preg_match('/Android (\d+)/i', $_SERVER["HTTP_USER_AGENT"], $matches);

                    $result = ($matches[1] >= 4) ? 'android4h' : 'android3l';

                }else {
                    $result = 'safari';
                }

            }else {
                $result = (self::getCarrier() == 'pc') ? 'other' : 'feature';
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャリアの文字コードを返す。
     *
     * @param string    ユーザエージェントのキャリア種別。FPhoneUtil::getCarrier()の戻り値。
     *                  省略した場合は現在アクセス中のユーザから取得する。
     * @return string   文字コード。"Shift_JIS" か "UTF-8" のいずれか。
     */
    public static function getEncoding($carrier = null) {

        // 引数省略時は現在アクセス中のユーザから取得。
        if(!$carrier)
            $carrier = self::getCarrier();

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
     * コンテナの形態を返す。
     *
     * @return string   "feature":フィーチャーフォン, "smart":Android or iOS, "xtop":PCのいずれか。
     */
    public static function getContainer() {

        $carrier = self::getCarrier();

        if($carrier == 'pc') {
            if(stripos($_SERVER["HTTP_USER_AGENT"], 'android') === false  &&  stripos($_SERVER["HTTP_USER_AGENT"], 'iPhone') === false  &&  stripos($_SERVER["HTTP_USER_AGENT"], 'iPad') === false  &&  stripos($_SERVER["HTTP_USER_AGENT"], 'iPod') === false)
                return 'xtop';
            else
                return 'smart';
        }else {
            return 'feature';
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された文字列をユーザエージェントに合わせて文字コード変換・絵文字変換する。
     *
     * @param string    変換したい文字列。UTF8で絵文字はドコモのものであること
     * @param string    目的種別。"web":WEBページで利用  か  "swf":FLASHで利用  のどちらか
     * @return string   変換後の文字列。
     */
    public static function adaptString($string, $purpose = 'web') {

        // 文字コードを決定。FLASHで利用する場合はとにかくSJIS。それ以外はアクセス中の端末による。
        $charset = ($purpose == 'swf') ? 'Shift_JIS' : FPhoneUtil::getEncoding();

        // 絵文字変換ライブラリのインスタンスを取得。
        $util = HTML_Emoji::getInstance();
        $util->setImageUrl(APP_WEB_ROOT.'img/emoji/');

        // PCによるアクセスでswf用途の場合、絵文字をすべて全角空白に置き換える。
        // これを行わないと<img>による置き換えになるが、swfでそんなことしてもしょうがない。
        if(self::getCarrier() == 'pc'  &&  $purpose == 'swf')
            $string = self::replaceEmoji($string, '　');

        // アクセス中の端末がドコモ以外なら、変換。
        if(self::getCarrier() != 'docomo')
            $string = $util->convertCarrier($string);

        // 文字コードをSJISにするなら変換。
        if($charset == 'Shift_JIS')
            $string = $util->convertEncoding($string, 'SJIS', 'UTF-8');

        // リターン。
        return $string;
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


    //---------------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたUTF-8の文字列に、携帯の絵文字が含まれているかどうかを返す。
     */
    public static function emojiExists($string) {

        // 絵文字削除を行ってみて、オリジナルと文字列長が異なるようなら含まれていると判断できる。
        return strlen($string) != strlen(self::replaceEmoji($string, ''));
    }
}
