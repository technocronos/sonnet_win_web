<?php

/**
 * 期間限定クエの特殊処理を記述する
 */
class SphereOn98043 extends SphereCommon {

    // クリアした後は何度でも「後劇」を連続実行させる。
    protected $nextQuestId = 98044;
    protected $nextQuestRepeat = true;

    //-----------------------------------------------------------------------------------------------------
    /**
     * 時空の穴をふさぐギミック
     */
    protected function processWhitehallOut(&$leads, &$gimmick, $trigger) {
        $gimmicks = $this->state['gimmicks'];
        $whitehall = $gimmicks["whitehall"];

        if($whitehall != null){
            $_x = $whitehall["pos"][0];
            $_y = $whitehall["pos"][1];

            $orns = $this->map->getOrnaments();
            foreach($orns as $key => $rows){
                if($rows[pos][0] == $_x && $rows[pos][1] == $_y){

                    $orn = array(
                                "pos" => $whitehall["pos"] ,
                                "type" => "whitehallout" ,
                            );
                    //置物を変更
                    $this->map->changeOrnament($leads, $key, $orn);

                    //SE
                    $leads[] = 'ENVCG FF7A7A';
                    $leads[] = 'VIBRA 10';
                    $leads[] = 'SEPLY se_explosionshort';
                    $leads[] = 'DELAY 500';
                    $leads[] = 'SEPLY se_explosionshort';
                    $leads[] = 'DELAY 500';
                    $leads[] = 'SEPLY se_explosionshort';
                    $leads[] = 'DELAY 500';
                    $leads[] = 'SEPLY se_explosionshort';
                    $leads[] = 'DELAY 500';
                    $leads[] = 'SEPLY se_hover';
                    $leads[] = 'DELAY 2000';
                    $leads[] = 'VIBRA 0';
                    $leads[] = 'ENVCG FFFFFF';

                    $orn = array(
                                "pos" => $whitehall["pos"] ,
                                "type" => "mark1" ,
                            );

                    //置物を再度変更
                    $this->map->changeOrnament($leads, $key, $orn);

                    break;
                }
            }
        }

        return false;
    }

}
