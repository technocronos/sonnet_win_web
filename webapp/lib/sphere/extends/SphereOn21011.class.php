<?php

/**
 * トロルの里の特殊処理を記述する
 */
class SphereOn21011 extends SphereCommon {

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
