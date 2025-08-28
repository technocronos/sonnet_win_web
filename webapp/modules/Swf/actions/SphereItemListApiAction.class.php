<?php

/**
 * フィールドクエストで、ユーザのコマンドを受け付けるアクション。
 */
class SphereItemListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

          // 指定されたスフィアの情報をロード。
          $record = Service::create('Sphere_Info')->needRecord($params['id']);

          // 他人のスフィアならエラー。
          if($record['user_id'] != $this->user_id)
              throw new MojaviException('他人のスフィアでコマンドを実行しようとした');

          // バリデーションコードが合わないならエラー。
          if($record['validation_code'] != $params['code'])
              throw new MojaviException('バリデーションコードが合わない');

          // スフィアを制御するオブジェクトを作成。
          $sphere = SphereCommon::load($record);

          $unit = $sphere->getUnit();
          $unit_items = $unit->getProperty('items');
//Common::varLog($unit_items);

          $itemTable = $sphere->getItemTable();
//Common::varLog($itemTable);
          // スフィアのアイテムマップにある user_item レコードをすべてロードして、
          // user_item_id をキー、item_id を値とする配列を取得する。
          $uitems = Service::create('User_Item')->getRecordsIn(array_keys($itemTable), false);
          $uitems = ResultsetUtil::colValues($uitems, 'item_id', 'user_item_id');

          // アイテムマップにあるアイテムレコードをすべてロード。
          $items = Service::create('Item_Master')->getRecordsIn($uitems);

          // item_master.item_type に対応する SWF での値。
          static $ITEM_TYPE_MAP = array(
              Item_MasterService::RECV_HP =>  'recov',
              Item_MasterService::TACT_ATT => 'damag',
          );

          // アイテムマップを一つずつ見ていく。
          $master = array();
          foreach($unit_items as $slot => $uitemId) {
                // item_master のレコードを取得。
                $item = $items[ $uitems[$uitemId] ];

                if($item["item_id"] == null)
                    continue;

                // SWFに渡すアイテム情報を作成。
                //$item_string = $sphere->getItemDataSpec($item);
                //$item["string"] = $item_string;

                $item["slot"] = $slot;

                foreach($itemTable as $uitemId2 => $itemNo) {
                    if($uitemId == $uitemId2){
                        $item["item_no"] = $itemNo;
                        continue;
                    }
                }

                $itemtype = array_key_exists($item['item_type'], $ITEM_TYPE_MAP) ? $ITEM_TYPE_MAP[$item['item_type']] : 'noeff';

                // 使用時の効果がある、あるいは
                // 装備していないアイテムであるなら、使用可能。
                if($item['category'] == "ITM"){
                    if($itemtype != "noeff"){
                        $item["useable"] = true;
                    }else{
                        $item["useable"] = false;
                    }
                }else{
                    $item["useable"] = true;
                }

                $master[] = $item;
          }

          // 指定されているキャラクタを取得。
          $avatar = Service::create('Character_Info')->needAvatar($this->user_id, true);
          $chara = Service::create('Character_Info')->needExRecord($avatar['character_id']);

          foreach($chara['equip'] as $mountId => $equip){
              $uitemId = $equip["user_item_id"];
              // item_master のレコードを取得。
              $item = $items[ $uitems[$uitemId] ];
              $item["useable"] = false;

              $master[] = $item;
          }

          // レスポンス内容をリターン。
          return $master;
    }
}
