<?php

/**
 * APIを処理するアクションの基底クラス。
 */
abstract class ApiBaseAction extends UserBaseAction {

    //-----------------------------------------------------------------------------------------------------
    /**
     * execute内で呼ばれる。派生クラスでオーバーライドして、各自の処理を記述する。
     *
     * @params array    Flash から loadVariables で送信されている GET パラメータ
     * @return array    Flashに返答する値を配列で。
     */
    abstract protected function doExecute($params);


    //-----------------------------------------------------------------------------------------------------
    /**
     * execute()をオーバーライド。基本となる処理を行う。
     */
    public function execute() {

        // 子クラス個別の処理を行って、Flashに返す値を取得。
        $resValues = $this->doExecute( $this->getTransmitParams() );

        // 作成したswfを出力。
        $this->respond($resValues);

        return View::NONE;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * FLASHから送信されている値を返す。
     * getTransmitUrl() で生成されたURLで送信されているパラメータを解析するためのもの。
     *
     * @return array    FLASHから送信されている値を列挙している配列。
     */
    protected function getTransmitParams() {

        // 基本的に $_GET をそのまま返したいわけだが、mixi とそれ以外のプラットフォームで、loadVariables
        // されたときのURLに次のような差異が生じる。
        //     mixi以外
        //         http://mgadget-sb.gree.jp/00000?guid=ON&url=http%3A%2F%2Fgree.sonnet.t-cronos.co.jp%2Findex.php%3Fmodule%3DSwf%26action%3DTransmit%26foo%3Dbar%26mixi%3D%26aaa%3Dbbb%26ddd%3Dddd
        //             ↓コンテナを経由してこうなる
        //         http://gree.sonnet.t-cronos.co.jp/index.php?module=Swf&action=Transmit&foo=bar&mixi=&aaa=bbb&ccc=ddd
        //     mixi
        //         コンテナを経由せずに直接...
        //         http://mixi.sonnet.t-cronos.co.jp/index.php?module=Swf&action=Transmit&foo=bar&mixi=%26aaa%3Dbbb%26ddd%3Dddd
        //
        // この差異を吸収するのがこのメソッドの役目。

        // とりあえず GET パラメータを取得。
        $get = Common::cutRefArray($_GET);

        // mixi パラメータの値を取り出しておく。
        $mixi = $get['mixi'];
        unset($get['mixi']);

        // プラットフォームが mixi でないなら、話は簡単。
        if(PLATFORM_TYPE != 'mixi')
            return $get;

        // パラメータ "mixi" の値を取得。上の例だと、"&aaa=bbb&ccc=ddd" となる。ただし、最初の "&" は不要。
        $mixi = substr($mixi, 1);

        // これをクエリストリングとして解析、配列に変換して、変数 $params に格納する。
        parse_str($mixi, $params);

        // それを "mixi" 以外のパラメータとマージしてリターン。
        return $get + $params;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * Jsonで返答を行う。
     *
     */
    protected function respond($resData) {
        header("Content-Type: application/json; charset=utf-8");
        header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        echo json_encode($resData);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * リダイレクトを行う。APIはajaxでリクエストされるため通常のリダイレクトがおかしくなるためこちらを使う。
     *
     */
    protected function redirect($controller, $action, $opt = array()) {
        $array["redirectURL"] = Common::genContainerUrl($controller, $action, $opt);

        header("Content-Type: application/json; charset=utf-8");
        header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        echo json_encode($array);
        exit;
    }

    // ユーティリティ static メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * loadVariables 先のURLを返す。
     * mixi だけ、クロスドメインの問題でコンテナ経由にできない問題に対処するためのメソッド。
     * loadVariables 先のアクションでは、getTransmitParams() を使ってパラメータを取得する。
     *
     * @param array     loadVariables 先のURLに含まれるGETパラメータ。
     *                  Common::genUrl の第三引数と同じだが、"action" は必ず含める必要がある。
     * @return string   loadVariables 先とするURL。
     */
    public static function getTransmitUrl($urlParams) {

        // mixiの場合はコンテナ経由せずに直接リクエストを受けるので、必要なパラメータを追加する。
        if(PLATFORM_TYPE == 'mixi') {
            $urlParams['opensocial_app_id'] = $_REQUEST['opensocial_app_id'];
            $urlParams['opensocial_owner_id'] = $_REQUEST['opensocial_owner_id'];
            $urlParams['_sign'] = true;
        }

        // コンテナを経由しない形でURLを生成。最後に "&mixi=" が付いているのは getTransmitParams() で
        // 説明している。
        $urlParams['module'] = 'Swf';
        $url = Common::genUrl($urlParams) . '&mixi=';

        // mixi 以外では、コンテナ経由に変換する。
        if(PLATFORM_TYPE != 'mixi')
            $url = Common::adaptUrl($url, true);

        // URLをリターン。
        return $url;
    }
}
