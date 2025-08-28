<?php

/**
 * 期間限定クエの特殊処理を記述する
 */
class SphereOn98012 extends SphereCommon {

    // クリアした後は何度でも「後劇」を連続実行させる。
    protected $nextQuestId = 98013;
    protected $nextQuestRepeat = true;

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressEvent()をオーバーライド。
     */
    protected function progressEvent(&$leads, $event) {

        $gimmicks = $this->state['gimmicks'];

        //主人公が死んでいなければ
        if($this->getUnitByCode() != null){
            // 主人公ユニットの番号を取得。
            $avatarNo = sprintf('%03d', $this->getUnitByCode()->getNo());

            if($event['type'] =="gimmick"){
                //unlock1
                if($event['name'] == "unlock1"){
                    $this->openGate($leads, $gimmicks["gate1"]);
                }else if($event['name'] == "unlock2"){
                    $this->openGate($leads, $gimmicks["gate2"]);
                }else if($event['name'] == "unlock3"){
                    $pos = array(3, 15);
                    $leads[] = "UALGN {$avatarNo} 0";
                    $this->changeSwitch($leads, $pos);

                    $this->openGate($leads, $gimmicks["gate3"]);
                }else if($event['name'] == "unlock4"){
                    $this->openGate($leads, $gimmicks["gate4"]);
                }else if($event['name'] == "unlock5"){
                    $pos = array(2, 3);
                    $leads[] = "UALGN {$avatarNo} 3";
                    $this->changeSwitch($leads, $pos);

                    $this->openGate($leads, $gimmicks["gate5"]);
                }else if($event['name'] == "unlock6"){
                    $this->openGate($leads, $gimmicks["gate6"]);
                }else if($event['name'] == "unlock7"){
                    $pos = array(9, 3);
                    $leads[] = "UALGN {$avatarNo} 2";
                    $this->changeSwitch($leads, $pos);

                    $this->openGate($leads, $gimmicks["gate7"]);
                }
            }
        }

        return parent::progressEvent($leads, $event);

    }

    /*
     * スイッチを切り替える
    */
    public function changeSwitch(&$leads, $pos) {
        //スイッチ切り替え
        $leads[] = $this->map->changeSquare($pos, 2757);
        $leads[] = 'DELAY 100';
        $leads[] = $this->map->changeSquare($pos, 2758);
        $leads[] = 'DELAY 100';
        $leads[] = $this->map->changeSquare($pos, 2759);
        $leads[] = 'DELAY 100';
    }

    /*
     * 扉が開く
    */
    public function openGate(&$leads, $gate) {
        $gate_x = $gate["pos"][0];
        $gate_y = $gate["pos"][1];

        $tips = 2718;

        //チップ入れ替えして通れるようにする
        $leads[] = $this->map->changeSquare(array($gate_x, $gate_y), $tips);
        $leads[] = $this->map->changeSquare(array($gate_x + 1, $gate_y), $tips);

        $orns = $this->map->getOrnaments();
        foreach($orns as $key => $rows){
            if($rows[pos][0] == $gate_x && $rows[pos][1] == $gate_y){

                $orn = array(
                            "pos" => $gate["pos"] ,
                            "type" => "gate_open" ,
                        );
                //置物を変更
                $this->map->changeOrnament($leads, $key, $orn);

                $leads[] = 'SEPLY se_gachashutter';
                $leads[] = 'DELAY 500';

                $orn = array(
                            "pos" => $gate["pos"] ,
                            "type" => "gate_opened" ,
                        );

                //置物を再度変更（開きっぱなしにする）
                $this->map->changeOrnament($leads, $key, $orn);

                break;
            }
        }
    }
}
