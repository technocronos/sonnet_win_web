
/**
 * 階級アップ結果を制御するシングルトンオブジェクト。
 *
 */
function GradeUpDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(GradeUpDisplay.prototype, 'constructor', {
        value : GradeUpDisplay,
        enumerable : false
    });
    this.list = Page.getParams("list");
    this.me = Page.getParams("gradeup_me");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
GradeUpDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
GradeUpDisplay.prototype.start = function() {
console.log("GradeUpDisplay.start rannning...");
    var self = GradeUpDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
GradeUpDisplay.prototype.reload = function (){
    var self = GradeUpDisplay;

    $("#rorate_image").show();
    //回転
    AppUtil.rotate($("#rorate_image"), 2.5);

    var grade = self.list.grade;

    //階級
    $("#grade_text").html(self.list.grade.grade_name + "に昇格しました");
    $("#dtech_name").html(self.list.grade.dtech.dtech_name + "が使えるようになった");

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
GradeUpDisplay.prototype.onLoaded = function() {
    var self = GradeUpDisplay;

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
GradeUpDisplay.prototype.destroy = function (){
    var self = GradeUpDisplay;

    self.me.close();
    self.super.destroy.call(self);
    GradeUpDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var GradeUpDisplay = new GradeUpDisplay();

$(document).ready(GradeUpDisplay.start.bind(GradeUpDisplay));

