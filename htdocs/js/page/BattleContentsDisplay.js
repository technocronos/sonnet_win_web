
/**
 * バトルFlashを制御するシングルトンオブジェクト。
 *
 */
function BattleContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "BattleContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    //背景透過
    this.transparent = true;

    //スクロールをストップする
    $(window).on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //背景スクロールをストップする
    $("#bg").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //背景スクロールをストップする
    $("#canvas_bg").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //androidが遅いので・・
    if(carrier == "android")
        this.shapeDetail = { all: { method: "func" } }

    $(window).scrollTop(0);
}

// 親クラスを継承
BattleContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BattleContentsDisplay.prototype.start = function() {
console.log("BattleContentsDisplay.start rannning...");
    var self = BattleContentsDisplay;

    self.super.start.call(self);
}

BattleContentsDisplay.prototype.swfstart = function(pex, callback) {
    var timer = null;
    $(function(){
        timer = setInterval(function(){
        var CurrentFrame = pex.getAPI().getCurrentFrame("/main");
            if(CurrentFrame == 1){
                //loading..表示タイマーストップ
                clearInterval(timer);

                pex.getAPI().setVariable("/", "sound_loaded", 1);
                audio.sndfx.sound_loaded = 1;

                callback();

                return;

            }
        },100);
    }); 
}

//---------------------------------------------------------------------------------------------------------
/**
 * html5へのタッチハンドラ。
 */
BattleContentsDisplay.prototype.touchHandler = function(e) {
    e.preventDefault();

    var touch = e.touches[0];
    var changedTouches = e.changedTouches[0];

    if(e.type == "touchstart"){
          pex.getAPI().setVariable("/", "touchstartX", touch.pageX);
          pex.getAPI().setVariable("/", "touchstartY", touch.pageY);
      }
    if(e.type == "touchmove"){

      }
    if(e.type == "touchend"){
          pex.getAPI().setVariable("/", "touchendX", changedTouches.pageX);
          pex.getAPI().setVariable("/", "touchendY", changedTouches.pageY);

    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BattleContentsDisplay.prototype.reload = function (){
    var self = BattleContentsDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BattleContentsDisplay.prototype.onLoaded = function() {
    var self = BattleContentsDisplay;

    if(is_tablet == "tablet"){
        $("#canvas_bg").css("top", "-8%");
        $("#container").css("margin-top", "-14%");
    }

    pex.getAPI().gotoFrame("/main", "onFirstTap");

    $(window).scrollTop(0);
    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BattleContentsDisplay.prototype.destroy = function (){
console.log("BattleContentsDisplay.destroy run..");

    var self = BattleContentsDisplay;

    //var canvas = document.getElementById("container");
    //canvas.width = 0;
    //canvas.height = 0;

    pex.getAPI().destroy();

    this.super.destroy.call(this);
    BattleContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 乱数を生成して戻す
 */
BattleContentsDisplay.prototype.randGen = function (path, rnd_return){
    var rand = AppUtil.mt_rand(1, 65535);
    pex.getAPI().setVariable(path, rnd_return, rand);
}

//---------------------------------------------------------------------------------------------------------
/**
 * バトル結果を通知してレスポンスを返す
 */
BattleContentsDisplay.prototype.battle_end = function (battleId, mv){
console.log("BattleContentsDisplay.battle_end run..");

    //postデータを取得する
    var post = pex.getAPI().getVariables(mv);

console.log(post);

    BattleApi.result(battleId, null, null, post, function(response){
        console.log(response);

        //ランチャーにレスポンスを返す
        pex.getAPI().setVariable(mv,"response",response["response"]);
    });
}
//---------------------------------------------------------------------------------------------------------
/**
 * バトル結果を返す
 */
BattleContentsDisplay.prototype.showBattleResult = function (battleId, repaireId){
console.log("BattleContentsDisplay.showBattleResult run..");

    var self = BattleContentsDisplay;

    var d = new Dialogue();

    Page.setParams("battleId", battleId);
    Page.setParams("repaireId", repaireId);
    Page.setParams("battle_result_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(BattleResultHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;
    d.opacity = 1;

    d.show();

    //もう必要ないのでdestroyしてしまう
    self.destroy();
}

//---------------------------------------------------------------------------------------------------------
var BattleContentsDisplay = new BattleContentsDisplay();

$(document).ready(BattleContentsDisplay.start.bind(BattleContentsDisplay));

