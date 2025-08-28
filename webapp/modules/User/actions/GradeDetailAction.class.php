<?php

class GradeDetailAction extends UserBaseAction {

    public function execute() {

        // 省略されているパラメータを補う。
        if($_GET['page'] == '')  $_GET['page'] = 0;

        $gradeSvc = new Grade_MasterService();

        $this->setAttribute('grade', $gradeSvc->needRecord($_GET['gradeId']));
        $this->setAttribute('list', $gradeSvc->getCharacterList($_GET['gradeId'], 6, $_GET['page']));

        return View::SUCCESS;
    }
}
