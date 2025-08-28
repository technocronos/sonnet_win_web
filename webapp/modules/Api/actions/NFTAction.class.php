<?php

/**
 * 事前登録を処理するアクション。
 */
class NFTAction extends SmfBaseAction {

    protected $guestAccess = true;

    protected function doExecute($params){
        return array();
    }

    public function execute() {

        $token_id = $_GET['tokenId'];

        $array = array();

        if($token_id == ""){
            //特典コードが入力されていない
            return $array;
        }
        
        $record = Service::create('NFT_Equip')->getRecord($token_id);

        if($record["type"] == 'PLA'){
            $head = Service::create('Item_Master')->getRecord($record["HED"]);
            $body = Service::create('Item_Master')->getRecord($record["BOD"]);
            $weapon = Service::create('Item_Master')->getRecord($record["WPN"]);
            $acs = Service::create('Item_Master')->getRecord($record["ACS"]);

            $chara['race'] = $record["type"];
            $chara['equip'][Mount_MasterService::PLAYER_HEAD]["item_id"] = $head['item_id'];
            $chara['equip'][Mount_MasterService::PLAYER_BODY]["item_id"] = $body['item_id'];
            $chara['equip'][Mount_MasterService::PLAYER_WEAPON]["item_id"] = $weapon['item_id'];
            $chara['equip'][Mount_MasterService::PLAYER_SHIELD]["item_id"] = $acs['item_id'];

            $array["name"] = "#" . $token_id;
        }else if($record["type"] == 'MOB'){
            $chara = Service::create('Character_Info')->getRecord($record["character_id"]);
            $chara['equip'][Mount_MasterService::PLAYER_HEAD]["item_id"] = null;
            $chara['equip'][Mount_MasterService::PLAYER_BODY]["item_id"] = null;
            $chara['equip'][Mount_MasterService::PLAYER_WEAPON]["item_id"] = null;
            $chara['equip'][Mount_MasterService::PLAYER_SHIELD]["item_id"] = null;
            $chara["name"] = Text_LogService::get($chara['name_id']);

            $monster = Service::create('Monster_Master')->getRecord($record["character_id"]);

            $array["name"] = $chara["name"];
        }

        $spec = CharaImageUtil::getSpec($chara);

        $imgtype = 'large';
        if($record["type"] == 'PLA'){
            if($record["BG"] != "")
                $imgtype = $imgtype . '_' . $record["BG"];
        }else if($record["type"] == 'MOB'){
            $imgtype = $imgtype . '_bgmon' . $monster["rare_level"];
        }

        $array["external_url"] = "https://web.sonnet.crns-game.net";

        if($record["character_id"] != null){
            $character = Service::create('Character_Info')->getRecord($record["character_id"]);
            $array["image"] = sprintf(APP_WEB_ROOT.'img/GIF/' . $record["type"] . '/%05d.gif', $character["graphic_id"]);
        }else{
            $array["image"] = APP_WEB_ROOT . "img/chara/" . $spec . "." . $imgtype . ".gif";
        }

        $array["attributes"] = array();

        $i = -1;
        if($record["type"] == 'PLA'){
            $i++;
            $array["attributes"][$i]["trait_type"] = "WEAPON";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = $weapon["item_name"] . "(" . Set_MasterService::$RARITY[$weapon["rear_level"]] . ")";

            $i++;
            $array["attributes"][$i]["trait_type"] = "BODY";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = $body["item_name"] . "(" . Set_MasterService::$RARITY[$body["rear_level"]] . ")";

            $i++;
            $array["attributes"][$i]["trait_type"] = "HEAD";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = $head["item_name"] . "(" . Set_MasterService::$RARITY[$head["rear_level"]] . ")";

            $i++;
            $array["attributes"][$i]["trait_type"] = "ACCESSORY";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = $acs["item_name"] . "(" . Set_MasterService::$RARITY[$acs["rear_level"]] . ")";

            $i++;
            $array["attributes"][$i]["trait_type"] = "TYPE";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = "HERO";

            $str = "- " . "**"  . $array["attributes"][0]["value"] . "**:" . $head["flavor_text"] . "
" . "- " . "**"  . $array["attributes"][1]["value"] . "**:" . $body["flavor_text"] . "
" . "- " . "**"  . $array["attributes"][2]["value"] . "**:" . $weapon["flavor_text"] . "
" . "- " . "**"  . $array["attributes"][3]["value"] . "**:" . $acs["flavor_text"] . "
";

        }else if($record["type"] == 'MOB'){
            $i++;
            $array["attributes"][$i]["trait_type"] = "RARELITY";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = Monster_MasterService::$RARE_LEVELS[$monster["rare_level"]];

            $i++;
            $array["attributes"][$i]["trait_type"] = "CATEGORIE";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = Monster_MasterService::$CATEGORIES[$monster["category"]];


            $i++;
            $array["attributes"][$i]["trait_type"] = "TYPE";
            $array["attributes"][$i]["display_type"] = "string";
            $array["attributes"][$i]["value"] = "MONSTER";

            $str = $monster["flavor_text"];
        }
       
        $array["description"] = $str;

        // 作成したswfを出力。
        $this->respond($array);

        return View::NONE;

    }
}
