
/**
 * バトル結果を制御するシングルトンオブジェクト。
 *
 */
function BattleConfirmDisplay(){

    //コンストラクタ名を書き換える
    Object.defineProperty(BattleConfirmDisplay.prototype, 'constructor', {
        value : BattleConfirmDisplay,
        enumerable : false
    });

    this.rivalId = Page.getParams("rivalId");

    this.me = Page.getParams("battle_confirm_me");

    this.CharaCanvasP = null;
    this.CharaCanvasE = null;
    this.SparkCanvas = null;

    this.category = "EQP";
    this.reslt = null;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
BattleConfirmDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BattleConfirmDisplay.prototype.start = function() {

    var self = BattleConfirmDisplay;

    if(is_tablet == "tablet"){
        $("#battle_middle").css("top", "-261px");
        $("#bg_image").css("top", "-373px");
    }

    RivalApi.confirm(self.rivalId, null, function(response){
console.log(response);
        self.list = response;

        $("#bg_image").html(Page.preload_image.circle_bg);

        self.CharaCanvasP = new CharaCanvas(self,"P",self.list.chara1.user_id, self.list.imageUrl1);
        self.CharaCanvasE = new CharaCanvas(self,"E",self.list.chara2.user_id, self.list.imageUrl2);

        self.SparkCanvas = new SparkCanvas(self);

        //全canvasが読み込まれていることを保証する。
        var timer = null;
        $(function(){
            timer = setInterval(function(){
                if(self.CharaCanvasP.loaded && self.CharaCanvasE.loaded ){
                    //loading..表示タイマーストップ
                    clearInterval(timer);

                    self.super.start.call(self);
                }
            },500);
        });
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BattleConfirmDisplay.prototype.reload = function (){
    var self = BattleConfirmDisplay;

    sound("bgm_dungeon");
        
    //階級
    $("#gradeP").html(self.list.chara1.grade_name);
    //名前
    $("#nameP").html(self.list.chara1.player_name);

    //敵階級
    $("#gradeE").html(self.list.chara2.grade_name);
    //敵名前
    $("#nameE").html(self.list.chara2.player_name);

    $("#levelP").html(self.list.chara1.level);
    $("#levelE").html(self.list.chara2.level);

    $("#att1P").html(self.list.chara1.total_attack1);
    $("#att2P").html(self.list.chara1.total_attack2);
    $("#att3P").html(self.list.chara1.total_attack3);

    $("#def1E").html(self.list.chara2.total_defence1);
    $("#def2E").html(self.list.chara2.total_defence2);
    $("#def3E").html(self.list.chara2.total_defence3);

    $("#spdP").html(self.list.chara1.total_speed);
    $("#spdE").html(self.list.chara2.total_speed);

    $("#bp").html(self.list.matchPt);

    //HPゲージを更新
    var gauge_widthP = ((self.list.chara1.hp / self.list.chara1.hp_max) * 277);
    $("#hp_gauge_barP").css("width", gauge_widthP + "px");
    $("#hp_textP").html(parseInt(self.list.chara1.hp) + "/" + parseInt(self.list.chara1.hp_max));

    //HPゲージを更新
    var gauge_widthE = ((self.list.chara2.hp / self.list.chara2.hp_max) * 277);
    $("#hp_gauge_barE").css("width", gauge_widthE + "px");
    $("#hp_textE").html(parseInt(self.list.chara2.hp) + "/" + parseInt(self.list.chara2.hp_max));

    if(self.list.canBattle == "count_rival"){
        $("#btn_left").hide();
        self.showMessage("1日で同じ人と" + DUEL_LIMIT_ON_DAY_RIVAL + "回以上戦えないのだ<br>今日は別のヤツと戦うのだ");
    }else{
        //対戦ボタン
        $("#btn_left").off('click').on('click',function() {
            sound("se_btn");
            sound_stop();
            RivalApi.confirm(self.rivalId, "ok", function(response){
                console.log(response);
            });
        });
    }

    //キャンセルボタン
    $("#btn_right").off('click').on('click',function() {
        sound("se_btn");
        sound("bgm_menu");
        self.destroy();
    });

    self.super.reload.call(self);
}
//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BattleConfirmDisplay.prototype.onLoaded = function() {
    var self = BattleConfirmDisplay;

    //キャラ登場
    self.CharaCanvasP.pos(0, 0);
    self.CharaCanvasP.in();

    self.CharaCanvasE.pos(0, 0);
    self.CharaCanvasE.in();

    //シャキーン
    sound("se_flash");

    $("#smork").css("background", "url(" + AppUrl.asset("img/parts/sp/smork_anim.png") + ")");
    $("#smork").css("background-repeat", "repeat-x");
    $("#smork").css("width", "3000px");
    $("#smork").css("left", "-2250px");

    $("#smork").animate({
        "marginLeft": "500px"
    }, 50000).animate({
        "opacity": "0"
    });

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BattleConfirmDisplay.prototype.destroy = function (){
    var self = BattleConfirmDisplay;

    self.CharaCanvasP.destroy();
    self.CharaCanvasE.destroy();

    self.CharaCanvasP = null;
    self.CharaCanvasE = null;

    self.SparkCanvas.destroy();

    Page.setParams("rivalId", null);
    Page.setParams("battle_confirm_me", null);

    self.me.close();

    self.super.destroy.call(self);
    BattleConfirmDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BattleConfirmDisplay = new BattleConfirmDisplay();

$(document).ready(BattleConfirmDisplay.start.bind(BattleConfirmDisplay));

