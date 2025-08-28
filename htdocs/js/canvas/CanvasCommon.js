
/**
 * HTML5制御を行うスクリプトに共通のタスクを実装しているクラス。
 * flasmで扱うSWFではなく、部品としてのcanvasを扱う。
 */
function CanvasCommon() {
    //swf名。canvasのIDでもある。
    this.swf_name = null;
    this.canvas_id = null;

    //canvasのスケールの倍率
    this.scale_magnification = 3.3333333333;

    this.width = 0;
    this.height = 0; //widthが決まってれば自動計算してくれるっぽいので基本設定しない
    this.top = 0;
    this.left = 0;

    this.canvas = null;
    this.pex = null;
    this.handler = null;
    this._parent = null;

    //クラス名
    this.ClassName = "";

    this.devicewidth = $(window).width();
    this.deviceheight = $(window).height();

    this.PartialDraw = true;

    this.shapeDetail = { all: { method: "func" } }

    this.loaded = false;

    //---------------------------------------------------------------------------------------------------------
    /**
     * 画面がスタートされた際に実行される。
     * 子クラスのstartから　this.super.start.call(this);　のようにコールすること。
     *
     */
    this.__proto__.start = function (){
        console.log( this.ClassName + ".start rannning...");

        var self = this;

        if(self.canvas_id == null)
            self.canvas_id = self.swf_name;

        self.canvas = document.getElementById(self.canvas_id);
        var swf_url = AppUtil.htmlspecialchars_decode(apiOnSwfResource).replace("swf_name_string", self.swf_name);

        // 実際のサイズを格納（この値に合わせる）
        var scale = window.devicePixelRatio; // この値を1にすると、Retinaディスプレイではぼやけるようになります

        // ズームをかける
        self.zoom(scale);

        // キャンバスの位置調整。
        self.pos(self.left, self.top);

        self.pex = new Pex(swf_url, self.canvas, {
            "width": self.width * scale,
            "height": self.height * scale,
            "debug": false,
            "enableButton": true,
            "enableTouch": true,
            "transparent": true,
            "partialDraw": self.PartialDraw,
            "shapeDetail": self.shapeDetail,
            "stopOnStart": true
        });

        // SWF の再生準備が整ったタイミングで再生を開始する。でないとクエストがうまく動かなかった。
        self.pex.getAPI().ready(function() { 
            self.pex.getAPI().engineStart(); 
            //フレームイベントを設定
            self.handler = onEnterFrame.bind(self);
            self.pex.getAPI().addEventListener("enterframe", self.handler ,"/");

/*
            //仮コード。本当はenterframeを使いたいがリークするので解決法が見つかるまで。
            var timeout_id = setTimeout(function(){
                clearTimeout(timeout_id);
                //クラス名をcanvasに伝える
                self.pex.getAPI().setVariable("/", "class_name", self._parent.constructor.name + "." + self.ClassName);

                self.reload();
            },500);
*/

        });
    }


    var onEnterFrame = function(api, name, currentframe) {
        if(currentframe == 2){
            var self = this;
            //この地点でpexイベントリスナは破棄しないと何度もコールされる
            //var onEnterFrame = this[0];
            api.removeEventListener("enterframe", this.handler ,"/");

            //クラス名をcanvasに伝える
            api.setVariable("/", "class_name", self._parent.constructor.name + "." + self.ClassName);

            self.reload();

        }
    }

    //---------------------------------------------------------------------------------------------------------
    /**
     * startメソッドから自動的に呼ばれる画面HTML作成用メソッド。
     * 基底では何もしないのでオーバーライドして使う。
     *
     * startから直接呼ばれるかsoundLoadのコールバックとして呼ばれる。
     * 後者の場合、thisが仕えないのでこの関数内ではthisは使わない方が無難。
     * 
     */
    this.__proto__.reload = function() {
        var self = this;

        self.onLoaded();

    }

    //---------------------------------------------------------------------------------------------------------
    /*
     * EntryBoardがすべて表示し終わった時のイベントハンドラ
     * 
     *
    */
    this.__proto__.onLoaded = function() {
        var self = this;

        self.loaded = true;

        console.log(this.ClassName + "onLoaded start..");
    }

    /*
      画像を動的に差し替える
    */
    this.__proto__.changeImage = function(imageUrl,movie_name, sizeW, sizeH, phase_movie){
        var self = this;

        //マップを読んでおく
        var image = new Image();
        image.src = AppUrl.asset(imageUrl);

        image.onload = function() { 
            self.pex.getAPI().replaceMovieClip(movie_name, image, sizeW, sizeH, true, 0, 0);
            if(phase_movie != "" && phase_movie != undefined){
                //phase_movieに終了を伝える
                self.pex.getAPI().setVariable(phase_movie, "change", "ok");
            }
        }
    }

    /*
      画像の縮尺を調整する
    */
    this.__proto__.zoom = function(scale){
        var self = this;

        // キャンバスのサイズ調整。普通にcanvasのstyleで調整したいとこだが、ボタン領域が追随してくれない。
        var zoom = ((document.body.clientWidth / (window.innerWidth * scale)) * 100);
        self.canvas.style.zoom = zoom + "%";
    }

    /*
      画像の位置を調整する
    */
    this.__proto__.pos = function(left, top){
        var self = this;

        var scale = window.devicePixelRatio;

        // キャンバスの位置調整。
        if(left != null)
            self.canvas.style.left = (left * scale) + "px";

        if(top != null)
            self.canvas.style.top = (top * scale) + "px";
    }

    //---------------------------------------------------------------------------------------------------------
    /*
      destroyメソッドを定義する
    */
    this.__proto__.destroy = function (){
        var self = this;
        console.log( self.ClassName + ".destroy rannning...");

        self.pex.getAPI().destroy();
        self.pex = null;
        self.canvas = null;

        self.handler = null;
        onEnterFrame = null;
    }

}