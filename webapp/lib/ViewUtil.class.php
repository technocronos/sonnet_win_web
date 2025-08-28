<?php

/**
 * 主にビュー(テンプレート)でのユーティリティを収めるクラス。
 */
class ViewUtil {

    // 各画像のサイズとalt。
    // 必須ではないが記述しておいたほうが、width, height属性を出力できるので
    // 見た目上の読み込みが早い。特にdocomo。
    private static $IMAGE_INFO = array(
        'parts' => array(
            'titlelogo.jpg' => array(240, 160, SITE_NAME),
//            'titlelogo.gif' => array(240, 285, SITE_NAME),
            'header1.gif' => array(240, 30),
            'page_bottom.gif' => array(240, 25),
            'fukidashi_upper.gif' => array(240, 9, '┌────────┐'),
            'fukidashi_lower.gif' => array(240, 19, '└────────┘'),
            'hr.gif' => array(240, 12, '------------------'),
            'komon.gif' => array(15, 15),
            'pHeaderBottom.gif' => array(180, 6),
            'syoukaku.gif' => array(220, 80, 'GRADE UP'),
            'koukaku.gif' => array(220, 80, 'GRADE DOWN'),
            'mixipoint_small.gif' => array(16, 16, 'mixiﾎﾟｲﾝﾄ'),
            'levelup.gif' => array(220, 40, 'LEVEL UP'),
            'win.gif' => array(220, 40, 'WIN'),
            'lose.gif' => array(220, 40, 'LOSE'),
            'navi_mini.gif' => array(50, 32, 'ﾓｼﾞｮ'),
            'navi2_mini.gif' => array(50, 48, 'ｼｼｮｰ'),
            'navi2_mini2.gif' => array(50, 32, 'ｼｼｮｰ'),
            'navigator.gif' => array(80, 80, 'ﾓｼﾞｮ'),
            'picon_att1.gif' => array(15, 15, '功(炎'),
            'picon_att2.gif' => array(15, 15, '功(水'),
            'picon_att3.gif' => array(15, 15, '功(雷'),
            'picon_def1.gif' => array(15, 15, '守(炎'),
            'picon_def2.gif' => array(15, 15, '守(水'),
            'picon_def3.gif' => array(15, 15, '守(雷'),
            'picon_defX.gif' => array(15, 15, '守(特'),
            'picon_hp.gif' => array(15, 15, 'HP'),
            'picon_speed.gif' => array(15, 15, '速'),
            'versus.gif' => array(25, 35, 'VS'),
            'comment.gif' => array(32, 32, 'ｺﾒﾝﾄ'),
            'up.gif' => array(20, 10, 'UP'),
            'information.gif' => array(32, 32, ''),
            'jikai_yokoku.jpg' => array(240, 160, '次回予告'),
            'gacha_shop.gif' => array(68, 16, 'ｽﾍﾟｼｬﾙｶﾞﾁｬｼｮｯﾌﾟ'),
            'try_gacha.gif' => array(100, 30, 'ｺｲﾝでﾄﾗｲ'),
            'try_ticket.gif' => array(100, 30, 'ﾌﾘｰﾁｹｯﾄでﾄﾗｲ'),
            'try_ticket_d.gif' => array(100, 30, 'ﾌﾘｰﾁｹｯﾄでﾄﾗｲ'),
            'tutorial_next.gif' => array(120, 30, '次へ進む'),
            'start.gif' => array(150, 33, 'ゲーム開始'),
            'b_q_99999.gif' => array(207, 53, 'モンスターの洞窟'),
            'b_q_98001.gif' => array(207, 53, '期間限定クエスト'),
            'b_q_battle_event.gif' => array(207, 53, 'バトルイベント'),
            'menu_top.png' => array(48, 48, 'TOP'),
            'menu_quest.png' => array(48, 48, 'クエスト'),
            'menu_shop.png' => array(48, 48, 'ショップ'),
            'menu_status.png' => array(48, 48, 'ステータス'),
            'menu_main.png' => array(48, 48, 'メイン'),
            'b_startdush.gif' => array(207, 53, 'スタートダッシュ'),
            'caption_eventquest.png' => array(150, 23, 'イベントクエスト'),
            'caption_weekquest.png' => array(150, 23, '曜日クエスト'),
            'caption_storyquest.png' => array(150, 23, 'ストーリークエスト'),
        ),
        'notice' => array(207, 53),
        'parts/gauge/normal' => array(90, 12),
        'item' => array(48, 48),
        'gacha' => array(207, 53),
        'monster' => array(100, 100),
        'dictionary' => array(120, 175),
        'moveMap' => array(240, 240),
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 現在のURLを元に、backtoパラメータの値を作成する。
     *
     * @param array     現在のGET変数で追加・変更したいパラメータがある場合は、ここで指定する。
     */
    public static function serializeBackto($params = array()) {

        // 現在のGETパラメータを取得。
        // ただし、戻るときにパラメータに含めるべきでないキーは削除する。
        $get = Common::cutRefArray($_GET);
        unset($get['opensocial_app_id'], $get['opensocial_owner_id'], $get['opensocial_viewer_id']);
        unset($get['result'], $get['sign']);

        // 指定されたパラメータにマージ。
        $params += $get;

        // name=value の形式に直す。
        $result = self::serializeParams($params);

        // 300文字を超えるようなら、古い履歴から削除して300以下になるように試行。
        while(strlen($result) > 300) {
            if( !self::cutOldestHistory($result) )
                break;
        }

        // リターン。
        return $result;
    }

    /**
     * serializeBackto のヘルパ。
     * 指定されたbackto値に含まれている最も古いbacktoを削除する。
     * 指定されたbacktoにさらにbacktoが含まれていたならtrue、もうないならfalseを返す。
     */
    public static function cutOldestHistory(&$string) {

        parse_str($string, $params);

        if( !isset($params['backto']) )
            return false;

        if( !self::cutOldestHistory($params['backto']) )
            unset($params['backto']);

        $string = self::serializeParams($params);
        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * serializeBackto() と同じように指定されパラメータを文字列に変換する。
     * serializeBackto() は現在のURLを元に「戻る」用の値を作成するのが主眼だが、このメソッドは
     * 指定されたパラメータのみを変換する。
     *
     * @param array     復元可能な文字列としたいパラメータ配列
     */
    public static function serializeParams($params) {

        // name=value の形式に直す。
        return http_build_query($params);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * serializeBacktoの戻り値から、戻り先URLを示す連想配列を復元する。
     *
     * @param string    serializeBacktoの戻り値。省略時はGETパラメータ "backto" から取得する。
     * @return string   戻り先URLを示す連想配列
     */
    public static function unserializeBackto($backtoValue = false) {

        // 省略時はGETパラメータ "backto" から。
        if($backtoValue === false)
            $backtoValue = $_GET['backto'];

        // 有効な値があるなら復元。ないならメインページ。
        if($backtoValue)
            parse_str($backtoValue, $result);
        else
            $result = array('module'=>'User', 'action'=>'Main');

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * serializeBacktoの戻り値を元に、戻り先URLを生成する。
     *
     * @param string    serializeBacktoの戻り値
     * @return string   戻り先URL。
     */
    public static function getBacktoUrl($backtoValue) {

        return Common::genContainerURL( self::unserializeBackto($backtoValue) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * パラメータで指定された画像を出力する<img>タグを返す。
     * このクラスの定数 $IMAGE_INFO で設定されていれば、width, height, alt も出力する。
     *
     * @param array     以下のキーを含む連想配列。
     *                      file         画像のファイル名。
     *                      cat          画像の種類。html/img からのディレクトリパス。省略時は "parts"。
     *                      float        画像の回り込み指定。"left", "right" のいずれか。
     * @param return    <img> を含むHTML
     */
    public static function getImageTag($params) {

        // 省略されているパラメータを補う。
        $params += array('cat'=>'parts');

        // 画像のパスを取得。
        $path = sprintf('img/%s/%s', $params['cat'], $params['file']);


        // 管理モジュール以外でPC版の場合、PC版用の画像があるかチェックする。
        if( Controller::getInstance()->getContext()->getModuleName() != 'Admin'  &&  (Common::getCarrier() == 'android' ||  Common::getCarrier() == 'iphone')) {

            // 例えば、画像のファイル名が "dir/xxx.gif" なら、"dir/xxx.pc.gif" か "dir.pc/xxx.gif" が
            // PC版用になる。
            $pcPath1 = preg_replace('#\.[^\.]+$#', '.pc$0', $path);
            $pcPath2 = preg_replace('#[^/]+(?=/[^/]+$)#', '$0.pc', $path);


            // PC版用が存在するならそちらを使う。このとき、フィッティング指定は無視する。
            if( file_exists(MO_HTDOCS.'/'.$pcPath1) ) {
                $path = $pcPath1;
            }
            if( $path != $pcPath2  &&  file_exists(MO_HTDOCS.'/'.$pcPath2) ) {
                $path = $pcPath2;
            }
        }

        // 画像の更新時刻を取得。ファイルがなくてもエラーにならないようにする。
        $mtime = (int)@filemtime(MO_HTDOCS.'/'.$path);

        // 画像のURLを決定。更新時刻を含めて、キャッシュをうまく更新できるようにする。
        $src = APP_WEB_ROOT . $path . '?' . $mtime;
        $src = htmlspecialchars($src, ENT_QUOTES);

        // floatパラメータを処理するHTMLの属性を取得。
        if(!empty($params['float']))
            $floatAttr = sprintf('style="float:%s" align="%s"', $params['float'], $params['float']);
        else
            $floatAttr = '';

        // 指定された画像に対してセットされている情報を取得。
        $info = self::$IMAGE_INFO[ $params['cat'] ];

        if($info  &&  !array_key_exists(0, $info))
            $info = $info[ $params['file'] ];

        // 情報があったなら、width, height, alt 属性の出力の仕方を決める。
        if($info) {

			if(Common::getCarrier() == 'android' ||  Common::getCarrier() == 'iphone'){
				$info[0] = $info[0] / SCREEN_WIDTH_MOBILE * SCREEN_WIDTH_PC;
				$info[1] = $info[1] / SCREEN_WIDTH_MOBILE * SCREEN_WIDTH_PC;
			}
				
            $sizeAttr = sprintf('width="%s" height="%s"', $info[0], $info[1]);
            $alt = isset($info[2]) ? $info[2] : '';
        }else {
            $sizeAttr = '';
            $alt = '';
        }

        // 出力。
        return sprintf('<img src="%s" alt="%s" %s %s />', $src, $alt, $floatAttr, $sizeAttr);
    }

	//ただsrcだけが欲しい時用
    public static function getImageURL($params) {
        // 省略されているパラメータを補う。
        $params += array('cat'=>'parts');

        // 画像のパスを取得。
        $path = sprintf('img/%s/%s', $params['cat'], $params['file']);


        // 管理モジュール以外でPC版の場合、PC版用の画像があるかチェックする。
        if( Controller::getInstance()->getContext()->getModuleName() != 'Admin'  &&  (Common::getCarrier() == 'android' ||  Common::getCarrier() == 'iphone')) {

            // 例えば、画像のファイル名が "dir/xxx.gif" なら、"dir/xxx.pc.gif" か "dir.pc/xxx.gif" が
            // PC版用になる。
            $pcPath1 = preg_replace('#\.[^\.]+$#', '.pc$0', $path);
            $pcPath2 = preg_replace('#[^/]+(?=/[^/]+$)#', '$0.pc', $path);


            // PC版用が存在するならそちらを使う。このとき、フィッティング指定は無視する。
            if( file_exists(MO_HTDOCS.'/'.$pcPath1) ) {
                $path = $pcPath1;
            }
            if( $path != $pcPath2  &&  file_exists(MO_HTDOCS.'/'.$pcPath2) ) {
                $path = $pcPath2;
            }
        }

        // 画像の更新時刻を取得。ファイルがなくてもエラーにならないようにする。
        $mtime = (int)@filemtime(MO_HTDOCS.'/'.$path);

        // 画像のURLを決定。更新時刻を含めて、キャッシュをうまく更新できるようにする。
        $src = APP_WEB_ROOT . $path . '?' . $mtime;
        $src = htmlspecialchars($src, ENT_QUOTES);

		return $src;
	}

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された情報でHTMLタグを作成する。
     *
     * @param string    タグ名。"a" とか "hr" とか。
     * @param array     属性名をキー、値を値とする配列。
     * @param string    タグではさみたい内容がある場合は指定する。この場合、終了タグも一緒に返される。
     * @return string   HTML
     */
    public static function tag($tagName, $attributes = array(), $content = null) {

        $tag = "<{$tagName} " . self::convertAttributes($attributes) . ">";

        if( is_null($content) )
            return $tag;
        else
            return $tag . self::html($content) . "</{$tagName}>";
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された文字列をHTMLエンコードして返す。
     *
     * @param string    HTMLエンコードしたい文字列。
     * @return string   HTMLエンコードした文字列。
     */
    public static function html($string) {

        return htmlspecialchars($string, ENT_QUOTES);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された配列をHTMLタグの属性として展開する。
     *
     * @param array     属性として展開したい配列。
     * @return string   属性として展開した文字列。
     */
    public static function convertAttributes($attributes) {

        $result = '';

        foreach($attributes as $name => $value)
            $result .= sprintf(' %s="%s"', self::html($name), self::html($value));

        return $result;
    }


    //---------------------------------------------------------------------------------------------------------
    /**
     * 携帯の初期入力モードを指定するためのHTML属性記述を返す。
     *
     * @param string    指定したい初期入力モード。次の値のうちいずれか。
     *                      alphanum    半角英数
     *                      number      半角数字
     */
    function getImeAttr($imeMode) {

        $outputTable = array(
            'docomo' => array(
                'alphanum' => 'style="-wap-input-format:&quot;*&lt;ja:en&gt;&quot;"',
                'number' =>   'style="-wap-input-format:&quot;*&lt;ja:n&gt;&quot;"',
            ),
            'au' => array(
                'alphanum' => 'istyle="3"',
                'number' =>   'istyle="4"',
            ),
            'softbank' => array(
                'alphanum' => 'istyle="3"',
                'number' =>   'istyle="4"',
            ),
        );

        $carrier = Common::getCarrier();

        return isset($output_table[$carrier][$imeMode]) ? $output_table[$carrier][$imeMode] : '';
    }
}
