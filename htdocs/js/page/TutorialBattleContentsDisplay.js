
/**
 * バトルFlashを制御するシングルトンオブジェクト。
 *
 */
function TutorialBattleContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "TutorialBattleContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    //背景透過
    this.transparent = true;

    //スクロール無効
    $("#bottom-div").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //背景スクロールをストップする
    $("#bg").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //背景スクロールをストップする
    $("#canvas_bg").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    $(window).scrollTop(0);
}

// 親クラスを継承
TutorialBattleContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
TutorialBattleContentsDisplay.prototype.start = function() {
console.log("TutorialBattleContentsDisplay.start rannning...");
    var self = TutorialBattleContentsDisplay;

    self.super.start.call(self);
}

TutorialBattleContentsDisplay.prototype.swfstart = function(pex, callback) {
    var timer = null;
    $(function(){
        timer = setInterval(function(){
        var CurrentFrame = pex.getAPI().getCurrentFrame("/main");
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
TutorialBattleContentsDisplay.prototype.reload = function (){
    var self = TutorialBattleContentsDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
TutorialBattleContentsDisplay.prototype.onLoaded = function() {
    var self = TutorialBattleContentsDisplay;

    if(is_tablet == "tablet"){
        $("#canvas_bg").css("top", "-8%");
        $("#container").css("margin-top", "-14%");
    }

    pex.getAPI().gotoFrame("/main", "onFirstTap");

    $(window).scrollTop(0);

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
TutorialBattleContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    TutorialBattleContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var TutorialBattleContentsDisplay = new TutorialBattleContentsDisplay();

$(document).ready(TutorialBattleContentsDisplay.start.bind(TutorialBattleContentsDisplay));

