<?php

/**
 * ゲソてんの購入テスト用画面
 */
class TestPayAction extends UserBaseAction {

    public function execute() {

#         $ret = PlatformApi::readyPayment(array(
#             'user_id'=>12257739, 'item_id'=>100, 'amount'=>2, 'unit_price'=>200
#         ));
#
#         var_dump($ret); exit;
#
#         Controller::getInstance()->redirect($ret['transactionUrl']);

        switch($_POST['go']) {
            case 1:
                $url = AppUtil::readyPayment(array(
                    'item_type' => 'IT',
                    'item_id' => 1902,
                    'item_name' => 'ニワトリの時計',
                    'unit_price' => 100,
                    'amount' => 2,
                ));
                break;
            case 2:
                $url = AppUtil::readyPayment(array(
                    'item_type' => 'GC',
                    'item_id' => 2,
                    'item_name' => 'スペシャルガチャ',
                    'unit_price' => 500,
                ));
                break;
        }

        if($url) {
            # var_dump($url); exit;
            Controller::getInstance()->redirect($url);
        }

        return View::SUCCESS;
    }
}
