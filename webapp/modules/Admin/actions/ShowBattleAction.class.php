<?php

/**
 * バトルログの詳細を見るアクション。
 * デバックメニュー。
 */
class ShowBattleAction extends AdminBaseAction {

    public function execute() {

        // id が指定されているなら...
        if(isset($_GET['id'])) {

            // 指定されたバトル情報を取得。
            $battleSvc = new Battle_LogService();
            $data = $battleSvc->getRecord($_GET['id']);

            // バトル情報あったなら、ちょっといじる。
            if($data) {

                $this->setAttribute('ready_detail', $data['ready_detail']);
                $this->setAttribute('result_detail', $data['result_detail']);

                unset($data['ready_detail']['challenger'], $data['ready_detail']['defender']);
                $this->setAttribute('ready_other', $data['ready_detail']);

                unset($data['ready_detail']);
                unset($data['result_detail']);
            }

            $this->setAttribute('data', $data);
        }

        return View::SUCCESS;
    }
}
