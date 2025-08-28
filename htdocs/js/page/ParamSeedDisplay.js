
/**
 * ショップ確認を制御するシングルトンオブジェクト。
 *
 */
function ParamSeedDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ParamSeedDisplay.prototype, 'constructor', {
        value : ParamSeedDisplay,
        enumerable : false
    });

    this.status = null;
    this._parent = Page.getParams("parent");
    this.me = Page.getParams("paramseed_d");

    this.list = ["att1", "att2", "att3", "def1", "def2", "def3", "spd", "hp",];

    this.param_seed = {};
    this.param_seed["att1"] = 0;
    this.param_seed["att2"] = 0;
    this.param_seed["att3"] = 0;
    this.param_seed["def1"] = 0;
    this.param_seed["def2"] = 0;
    this.param_seed["def3"] = 0;
    this.param_seed["spd"] = 0;
    this.param_seed["hp"] = 0;
    this.param_seed["total"] = 0;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ParamSeedDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ParamSeedDisplay.prototype.start = function() {
console.log("ParamSeedDisplay.start rannning...");
    var self = ParamSeedDisplay;

    //マイページ画面ステータス情報取得API
    MypageApi.status(function(response){
        console.log(response);

        self.status = response.chara;

        self.super.start.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ParamSeedDisplay.prototype.reload = function (){
    var self = ParamSeedDisplay;

console.log(self.status);

    $("#navi_serif").html("ステータスポイントを振り分けるのだ");
    $("#curr_param_seed").html(self.status.param_seed + " pt");

    $("#curr_att1").text( self.status["attack1"] );
    $("#curr_att2").text( self.status["attack2"] );
    $("#curr_att3").text( self.status["attack3"] );
    $("#curr_spd").text( self.status["speed"] );

    $("#curr_def1").text( self.status["defence1"] );
    $("#curr_def2").text( self.status["defence2"] );
    $("#curr_def3").text( self.status["defence3"] );
    $("#curr_hp").text( parseInt(self.status["hp_max"]) );

    $.each(self.list, function(key, value){
        var add_point = 1;
        if(value == "hp")
            add_point = 7;

        //↑ボタンクリック時イベントハンドラ
        $("#"+value+"_btn_up").off('click').on('click',function() {
            if(self.param_seed["total"] < self.status.param_seed){
                sound("se_btn");
                self.param_seed[value]++;
                $("#"+value+"_add").html(self.param_seed[value]);

                if(self.param_seed[value] == self.status.param_seed)
                    $("#"+value+"_btn_up").attr("src", AppUrl.asset("img/parts/sp/status_down_btn_disable.png"));

                if(self.param_seed[value] >= 1)
                    $("#"+value+"_btn_down").attr("src", AppUrl.asset("img/parts/sp/status_down_btn.png"));

                $("#curr_"+value).text( parseInt($("#curr_"+value).text()) + add_point);

                self.param_seed["total"]++;
                $("#curr_param_seed").html((self.status.param_seed - self.param_seed["total"]) + " pt");

                if(self.param_seed["total"] > 0){
                    AppUtil.ableButton($("#param_seed_ok"), "174_74");
                }

                if((self.status.param_seed - self.param_seed["total"]) == 0){
                    $.each(self.list, function(key2, value2){
                        $("#"+value2+"_btn_up").attr("src", AppUrl.asset("img/parts/sp/status_down_btn_disable.png"));
                    });
                }
            }
        });
        //↓ボタンクリック時イベントハンドラ
        $("#"+value+"_btn_down").off('click').on('click',function() {
            if(self.param_seed["total"] >= 1){

                if(parseInt($("#"+value+"_add").text()) <= 0)
                    return;

                sound("se_btn");
                self.param_seed[value]--;

                $("#"+value+"_add").html(self.param_seed[value]);

                if(self.param_seed[value] == 0)
                    $("#"+value+"_btn_down").attr("src", AppUrl.asset("img/parts/sp/status_down_btn_disable.png"));

                if(self.param_seed[value] < self.status.param_seed)
                    $("#"+value+"_btn_up").attr("src", AppUrl.asset("img/parts/sp/status_up_btn.png"));

                $("#curr_"+value).text( parseInt($("#curr_"+value).text()) - add_point);

                self.param_seed["total"]--;
                $("#curr_param_seed").html((self.status.param_seed - self.param_seed["total"]) + " pt");

                if(self.param_seed["total"] == 0){
                    AppUtil.disableButton($("#param_seed_ok"), "174_74");
                }

                if((self.status.param_seed - self.param_seed["total"]) > 0){
                    $.each(self.list, function(key2, value2){
                        $("#"+value2+"_btn_up").attr("src", AppUrl.asset("img/parts/sp/status_up_btn.png"));
                    });
                }
            }
        });
    });

    //振り分けボタンクリック時イベントハンドラ
    $("#param_seed_ok").off('click').on('click',function() {
        if(self.param_seed["total"] == 0)
            return;

        sound("se_btn");
        var add_att1 = $("#att1_add").html();
        var add_att2 = $("#att2_add").html();
        var add_att3 = $("#att3_add").html();
        var add_def1 = $("#def1_add").html();
        var add_def2 = $("#def2_add").html();
        var add_def3 = $("#def3_add").html();
        var add_spd = $("#spd_add").html();
        var add_hp = $("#hp_add").html();

        MypageApi.paramup(null, add_att1, add_att2, add_att3, add_def1, add_def2, add_def3 ,add_spd, add_hp, function(response){
            console.log(response);
            self.destroy();
            self._parent.restart();
        });
    });

    if(self.status.param_seed > 0){
    }else{
        $("#param_seed_ok").off('click');
        AppUtil.disableButton($("#param_seed_ok"), "174_74");
    }

    //キャンセルボタンクリック時イベントハンドラ
    $("#param_seed_close").off('click').on('click',function() {
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
ParamSeedDisplay.prototype.onLoaded = function() {
    var self = ParamSeedDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ParamSeedDisplay.prototype.destroy = function (){
    var self = ParamSeedDisplay;

    self.me.close();
    self.super.destroy.call(self);
    ParamSeedDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ParamSeedDisplay = new ParamSeedDisplay();

$(document).ready(ParamSeedDisplay.start.bind(ParamSeedDisplay));

