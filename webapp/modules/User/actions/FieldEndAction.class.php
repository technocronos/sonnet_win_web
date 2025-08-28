<?php

class FieldEndAction extends UserBaseAction {

    public function execute() {

        $questSvc = Service::create('Quest_Master');

        // スフィア情報をロード。
        $record = Service::create('Sphere_Info')->getRecord($_GET['sphereId']);
        $this->setAttribute('record', $record);
        if(!$record)
            Common::redirect('User', 'QuestList');

        // 他人のスフィアだったらエラー。
        if($record['user_id'] != $this->user_id)
            throw new MojaviException('他人のスフィアの結果を表示しようとした');

        // まだ終わっていないならエラー。
        if($record['result'] == Sphere_InfoService::ACTIVE)
            throw new MojaviException('まだ終わっていないスフィアの結果を表示しようとした');

        // クエスト情報をロード。
        $quest = $questSvc->needRecord($record['quest_id']);
        $this->setAttribute('quest', $quest);

        // スフィアオブジェクトを作成。
        $sphere = SphereCommon::load($record);

        // フィールドサマリを取得。
        $summary = $sphere->getSummary();
        $this->setAttribute('summary', $summary);

        // クエスト中に手に入れたアイテムの情報を取得。
        $itemSvc = new Item_MasterService();
        $treasures = array();
        foreach($summary['treasures'] as $itemId)
            $treasures[] = $itemSvc->getExRecord($itemId);

        $this->setAttribute('treasures', $treasures);

        // 終了後、連続実行したいクエストがあるかどうかをチェック。
        $nextId = $sphere->getNextQuest();
        if($nextId)
            $this->setAttribute('next', $questSvc->needRecord($nextId));

        // プラットフォーム投稿時の本文と戻り先URLを取得。
        $resultText = array(Sphere_InfoService::SUCCESS=>'ｸﾘｱ!', Sphere_InfoService::FAILURE=>'失敗', Sphere_InfoService::GIVEUP=>'ｷﾞﾌﾞｱｯﾌﾟ', Sphere_InfoService::ESCAPE=>'脱出!');
        $this->setAttribute( 'body', sprintf('%sのｸｴｽﾄ｢%s｣を%s', SITE_SHORT_NAME, $quest['quest_name'], $resultText[$record['result']]) );
        $this->setAttribute( 'returnTo', Common::genUrl('User', 'PlatformArticle', array('done'=>time())) );

        return View::SUCCESS;
    }
}
