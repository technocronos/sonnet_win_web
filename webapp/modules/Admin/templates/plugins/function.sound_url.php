<?php

/**
 * パラメータで指定されたサウンドのURLを返す。
 */
function smarty_function_sound_url($params, $smarty) {

    if($params["containar"] == 1){
        return Common::adaptUrl(APP_WEB_ROOT . "sound/" . $params["file"] . "." . $params["ext"]);
    }

    return APP_WEB_ROOT . "sound/" . $params["file"] . "." . $params["ext"];
}
