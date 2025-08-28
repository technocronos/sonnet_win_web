
/**
 * バトルFlashを制御するシングルトンオブジェクト。
 *
 */
function WakuStartDushCampainContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "WakuStartDushCampainContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    this.contentheight = 0;

    this.shapeDetail = { all: { method: "func" } }

    $(window).scrollTop(0);
}

// 親クラスを継承
WakuStartDushCampainContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
WakuStartDushCampainContentsDisplay.prototype.start = function() {
console.log("WakuStartDushCampainContentsDisplay.start rannning...");
    var self = WakuStartDushCampainContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));

    self.super.start.call(self);
}

WakuStartDushCampainContentsDisplay.prototype.swfstart = function(pex, callback) {
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
WakuStartDushCampainContentsDisplay.prototype.reload = function (){
    var self = WakuStartDushCampainContentsDisplay;

    $("#flex").hide();

    $(window).scrollTop(0);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
WakuStartDushCampainContentsDisplay.prototype.onLoaded = function() {
    var self = WakuStartDushCampainContentsDisplay;

    //メインウィンドウ位置調整
    Page.setDramaWin(pex.getAPI(), self.contentheight);

    pex.getAPI().gotoFrame("/", "onFirstTap");

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
WakuStartDushCampainContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    WakuStartDushCampainContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var WakuStartDushCampainContentsDisplay = new WakuStartDushCampainContentsDisplay();

$(document).ready(WakuStartDushCampainContentsDisplay.start.bind(WakuStartDushCampainContentsDisplay));

