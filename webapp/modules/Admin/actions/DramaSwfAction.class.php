<?php

class DramaSwfAction extends DramaBaseAction {

    // 管理ページからのチェック用なので、ユーザ登録ナシでアクセスできるようにする。
    protected $guestAccess = true;


    protected function onExecute() {

        // 再生する寸劇のIDを設定。
        $this->dramaId = (int)$_GET['id'];
        $this->lang = (int)$_GET['lang'];

        // 戻り先の設定。
        $this->endTo = Common::genUrl(array('_self'=>true), null, null, true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * flowCompile() をオーバーライド
     */
    protected function flowCompile($flow) {

        $transes = array();
        foreach($_GET['holder'] as $index => $name)
            $transes["%{$name}%"] = $_GET['value'][$index];

        $flow = "!PAGE\n" . strtr($flow, $transes);

        return parent::flowCompile($flow);
    }
}
