
/**
 * 階級アップ結果を制御するシングルトンオブジェクト。
 *
 */
function AvatarCreateFinishDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(AvatarCreateFinishDisplay.prototype, 'constructor', {
        value : AvatarCreateFinishDisplay,
        enumerable : false
    });

    //スクロールをストップする
    $(window).on('touchmove.noScroll', function(e) {
        if(PLATFORM_TYPE != "geso")
            e.preventDefault();
    });

    //スクロールをストップする
    $("#out").on('touchmove.noScroll', function(e) {
        if(PLATFORM_TYPE != "geso")
            e.preventDefault();
    });

    //スクロールをストップする
    $("#AvatarCreateFinish").on('touchmove.noScroll', function(e) {
            e.preventDefault();
    });

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
AvatarCreateFinishDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
AvatarCreateFinishDisplay.prototype.start = function() {
console.log("AvatarCreateFinishDisplay.start rannning...");
    var self = AvatarCreateFinishDisplay;

    var ratio = devicewidth / 750;
    $("#AvatarCreateFinish").css("width",(devicewidth / ratio) + "px");
    $("#AvatarCreateFinish").css("transform","scale(" + ratio + ")");
    $("#AvatarCreateFinish").css("transform-origin","0px 0px");

    //高さが設定してある場合、縦中央ぞろえ
    $("#maincontent").css("top",deviceheight + "px");
    $("#maincontent").css("margin-top", "-" + ($("#maincontent").outerHeight() / 2) + "px");

    $("#btn_ok").off('click').on('click',function() {
        sound("se_btn");
        sound_stop();
        setTimeout(function(){
            location.href = AppUrl.htmlspecialchars_decode( tuto_url , 'ENT_NOQUOTES' );
        }, 500);
    });

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
AvatarCreateFinishDisplay.prototype.reload = function (){
    var self = AvatarCreateFinishDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
AvatarCreateFinishDisplay.prototype.onLoaded = function() {
    var self = AvatarCreateFinishDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
AvatarCreateFinishDisplay.prototype.destroy = function (){
    var self = AvatarCreateFinishDisplay;

    self.super.destroy.call(self);
    AvatarCreateFinishDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var AvatarCreateFinishDisplay = new AvatarCreateFinishDisplay();

$(document).ready(AvatarCreateFinishDisplay.start.bind(AvatarCreateFinishDisplay));

