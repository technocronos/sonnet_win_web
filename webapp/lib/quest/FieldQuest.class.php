<?php

/**
 * フィールド型のクエストに対する操作を提供するクラス。
 * 直接 new するのではなく、QuestCommon::factory でインスタンスを得て使用する。
 */
class FieldQuest extends QuestCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * クエストを開始するときの処理を行う。
     *
     * @param array     キャラクタID
     * @param array     持っていくuser_item_id
     * @param mixed     特定のクエストの開始処理で渡したいパラメータがある場合はここに指定する。
     *                  このパラメータはそのまま SphereCommon::startState に渡される。
     * @return int      スフィアID
     */
    public function startField($charaId, $uitems, $extends = null) {

        $charaSvc = new Character_InfoService();
        $flagSvc = new Flag_LogService();

        // 基底の開始処理を行う。
        $this->startQuest();

        // 出撃するキャラを取得。
        $chara = $charaSvc->needRecord($charaId);

        // すでに出撃中ならエラー。
        if($chara['sally_sphere'])
            throw new MojaviException('出撃中なのに再度出撃しようとした');

        // 初めてプレイするクエストかどうかを取得。
        if( 1 == $flagSvc->getValue(Flag_LogService::TRY_COUNT, $this->userId, $this->quest['quest_id']) )
            $flagSvc->setValue(Flag_LogService::FIRST_TRY, $this->userId, $this->quest['quest_id'], $chara['level']);

        // スフィアを作成。
        $sphere = SphereCommon::start($this->quest['quest_id'], $this->userId, $uitems, $extends);
        $sphereId = $sphere->getSphereId();

        // character_info に、出撃しているスフィアを記録する。
        $charaSvc->sallyTo($charaId, $sphereId);

        // 作成したスフィアのIDをリターン。
        return $sphereId;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド
     * 第2引数は sphere_info.result の値を格納すること。
     */
    public function endQuest($success, $code) {

        // 失敗しているなら、quest_master で定められているマグナペナルティを課す。
        if($code == Sphere_InfoService::FAILURE  ||  $code == Sphere_InfoService::GIVEUP) {
            Service::create('User_Info')->plusValue($this->userId, array(
                'gold' => -1 * $this->quest['penalty_pt']
            ));
        }

        // 成功しているなら...
        if($success) {

            // 初めてかどうか調べて...
            $cleared = Service::create('Flag_Log')->getValue(
                Flag_LogService::CLEAR, $this->userId, $this->quest['quest_id']
            );

            // 初めてならアクティビティを飛ばす。
            if(!$cleared)
                PlatformApi::postActivity(sprintf('ｸｴｽﾄ"%s"をｸﾘｱ!!', $this->quest['quest_name']));
        }

        // あとは基底に任せる。
        parent::endQuest($success, $code);
    }
}
