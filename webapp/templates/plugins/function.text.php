<?php

/**
 * IDからテキストを取得して返す。
 *
 * パラメータ一覧)
 *     いずれかを指定する。
 *     id       テキストID
 *     grade    階級ID
 */
function smarty_function_text($params, &$smarty) {

    if( isset($params['id']) )
        return Text_LogService::get($params['id']);
    else if( isset($params['grade']) )
        return Grade_MasterService::name($params['grade']);
}
