<?php

/**
 * 砂漠蜘蛛の特殊処理を記述する
 */
class SphereOn41004 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    //protected $nextQuestId = 41005;

    public function progressTurnEnd(&$leads) {

        // コマンドと、コマンド対象になっているユニットを取得。
        $command = $this->state['command'];
        $commUnit = $this->getUnit();

		//子蜘蛛の場合
		if($commUnit->getProperty("character_id") == -10053){

			//攻撃しかけた後はネットを張らない
			if(!$command["attack"]){
				//移動の場合
				//pathは携帯のテンキーで表現されている　例：4488→左左下下
				if($command["move"]["path"]){
					$lastmove = substr($command["move"]["path"], -1);    // 最後に移動した方向を返す
					$x = $command["move"]["to"][0];
					$y = $command["move"]["to"][1];

					switch($lastmove){
						case 2:
							$this->changetips($leads, array($x, $y-1));
							break;
						case 4:
							$this->changetips($leads, array($x-1, $y));
							break;
						case 6:
							$this->changetips($leads, array($x+1, $y));
							break;
						case 8:
							$this->changetips($leads, array($x, $y+1));
							break;
					}
				}else{
					//動かない場合、どこかしらにネットを貼る
					$pos = $commUnit->getPos();
					$x = $pos[0];
					$y = $pos[1];

					if($this->getGraph_no(array($x, $y+1)) == 2)
						$this->changetips($leads, array($x, $y+1));
					else if($this->getGraph_no(array($x, $y-1)) == 2)
						$this->changetips($leads, array($x, $y-1));
					else if($this->getGraph_no(array($x+1, $y)) == 2)
						$this->changetips($leads, array($x+1, $y));
					else if($this->getGraph_no(array($x-1, $y)) == 2)
						$this->changetips($leads, array($x-1, $y));

				}
			}
		}else if($commUnit->getCode() == 'avatar'){
		//主人公の場合
			if($this->getGraph_no($command["move"]["to"]) == 515){
				//蜘蛛の巣を踏んだ
				$this->fireTrap($leads, $commUnit);
			}
		}

        return parent::progressTurnEnd($leads);
	}

	//チップを変更
	private function changetips(&$leads, $pos){

		//チップ番号2（土）以外はネットを張らない
		if($this->getGraph_no($pos) != 2)
			return false;

        $leads[] = "NOTIF " . AppUtil::getText("TEXT_SPIDER_NET");
        $leads[] = 'DELAY 200';

		// マップチップを蜘蛛の巣に変更する。
        $leads[] = $this->map->changeSquare($pos, 515);
        $leads[] = 'DELAY 200';

		return true;
	}

	//そのポジションにあるマップチップのIDを得る
	private function getGraph_no($pos){
		$structure = $this->map->getStructure();
		$maptips = $this->map->getMapTips();

		return $maptips[$structure[$pos[1]][$pos[0]]]["graph_no"];
	}

	//トラップを作動させる
    private function fireTrap(&$leads, $unit) {

        // ユーザの初回突入レベルを取得。
        $firstLevel = Service::create('Flag_Log')->getValue(Flag_LogService::FIRST_TRY, $this->info['user_id'], $this->info['quest_id']);

		//ユーザの初回突入レベル分消費させる
		$damage = $firstLevel / 2;

		// ﾄﾗｯﾌﾟアイテムを自分のいるポイントで起動。
        $item = array(
			'item_id'=>0, 
			'item_name'=> AppUtil::getText("TEXT_POISON_NET"), 
			'item_value'=>$damage, 
			'item_type'=>Item_MasterService::TACT_ATT,
			'item_vfx'=>3,
		);

        $this->fireItem($leads, $item, $unit->getPos(), $unit);

        $avatarNo = sprintf('%03d', $unit->getNo());

        $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireTrap_1", array("%avatar%"), array($avatarNo)));

		return true;
    }
}
