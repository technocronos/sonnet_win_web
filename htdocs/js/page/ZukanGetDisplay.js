
/**
 * 階級アップ結果を制御するシングルトンオブジェクト。
 *
 */
function ZukanGetDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ZukanGetDisplay.prototype, 'constructor', {
        value : ZukanGetDisplay,
        enumerable : false
    });

    this.list = Page.getParams("list");
    this.me = Page.getParams("zukanget_me");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ZukanGetDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ZukanGetDisplay.prototype.start = function() {
console.log("ZukanGetDisplay.start rannning...");
    var self = ZukanGetDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ZukanGetDisplay.prototype.reload = function (){
    var self = ZukanGetDisplay;

    $("#rorate_image").show();
    //回転
    AppUtil.rotate($("#rorate_image"), 2.5);

    $("#monster_card").attr("src",  AppUrl.asset("img/parts/sp/cardbase_" + self.list.capture["rare_level"] + ".png"));
    $("#monster_icon").attr("src", AppUrl.asset("img/chara/" + self.list.capture.image_url) );
    $("#monster_name").html(self.list.capture.monster_name);
    if(self.list.capture["rare_level"] == 3)
        $("#monster_name").toggleClass("colorNormal");
    else
        $("#monster_name").toggleClass("colorNearWhite");

    $("#monster_flavor_text").html(self.list.capture.flavor_text);


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
ZukanGetDisplay.prototype.onLoaded = function() {
    var self = ZukanGetDisplay;

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
ZukanGetDisplay.prototype.destroy = function (){
    var self = ZukanGetDisplay;

    self.me.close();
    self.super.destroy.call(self);
    ZukanGetDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ZukanGetDisplay = new ZukanGetDisplay();

$(document).ready(ZukanGetDisplay.start.bind(ZukanGetDisplay));

