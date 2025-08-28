<?php

/**
 * 数学関連の汎用的な関数を定義するクラス。
 */
class MathUtil {

    //-----------------------------------------------------------------------------------------------------
    /**
     * ランダムで0.0以上、1.0未満の値を返す。
     */
    public static function randFloat() {

        return (mt_rand() >> 1) / ((mt_getrandmax() >> 1) + 1);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 第一引数で指定された値を、第二引数で指定された割合の範囲で、ランダムに増減した値を返す。
     * 第二引数は小数で指定する。
     */
    public static function swingRandom($base, $width_rate) {

        $swing_width = (int)($base * $width_rate);

        return $base + mt_rand(-$swing_width, $swing_width);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された配列からランダムに要素を取得して、返す。
     */
    public static function randomDraw(&$array) {

        return $array[ array_rand($array) ];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された配列からランダムに要素を取得して、返す。
     * 取得した要素は削除される。
     */
    function randomPop(&$array) {

        // 要素が一つもない配列の場合はnullを返す。
        if( 0 == count($array) )
            return null;

        // ランダムに要素を選択。キーを得る
        $pick = array_rand($array);

        // 要素を削除して、リターン。
        $pop = $array[$pick];
        unset($array[$pick]);
        return $pop;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 配列で指定された割合で、特定要素の値を返す。
     * 例えば、次のような配列を指定したとき...
     *     array(
     *         0 => array('rate' => 10),
     *         1 => array('rate' => 15),
     *         2 => array('rate' => 20),
     *     )
     * 各値は次のような確率で返る。
     *     要素0    10 / (10 + 15 + 20)
     *     要素1    15 / (10 + 15 + 20)
     *     要素2    20 / (10 + 15 + 20)
     */
    public static function biasDraw($values) {

        // rate の値合計を求める
        $total = 0;
        foreach($values as $entry)
            $total += $entry['rate'];

        // 合計が変なのはカラ配列とか考えられるので、nullリターン。
        if($total == 0)
            return null;

        // 1～合計値でランダム値を取得。
        $pick = mt_rand(1, $total);

        // ランダムにしたがって値を一つ返す。
        foreach($values as $entry) {

            if($pick <= $entry['rate'])
                return $entry;

            $pick -= $entry['rate'];
        }

        // ここに来るのはエラー。
        throw new Exception('ここに制御は来ないはず');
    }
}
