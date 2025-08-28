
/**
 * お知らせ詳細を制御するシングルトンオブジェクト。
 *
 */
function HomeDisplay(){

    this.scroll = undefined;

    //スクロール無効
    $("#HomeContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //スクロール無効
    $("#bg_front").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    $("#bg_front").css("pointer-events", "none");

    this.CharaCanvas = null;
    this.NaviCanvas = null;
    this.summary = null;

    this.super = DisplayCommon.prototype;

    //コンストラクタ名を書き換える
    Object.defineProperty(HomeDisplay.prototype, 'constructor', {
        value : HomeDisplay,
        enumerable : false
    });
}

// 親クラスを継承
HomeDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
HomeDisplay.prototype.start = function() {

console.log("HomeDisplay.start rannning...");
    var self = HomeDisplay;

    var safeareatop = getComputedStyle(document.documentElement).getPropertyValue("--sat");
    safeareatop = safeareatop.replace("px", "");

    if(isNaN(safeareatop)){
        safeareatop = 0;
    }

    //コンテンツ上部（クエスト、ナビあたり）の高さをセットする
    //このコンテンツは縮小されているので戻した高さにbg_fontで取りたい分をセットしてあげる
    var h = (parseInt($("#mainflex").css("height")) / (devicewidth / 750)) - (safeareatop * 2);
    $("#homeflex").css("height", h + "px")

    //ホームサマリ情報を取得する。
    HomeApi.summary(null, null, null, null, function(response){
        console.log(response);
        self.summary = response;

        //サマリを格納しておく
        Page.setSummary(self.summary);

        //MainContentsDisplayのサマリは一回しか呼ばれないので最新サマリで同期を取る。
        MainContentsDisplay.summary = self.summary;

        if(self.summary.start_speak1 == ""){
            if(self.summary.history.length > 0)
                self.summary.start_speak1 = "仲間の履歴なのだ。\n" + "『" + self.summary.history[0]["player_name"] + "さん：" + AppUtil.getHistoryText(self.summary.history[0]) + "』";
            else
                self.summary.start_speak1 = "何かあればここでお知らせするのだ";
        }

        //キャラ作成
        self.CharaCanvas = new CharaCanvas(self,null,self.summary["chara"]["user_id"], self.summary["chara"]["image_url"]);

        if(is_tablet != "tablet"){
            //navi作成
            self.NaviCanvas= new NaviCanvas(self,null,self.summary);
        }else{
            //navi作成
            self.NaviCanvas= new NaviCanvas(self,null,self.summary, 0, 180, 120);
        }

        //全canvasが読み込まれていることを保証する。
        var timer = null;
        $(function(){
            timer = setInterval(function(){
                if(self.CharaCanvas.loaded && self.NaviCanvas.loaded){
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
HomeDisplay.prototype.reload = function (){
    var self = HomeDisplay;

    //プレイヤー名
    $("#player_name").html(self.summary.player_name);

    //最新のお知らせ
    if(self.summary.oshiraseList[0] != undefined)
        $("#notice_latest").html(Date.generate(self.summary.oshiraseList[0].notify_at).format("MM月DD日") + " " + self.summary.oshiraseList[0].title);
    else
        $("#notice_latest").html("");

    if(self.summary.menu6State != "disable"){
        $("#img_quest").attr("src", AppUrl.asset("img/parts/sp/btn_quest.png"));
        $("#btn_quest").off("click").on("click",function(){
            self.onQuestClick();
        });
    }else{
        $("#img_quest").attr("src", AppUrl.asset("img/parts/sp/btn_quest_disable.png"));
    }

    //プロモーション用バナー
    if(parseInt(self.summary.tutorial_step) >= parseInt(TUTORIAL_END)){

        if(self.summary.battle_rank_info.status == 1 || self.summary.battle_rank_info.status == 4){
            $("#promotion_bannar_img").attr("src", AppUrl.asset("img/parts/sp/bannar/promotion.png") );
            $("#promotion_bannar_img").off('click').on('click',function() {
                self.onBattleClick();
            });
        }else{
/*
            $("#promotion_bannar_img").attr("src", AppUrl.asset("img/parts/sp/bannar/b_q_98061_b.png") );
            $("#promotion_bannar_img").off('click').on('click',function() {
                sound("se_btn");

                var d = new Dialogue();

                d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');

                d.content('<div id="content" style="width:750px;text-align: center;"><div style="margin-top: -84px;"><iframe width="700" height="394" src="https://www.youtube.com/embed/lwpRViLTOnE?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div></div>');

                d.autoClose = true;
                d.veilClose = true;
                d.opacity = 0.5;

                audio.sound_stop();

                d.show(function(){
                    sound("se_btn");
                    audio.sound(audio.currBgm);
                });

            });
*/
        }

        $("#promotion_bannar").show();
        $("#navi_sm").css("pointer-events", "none");

    }

    if(self.summary.bitcoin_show == 1){
        $("#btn_bitcoin").show();
        $("#btn_bitcoin").off("click").on("click",function(){
            self.bitcoinShow();
        });
    }

    if(self.summary.menu7State != "disable"){
        $("#img_battle").attr("src", AppUrl.asset("img/parts/sp/btn_battle.png"));
        $("#btn_battle").off("click").on("click",function(){
            self.onBattleClick();
        });
    }else{
        $("#img_battle").attr("src", AppUrl.asset("img/parts/sp/btn_battle_disable.png"));
    }

    if(self.summary.menu8State != "disable"){
        $("#img_notice").attr("src", AppUrl.asset("img/parts/sp/btn_notice.png"));
        $("#btn_notice").off("click").on("click",function(){
            self.NoticeListShow();
        });
    }else{
        $("#img_notice").attr("src", AppUrl.asset("img/parts/sp/btn_notice_disable.png"));
    }

    if(parseInt(self.summary.tutorial_step) < parseInt(TUTORIAL_END)){
        $("#btn_bitcoin").hide();
    }

    //フッター更新
    MainContentsDisplay.FooterCanvas.summary = self.summary;
    MainContentsDisplay.FooterCanvas.set();
    MainContentsDisplay.FooterCanvas.ref();

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト実行確認ポップアップを立ち上げる
 */
HomeDisplay.prototype.showConfirm = function(quest_entry,sally_quest_id){
    var d = new Dialogue();

    Page.setParams("sally_quest_id", sally_quest_id);
    Page.setParams("quest_entry", quest_entry);
    Page.setParams("quest_confirm_d", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(QuestConfirmHtml);

    d.autoClose = false;
    d.veilClose = false;
    //d.top = 32;

    d.show();
}
//---------------------------------------------------------------------------------------------------------
/**
 * クエスト実行確認ポップアップを立ち上げる
 */
HomeDisplay.prototype.showGiveup = function(quest_entry){
    var self = HomeDisplay;

    var d = new Dialogue();

    Page.setParams("popup_d", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(PopupConfirmHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();

    var text = quest_entry["quest_name"] + "をギブアップするのだ？";
    $("#confirm_body").html(text);

    $("#popupconf-ok").off('click').on('click',function() {
        sound("se_btn");
        //ギブアップをする
        QuestApi.giveup(function(response){
            console.log(response);

            if(response["result"] == "ok"){
                self.showFieldEnd(self.summary.chara.sally_sphere);

                self.CharaCanvas.destroy();
                self.NaviCanvas.destroy();

                self.CharaCanvas = null;
                self.NaviCanvas = null;

                HomeDisplay = null;

                $("#main_contents").empty();

                self.super.destroy.call(self);

                MainContentsDisplay.ContentsShow(HomeHtml);
            }

            PopupConfirmDisplay.destroy();

        });
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト終了ポップアップを表示する
 */
HomeDisplay.prototype.showFieldEnd = function (sphereId){
    var self = HomeDisplay;
    var d = new Dialogue();

    Page.setParams("_parent", null);
    Page.setParams("sphereId", sphereId);
    Page.setParams("fieldend_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(FieldEndHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト一覧を出す
 */
HomeDisplay.prototype.onQuestClick = function(){
    sound("se_btn");

    MainContentsDisplay.FooterCanvas.out('quest','menu');

/*
    var d = new Dialogue();

    Page.setParams("battleId", 8342);
    Page.setParams("repaireId", null);
    Page.setParams("battle_result_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(BattleResultHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = -50;
    d.opacity = 1;

    d.show();
*/

}
//---------------------------------------------------------------------------------------------------------
/**
 * バトル一覧を出す
 */
HomeDisplay.prototype.onBattleClick = function(){
    sound("se_btn");

    MainContentsDisplay.FooterCanvas.out('rival','menu');
}

//---------------------------------------------------------------------------------------------------------
/**
 * お知らせ一覧を出す
 */
HomeDisplay.prototype.bitcoinShow = function(){
    sound("se_btn");

    var d = new Dialogue();

    Page.setParams("me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(BitcoinHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;

    d.show();
}
//---------------------------------------------------------------------------------------------------------
/**
 * お知らせ一覧を出す
 */
HomeDisplay.prototype.NoticeListShow = function(){
    sound("se_btn");

    var d = new Dialogue();

    Page.setParams("me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(NoticeListHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}
//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
HomeDisplay.prototype.onLoaded = function() {
    var self = HomeDisplay;

    $("#home_main_div").hide();

    $("#main_contents").fadeIn("fast", function(){
        //真ん中のクエスト＋ナビのコンテンツを縦中央にする
        var flex1_height = parseInt($("#home_flex1").css("height")) / 2;
        $("#home_main_div").css("margin-top", flex1_height + "px")

        $("#home_main_div").show();

        $("#name_panel").show();

        $("#bg_front").html(Page.preload_image["bg_front"]);

        if(self.summary.chara.sally_sphere != null){
            $("#in_quest").show();
            $("#player_name").html("クエスト中");
        }else{
            //キャラ登場
            self.CharaCanvas.canvas.style.left = "-30px";
            self.CharaCanvas.in();
        }

        //navi登場
        self.NaviCanvas.appear();


        //実行中クエストがある場合
        if(self.summary.sally_quest != null){
            $("#bannar_panel").hide();

            $("#sally_quest_panel").show();
            $("#sally_quest_name").html("『" + self.summary.sally_quest.quest_name + "』実行中！");

            //クエスト実行ボタンイベントハンドラ
            $("#quest_do").off("click").on("click",function(){
                sound("se_btn");
                self.showConfirm(self.summary.sally_quest, self.summary.sally_quest.quest_id);
            });

            if(parseInt(self.summary.tutorial_step) >= parseInt(TUTORIAL_END)){
                //クエストやめボタンイベントハンドラ
                $("#quest_giveup").off("click").on("click",function(){
                    sound("se_btn");
                    self.showGiveup(self.summary.sally_quest);
                });
            }else{
                AppUtil.disableButton($("#quest_giveup"), "132_70");
            }

        }else{
            $("#sally_quest_panel").hide();

            if(parseInt(self.summary.tutorial_step) >= parseInt(TUTORIAL_END)){

                //バナー
                if(self.summary.freeGacha){
                    $("#banner").attr("src", AppUrl.asset("/img/gacha/09997.png"));
                    $("#banner_explain").html("現在、1日1回無料ガチャが引けます");

                    $("#bannar_panel").show();
                    $("#bannar_panel").off("click").on("click", function(){
                        sound("se_btn");

                        GachaApi.gacha(function(response){
                            console.log(response);
                            var entry = {"gacha_id":9997};
                            Page.setParams("freeGacha", response.freeGacha);
                            Page.setParams("ticketCount", response.ticketCount);
                            Page.setParams("gacha_entry", entry);

                            MainContentsDisplay.FooterCanvas.out("gacha_detail","home");
                        });
                    });
                }else if(self.summary.battle_rank_info.status == 1 || self.summary.battle_rank_info.status == 4){
                    //$("#promotion_bannar").show();
                    $("#banner").attr("src", AppUrl.asset("img/parts/sp/bannar/b_q_battle_event.png") )
                    if(self.summary.battle_rank_info.status == 1){
                        //$("#banner_explain").html("只今「軍鶏の時計」20％OFF！");
                        $("#banner_explain").html("只今開催中！！");
                    }else if(self.summary.battle_rank_info.status == 4){
                        $("#banner_explain").html(Date.generate(AppUtil.fromtimestamp(self.summary.battle_rank_info.start_date)).format("MM月DD日") + "から開催！");
                    }

                    $("#bannar_panel").show();
                    $("#bannar_panel").off("click").on("click", function(){
                        sound("se_btn");
                        self.onBattleClick();
                    });
                }else if(self.summary.bannar.quest != null){
                    //バナーがある場合はそれを表示する
                    $("#banner").attr("src", AppUrl.asset("/img/parts/sp/bannar/b_q_"+self.summary.bannar.quest[0]["quest_id"]+".png"));
                    $("#banner_explain").html(self.summary.bannar.explain);

                    $("#bannar_panel").show();
                    $("#bannar_panel").off("click").on("click", function(){
                        sound("se_btn");
                        self.showConfirm(self.summary.bannar.quest[0], null);
                    });
                }
            }
        }

    });

    self.super.onLoaded.call(self);
}

/*
 * メインメニュー＆クエストのチュートリアルでナビがしゃべり終わった後にコールされるので
 * クエストに案内すること
*/
HomeDisplay.prototype.tutorial_mainmenu_navi_speak_end = function (){
    var self = HomeDisplay;

    //クエストボタンを有効にする
    $("#img_quest").attr("src", AppUrl.asset("img/parts/sp/btn_quest.png"));
    $("#btn_quest").off("click").on("click",function(){
        self.onQuestClick();
    });

    //ナビカーソルを表示する
    AppUtil.showArrow($("#btn_quest"), "down", -40, 125);    
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
HomeDisplay.prototype.destroy = function (){
    var self = HomeDisplay;

    //キャラ退場
    self.CharaCanvas.out();
    self.NaviCanvas.out();

    //退場アニメーションが終わったらdestroy
    $("#main_contents").fadeOut("fast", function(){
        self.CharaCanvas.destroy();
        self.NaviCanvas.destroy();

        self.CharaCanvas = null;
        self.NaviCanvas = null;

        $("#main_contents").empty();

        HomeDisplay.summary = null;
        HomeDisplay = null;

        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var HomeDisplay = new HomeDisplay();

$(document).ready(HomeDisplay.start.bind(HomeDisplay));

