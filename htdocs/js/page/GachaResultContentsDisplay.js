
/**
 * ガチャFlashを制御するシングルトンオブジェクト。
 *
 */
function GachaResultContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "GachaResultContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";


    //スクロール無効
    $("#bottom-div").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    $(window).scrollTop(0);

    this.shapeDetail = { all: { method: "func" } }
}

// 親クラスを継承
GachaResultContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
GachaResultContentsDisplay.prototype.start = function() {
console.log("GachaResultContentsDisplay.start rannning...");
    var self = GachaResultContentsDisplay;

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * html5へのタッチハンドラ。
 */
GachaResultContentsDisplay.prototype.swfstart = function(pex, callback) {
    var timer = null;
    $(function(){
        timer = setInterval(function(){
        var CurrentFrame = pex.getAPI().getCurrentFrame("/");
            if(CurrentFrame == 5){
                //loading..表示タイマーストップ
                clearInterval(timer);

                pex.getAPI().setVariable("/", "sound_loaded", 1);
                audio.sndfx.sound_loaded = 1;

                callback();

                return;

            }
        },100);
    }); 
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
GachaResultContentsDisplay.prototype.reload = function (){
    var self = GachaResultContentsDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
GachaResultContentsDisplay.prototype.onLoaded = function() {
    var self = GachaResultContentsDisplay;

    if(is_tablet == "tablet"){
        $("#container").css("margin-top", "-8%");
    }

    pex.getAPI().gotoFrame("/", "onFirstTap");

    $(window).scrollTop(0);

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
GachaResultContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    GachaResultContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var GachaResultContentsDisplay = new GachaResultContentsDisplay();

$(document).ready(GachaResultContentsDisplay.start.bind(GachaResultContentsDisplay));

