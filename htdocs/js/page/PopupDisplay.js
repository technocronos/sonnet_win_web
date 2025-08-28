
/**
 * 汎用ポップアップを制御するシングルトンオブジェクト。
 *
 */
function PopupDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(PopupDisplay.prototype, 'constructor', {
        value : PopupDisplay,
        enumerable : false
    });

    this.me = Page.getParams("pop_d");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
PopupDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
PopupDisplay.prototype.start = function() {
console.log("PopupDisplay.start rannning...");
    var self = PopupDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
PopupDisplay.prototype.reload = function (){
    self = PopupDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
PopupDisplay.prototype.onLoaded = function() {
    var self = PopupDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
PopupDisplay.prototype.destroy = function (){
    var self = PopupDisplay;

    self.me.close();
    self.super.destroy.call(self);
    PopupDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var PopupDisplay = new PopupDisplay();

$(document).ready(PopupDisplay.start.bind(PopupDisplay));

