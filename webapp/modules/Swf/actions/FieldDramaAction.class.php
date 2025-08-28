<?php

class FieldDramaAction extends DramaBaseAction {

    protected function onExecute() {

        // 指定されたスフィアの情報をロード。
        $record = Service::create('Sphere_Info')->needRecord($_GET['sphereId']);

        // 他人のスフィアだったらエラー。
        if($record['user_id'] != $this->user_id)
            throw new MojaviException('他人のスフィアで寸劇を見ようとした');

        // スフィアを制御するオブジェクトを作成。
        $sphere = SphereCommon::load($record);

        // 再生終了の場合。
        if($_GET['end']) {

            // 寸劇終了を処理。
            $sphere->dramaEnd($_GET['end']);

            // フィールドに戻す。
            Common::redirect('Swf', 'Sphere', array('id'=>$_GET['sphereId']));

        // これから再生する場合。
        }else {

            // 再生する寸劇のIDを設定。
            $this->dramaId = $sphere->getSceneId();

            // 戻り先の設定。
            $this->endTo = Common::genContainerUrl(
                array('_self'=>true, 'end'=>$this->dramaId), null, null, true
            );
        }
    }
}
