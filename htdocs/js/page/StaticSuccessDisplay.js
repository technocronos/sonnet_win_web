
/**
 * 階級アップ結果を制御するシングルトンオブジェクト。
 *
 */
function StaticSuccessDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(StaticSuccessDisplay.prototype, 'constructor', {
        value : StaticSuccessDisplay,
        enumerable : false
    });

    //スクロールをストップする
    $(window).on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //スクロールをストップする
    $("#out").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
StaticSuccessDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
StaticSuccessDisplay.prototype.start = function() {
console.log("StaticSuccessDisplay.start rannning...");
    var self = StaticSuccessDisplay;

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
StaticSuccessDisplay.prototype.reload = function (){
    var self = StaticSuccessDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
StaticSuccessDisplay.prototype.onLoaded = function() {
    var self = StaticSuccessDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
StaticSuccessDisplay.prototype.destroy = function (){
    var self = StaticSuccessDisplay;

    self.super.destroy.call(self);
    StaticSuccessDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var StaticSuccessDisplay = new StaticSuccessDisplay();

$(document).ready(StaticSuccessDisplay.start.bind(StaticSuccessDisplay));

