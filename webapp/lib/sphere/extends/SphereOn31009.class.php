<?php

/**
 * 大脱走の特殊処理を記述する
 */
class SphereOn31009 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    protected $nextQuestId = 31010;

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
                    if($arr[1] == "%Galuf2%" && !$this->getUnitByCode('Galuf2')){
                        $lead = str_replace(array("LINES", "%Galuf2%"), array("SPEAK", "%avatar% " . AppUtil::getText("DRAMA_CODE_shisyou") . " "), $lead);
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
