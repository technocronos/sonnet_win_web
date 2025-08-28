
/**
 * 仮想通貨ゲット結果を制御するシングルトンオブジェクト。
 *
 */
function VcoinGetDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(VcoinGetDisplay.prototype, 'constructor', {
        value : VcoinGetDisplay,
        enumerable : false
    });
    this.amount = Page.getParams("amount");
    this.me = Page.getParams("vcoin_me");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
VcoinGetDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
VcoinGetDisplay.prototype.start = function() {
console.log("VcoinGetDisplay.start rannning...");
    var self = VcoinGetDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
VcoinGetDisplay.prototype.reload = function (){
    var self = VcoinGetDisplay;

    $("#rorate_image").show();
    //回転
    AppUtil.rotate($("#rorate_image"), 2.5);

    //仮想通貨ゲット
    $("#vcoin_text").html("ビットコイン" + this.amount + "BTCをゲットしました");

    //OKボタンクリック時イベントハンドラ
    $("#popup-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        BattleResultDisplay.onLoaded();
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
VcoinGetDisplay.prototype.onLoaded = function() {
    var self = VcoinGetDisplay;

    //ちょっと遅延させてから消す
    $(function(){
        $("#weapon_area").sparkleh();

        setTimeout(function(){
            sound("se_congrats");
            AppUtil.circle($("#circle_div"),750 / 2, 160);

            setTimeout(function(){
                AppUtil.circle($("#circle_div2"),750 / 2, 160);
            },40);
        },200);
    });

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
VcoinGetDisplay.prototype.destroy = function (){
    var self = VcoinGetDisplay;

    self.me.close();
    self.super.destroy.call(self);
    VcoinGetDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var VcoinGetDisplay = new VcoinGetDisplay();

$(document).ready(VcoinGetDisplay.start.bind(VcoinGetDisplay));

