<?php

/**
 * ---------------------------------------------------------------------------------
 * キャラのアバター画像情報を取得する。ユーザーIDを指定するが代わりにバトルIDも指定できる。
 * @param user_id　ユーザーID
 * @param prefix　プレフィックス
 * @param battleId　バトルID
 * @param side　バトルIDがある場合、どちらの情報か　P or E
 * ---------------------------------------------------------------------------------
 */
class CharaImgApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $prefix  = $_GET["prefix"];

        if($_GET["user_id"]){
            // キャラグラ差し替え。
            $chara = Service::create('Character_Info')->needAvatar($_GET["user_id"], true);
        }else if($_GET["battleId"]){
            //バトルが超重たくなるため廃止・・

            $battleSvc = new Battle_LogService();

            // 指定されているバトル情報をロード。
            $battle = $battleSvc->needRecord($_GET['battleId']);

            // 他人のバトルの場合はエラー。
            //if($this->user_id != $battle['player_id'])
            //    throw new MojaviException('他人のバトルをロードしようとした');

            // すでに開始されているバトルの場合はエラー画面へ。ただしコンティニューの場合はOK。また、アイテム購入遷移から戻った場合もOK。
            //if($battle['true_status'] != Battle_LogService::CREATED && $battle['true_status'] != Battle_LogService::IN_CONTINUE && $_GET['firstscene'] != 'result')
                //Common::redirect('User', 'Static', array('id'=>'BattleStartError'));

            // 画像を読み込むだけだしここでは細かくエラーチェックしない。

            // バトルを扱うユーティリティクラスを取得。
            $battleUtil = BattleCommon::factory($battle);
            $params = $battleUtil->getFlashParams($battle);

            // バトルFLASHで主に使用するパラメータ配列を取得。
            $chara = &$params['side' . $_GET['side']];

        }else{
            throw new MojaviException('引数が何も指定されていない');
        }
        //キャラグラのパスを返す
        $spec = CharaImageUtil::getSpec($chara);

        //  [画像構成 ＋ 認証キー] を取得。
        $formation = explode('.', $spec);

        // 画像構成と認証キーを分離
        $key = array_pop($formation);

        // 認証キーが合っているかチェック。
//        if( $key != CharaImageUtil::getWebKey($formation) )
//            throw new MojaviException('リクエストされているパスとファイルキーが一致しません。');

        // 構成の最初の要素は race の値なので取り出しておく。
        $race = $formation[0];

        // レイヤ構造を下から順にファイル名のみで作成。
        $layers = array();

        switch($race) {
            // race:PLA の場合。この場合、配列 $formation の要素には次の部位のアイテムIDが格納されている。
            //     1:武器、2:体、3:頭、4:盾
            case 'PLA':
                $layers[$prefix . "_head1"] = sprintf('%05d', $formation[3]) . '_1.png';
                $layers[$prefix . "_body1"] = sprintf('%05d', $formation[2]) . '_1.png';
                $layers[$prefix . "_weapon"] = sprintf('%05d', $formation[1]) . '.png';
                $layers[$prefix . "_body"] = sprintf('%05d', $formation[2]) . '.png';
                $layers[$prefix . "_acs1"] = sprintf('%05d', $formation[4]) . '_1.png';
                $layers[$prefix . "_head2"] = sprintf('%05d', $formation[3]) . '_2.png';
                $layers[$prefix . "_acs2"] = sprintf('%05d', $formation[4]) . '_2.png';
                break;
            // race:MOB の場合はレイヤは一つしかない。
            case 'MOB':
                $layers[$prefix . "_head1"] = "none";
                $layers[$prefix . "_body1"] = "none";
                $layers[$prefix . "_weapon"] = "none";
                $layers[$prefix . "_body"] = "none";
                $layers[$prefix . "_acs1"] = "none";
                $layers[$prefix . "_head2"] = "none";
                $layers[$prefix . "_acs2"] = sprintf('%05d', $formation[1]) . '.png';
                break;
        }


        // race の値に応じて、パーツ画像置き場を取得。
        $partsDir = IMG_RESOURCE_DIR . '/' . $race . "_sm";

        // 構成された各レイヤを見て...
        foreach($layers as $index => $layer) {

            $url = Common::genContainerUrl(
                 'Task', 'GetSpecResourse', array('filename'=>$layer, "race"=>$race), true
            );

            // 絶対パスに変換。
            $layers[$index] = $url;

            // 絶対パスに変換。
            $p = "{$partsDir}/{$layer}";

            // ファイルが存在しないものを削除する。
            if(!file_exists($p)){
                //unset($layers[$index]);
                $layers[$index] = "none";
            }
        }

        return $layers;

    }

}
