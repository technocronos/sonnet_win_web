<?php

/**
 * パラメータで指定された画像を<img>で出力する。
 * ViewUtil::getImageTag() のSmarty用インターフェースなので、パラメータ等はそちらを参照。
 */
function smarty_function_image_tag($params, $smarty) {

    return ViewUtil::getImageTag($params);
}
