<?php

require_once(MO_MPC_DIR . '/MobilePictogramConverter.php');

/**
 * 文字コードや絵文字の変換を行うフィルタ。
 * ついでにHTTPヘッダの調整も行う。
 */
class CharsetFilter extends WideFilter {

    //-----------------------------------------------------------------------------------------------------
    /**
     * preProcessをオーバーライド。
     */
    protected function preProcess() {

        // ユーザエージェントから渡された入力の調整を行う。
        self::convertInput();

        // 出力バッファリングスタート。
        ob_start();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * postProcessをオーバーライド。
     */
    protected function postProcess() {

        // Content-Type の値を取得。
        $mediaTypes = $this->getMediaTypes();

        // text/???? でない場合は変換しない。
        if($mediaTypes[0] != 'text') {
            ob_end_flush();
            return;
        }

        // HTTPヘッダを調整
        $this->reviseResponseHeader($mediaTypes);

        // 出力文字列の文字コード変換。
        $output = ob_get_clean();

        echo Common::adaptString($output);
    }


    // privateメソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザエージェントから渡された入力($_POST)をUTF8、ドコモ絵文字に統一する。
     */
    private static function convertInput() {

        $carrier = Common::getCarrier();

        // まずはdocomo絵文字に統一する。
        if($carrier == 'au'  ||  $carrier == 'softbank')
            array_walk_recursive($_POST, array(__CLASS__, 'toDocomoPictogram'), $carrier);

        // docomo絵文字ならば、SJIS => UTF-8 変換が可能。
        if(Common::getEncoding($carrier) == 'Shift_JIS')
            array_walk_recursive($_POST, array(__CLASS__, 'sjisToUtf8'));
    }

    // 以下、convertInputのヘルパ。array_walk_recursive を通じて呼ぶため、public である必要がある。
    public static function toDocomoPictogram(&$value, $key, $carrier) {

        // 元は何のキャリアかを、MPC の値で取得。
        $from = ($carrier == 'au') ? 'EZWEB' : 'SOFTBANK';

        // 入力の文字コードを MPC の値で取得。
        $encode = Common::getEncoding($carrier);
        if($encode == 'Shift_JIS') $encode = 'SJIS';

        // 変換。
        $mpc = MobilePictogramConverter::factory($value, $from, $encode);
        $value = $mpc->Convert('FOMA');
    }

    public static function sjisToUtf8(&$value) {
        $value = mb_convert_encoding($value, 'UTF-8', 'SJIS-WIN');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * レスポンスされようとしている Content-Type のメディアタイプを取得する。
     *
     * @return array    レスポンスされようとしているのが例えば text/html なら、
     *                  第0要素に "text"、第1要素に "html" を格納した配列。
     *                  大文字でセットされていても小文字に変換する。
     */
    private function getMediaTypes() {

        // 送信されようとしている(されている)ヘッダを一つずつチェック。
        $headers = headers_list();
        foreach($headers as $header) {

            // 「Content-Type:」なヘッダを見つけたら解析してリターン。
            if( preg_match('#^content-type:\s*(\S+?)/(\S+)#i', $header, $matches) ) {
                return array(strtolower($matches[1]), strtolower($matches[2]));
            }
        }

        // Content-Type のヘッダがないならtext/htmlになる。
        return array('text', 'html');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * text/???? のときの、出力時のHTTPヘッダを調整する。
     * 引数は getMediaTypes メソッドの戻り値。
     */
    private function reviseResponseHeader($mediaTypes) {

        // ユーザエージェントからキャリア種別を取得。
        $carrier = Common::getCarrier();

        // Content-Type の値を決定。docomoでtext/htmlな場合だけ変換する。
        $type = ($carrier == 'docomo' && $mediaTypes[1] == 'html') ?
                'application/xhtml+xml' : $mediaTypes[0].'/'.$mediaTypes[1];

        // Content-Type の Charset オプションの値を決定。
        $charset = Common::getEncoding($carrier);

        // レスポンスのContent-Typeをセット
        header("Content-Type: {$type}; Charset={$charset}");
    }
}
