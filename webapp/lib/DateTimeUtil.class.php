<?php

/**
 * 日付・日時に関する汎用ユーティリティ。
 */
class DateTimeUtil {

    public static $JAPANESE_DAYS_OF_WEEK = array('日', '月', '火', '水', '木', '金', '土');

    //-----------------------------------------------------------------------------------------------------
    /**
     * PHP標準のdate関数の拡張。次の点が異なる。
     *     ・フォーマット文字列の "k" を日本語の曜日("日", "月" 等)に置き換える。
     *     ・第2引数はタイムスタンプだけでなく、日付変換可能な文字列でも良い。
     *     ・第3引数にタイムスタンプがNULL、あるいはカラ文字だった場合の値を指定できる。
     */
    public static function dateEx($format, $timestamp = false, $ifNull = null) {

        // 第二引数省略時は現在時。
        if($timestamp === false)
           $timestamp = time();

        // 第二引数がNULL、あるいはカラ文字だった場合は第三引数をリターン。
        if( '' == (string)$timestamp )
            return $ifNull;

        // 第二引数をタイムスタンプに統一。
        $timestamp = self::normalize($timestamp);

        // フォーマット文字列のkを曜日に置き換える。
        $format = str_replace('k', self::$JAPANESE_DAYS_OF_WEEK[idate('w', $timestamp)], $format);

        // あとは標準のdate関数に任せる。
        return date($format, $timestamp);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定の日付に指定の間隔を足し引きしたものを返す。
     *
     * @param string    足し引きする間隔。strtotimeに指定可能な形式。
     * @param mixed     基準になる日時。タイムスタンプか、それに変換可能な文字列。
     * @param string    戻り値をフォーマットする場合はその書式。省略した場合はタイムスタンプのまま返る。
     * @return mixed    指定の間隔を足し引きした日時。
     */
    public static function add($quantity, $baseTime = false, $format = false) {

        // 基準日時省略時は現在時。
        if($baseTime === false)
           $baseTime = time();

        // 基準日時をタイムスタンプに統一。
        $result = strtotime($quantity, self::normalize($baseTime));

        // フォーマットが指定されていないならタイムスタンプのまま、指定されているならその通り返す。
        if($format === false)
            return $result;
        else
            return self::dateEx($format, $result);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された日付を誕生日として、満年齢を返す。
     * 第2引数には、誕生日がNULLだった場合に返してほしい値を指定できる。
     */
    public static function getAge($birthday, $ifNull = null) {

        if( is_null($birthday) )
            return $ifNull;

        // 現在日時と誕生日をYYYYMMDDの8桁の数値にして引き算、下4桁を切り捨てたものが年齢になる。
        return (int)floor( (date('Ymd') - self::dateEx('Ymd', $birthday)) / 10000 );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された年月の最終日を数値で返す。
     * 月に13以上の数値を指定した場合は年の数値が増やされる。
     *
     * @param int     月
     * @param int     年
     * @return int    指定年月の最終日
     */
    public static function monthLastDay($month, $year = false) {

        // 引数正規化。
        if(!$year)
            $year = idate('Y');

        return idate( 'd', mktime(0, 0, 0, $month + 1, 0, $year) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に受け取った値をタイムスタンプに変換して返す。
     * 日時らしき文字列を受け取った場合はタイムスタンプに変換し、整数を受け取った場合はそのまま返す。
     * 解析処理に重点のあるparseメソッドと違い、問題ないと分かっている日付・時刻値をタイムスタンプに
     * 統一するのが主眼。
     *
     * @param mixed     確実にタイムスタンプにしたい値。
     * @param mixed     0に評価される値だった場合に返したい値。
     * @return int      タイムスタンプ。変換できなかった場合はfalse。
     */
    public static function normalize($mixed, $ifEmpty = 0) {

        // 0に評価される値の場合は指定の値を返す。
        if(0 == $mixed)
            return $ifEmpty;

        // 数値として評価できる値はそのまま返す。
        if( is_numeric($mixed) )
            return $mixed;

        // 後続でstrtotimeに任せるが、"xx/xx/xx" の形式が "月/日/年" でパースされるので、
        // その形式がある場合は入れ替えておく。
        $mixed = preg_replace('#(^|\D)(\d{1,2})/(\d{1,2})/(\d{1,2})(\D|$)#', '$1$3/$4/$2$5', $mixed);
        return strtotime($mixed);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に受け取った文字列を日付・時刻表現として解析し、タイムスタンプに変換して返す。
     * ほぼstrtotimeのラッパであるnormalizeと違い、さまざまな表現を解析しようと努力する。
     * また、解析できなかった場合に、その理由を返す。
     * 主に、ユーザが入力した日付・時刻を処理する用途で使用する。
     *
     * @param string    解析したい日付・時刻表現。
     * @param array     時刻部分がない場合のデフォルト時刻。
     *                  "HH:MM:SS" の形式で指定する。省略時は 00:00:00。
     * @param array     日付部分がない場合のデフォルト日。
     *                  "YYYY/MM/DD" の形式で指定する。省略時は現在日。
     * @return mixed    解析に成功した場合はタイムスタンプ値。
     *                  失敗した場合は以下の文字列のいずれか。
     *                      cannot_parse    日付・時刻表現として解析できない。
     *                      invalid_date    存在しない日付。
     *                      invalid_time    存在しない時刻。
     */
    public static function parse($text, $timeDefault = '00:00:00', $dateDefault = false) {

        // 日付の区切り文字がない場合に、数字文字の4,6,8桁の並びを日付として解析できるようにする。
        if(false == strpos($text, '/')  &&  false == strpos($text, '-'))
            $text = preg_replace_callback('/(^|\D)(\d{2}|\d{4})?(\d{2})(\d{2})(\D|$)/', array(__CLASS__, 'parse_replaceCallback'), $text);

        // 日付がない場合はデフォルト値を補う。
        if(false == strpos($text, '/')  &&  false == strpos($text, '-')) {
            if($dateDefault === false) $dateDefault = date('Y/m/d');
            $text = $dateDefault . ' ' . $text;
        }

        // 時刻がない場合はデフォルト値を補う。
        if(false == strpos($text, ':')) {
            $text .= ' ' . $timeDefault;
        }

        // "YYYY/MM/DD HH:MM:SS" 形式として解析するが...
        //     ・年が省略されていても良い
        //     ・年が2桁でも良い
        //     ・日付の区切り文字は "-" でも良い
        //     ・時刻はなくても良い
        //     ・秒はなくても良い
        //     ・区切り文字の前後に空白があっても良い
        // とする。
        $matchResult = preg_match(
              '#^\s*(?:(?:(\d+)\s*[/\-]\s*)?(\d+)\s*[/\-]\s*(\d+))?(?:\s+(\d+)\s*:\s*(\d+)(?:\s*:\s*(\d+))?)?\s*$#'
            , $text
            , $match
        );

        // 解析できなかったらエラーリターン。
        if( !$matchResult )
            return 'cannot_parse';

        // この時点で変数 $match は、第一要素から順に、年・月・日・時・分・秒 を格納していることになる。

        // 年が省略されている場合は現在年とする。
        if($match[1] == '')
            $match[1] = idate('Y');

        // 年が2桁以下の場合は 1900 か 2000 を補う。
        if($match[1] < 100)
            $match[1] += ($match[1] >= 70) ? 1900 : 2000;

        // 秒が省略されている場合は 0 秒とする。
        if(!isset($match[6]))
            $match[6] = 0;

        // 無効な日付かどうかチェック。
        if( !checkdate($match[2], $match[3], $match[1]) )
            return 'invalid_date';

        // 時・分・秒の範囲チェック。前述の正規表現から、下限チェックは必要ない。
        if($match[4] > 23  ||  $match[5] > 59  ||  $match[6] > 59)
            return 'invalid_time';

        // ここまでくればOK。タイムスタンプに変換してリターン。
        return mktime($match[4], $match[5], $match[6], $match[2], $match[3], $match[1]);
    }

    // parse のヘルパ関数。
    private static function parse_replaceCallback($match) {
        return $match[1] . ($match[2]=='' ? '' : $match[2].'/') . $match[3].'/'.$match[4].$match[5];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に受け取った文字列を年月表現として解析し、その1日のタイムスタンプに変換して返す。
     * 解析できなかった場合は、その理由を返す。
     * 主に、ユーザが入力した年月表現を処理する用途で使用する。
     *
     * @param string    解析したい年月表現。
     * @param int       年が省略されている場合のデフォルト値。省略時は現在年。
     * @return mixed    解析に成功した場合は、入力された年月の1日のタイムスタンプ値。
     *                  失敗した場合は以下の文字列のいずれか。
     *                      cannot_parse    年月表現として解析できない。
     *                      invalid_year    範囲外の年。
     *                      invalid_month   存在しない月。
     */
    public static function parseYm($text, $yearDefault = false) {

        // 日付の区切り文字がない場合に、数字文字の4,6桁の並びを年月として解析できるようにする。
        if(false == strpos($text, '/')  &&  false == strpos($text, '-'))
            $text = preg_replace('/(^|\D)(\d{2}|\d{4})(\d{2})(\D|$)/', '$1$2/$3$4', $text);

        // "YYYY/MM" 形式として解析するが...
        //     ・年が省略されていても良い
        //     ・年が2桁でも良い
        //     ・日付の区切り文字は "-" でも良い
        //     ・区切り文字の前後に空白があっても良い
        // とする。
        $matchResult = preg_match(
              '#^\s*(?:(\d+)\s*[/\-]\s*)?(\d+)\s*$#'
            , $text
            , $match
        );

        // 解析できなかったらエラーリターン。
        if( !$matchResult )
            return 'cannot_parse';

        // 年が省略されている場合は引数で指定された値とする。
        if($match[1] == '')
            $match[1] = ($yearDefault === false) ? idate('Y') : $yearDefault;

        // 年が2桁以下の場合は 1900 か 2000 を補う。
        if($match[1] < 100)
            $match[1] += ($match[1] >= 70) ? 1900 : 2000;

        // 無効な年かどうかチェック。
        if($match[1] < 1902  ||  2037 < $match[1])
            return 'invalid_year';

        // 無効な月かどうかチェック。
        if($match[2] < 1  ||  12 < $match[2])
            return 'invalid_month';

        // ここまでくればOK。タイムスタンプに変換してリターン。
        return mktime(0, 0, 0, $match[2], 1, $match[1]);
    }


    //-------------------------------------------------------------------------------------------------------
    /**
     * 指定された年・月のカレンダを返す。
     *
     * @param int     年
     * @param int     月
     * @return array  指定された月のカレンダを表す配列。次のような構造を持つ。
     *                    ['year'] => 指定された年
     *                    ['month'] => 指定された月
     *                    ['weeks'] => [0] => [0] => array('day'=>日, 'dayOfWeek'=>'sun', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)  // 第一週
     *                                        [1] => array('day'=>日, 'dayOfWeek'=>'mon', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)
     *                                        [2] => array('day'=>日, 'dayOfWeek'=>'tue', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)
     *                                        [3] => array('day'=>日, 'dayOfWeek'=>'wed', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)
     *                                        [4] => array('day'=>日, 'dayOfWeek'=>'thu', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)
     *                                        [5] => array('day'=>日, 'dayOfWeek'=>'fri', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)
     *                                        [6] => array('day'=>日, 'dayOfWeek'=>'sat', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)
     *                                 [1] => [0] => array('day'=>日, 'dayOfWeek'=>'sun', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)  // 第二週
     *                                        [1] => array('day'=>日, 'dayOfWeek'=>'mon', 'header'=>第一週のカラセルかどうか, 'trailer'=>最終週のカラセルかどうか)
     *                                        ...
     * 例) 2010/06月
     *     カレンダ
     *                1  2  3  4  5
     *          6  7  8  9 10 11 12
     *          ...
     *          27 28 29 30
     *     戻り値
     *          ['year'] =>  2010
     *          ['month'] => 6
     *          ['weeks'] => [0] => [0] => array('day'=>'', 'dayOfWeek'=>'sun', 'header'=>true,  'trailer'=>false)
     *                              [1] => array('day'=>'', 'dayOfWeek'=>'mon', 'header'=>true,  'trailer'=>false)
     *                              [2] => array('day'=> 1, 'dayOfWeek'=>'tue', 'header'=>false, 'trailer'=>false)
     *                              [3] => array('day'=> 2, 'dayOfWeek'=>'wed', 'header'=>false, 'trailer'=>false)
     *                              [4] => array('day'=> 3, 'dayOfWeek'=>'thu', 'header'=>false, 'trailer'=>false)
     *                              [5] => array('day'=> 4, 'dayOfWeek'=>'fri', 'header'=>false, 'trailer'=>false)
     *                              [6] => array('day'=> 5, 'dayOfWeek'=>'sat', 'header'=>false, 'trailer'=>false)
     *                       [1] => [0] => array('day'=> 6, 'dayOfWeek'=>'sun', 'header'=>false, 'trailer'=>false)
     *                              [1] => array('day'=> 7, 'dayOfWeek'=>'mon', 'header'=>false, 'trailer'=>false)
     *                               ...
     *                       [4] => [0] => array('day'=>27, 'dayOfWeek'=>'sun', 'header'=>false, 'trailer'=>false)
     *                              [1] => array('day'=>28, 'dayOfWeek'=>'mon', 'header'=>false, 'trailer'=>false)
     *                              [2] => array('day'=>29, 'dayOfWeek'=>'tue', 'header'=>false, 'trailer'=>false)
     *                              [3] => array('day'=>30, 'dayOfWeek'=>'wed', 'header'=>false, 'trailer'=>false)
     *                              [4] => array('day'=>'', 'dayOfWeek'=>'thu', 'header'=>false, 'trailer'=>true)
     *                              [5] => array('day'=>'', 'dayOfWeek'=>'fri', 'header'=>false, 'trailer'=>true)
     *                              [6] => array('day'=>'', 'dayOfWeek'=>'sat', 'header'=>false, 'trailer'=>true)
     */
    private function getCalendar($year, $month) {

        // 曜日の値。
        $DAYOFWEEKS = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');

        // 戻り値初期化。
        $result = array('year'=>$year, 'month'=>$month, 'weeks'=>array());

        // 指定月の1日のタイムスタンプを取得。
        $monthTimestamp = mktime(0, 0, 0, $month, 1, $year);

        // 1日の曜日と最終日を取得。
        $firstDayOfWeek = idate('w', $monthTimestamp);
        $lastDay = idate('t', $monthTimestamp);

        // $dayOfWeekで曜日を指しながら、$dayで日にちを指しながらカレンダを作成していく。
        $dayOfWeek = 0;
        $day = 1;
        for($day = 1 ; $day <= $lastDay ; ) {

            // 週はじめの場合はそれまでの週を weeks に追加して、$week を初期化する。
            if($dayOfWeek == 0) {
                if(isset($week))
                    $result['weeks'][] = $week;
                $week = array();
            }

            // $week にセルを追加。
            if($day == 1    &&    $dayOfWeek < $firstDayOfWeek) {
                $week[] = array('day'=>'',   'dayOfWeek'=>$DAYOFWEEKS[$dayOfWeek], 'header'=>true,  'trailer'=>false);
            }else {
                $week[] = array('day'=>$day, 'dayOfWeek'=>$DAYOFWEEKS[$dayOfWeek], 'header'=>false, 'trailer'=>false);
                $day++;
            }

            // 曜日を進行。7に達したら0にリセット。
            if(++$dayOfWeek >= 7)
                $dayOfWeek = 0;
        }

        // 月の終わりのカラセルを追加して、最終週をweeksに追加する。。
        for( ; $dayOfWeek < 7 ; $dayOfWeek++)
            $week[] = array('day'=>'', 'dayOfWeek'=>$DAYOFWEEKS[$dayOfWeek], 'header'=>false, 'trailer'=>true);
        $result['weeks'][] = $week;

        // リターン。
        return $result;
    }
}
