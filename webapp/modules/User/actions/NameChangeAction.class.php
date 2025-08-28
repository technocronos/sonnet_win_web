<?php

class NameChangeAction extends UserBaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // GET で指定されているキャラを取得。
        $chara = Service::create('Character_Info')->needExRecord($_GET['charaId']);
        $this->setAttribute('chara', $chara);

        // 自分のキャラでなかったらエラー。
        if($chara['user_id'] != $this->user_id)
            throw new MojaviException('自分ものでないキャラの名前を変更しようとした');

        // フォームが送信されている場合はそれを処理。入力エラー以外で制御は戻ってこない。
        if($_POST)
            $this->processForm();

        // フォームが送信されていない、つまり初期表示の場合は、キャラ名に現在のキャラ名を
        // 入れておく。
        if(!$_POST)
            $_POST['name'] = Text_LogService::get($chara['name_id']);

        // 入力用のテンプレートを表示。
        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームの送信を処理する。
     */
    private function processForm() {

        // 入力正規化
        $_POST['name'] = str_replace(array("\r", "\n"), ' ', $_POST['name']);

        // 名前入力をチェック。
        $errorMes = Common::validateInput($_POST['name'], array('length'=>CHARACTER_NAME_LENGTH));
        if($errorMes != '') {
            $this->setAttribute('error_name', $errorMes);
            return;
        }

        // 名前を更新。
        Service::create('Character_Info')->updateName($_GET['charaId'], $_POST['name']);

        // 結果画面へリダイレクト
        Common::redirect('User', 'Status', array('result'=>'name'));
    }
}
