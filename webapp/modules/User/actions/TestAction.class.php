<?php

/**
 */
class TestAction extends UserBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;


    public function execute() {


        if(ENVIRONMENT_TYPE == 'prod')
            throw new MojaviException('本番でテストは実行できません');

        DataAccessObject::$DEBUG = true;


        # $ret = PlatformApi::queryNickname();

#         $ret = PlatformApi::readyPayment(array(
#             'user_id'=>12257739, 'item_id'=>100, 'amount'=>2, 'unit_price'=>200
#         ));
#
#         var_dump($ret); exit;
#
#         Controller::getInstance()->redirect($ret['transactionUrl']);

        $url = AppUtil::readyPayment(array(
            'item_type' => 'IT',
            'item_id' => 1902,
            'item_name' => 'ニワトリの時計',
            'unit_price' => 100,
            'amount' => 2,
        ));

        var_dump($url); exit;

        Controller::getInstance()->redirect($url);

#         $this->setAttribute('ret', $ret);
        return View::NONE;
#         return View::SUCCESS;
    }
}
