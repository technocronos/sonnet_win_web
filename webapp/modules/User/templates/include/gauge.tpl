{*
    ゲージを表示するテンプレート。

    パラメータ)
        value   ゲージの値
        max     ゲージの最大値
        type    ゲージの種類。以下のいずれか。
                    AP    行動pt
                    MP    行動pt
                    HP    HP
                    EXP   経験値
        float   ゲージ画像にfloat属性を付ける場合に、"left" か "right" で指定する。

*}{php}

    // 実際のゲージの種類を決める。
    switch( $this->get_template_vars('type') ) {
        case 'AP':   $gauge = 'normal';   break;
        case 'MP':   $gauge = 'normal';   break;
        case 'HP':   $gauge = 'normal';   break;
        case 'EXP':  $gauge = 'normal';   break;
    }
    $this->assign('gauge', $gauge);

    // ゲージの長さを100分率で求める。
    if( $this->get_template_vars('max') ) {
        $length = floor($this->get_template_vars('value') / $this->get_template_vars('max') * 100);
        $this->assign('length', sprintf('%03d', floor($length/4)*4));
    }else {
        $this->assign('length', '000');
    }


{/php}{image_tag cat="parts/gauge/`$gauge`" file="`$length`.gif" float=`$float`}