<?php

/**
 * プラットフォームのユーザサムネイルを<img>で表示する。
 *
 * パラメータ一覧)
 *     id       ユーザID。省略時は現在アクセス中のユーザのID。
 *              srcパラメータを指定した場合は無視される。
 *     src      サムネイルのURLが分かっているのなら、ここで指定する。
 *     size     サムネイルのサイズ。"M" か "L"。省略時は "L"。
 *     float    画像の回り込み指定。"left", "right" のいずれか。
 */
function smarty_function_platform_thumbnail($params, $smarty) {

    // 各プラットフォームにおける、画像サイズ。
    static $IMAGE_INFO = array(
        'mbga' => array(
            'M' => array(30, 40),
            'L' => array(60, 80),
        ),
        'gree' => array(
            'M' => array(48, 48),
            'L' => array(48, 76),
        ),
        'mixi' => array(
            'M' => array(48, 48),
            'L' => array(76, 76),
        ),
    );

    // 省略されているパラメータを補う。
    $params += array('size'=>'L', 'id'=>$smarty->get_template_vars('userId'));

    // srcパラメータが省略されている場合はidから取得する。
    if(empty($params['src']))
        $params['src'] = Service::create('User_Thumbnail')->getThumbnail($params['id'], $params['size']);

    // floatパラメータを処理するHTMLの属性を取得。
    if(!empty($params['float']))
        $floatAttr = sprintf('style="float:%s" align="%s"', $params['float'], $params['float']);
    else
        $floatAttr = '';

    // width, height 属性の出力を決める。
    $info = $IMAGE_INFO[PLATFORM_TYPE][ $params['size'] ];
    $sizeAttr = $info ? sprintf('width="%s" height="%s"', $info[0], $info[1]) : '';

    // 出力。
    return sprintf('<img src="%s" alt="" %s %s />', $params['src'], $floatAttr, $sizeAttr);
}
