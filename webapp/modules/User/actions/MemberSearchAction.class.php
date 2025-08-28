<?php

class MemberSearchAction extends UserBaseAction {

    public function execute() {

        // 一門候補を取得。
        $memberSvc = new User_MemberService();
        $this->setAttribute('list', $memberSvc->getCandidateList($this->user_id));

        return View::SUCCESS;
    }
}
