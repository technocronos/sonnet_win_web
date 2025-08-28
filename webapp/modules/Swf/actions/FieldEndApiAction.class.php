<?php

/**
 * クエスト結果を処理するアクション。
 */
class FieldEndApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $array = array();

        //ヘッダー構成に必要な情報を得る
        $array['gold'] = (int)$this->userInfo['gold'];

        //メニュー内クエストへのURL
        $array['urlOnQuest'] = Common::genContainerUrl('Swf', 'Main', array("firstscene" => "quest"), true);

        $array['SPHERE_SUCCESS'] = Sphere_InfoService::SUCCESS;
        $array['SPHERE_ESCAPE'] = Sphere_InfoService::ESCAPE;
        $array['SPHERE_FAILURE'] = Sphere_InfoService::FAILURE;
        $array['SPHERE_GIVEUP'] = Sphere_InfoService::GIVEUP;

        $questSvc = Service::create('Quest_Master');

        // スフィア情報をロード。
        $record = Service::create('Sphere_Info')->getRecord($_GET['sphereId']);
        $array['sphere_result'] = $record['result'];
        if(!$record)
            $this->redirect('Swf', 'Main');

        // 他人のスフィアだったらエラー。
        if($record['user_id'] != $this->user_id)
            throw new MojaviException('他人のスフィアの結果を表示しようとした');

        // まだ終わっていないならエラー。
        if($record['result'] == Sphere_InfoService::ACTIVE)
            throw new MojaviException('まだ終わっていないスフィアの結果を表示しようとした');

        // クエスト情報をロード。
        $quest = $questSvc->needRecord($record['quest_id']);
        $array['quest'] = $quest;

        // スフィアオブジェクトを作成。
        $sphere = SphereCommon::load($record);

        // フィールドサマリを取得。
        $summary = $sphere->getSummary();
        $array['summary'] = $summary;

        // クエスト中に手に入れたアイテムの情報を取得。
        $itemSvc = new Item_MasterService();
        $treasures = array();
        foreach($summary['treasures'] as $itemId)
            $treasures[] = $itemSvc->getExRecord($itemId);

        $array['treasures'] = $treasures;

        // 終了後、連続実行したいクエストがあるかどうかをチェック。
        $nextId = $sphere->getNextQuest();
        if($nextId){
            $array['next'] = $questSvc->needRecord($nextId);
            $array['urlOnNext'] = Common::genContainerUrl('Swf', 'QuestDrama', array("questId" => $nextId), true);       
        }else{
            $array['next'] = null;
        }

        return $array;
    }
}
