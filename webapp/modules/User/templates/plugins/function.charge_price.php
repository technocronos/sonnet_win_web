<?php

/**
 * プラットフォームによる課金の値段を表示する。
 *
 * パラメータ)
 *      price       値段の値
 *      currency    省略可能。ゲーム内通貨で表示する場合は "gold" を指定する。
 */
function smarty_function_charge_price($params, $smarty) {

    if($params['currency'] == 'gold') {
        return sprintf(
            '<span style="color:%s">%s</span><span style="color:%s">%s</span>'
          , $smarty->get_config_vars('statusValueColor')
          , $params['price']
          , $smarty->get_config_vars('statusNameColor')
          , GOLD_NAME
        );
    }

    switch(PLATFORM_TYPE) {
        case 'mixi':
            return sprintf(
                '%s<span style="color:%s">%s</span><span style="color:%s">pt</span>'
              , ViewUtil::getImageTag(array('file'=>'mixipoint_small.gif'))
              , $smarty->get_config_vars('statusValueColor')
              , $params['price']
              , $smarty->get_config_vars('statusNameColor')
            );
        default:
            return sprintf(
                '<span style="color:%s">%s</span><span style="color:%s">%s</span>'
              , $smarty->get_config_vars('statusValueColor')
              , $params['price']
              , $smarty->get_config_vars('statusNameColor')
              , PLATFORM_CURRENCY_NAME
            );
    }
}
