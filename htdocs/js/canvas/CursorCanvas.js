
/**
 * カーソルCanvasを制御するシングルトンオブジェクト。
 * @param _parent インスタンスを生成した親
 *        no      インスタンスのナンバー。一つだけ生成ならnullで。
 *        x,y     インスタンスのleftとtop
 */
function CursorCanvas(_parent, no, x, y){

    // 親クラスを継承
    this.__proto__ = new CanvasCommon();
    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : CursorCanvas,
        enumerable : false
    });

    if(no == null || no == undefined)
        no = "";

    this.swf_name = "cursor_sm";
    this.canvas_id = this.swf_name + no;

    this.width = 34 * 0.7;
    this.top = y;
    this.left = x;

    this.width = this.width * this.scale_magnification;
    this.height = this.height * this.scale_magnification;

    this.PartialDraw = false;
    this.shapeDetail = {
      "all": { "method":"func"},
    };

    //newしたクラス名
    this._parent = _parent;

    //クラス名
    this.ClassName = this.constructor.name + no;

    //---------------------------------------------------------------------------------------------------------
    /**
     * ページが読み込まれたら呼ばれる。
     */
    this.__proto__.start = function() {
    console.log("CursorCanvas.start rannning...");
        this.super.start.call(this);
    }

    //---------------------------------------------------------------------------------------------------------
    /**
     * メイン処理
     */
    this.__proto__.reload = function (){
        this.super.reload.call(this);
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

    }
    //---------------------------------------------------------------------------------------------------------
    /**
     * ページのオブジェクトを破棄する。
     */
    this.__proto__.destroy = function (){
        var self = this;
        self.super.destroy.call(self);
    }

    this.super = CanvasCommon.prototype;

    //startをコール
    this.start();
}