<?php

/**
 * Userモジュール、Swfモジュールの全アクションの親クラス。
 * 以下の処理を行う。
 *     ・ユーザレコードを取得して、userInfoプロパティに格納する。
 *     ・その "user_id" 列の値をuser_idプロパティに格納する。
 */
abstract class UserBaseAction extends BaseAction {

    // ユーザ登録していなくてもアクセス可能なアクションかどうか。
    // 必要なら派生クラスでオーバーライドする。
    protected $guestAccess = false;

    //webフォントを指定する。小学生までの漢字はkokumr-normal_s_light
    protected $fontName = 'kokumr-normal';
    protected $html_list = array();
    protected $variable_list = array();

    //セーフモード設定。違う設定にする場合はオーバーライドされたい。
    protected $safe_mode = 'cover';


    //-----------------------------------------------------------------------------------------------------
    /**
     * initializeメソッドオーバーライド。
     */
    public function initialize ($context) {

        if( !parent::initialize($context) )
            return false;

        if((Common::getCarrier() == "android" && ANDROID_VER > $_GET["ver"]) || (Common::getCarrier() == "iphone" && IOS_VER > $_GET["ver"])){
            if(($_GET["action"] === "Index" && $_GET["module"] === "User") || $_GET["module"] === "Admin"){
                //print_r("version_ok");
                //exit;
            }else{
                //バージョンが違う場合、TOPへ
                Common::redirect('User', 'Index');
                exit;
            }
        }

        // 時間経過処理＆ユーザレコード取得。
        $userSvc = new User_InfoService();
        $this->userInfo = $userSvc->affectByTime($_REQUEST["opensocial_owner_id"]);

        // "user_id" 列の値をuser_idプロパティに格納する。
        $this->user_id = $this->userInfo ? $this->userInfo['user_id'] : null;

        // まだ登録されていなくて、guestAccessプロパティがOFFになっているアクションの場合は
        // エラーページに飛ばす。
        if(!$this->userInfo  &&  !$this->guestAccess)
            Common::redirect('User', 'Static', array('id'=>'NoRegisterAccess'));

        //この画面固有のHTMLが何かをテンプレートに伝える
        $ActionName = $_GET["action"];

        //tablet用HTMLがある場合はそれを使う
        if(Common::isTablet() == "tablet"){
            $page_html = MO_HTDOCS . "/html/contents/tablet/" . $ActionName . "Contents.html";
            if(!file_exists($page_html)){
                $page_html = MO_HTDOCS . "/html/contents/" . $ActionName . "Contents.html";
            }
        }else{
            $page_html = MO_HTDOCS . "/html/contents/" . $ActionName . "Contents.html";
        }

        $this->html_list[basename($page_html, ".html")] = $page_html;

        $this->setAttribute('html_list', $this->html_list);
        $this->setAttribute('ActionName', $ActionName);

        $this->setAttribute('isTablet', Common::isTablet());

        //WEBフォントの設定をテンプレートに伝える
        $this->setAttribute('fontName', $this->fontName);
        $this->setAttribute('font_eot', Common::adaptUrl(APP_WEB_ROOT . "css/fonts/" . $this->fontName . ".eot"));
        $this->setAttribute('font_woff', Common::adaptUrl(APP_WEB_ROOT . "css/fonts/" . $this->fontName . ".woff"));
        $this->setAttribute('font_woff2', Common::adaptUrl(APP_WEB_ROOT . "css/fonts/" . $this->fontName . ".woff2"));
        $this->setAttribute('font_ttf', Common::adaptUrl(APP_WEB_ROOT . "css/fonts/" . $this->fontName . ".ttf"));

        $this->setAttribute('safe_mode', $this->safe_mode);

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * userInfoプロパティのユーザレコードを読み込みなおす。
     * アクション内でユーザレコードを変更し、変更後のレコードを参照する必要があるときに呼び出す。
     */
    public function reloadUser() {

        $this->userInfo = Service::create('User_Info')->needRecord($this->user_id);
    }
}
