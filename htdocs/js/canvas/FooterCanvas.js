
/**
 * フッターCanvasを制御するオブジェクト。
 *
 */
function FooterCanvas(_parent, no, summary){

    // 親クラスを継承
    this.__proto__ = new CanvasCommon();
    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : FooterCanvas,
        enumerable : false
    });

    if(no == null || no == undefined)
        no = "";

    this.swf_name = "footer_sm";
    this.canvas_id = this.swf_name + no;

    this.width = devicewidth;

    this.top = 0;
    this.left = 0;

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

    //outメソッドが有効かどうか
    this.out_enable = true;

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

        this.init();

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
     * すべて表示し終わった時のイベントハンドラ
     *
    */
    this.__proto__.onLoaded = function() {

        this.super.onLoaded.call(this);
    }

    /*
      初期化する
    */
    this.__proto__.init = function(){
    
        this.pex.getAPI().gotoFrame("/", "init");
    }

    this.__proto__.ref = function(){    
        this.pex.getAPI().gotoFrame("/menubar", "init");
    }

    /*
      フッターを登場させる。
    */
    this.__proto__.in = function(){
        this.out_enable = true;

        this.pex.getAPI().gotoFrame("/menubar", "in");
    }

    /*
      フッターを退場させる。
    */
    this.__proto__.out = function(start_screen, end_screen, accessor){
        if(accessor == null)
            var self = this;
        else
            var self = eval(accessor);

        //二度押し対策
        if(!self.out_enable) return;
        self.out_enable = false;

        //画面を変更する
        MainContentsDisplay.onScreenChange(start_screen, end_screen);

        //フッターに何が選択されたか伝える    
        self.pex.getAPI().setVariable("/", "selectmenu", start_screen);

        //フッターにはendをコールする
        self.pex.getAPI().gotoFrame("/menubar", "end");
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
