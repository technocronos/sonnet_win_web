
/**
 * モンスター図鑑を制御するシングルトンオブジェクト。
 *
 */
function MonsterDetailDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MonsterDetailDisplay.prototype, 'constructor', {
        value : MonsterDetailDisplay,
        enumerable : false
    });

    this.me = Page.getParams("monster_detail_d");
    this.monster = Page.getParams("monster");
    this.field = Page.getParams("field");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MonsterDetailDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MonsterDetailDisplay.prototype.start = function() {
console.log("MonsterDetailDisplay.start rannning...");
    var self = MonsterDetailDisplay;

    //okボタンクリック時イベントハンドラ
    $("#detail-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MonsterDetailDisplay.prototype.reload = function (){
    self = MonsterDetailDisplay;
console.log(this.monster);

    $("#monster_card").attr("src",  AppUrl.asset("img/parts/sp/cardbase_" + this.monster["rare_level"] + ".png"));
    $("#monster_icon").attr("src", AppUrl.asset("img/chara/" + this.monster.image_url) );
    $("#monster_name").html(this.monster.monster_name);
    if(this.monster["rare_level"] == 3)
        $("#monster_name").toggleClass("colorNormal");
    else
        $("#monster_name").toggleClass("colorNearWhite");

    $("#monster_flavor_text").html(this.monster.flavor_text);

    $("#field").html(this.field);
    if(this.monster["rare_level"] == 1)
        $("#rare").html("ノーマル");
    else if(this.monster["rare_level"] == 2)
        $("#rare").html("レア");
    else if(this.monster["rare_level"] == 3)
        $("#rare").html("Sレア");

    $("#habitat").html(this.monster["habitat"]);

    $("#hp_max").html(parseInt(this.monster["hp_max"]));

    $("#att1").html(this.monster["attack1"]);
    $("#att2").html(this.monster["attack2"]);
    $("#att3").html(this.monster["attack3"]);
    $("#def1").html(this.monster["defence1"]);
    $("#def2").html(this.monster["defence2"]);
    $("#def3").html(this.monster["defence3"]);
    $("#spd").html(this.monster["speed"]);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MonsterDetailDisplay.prototype.onLoaded = function() {
    var self = MonsterDetailDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
MonsterDetailDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    MonsterDetailDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var MonsterDetailDisplay = new MonsterDetailDisplay();

$(document).ready(MonsterDetailDisplay.start.bind(MonsterDetailDisplay));

