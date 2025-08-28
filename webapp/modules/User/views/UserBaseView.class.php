<?php

/**
 * Userモジュールの共通ビュー。
 */
class UserBaseView extends BaseView {

    //-----------------------------------------------------------------------------------------------------
    // 親のinitializeメソッドをオーバーライド。
    public function initialize ($context)
    {
        // 親の同メソッドを呼ぶ。
        if( !parent::initialize($context) )
            return false;

        // Smartyオブジェクトを取得。
        $smarty = $this->getEngine();

        //コンフィグファイルロード。
        $smarty->config_load($this->getDirectory().'/include/colors.ini');

        // ポストフィルタを設定。
        $smarty->register_outputfilter( array($this,'filterGenerally') );

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * executeをオーバーライド。
     */
    public function execute () {

        // アクションのプロパティuserInfoをビューでも使えるようにする。
        $action = $this->getContext()->getController()->getActionStack()->getLastEntry()->getActionInstance();
        $this->setAttribute('userInfo', $action->userInfo);
        $this->setAttribute('userId', $action->userInfo ? $action->userInfo['user_id'] : null);

        // キャリアを判別する。
        $carrier = Common::getCarrier();

        // 共通でセットするSmarty変数をセット。
        $this->setAttribute('appId', isset($_REQUEST["opensocial_app_id"]) ? $_REQUEST["opensocial_app_id"] : null);
        $this->setAttribute('carrier', $carrier);
        $this->setAttribute('encode', Common::getEncoding($carrier));
        switch($carrier) {
            case 'docomo':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.3) 1.0//EN" "i-xhtml_4ja_10.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'x-small');
                break;
            case 'au':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//OPENWAVE//DTD XHTML 1.0//EN" "http://www.openwave.com/DTD/xhtml-basic.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'x-small');
                break;
            case 'softbank':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//J-PHONE//DTD XHTML Basic 1.0 Plus//EN" "xhtml-basic10-plus.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'small');
                break;
            case 'pc':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'small');
                break;
        }

        // あとは親に任せる。
        return parent::execute();
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * smarty で設定しているアウトプットフィルタ。全キャリア１ソースのための調整を行う。
     */
    public function filterGenerally($output, $smarty) {

        $normalize = sprintf('<div style="font-size:%s">'
            , htmlspecialchars($smarty->get_template_vars('css_small'), ENT_QUOTES)
        );

        // テーブルセルの文字が標準サイズになってしまうのを修正する。
        $output = preg_replace('/<td(?:\s[^>]*)?>/', '$0'.$normalize, $output);
        $output = str_replace('</td>', '</div></td>', $output);

        // docomo の場合に、<input> の istyle をXHTML用のものに変換する。
        if(Common::getCarrier() == 'docomo') {
            $output = preg_replace('/(<input\b.*?)istyle="3"(.*?>)/is', '$1style="-wap-input-format:&quot;*&lt;ja:en&gt;&quot;"$2', $output);
            $output = preg_replace('/(<input\b.*?)istyle="4"(.*?>)/is', '$1style="-wap-input-format:&quot;*&lt;ja:n&gt;&quot;"$2', $output);
        }

        // PCの場合に...
        if(Common::getCarrier() == 'android' || Common::getCarrier() == 'iphone') {

            // <input type="submit"> を <button></button> に変換する。こうしないと絵文字が<img>に
            // 変換されたときに <input> タグが壊れる。
            //$output = preg_replace('/<input type="submit"(.*?)value="([^"]*)"([^>]*)>/is', '<button type="submit"$1 value="1" $3>$2</button>', $output);

            // 半角カナを全角カナに変換。ユーザが入力したテキストを再表示するときとか、これでいいのかと思う
            // 場面があるが、まぁいいや。
            $output = mb_convert_kana($output, 'KV', 'UTF-8');
        }

        return $output;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * setTemplateでテンプレートを指定するとき、いちいち拡張子を指定しなくても良いようにする。
     */
    public function setTemplate($template_file)
    {
        // キャリアを判別する。
        $carrier = Common::getCarrier();
        $this->setAttribute("template_file", $template_file);

        //ネイティブ用HTMLがある場合はそれを使う
        if(PLATFORM_TYPE == "nati"){
            $contents_file = MO_HTDOCS . "/html/contents/native/" . $template_file . ".html";
            if(!file_exists($contents_file)){
                $contents_file = MO_HTDOCS . "/html/contents/" . $template_file . ".html";
            }
        }else{
            $contents_file = MO_HTDOCS . "/html/contents/" . $template_file . ".html";
        }
        $this->setAttribute("contents_file", $contents_file);


        //超暫定対応。ヘルプの時だけそのままのテンプレート使う
        //モバグリの友達招待はajaxでコールしたHTMLでできないので仕方なく・・
        if($template_file == "HelpContent")
            parent::setTemplate($template_file);
        else if($carrier == "iphone" || $carrier == "android")
            parent::setTemplate("BaseContainer");
        else
            parent::setTemplate($template_file);

    }
}
