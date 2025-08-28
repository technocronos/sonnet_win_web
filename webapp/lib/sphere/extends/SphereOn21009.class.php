<?php

/**
 * コバイヤ火山で蟲退治の特殊処理を記述する
 */
class SphereOn21009 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    //protected $nextQuestId = 31007;

    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmick() をオーバーライド。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {

        // このクエの特殊イベントを処理する
        if($gimmick['type'] == 'unit_move') {
            //サラマンダー道をあける
            $saram = $this->getUnitByCode('saram');

            $route = $this->map->getThroughRoute($saram->getPos(), array(3,7), 'x');
            $leads[] = sprintf('UMOVE %03d %s', $saram->getNo(), $route);
            $saram->setPos(array(3,7));

        }else if($gimmick['type'] == 'trap2') {

            $commUnit = $this->getUnit();

            //レッドワーム以外の場合
            if($commUnit->getProperty("character_id") != -10105){

                $this->fireTrap($leads, $commUnit);

            }

        }

        // あとは、通常通り処理。
        return parent::fireGimmick($leads, $gimmick, $unit);

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressTurnEndをオーバーライド
     */
    public function progressTurnEnd(&$leads) {

        // コマンドと、コマンド対象になっているユニットを取得。
        $command = $this->state['command'];
        $commUnit = $this->getUnit();

        //レッドワームの場合
        if($commUnit->getProperty("character_id") == -10105){

            //攻撃しかけた後はネットを張らない
            if(!$command["attack"]){
                //移動の場合
                //pathは携帯のテンキーで表現されている　例：4488→左左下下
                if($command["move"]["path"]){
                    $lastmove = substr($command["move"]["path"], -1);    // 最後に移動した方向を返す
                    $x = $command["move"]["to"][0];
                    $y = $command["move"]["to"][1];

                    //進行方向に吐く
                    switch($lastmove){
                      case 2:
                          $result = $this->changetips_magma($leads, array($x, $y-1));
                          break;
                      case 4:
                          $result = $this->changetips_magma($leads, array($x-1, $y));
                          break;
                      case 6:
                          $result = $this->changetips_magma($leads, array($x+1, $y));
                          break;
                      case 8:
                          $result = $this->changetips_magma($leads, array($x, $y+1));
                          break;
                    }

                    //どこにも吐いてない場合はどっかしらに吐く
                    if($result == false){
                        $result = $this->changetips_magma($leads, array($x, $y+1));
                        if($result == false){
                            $result = $this->changetips_magma($leads, array($x, $y-1));
                            if($result == false){
                                $result = $this->changetips_magma($leads, array($x-1, $y));
                                if($result == false){
                                    $result = $this->changetips_magma($leads, array($x+1, $y));
                                }
                            }
                        }
                    }
                }else{
                    //動かない場合、どこかしらにマグマを吐く 
                    $pos = $commUnit->getPos();
                    $x = $pos[0];
                    $y = $pos[1];

                    $result = $this->changetips_magma($leads, array($x, $y+1));
                    if($result == false){
                        $result = $this->changetips_magma($leads, array($x, $y-1));
                        if($result == false){
                            $result = $this->changetips_magma($leads, array($x-1, $y));
                            if($result == false){
                                $result = $this->changetips_magma($leads, array($x+1, $y));
                            }
                        }
                    }
                }
            }
        //マグマイーターの場合
        }else if($commUnit->getProperty("character_id") == -10106){
            //攻撃しかけた後はネットを張らない
            if(!$command["attack"]){
                //移動の場合
                //pathは携帯のテンキーで表現されている　例：4488→左左下下
                if($command["move"]["path"]){
                    $lastmove = substr($command["move"]["path"], -1);    // 最後に移動した方向を返す
                    $x = $command["move"]["to"][0];
                    $y = $command["move"]["to"][1];

                    //進行方向のマグマを食べる
                    switch($lastmove){
                      case 2:
                          $result = $this->changetips_normal($leads, array($x, $y-1));
                          break;
                      case 4:
                          $result = $this->changetips_normal($leads, array($x-1, $y));
                          break;
                      case 6:
                          $result = $this->changetips_normal($leads, array($x+1, $y));
                          break;
                      case 8:
                          $result = $this->changetips_normal($leads, array($x, $y+1));
                          break;
                    }

                    //どこも食べてない場合はどっかしらを食べる
                    if($result == false){
                        $result = $this->changetips_normal($leads, array($x, $y+1));
                        if($result == false){
                            $result = $this->changetips_normal($leads, array($x, $y-1));
                            if($result == false){
                                $result = $this->changetips_normal($leads, array($x-1, $y));
                                if($result == false){
                                    $result = $this->changetips_normal($leads, array($x+1, $y));
                                }
                            }
                        }
                    }
                }else{
                    //動かない場合、どこかしらのマグマを食べる
                    $pos = $commUnit->getPos();
                    $x = $pos[0];
                    $y = $pos[1];

                    $result = $this->changetips_normal($leads, array($x, $y+1));
                    if($result == false){
                        $result = $this->changetips_normal($leads, array($x, $y-1));
                        if($result == false){
                            $result = $this->changetips_normal($leads, array($x-1, $y));
                            if($result == false){
                                $result = $this->changetips_normal($leads, array($x+1, $y));
                            }
                        }
                    }

                }
            }
        }else if($commUnit->getCode() == 'avatar'){
          //主人公の場合
            if($this->getGraph_no($command["move"]["to"]) == 400){
                //マグマを踏んだ
                $this->fireTrap($leads, $commUnit);
            }
        }

        return parent::progressTurnEnd($leads);
  }

    //-----------------------------------------------------------------------------------------------------
    /**
     * チップをマグマに変更
     */
    private function changetips_magma(&$leads, $pos){
        //チップ番号107(ゴールの穴)と199（外壁）はマグマで潰せないのでリターン。また、すでにマグマの場合もリターン
        if($this->getGraph_no($pos) == 107 
              || $this->getGraph_no($pos) == 199 
                || $this->getGraph_no($pos) == 1922 || $this->getGraph_no($pos) == 1923 || $this->getGraph_no($pos) == 1924 
                || $this->getGraph_no($pos) == 1800 || $this->getGraph_no($pos) == 1803 || $this->getGraph_no($pos) == 1815 
                || $this->getGraph_no($pos) == 1919 || $this->getGraph_no($pos) == 1920 || $this->getGraph_no($pos) == 1921 
                  || $this->getGraph_no($pos) == 400)
            return false;

        //そこに何かユニットがある場合はマグマは吐けないのでリターン
        if($this->map->findUnitOn($pos) != null)
            return false;

        $leads[] = "NOTIF " . AppUtil::getText("TEXT_MAGMA_ROT");
        $leads[] = 'DELAY 200';

        // マップチップをマグマに変更する。
        $leads[] = $this->map->changeSquare($pos, 400);
        $leads[] = 'DELAY 200';

        return true;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * チップを岩に変更
     */
    private function changetips_normal(&$leads, $pos){
        //チップ番号400（マグマ）以外はリターン
        if($this->getGraph_no($pos) != 400)
            return false;

        //そこに何かユニットがある場合はリターン
        if($this->map->findUnitOn($pos) != null)
            return false;

        $leads[] = "NOTIF " . AppUtil::getText("TEXT_MAGMA_EAT");
        $leads[] = 'DELAY 200';

        // マップチップを岩に戻す。
        $leads[] = $this->map->changeSquare($pos, 101);
        $leads[] = 'DELAY 200';

        return true;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * そのポジションにあるマップチップのIDを得る
     */
    private function getGraph_no($pos){
        $structure = $this->map->getStructure();
        $maptips = $this->map->getMapTips();

        return $maptips[$structure[$pos[1]][$pos[0]]]["graph_no"];
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * トラップを作動させる
     */
    private function fireTrap(&$leads, $unit) {

        // ﾄﾗｯﾌﾟアイテムを自分のいるポイントで起動。
        $item = array(
          'item_id'=>0, 
          'item_name'=>AppUtil::getText("TEXT_MAGMA"), 
          'item_value'=>999, 
          'item_type'=>Item_MasterService::TACT_ATT,
          'item_vfx'=>1,
        );

        $this->fireItem($leads, $item, $unit->getPos(), $unit);

        // 無条件死亡
        $unit->collapse();

        return true;
    }

}
