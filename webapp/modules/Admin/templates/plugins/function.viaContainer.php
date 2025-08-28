<?php

/**
 * パラメータで指定されたアクションへのURLを返す。
 * パラメータ仕様については Common::genURL を参照。
 * パラメータを配列で指定したい場合は _params パラメータで指定する。
 */
function smarty_function_viaContainer($params, $smarty) {

    // "_params" パラメータだけは処理しておく。
    if(isset($params['_params'])  &&  is_array($params['_params'])) {
        $params += $params['_params'];
        unset($params['_params']);
    }

    return Common::viaContainer($params);
}
