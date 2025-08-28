
/**
 * 階級アップ結果を制御するシングルトンオブジェクト。
 *
 */
function ItemGetSuccessDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ItemGetSuccessDisplay.prototype, 'constructor', {
        value : ItemGetSuccessDisplay,
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
ItemGetSuccessDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ItemGetSuccessDisplay.prototype.start = function() {
console.log("ItemGetSuccessDisplay.start rannning...");
    var self = ItemGetSuccessDisplay;

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ItemGetSuccessDisplay.prototype.reload = function (){
    var self = ItemGetSuccessDisplay;


    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
ItemGetSuccessDisplay.prototype.onLoaded = function() {
    var self = ItemGetSuccessDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ItemGetSuccessDisplay.prototype.destroy = function (){
    var self = ItemGetSuccessDisplay;

    self.super.destroy.call(self);
    ItemGetSuccessDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ItemGetSuccessDisplay = new ItemGetSuccessDisplay();

$(document).ready(ItemGetSuccessDisplay.start.bind(ItemGetSuccessDisplay));

