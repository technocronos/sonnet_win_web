<?php

/**
 * アイテムを取得したあとに表示されるページ。
 * ショップでのアイテム購入、ガチャでのアイテム取得など。
 *
 * GETパラメータ)
 *      backto      「戻る」リンクのURL。ViewUtil::serializeBackto() の戻り値
 *      uitemId     購入アイテムのユーザ所持レコード
 *      nextUrl    「使用する」「装備する」のリンク先を通常とは異なるものにしたい場合はそのURLを
 *                  ViewUtil::serializeBackto() の戻り値で指定する。
 *      coin        課金で買った場合は1を指定する。
 *      dataId      上記パラメータをミニセッションから取得させたい場合に、セッションID
 */
class ItemGetAction extends UserBaseAction {

    public function execute() {

        // GET変数 dataId が指定されている場合は、そこからパラメータを取り出す。
        if( !empty($_GET['dataId']) ) {
            $data = Service::create('Mini_Session')->getData($_GET['dataId']);

            $_GET['backto'] =  isset($data['backto']) ? $data['backto'] : null;
            $_GET['nextUrl'] = isset($data['nextUrl']) ? $data['nextUrl'] : null;
            $_GET['coin'] =    isset($data['coin']) ? $data['coin'] : null;

            if(is_array($data['uitemId'])){
                $_GET['uitemId'] = isset($data['uitemId']) ? $data['uitemId'][0] : null;
            }else{
                $_GET['uitemId'] = isset($data['uitemId']) ? $data['uitemId'] : null;
            }
        }

        // 課金で購入している場合に決済完了しているかどうかのチェック。
        if( !PlatformApi::validatePayment() )
            Controller::getInstance()->redirect( $_GET['backto'] );

        // 購入したアイテムの user_item レコードを取得。
        $svc = new User_ItemService();
        $uitem = $svc->getRecord($_GET['uitemId']);
        $this->setAttribute('item', $uitem);

        // 取得できない場合はエラーページに飛ばす。
        if(!$uitem)
            Common::redirect('User', 'Static', array('id'=>'Timeout'));

        // 他人のアイテムだったらエラー。
        if($uitem['user_id'] != $this->user_id)
            throw new MojaviException('他人のアイテムで取得ページを表示しようとした');

        //バトルIDがある場合、即時使用してバトルにリダイレクト
        if($data["battleId"]){
            $result = $this->ContinueBattle($data);
            if($result == "ok")
                Common::redirect( array("module"=>"Swf", "action"=>"Battle","battleId"=>$data["battleId"]) );
        }

        //firstsceneがある場合、MAINにリダイレクト
        if($data["firstscene"]){
            Common::redirect( array("module"=>"Swf", "action"=>"Main", "dataId" => $_GET['dataId']) );
        }

        // 次に遷移すべきURLを決定する。GETパラメータ "nextUrl" が指定されている場合。
        if($_GET['nextUrl']) {
            $nextUrl = Common::genContainerUrl( ViewUtil::unserializeBackto($_GET['nextUrl']) );

        // 取得アイテムが装備品の場合。
        }else if( Item_MasterService::isDurable($uitem['category']) ) {

            // アバターキャラのIDを取得。
            $charaId = Service::create('Character_Info')->needAvatarId($this->user_id);

            // 装備箇所を取得。
            $mountIds = array('WPN'=>Mount_MasterService::PLAYER_WEAPON, 'BOD'=>Mount_MasterService::PLAYER_BODY, 'HED'=>Mount_MasterService::PLAYER_HEAD, 'ACS'=>Mount_MasterService::PLAYER_SHIELD);

            // URLを決定
            $nextUrl = Common::genContainerUrl(array(
                'action'=>'EquipChange', 'charaId'=>$charaId, 'mountId'=>$mountIds[$uitem['category']], 'backto'=>$_GET['backto']
            ));

            $set_data =  Service::create('Set_Master')->getRecord($uitem["set_id"]);
            $this->setAttribute('set_data', $set_data);
            $this->setAttribute('is_equip', true);

        // 取得アイテムが使用可能なアイテムの場合。
        }else if( in_array($uitem['item_type'], Item_MasterService::$ON_CONFIG) ) {
            $nextUrl = Common::genContainerUrl(array(
                'action'=>'ItemList', 'uitemId'=>$uitem['user_item_id'], 'backto'=>$_GET['backto']
            ));
        }

        // ビューにセット。
        $this->setAttribute('nextUrl', $nextUrl);
        $this->setAttribute('backto', $_GET['backto']);

        return View::SUCCESS;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたバトルの状態が開始されるに適当かどうかチェックして開始処理を行う。
     *
     * @params array    Flash から POSTとGET で送信されている パラメータ
     */
    private function ContinueBattle($params) {

        // 指定されているバトル情報をロード。見つからないならエラーリターン。
        $battle = Service::create('Battle_Log')->getRecord($params['battleId']);
        if(!$battle) {
            $this->log("BattleOpenAction: 指定されているバトル情報が見つからない\n_GET = " . print_r($params, true));
            return 'error';
        }

        // バリデーションコードをチェック。
        if($battle['validation_code'] != $params['code']) {
            $this->log("BattleOpenAction: バリデーションコードが不正\n_GET = " . print_r($params, true));
            return 'error';
        }

        // 他人のバトルの場合はエラー。
        if($this->user_id != $battle['player_id']) {
            $this->log("他人のバトルで開始通知をしようとした\n_GET = " . print_r($params, true));
            return 'error';
        }

        //エラーが無ければ以下継続

        // バトル種別に応じたバトルユーティリティを取得。
        $battleUtil = BattleCommon::factory($battle);

        // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
        $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
        $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

        //プレイヤーのhpを書き換える
        $sideP["hp"] = (int)$sideP['hp_max']; //全回復

        //自分が受けたコンティニュー以前のダメージは無かったことにする。サマリーも引き継がない。

        $sideP['summary']['tact0'] = 0;
        $sideP['summary']['tact1'] = 0;
        $sideP['summary']['tact2'] = 0;
        $sideP['summary']['tact3'] = 0;
        $sideP['summary']['nattCnt'] = 0;
        $sideP['summary']['nhitCnt'] = 0;
        //$sideP['summary']['ndam'] = (int)$param['ndamP'];
        $sideP['summary']['revCnt'] = 0;
        $sideP['summary']['rattCnt'] = 0;
        $sideP['summary']['rhitCnt'] = 0;
        //$sideP['summary']['rdam'] = (int)$param['rdamP'];
        //$sideP['summary']['odam'] = (int)$param['odamP'];
        $sideE['summary']['tact0']= 0;
        $sideE['summary']['tact1']= 0;
        $sideE['summary']['tact2']= 0;
        $sideE['summary']['tact3']= 0;
        $sideE['summary']['nattCnt'] = 0;
        $sideE['summary']['nhitCnt'] = 0;
        $sideE['summary']['ndam'] = 0;
        $sideE['summary']['revCnt']= 0;
        $sideE['summary']['rattCnt'] = 0;
        $sideE['summary']['rhitCnt'] = 0;
        $sideE['summary']['rdam'] = 0;
        $sideE['summary']['odam'] = 0;

        //食らったダメージ以外のサマリすべて引き継ぎたいならこれだけ初期化
        //$sideE['summary']['ndam'] = 0;
        //$sideE['summary']['rdam'] = 0;
        //$sideE['summary']['odam'] = 0;

        $battle['ready_detail']["continue_count"]++;//コンティニュー回数を増やす

        // バトル種別に応じた開始処理。
        $errorCode = $battleUtil->continueBattle($battle);
        if($errorCode)
            return $errorCode;


        // ここまで来ればOK。
        return 'ok';
    }
}
