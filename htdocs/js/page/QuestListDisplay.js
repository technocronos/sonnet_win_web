
/**
 * クエストリストのオーバーレイコンテンツ（ボタン等）を制御するシングルトンオブジェクト。
 *
 */
function QuestListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(QuestListDisplay.prototype, 'constructor', {
        value : QuestListDisplay,
        enumerable : false
    });

    this.defaultListId = "quest-list";
    this.defaultEntryId = "quest-list-template";

    this.questlist = Page.getParams("questlist");
    this.region = Page.getParams("region");
    this.place = Page.getParams("place");
    this.me = Page.getParams("questlist_d");

    this.QuestStatusCanvas = null;
    this.NaviCanvas = null;

    this.timer = $();
    this.list = $();

    this.sp_quest_exists = false;

    this.scroll = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
QuestListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
QuestListDisplay.prototype.start = function() {
console.log("QuestListDisplay.start rannning...");
    var self = QuestListDisplay;

    if(is_tablet == "tablet"){
        $("#questlist_middle").css("transform", "scale(0.8)");
        $("#questlist_middle").css("transform-origin", "50% 50%");
        $("#questlist_middle").css("top", "200px");
    }

    //クエスト閉じるボタンイベントハンドラ
    $("#quest_close").off("click").on("click",function(){
        sound("se_btn");
        sound("se_hover");

        $("#" + self.defaultListId).animate({
            "left": "710px"
        },400, function(){
            self.destroy();
        });
    });

/*
    //実行中クエストがある場合
    if(self.questlist.sally_quest_id != ""){
        $("#sally_quest_panel").show();
        $("#sally_quest_name").html(self.questlist.sally_quest.quest_name + "実行中！");

        //クエスト実行ボタンイベントハンドラ
        $("#quest_do").off("click").on("click",function(){
            sound("se_btn");
            self.showConfirm(self.questlist.sally_quest, self.questlist.sally_quest_id);
        });

        if(parseInt(Page.getSummary().tutorial_step) >= parseInt(TUTORIAL_END)){
            //クエストやめボタンイベントハンドラ
            $("#quest_giveup").off("click").on("click",function(){
                sound("se_btn");
                self.showGiveup(self.questlist.sally_quest);
            });
        }else{
            AppUtil.disableButton($("#quest_giveup"), "132_70");
        }
    }else{
        $("#sally_quest_panel").hide();
    }
*/

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
QuestListDisplay.prototype.reload = function (){
    var self = QuestListDisplay;

    var contents_height = parseInt($("#mainflex").css("height")) / ($(window).width() / 750);
    var banner_height = parseInt($("#week_bannar_panel").css("height"));


    //ポイントpex格納オブジェクトを初期化
    self.QuestStatusCanvas = new Object();

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    list.css("left", "710px");

    var caption_height = contents_height;

    $.each(self.questlist.quest[self.region][self.place], function(key, value){
        console.log(value);
        var height = parseInt($("#mainflex").css("height")) + 200;

        if(value.place_id == 0){
            //モンスターの洞窟
            self.sp_quest_exists = true;

            $("#week_banner").attr("src", AppUrl.asset("/img/parts/sp/bannar/b_q_"+value.quest_id+".png"));

            $("#week_banner_explain").html(value.special_explain);

            $("#week_bannar_panel").css("top", (contents_height - banner_height) + "px");
            caption_height -= banner_height;            
            $("#week_bannar_panel").show();

            //クエスト実行ボタンイベントハンドラ
            $("#week_bannar_panel").off("click").on("click",function(){
                sound("se_btn");
                self.showConfirm(value, self.questlist.sally_quest_id);
            });
        }else if(value.place_id == 98){
            //イベント
            self.sp_quest_exists = true;

            $("#event_banner").attr("src", AppUrl.asset("/img/parts/sp/bannar/b_q_"+value.quest_id+".png"));

            $("#event_banner_explain").html("只今イベント開催中！");

            $("#event_bannar_panel").css("top", (contents_height - (banner_height * 2)) + "px");
            caption_height -= banner_height;            
            $("#event_bannar_panel").show();

            //クエスト実行ボタンイベントハンドラ
            $("#event_bannar_panel").off("click").on("click",function(){
                sound("se_btn");
                self.showConfirm(value, self.questlist.sally_quest_id);
            });
        }else{
            self.list = self.list.add(value);
        }
    });

    $("#special_quest_caption").css("top", (caption_height - 100) + "px");

    if(self.list.length == 0){
        var list = [{"quest_id":null}]; 
        self.list = self.list.add(list);
    }

    //self.list = self.questlist.quest[self.region][self.place];

    // パケットをリストに表示。
    self.refreshList(self.list);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
QuestListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = QuestListDisplay;

    if(entry.quest_id == null){
        $("[key='icon']", board).hide();
        $("[key='go']", board).hide();
        $("[key='name']", board).text( "まだ解放されているクエストがありません" );
        self.super.setupEntryBoard.call(self);
        return;
    }

    $("[key='name']", board).text( entry["quest_name"] );

    //背景パネル色切り替え
    if(entry["place_id"] == 0){
        //モンスターの洞窟
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/quest_list_panel_clear.png"));
        $("[key='name']", board).css("color", "#226666");
    }else if(entry["place_id"] == 98){
        //イベント
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/quest_list_panel_enbl_event.png"));
        $("[key='name']", board).css("color", "#FFB3B3");
    }else{
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/quest_list_panel_normal.png"));
        $("[key='name']", board).css("color", "#6E594E");
    }

    //ステータス作成
    var status = entry.status;

    //実行中クエの場合
    if(self.questlist.sally_quest_id != "" && entry.quest_id == self.questlist.sally_quest_id){
        status = 4;
    }

    $("[key='icon']", board).attr("src", AppUrl.asset("img/parts/sp/quest_status_"+status+".png"));

    if(status == 1 || status == 4){
        var image = {};
        image[0] = "img/parts/sp/quest_status_"+status+"_2.png";
        image[1] = "img/parts/sp/quest_status_"+status+".png";

        self.timer = self.timer.add(AppUtil.changeImageAnim($("[key='icon']", board), image));
    }

    //クエスト実行ボタンイベントハンドラ
    $("[key='go']", board).off("click").on("click",function(){
        sound("se_btn");

        if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_MAINMENU))
            AppUtil.removeArrow();

        self.showConfirm(entry, self.questlist.sally_quest_id);
    });

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
QuestListDisplay.prototype.onLoaded = function() {
console.log("QuestListDisplay start..");
    var self = QuestListDisplay;

    //特別クエストのキャプション
    //$("#special_quest_caption").css("top");
    console.log(parseInt($("#quest-list").css("height")) * (devicewidth / 750));
    console.log(parseInt($("#quest-list").css("top")) * (devicewidth / 750));
    console.log(parseInt($("#special_quest_caption").css("top")) * (devicewidth / 750));

    //特別クエキャプションのTOP
    var caption_top = parseInt($("#special_quest_caption").css("top")) * (devicewidth / 750);
    var questlist_top = parseInt($("#quest-list").css("top")) * (devicewidth / 750);
    var questlist_height = parseInt($("#quest-list").css("height")) * (devicewidth / 750);

    //リストの一番下
    var list_height = questlist_top + questlist_height;

    //それでも被ってる場合は・・いさぎよく非表示
    if(list_height >= caption_top){
        //var t = parseInt($("#quest-list").css("top")) - (list_height - caption_top + 20);
        //$("#quest-list").css("top", t);
        $("#special_quest_caption").hide();
        $("#event_bannar_panel").hide();
        $("#week_bannar_panel").hide();
        self.sp_quest_exists = false;
    }

    //地点キャプション位置
    $("#quest_caption").css("top", (parseInt($("#quest-list").css("top")) - 130) + "px" )
    //地点キャプション名
    $("#placename").html(self.questlist.place[self.region][self.place]["Name"])

    $("#quest_caption").show();

    if(self.sp_quest_exists)
        $("#special_quest_caption").show();

    //チュートリアル中の場合
    if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_MAINMENU)){
        var summary = Page.getSummary();
        summary.opening = ["ここがクエストなのだ\n今いる地点のクエストのリストが出るのだ", 
                                "リストをひっこめて別の所に移動\nしたきゃクエストのリストを右に\nフリックしたらどくのだ",
                                "それか右上の「リストを閉じる」の\n黄色いボタン押しても閉じるのだ",
                                "また表示したきゃその地点をタップ\nすれば出てくるのだ",
                                "ちょっとやってみてもいいのだ\n大丈夫なのだ？",
                                "じゃ、今はソネットの契約をしに\n精霊の洞窟に行くのだ",
                                "GOボタンを押すのだ",
                               ];
        summary.openingNum = summary.opening.count().length;
        summary.end_function = "QuestListDisplay.tutorial_quest_navi_speak_end"

        //navi作成
        self.NaviCanvas= new NaviCanvas(self,"_quest",summary);

        if(is_tablet !== "tablet")
            $("#navi_panel").css("top", (parseInt($("#quest-list").css("top")) + 10) + "px");
        else
            $("#navi_panel").css("top", 200 + "px");

        var timer = null;
        $(function(){
            timer = setInterval(function(){
                if(self.NaviCanvas.loaded){
                    //loading..表示タイマーストップ
                    clearInterval(timer);
                    //ナビ開始
                    self.NaviCanvas.appear();
                }
            },500);
        });

        $("#swipe_div").css("top", $("#quest_caption").css("top"));

        $("#swipe_div").show();
        $("#list_show_cursor_div").show();

    }

    sound("se_hover");

    list = $("#" + self.defaultListId);
    list.animate({
        "left": "0px"
    },200);

    console.log("self.constructor.name = " + self.constructor.name);

    //タッチ用イベントハンドラ
    list.off("touchstart").on("touchstart", self.touchHandler);
    list.off("touchmove").on("touchmove", self.touchHandler);
    list.off("touchend").on("touchend", self.touchHandler);

//console.log($("#quest-list").css("height"))

    self.super.onLoaded.call(self);
}

/*
  ナビがしゃべり終わった
*/
QuestListDisplay.prototype.tutorial_quest_navi_speak_end = function(e) {

    //ナビカーソルを表示する
    AppUtil.showArrow($("#quest-list"), "down", -60, 600);
    
}
//---------------------------------------------------------------------------------------------------------
/**
 * タッチハンドラ。
 */
QuestListDisplay.prototype.touchHandler = function(e) {
    var self = QuestListDisplay;
    list = $("#" + self.defaultListId);

    //e.preventDefault();

    var touch = e.touches[0];
    var changedTouches = e.changedTouches[0];

    if(e.type == "touchstart"){
        self.touchstartX = touch.pageX;
        self.touchstartY = touch.pageY;

        self._x = list.offset().left;
        self._y = list.offset().top;
    }
    if(e.type == "touchmove"){

        var touchX = self._x + (touch.pageX - self.touchstartX);
        var touchY = self._y + (touch.pageY - self.touchstartY);

        if(touchX <= 0)
            return;

        if(touchX > 0 && touchX <= 100){
            list.css("left", touchX + "px");
        }else{
            sound("se_hover");
            list.animate({
                "left": "710px"
            },400, function(){
                self.destroy();
            });
        }
    }
    if(e.type == "touchend"){
        if(list.offset().left <= 100){
            list.animate({
                "left": "0px"
            },100);
        }
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト実行確認ポップアップを立ち上げる
 */
QuestListDisplay.prototype.showConfirm = function(quest_entry,sally_quest_id){
    var d = new Dialogue();

    Page.setParams("sally_quest_id", sally_quest_id);
    Page.setParams("quest_entry", quest_entry);
    Page.setParams("quest_confirm_d", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(QuestConfirmHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト実行確認ポップアップを立ち上げる
 */
QuestListDisplay.prototype.showGiveup = function(quest_entry){
    var self = QuestListDisplay;

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
                QuestDisplay.showFieldEnd(QuestDisplay.questlist.sally_sphere);
            }

            PopupConfirmDisplay.destroy();
            self.destroy();
        });

    });
}
//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
QuestListDisplay.prototype.destroy = function (){
    var self = QuestListDisplay;

    //タイマー後処理
    $.each(self.timer, function(key, timer){
        clearInterval(timer);
    });

    if(self.NaviCanvas != null)
        self.NaviCanvas.destroy();

    Page.setParams("questlist", null);
    Page.setParams("region", null);
    Page.setParams("place", null);

    Page.setParams("questlist_d", null);

    self.me.close();

    self.super.destroy.call(self);

    QuestListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var QuestListDisplay = new QuestListDisplay();

$(document).ready(QuestListDisplay.start.bind(QuestListDisplay));

