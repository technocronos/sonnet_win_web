<?php

class AvatarCreateAction extends UserBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;

    // オマケアイテムのIDを決定。
    const OMAKE_ID = 13002;


    //-----------------------------------------------------------------------------------------------------
    public function execute() {
        $api_list = array();
        //APIリスト
        $api_list = array(
            'apiOnHomeInherit' => Common::genContainerUrl('Swf', 'HomeInheritApi', array(), true), //ショップリストのURL
        );
        $this->setAttribute('api_list', $api_list);
        //メインページURL
        $this->setAttribute('urlOnMain', Common::genContainerUrl('Swf', 'Main', array(), true));

        // 結果画面を表示することになっているならそちらへ。
        if(!empty($_GET['result'])) {

            // オマケアイテムの情報を取得。
            $this->setAttribute('omake', Service::create('Item_Master')->needRecord(self::OMAKE_ID));

            // 結果画面を表示。
            return 'Finish';
        }

        // すでにアバターキャラが作成されているならここには来ないはず。とりあえず、メイン画面に飛ばす。
        if( Service::create('Character_Info')->getAvatarId($this->user_id) )
            Common::redirect('Swf', 'Main');

        // フォームが送信されている場合。
        if($_POST) {

            // 「ツっこみ」ボタンの場合
            if( !empty($_POST['tukkomi']) ) {

                // オマケフラグをONにして、入力画面の再表示を行う。
                $_POST['appology'] = '1';

            // それ以外のフォーム送信の場合。
            }else {

                // 入力検証。問題ないなら...
                if( $this->validateForm() ) {

                    // 保存処理
                    $this->saveForm();

                    // 結果ページへリダイレクト。
                    Common::redirect(array(
                        '_self' => true,
                        'result' => empty($_POST['appology']) ? '1' : 'omake',
                    ));

                // フォームの入力に問題ある場合。ビュー用のフラグをONにして、後続の処理へ。
                }else {
                    $this->setAttribute('errorExists', true);
                }
            }
        }

        // フォームが送信されていない、つまり初期表示の場合は、キャラ名にユーザのニックネームを
        // 入れておく。
        if(!$_POST) {
            $nickname = PlatformApi::queryNickname($_REQUEST['opensocial_owner_id']);
            $_POST['name'] = mb_strimwidth($nickname, 0, CHARACTER_NAME_LENGTH, '', 'UTF-8');
        }

        // 入力用のテンプレートを表示。
        return 'Input';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームの入力内容を確認する。
     */
    private function validateForm() {

        // 戻り値初期化。
        $result = true;

        // 入力正規化
        $_POST['name'] = str_replace(array("\r", "\n"), ' ', $_POST['name']);

        // 名前入力をチェック。
        $errorMes = Common::validateInput($_POST['name'], array('length'=>CHARACTER_NAME_LENGTH));
        if($errorMes != '') {
            $this->setAttribute('error_name', $errorMes);
            $result = false;
        }

        if(!isset($_REQUEST["opensocial_owner_id"]) || $_REQUEST["opensocial_owner_id"] == ""){
            $this->setAttribute('error_name', 'セッションが切れました。<br>お手数ですが一回アプリを再起動して再度登録をしてください');
            $result = false;
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームの入力内容を保存する。
     */
    private function saveForm() {

        // まだユーザ登録していないなら、ここで登録。
        if(!$this->userInfo)
            $this->userInfo = AppUtil::registerUser($_REQUEST['opensocial_owner_id']);

        // キャラクターレコードを作成。
        $chara = new Character_InfoService();
        $chara->insertRecord(array(
            'user_id' => $this->userInfo['user_id'],
            'name' => $_POST['name'],
            'entry' => 'AVT',
            'race' => 'PLA',
        ));

        // お詫びフラグがONになっているならアイテム付与。
        if($_POST['appology'])
            Service::create('User_Item')->gainItem($this->userInfo['user_id'], self::OMAKE_ID);

        // アクティビティ送信
        PlatformApi::postActivity(ACTIVITY_GAME_START);
    }
}
