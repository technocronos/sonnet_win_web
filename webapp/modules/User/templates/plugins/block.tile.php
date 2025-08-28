<?php

/**
 * 内容で指定されたHTMLを縦に隙間なく並べるレイアウトを行う。
 *
 * 次のHTMLを縦に隙間なく並べるには...
 *     <img src="top.jpg" /><br />
 *     <div style="background-color:#333333">あああああ</div>
 *     <img src="foot.jpg" /><br />
 *
 * 次のように記述する。
 *     {tile}
 *       <part><img src="top.jpg" /></part>
 *       <part style="background-color:#333333">あああああ</part>
 *       <part><img src="foot.jpg" /></part>
 *     {/tile}
 *
 * 注) PCの場合、line-height や vertical-align の問題があってうまくいかない。
 *     <part> は単純に <div> に置き換えられるので、それを念頭に置きながら、次のことに気をつける。
 *     ・<img> のstyleで vertical-align:bottom を指定する。
 *          ⇒これがないと、イメージの下端がベースラインに揃えられるため、ベースラインからボトムライン
 *            までの空白が生じる。
 *     ・高さが１行の高さに満たない <img> のみを含む <part> はstyleで line-height:1px を指定する。
 *          ⇒これがないと、PCブラウザでは最低でも１行分の高さが確保されるため、空白が生じる。
 */
function smarty_block_tile($params, $content, $smarty, $repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    // キャリアによって分岐して、特殊タグ <part> を置き換える。
    if(FPhoneUtil::getCarrier() == 'au') {
        $content = preg_replace('#<part(\s+[^>]*)?>#', '<tr><td $1>', $content);
        $content = preg_replace('#</part>#', '</td></tr>', $content);
        return '<table border="0" cellspacing="0" cellpadding="0">' . $content . '</table>';
    }else {
        $content = preg_replace('#<part(\s+[^>]*)?>#', '<div $1>', $content);
        $content = preg_replace('#</part>#', '</div>', $content);
        return $content;
    }
}
