<?php

/**
 * パラメータで指定されたキャラクター画像を表示する <img> を出力する。
 *
 * パラメータ一覧)
 *     charaId      キャラクターID。"chara" パラメータを指定するなら省略可能。
 *     chara        Character_InfoService::getExRecord で取得したキャラクター情報。
 *                  "charaId" パラメータを指定するなら省略可能。
 *     size         大きさ。以下のいずれかで指定する。
 *                      full    フルサイズ。デフォルト。
 *                      nail    サムネイルサイズ
 *     floatLeft    cssの "float:left" を指定するかどうか。
 */
function smarty_function_chara_img($params, $smarty) {

    // chara パラメータが省略されている場合は charaId から取得する。
    if(empty($params['chara']))
        $params['chara'] = Service::create('Character_Info')->needExRecord($params['charaId']);

    // size パラメータが省略されている場合は "full"。
    if(empty($params['size']))
        $params['size'] = 'full';

    // キャラ情報から画像に必要な情報のみを文字列で取得。
    $spec = CharaImageUtil::getSpec($params['chara']);

    // イメージURLを決定。
    $imgUrl = sprintf('%simg/chara/%s.%s.gif', APP_WEB_ROOT, $spec, $params['size']);

    // width と height を取得。
    if($params['size'] == 'full') {
        $width = '80';
        $height = '100';
    }else {
        $width = '60';
        $height = '48';
    }

    if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
      $width = $width * 1.5;
      $height = $height * 1.5;
    }

    // cssの "float:left" を出すかどうかを決定。
    if(!empty($params['float'])) {
        $floatStyle = 'float:'.$params['float'];
        $alignAttr = sprintf('align="%s"', $params['float']);
    }else {
        $floatStyle = '';
        $alignAttr = '';
    }

    // リターン。
    return sprintf(
        '<img src="%s" style="%s" %s alt="キャラ" width="%s" height="%s" />',
        htmlspecialchars($imgUrl, ENT_QUOTES), $floatStyle, $alignAttr, $width, $height
    );
}
