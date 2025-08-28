
/**
 * バトルFlashを制御するシングルトンオブジェクト。
 *
 */
function FieldDramaContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "FieldDramaContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    //スクロール無効
    $("#bottom-div").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.shapeDetail = { all: { method: "func" } }

    this.contentheight = 0;

    $(window).scrollTop(0);
}

// 親クラスを継承
FieldDramaContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
FieldDramaContentsDisplay.prototype.start = function() {
console.log("FieldDramaContentsDisplay.start rannning...");
    var self = FieldDramaContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));

    self.super.start.call(self);
}


FieldDramaContentsDisplay.prototype.swfstart = function(pex, callback) {
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
FieldDramaContentsDisplay.prototype.reload = function (){
    var self = FieldDramaContentsDisplay;

    $("#flex").hide();

    $(window).scrollTop(0);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
FieldDramaContentsDisplay.prototype.onLoaded = function() {
    var self = FieldDramaContentsDisplay;

    //メインウィンドウ位置調整
    Page.setDramaWin(pex.getAPI(), self.contentheight);

    pex.getAPI().gotoFrame("/", "onFirstTap");

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
FieldDramaContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    FieldDramaContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var FieldDramaContentsDisplay = new FieldDramaContentsDisplay();

$(document).ready(FieldDramaContentsDisplay.start.bind(FieldDramaContentsDisplay));

