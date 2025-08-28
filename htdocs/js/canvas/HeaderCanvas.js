
/**
 * ヘッダーCanvasを制御するシングルトンオブジェクト。
 *
 */
function HeaderCanvas(_parent, no, summary){

    // 親クラスを継承
    this.__proto__ = new CanvasCommon();
    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : HeaderCanvas,
        enumerable : false
    });

    if(no == null || no == undefined)
        no = "";

    this.swf_name = "header_sm";
    this.canvas_id = this.swf_name + no;

    this.width = devicewidth;

    //セーフエリアを取得
    var safeareatop = getComputedStyle(document.documentElement).getPropertyValue("--sat");
//console.log("safeareatop=" + safeareatop);
    safeareatop = safeareatop.replace("px", "");

    if(isNaN(safeareatop)){
        safeareatop = 0;
    }

    //セーフエリア対応。
    if(safeareatop == 0){
        //セーフエリアがない場合
        this.top = -28;
    }else{
        this.top = 0;
    }

    this.PartialDraw = false;
    this.shapeDetail = {
      "all": { "method":"func"},
    };

    //newしたクラス名
    this._parent = _parent;

    //クラス名
    this.ClassName = this.constructor.name + no;

    //home.summary
    this.summary = summary;

    //---------------------------------------------------------------------------------------------------------
    /**
     * ページが読み込まれたら呼ばれる。
     */
    this.__proto__.start = function() {
        this.super.start.call(this);
    }

    //---------------------------------------------------------------------------------------------------------
    /**
     * メイン処理
     */
    this.__proto__.reload = function (){
        this.set();

        this.pex.getAPI().gotoFrame("/", "init");

        this.super.reload.call(this);
    }

    //---------------------------------------------------------------------------------------------------------
    /**
     * サマリ情報をセットして一気に戻す
     */
    this.__proto__.set = function() {
        //再帰処理で全データを一次元にする
        parse_arr = {};
        AppUtil.array_parse(this.summary,"");
        console.log(parse_arr);

        //再帰処理したものを一気に渡す
        var self = this;
        $.each(parse_arr, function(key, value){
            self.pex.getAPI().setVariable("/", key, value);
        });
    }
    //---------------------------------------------------------------------------------------------------------
    /*
     * キャプションを変更する。initかinを呼ぶ前に設定する。
     *
    */
    this.__proto__.caption = function(text) {

        this.pex.getAPI().setVariable("/", "caption", text);
    }

    //---------------------------------------------------------------------------------------------------------
    /*
     * すべて表示し終わった時のイベントハンドラ
     *
    */
    this.__proto__.onLoaded = function() {

        this.super.onLoaded.call(this);
    }

    /*
      ヘッダーを該当サマリーで初期化する
    */
    this.__proto__.init = function(summary){
        this.summary = summary;
        this.set();
        this.pex.getAPI().gotoFrame("/header", "init");
    }

    /*
      ヘッダーを該当情報で更新する
    */
    this.__proto__.update = function(key, value){
        this.pex.getAPI().setVariable("/", key, value);
        this.pex.getAPI().gotoFrame("/header", "init");
    }

    /*
      ヘッダーを登場させる
    */
    this.__proto__.in = function(){

        this.pex.getAPI().gotoFrame("/header", "in");
    }

    /*
      ヘッダーを終了する
    */
    this.__proto__.out = function(){

        this.pex.getAPI().gotoFrame("/header", "out");
    }

    /*
      時間を計算して返す
    */
    this.__proto__.getTime = function(accessor){
        var self = eval(accessor);

        var now = AppUtil.getTime();
        self.pex.getAPI().setVariable("/", "now", parseInt(now / 1000));
    }

    //---------------------------------------------------------------------------------------------------------
    /**
     * ページのオブジェクトを破棄する。
     */
    this.__proto__.destroy = function (){

        this.super.destroy.call(this);
    }

    this.super = CanvasCommon.prototype;
    //startをコール
    this.start();
}  

