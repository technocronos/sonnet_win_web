<?php

class NFTListAction extends AdminBaseAction {

    public function execute() {

//$this->mint("0x1b24d129a18153644ce423693Ea7E9a92927154C", 33);

        $nft = Service::create('NFT_Equip')->getList(100, 0);

        $Parsedown = new Parsedown();

        foreach($nft["resultset"] as &$row){

            $json = "";

            $url = APP_WEB_ROOT . '?module=Api&action=NFT&tokenId=' . $row["token_id"];

            $fp = fopen($url, 'r');

            // 1行づつ読み込み出力する
            while (($line = fgets($fp, 1024))) {
                $json .= $line;
            }

            fclose($fp);  // ファイルを閉じる

            $row["metadata"] = json_decode($json,true);

            $row["metadata"]["description"] = $Parsedown->text($row["metadata"]["description"]);
        }

        // ビューに割り当てる。
        $this->setAttribute('nft', $nft["resultset"]);

        return View::SUCCESS;
    }

    private function mint($address, $token_id){

        //web3.sha3('mint(address,uint256)').substring(0,10)
        //0x40c10f19

        $master_address = $this->removeHexPrefix(ETH_ADDRESS);
        $send_address = $this->removeHexPrefix($address);
        $tokenId = sprintf("%064s", dechex($token_id));

        $datas = "0x40c10f19" . "000000000000000000000000" . $send_address . $tokenId;

        // getBalance
        $data = [
            "jsonrpc" => "2.0",
            "method" => "eth_call",
            "params" => [
            ],
            "id" => 65534,
        ];

        $data["params"][0]["from"] = ETH_ADDRESS;
        $data["params"][0]["to"] = CONTRACT_ADDRESS;
        $data["params"][0]["data"] = $datas;
        $data["params"][1] = "latest";

        $response = $this->postJson($data);
        $getData = json_decode($response);
print_r($getData);
exit;
        return $getData->result;
    }

    private function getTokenOfOwnerByIndex($address, $token_id){

        //web3.sha3('tokenOfOwnerByIndex(address,uint256)').substring(0,10)
        //0x2f745c59

        $master_address = $this->removeHexPrefix(ETH_ADDRESS);
        $send_address = $this->removeHexPrefix($address);
        $tokenId = sprintf("%064s", dechex($token_id));

        $datas = "0x2f745c59" . "000000000000000000000000" . $send_address . $tokenId;

        // getBalance
        $data = [
            "jsonrpc" => "2.0",
            "method" => "eth_call",
            "params" => [
            ],
            "id" => 65534,
        ];

        $data["params"][0]["from"] = null;
        $data["params"][0]["to"] = CONTRACT_ADDRESS;
        $data["params"][0]["data"] = $datas;
        $data["params"][1] = "latest";

        $response = $this->postJson($data);

        $getData = json_decode($response);

        return $getData->result;
    }

    private function getOwnerOf($token_id){
        //https://qiita.com/minato774/items/ade85571665a02d3db5f

        //C:\Program Files\Geth geth console
        //web3.sha3('ownerOf(uint256)').substring(0,10)
        //0x6352211e
        //web3.sha3('name()').substring(0,10)
        //0x06fdde03
        // web3.sha3('balanceOf(address)').substring(0,10)
        //0x70a08231

        //コントラクトアドレス
        $contract = CONTRACT_ADDRESS;

        $tokenId = sprintf("%064s", dechex($token_id));
        $datas = "0x6352211e" . $tokenId;

        // getBalance
        $data = [
            "jsonrpc" => "2.0",
            "method" => "eth_call",
            "params" => [
            ],
            "id" => 65534,
        ];

        $data["params"][0]["from"] = null;
        $data["params"][0]["to"] = $contract;
        $data["params"][0]["data"] = $datas;
        $data["params"][1] = "latest";

        $response = $this->postJson($data);
        $getData = json_decode($response);

        return $getData->result;

    }

    private function getBalanceOf(){
        //https://qiita.com/minato774/items/ade85571665a02d3db5f

        //C:\Program Files\Geth geth console
        //web3.sha3('ownerOf(uint256)').substring(0,10)
        //0x6352211e
        //web3.sha3('name()').substring(0,10)
        //0x06fdde03
        // web3.sha3('balanceOf(address)').substring(0,10)
        //0x70a08231

        //コントラクトアドレス
        $contract = CONTRACT_ADDRESS;

        $address = $this->removeHexPrefix(ETH_ADDRESS);

        $datas = "0x70a08231" . "000000000000000000000000" . $address;

        // getBalance
        $data = [
            "jsonrpc" => "2.0",
            "method" => "eth_call",
            "params" => [
            ],
            "id" => 65534,
        ];

        $data["params"][0]["from"] = null;
        $data["params"][0]["to"] = $contract;
        $data["params"][0]["data"] = $datas;
        $data["params"][1] = "latest";

        $response = $this->postJson($data);
        $getBalance = json_decode($response);

        $ethbalance = $this->decodeHex($getBalance->result);

        return $ethbalance;

    }


    private function postJson($json){
        //
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://rinkeby.infura.io/v3/9cb0242302974358a674852b3825a8b7');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function decodeHex($hex){
        $dec = null;
        if(substr($hex, 0, 2)=='0x') {
            $hex = substr($hex, 2);
        }
        if(preg_match('/[0-9a-f]+/', $hex)) {
            $dec = hexdec($hex) / pow(10, 18);
        }
        return $dec;
    }

    function removeHexPrefix($str)
    {
        if (!$this->hasHexPrefix($str)) {
            return $str;
        }

        return substr($str, 2);
    }

    function hasHexPrefix($str)
    {
        return substr($str, 0, 2) === '0x';
    }

}
