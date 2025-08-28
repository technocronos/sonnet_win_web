
/**
 * ドラマFlashを制御するシングルトンオブジェクト。
 *
 */
function DetainContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "DetainContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    this.contentheight = 0;

    $(window).scrollTop(0);
}

// 親クラスを継承
DetainContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
DetainContentsDisplay.prototype.start = function() {
console.log("DetainContentsDisplay.start rannning...");
    var self = DetainContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));

    self.super.start.call(self);
}

DetainContentsDisplay.prototype.swfstart = function(pex, callback) {
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
DetainContentsDisplay.prototype.reload = function (){
    var self = DetainContentsDisplay;

    $("#flex").hide();

    $(window).scrollTop(0);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
DetainContentsDisplay.prototype.onLoaded = function() {
    var self = DetainContentsDisplay;

    //メインウィンドウ位置調整
    Page.setDramaWin(pex.getAPI(), self.contentheight);

    pex.getAPI().gotoFrame("/", "onFirstTap");

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
DetainContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    DetainContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var DetainContentsDisplay = new DetainContentsDisplay();

$(document).ready(DetainContentsDisplay.start.bind(DetainContentsDisplay));

