
/**
 * 汎用確認ポップアップを制御するシングルトンオブジェクト。
 *
 */
function PopupConfirmDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(PopupConfirmDisplay.prototype, 'constructor', {
        value : PopupConfirmDisplay,
        enumerable : false
    });

    this.me = Page.getParams("popup_d");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
PopupConfirmDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
PopupConfirmDisplay.prototype.start = function() {
console.log("PopupConfirmDisplay.start rannning...");
    var self = PopupConfirmDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
PopupConfirmDisplay.prototype.reload = function (){
    self = PopupConfirmDisplay;

    //okボタンクリック時イベントハンドラ
    $("#popupconf-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
PopupConfirmDisplay.prototype.onLoaded = function() {
    var self = PopupConfirmDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
PopupConfirmDisplay.prototype.destroy = function (){
    var self = PopupConfirmDisplay;

    self.super.destroy.call(self);
    self.me.close();
    PopupConfirmDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var PopupConfirmDisplay = new PopupConfirmDisplay();

$(document).ready(PopupConfirmDisplay.start.bind(PopupConfirmDisplay));

