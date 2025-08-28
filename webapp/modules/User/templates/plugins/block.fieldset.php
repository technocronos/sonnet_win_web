<?php

/**
 * 内容で指定されたHTMLを<table>タグを使って次のように枠で囲む。
 *      ┌─────────────┐
 *      │ヘッダ                    │
 *      ├─────────────┤
 *      │内容                      │
 *      │                          │
 *      └─────────────┘
 *
 * 使用するときは次のように <legend> タグでヘッダを囲む。
 *      {fieldset}
 *        <legend>ヘッダヘッダヘッダ</legend>
 *        内容内容内容内容内容内容内容<br />
 *        内容内容内容内容内容内容内容<br />
 *        内容内容内容内容内容内容内容<br />
 *      {/fieldset}
 *
 * 見出しがない場合は <legend> タグ自体を省略する。
 *
 * パラメータ)
 *     width    横幅。ディスプレイに対する割合で "80%" などのように指定する。
 *     color    枠全体の色テーマ。現在サポートしているのは "blue", "red", "yellow", "pink", "black"。省略時は "blue"
 */
function smarty_block_fieldset($params, $content, $smarty, $repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    // 省略されているパラメータを補う。
    if(is_null($params['width'])) $params['width'] = '100%';
    if(is_null($params['color'])) $params['color'] = 'blue';

    // 幅を取得。
    $width = $params['width'];

    // 各色を取得する。
    switch($params['color']) {
        case 'blue':
            $borderColor = "#0080e6";
            $legendBgColor = "#334d99";
            $bodyBgColor = "#001a66";
            break;
        case 'red':
            $fontColor = "#ffffff";
            $borderColor = "#990000";
            $legendBgColor = "#4d0000";
            $bodyBgColor = "#33001a";
            break;
        case 'yellow':
            $fontColor = "#ffffff";
            $borderColor = "#999900";
            $legendBgColor = "#4d4d00";
            $bodyBgColor = "#33331a";
            break;
        case 'pink':
            $borderColor = "#FF9999";
            $legendBgColor = "#B36699";
            $bodyBgColor = "#661A4D";
            break;
        case 'black':
            $fontColor = "#ffffff";
            $borderColor = "#767676";
            $legendBgColor = "#1B1B1B";
            $bodyBgColor = "#C7ABB9";
            break;
        case 'brown':
            $fontColor = "#ffffff";
            $borderColor = "#767676";
            $legendBgColor = "#1B1B1B";
            $bodyBgColor = "#C7ABB9";
            break;
    }

    // 見出しと本体とを分ける。
    $matched = preg_match('#<legend>(.*?)</legend>#s', $content, $matches, PREG_OFFSET_CAPTURE);
    if($matched) {
        $legend = $matches[1][0];
        $content = substr($content, $matches[0][1] + strlen($matches[0][0]));
    }else {
        $legend = null;
    }

    // 見出し部分のHTMLを作成。
    if( isset($legend) )
        $header = '<tr><td style="background-color:'.$legendBgColor.'">'.'<span style="color: '.$fontColor.'">'.$legend.'</span>'.'</td></tr>';

    //スマホ以外の場合は無視する
    if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone") 
        return $content;

    // リターン。
    return <<<HDOC
      <div style="text-align:center;">
        <table style="width:{$width}; margin:0 auto;">
          {$header}
          <tr><td style="background-color:{$bodyBgColor};border:1px solid {$borderColor};">
            {$content}
          </td></tr>
        </table>
      </div>
HDOC;

}
