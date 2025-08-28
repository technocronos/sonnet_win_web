
/**
 * お知らせ詳細を制御するシングルトンオブジェクト。
 *
 */
function MypageDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MypageDisplay.prototype, 'constructor', {
        value : MypageDisplay,
        enumerable : false
    });

    //スクロール無効
    $("#MypageContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.CharaCanvas = null;
    this.NaviCanvas = null;

    this.timer = null;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MypageDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MypageDisplay.prototype.start = function() {
console.log("MypageDisplay.start rannning...");
    var self = MypageDisplay;

    //マイページ画面ステータス情報取得API
    MypageApi.status(function(response){
        console.log(response);

        self.status = response;

        //キャラ作成
        self.CharaCanvas = new CharaCanvas(self,null,self.status["chara"]["user_id"], self.status["chara"]["imageUrl"]);

        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END)){
            var summary = Page.getSummary();
            summary.opening = ["ここがマイページなのだ", 
                                    "個別に装備してもいいんだが\nめんどいので今回はささっと装備\nするのだ",
                                    "最強装備ってボタンでカンタンに\n一番いい装備にチェンジできるのだ",
                                    "とりあえずちょっとやってみるのだ",
                                   ];
            summary.openingNum = summary.opening.count().length;
            summary.end_function = "MypageDisplay.tutorial_equip_navi_speak_end"

            //navi作成
            self.NaviCanvas= new NaviCanvas(self,"_mypage",summary);
            self.NaviCanvas.pos(0,0);
        }

        //全canvasが読み込まれていることを保証する。
        var timer = null;
        $(function(){
            timer = setInterval(function(){
                //チュートリアル中の場合
                if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END)){
                    if(self.CharaCanvas.loaded && self.NaviCanvas.loaded){
                        //loading..表示タイマーストップ
                        clearInterval(timer);

                        self.super.start.call(self);
                    }
                }else{
                    if(self.CharaCanvas.loaded){
                        //loading..表示タイマーストップ
                        clearInterval(timer);

                        self.super.start.call(self);
                    }
                }
            },500);
        });
    });
}


/*
 * ナビがしゃべり終わった後にコールされるので
 * 最強装備を案内すること
*/
MypageDisplay.prototype.tutorial_equip_navi_speak_end = function() {
console.log("tutorial_equip_navi_speak_end runn..");
    //ナビカーソルを表示する
    AppUtil.showArrow($("#MypageContents"), "down", 500, 60); 

}

MypageDisplay.prototype.tutorial_end = function() {
    //チュートリアルswfに遷移
    setTimeout(function(){
        window.location.href = Page.getSummary().urlOnTutorial;
    }, 1000);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 再スタート時に呼び出す。振り分け後等に呼ばれる
 */
MypageDisplay.prototype.restart = function() {
    var self = MypageDisplay;

    //マイページ画面ステータス情報取得API
    MypageApi.status(function(response){
        console.log(response);

        self.status = response;
        clearInterval(self.timer);

        self.reload();
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MypageDisplay.prototype.reload = function (){
    var self = MypageDisplay;

    //階級情報        
    $("#grade_name").html(self.status["grade"]["grade_name"]);
    $("#grade_pt").html(self.status["chara"]["grade_pt"] + "pt");
    $("#raise_border").html(self.status["grade"]["raise_border"]);
    $("#abase_border").html(self.status["grade"]["abase_border"]);

    //振り分けポイント情報
    $("#param_seed").html(self.status["chara"]["param_seed"] + "pt");

    //振り分けポイントがある場合
    if(parseInt(self.status["chara"]["param_seed"]) > 0){
        $("#btn_param_seed").show();

        //振り分けボタン
        $("#btn_param_seed").off('click').on('click',function() {
            sound("se_btn");            

            var d = new Dialogue();

            Page.setParams("parent", MypageDisplay);
            Page.setParams("paramseed_d", d);

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(ParamSeedHtml);

            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();
        });

        //ボタンを押してくださいの文言をブリンク
        $(function(){
            self.timer = setInterval(function(){
                $(".blink").fadeOut(500,function(){$(this).fadeIn(500)});
            }, 1400);
        });

    }else{
        $("#btn_param_seed").hide();
        $("#btn_param_seed").off('click');
    }

    $("#grade_list_btn").off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("gradelist_d", d);
        Page.setParams("screen", "status");

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(GradeListHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();
        
    });

    //self.setEquipInfo();

    self.setStatusInfo();

    //HPゲージを更新
    var gauge_width = ((self.status["chara"]["hp"] / self.status["chara"]["hp_max"]) * 277);
    $("#hp_gauge_bar").css("width", gauge_width + "px");
    $("#hp_text").html(parseInt(self.status["chara"]["hp"]) + "/" + parseInt(self.status["chara"]["hp_max"]));

    //キャラ登場
    self.CharaCanvas.pos(130, 80);
    self.CharaCanvas.fadein();

    //未読メッセージバッチ表示
    if(MainContentsDisplay.summary.unreadCount > 0){
        $("#batch_message").show();
    }else{
        $("#batch_message").hide();
    }

    //仲間申請バッチ表示
    if(MainContentsDisplay.summary.unanswerCount > 0 || MainContentsDisplay.summary.unconfirmCount > 0 ){
        $("#batch_member").show();
    }else{
        $("#batch_member").hide();
    }

    //頭ボタン
    $("#btn_hed").off('click').on('click',function() {
        sound("se_btn");
        self.ShowEquipChange("HED");
    });
    //服ボタン
    $("#btn_bod").off('click').on('click',function() {
        sound("se_btn");
        self.ShowEquipChange("BOD");
    });
    //武器ボタン
    $("#btn_wpn").off('click').on('click',function() {
        sound("se_btn");
        self.ShowEquipChange("WPN");
    });
    //アクセサリボタン
    $("#btn_acs").off('click').on('click',function() {
        sound("se_btn");
        self.ShowEquipChange("ACS");
    });

    //最強装備ボタン
    $("#btn_change").off('click').on('click',function() {
        sound("se_btn");
        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END)){
            AppUtil.removeArrow();

            $("#navi_panel").hide();
            self.equipChange("auto");

            var summary = Page.getSummary();
            summary.opening = ["なかなか似合ってるのだ", 
                                "ま、だいたい説明はこんなもんなのだ",
                                "じゃ、そろそろじじいの家に戻るのだ",
                                   ];
            summary.openingNum = summary.opening.count().length;
            summary.end_function = "MypageDisplay.tutorial_end"

            self.NaviCanvas.reset();
        }else{
            self.equipChange("auto");
        }
    });
    //全装備解除ボタン
    $("#btn_cloth_out").off('click').on('click',function() {
        sound("se_btn");
        self.equipChange("release");
    });

    //メッセージボタン
    $("#btn_msg").off('click').on('click',function() {
        sound("se_btn");
        self.showMessageList(chara_user_id, "receive");
    });
    //仲間ボタン
    $("#btn_member_list").off('click').on('click',function() {
        sound("se_btn");
        self.showMemberList(chara_user_id);
    });
    //履歴ボタン
    $("#btn_history_list").off('click').on('click',function() {
        sound("se_btn");
        self.showHistoryList(chara_user_id, MainContentsDisplay.summary.player_name);
    });
    //戦歴ボタン
    $("#btn_battlelog").off('click').on('click',function() {
        sound("se_btn");
        self.showBattlelogList(chara_character_id);
    });

    self.super.reload.call(self);
}


//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MypageDisplay.prototype.onLoaded = function() {
    var self = MypageDisplay;

    $("#main_contents").fadeIn("fast", function(){
        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END)){
            //navi登場
            self.NaviCanvas.appear();

            $("#btn_hed").off('click');
            $("#btn_bod").off('click');
            $("#btn_wpn").off('click');
            $("#btn_acs").off('click');
            $("#btn_cloth_out").off('click');
            $("#grade_list_btn").off('click');

            $("#btn_msg").off('click');
            $("#btn_member_list").off('click');
            $("#btn_history_list").off('click');
            $("#btn_battlelog").off('click');

            AppUtil.disableButton($("#btn_cloth_out"), "174_66");
            AppUtil.disableButton($("#btn_msg"), "174_66");
            AppUtil.disableButton($("#btn_member_list"), "174_66");
            AppUtil.disableButton($("#btn_history_list"), "174_66");
            AppUtil.disableButton($("#btn_battlelog"), "174_66");

        }

        self.setEquipInfo();
        self.showExpireInfo();
        self.showParamupItemInfo();


        self.super.onLoaded.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * ステータスUPアイテムの使用状況を表示する
 */
MypageDisplay.prototype.showParamupItemInfo = function (){
    var self = MypageDisplay;

    if(self.status.paramupItemStatus != null){
        if(self.status.paramupItemStatus.param1 > 0){
            $("#paramup1").show();
            $("#paramup1_expire").html("残" + (20 - self.status.paramupItemStatus.param1) + "回");
        }
        if(self.status.paramupItemStatus.param2 > 0){
            $("#paramup2").show();
            $("#paramup2_expire").html("残" + (20 - self.status.paramupItemStatus.param2) + "回");
        }
        if(self.status.paramupItemStatus.param3 > 0){
            $("#paramup3").show();
            $("#paramup3_expire").html("残" + (20 - self.status.paramupItemStatus.param3) + "回");
        }
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * 効果を表示する
 */
MypageDisplay.prototype.showExpireInfo = function (){
    var self = MypageDisplay;

    self.flg = false;

    if(self.status.effectExpires != null){
        $("#effect_panel").show();
        var i = 0;
        $.each(self.status.effectExpires, function(type, entry){
            switch(type){
                case TYPE_EXP_INCREASE: //経験値増加
              		//発動時間
                  $("#effect_expup").show();
              		$("#effect_expup_expire").html(AppUtil.compareDate(entry.expire));
              		$("#effect_expup_text").html(entry.effect_name + "("+entry.value+"%)");
                  $("#effect_expup_icon").off("click").on("click",function(){
                      if(self.flg)
                          sound("se_btn");

                      $("#effect_expup_balloon").show();
                      $("#effect_rare_balloon").hide();
                      $("#effect_dtech_balloon").hide();

                      self.flg = true;
                  });

                  if(i == 0)
                      $("#effect_expup_icon").trigger("click");

                  break;                
                case TYPE_HP_RECOVER: //HP回復量増加(現在の所無し)
                  break;                
                case TYPE_ATTRACT: //ﾚｱ遭遇率上昇
                  $("#effect_rare").show();
              		$("#effect_rare_expire").html(AppUtil.compareDate(entry.expire));

                  if(entry.value == "1"){
                  		$("#effect_rare_text").html("レア遭遇↑("+ITEM_RARE_ENCOUNT_LV1+"%)<br>Sレア遭遇↑("+ITEM_SRARE_ENCOUNT_LV1+"%)");
                  }else if(entry.value == "2"){
                  		$("#effect_rare_text").html("レア遭遇↑("+ITEM_RARE_ENCOUNT_LV2+"%)<br>Sレア遭遇↑("+ITEM_SRARE_ENCOUNT_LV2+"%)");
                  }else if(entry.value == "3"){
                  		$("#effect_rare_text").html("レア遭遇↑("+ITEM_RARE_ENCOUNT_LV3+"%)<br>Sレア遭遇↑("+ITEM_SRARE_ENCOUNT_LV3+"%)");
                  }

                  $("#effect_rare_icon").off("click").on("click",function(){
                      if(self.flg)
                          sound("se_btn");

                      $("#effect_expup_balloon").hide();
                      $("#effect_rare_balloon").show();
                      $("#effect_dtech_balloon").hide();
                      self.flg = true;
                  });

                  if(i == 0)
                      $("#effect_rare_icon").trigger("click");

                  break;                
                case TYPE_DTECH_POWUP: //必殺率上昇
                  $("#effect_dtech").show();
              		$("#effect_dtech_expire").html(AppUtil.compareDate(entry.expire));
                  if(entry.value == "2")
                  		$("#effect_dtech_text").html(entry.effect_name + "("+ITEM_DTECH_UPPER_INVOKE+"%)" + "<br>威力↑("+ITEM_DTECH_UPPER_POWER+"%)");
                  else
                  		$("#effect_dtech_text").html(entry.effect_name + "("+ITEM_DTECH_UPPER_INVOKE+"%)" );
                  $("#effect_dtech_icon").off("click").on("click",function(){
                      if(self.flg)
                          sound("se_btn");

                      $("#effect_expup_balloon").hide();
                      $("#effect_rare_balloon").hide();
                      $("#effect_dtech_balloon").show();
                      self.flg = true;
                  });

                  if(i == 0)
                      $("#effect_dtech_icon").trigger("click");

                  break;      
            }
            i++;
        });
    }else{
        $("#effect_panel").hide();        
    }
}

//---------------------------------------------------------------------------------------------------------
/*
 * 装備情報を更新する
 *
*/
MypageDisplay.prototype.setEquipInfo = function() {
    var self = MypageDisplay;

    //装備武器

    //ヘッド
    $("#hed_text").empty();
    var head_dom = $("<div>");

    if(self.status["PLAEQP"][3] != undefined)
        head_dom.html(self.status["PLAEQP"][3]["item_name"]);
    else
        head_dom.html("(装備なし)");

    head_dom.css("font-size", "5em");
    $("#hed_text").append(head_dom);

    //arctextライブラリを使って文字をアーチ状にする
    head_dom.arctext({radius: 140, fitText:true});

    //アクセサリ
    $("#acs_text").empty();
    var acs_dom = $("<div>");

    if(self.status["PLAEQP"][4] != undefined)
        acs_dom.html(self.status["PLAEQP"][4]["item_name"]);
    else
        acs_dom.html("(装備なし)");

    acs_dom.css("font-size", "5em");
    $("#acs_text").append(acs_dom);

    //arctextライブラリを使って文字をアーチ状にする
    acs_dom.arctext({radius: 140, fitText:true});

    //武器
    $("#wpn_text").empty();
    var wpn_dom = $("<div>");

    if(self.status["PLAEQP"][1] != undefined)
        wpn_dom.html(self.status["PLAEQP"][1]["item_name"]);
    else
        wpn_dom.html("(装備なし)");

    wpn_dom.css("font-size", "5em");
    $("#wpn_text").append(wpn_dom);

    //arctextライブラリを使って文字をアーチ状にする
    wpn_dom.arctext({radius: 140, fitText:true});


    //ボディ
    $("#bod_text").empty();
    var bod_dom = $("<div>");

    if(self.status["PLAEQP"][2] != undefined)
        bod_dom.html(self.status["PLAEQP"][2]["item_name"]);
    else
        bod_dom.html("(装備なし)");

    bod_dom.css("font-size", "5em");
    $("#bod_text").append(bod_dom);

    //arctextライブラリを使って文字をアーチ状にする
    bod_dom.arctext({radius: 140, dir: -1, fitText:true});

}

//---------------------------------------------------------------------------------------------------------
/*
 * ステータス情報を更新する
 *
*/
MypageDisplay.prototype.setStatusInfo = function() {
    var self = MypageDisplay;
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
/*
 * 装備変更時に情報を更新する
 *
*/
MypageDisplay.prototype.changeReload = function() {
    var self = MypageDisplay;

    //マイページ画面ステータス情報取得API
    MypageApi.status(function(response){
        self.status = response;
        sound("se_repair");

        console.log(self.status["chara"]["imageUrl"]);

        self.setEquipInfo();
        self.setStatusInfo();
        self.CharaCanvas.change(self.status["chara"]["imageUrl"]);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 自動装備、装備をはずす
 * "auto"/"release"  自動装備/解除
 */
MypageDisplay.prototype.equipChange = function(func){
    var self = MypageDisplay;

    EquipApi.change(func, null,null,null, function(response){
        console.log(response);
        var text = "";
        if(response["result"] == "ok"){
            //flashに通知
            self.changeReload();
            //チュートリアル中の場合、ナビを再表示
            if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END)){
                setTimeout(function(){
                    $("#navi_panel").show();
                    self.NaviCanvas.appear();
                }, 2500);
            }
        }else{
            var text = "";
            switch(response["result"]){
                case "noitem":
                    text = "アイテムがないのだ";
                    break;
                case "equipping":
                    text = "装備中なのだ";
                    break;
                case "maxlevel":
                    text = "レベルがこれ以上上がらないのだ";
                    break;
                case "nomoney":
                    text = "マグナがないのだ。地に足をつけるのだ・・。";
                    break;
                case "in_quest":
                    text = "クエスト中は装備の変更はできないのだ。";
                    break;
                case "not_me":
                    text = "誰の装備を変更しようとしてるのだ・・？";
                    break;
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
            });
        }
    });
}
//---------------------------------------------------------------------------------------------------------
/**
 * 武器、アイテム一覧を出す
 */
MypageDisplay.prototype.ShowEquipChange = function(category){
    var d = new Dialogue();

    Page.setParams("equip_category", category);
    Page.setParams("me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(EquipChangeHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}
//---------------------------------------------------------------------------------------------------------
/**
 * メッセージリストポップアップを呼び出す
 */
MypageDisplay.prototype.showMessageList = function(userId,type){
    var self = MypageDisplay;

    var d = new Dialogue();

    Page.setParams("userId", userId);
    Page.setParams("type", type);
    Page.setParams("category", null);

    Page.setParams("me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(MessageListHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 仲間一覧用ポップアップを立ち上げる
 */
MypageDisplay.prototype.showMemberList = function(userId){
    var d = new Dialogue();

    Page.setParams("userId", userId);
    Page.setParams("me", d);
    Page.setParams("category", null);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(MemberListHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 履歴一覧用ポップアップを立ち上げる
 */
MypageDisplay.prototype.showHistoryList = function(userId, player_name){
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
MypageDisplay.prototype.showBattlelogList = function(charaId){
    var d = new Dialogue();

    Page.setParams("charaId", charaId);
    Page.setParams("me", d);
    Page.setParams("category", null);

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
MypageDisplay.prototype.destroy = function (){
    var self = MypageDisplay;

    //退場アニメーションが終わったらdestroy
    $("#main_contents").fadeOut("fast", function(){
        self.CharaCanvas.destroy();
        self.CharaCanvas = null;

        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END)){
            self.NaviCanvas.destroy();
            self.NaviCanvas = null;
        }

        $("#main_contents").empty();

        MypageDisplay = null;

        self.super.destroy.call(self);

    });

}

//---------------------------------------------------------------------------------------------------------
var MypageDisplay = new MypageDisplay();

$(document).ready(MypageDisplay.start.bind(MypageDisplay));

