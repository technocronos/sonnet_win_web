
/**
 * 経験値ゲージCanvasを制御するオブジェクト。
 *
 */
function ExpGaugeCanvas(_parent, no){

    // 親クラスを継承
    this.__proto__ = new CanvasCommon();
    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : ExpGaugeCanvas,
        enumerable : false
    });


    if(no == null || no == undefined)
        no = "";

    this.swf_name = "expgauge_sm";
    this.canvas_id = this.swf_name + no;

    this.width = 108;
    this.top = 0;
    this.left = 0;

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

    //---------------------------------------------------------------------------------------------------------
    /*
     * 情報を更新してinitをコールする
    */
    this.__proto__.init = function(add_exp, exp, exp_max){

        this.pex.getAPI().setVariable("/", "add_exp", parseInt(add_exp));
        this.pex.getAPI().setVariable("/", "exp", parseInt(exp));
        this.pex.getAPI().setVariable("/", "exp_max", parseInt(exp_max));

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
  

