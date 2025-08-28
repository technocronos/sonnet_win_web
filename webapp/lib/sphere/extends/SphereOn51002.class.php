<?php

/**
 * 流刑地の波止場の特殊処理を記述する
 */
class SphereOn51002 extends SphereCommon {

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
                    if($arr[1] == "%layla%" && !$this->getUnitByCode('layla')){
                        $lead = str_replace(array("LINES", "%layla%"), array("SPEAK", "%avatar% " . AppUtil::getText("text_log_body_-20103") . " "), $lead);
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
