<?php

/**
 * シルフの森の特殊処理を記述する
 */
class SphereOn21006 extends SphereCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressCommand()をオーバーライド。
     */
    public function progressCommand(&$leads) {

        //startルームのみ
        if($this->state["current_room"] != "start")
            return parent::progressCommand($leads);

        // コマンド対象になっているユニットを取得。
        $commUnit = $this->getUnit();

        // ドワーフ以外のユニットは基底処理
        if( $commUnit->getProperty('code') != "dwarf" ) {
            return parent::progressCommand($leads);
    		}

    		//ドワーフは何もしない（フォーカスも当てない）
        $this->phaseNext($leads);
        return false;
  	}

    //-----------------------------------------------------------------------------------------------------
    /**
     * replaceEmbedCode() をオーバーライド。
     */
    protected function replaceEmbedCode($leads) {

        $_leads = array();

        // 置き換え。
        foreach($leads as $lead) {

            // あとはコマンドごとに...
            switch( substr($lead, 0, 5) ) {

                case 'LINES':
                    $arr = explode(" ", $lead);
                    if($arr[1] == "%elena%" && !$this->getUnitByCode('elena')){
                        $lead = str_replace(array("LINES", "%elena%"), array("SPEAK", "%avatar% " . AppUtil::getText("text_log_body_-20100") . " "), $lead);
                    }

                    $_leads[] = $lead;
                    break;
                default:
                    $_leads[] = $lead;
            }

        }

        // あとは、通常通り処理。
        return parent::replaceEmbedCode($_leads);
    }


}
