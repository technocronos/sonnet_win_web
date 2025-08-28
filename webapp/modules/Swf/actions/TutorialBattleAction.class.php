<?php

class TutorialBattleAction extends SwfBaseAction {

    protected function doExecute() {

        if( isset($_POST['result']) )
            $this->processResult();

        // 元になるswfファイルを指定。
        $this->swfName = 'tutobattle';

        // 開始時と終了時のセリフをセット。
        $this->replaceStrings['tutOpen0'] = "道を歩いてたゴブリン君に\nムリヤリ協力してもらったのだ";

        $this->replaceStrings['tutOpen1'] = "バトルは基本的に､ターンごとに\n双方3回ずつ攻撃して";
        $this->replaceStrings['tutOpen2'] = "進行するのだ。ターンが\n始まるとこんなふうに…";
        $this->replaceStrings['tutOpen3'] = "!END";

        $this->replaceStrings['tutTurn0'] = "攻撃カードが毎回\nランダムに決まるのだ";
        $this->replaceStrings['tutTurn1'] = "相手のカードとの相性があって\n水色の矢印で表示されてるのだ";
        $this->replaceStrings['tutTurn2'] = "炎は水に弱い､水は雷に弱い､\n雷は炎に弱いのだ";
        $this->replaceStrings['tutTurn3'] = "属性が強いと相手の攻撃\n無効にできるのだ";
        $this->replaceStrings['tutTurn4'] = "そのターンの相性が分かったら\n戦術を決めるのだ";
        $this->replaceStrings['tutTurn5'] = "戦術は最初のうちは\n「慎重」選んでればいいのだ";
        $this->replaceStrings['tutTurn6'] = "!END";

        $this->replaceStrings['tutUni0'] = "どっちかのHPが 0 になるか\n4ターン経過すると終了なのだ";
        $this->replaceStrings['tutUni1'] = "じゃ、次のターンなのだ";
        $this->replaceStrings['tutUni2'] = "!END";

        $this->replaceStrings['tutStar0'] = "…まさか向こうがユニゾン\nするとは思わなかったのだ";
        $this->replaceStrings['tutStar1'] = "今のは「ユニゾン」と言って、属性そろうと自動的になるのだ";
        $this->replaceStrings['tutStar2'] = "ユニゾンは強い上に属性同じでも\n相手の攻撃無効にできるのだ";
        $this->replaceStrings['tutStar3'] = "さらにユニゾンされた相手は\n｢慎重｣しか選べないのだ";
        $this->replaceStrings['tutStar4'] = "ところで､足元のスターに\n気づいてるのだ?";
        $this->replaceStrings['tutStar5'] = "自分の攻撃無効にされたり\n｢吸収｣に成功したりすると";
        $this->replaceStrings['tutStar6'] = "スターがたまるのだ\n｢吸収｣だと3つもたまるのだ";
        $this->replaceStrings['tutStar7'] = "スターが10個以上たまると\n｢リベンジ｣が発動するのだ";
        $this->replaceStrings['tutStar8'] = "今回は特別にスターを\n増やしてやるから、";
        $this->replaceStrings['tutStar9'] = "さっきのお返しするのだ！";
        $this->replaceStrings['tutStar10'] = "!END";

        $this->replaceStrings['tutRevP0'] = "リベンジが発動すると\nこんなふうになるのだ";
        $this->replaceStrings['tutRevP1'] = "数字キー押すと､そこに\n魔方陣が出るのだ";
        $this->replaceStrings['tutRevP2'] = "魔方陣がカードに当たると\n発射されるのだ";
        $this->replaceStrings['tutRevP3'] = "相手に迎撃される前に撃つのだ";
        $this->replaceStrings['tutRevP4'] = "んじゃ､いくのだ!";
        $this->replaceStrings['tutRevP5'] = "!END";

        $this->replaceStrings['tutClose0_0'] = "まあまあなのだ\n愛想でほめてやるのだ";
        $this->replaceStrings['tutClose0_1'] = "スゴいのだ\nいい判断力してるのだ";
        $this->replaceStrings['tutClose0_2'] = "おおー 倒してしまうとは\n大したものなのだ";

        $this->replaceStrings['tutClose1'] = "と､まぁバトルは\nこんなトコなのだ";
        $this->replaceStrings['tutClose2'] = "ワケ分かんなくても\nそのうち分かってくるのだ";
        $this->replaceStrings['tutClose3'] = "!END";

        $this->replaceStrings['NAV_SEL_TACT'] =    "数字キーで戦術えらぶのだ";
        $this->replaceStrings['NAV_REVP'] =        "リベンジなのだ!\n数字キーで発射なのだ!";
        $this->replaceStrings['NAV_REVE'] =        "相手のリベンジなのだ!\n数字キーで打ち落とすのだ!";
        $this->replaceStrings['NAV_ALD_START'] =   "ケータイの｢戻る｣を使ったのだ?\n同じバトルの再戦はできないのだ";
        $this->replaceStrings['NAV_ERROR'] =       "なんかエラーなのだ…\nケータイの｢戻る｣しかないのだ";
        $this->replaceStrings['NAV_FIN_TIMEOUT'] = "電波悪いのだ？\nもっかい押してみるのだ？";
        $this->replaceStrings['NAV_FIN_RETRY'] =   "今度はうまくいくように\n手を合わせて祈るのだ";

        $this->replaceStrings['STR_TACTICS_1'] =   "強攻";
        $this->replaceStrings['STR_TACTICS_2'] =   "慎重";
        $this->replaceStrings['STR_TACTICS_3'] =   "吸収";
        $this->replaceStrings['STR_TACTICS_4'] =   "ユニゾン";

        $this->replaceStrings['STR_TACTICS_MESSAGE'] =  "④吸収　⑤慎重　⑥強攻";

        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone")
            $this->replaceStrings['STR_PUSH_BUTTON_MESSAGE'] =  "ボタンを押してください";
        else
            $this->replaceStrings['STR_PUSH_BUTTON_MESSAGE'] =  "画面をタップしてください";

        $this->replaceStrings['STR_WAIT_PLEASE'] = "お待ちください...";

        if( empty($_GET['help']) ) {
            $this->replaceStrings['navSerif_end'] = "さて､いいかげんじじぃも\nめし食い終わってるのだ";
        }else {
            $this->replaceStrings['navSerif_end'] = "あとはやりながら\n覚えたほうだいいのだ";
        }

        //ネイティブの場合は上を詰め詰めにしないで35px空ける
        if(PLATFORM_TYPE == "nati")
            $this->replaceStrings['main_position'] = 35;
        else
            $this->replaceStrings['main_position'] = 0;

        // 置換する値を連想配列で設定
        $this->replaceStrings['urlOnEnd'] = Common::genContainerUrl(
            'Swf', 'TutorialBattle', array('_self'=>true), true
        );

        //バトルのアニメーションが動かないのでfalse
        $this->PexPartialDraw = 'false';

        //バトルはtrue
        $this->use_web_audio_api = 'true';
        $this->swf_loadmsg_pass = "/main";

        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);

        $this->replaceStrings['nameP'] =      Text_LogService::get($avatar['name_id']);
        $this->replaceStrings['nameE'] =      "ゴブリン";

        // バトル背景のデータ取得。
        $this->img_list["battle_bg"] = "img/battleBg/forest.png";

        //先に読み込んでおく画像を定義する
        $this->img_list["bg_none"] = "img/parts/sp/preload/bg_none.png";
        $this->setAttribute('img_list', $this->img_list);

        //使うファイル名を指定
        $this->use_web_audio = array(
            "se_btn",
            "se_hit",
            "se_damage",
            "se_retire",
            "se_battle_end",
            "se_gachawhiteout",
            "se_gachashutter",
            "se_beam",
            "se_getprice",
            "se_airassaultdown",
            "se_combo",
            "se_kyoka",
            'se_hover',
        );

        //BGMはaudioタグを使う
        $this->use_audio_tag = array(
            "bgm_battle",
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * チュートリアルバトルが終わったときの処理を行う。
     */
    private function processResult() {

        // 全体チュートリアルの一環として実行されていたなら。
        if( empty($_GET['help']) ) {

            // チュートリアルを次段階へ。
            Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_BATTLE);

            // リダイレクト。
            Common::redirect('Swf', 'Tutorial');

        // ヘルプから実行されていたならヘルプ一覧へ戻る。
        }else {
            Common::redirect('Swf', 'Main');
        }
    }
}
