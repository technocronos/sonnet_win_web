<?php

/**
 * 引数に受け取った数値を横棒グラフとともに出力するHTMLを出力する。
 * スタイルシートで、クラス "graph", "graphBar", "graphNobar", "graphNumber" が
 * 定義されている必要がある。
 *
 * 引数一つ目はグラフのスケール。
 * 引数二つ目は数値を出力するときのフォーマット。
 */
function smarty_modifier_bar_graph($value, $scale = 0, $format = null) {

    // 引数正規化。
    if(!is_numeric($value)) $value = 0;
    if($scale == 0) $scale = 100;
    if(is_null($format)) $format = '%s';

    // バーの長さを100分率で求める。
    $barLength = $value / $scale * 100;
    if($barLength > 100)  $barLength = 100;

    // バーのcssクラスを決定。
    $barStyle = $value ? 'graphBar' : 'graphNobar';

    // 数値をフォーマットにしたがって文字列に。
    $value = sprintf($format, $value);

    // 出力。
    return <<<HDOC
      <div class="graph">
        <div class="{$barStyle}" style="width:{$barLength}%"></div>
        <div class="graphNumber">{$value}</div>
      </div>
HDOC;
}
