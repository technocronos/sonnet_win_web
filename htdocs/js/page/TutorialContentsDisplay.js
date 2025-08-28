
/**
 * チュートリアルでのドラマFlashを制御するシングルトンオブジェクト。
 *
 */
function TutorialContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "TutorialContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    this.shapeDetail = { all: { method: "func" } }

    this.contentheight = 0;

    $(window).scrollTop(0);
}

// 親クラスを継承
TutorialContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
TutorialContentsDisplay.prototype.start = function() {
console.log("TutorialContentsDisplay.start rannning...");
    var self = TutorialContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));

    self.super.start.call(self);
}

TutorialContentsDisplay.prototype.swfstart = function(pex, callback) {
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
TutorialContentsDisplay.prototype.reload = function (){
    var self = TutorialContentsDisplay;

    $("#flex").hide();

    $(window).scrollTop(0);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
TutorialContentsDisplay.prototype.onLoaded = function() {
    var self = TutorialContentsDisplay;
console.log("self.contentheight=" + self.contentheight)
    //メインウィンドウ位置調整
    Page.setDramaWin(pex.getAPI(), self.contentheight);

    pex.getAPI().gotoFrame("/", "onFirstTap");

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
TutorialContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    TutorialContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var TutorialContentsDisplay = new TutorialContentsDisplay();

$(document).ready(TutorialContentsDisplay.start.bind(TutorialContentsDisplay));

