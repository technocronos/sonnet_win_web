
/**
 * クエスト確認を制御するシングルトンオブジェクト。
 *
 */
function QuestConfirmDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(QuestConfirmDisplay.prototype, 'constructor', {
        value : QuestConfirmDisplay,
        enumerable : false
    });

    this.timer = null;

    this.sally_quest_id = Page.getParams("sally_quest_id");
    if(this.sally_quest_id == "")
        this.sally_quest_id = null;

    this.entry = Page.getParams("quest_entry");
    this.me = Page.getParams("quest_confirm_d");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
QuestConfirmDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
QuestConfirmDisplay.prototype.start = function() {
console.log("QuestConfirmDisplay.start rannning...");
    var self = QuestConfirmDisplay;

    $("#img_win").html(Page.preload_image.quest_conf_window);

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
QuestConfirmDisplay.prototype.reload = function (){
    var self = QuestConfirmDisplay;

    //クエスト名
    $("#quest_name").html(self.entry.quest_name);
    //フレーバーテキスト
    $("#flavor_text").html(AppUtil.nl2br(self.entry.flavor_text));

    //クエストタイプ
    switch(self.entry.type){
        case "FLD":
            $("#quest_type").html("フィールド");
            break;
        default:
            $("#quest_type").html("イベント");
            break;
    }

    //推奨レベル
    if(self.entry.preferred_level != "" && self.entry.preferred_level != null)
        $("#preferred_level").html(self.entry.preferred_level);
    else
        $("#preferred_level").html("---");


    //消費AP
    $("#consume_pt").html(self.entry.consume_pt);

    $("#navi_serif_conf").html("このクエを実行するのだ？");

    var no = self.entry.quest_id + "_conf"

    //ステータス作成
    var status = self.entry.status;
    //実行中クエの場合
    if(self.sally_quest_id != null && self.entry.quest_id == self.sally_quest_id){
        status = 4;
    }

    $("#quest_status_icon").attr("src", AppUrl.asset("img/parts/sp/quest_status_"+status+".png"));

    if(status == 1 || status == 4){
        var image = {};
        image[0] = "img/parts/sp/quest_status_"+status+"_2.png";
        image[1] = "img/parts/sp/quest_status_"+status+".png";

        self.timer = AppUtil.changeImageAnim($("#quest_status_icon"), image);
    }


    //OKボタンクリック時イベントハンドラ
    $("#quest-confirm-ok").off('click').on('click',function() {
        sound("se_btn");

        $("#quest-confirm-ok").off('click');

        //実行中クエがあり、それの場合
        if(self.sally_quest_id != null && self.entry.quest_id == self.sally_quest_id){
            sound_stop();
            //すぐ遷移するとサウンドが鳴らないので・・
            setTimeout(function(){
                window.location.href = self.entry["url"];
            }, 500);
        }else{
            if(self.entry.type == "FLD"){
                //準備画面へ
                self.showReady(self.entry);
                setTimeout(function(){
                  self.destroy();
                }, 500)
            }else{
                sound_stop();
                //ドラマの場合は直接遷移
                setTimeout(function(){
                    window.location.href = self.entry["url"];
                }, 500);
            }
        }
    });

    //実行中クエがあり、それでない場合
    if(self.sally_quest_id != null && self.entry.quest_id != self.sally_quest_id){
        $("#quest-confirm-ok").off('click');
        $("#navi_serif_conf").html("別のクエを実行中なのだ。");
        AppUtil.disableButton($("#quest-confirm-ok"),"174_74");
    }

    //キャンセルボタンクリック時イベントハンドラ
    $("#quest-confirm-cancel").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * オーバーレイコンテンツを表示する。
 */
QuestConfirmDisplay.prototype.showReady = function (entry){
    //オーバーレイボタン領域表示
    var d = new Dialogue();

    Page.setParams("ready_entry", entry);
    Page.setParams("ready_d", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(ReadyHtml);
    d.autoClose = false;
    d.veilClose = false;
    d.veilShow = false;
    d.top = 0;

    d.show();
}


//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
QuestConfirmDisplay.prototype.onLoaded = function() {
    var self = QuestConfirmDisplay;

    if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_MAINMENU)){
        //ナビカーソルを表示する
        AppUtil.showArrow($("#QuestConfirmContents"), "down", 600, 250);
        $("#quest-confirm-cancel").off('click');
        AppUtil.disableButton($("#quest-confirm-cancel"), "174_74");
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
QuestConfirmDisplay.prototype.destroy = function (){
    var self = QuestConfirmDisplay;

    clearInterval(self.timer);

    Page.setParams("quest_entry", null);
    Page.setParams("quest_confirm_d", null);

    self.me.close();
    self.super.destroy.call(self);
    QuestConfirmDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var QuestConfirmDisplay = new QuestConfirmDisplay();

$(document).ready(QuestConfirmDisplay.start.bind(QuestConfirmDisplay));

