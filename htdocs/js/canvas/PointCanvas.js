
/**
 * ポイントCanvasを制御するオブジェクト。
 * @param _parent インスタンスを生成した親
 *        no      インスタンスのナンバー。一つだけ生成ならnullで。
 *        x,y     インスタンスのleftとtop
 *        num     ポイントの状態を指定。1：赤　2：青　3：アイコンあり
 */
function PointCanvas(_parent, no, x, y, num){
    // 親クラスを継承
    this.__proto__ = new CanvasCommon();
    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : PointCanvas,
        enumerable : false
    });

    if(no == null || no == undefined)
        no = "";

    this.swf_name = "point_sm";
    this.canvas_id = this.swf_name + no;

    this.width = 48 * 0.7;

    this.top = y;
    this.left = x;
    this.num = num;

    this.width = this.width * this.scale_magnification;
    this.height = this.height * this.scale_magnification;

    this.PartialDraw = true;

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
    console.log("PointCanvas.start rannning...");
        

        this.super.start.call(this);
    }

    //---------------------------------------------------------------------------------------------------------
    /**
     * メイン処理
     */
    this.__proto__.reload = function (){

        this.init();

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
        this.pex.getAPI().setVariable("/", "num", this.num);
        //初期化
        this.pex.getAPI().gotoFrame("/", "init");
    }

    //---------------------------------------------------------------------------------------------------------
    /**
     * ページのオブジェクトを破棄する。
     */
    this.__proto__.destroy = function (){
        this.super.destroy.call(this);
    }

    //親メソッドを登録
    this.super = CanvasCommon.prototype;

    //startをコール
    this.start();
}