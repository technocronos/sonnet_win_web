
/**
 * ドラマFlashを制御するシングルトンオブジェクト。
 *
 */
function SummerCampaignContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "SummerCampaignContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    this.contentheight = 0;

    $(window).scrollTop(0);
}

// 親クラスを継承
SummerCampaignContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
SummerCampaignContentsDisplay.prototype.start = function() {
console.log("SummerCampaignContentsDisplay.start rannning...");
    var self = SummerCampaignContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));

    self.super.start.call(self);
}

SummerCampaignContentsDisplay.prototype.swfstart = function(pex, callback) {
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
SummerCampaignContentsDisplay.prototype.reload = function (){
    var self = SummerCampaignContentsDisplay;

    $("#flex").hide();

    $(window).scrollTop(0);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
SummerCampaignContentsDisplay.prototype.onLoaded = function() {
    var self = SummerCampaignContentsDisplay;

    //メインウィンドウ位置調整
    Page.setDramaWin(pex.getAPI(), self.contentheight);

    pex.getAPI().gotoFrame("/", "onFirstTap");

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
SummerCampaignContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    SummerCampaignContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var SummerCampaignContentsDisplay = new SummerCampaignContentsDisplay();

$(document).ready(SummerCampaignContentsDisplay.start.bind(SummerCampaignContentsDisplay));

