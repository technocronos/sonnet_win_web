<?php

/**
 * ETHアドレスを登録処理するアクション。
 */
class AddrRegistAction extends SmfBaseAction {

    protected function doExecute($params) {

        $address = $params["address"];

        if($address == "")
            return array('result'=>'ok', "err_code"=>"empty_address");

        $validator = new EthereumValidator();
        if(!$validator->isAddress($address)){
            return array('result'=>'ok', "err_code"=>"invaild_address");
        }

        Service::create('User_Property_String')->updateProperty($this->user_id, 'ether_addr', $address);

        return array('result'=>'ok');

    }

}
