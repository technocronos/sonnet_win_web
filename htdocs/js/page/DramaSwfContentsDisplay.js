
/**
 * ドラマFlashを制御するシングルトンオブジェクト。管理ページでの再現用。
 *
 */
function DramaSwfContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "DramaSwfContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    //スクロール無効
    $("#bottom-div").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.shapeDetail = { all: { method: "func" } }

    $(window).scrollTop(0);
}

// 親クラスを継承
DramaSwfContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
DramaSwfContentsDisplay.prototype.start = function() {
console.log("DramaSwfContentsDisplay.start rannning...");
    var self = DramaSwfContentsDisplay;

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * loadingつきでサウンドを読み込む
 * 各画面で個別のサウンド処理がある場合にはオーバーライドして実装すること。
 */
DisplayCommon.prototype.soundLoad = function(callback){
    var self = this;

    var canvas = document.getElementById("container");

    // 実際のサイズを格納（この値に合わせる）
    var scale = window.devicePixelRatio; // この値を1にすると、Retinaディスプレイではぼやけるようになります

    // キャンバスのサイズ調整。普通にcanvasのstyleで調整したいとこだが、ボタン領域が追随してくれない。
    canvas.style.zoom = ((self.devicewidth / (self.devicewidth * scale)) * 100) + "%";

    if(self.canvas_margin > 0)
        canvas.style.top = (self.canvas_margin / ((self.devicewidth / (self.devicewidth * scale)) * 100)) + "px";

    pex = new Pex(swf, canvas, {
        "width": self.devicewidth * scale,
        "height": self.deviceheight * scale,
        "debug": false,
        "enableButton": true,
        "enableTouch": true,
        "transparent": this.transparent,
        "partialDraw": PartialDraw, //true推奨だがアニメーションが動かない場合はfalseを設定する
        "shapeDetail": this.shapeDetail,
        "stopOnStart": true
    });

    self.beforeStart();

    // SWF の再生準備が整ったタイミングで再生を開始する。でないとクエストがうまく動かなかった。
    pex.getAPI().ready(function() { 
        $("#mini_loading").hide();
        pex.getAPI().engineStart(); 

        //clientWidthをここで渡しておく。全SWF共通処理。
        pex.getAPI().setVariable("/", "clientWidth", self.devicewidth);

        //web audio APIを使う場合、サウンドを読み込む
        audio.sndfx = new webaudio;

        //読みこむファイルの個数
        audio.sndfx.all_file_count = selist.length;

        //loadは配列で渡せます。{alias:"音の名前","src":"音声ファイルのパス"}で。
        audio.sndfx.load(selist, pex, callback);

        //タッチ用イベントハンドラ
        canvas.addEventListener("touchstart", eval(self.ClassName+".touchHandler"), false);
        canvas.addEventListener("touchmove", eval(self.ClassName+".touchHandler"), false);
        canvas.addEventListener("touchend", eval(self.ClassName+".touchHandler"), false);
        canvas.addEventListener("click", eval(self.ClassName+".touchHandler"), false);
    });
}


//---------------------------------------------------------------------------------------------------------
/**
 * html5へのタッチハンドラ。
 */
DramaSwfContentsDisplay.prototype.touchHandler = function(e) {
    e.preventDefault();

    //管理P確認用
    if(e.type == "click"){
        //初回
        if(audio.sndfx.play_cnt('se_btn') == 0 && audio.sndfx.sound_loaded == 1){
            pex.getAPI().gotoFrame("/", "onFirstTap");
            //メインウィンドウ位置調整
            var height = (Page.getContentsHeight() + 120) - 428;
            pex.getAPI().setPosition("/main/window", 0, height);
        }
          //まだ鳴らされてないならBGMを鳴らす
          if(audio.currBgm == undefined){
              if(audio.sndfx.ctx.state == "suspended"){
                  audio.sndfx.ctx.resume();
              }

              sound('bgm_mute');
          }

        return;
    }

    var touch = e.touches[0];
    var changedTouches = e.changedTouches[0];

    if(e.type == "touchstart"){
          //座標を渡しておく
          pex.getAPI().setVariable("/", "touchstartX", touch.pageX);
          pex.getAPI().setVariable("/", "touchstartY", touch.pageY);
          if(carrier == 'android'){
             if(audio.sndfx.play_cnt('se_btn') == 0 && audio.sndfx.sound_loaded == 1){
                  pex.getAPI().gotoFrame("/", "onFirstTap");
                  //メインウィンドウ位置調整
                  Page.setDramaWin(pex.getAPI());
              }
          }
      }
    if(e.type == "touchmove"){

      }
    if(e.type == "touchend"){
          pex.getAPI().setVariable("/", "touchendX", changedTouches.pageX);
          pex.getAPI().setVariable("/", "touchendY", changedTouches.pageY);
          if(carrier == 'iphone'){
             if(audio.sndfx.play_cnt('se_btn') == 0 && audio.sndfx.sound_loaded == 1){
                pex.getAPI().gotoFrame("/", "onFirstTap");
                    //メインウィンドウ位置調整
                    Page.setDramaWin(pex.getAPI());
                }
          }

          //まだ鳴らされてないならBGMを鳴らす
          if(audio.currBgm == undefined){
              if(audio.sndfx.ctx.state == "suspended"){
                  audio.sndfx.ctx.resume();
              }

              sound('bgm_mute');
          }
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
DramaSwfContentsDisplay.prototype.reload = function (){
    var self = DramaSwfContentsDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
DramaSwfContentsDisplay.prototype.onLoaded = function() {
    var self = DramaSwfContentsDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
DramaSwfContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    DramaSwfContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var DramaSwfContentsDisplay = new DramaSwfContentsDisplay();

$(document).ready(DramaSwfContentsDisplay.start.bind(DramaSwfContentsDisplay));

