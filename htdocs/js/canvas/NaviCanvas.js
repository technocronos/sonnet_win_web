

/**
 * キャラCanvasを制御するシングルトンオブジェクト。
 *
 */
function NaviCanvas(_parent, no, summary, top = 120, width = 240, left = 0){

    // 親クラスを継承
    this.__proto__ = new CanvasCommon();
    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : NaviCanvas,
        enumerable : false
    });

    if(no == null || no == undefined)
        no = "";

    this.swf_name = "navi_sm";
    this.canvas_id = this.swf_name + no;

    this.width = width;
    this.top = top;
    this.left = left;

    this.width = this.width * this.scale_magnification;
    this.height = this.height * this.scale_magnification;
/*
    this.PartialDraw = false;
    this.shapeDetail = {
      "all": { "method":"func"},
    };
*/
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
    console.log("NaviCanvas.start rannning...");

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
    /**
     * 再度セリフを言う場合
     */
    this.__proto__.reset = function (){
        this.set();

        this.pex.getAPI().gotoFrame("/nav", "init");

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

        //初期化
        this.pex.getAPI().gotoFrame("/", "init");
    }
    /*
      ナビを待機状態にさせる
    */
    this.__proto__.wait = function(){

        this.pex.getAPI().gotoFrame("/nav", "wait");
    }
    /*
      ナビを登場させる
    */
    this.__proto__.appear = function(){

        this.pex.getAPI().gotoFrame("/nav", "appear");
    }
    /*
      ナビを退場させる
    */
    this.__proto__.out = function(){

        this.pex.getAPI().gotoFrame("/nav", "out");
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

