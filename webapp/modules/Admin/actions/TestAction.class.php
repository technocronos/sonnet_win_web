<?php

/**
 */
class TestAction extends AdminBaseAction {

    public function execute() {

        if(ENVIRONMENT_TYPE == 'prod')
            throw new MojaviException('本番でテストは実行できません');

        DataAccessObject::$DEBUG = true;


#         $svc = new Monster_MasterService();

        # $ret = PlatformApi::queryProfile(12257739);
        # $ret = PlatformApi::postText('テスト', '12257739', NULL)
        $ret = PlatformApi::readyPayment(array(
            'user_id'=>12257739, 'item_id'=>100, 'amount'=>2, 'unit_price'=>200
        ));


        var_dump($ret);
        exit();


        return View::NONE;
    }
}
