<?php

/**
 * 置換文字列(プレースホルダ)を含んだテンプレートを展開したいときに使うユーティリティクラス。
 * たとえば次のようなテンプレートの...
 *
 *     こんにちは。{name}さん。
 *     ただいま {time} です。
 *
 * {} で囲まれた部分を特定の値で展開したいとき等に用いる。
 *
 * 使用例1) テンプレートと置換文字列を指定して展開。
 *
 *     TemplateExpander::execute('abc is {abc}. def is {def}.', array('abc'=>'def', 'def'=>'ghi'));
 *     // 「abc is def. def is ghi.」という文字列が返る
 *
 * 使用例2) 同じテンプレートを、置換文字列を変更して展開。
 *
 *     $expander = new TemplateExpander('abc is {abc}. def is {def}.');
 *
 *     $expander->expand( array('abc'=>'def', 'def'=>'ghi') );
 *     // 「abc is def. def is ghi.」という文字列が返る
 *
 *     $expander->expand( array('abc'=>'ABC', 'def'=>'DEF') );
 *     // 「abc is ABC. def is DEF.」という文字列が返る
 *
 * 使用例3) コールバックを使用して、置換文字列を動的に決定する。
 *
 *     function replace_callback($holderName) {
 *         if($holderName == 'time')
 *             return date('H:i');
 *         else
 *             return null;
 *     }
 *
 *     $expander = new TemplateExpander('abc is {abc}. def is {def}. time is {time}.');
 *     $expander->replaceCallback = 'replace_callback';
 *     $expander->expand( array('abc'=>'ABC', 'def'=>'DEF') );
 *     // 「abc is ABC. def is DEF. time is 12:00.」(12:00はそのときの時間)という文字列が返る
 *
 * 使用例4) プレースホルダをデフォルトのものから変える。
 *
 *     $expander = new TemplateExpander('abc is [:abc:]. def is [:def:].');
 *     $expander->placeHolder = '/\[:(\S+):\]/';
 *     $expander->expand( array('abc'=>'def', 'def'=>'ghi') );
 *     // 「abc is def. def is ghi.」という文字列が返る
 */
class TemplateExpander {

    // public プロパティ
    //=====================================================================================================

    /**
     * 展開対象のテンプレート。
     * privateになっているが、PHPオーバーロードでアクセスできるようになっている。
     */
    private $template = '/\{(\S+)\}/';

    /**
     * プレースホルダにマッチする正規表現。ホルダ名の部分がサブマッチになるようにする。
     * privateになっているが、PHPオーバーロードでアクセスできるようになっている。
     */
    private $placeHolder = '/\{(\S+)\}/';

    /**
     * 値が与えられていないプレースホルダをそのまま出すかどうか。
     * false が指定されている場合は空文字で置き換える。
     */
    public $showCannotExpand = true;

    /**
     * 指定されていないプレースホルダを見つけたときに呼ばれるコールバック。
     * このコールバック関数は第一引数にプレースホルダ名を取り、置換後の文字列を返すものであること。
     * 置換後の文字列が分からない場合は null を返す。
     */
    public $replaceCallback = null;


    // 静的publicメソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * テンプレートと値を渡して、展開を実行する。
     * 展開を何度も実行する必要がないならこのメソッドのほうが簡単に呼べる。
     *
     * @param string    テンプレート文字列。
     * @param array     展開に使用する値を保持している配列。
     *                  例えば、{name} というプレースホルダを "田中" という文字列で置き換えたい場合は
     *                  次のように指定する。
     *                      array("name" => "田中")
     */
    public static function execute($template, $values) {
        $object = new self($template);
        return $object->expand($values);
    }


    // public メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     *
     * @param string    テンプレート文字列。
     *                  後から設定する場合は省略可能。
     */
    public function __construct($template = '') {
        $this->template = $template;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プロパティオーバーロード。
     * template, placeHolder プロパティに値がセットされた場合に、中間生成物をクリアする。
     */
    public function __set($name, $value) {

        if($name == 'template'  ||  $name == 'placeHolder')
            $this->splittedCache = null;

        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __isset($name) {
        return isset($this->$name);
    }

    public function __unset($name) {
        unset($this->$name);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に渡された値でテンプレートを展開する。
     *
     * @param array     展開に使用する値を保持している配列。
     *                  例えば、{name} というプレースホルダを "田中" という文字列で置き換えたい場合は
     *                  次のように指定する。
     *                      array("name" => "田中")
     * @return string   置き換え後の文字列。
     */
    public function expand($values) {

        // テンプレートを展開するときの中間処理を行う。
        $this->compile();

        // 戻り値を初期化。
        $result = "";

        // 分割した内容を一つずつみていく。
        for($index = 0 ; $index < count($this->splittedCache) ; $index++) {

            // 平テキストの場合はそのまま戻り値に追加。
            if($index % 2 == 0) {
                $result .= $this->splittedCache[$index];

            // プレースホルダの場合。
            }else {

                // ホルダ名を取得。
                $placeName = $this->splittedCache[$index];

                // ホルダの値が存在するならばその値に置き換える。
                if( array_key_exists($placeName, $values) ) {
                    $result .= $values[$placeName];

                // ホルダの値が存在しない場合。
                }else {

                    // replaceCallbackプロパティでコールバックが指定されているならばそれを呼ぶ。
                    $replace = null;
                    if($this->replaceCallback)
                        $replace = call_user_func($this->replaceCallback, $placeName);

                    // 指定されていない、あるいはnullが戻された場合は、showCannotExpandプロパティに
                    // したがって、ホルダをそのままにするのか、カラ文字で置き換えるのかを決める。
                    if(is_null($replace))
                        $replace = $this->showCannotExpand ? '{'.$placeName.'}' : '';

                    // 戻り値に追加。
                    $result .= $replace;
                }
            }
        }

        // 戻り値をリターン。
        return $result;
    }


    // private メンバ
    //=====================================================================================================

    // 展開処理時の中間生成物。
    private $splittedCache;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 展開時の中間生成物を作成する。すでに作成されている場合はすぐに返る。
     */
    private function compile() {

        // すでに作成されているなら即リターン。
        if( isset($this->splittedCache) )
            return;

        // 渡されたテンプレートを、平テキスト → プレースホルダ → 平テキスト → プレースホルダ ... と
        // いう風に分割する。
        $this->splittedCache = preg_split($this->placeHolder, $this->template, -1, PREG_SPLIT_DELIM_CAPTURE);
    }
}
