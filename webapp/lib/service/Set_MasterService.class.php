<?php

class Set_MasterService extends Service {

    public static $RARITY = array(
        1 => "N",
        2 => "N+",
        3 => "R",
        4 => "R+",
        5 => "SR",
        6 => "SR+",
        7 => "UR",
        8 => "UR+",
        9 => "LR",
        10 => "LR+"
    );

    /**
     * getRecordをオーバーライドして多言語対応
     */
    public function getRecord(/* 可変引数 */) {
        $args = func_get_args();
        $record = parent::getRecord($args[0]);

        $columns = ["set_name", "set_text"];

        foreach($columns as $column){
            $data = AppUtil::getText("set_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'set_id';

    protected $isMaster = true;
}
