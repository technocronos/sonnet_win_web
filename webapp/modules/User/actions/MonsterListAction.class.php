<?php

class MonsterListAction extends UserBaseAction {

    public function execute() {

        $umonsSvc = new User_MonsterService();

        //スマホはなるぺくページングしない。100件でもいいが負荷を見て・・。
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone")
          $page_const = 30;
        else
          $page_const = 10;

        // リストを取得。
        if($_GET['field'] == 'terminate')
            $list  = $umonsSvc->getTerminateList($this->user_id, $page_const, $_GET['page']);
        else
            $list  = $umonsSvc->getCollectionList(array('user_id'=>$this->user_id) + $_GET, $page_const, $_GET['page']);

        $this->setAttribute('list', $list);

        // タイトルを決定。
        $this->setAttribute('title', $this->decideTitle());

        // 飾りテキストを決定
        $this->setAttribute('flavor', $this->decideFlavorText());

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * タイトルを返す。
     */
    private function decideTitle() {

        switch($_GET['field']) {

            case 'category':
                return Monster_MasterService::$CATEGORIES[ $_GET['value'] ];

            case 'rare_level':
                return Monster_MasterService::$RARE_LEVELS[ $_GET['value'] ];

            case 'appearance':
                return Monster_MasterService::getAppearanceText( $_GET['value'] );

            case 'terminate':
                return '倒したﾓﾝｽﾀｰ';
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 飾りテキストを返す。
     */
    private function decideFlavorText() {

        switch($_GET['field']) {

            case 'category':
                $texts = array(
                     1 => '古い植物に精霊が憑依して生命を持ったﾓﾝｽﾀｰ｡',
                     2 => '水の中や水辺に棲むﾓﾝｽﾀｰ｡水属性を持つものが多い｡',
                     3 => '生物の死体がよみがえったﾓﾝｽﾀｰ｡強力なものが多いが火に弱い｡',
                     4 => '巨大化した虫がﾓﾝｽﾀｰ化したもの｡',
                     5 => '自然界に棲む猛獣｡強力な爪と牙を持つ｡',
                     6 => '修行によりﾓﾝｽﾀｰと同等の力を持った人間｡',
                     7 => '強力な力を持つ亜人｡100年戦争で絶滅しかかっているが個々の力は人間より強力｡',
                     8 => 'ﾏﾙﾃｨｰﾆが作り出した感情を持たないﾏｼｰﾝ｡',
                     9 => '意思はあるけど実体のないエネルギー体｡',
                    10 => '誰もが知っている伝説の生物｡地域によっては神と崇められている者もいる｡',
                );
                break;

            case 'rare_level':
                $texts = array(
                     1 => 'どこにでも出現するﾓﾝｽﾀｰ｡見つけるのは簡単だが入手できるかは実力次第｡',
                     2 => 'ｽﾄｰﾘｰ内ではﾎﾞｽｷｬﾗ､ﾓﾝｽﾀｰの洞窟内ではたまに現れるﾓﾝｽﾀｰ｡強力なﾓﾝｽﾀｰが多く､見つけるのも困難なので十分な準備が必要｡',
                     3 => '見つけるのも倒すのも非常に困難なﾓﾝｽﾀｰ｡特殊能力を持つものが多く､通常はまず発見できない｡',
                );
                break;

            case 'appearance':
                $texts = array(
                     0 => '誰も一番奥を見たことがないという果てしない洞窟｡ﾓﾝｽﾀｰはここから生まれてくるとか…',
                     1 => '主人公の生まれ育った故郷の島｡牧歌的であまり強力な敵はいない平和なところ｡',
                );
                break;

            case 'terminate':
                return 'いままで倒してきたﾓﾝｽﾀｰたち｡ｺﾝﾌﾟしたら立派なﾓﾝｽﾀｰﾊﾝﾀｰだ｡';
        }

        return is_null($texts) ? '' : $texts[ $_GET['value'] ];
    }
}
