
/**
 * 階級アップ結果を制御するシングルトンオブジェクト。
 *
 */
function SessionExpiredSuccessDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(SessionExpiredSuccessDisplay.prototype, 'constructor', {
        value : SessionExpiredSuccessDisplay,
        enumerable : false
    });

    //スクロールをストップする
    $(window).on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
SessionExpiredSuccessDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
SessionExpiredSuccessDisplay.prototype.start = function() {
console.log("SessionExpiredSuccessDisplay.start rannning...");
    var self = SessionExpiredSuccessDisplay;

    self.reload();
    //self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
SessionExpiredSuccessDisplay.prototype.reload = function (){
    var self = SessionExpiredSuccessDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
SessionExpiredSuccessDisplay.prototype.onLoaded = function() {
    var self = SessionExpiredSuccessDisplay;

    //self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
SessionExpiredSuccessDisplay.prototype.destroy = function (){
    var self = SessionExpiredSuccessDisplay;

    self.super.destroy.call(self);
    SessionExpiredSuccessDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var SessionExpiredSuccessDisplay = new SessionExpiredSuccessDisplay();

$(document).ready(SessionExpiredSuccessDisplay.start.bind(SessionExpiredSuccessDisplay));

