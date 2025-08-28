<?php

/**
 *
 *
 * パラメータ一覧)
 */
function smarty_function_item_level_max($params, $smarty) {

    $maxLv = Service::create('Item_Level_Master')->getMaxLevel($params['uitem']['item_id']);

    if($maxLv <= $params['uitem']['level']){
        return '[MAX]';
    }else{
		//$nextLv = Service::create('Item_Level_Master')->getRecord($params['uitem']['item_id'], ($params['uitem']['level'] + 1));
        //return "[" . $params['uitem']['item_exp'] . "/" . $nextLv["exp"] . "]";
		return "";
	}
}
