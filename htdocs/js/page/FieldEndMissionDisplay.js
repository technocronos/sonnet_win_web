
/**
 * 汎用ポップアップを制御するシングルトンオブジェクト。
 *
 */
function FieldEndMissionDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(FieldEndMissionDisplay.prototype, 'constructor', {
        value : FieldEndMissionDisplay,
        enumerable : false
    });

    this.mission = Page.getParams("mission");
    this.me = Page.getParams("fieldend_mission_d");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
FieldEndMissionDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
FieldEndMissionDisplay.prototype.start = function() {
console.log("FieldEndMissionDisplay.start rannning...");
    var self = FieldEndMissionDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
FieldEndMissionDisplay.prototype.reload = function (){
    self = FieldEndMissionDisplay;

    $("#get_gold").html("+" + self.mission.gold);
    $("#curr_gold").html(chara_gold + "→" + (parseInt(chara_gold) + parseInt(self.mission.gold)));

    //戻るボタンクリック時イベントハンドラ
    $("#mission-close").off('click').on('click',function() {
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
FieldEndMissionDisplay.prototype.onLoaded = function() {
    var self = FieldEndMissionDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
FieldEndMissionDisplay.prototype.destroy = function (){
    var self = FieldEndMissionDisplay;

    self.me.close();
    self.super.destroy.call(self);
    FieldEndMissionDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var FieldEndMissionDisplay = new FieldEndMissionDisplay();

$(document).ready(FieldEndMissionDisplay.start.bind(FieldEndMissionDisplay));

