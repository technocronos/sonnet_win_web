<?php

/**
 * ステータス振り分けでリクエストされるアクション。
 */
class ParamUpAction extends SmfBaseAction {

    protected function doExecute($params) {

        $charaSvc = new Character_InfoService();

        // charaId が省略されていたらアバターキャラのIDにする。
        if(!$_GET['charaId'])
            $_GET['charaId'] = $charaSvc->getAvatarId($this->user_id);

        // 指定されているキャラクタを取得。
        $chara = $charaSvc->needRecord($_GET['charaId']);

        // 他人のキャラだったらエラー。
        if($chara['user_id'] != $this->user_id)
            return array('result'=>"not_me");

        // 検証して問題ないなら保存＆リダイレクト。
        // エラーがある場合は後続に流れる。
        $result = $this->validateForm();
        if($result == "ok") {
            $result = $this->saveForm();
        }

        // Flashにエラーコードを返す。
        return array('result'=>$result);
    }
    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームの入力に問題がないかどうかチェックする。
     */
    private function validateForm() {

        // 指定されているキャラクタを取得。
        $chara = Service::create('Character_Info')->needRecord($_GET['charaId']);

        // 入力されている合計値を取得。
        $total =
              $_GET['attack1'] + $_GET['attack2'] + $_GET['attack3']
            + $_GET['defence1'] + $_GET['defence2'] + $_GET['defence3']
            + $_GET['speed'] + $_GET['hp_max'];

        // 入力されていない
        if(0 == $total) {
            return "no_input";
        }

        // ステータスptより多い
        if($chara['param_seed'] < $total) {
            return "over_param";
        }

        // ここまで来ればOK
        return "ok";
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームに入力された内容で保存する。
     */
    private function saveForm() {

        $plus = array();

        if($_GET['attack1'] > 0)   $plus['attack1'] = (int)$_GET['attack1'];
        if($_GET['attack2'] > 0)   $plus['attack2'] = (int)$_GET['attack2'];
        if($_GET['attack3'] > 0)   $plus['attack3'] = (int)$_GET['attack3'];
        if($_GET['defence1'] > 0)  $plus['defence1'] = (int)$_GET['defence1'];
        if($_GET['defence2'] > 0)  $plus['defence2'] = (int)$_GET['defence2'];
        if($_GET['defence3'] > 0)  $plus['defence3'] = (int)$_GET['defence3'];
        if($_GET['speed'] > 0)     $plus['speed'] = (int)$_GET['speed'];
        if($_GET['hp_max'] > 0)    $plus['hp_max'] = (int)$_GET['hp_max'] * Character_InfoService::HP_SCALE;

        $plus['param_seed'] = -1 * (
              $_GET['attack1'] + $_GET['attack2'] + $_GET['attack3']
            + $_GET['defence1'] + $_GET['defence2'] + $_GET['defence3']
            + $_GET['speed'] + $_GET['hp_max']
        );

//Common::varLog($plus);

        Service::create('Character_Info')->plusValue($_GET['charaId'], $plus);

        return "ok";
    }
}
