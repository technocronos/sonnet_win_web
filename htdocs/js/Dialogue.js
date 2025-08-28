/**
 * 画面全体に薄いベールをかけて、画面中央にポップアップ状のボックス(ダイアログ)を表示するためのユーティリティ。
 * これは飽くまで汎用的な処理で汎用的なダイアログを出すにとどめている。アプリケーションごとに
 * 決められたスタイルで表示するダイアログは AppDialogue を利用すること。
 *
 * 例) デフォルトのスタイルでHTMLを表示。
 *
 *      // インスタンスを作成。
 *      var d = new Dialogue();
 *
 *      // HTMLをセット。
 *      d.content('Hello! <span="color:red">World</span>');
 *
 *      // 表示。
 *      d.show();
 *
 * 例) デフォルトのボックスではなく、ボックスを自分で指定する。
 *
 *      var d = new Dialogue();
 *
 *      d.appearance('<div>HELLO!!!!!</div>');
 *
 *      d.show();
 *
 * 例) ボックスを指定した上で、別途中身をセットする。
 *
 *      var d = new Dialogue();
 *
 *      d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
 *
 *      // key="dialogue-content" の要素の中身が指定したHTMLに置き換わる。
 *      d.content('Hello! World');
 *
 *      d.show();
 *
 * 例) その他
 *
 *      var d = new Dialogue();
 *      d.content('Hello! <span="color:red">World</span>');
 *      d.show();
 *
 *      // 閉じられたときに行いたい処理がある場合はこのようにセットできる。
 *      // 引数には、この例で言えば d が与えられる。
 *      d.onClose = function(dialogue) {
 *          console.log("閉じられました");
 *      };
 *
 *      // プログラムで閉じる。これはなくても、ユーザがベールやポップアップをクリックすれば閉じられる
 *      // ようになっている。
 *      d.close();
 *
 *      // ユーザがポップアップをクリックしたときに閉じられないようにしたい場合は次のように指定しておく。
 *      d.autoClose = false;
 *
 *      // さらにベール部分のクリックも閉じられないようにしたい場合は次のように指定しておく。
 *      d.veilClose = false;
 */

//---------------------------------------------------------------------------------------------------------
/**
 * コンストラクタ。
 *
 * @param   ベールに自分で作成した要素を使いたい場合はそのid値を指定する。この場合、画面を覆うベールとして
 *          適切にcssが設定されている必要がある。
 *          省略した場合は自動で作成する。
 */
function Dialogue(veilId) {

    // 表示中のダイアログ背景の暗幕要素。
    this.$veil = undefined;

    // 表示中のポップアップ要素。
    this.$appearance = undefined;

    // ダイアログが閉じられるときに呼ばれるコールバック。
    // コールバックの引数にはこのインスタンスが渡される。
    this.onClose = undefined;

    // ユーザがポップアップやベールをクリックしたときに自動で閉じるかどうか。
    this.autoClose = true;
    this.veilClose = true;
    this.veilShow = true;
    this.top = null;

    // ベール要素を取得。なかったら内部で作成する。
    if(veilId) {
        this.$veil = $("#" + veilId).clone().show();
        if(this.$veil.length == 0)
            console.log('指定されたid "' + veilId + '" の要素が見付からない');
    }else {
        this.$veil = $('<div id="dialogue-veil"></div>');
        this.$veil.css(Dialogue.veilStyle);
    }
}

//---------------------------------------------------------------------------------------------------------

// 自動で作成するベール要素のcss。
Dialogue.veilStyle = {
    position: "absolute",
    left: "0px",
    top: "0px",
    right: "0px",
    bottom: "0px",
    opacity: "0.3",
    "background-color": "black",
};

// デフォルトのボックス要素のcss。
Dialogue.defaultAppearance = {
    "background-color": "white",
    "border-radius": "10px",
    "box-shadow": "0px 0px 10px 10px rgba(0,0,0, 0.5)",
    "padding-top": "2em",
    "padding-left": "2em",
    "padding-right": "2em",
    "padding-bottom": "2em",
    "color": "black",
};

//---------------------------------------------------------------------------------------------------------
/**
 * 引数で指定された内容をポップアップボックスとして保持する。
 *
 * @param   ポップアップボックスを表す要素。jQueryオブジェクトかそれに変換可能な値。
 */
Dialogue.prototype.appearance = function(appearance) {

    if(!appearance.jquery)
        appearance = $(appearance);

    if(appearance.length >= 2)
        console.log("ポップアップボックスは一つの要素で表される必要があります。");

    this.$appearance = appearance;
}

//---------------------------------------------------------------------------------------------------------
/**
 * ポップアップの中身を引数に指定された内容にセットする。
 * key="dialogue-content" の要素の中身が指定したHTMLに置き換わる。
 * appearance() でポップアップが指定されていない場合は自動で作成される。
 *
 * @param   ポップアップの中身。HTML文字列かjQueryオブジェクト。
 */
Dialogue.prototype.content = function(content) {

    // appearance がまだ指定されていない場合は自動で作成する。
    if( !this.$appearance ) {
        var $default = $('<div id="dialogue-appearance"><div key="dialogue-content" ></div></div>');
        $default.css(Dialogue.defaultAppearance);
        this.appearance($default);
    }

    try {
        // コンテンツの要素を書き換える
        var _devicewidth = devicewidth;
        var ratio = _devicewidth / 750;

        //if(is_tablet == "tablet") ratio = 1;

        var target = $(content).filter("#content").clone();
        var script = $(content).filter("script").clone();

        target.css("transform","scale(" + ratio + ")");

        //親をクローンして入れ替える
        var content_clone = $();
        content_clone = content_clone.add(script);
        content_clone = content_clone.add(target);

        //戻す
        content = content_clone;
    }catch(e){
        console.log(e);
    }

    // ポップアップの中から key="dialogue-content" な要素を探して、その中にセットする。
    this.setPlace("dialogue-content", content);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ポップアップ要素の中から指定された値を key 属性に持つ要素を探し、その内容を指定されたコンテンツに
 * 入れ替える。
 *
 * @param   内容を入れ替えたい要素の key 属性の値。
 * @param   入れ替え後の内容。
 */
Dialogue.prototype.setPlace = function(key, content) {

    // ポップアップの中から該当の key 属性を持つ要素を探す。
    var selector = '[key="' + key + '"]';
    var $place = $(selector, this.$appearance);

    // その中を指定された内容に置き換える。
    $place.empty().append(content);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ダイアログを表示する。
 */
Dialogue.prototype.show = function(close_callback) {
console.log("Dialogue.prototype.show run..");

    // ベールを表示。
    if(this.veilShow)
        this.$veil.appendTo(document.body);

    //スクロール抑制
    this.$veil.on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    // ポップアップを表示。
    this.$appearance.appendTo(document.body);

    // これでポップアップのサイズを求められるので、位置を設定する。
    // まずは position を設定する。そうしないと outerWidth() を適切に算出できない。
    this.$appearance.css("position", "absolute");

    // それから位置合わせ。
    if(this.top == null){
        this.$appearance.css({
            "top": (deviceheight/ 2) + "px",
            "margin-top": "-" + (this.$appearance.outerHeight()/2) + "px",
            "left": (devicewidth / 2) + "px",
            "margin-left": "-" + (this.$appearance.outerWidth()/2) + "px",
        });
    }else{
        //セーフエリアを取得
        var safeareatop = getComputedStyle(document.documentElement).getPropertyValue("--sat");
        safeareatop = safeareatop.replace("px", "");

        if(isNaN(safeareatop)){
            safeareatop = 0;
        }

        //ヘッダーを覆わないように最低50pxはTOPから空ける
        this.$appearance.css({
            "top": this.top + "px",
            "left": (devicewidth / 2) + "px",
            "margin-left": "-" + (this.$appearance.outerWidth()/2) + "px",
            "padding-top": "0em",
        });
    }

    if(this.opacity != null){
        this.$veil.css("opacity", this.opacity);
    }
    //z-indexが指定されていない場合は20に指定する。
    //すべてのポップアップは20で表示するが、エラーメッセージだけは100以上でないとflashに隠れて見えない時がある。
    if(this.$appearance.css("z-index") == "auto"){
        this.$veil.css("z-index", 20);
        this.$appearance.css("z-index", 20);
    }else{
        //指定されてる場合はveilだけ合わせる
        this.$veil.css("z-index", this.$appearance.css("z-index"));
    }

    // クリック時、閉じられるようにする。
    //var closer = this.clicked.bind(this);
    var self = this;
    this.$veil.on("click", function(e){
        self.clicked(e, close_callback);
    });
    this.$appearance.on("click", function(e){
        self.clicked(e, close_callback);
    });

    //スクロール抑制
    this.$appearance.on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * ダイアログを閉じる。
 */
Dialogue.prototype.close = function() {

    this.$appearance.remove();
    this.$veil.remove();

    if(this.onClose)
        this.onClose(this);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ユーザがポップアップやベールをクリックしたら呼ばれる。
 */
Dialogue.prototype.clicked = function(event,close_callback) {

    if(event.currentTarget == this.$appearance.get(0)  &&  this.autoClose){
        if(close_callback != null)
            close_callback();
        this.close();
    }

    if(event.currentTarget == this.$veil.get(0)  &&  this.veilClose){
        if(close_callback != null)
            close_callback();
        this.close();
    }
}
