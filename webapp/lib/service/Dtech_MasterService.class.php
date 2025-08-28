<?php

class Dtech_MasterService extends Service {

    // カラの必殺技のID。
    const NONE = 0;

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["dtech_name", "dtech_desc"];

        foreach($columns as $column){
            $data = AppUtil::getText("dtech_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'dtech_id';

    protected $isMaster = true;

    /**
     * getRecordをオーバーライドして多言語対応
     */
    public function getRecord(/* 可変引数 */) {
        $args = func_get_args();
        $record = parent::getRecord($args[0]);

        $record = $this->getTransText($record);

        return $record;
    }

    /**
     * selectResultsetをオーバーライドして多言語対応
     */
    public function selectResultset($where) {
        $record = parent::selectResultset($where);

        foreach($record as &$row){
            $row = $this->getTransText($row);
        }

        return $record;
    }
}
