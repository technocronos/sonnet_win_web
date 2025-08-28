<?php

class ParamUpAction extends UserBaseAction {

    public function execute() {

        $charaSvc = new Character_InfoService();

        // charaId が省略されていたらアバターキャラのIDにする。
        if(!$_GET['charaId'])
            $_GET['charaId'] = $charaSvc->getAvatarId($this->user_id);

        // 指定されているキャラクタを取得。
        $chara = $charaSvc->needRecord($_GET['charaId']);

        // 他人のキャラだったらエラー。
        if($chara['user_id'] != $this->user_id)
            throw new MojaviException('他人のキャラで振り分けをしようとした');

        // フォームが送信されているなら。
        if($_POST) {

            // 検証して問題ないなら保存＆リダイレクト。
            // エラーがある場合は後続に流れる。
            if( $this->validateForm() ) {
                $this->saveForm();
                Common::redirect(array('action'=>'Status'));
            }
        }

        // キャラ情報をビュー変数に割り当て。
        $this->setAttribute('chara', $chara);

        // <select> の <option> の配列を作成。
        $options = array();
        for($i = 0 ; $i <= 9 ; $i++)
            $options[$i] = $i;
        $this->setAttribute('selectOptions', $options);

        return View::SUCCESS;
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
              $_POST['attack1'] + $_POST['attack2'] + $_POST['attack3']
            + $_POST['defence1'] + $_POST['defence2'] + $_POST['defence3']
            + $_POST['speed'] + $_POST['hp_max'];

        // 入力されていない
        if(0 == $total) {
            $this->setAttribute('error', '全部0なのだ｡ﾅﾆがしたいのだ');
            return false;
        }

        // ステータスptより多い
        if($chara['param_seed'] < $total) {
            $this->setAttribute('error', 'そんなにｽﾃｰﾀｽptないのだ｡修行しろなのだ');
            return false;
        }

        // ここまで来ればOK
        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームに入力された内容で保存する。
     */
    private function saveForm() {

        $plus = array();

        if($_POST['attack1'] > 0)   $plus['attack1'] = (int)$_POST['attack1'];
        if($_POST['attack2'] > 0)   $plus['attack2'] = (int)$_POST['attack2'];
        if($_POST['attack3'] > 0)   $plus['attack3'] = (int)$_POST['attack3'];
        if($_POST['defence1'] > 0)  $plus['defence1'] = (int)$_POST['defence1'];
        if($_POST['defence2'] > 0)  $plus['defence2'] = (int)$_POST['defence2'];
        if($_POST['defence3'] > 0)  $plus['defence3'] = (int)$_POST['defence3'];
        if($_POST['speed'] > 0)     $plus['speed'] = (int)$_POST['speed'];
        if($_POST['hp_max'] > 0)    $plus['hp_max'] = (int)$_POST['hp_max'] * Character_InfoService::HP_SCALE;

        $plus['param_seed'] = -1 * (
              $_POST['attack1'] + $_POST['attack2'] + $_POST['attack3']
            + $_POST['defence1'] + $_POST['defence2'] + $_POST['defence3']
            + $_POST['speed'] + $_POST['hp_max']
        );

        Service::create('Character_Info')->plusValue($_GET['charaId'], $plus);
    }
}
