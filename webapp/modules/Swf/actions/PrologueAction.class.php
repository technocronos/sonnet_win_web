<?php

class PrologueAction extends SwfBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;


    protected function doExecute() {

        // すでにアバターキャラが作成されているならここには来ないはず。とりあえず、メイン画面に飛ばす。
        if( Service::create('Character_Info')->getAvatarId($this->user_id) )
            Common::redirect('Swf', 'Main');

        //opensocial_owner_idが無い場合はセッション切れ
        if(!isset($_REQUEST["opensocial_owner_id"]) || $_REQUEST["opensocial_owner_id"] == ""){
            $this->setAttribute('error_name', 'セッションが切れました。<br>お手数ですが一回アプリを再起動して再度登録をしてください');
            $result = false;
            return false;
        }

        $this->replaceStrings['endUrl'] = Common::genContainerUrl('User', 'AvatarCreate', null, true);

        //追加で必要なHTMLを定義する。
        $this->html_list["AvatarCreateInput"] = MO_HTDOCS . "/html/contents/AvatarCreateInput.html";
        $this->html_list["Popup"] = MO_HTDOCS . "/html/Popup.html";
        $this->html_list["PopupConfirm"] = MO_HTDOCS . "/html/PopupConfirm.html";

        $this->setAttribute('html_list', $this->html_list);

        //この画面で使用されるcanvasリスト
        $filepath = MO_HTDOCS . "/js/canvas";
        $html_tmp = scandir($filepath);

        foreach($html_tmp as $html){
            $ext = pathinfo($filepath . "/" . $html, PATHINFO_EXTENSION);

            if($ext == "js"){
                $canvas_list[basename($html, ".js")] = $filepath . "/" . $html;
            }
        }

        $this->setAttribute('canvas_list', $canvas_list);

        //サウンド設定。効果音はweb_audio_apiを使う
        $this->use_web_audio = array(
            "se_btn",
        );

        //BGMが設定されていないならすべてbgm_quest
        $bgm = "bgm_op";

        //BGMはaudioタグを使う
        $this->use_audio_tag = array(
          $bgm,
        );

        $this->setAttribute("bgm", $bgm);
    }
}
