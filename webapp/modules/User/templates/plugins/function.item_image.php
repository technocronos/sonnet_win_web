<?php

/**
 * パラメータで指定されたアイテム画像、ガチャ画像を<img>で出力する。
 *
 * パラメータ一覧)
 *     id           アイテムのID
 *     float        画像の回り込み指定。"left", "right" のいずれか。
 *     cat          画像の種別。以下のいずれかを指定する。
 *                      item        アイテム。省略可能
 *                      gacha       ガチャ
 *                      monster     モンスター
 *                      dictionary  辞典
 */
function smarty_function_item_image($params, $smarty) {

    // cat省略時は "item"
    if(!$params['cat'])  $params['cat'] = 'item';

    // モンスター画像、辞典画像の場合は指定されるIDはマイナス値なのでプラスに直す。
    if($params['cat'] == 'monster'  ||  $params['cat'] == 'dictionary')
        $params['id'] *= -1;

    // 画像ファイルのベース名を取得。アイテムで、汎用攻撃アイコンを使用する場合は "att"。
    // それ以外は先頭0詰めのID5桁。
    if($params['cat'] == 'item'  &&  3000 <= $params['id']  &&  $params['id'] <= 3999)
        $baseName = 'att';
    else
        $baseName = sprintf('%05d', $params['id']);

    // 出力
    return ViewUtil::getImageTag(array(
        'file' => "{$baseName}.gif",
        'cat' => $params['cat'],
        'float' => isset($params['float']) ? $params['float'] : null,
    ));
}
