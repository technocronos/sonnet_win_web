<?php

/**
 * 期間限定クエの特殊処理を記述する
 */
class SphereOn98042 extends SphereCommon {

    // クリアした後は何度でも「後劇」を連続実行させる。
    //protected $nextQuestId = 98042;
    //protected $nextQuestRepeat = true;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 卵が産まれるギミック
     */
    protected function processBirthEgg(&$leads, &$gimmick, $trigger) {
        $gimmicks = $this->state['gimmicks'];
        $egg = $gimmicks["egg"];

        //もともとeggが無ければ処理しない
        if($egg != null){
            $_x = $egg["pos"][0];
            $_y = $egg["pos"][1];

            $orns = $this->map->getOrnaments();
            foreach($orns as $key => $rows){
                if($rows[pos][0] == $_x && $rows[pos][1] == $_y){

                    $orn = array(
                                "pos" => $egg["pos"] ,
                                "type" => "egg_birth" ,
                            );
                    //置物を変更
                    $this->map->changeOrnament($leads, $key, $orn);

                    //SE
                    $leads[] = 'ENVCG FF7A7A';
                    $leads[] = 'VIBRA 10';
                    $leads[] = 'SEPLY se_kyoka2';
                    $leads[] = 'DELAY 3000';
                    $leads[] = 'SEPLY se_thunder';
                    $leads[] = 'DELAY 2000';
                    $leads[] = 'VIBRA 0';
                    $leads[] = 'ENVCG FFFFFF';

                    break;
                }
            }
        }

        return false;
    }

}
