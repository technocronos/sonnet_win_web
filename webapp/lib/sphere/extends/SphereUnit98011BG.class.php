<?php

/**
 * 電脳世界のソネットの古参を処理するクラス。
 */
class SphereUnit98011BG extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * decideCommand() をオーバーライド。
     */
    public function decideCommand(&$leads) {
//Common::varLog("decideCommand run....");

        //常にまずはgenericで考えさせる
        $this->changeBrain("generic");
        $command = parent::decideCommand($leads, $event);

        $map = $this->sphere->getMap();
        $pos = $this->getPos();
//Common::varLog($this->getCode());
//Common::varLog($command["move"]);
        //移動していない場合
        if(!$command["move"]){
            $point = null;
            foreach(SphereOn98011::$pos_array as $k => $value){
                $cost = $map->getRoute($this, $value["source"], $route);
//Common::varLog("cost=" . $cost);
                //ちゃんと目指せる所を目指す
                if($cost < 9990){
//Common::varLog("key=" . $k);
                    //現在いる位置が目指す座標にはならない
                    if($value["source"][0] != $pos[0] || $value["source"][1] != $pos[1]){
                        if(is_null($point)){
                            $point = array("key"=> $k, "cost" => $cost);
                        }else{
//Common::varLog($point["key"]);
                            //ただし、"warp3,4"と"warp5"の場合は主人公のX座標に近い方に行く
                            if(($point["key"] == "warp3" || $point["key"] == "warp4") && $k == "warp5"){
                                $avatar = $this->sphere->getUnitByCode('avatar');
                                $avatar_pos = $avatar->getPos();
//Common::varLog($avatar_pos);
//Common::varLog($pos);

                                //自分よりプレイヤーが右にいればwarp5にする
                                if(($avatar_pos[0] >= 11 && $avatar_pos[1] <= 13) || $avatar_pos[1] <= 5)
                                    $point = array("key"=> $k, "cost" => $cost);
                            }else{
                                $point = array("key"=> $k, "cost" => $cost);
                            }
                        }
                    }
                }
            }

            if(!is_null($point)){
//Common::varLog($point["key"]);

                $this->changeBrain("destine");
                $this->setProperty("destine_pos", SphereOn98011::$pos_array[$point["key"]]["source"]);
            }

        }
        return parent::decideCommand($leads, $event);
    }

}
