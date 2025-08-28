
/**
 * プロローグFlashを制御するシングルトンオブジェクト。
 *
 */
function PrologueContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //コンストラクタ名を書き換える
    Object.defineProperty(PrologueContentsDisplay.prototype, 'constructor', {
        value : PrologueContentsDisplay,
        enumerable : false
    });

    //クラス名
    this.ClassName = this.constructor.name;

    $(window).scrollTop(0);

    this.shapeDetail = { all: { method: "func" } }
}

// 親クラスを継承
PrologueContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
PrologueContentsDisplay.prototype.start = function() {
console.log("PrologueContentsDisplay.start rannning...");
    var self = PrologueContentsDisplay;

    self.super.start.call(self);
}

PrologueContentsDisplay.prototype.swfstart = function(pex, callback) {
    var timer = null;
    $(function(){
        timer = setInterval(function(){
        var CurrentFrame = pex.getAPI().getCurrentFrame("/");
            if(CurrentFrame == 4){
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
PrologueContentsDisplay.prototype.reload = function (){
    var self = PrologueContentsDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
PrologueContentsDisplay.prototype.onLoaded = function() {
    var self = PrologueContentsDisplay;

    if(is_tablet == "tablet"){
        $("#container").css("margin-top", "-36%");
    }

    pex.getAPI().gotoFrame("/", "onFirstTap");

    $(window).scrollTop(0);

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * 登録画面表示
 *
*/
PrologueContentsDisplay.prototype.showAvatarCreate = function() {
    var self = PrologueContentsDisplay;

    var d = new Dialogue();

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(AvatarCreateInputHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;
    d.opacity = 0.6;

    d.show();

    //もう必要ないのでdestroyしてしまう
    //self.destroy();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
PrologueContentsDisplay.prototype.destroy = function (){
    pex.getAPI().destroy();

    this.super.destroy.call(this);
    PrologueContentsDisplay = null;
}

PrologueContentsDisplay.prototype.navi_speak_end = function() {
    AvatarCreateInputDisplay.navi_speak_end();
}

PrologueContentsDisplay.prototype.navi_speak_end2 = function() {
    AvatarCreateInputDisplay.navi_speak_end2();

    $("#container").fadeOut("slow", function(){
        pex.getAPI().gotoFrame("/", "push");
    });
}

//---------------------------------------------------------------------------------------------------------
var PrologueContentsDisplay = new PrologueContentsDisplay();

$(document).ready(PrologueContentsDisplay.start.bind(PrologueContentsDisplay));

