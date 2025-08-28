<?php

/**
 * 内容で指定されたHTMLの一番最後に float レイアウトを解除するタグを入れる。
 *
 * 例えば次のように、float レイアウトを行うのだが条件分岐などで一番最後の <br /> が不明なときに...
 *
 *      <img src="..." style="float:left" />
 *      {if $condtion1}condition1 is ON<br />{/if}
 *      {if $condtion2}condition2 is ON<br />{/if}
 *
 * 次のように記述する。
 *
 *      {float_block}
 *        <img src="..." style="float:left" />
 *        {if $condtion1}condition1 is ON<br />{/if}
 *        {if $condtion2}condition2 is ON<br />{/if}
 *      {/float_block}
 *
 * 一番最後の <br /> を検出してフロート解除のタグに差し替える。
 * 最後の<br />を検出できない場合は追加する。
 */
function smarty_block_float_block($params, $content, $smarty, $repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    // 一番最後の <br /> をフロート解除のタグに置き換える。
    if( preg_match('#<br(?=\b)[^>]*>\s*$#', $content, $matches, PREG_OFFSET_CAPTURE) ) {
        $brTag = str_replace('/>', '/><div style="clear:both"></div>', str_replace('<br', '<br clear="all" ', $matches[0][0]));
        return substr($content, 0, $matches[0][1]) . $brTag;

    // 一番最後に <br /> がない場合はタグを追加する。
    }else {
        return $content . '<br clear="all" /><div style="clear:both"></div>';
    }
}
