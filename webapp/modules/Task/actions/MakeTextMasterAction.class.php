<?php

/**
 * テキスト関連を全部textmasterに移す。
 *
 */
class MakeTextMasterAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        /*
         * drama_master
        */
        $list = Service::create('Drama_Master')->getAllRecords();

        $drama_flow_prefix = "drama_master_flow_";

        foreach($list as $key=>$value){
            $key = $drama_flow_prefix . $value["drama_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["flow"]);
                Service::create('Text_Master')->saveRecord($record);
            }            
        }

        /*
         * item_master
        */
        $list = Service::create('Item_Master')->getAllRecords();

        $item_master_item_name_prefix = "item_master_item_name_";
        $item_master_flavor_text_prefix = "item_master_flavor_text_";

        foreach($list as $key=>$value){
            $key = $item_master_item_name_prefix . $value["item_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["item_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }

            $key = $item_master_flavor_text_prefix . $value["item_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["flavor_text"]);
                Service::create('Text_Master')->saveRecord($record);
            }
            
        }

        /*
         * gacha_master
        */
        $list = Service::create('Gacha_Master')->getAllRecords();

        $gacha_master_gacha_name_prefix = "gacha_master_gacha_name_";
        $gacha_master_caption_prefix = "gacha_master_caption_";
        $gacha_master_flavor_text_prefix = "gacha_master_flavor_text_";

        foreach($list as $key=>$value){
            $key = $gacha_master_gacha_name_prefix . $value["gacha_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["gacha_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }

            $key = $gacha_master_caption_prefix . $value["gacha_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["caption"]);
                Service::create('Text_Master')->saveRecord($record);
            }

            $key = $gacha_master_flavor_text_prefix . $value["gacha_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["flavor_text"]);
                Service::create('Text_Master')->saveRecord($record);
            }
            
        }

        /*
         * grade_master
        */
        $list = Service::create('Grade_Master')->getAllRecords();

        $grade_master_grade_name = "grade_master_grade_name_";

        foreach($list as $key=>$value){
            $key = $grade_master_grade_name . $value["grade_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["grade_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }            
        }


        /*
         * help_master
        */
        $list = Service::create('Help_Master')->getAllRecords();

        $help_master_help_title = "help_master_help_title_";
        $help_master_help_body = "help_master_help_body_";

        foreach($list as $key=>$value){
            $key = $help_master_help_title . $value["help_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["help_title"]);
                Service::create('Text_Master')->saveRecord($record);
            }

            $key = $help_master_help_body . $value["help_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["help_body"]);
                Service::create('Text_Master')->saveRecord($record);
            }

        }


        /*
         * monster_master
        */
        $list = Service::create('Monster_Master')->getAllRecords();

        $monster_master_habitat = "monster_master_habitat_";
        $monster_master_flavor_text = "monster_master_flavor_text_";

        foreach($list as $key=>$value){
            $key = $monster_master_habitat . $value["character_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["habitat"]);
                Service::create('Text_Master')->saveRecord($record);
            }

            $key = $monster_master_flavor_text . $value["character_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["flavor_text"]);
                Service::create('Text_Master')->saveRecord($record);
            }

        }

        /*
         * mount_master
        */
        $list = Service::create('Mount_Master')->getAllRecords();

        $mount_master_mount_name = "mount_master_mount_name_";

        foreach($list as $key=>$value){
            $key = $mount_master_mount_name . $value["race"] . "_" . $value["mount_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["mount_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }            
        }


        /*
         * place_master
        */
        $list = Service::create('Place_Master')->getAllRecords();

        $place_master_place_name = "place_master_place_name_";

        foreach($list as $key=>$value){
            $key = $place_master_place_name . $value["place_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["place_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }            
        }

        /*
         * quest_master
        */
        $list = Service::create('Quest_Master')->getAllRecords();

        $quest_master_quest_name = "quest_master_quest_name_";
        $quest_master_flavor_text = "quest_master_flavor_text_";

        foreach($list as $key=>$value){
            $key = $quest_master_quest_name . $value["quest_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["quest_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }            

            $key = $quest_master_flavor_text . $value["quest_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["flavor_text"]);
                Service::create('Text_Master')->saveRecord($record);
            }            

        }

        /*
         * raid_dungeon
        */
        $list = Service::create('Raid_Dungeon')->getAllRecords();

        $raid_dungeon_title = "raid_dungeon_title_";
        $raid_dungeon_description = "raid_dungeon_description_";

        foreach($list as $key=>$value){
            $key = $raid_dungeon_title . $value["id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["title"]);
                Service::create('Text_Master')->saveRecord($record);
            }            

            $key = $raid_dungeon_description . $value["id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["description"]);
                Service::create('Text_Master')->saveRecord($record);
            }            

        }

        /*
         * set_master
        */
        $list = Service::create('Set_Master')->getAllRecords();

        $set_master_set_name = "set_master_set_name_";
        $set_master_set_text = "set_master_set_text_";

        foreach($list as $key=>$value){
            $key = $set_master_set_name . $value["set_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["set_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }            

            $key = $set_master_set_text . $value["set_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["set_text"]);
                Service::create('Text_Master')->saveRecord($record);
            }            

        }

        /*
         * text_log
        */
        $list = Service::create('Text_Log')->getWriters(-1);

        $text_log_body = "text_log_body_";

        foreach($list as $key=>$value){
            $key = $text_log_body . $value["text_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["body"]);
                Service::create('Text_Master')->saveRecord($record);
            }            
        }

        /*
         * dtech_master
        */
        $list = Service::create('Dtech_Master')->getAllRecords();

        $dtech_master_dtech_name = "dtech_master_dtech_name_";
        $dtech_master_dtech_desc = "dtech_master_dtech_desc_";

        foreach($list as $key=>$value){
            if($value["dtech_id"]  == 0)
                continue;

            $key = $dtech_master_dtech_name . $value["dtech_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["dtech_name"]);
                Service::create('Text_Master')->saveRecord($record);
            }      

            $key = $dtech_master_dtech_desc . $value["dtech_id"];

            $row = Service::create('Text_Master')->getSymbol($key);
            if($row == null){
                $record = array("symbol" => $key, "ja" => $value["dtech_desc"]);
                Service::create('Text_Master')->saveRecord($record);
            }        
        }




        return View::NONE;
    }
}
