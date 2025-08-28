
/**
 * キャラCanvasを制御するオブジェクト。
 *
 */
function CharaCanvas(_parent,no,user_id,image_url, top = null, width = 80){
    // 親クラスを継承
    this.__proto__ = new CanvasCommon();

    //コンストラクタ名を書き換える
    Object.defineProperty(this.__proto__, 'constructor', {
        value : CharaCanvas,
        enumerable : false
    });

    if(no == null || no == undefined)
        no = "";

    this.swf_name = "chara_sm";
    this.canvas_id = this.swf_name + no;

    this.width = width;
    this.top = top;
    this.left = -60;

    this.width = this.width * this.scale_magnification;
    this.height = this.height * this.scale_magnification;

    this.user_id = user_id;
    this.image_url = image_url;

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
        //必要な情報を渡す
        this.pex.getAPI().setVariable("/", "chara_user_id", this.user_id);
        this.pex.getAPI().setVariable("/", "imageUrl", this.image_url);

        //初期化
        this.pex.getAPI().gotoFrame("/", "init");
    }
    /*
      画像を動的に差し替える
    */
    this.__proto__.in = function(){
        this.pex.getAPI().gotoFrame("/chara", "in");
    }

    /*
      画像を動的に差し替える
    */
    this.__proto__.out = function(){
        this.pex.getAPI().gotoFrame("/chara", "out");
    }

    /*
      画像を動的に差し替える
    */
    this.__proto__.fadein = function(){
        this.pex.getAPI().gotoFrame("/chara", "fadein");
    }

    /*
      画像を動的に差し替える
    */
    this.__proto__.fadeout = function(){
        this.pex.getAPI().gotoFrame("/chara", "fadeout");
    }

    /*
      画像を動的に差し替える
    */
    this.__proto__.change = function(image_url){
        this.pex.getAPI().setVariable("/", "imageUrl", image_url);
        this.pex.getAPI().gotoFrame("/chara", "change");
    }

    /*
      キャラ画像を動的に差し替える
    */
    this.__proto__.changeImage = function(imageUrl,movie_name, sizeW, sizeH, phase_movie,accessor){        
//console.log(imageUrl);
        var self = eval(accessor);
        self.super.changeImage.call(self, imageUrl,movie_name, sizeW, sizeH, phase_movie);
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

