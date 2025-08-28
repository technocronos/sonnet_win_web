
/**
 * 汎用ポップアップを制御するシングルトンオブジェクト。
 *
 */
function MonsterDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MonsterDisplay.prototype, 'constructor', {
        value : MonsterDisplay,
        enumerable : false
    });
    //スクロール無効
    $("#MonsterContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MonsterDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MonsterDisplay.prototype.start = function() {
console.log("MonsterDisplay.start rannning...");
    var self = MonsterDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MonsterDisplay.prototype.reload = function (){
    self = MonsterDisplay;

    var capture = "キャプチャ率 " + monster_capture + "/" + monster_count;

    $("#capture_text").html(capture);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MonsterDisplay.prototype.onLoaded = function() {
    var self = MonsterDisplay;

    $("#main_contents").show();

    $("#btn_list").animate({
        "left": "57px"
    },200);

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * モンスターリストポップアップを呼び出す
 */
MonsterDisplay.prototype.showMonsterList = function(category){
    sound("se_btn");

    Page.setParams("category", category);

    MainContentsDisplay.FooterCanvas.out("zukan_list","zukan");
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
MonsterDisplay.prototype.destroy = function (){
    var self = MonsterDisplay;

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();
        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var MonsterDisplay = new MonsterDisplay();

$(document).ready(MonsterDisplay.start.bind(MonsterDisplay));

