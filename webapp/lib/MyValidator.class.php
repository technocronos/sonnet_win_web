<?php

/**
 * フォーム検証＆正規化用のクラス。
 *
 * 使用例1)
 *
 *     <?php
 *
 *         // POSTメソッドで入力されたフォームを、以下のルールで検証する。
 *         //     item1     必須
 *         //     item2     必須、数字のみ
 *         //     item3     必須、数字かハイフンのみ、ハイフンを削除する
 *         $validator = new Validator(array(
 *               'item1' => 'required'
 *             , 'item2' => array('required', 'numonly')
 *             , 'item3' => array('required', 'hypennum', 'filter'=>'-')
 *         ));
 *
 *         // 検証。
 *         if($_POST)
 *             $validator->validate();
 *
 *         // GETフォームの場合は次のコードで検証対象をセットする。
 *         // $validator->validate($_GET);
 *
 *         if( $validator->isValid() ) {
 *             // フォームがエラーなく送信されている場合の処理...
 *
 *             // 正規化された値(両端の空白削除等)を取得。
 *             var_dump($validator->values);
 *             exit();
 *         }
 *
 *     ?>
 *
 *     <html>
 *       <body>
 *
 *         <!-- いずれかの項目にエラーがある場合にメッセージを出力 -->
 *         <?php if($validator->isError()): ?>エラーがあります<br /><?php endif ?>
 *
 *         <form method="post">
 *
 *           <!-- 項目1にエラーがある場合に典型的なエラーメッセージを出力 -->
 *           <?php $validator->outputError('item1') ?>
 *           item1:<input type="text" name="item1" value="<?php echo $validator->input('item1') ?>" /><br />
 *
 *           <?php $validator->outputError('item2') ?>
 *           item2:<input type="text" name="item2" value="<?php echo $validator->input('item2') ?>" /><br />
 *
 *           <?php $validator->outputError('item3') ?>
 *           item3:<input type="text" name="item3" value="<?php echo $validator->input('item3') ?>" /><br />
 *           <input type="submit" />
 *         </form>
 *
 *         <!-- 個別のエラーに対してオリジナルメッセージを出力 -->
 *         <?php if($validator->errors['item2'] == 'numonly'): ?>項目2は数字だけ入れてね<br /><?php endif ?>
 *
 *       </body>
 *     </html>
 *
 * 使用例2) 複数の項目に渡る検証
 *
 *     <?php
 *         // item1 と item2 に同じ値が入力されているかどうか、また、
 *         // year, mon, day の各項目にエラーがない場合に、存在する日付を構成しているかどうかを
 *         // チェックする。
 *
 *         $validator = new Validator(array(
 *               'year' => array('required', 'numonly')
 *             , 'mon' =>  array('required', 'numonly')
 *             , 'day' =>  array('required', 'numonly')
 *             , '_form' => array(
 *                     'foo' => array('equiv'=>array('item1', 'item2'))
 *                   , 'bar' => array('datecheck'=>array('year', 'mon', 'day'))
 *               )
 *         ));
 *
 *         // ...省略
 *
 *     ?>
 *
 *     <html>
 *       <body>
 *
 *         <form method="post">
 *
 *           <?php $validator->outputError('foo') ?>
 *           同じ値を入力<input name="item1" /> == <input name="item2" /><br />
 *
 *           <br />
 *           <?php $validator->outputError('bar') ?>
 *           日付<input name="year" /> / <input name="mon" /> / <input name="day" /><br />
 *           ※すべて入力してください<br />
 *
 *           <br />
 *           <input type="submit" value="go" /><br />
 *         </form>
 *
 *       </body>
 *     </html>
 *
 * 使用例3) コールバックを使用した検証
 *
 *     <?php
 *         // item1とitem2が正しく入力されている場合に、item1がitem2の倍数になっているか
 *         // どうかをチェックする。
 *
 *         $validator = new Validator(array(
 *               'item1' => array('required', 'numonly')
 *             , 'item2' => array('required', 'numonly')
 *             , '_form' => array(
 *                   'foo' => array('callback'=>'myCheck')
 *               )
 *         ));
 *
 *         function myCheck($validator) {
 *
 *             // 個別の検証でエラーが出ているならこの検証は行わない。
 *             if(!empty($validator->errors['item1']) || !empty($validator->errors['item2']))
 *                 return '';
 *
 *             if($validator->values['item1'] % $validator->values['item2'] == 0) {
 *                 return '';
 *             }else {
 *                 return 'individe';
 *             }
 *         }
 *
 *         // ...省略
 *     ?>
 *
 *     <html>
 *       <body>
 *
 *         <?php if($validator->errors['foo']): ?>倍数ではありません<?php endif ?>
 *
 *         <form method="post">
 *           <input name="item1" /> % <input name="item2" /> = 0<br />
 *           <input type="submit" value="go" /><br />
 *         </form>
 *
 *       </body>
 *     </html>
 *
 * 使用例4) 典型的な入力⇒確認画面の実装
 *
 *     <?php
 *         // 必須入力item1があるフォームの入力画面と確認画面。
 *         // ビジネスロジックは共用。
 *
 *         $validator = new Validator(array(
 *             'item1' => 'required',
 *             'item2' => 'numonly',
 *         ));
 *
 *         // フォームが入力されている場合。
 *         if($_SERVER["REQUEST_METHOD"] == 'POST') {
 *
 *             // 検証。
 *             $validator->validate();
 *
 *             // 確認画面で完了ボタンが押されている場合。
 *             if( isset($_POST['save']) && $validator->isValid() ) {
 *
 *                 // 入力の反映処理...
 *                 var_dump($validator->values);
 *
 *                 // 結果画面へのリダイレクト...
 *                 exit();
 *
 *             // 確認画面でのボタン押下ではなく、入力には問題ない場合。
 *             }else if(empty($_POST['cancel']) && $validator->isValid()) {
 *
 *                 // 確認画面を出力...
 *                 ?>
 *                     ※確認画面
 *                     <html>
 *                       <body>
 *
 *                         <form method="post">
 *                           <?php echo $validator->dumpOnHidden() ?>
 *
 *                           入力された値:<br />
 *                           項目1: <?php echo htmlspecialchars($validator->inputs['item1']) ?><br />
 *                           項目2: <?php echo htmlspecialchars($validator->inputs['item2']) ?><br />
 *
 *                           よろしいですか？
 *                           <input type="submit" name="save" value="はい" />
 *                           <input type="submit" name="cancel" value="戻る" />
 *
 *                         </form>
 *
 *                       </body>
 *                     </html>
 *                 <?php exit();
 *             }
 *         }
 *
 *         // まだフォームが入力されてない or 入力されているけどエラーがある or
 *         // 確認画面のキャンセルで戻ってきた場合。
 *
 *         // フォーム未入力時の初期値が必要な場合はココでセット。
 *         $validator->defaults = array(
 *             'item1' => 'hi',
 *         )
 *
 *         // 入力画面を表示...
 *         ?>
 *             ※入力画面
 *             <html>
 *               <style>
 *                   .error {
 *                       color:red;
 *                   }
 *               </style>
 *               <body>
 *
 *                 <form method="post">
 *                   <?php $validator->outputError('item1') ?>
 *                   必須<input type="text" name="item1" value="<?php echo $validator->input('item1') ?>"/><br />
 *                   <?php $validator->outputError('item2') ?>
 *                   任意.数字のみ<input type="text" name="item2" value="<?php echo $validator->input('item2') ?>"/><br />
 *                   <input type="submit" value="go" /><br />
 *                 </form>
 *
 *               </body>
 *             </html>
 *
 * 規則)
 *     ・Validatorインスタンスを作成時、各メンバは次のような値を返す。
 *           isValid()      false
 *           isError()      false
 *
 *     ・validateメソッド呼び出し後は、各メンバは次のような値を返す。
 *           isValid()      エラーがなかったかどうか。
 *           isError()      エラーがあったかどうか。isValid()の反対。
 *
 *     ・現在使用可能なルールは以下の通り。
 *         required     必須
 *         no_trim      両端空白削除を行わないようにする。
 *         no_halve     numonly, hypennum, ascii, email で、半角変換を行わないようにする。
 *         numonly      数字のみであること。半角変換可能な文字列は半角に補正される。
 *         hypennum     数字とハイフンのみであること。半角変換可能な文字列は半角に補正される。
 *         ascii        ASCIIの可視文字(0x20～0x7E)のみであること。半角スペースはOKだが、改行・タブ文字はNG。
 *                      半角変換可能な文字列は半角に補正される。
 *         kanaonly     全角カナのみであること。全角カナに変換可能な文字列は全角カナに補正される。
 *         email        メールアドレスであること。半角変換可能な文字列は半角に補正される。
 *         datetime     日時であること。半角変換可能な文字列は半角に補正される。
 *                      年が省略されてたら当年、時間が省略されていたら00:00:00になる。
 *         dateend      datetimeと同じだが、時間が省略されていたら翌日の00:00にする。
 *                      秒が省略されていたら翌分の00秒とする。
 *         length       パラメータで指定されている文字数であること。
 *         minlen       パラメータで指定されている文字数以上であること。
 *         maxlen       パラメータで指定されている文字数以下であること。
 *         filter       パラメータで指定されている文字を削除。(正規化のみ。検証はなし)
 *         ifempty      何も入力されていない場合はパラメータで指定されている値とする。(正規化のみ。検証はなし)
 *
 *     ・"no_trim" のルールを指定しない限り、すべての入力項目に両端空白削除の処理が行われる。
 *
 *     ・検証ルールは指定された順番通りに検証されていく。
 *
 *     ・項目名 "_form" で、フォーム全体に渡る検証・正規化を指定できる。
 *       指定した場合、各項目の検証が行われた後、フォーム全体の検証が行われる。
 *       "_form" に指定可能な検証ルールは以下の通り。
 *           equiv          二つの要素を含む配列を値として取る。
 *                          指定された二つの項目が等しい入力値を持っていること。
 *           datecheck      三つの要素を含む配列を値として取る。
 *                          日付として妥当であること。年・月・日を別々の項目で入力させる場合等に使用する。
 *           lowerupper     二つの要素を含む配列を値として取る。
 *                          指定された二つの項目が小、大の関係を保っていること。
 *                          保っていなかった場合は入れ替えられる。
 *           interval       三つの要素を含む配列を値として取る。
 *                          一つ目と二つ目で指定された項目の数値間隔が、三つ目で指定された値以下に収まること。
 *           dateinterval   三つの要素を含む配列を値として取る。
 *                          一つ目と二つ目で指定された項目の日時間隔が、三つ目で指定された値以下に収まること。
 *                          三つ目の値は、"1day"など、strtotimeで指定できるもの。
 *           callback       callback型の値を取る
 *                          指定された関数を呼ぶ。
 *                          指定された関数は以下の引数・戻り値仕様を満たしている必要がある。
 *                              第一引数    呼び出し元のValidatorインスタンス
 *                              戻り値      エラーの場合はエラーコード、エラーでないならば偽に評価される値。
 */
class MyValidator {

    // public フィールド
    //=====================================================================================================

    // 各項目の値を、検証ルールに従って正規化したもの。
    public $values = array();

    // 入力が行われていない場合のデフォルト値。
    public $defaults = array();

    // 入力された、正規化する前の各項目の値。
    public $inputs = array();

    // 各項目ごとのエラーコード。
    public $errors = array();

    // 各項目ごとのエラーコードに付随するパラメータ。
    public $errorsParam = array();

    // コンストラクタで指定したルール。
    public $rules = array();

    // エラーコードに対する、標準的なエラーメッセージ。
    public $errorMsg = array(
        'required'      => '入力してください',
        'numonly'       => '数字のみで入力してください',
        'hypennum'      => 'ハイフンか数字のみで入力してください',
        'ascii'         => '半角文字のみで入力してください',
        'kanaonly'      => 'カナのみで入力してください',
        'length'        => '%d文字で入力してください',
        'minlen'        => '%d文字以上で入力してください',
        'maxlen'        => '%d文字以下で入力してください',
        'email'         => '正しく入力してください',
        'equiv'         => '入力内容が異なっています',
        'datecheck'     => '存在しない日付です。',
        'notdate'       => '日時として解釈できません。',
        'interval'      => '範囲を%sまでにしてください。',
    );

    // 標準的なエラー出力フォーマット。
    public $errorFormat = '<div class="error">%s</div>';


    // public メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * コンストラクタ。
     *
     * @param array     フォームの各項目に対してどのような検証を行うかを設定した配列。
     */
    public function __construct($rules = array()) {

        $this->rules = $rules;

        // errorsプロパティはvalidateの呼び出しなしで参照される可能性があるので、
        // 初期化しておく。
        $this->errors = array_fill_keys(array_keys($rules), null);
        if(!empty($rules['_form']))
            $this->errors += array_fill_keys(array_keys($rules['_form']), null);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 入力された値を検証して、各メンバが結果を返せるようにする。
     *
     * @param array     入力された値。省略時は$_POSTを使用する。
     * @return boo      エラーがなかったならtrue、あったならfalse。
     */
    public function validate($input = null) {

        // 入力された値を保持。
        $this->inputs = is_null($input) ? $_POST : $input;

        // ルールを一旦取り出す。
        $rules = $this->rules;

        // 予約されている項目名を取り出しておく。
        $formRule = isset($rules['_form']) ? $rules['_form'] : array();
        unset($rules['_form']);

        // 引数で指定された、検証ルールが存在する項目を一つずつ見ていく。
        foreach($rules as $itemName => $itemRule) {

            // 項目の値を取得。入力にその項目がない場合はカラ文字とする。
            $value = isset($this->inputs[$itemName]) ? $this->inputs[$itemName] : '';

            // 検証＆値の正規化。
            $errorCode = self::validateItem($value, $itemRule, $errorParam);

            // エラーコードと正規化された値を各メンバに格納。
            $this->errors[$itemName] = $errorCode;
            $this->errorsParam[$itemName] = $errorParam;
            $this->values[$itemName] = $value;
        }

        // 入力値には存在するけど、検証ルールにはない項目をvalues, errorsに補う。
        $this->values += $this->inputs;
        $this->errors += array_fill_keys(array_keys($this->inputs), null);

        // 項目名 "_form" で指定されている検証を実行。
        $this->validateForm($formRule);

        // validateメソッドが呼ばれたことを覚えておく。
        $this->validated = true;

        // リターン
        return $this->isValid();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 入力値の検証で問題がないならtrue、エラーがあるならfalseを返す。
     * validateメソッドが呼ばれる前は常にfalseを返す。
     */
    public function isValid() {

        // validateメソッドが呼ばれる前は常にfalse。
        if(!$this->validated)
            return false;

        // エラーがないなら問題なし。
        return !$this->isError();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 入力値の検証でエラーがあるならtrue、問題がないならfalseを返す。isValid()の反対。
     * validateメソッドが呼ばれる前は常にfalseを返す。
     */
    public function isError() {

        // validateメソッドが呼ばれる前は常にfalse。
        if(!$this->validated)
            return false;

        // なにか一つでもエラーがあるならtrue。
        foreach($this->errors as $errors) {
            if($errors)
                return true;
        }

        // エラーが一つもないならばfalse。
        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された項目の値を返す。
     * validateが呼ばれた後ならinputsプロパティから、まだ呼ばれていない場合はdefaultsプロパティから
     * 検索する。
     *
     * @param string    項目の名前
     * @return mixed    項目の値
     */
    public function itemValue($itemName) {

        if($this->validated)
            return isset($this->inputs[$itemName]) ? $this->inputs[$itemName] : '';
        else
            return isset($this->defaults[$itemName]) ? $this->defaults[$itemName] : '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された項目にエラーがある場合に、標準エラーメッセージを使用して、エラー文言を出力する。
     *
     * @param string    項目のコード名
     * @param string    ユーザに表示する項目名。指定した場合、「項目名:メッセージ」という形式で出力される。
     */
    public function outputError($itemName, $itemCaption = '') {

        // まだvalidateが呼ばれていないなら出力しない。
        if(!$this->validated)
            return;

        // 指定された項目のエラーコードを取得。
        $errorCode = isset($this->errors[$itemName]) ? $this->errors[$itemName] : '';

        // エラーがないならなにも出力しない。
        if(!$errorCode)
            return;

        // エラーコードに対応する標準エラーメッセージを取得。
        $message = isset($this->errorMsg[$errorCode]) ? $this->errorMsg[$errorCode] : $errorCode;

        // エラーに付随しているパラメータを取得。
        $params = isset($this->errorsParam[$itemName]) ? $this->errorsParam[$itemName] : null;

        // 標準エラーメッセージのパラメータホルダを展開。
        $message = vsprintf($message, (array)$params);

        // 項目名が指定されている場合は「項目名:メッセージ」という形式にする。
        if($itemCaption)
            $message = $itemCaption . ':' . $message;

        // さらにerrorFormatプロパティで指定されているフォーマットにしたがって出力。
        printf($this->errorFormat, htmlspecialchars($message, ENT_QUOTES));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された項目の値を描画するHTMLを出力する。
     *
     * @param string    項目のコード名
     * @return mixed    項目の値を描画するHTML
     */
    public function input($itemName) {

        echo htmlspecialchars($this->itemValue($itemName), ENT_QUOTES);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された項目を<select>で描画するHTMLを出力する。
     *
     * @param string    項目のコード名
     * @param array     キーにvalueを、値に表示名を保持している <option> の配列。
     * @return mixed    <select>で描画するHTML
     */
    public function listbox($itemName, $options) {

        $selectedValue = $this->itemValue($itemName);

        // <option> の配列を見て、<option>を生成していく。
        foreach($options as $key => $caption) {
            $html .= sprintf('<option value="%s" %s>%s</option>' . "\n",
                htmlspecialchars($key, ENT_QUOTES),
                ($selectedValue == (string)$key) ? 'selected' : '',
                htmlspecialchars($caption, ENT_QUOTES)
            );
        }

        // 出力。
        echo
            '<select name="' . htmlspecialchars($itemName, ENT_QUOTES) . '">' . "\n" .
            $html .
            '</select>';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * すべての入力値を <input type="hidden" /> で出力する。
     * 確認画面の<form>などで使用する。
     */
    public function dumpOnHidden() {

        foreach($this->inputs as $name => $value) {
            printf(
                '<input type="hidden" name="%s" value="%s" />'."\n",
                htmlspecialchars($name, ENT_QUOTES),
                htmlspecialchars($value, ENT_QUOTES)
            );
        }
    }


    // private メンバ
    //=====================================================================================================

    // validateメソッドが呼ばれたかどうか。
    private $validated = false;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値を、指定されたルールで検証＆正規化する。
     *
     * @param string    値。正規化された値が返される変数でもある。
     * @param mixed     検証ルール。
     * @param reference エラーコードに付随する値を返してほしい変数。
     * @return string   エラーコード。エラーがない場合はカラ文字列。
     */
    private static function validateItem(&$value, $rules, &$errorParam) {

        // 指定されたルールを正規化。
        $rules = self::normalizeRule($rules);

        // 初期化。
        $errorParam = null;

        // 初期正規化として値の両端の空白を削除。ただし、"no_trim" ルールがある場合は除く。
        if( !array_key_exists('no_trim', $rules) )
            $value = preg_replace('/^(?:[\x00-\x20\x7F]|　)*((?:.|\n)*?)(?:[\x00-\x20\x7F]|　)*$/X', '$1', $value);

        // 検証ルールを指定された順に一つずつ処理していく。
        foreach($rules as $ruleName => $ruleParam) {

            // required, ifemptyルール以外で、値がカラの場合は検証しない。
            if($ruleName != 'required'  &&  $ruleName != 'ifempty'  &&  $value == '')
                continue;

            // ルールごとに分岐。
            switch($ruleName) {

                // requiredルール
                case 'required':

                    // 値がカラならエラー。
                    if( 0 == strlen($value) )
                        return 'required';
                    break;

                // ifemptyルール
                case 'ifempty':

                    // 値がカラならパラメータで指定されている値に置き換える。
                    if( 0 == strlen($value) )
                        $value = $ruleParam;
                    break;

                // numonlyルール
                case 'numonly':

                    // 半角化して、数字文字のみで構成されていないならエラー。
                    if( !self::halveAndCheck('/^\d+$/', $value, array_key_exists('no_halve', $rules)) )
                        return 'numonly';
                    break;

                // hypennumルール
                case 'hypennum':

                    // 半角化して、数字文字とハイフンのみで構成されていないならエラー。
                    if( !self::halveAndCheck('/^[\d\-]+$/', $value, array_key_exists('no_halve', $rules)) )
                        return 'hypennum';
                    break;

                // asciiルール
                case 'ascii':

                    // 半角化して、可視ASCII文字のみで構成されていないならエラー。
                    if( !self::halveAndCheck('/^[\x20-\x7E]+$/', $value, array_key_exists('no_halve', $rules)) )
                        return 'ascii';
                    break;

                // kanaonlyルール
                case 'kanaonly':

                    // 全角カナに変換可能な文字を変換。
                    $value = mb_convert_kana($value, 'KCV', 'UTF-8');

                    // 音引きとよく混同される文字も変換
                    $value = str_replace(array('-', '－', '‐', '―'), 'ー', $value);

                    // 全角カナのみで構成されていないならエラー。
                    if( !mb_ereg_match('^[ァ-ー]+$', $value) )
                        return 'kanaonly';

                    break;

                // emailルール
                case 'email':

                    // 半角化して、数字文字とハイフンのみで構成されていないならエラー。
                    if( !self::halveAndCheck('/^[\x21-\x7E]+@[\x21-\x7E]+$/', $value, array_key_exists('no_halve', $rules)) )
                        return 'email';
                    break;

                // datetime, dateendルール
                case 'datetime':
                case 'dateend':

                    // 半角化。
                    if( !array_key_exists('no_halve', $rules) )
                        $value = self::toHalfWidth($value);

                    // 解析。
                    $parse = DateTimeUtil::parse($value);

                    // 問題なく解析できたら。
                    if(is_numeric($parse)) {

                        // dateendだった場合の補正を行う。
                        if($ruleName == 'dateend') {

                            // 時間の区切り文字「:」の数を数える。
                            $colonNum = substr_count($value, ':');

                            // 「:」がないのは時間の省略。+1日する。
                            if($colonNum == 0)
                                $parse = strtotime('+1day', $parse);
                            // 「:」が一つしかないのは秒の省略。+1分する。
                            else if($colonNum == 1)
                                $parse = strtotime('+1minute', $parse);
                        }

                        // 「xxxx/xx/xx xx:xx:xx」の形式に正規化する。
                        $value = date('Y/m/d H:i:s', $parse);

                    // 解析できなかったら。
                    }else {
                        switch($parse) {
                            case 'cannot_parse':
                                return 'notdate';
                            case 'invalid_date':
                            case 'invalid_time':
                                return 'datecheck';
                        }
                    }

                    break;

                // lengthルール
                case 'length':

                    // 指定の文字数でないならエラー。
                    if(mb_strlen($value) != $ruleParam) {
                        $errorParam = $ruleParam;
                        return 'length';
                    }

                    break;

                // minlenルール
                case 'minlen':

                    // 指定の文字数未満でないならエラー。
                    if(mb_strlen($value) < $ruleParam) {
                        $errorParam = $ruleParam;
                        return 'minlen';
                    }

                    break;

                // maxlenルール
                case 'maxlen':

                    // 指定の文字数超過でないならエラー。
                    if(mb_strlen($value) > $ruleParam) {
                        $errorParam = $ruleParam;
                        return 'maxlen';
                    }

                    break;

                // filterルール
                case 'filter':

                    // パラメータに指定されている文字列を削除。
                    $value = str_replace($ruleParam, '', $value);
                    break;
            }
        }

        // ここまでくるのはエラーがないため。カラ文字をリターン。
        return '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 半角変換可能な文字を半角化し、そのあと正規表現でチェックする検証を行う。
     *
     * @param string    チェックに使う正規表現。
     * @param mixed     値。正規化された値が返される変数でもある。
     * @param bool      半角化を行うかどうか。
     * @return bool     正規表現にマッチしたなら true、しなかったら false。
     */
    private static function halveAndCheck($regexp, &$value, $noHalve) {

        // 半角変換可能な文字を半角に。
        if(!$noHalve)
            $value = self::toHalfWidth($value);

        // 正規表現でチェック。
        return (bool)preg_match($regexp, $value);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に受け取った文字列で半角変換可能な文字をすべて半角に変換して返す。
     * 漢字などの半角変換不能な文字はそのまま。
     *
     * @param string     半角に変換したい文字列。
     * @return string    半角に変換した文字列。
     */
    private static function toHalfWidth($string) {

        return mb_convert_kana($string, 'ask', 'UTF-8');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に受け取ったルール指定を正規化したものを返す。
     *
     * @param mixed     ルール指定
     * @return array    正規化したルール指定
     */
    private static function normalizeRule($rules) {

        // カラのルールが指定されている場合はカラ配列に、配列以外の単一値が指定されている場合はそれを唯一の
        // 要素とする配列にする。
        if(!$rules)
            $rules = array();
        else if( !is_array($rules) )
            $rules = array($rules);

        // キーがルール名、値がパラメータになっているはずだが、キーなし値にルール名が
        // 指定されているケースを前記の形に統一する。
        // このとき、要素の順番が狂わないようにする。
        $result = array();
        foreach($rules as $index => $value) {
            if( is_numeric($index) )
                $result[$value] = true;
            else
                $result[$index] = $value;
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * フォーム全体に対する検証(項目名 "_form" で指定されたもの)を処理する。
     *
     * @param mixed     項目名 "_form" で指定された、フォーム全体の検証ルール。
     */
    private function validateForm($formValidates) {

        // 検証内容がないなら即リターン。
        if(!$formValidates)
            return;

        // 検証項目を指定された順に一つずつ処理していく。
        foreach($formValidates as $validName => $rules) {
            $errorCode = $this->validateFormRules($rules, $errorParam);
            $this->errors[$validName] = $errorCode;
            $this->errorsParam[$validName] = $errorParam;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたルールでフォーム全体を検証＆正規化する。
     *
     * @param mixed     検証ルール。
     * @param reference エラーコードに付随する値を返してほしい変数。
     * @return string   エラーコード。エラーがない場合はカラ文字列。
     */
    private function validateFormRules($rules, &$errorParam) {

        // 指定されたルールを正規化。
        $rules = self::normalizeRule($rules);

        // 初期化。
        $errorParam = null;

        // 検証ルールを指定された順に一つずつ処理していく。
        foreach($rules as $ruleName => $ruleParam) {

            // ルールごとに分岐。
            switch($ruleName) {

                // equivルール
                case 'equiv':

                    // ルールの値から検査する項目名を取得。
                    list($item1, $item2) = $ruleParam;

                    // 対象のいずれかの項目でエラーが発生しているならチェックしない。
                    if(!empty($this->errors[$item1])  ||  !empty($this->errors[$item2]))
                        break;

                    // 等値チェック。違うならエラーコード設定。
                    if($this->values[$item1] != $this->values[$item2])
                        return 'equiv';

                    break;

                // datecheckルール
                case 'datecheck':

                    // ルールの値から年・月・日の項目名を取得。
                    list($yearItem, $monthItem, $dayItem) = $ruleParam;

                    // 年・月・日のいずれかの項目でエラーが発生しているならチェックしない。
                    if(!empty($this->errors[$yearItem]) || !empty($this->errors[$monthItem]) || !empty($this->errors[$dayItem]))
                        break;

                    // 日付チェック。エラーならエラーコード設定。
                    if( !checkdate($this->values[$monthItem], $this->values[$dayItem], $this->values[$yearItem]) )
                        return 'datecheck';

                    break;

                // lowerupperルール
                case 'lowerupper':

                    // ルールの値から検査する項目名を取得。
                    list($item1, $item2) = $ruleParam;

                    // 対象のいずれかの項目でエラーが発生しているならチェックしない。
                    if(!empty($this->errors[$item1])  ||  !empty($this->errors[$item2]))
                        break;

                    // 対象のいずれかの項目がカラならチェックしない。
                    if(0 == strlen($this->values[$item1])  ||  0 == strlen($this->values[$item2]))
                        break;

                    // 大小チェック。違うなら入れ替える。
                    if($this->values[$item1] > $this->values[$item2]) {
                        $dummy = $this->values[$item1];
                        $this->values[$item1] = $this->values[$item2];
                        $this->values[$item2] = $dummy;
                    }

                    break;

                // interval, dateinterval ルール
                case 'interval':
                case 'dateinterval':

                    // ルールの値から検査する項目名と間隔を取得。
                    list($item1, $item2, $interval) = $ruleParam;

                    // 対象のいずれかの項目でエラーが発生しているならチェックしない。
                    if(!empty($this->errors[$item1])  ||  !empty($this->errors[$item2]))
                        break;

                    // 対象のいずれかの項目がカラならチェックしない。
                    if(0 == strlen($this->values[$item1])  ||  0 == strlen($this->values[$item2]))
                        break;

                    // 小さいほうと大きいほうを取得。
                    if($this->values[$item1] < $this->values[$item2]) {
                        $lower = $this->values[$item1];
                        $upper = $this->values[$item2];
                    }else {
                        $lower = $this->values[$item2];
                        $upper = $this->values[$item1];
                    }

                    // "interval"ルールの場合。
                    if($ruleName == 'interval') {

                        // [小さいほう＋指定された間隔]が[大きいほう]に到達しないならエラー。
                        if($lower + $interval < $upper) {
                            $errorParam = $interval;
                            return 'interval';
                        }

                    // "dateinterval"ルールの場合。
                    }else if($ruleName == 'dateinterval') {

                        // タイムスタンプに変換。
                        $lower = strtotime($lower);
                        $upper = strtotime($upper);

                        // 変換に失敗している場合はチェックしない。
                        if($lower === false  ||  $upper === false)
                            break;

                        // [小さいほう＋指定された間隔]が[大きいほう]に到達しないならエラー。
                        if(strtotime($interval, $lower) < $upper) {

                            $errorParam = str_replace(
                                array('second', 'minute', 'hour', 'day', 'month', 'year'),
                                array('秒', '分', '時間', '日', 'ヶ月', '年'),
                                $interval
                            );

                            return 'interval';
                        }
                    }

                    break;

                // callbackルール
                case 'callback':

                    // 指定されたコールバックをコール。戻された値をエラー配列に代入。
                    $errorCode = call_user_func($ruleParam, $this);
                    if($errorCode) return $errorCode;
                    break;
            }
        }

        // ここまできたならエラーなし。
        return '';
    }
}
