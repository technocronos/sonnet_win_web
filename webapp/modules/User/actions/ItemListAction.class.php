<?php

/**
 * アイテム使用に関わる画面はややこしいフローになっているのでちょっと説明
 *
 * 基本的には次のような画面フローで組まれている
 *
 *     1. 使用するアイテム選択画面     ─┐
 *        (uitemIdを決定)                │
 *          ↓                           │ItemList アクション
 *     2. 対象を選択画面                 │
 *        (targetIdを決定)             ─┘
 *          ↓
 *     3. 確認画面                     ─┐
 *          ↓                           │ItemUseFire アクション
 *     4. 使用処理(画面なし)             │
 *        (CSRFキーが必要)             ─┘
 *          ↓
 *     5. 完了画面
 *
 * 対象選択をする必要がない場合、2はスキップされる。
 * 対象選択をする必要がある場合、3はスキップされる(ユーザ的に画面数が多すぎるため)。
 * 5は今はステータス画面になっている。
 */

class ItemListAction extends UserBaseAction {

    public function execute() {

        // 使うアイテムが選択されている場合。
        if($_GET['uitemId']) {

            // 使おうとしているアイテムの情報を取得。
            $uitem = Service::create('User_Item')->needRecord($_GET['uitemId']);
            $this->setAttribute('uitem', $uitem);

            // item_type によって次にどうするか決める。
            switch($uitem['item_type']) {

                // 使用対象が自動的に決まる場合はアイテム使用のアクションへ飛ばす。
                case Item_MasterService::RECV_AP:
                case Item_MasterService::RECV_MP:
                case Item_MasterService::RECV_HP:
                case Item_MasterService::INCR_PARAM:
                case Item_MasterService::DECR_PARAM:
                case Item_MasterService::INCR_EXP:
                case Item_MasterService::ATTRACT:
                case Item_MasterService::DTECH_UPPER:
                    Common::redirect(array('action'=>'ItemUseFire', 'uitemId'=>$_GET['uitemId'], 'backto'=>$_GET['backto']));

                // 耐久値の回復の場合は、このまま下に流れて使用対象アイテムの選択になる。
                case Item_MasterService::REPAIRE:
                    break;

                // その他の場合というのはありえないのだけど、とりあえず何も選択されていないように振舞う。
                default:
                    $_GET['uitemId'] = null;
            }
        }

        // 以降、所持アイテムの一覧を表示する場合。

        // 省略されているGETパラメータを補う。
        if(!$_GET['cat'])  $_GET['cat'] = 'WPN';

        // 該当カテゴリのアイテム一覧を取得。
        $condition = array('user_id'=>$this->user_id, 'category'=>$_GET['cat']);
        $list = Service::create('User_Item')->getHoldList($condition, 6, $_GET['page']);
        $this->setAttribute('list', $list);

        // アイテム名をクリックしたときの遷移先をセット。
        $this->setAttribute('nameCallback', array($this, 'makeItemName'));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザアイテムレコードを引数にとって、アイテム名を出力するHTMLを返す。
     * テンプレートから呼ばれるコールバック関数。
     */
    public function makeItemName($uitem) {

        // 使用アイテムを選択している場合...
        if($_GET['uitemId']) {

            // 今のところ耐久値回復アイテム以外はありえないので、それを前提に処理する。

            // 装備系のカテゴリを選択している場合に、アイテム使用アクションへのリンクを施す。
            if(in_array($_GET['cat'], Item_MasterService::$DURABLES)) {
                $href = Common::genContainerURL(array(
                    'action'=>'ItemUseFire', 'uitemId'=>$_GET['uitemId'], 'targetId'=>$uitem['user_item_id'],
                    '_backto'=>true, '_sign'=>true,
                ));
            }

        // 使用アイテムを選択していない場合は...
        }else {

            // アイテム廃棄のリンクを作成する。
            $url = Common::genContainerURL(array(
                'action'=>'Discard', 'uitemId'=>$uitem['user_item_id'], '_backto'=>true
            ));
            $discardHtml = ViewUtil::tag('a', array('href'=>$url,'class'=>'buttonlike label'), '⌒☆');

            // 使用可能なアイテムならアイテム名にリンクを施す。
            if($_GET['cat'] == 'ITM'  &&  in_array($uitem['item_type'], Item_MasterService::$ON_CONFIG)  &&  $uitem['free_count'] > 0) {
                $href = Common::genContainerURL(array(
                    'action'=>'ItemList', 'uitemId'=>$uitem['user_item_id'], '_backto'=>true
                ));
            }
        }

        // アイテム名のHTMLを作成。
        if($href){
            if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone")
              $html = ViewUtil::tag('a', array('href'=>$href, 'class'=>'buttonlike label'), "使う");
            else
              $html = ViewUtil::tag('a', array('href'=>$href, 'class'=>'buttonlike label'), $uitem['item_name']);
        }else{
            if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone")
              $html = "";
            else
              $html = ViewUtil::html($uitem['item_name']);
        }

        // 廃棄用のHTMLがあるなら、アイテム名に続けて出力する。
        if($discardHtml)
            $html .= ' ' . $discardHtml;

        // リターン。
        return $html;
    }
}
