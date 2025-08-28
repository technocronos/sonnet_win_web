
/**
 * 他人ページを制御するシングルトンオブジェクト。
 *
 */
function HispageDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(HispageDisplay.prototype, 'constructor', {
        value : HispageDisplay,
        enumerable : false
    });

    //スクロール無効
    $("#HispageContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.CharaCanvas = null;

    this.userId = Page.getParams("his_user_id");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
HispageDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
HispageDisplay.prototype.start = function() {
console.log("HispageDisplay.start rannning...");
    var self = HispageDisplay;

    //マイページ画面ステータス情報取得API
    MypageApi.other(self.userId, function(response){
        console.log(response);

        self.status = response;

        //キャラ作成
        self.CharaCanvas = new CharaCanvas(self,null,self.status["chara"]["user_id"], self.status["chara"]["imageUrl"]);

        //全canvasが読み込まれていることを保証する。
        var timer = null;
        $(function(){
            timer = setInterval(function(){
                if(self.CharaCanvas.loaded){
                    //loading..表示タイマーストップ
                    clearInterval(timer);

                    self.super.start.call(self);
                }
            },500);
        });
    });
}

HispageDisplay.prototype.restart = function() {
    var self = HispageDisplay;

    //マイページ画面ステータス情報取得API
    MypageApi.other(self.userId, function(response){
        console.log(response);
        self.status = response;

        self.super.start.call(self);
    });
}
//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
HispageDisplay.prototype.reload = function (){
    var self = HispageDisplay;

    $("#user_name").html(self.status["chara"]["player_name"]);
    $("#level").html(self.status["chara"]["level"]);
    $("#history").html(self.status["ctour"]["win"] + "勝" + self.status["ctour"]["lose"] + "敗" + self.status["ctour"]["draw"] + "分");
    $("#grade").html(self.status["chara"]["grade_name"]);

    if(self.status.isMember){
    	//仲間解除ボタンを出す
    	$("#apply_img").attr("src", AppUrl.asset("img/parts/sp/btn_friend_app_delete.png"));
    	$("#relation").html("仲間です");
    }else if(self.status.isApproaching){
    	//申請中ボタンを出す
    	$("#apply_img").attr("src", AppUrl.asset("img/parts/sp/btn_friend_app_disable.png"));
    	$("#relation").html("申請中");
    }else{
    	//友達申請ボタンを出す
    	$("#apply_img").attr("src", AppUrl.asset("img/parts/sp/btn_friend_app.png"));
    	$("#relation").html("他人");
    }

    //self.setEquipInfo();

    self.setStatusInfo();

    //HPゲージを更新
    var gauge_width = ((self.status["chara"]["hp"] / self.status["chara"]["hp_max"]) * 277);
    $("#hp_gauge_bar").css("width", gauge_width + "px");
    $("#hp_text").html(parseInt(self.status["chara"]["hp"]) + "/" + parseInt(self.status["chara"]["hp_max"]));

    //キャラ登場
    self.CharaCanvas.pos(130, 80);
    self.CharaCanvas.fadein();


    $("#btn_rival").off('click').on('click',function() {
        sound("se_btn");
    MainContentsDisplay.FooterCanvas.out('rival','his_page');
    });

    //mobageはメッセージ送信やめ
    if(PLATFORM_TYPE == "mbga")
        $("#btn_msg").hide();

    //メッセージボタン
    $("#btn_msg").off('click').on('click',function() {
        sound("se_btn");
        self.showMessage(self.status.chara.user_id, self.status.chara.player_name);
    });
    //仲間ボタン
    $("#btn_member_list").off('click').on('click',function() {
        sound("se_btn");
        self.showMemberList(self.status.chara.user_id, self.status.chara.player_name);
    });
    //履歴ボタン
    $("#btn_history_list").off('click').on('click',function() {
        sound("se_btn");
        self.showHistoryList(self.status.chara.user_id, self.status.chara.player_name);
    });
    //戦歴ボタン
    $("#btn_battlelog").off('click').on('click',function() {
        sound("se_btn");
        self.showBattlelogList(self.status.chara.character_id);
    });

    //回数制限
    if(self.status["canBattle"] == "count_rival"){

        $("#btn_battle").find("img").attr("src", AppUrl.asset("img/parts/sp/btn_his_battle_disable.png"));

    //}else if(self.status["canBattle"] == "consume_pt"){

    }else{

        //対戦ボタン
        $("#btn_battle").off('click').on('click',function() {
            sound("se_btn");
            sound_stop();
            self.showBattleConfirm(self.status.chara.character_id);
        });
    }

    $("#grade_list_btn").off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("gradelist_d", d);
        Page.setParams("screen", "his_page");

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(GradeListHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();
        
    });

    //申請ボタン
    $("#btn_member_app").off('click').on('click',function() {
        if(self.status.isApproaching == true)
            return;

        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("popup_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(PopupConfirmHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();

      	if(self.status.isMember){
      		var text = "友達を解除するのだ。いいのだ？";
    			var approach = 0;
    			var dissolve = 1;
      	}else if(self.status.isApproaching == false){
      		var text = "友達申請をするのだ。いいのだ？";
    			var approach = 1;
    			var dissolve = 0;
      	}

        $("#confirm_body").html(text);

        $("#popupconf-ok").off('click').on('click',function() {
            sound("se_btn");

            ApproachApi.send(self.userId, approach, dissolve, function(response){
                console.log(response);
                var text = "";
                if(response["result"] == "ok"){
                    if(approach == 1){
                    		text = "申請しておいたのだ。";
                    }else if(dissolve == 1){
                        text = "仲間を解除したのだ。";

                        //ヘッダーを更新する
                        var member_current = Page.getSummary().member.current -1;
                        MainContentsDisplay.HeaderCanvas.update("member_current", member_current);
                    }
                }else{
                    text = response["err_msg"];
                }
                var d = new Dialogue();

                Page.setParams("pop_d", d);

                d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
                d.content(PopupHtml);

                d.autoClose = false;
                d.veilClose = false;
                d.opacity = 0.5;

                d.show();

                $("#popup_body").html(text);

                $("#popup-close").off('click').on('click',function() {
                    sound("se_btn");
                    PopupDisplay.destroy();

                    if(response["result"] == "ok")
                        self.restart();
                });

                PopupConfirmDisplay.destroy();
            });
        });
    });


    self.super.reload.call(self);
}


//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
HispageDisplay.prototype.onLoaded = function() {
    var self = HispageDisplay;

    $("#main_contents").fadeIn("fast", function(){
        self.setEquipInfo();
        self.super.onLoaded.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
/*
 * 装備情報を更新する
 *
*/
HispageDisplay.prototype.setEquipInfo = function() {
    var self = HispageDisplay;

    //装備武器
    if(self.status["chara"]["equip"][3] != undefined)
        $("#hed_text").html(self.status["chara"]["equip"][3]["item_name"]);
    else
        $("#hed_text").html("(装備なし)");

    if(self.status["chara"]["equip"][4] != undefined)
        $("#acs_text").html(self.status["chara"]["equip"][4]["item_name"]);
    else
        $("#acs_text").html("(装備なし)");

    if(self.status["chara"]["equip"][1] != undefined)
        $("#wpn_text").html(self.status["chara"]["equip"][1]["item_name"]);
    else
        $("#wpn_text").html("(装備なし)");

    if(self.status["chara"]["equip"][2] != undefined)
        $("#bod_text").html(self.status["chara"]["equip"][2]["item_name"]);
    else
        $("#bod_text").html("(装備なし)");

    //arctextライブラリを使って文字をアーチ状にする
    $("#hed_text").arctext({radius: 140, fitText:true});
    $("#acs_text").arctext({radius: 140, fitText:true});
    $("#wpn_text").arctext({radius: 140, fitText:true});
    $("#bod_text").arctext({radius: 140, dir: -1, fitText:true});
}

//---------------------------------------------------------------------------------------------------------
/*
 * ステータス情報を更新する
 *
*/
HispageDisplay.prototype.setStatusInfo = function() {
    var self = HispageDisplay;
    //ステータス情報
    $("#att1").text( self.status["chara"]["total_attack1"] );
    $("#att2").text( self.status["chara"]["total_attack2"] );
    $("#att3").text( self.status["chara"]["total_attack3"] );
    $("#spd").text( self.status["chara"]["total_speed"] );

    $("#def1").text( self.status["chara"]["total_defence1"] );
    $("#def2").text( self.status["chara"]["total_defence2"] );
    $("#def3").text( self.status["chara"]["total_defence3"] );
    $("#defX").text( self.status["chara"]["total_defenceX"] );
}
//---------------------------------------------------------------------------------------------------------
/**
 * 対戦確認ポップアップを呼び出す
 */
HispageDisplay.prototype.showBattleConfirm = function(rivalId){
    var self = HispageDisplay;

    var d = new Dialogue();

    Page.setParams("rivalId", rivalId);
    Page.setParams("battle_confirm_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(BattleConfirmHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * メッセージ送信ポップアップを呼び出す
 */
HispageDisplay.prototype.showMessage = function(userId,Name){
    var self = HispageDisplay;

    var d = new Dialogue();

    Page.setParams("companionId", userId);
    Page.setParams("companionName", Name);
    Page.setParams("message_d", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(MessageHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 仲間一覧用ポップアップを立ち上げる
 */
HispageDisplay.prototype.showMemberList = function(userId, player_name){
    var d = new Dialogue();

    Page.setParams("player_name", player_name);
    Page.setParams("userId", userId);
    Page.setParams("category", null);

    Page.setParams("me", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(MemberListHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 履歴一覧用ポップアップを立ち上げる
 */
HispageDisplay.prototype.showHistoryList = function(userId, player_name){
    var d = new Dialogue();

    Page.setParams("player_name", player_name);
    Page.setParams("userId", userId);
    Page.setParams("category", null);

    Page.setParams("me", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(HistoryListHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 戦歴一覧用ポップアップを立ち上げる
 */
HispageDisplay.prototype.showBattlelogList = function(charaId){
    var d = new Dialogue();

    Page.setParams("charaId", charaId);
    Page.setParams("category", null);

    Page.setParams("me", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(BattleLogHtml);

    d.autoClose = false;
    d.veilClose = false;


    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
HispageDisplay.prototype.destroy = function (){
    var self = HispageDisplay;

    //退場アニメーションが終わったらdestroy
    $("#main_contents").fadeOut("slow", function(){
        self.CharaCanvas.destroy();
        self.CharaCanvas = null;

        $("#main_contents").empty();

        self.super.destroy.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
var HispageDisplay = new HispageDisplay();

$(document).ready(HispageDisplay.start.bind(HispageDisplay));

