
/**
 * 差し込みの寸劇Flashを制御するシングルトンオブジェクト。
 *
 */
function TerminableContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "TerminableContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    this.shapeDetail = { all: { method: "func" } }

    this.contentheight = 0;

    $(window).scrollTop(0);
}

// 親クラスを継承
TerminableContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
TerminableContentsDisplay.prototype.start = function() {
console.log("TerminableContentsDisplay.start rannning...");
    var self = TerminableContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));

    self.super.start.call(self);
}

TerminableContentsDisplay.prototype.swfstart = function(pex, callback) {
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
TerminableContentsDisplay.prototype.reload = function (){
    var self = TerminableContentsDisplay;

    $("#flex").hide();

    $(window).scrollTop(0);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
TerminableContentsDisplay.prototype.onLoaded = function() {
    var self = TerminableContentsDisplay;

    //メインウィンドウ位置調整
    Page.setDramaWin(pex.getAPI(), self.contentheight);

    pex.getAPI().gotoFrame("/", "onFirstTap");

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
TerminableContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    TerminableContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var TerminableContentsDisplay = new TerminableContentsDisplay();

$(document).ready(TerminableContentsDisplay.start.bind(TerminableContentsDisplay));

