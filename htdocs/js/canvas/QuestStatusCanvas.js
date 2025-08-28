
/**
 * クエストのステータスCanvasを制御するオブジェクト。
 * @param _parent インスタンスを生成した親
 *        no      インスタンスのナンバー。一つだけ生成ならnullで。
 *        x,y     インスタンスのleftとtop
 *        status_num     1:new 2：未クリア　3：クリア　4：実行中
 */
function QuestStatusCanvas(_parent, no, x, y, status_num){

    // 親クラスを継承
    this.__proto__ = new CanvasCommon();
    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : QuestStatusCanvas,
        enumerable : false
    });

    if(no == null || no == undefined)
        no = "";

    this.swf_name = "quest_status_sm";
    this.canvas_id = this.swf_name + no;

    this.width = 30;
    this.top = y;
    this.left = x;
    this.status_num = status_num;

    this.width = this.width * this.scale_magnification;
    this.height = this.height * this.scale_magnification;

    this.PartialDraw = false;

    //newしたクラス名
    this._parent = _parent;

    //クラス名
    this.ClassName = this.constructor.name + no;

    //---------------------------------------------------------------------------------------------------------
    /**
     * ページが読み込まれたら呼ばれる。
     */
    this.__proto__.start = function() {
    console.log("QuestStatusCanvas.start rannning...");
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
        this.pex.getAPI().setVariable("/", "status_num", this.status_num);
        //初期化
        this.pex.getAPI().gotoFrame("/", "init");
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