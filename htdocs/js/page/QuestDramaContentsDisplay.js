
/**
 * バトルFlashを制御するシングルトンオブジェクト。
 *
 */
function QuestDramaContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "QuestDramaContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    this.contentheight = 0;

    $(window).scrollTop(0);
}

// 親クラスを継承
QuestDramaContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
QuestDramaContentsDisplay.prototype.start = function() {
console.log("QuestDramaContentsDisplay.start rannning...");
    var self = QuestDramaContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));

    self.super.start.call(self);
}

QuestDramaContentsDisplay.prototype.swfstart = function(pex, callback) {
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
QuestDramaContentsDisplay.prototype.reload = function (){
    var self = QuestDramaContentsDisplay;

    $("#flex").hide();

    $(window).scrollTop(0);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
QuestDramaContentsDisplay.prototype.onLoaded = function() {
    var self = QuestDramaContentsDisplay;

    //メインウィンドウ位置調整
    Page.setDramaWin(pex.getAPI(), self.contentheight);

    pex.getAPI().gotoFrame("/", "onFirstTap");


    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
QuestDramaContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    QuestDramaContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var QuestDramaContentsDisplay = new QuestDramaContentsDisplay();

$(document).ready(QuestDramaContentsDisplay.start.bind(QuestDramaContentsDisplay));

