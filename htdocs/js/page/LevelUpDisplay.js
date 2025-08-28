
/**
 * レベルアップ結果を制御するシングルトンオブジェクト。
 *
 */
function LevelUpDisplay(){

    this.list = Page.getParams("list");
    this.me = Page.getParams("levelup_me");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
LevelUpDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
LevelUpDisplay.prototype.start = function() {
console.log("LevelUpDisplay.start rannning...");
    var self = LevelUpDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * ステータスから呼び出される。何もしない。
 */
LevelUpDisplay.prototype.restart = function() {
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
LevelUpDisplay.prototype.reload = function (){
    var self = LevelUpDisplay;

    $("#rorate_image").show();
    //回転
    AppUtil.rotate($("#rorate_image"), 2.5);

  	before = self.list.ready;
  	after = self.list.result.character;
  		
  	level = after.level; 

  	att1 = after.attack1 - before.attack1;
  	att2 = after.attack2 - before.attack2;
  	att3 = after.attack3 - before.attack3;
  	
  	def1 = after.defence1 - before.defence1;
  	def2 = after.defence2 - before.defence2;
  	def3 = after.defence3 - before.defence3;
  	defX = after.defenceX - before.defenceX;

  	spd = after.speed - before.speed;

  	hp_max = after.hp_max - before.hp_max;
  	param_seed = after.param_seed - before.param_seed;

    $("#att1").html(att1);
    $("#att2").html(att2);
    $("#att3").html(att3);
    $("#def1").html(def1);
    $("#def2").html(def2);
    $("#def3").html(def3);
    $("#defX").html(defX);
    $("#spd").html(spd);
    $("#hp_max").html(hp_max);

    $("#param_seed").html("+" + param_seed);

    if(param_seed > 0){
        $("#param_seed_panel").show();
        $("#param-seed-btn").show();
    }

    //レベル
    $("#level_text").html("レベルが" + level + "になりました");

    //振り分けボタンクリック時イベントハンドラ
    $("#param-seed-btn").off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("parent", LevelUpDisplay);
        Page.setParams("paramseed_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(ParamSeedHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();
    });

    //OKボタンクリック時イベントハンドラ
    $("#levelup-close").off('click').on('click',function() {
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
LevelUpDisplay.prototype.onLoaded = function() {
    var self = LevelUpDisplay;

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
LevelUpDisplay.prototype.destroy = function (){
    var self = LevelUpDisplay;

    self.me.close();
    self.super.destroy.call(self);
    LevelUpDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var LevelUpDisplay = new LevelUpDisplay();

$(document).ready(LevelUpDisplay.start.bind(LevelUpDisplay));

