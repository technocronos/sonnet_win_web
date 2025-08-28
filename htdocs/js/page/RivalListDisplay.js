/**
 * ユーザー対戦リストを制御するシングルトンオブジェクト。
 *
 */
function RivalListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(RivalListDisplay.prototype, 'constructor', {
        value : RivalListDisplay,
        enumerable : false
    });

    //スクロール無効
    $("#RivalListContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.defaultListId = "rivallist-list";
    this.defaultEntryId = "rivallist-template";

    this.list = {};

    this.scroll = undefined;

    this.key=0;

    this.summary = Page.getSummary();

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
RivalListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
RivalListDisplay.prototype.start = function() {
console.log("RivalListDisplay.start rannning...");
    var self = RivalListDisplay;

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
RivalListDisplay.prototype.reload = function (){
    var self = RivalListDisplay;

    $("#navi_serif").html("ユーザー対戦なのだ。バトルイベントもやってるのだ。下のバナー見てみるのだ。");
    list = $("#" + self.defaultListId);

    //バナーをステータスによって切り分ける
    $("#event_banner_img").attr("src", AppUrl.asset("img/parts/sp/bannar/b_q_battle_event_b_" + this.summary.battle_rank_info.status + ".png") )

    // まずは現在のリストを空に。
    list.empty();

    //バナークリック時イベントハンドラ
    $("#event_banner").off("click").on("click",function(){
        sound('se_btn');
        var d = new Dialogue();

        Page.setParams("category", 12);
        Page.setParams("battleevent_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(BattleEventHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();
    });

    $("#grade_list_btn").off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("gradelist_d", d);
        Page.setParams("screen", "rival");

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(GradeListHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();
        
    });

    //更新ボタンクリック時イベントハンドラ
    $("#btn_update").off("click").on("click",function(){
        sound('se_btn');
        self.reload();
    });

    //ユーザー一覧を取得。
    RivalApi.list(function(response){
        console.log(response);
        self.list = response.rivalList;

        // 一つもなかったら...
        if(self.list.length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);
            //スクロールは消す
            if(self.scroll)
                self.scroll.refresh();

            self.onLoaded();

            // 処理はここまで。
            return;
        }

        // パケットをリストに表示。
        self.refreshList(self.list);
        self.super.reload.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
RivalListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = RivalListDisplay;

    //ユーザー名
    //$("[key='user_name']", board).text( entry["user"]["name"] );
    //ユーザーアイコン
    $("[key='icon']", board).attr("src", AppUrl.asset("img/chara/" + entry.imageUrl) );

    $("[key='member']", board).text( entry["member"] );
    $("[key='player_name']", board).text( entry["player_name"] );
    $("[key='level']", board).text( entry["level"] );
    $("[key='grade']", board).text( entry["grade_name"] );

    //HPゲージ作成
    var gauge_width = ((entry.hp / entry.hp_max) * 277);
    $("[key='hp_gauge_bar']", board).css("width", gauge_width + "px");
    $("[key='hp_text']", board).html(parseInt(entry.hp) + "/" + parseInt(entry.hp_max));

    $("[key='att1']", board).text( entry["total_attack1"] );
    $("[key='att2']", board).text( entry["total_attack2"] );
    $("[key='att3']", board).text( entry["total_attack3"] );
    $("[key='spd']", board).text( entry["total_speed"] );

    $("[key='def1']", board).text( entry["total_defence1"] );
    $("[key='def2']", board).text( entry["total_defence2"] );
    $("[key='def3']", board).text( entry["total_defence3"] );
    $("[key='defX']", board).text( entry["total_defenceX"] );

    //対戦ボタンクリック時イベントハンドラ
    $("[key='battle_button']", board).off("click").on("click",function(){
        sound('se_btn');

        Page.setParams("his_user_id", entry.user_id);

        self.summary.menu1State = "hispageselect";
        self.summary.menu1Url = "his_page";
        MainContentsDisplay.FooterCanvas.summary = self.summary;
        MainContentsDisplay.FooterCanvas.set();
        MainContentsDisplay.FooterCanvas.ref();

        MainContentsDisplay.FooterCanvas.out("his_page","rival");

    });

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
RivalListDisplay.prototype.onLoaded = function() {
    var self = RivalListDisplay;

    $("#main_contents").fadeIn("fast", function(){
        //記事のスクロール表示
        if(self.scroll){
            self.scroll.refresh();
        }else{
            self.scroll = new IScroll('#RivalListContents .scrollWrapper', {
                click: true,
                scrollbars: 'custom', /* スクロールバーを表示 */
                //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
                //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
                //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
                mouseWheel: false
            });
        }
        self.super.onLoaded.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
RivalListDisplay.prototype.destroy = function (){
    var self = RivalListDisplay;

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();
        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var RivalListDisplay = new RivalListDisplay();

$(document).ready(RivalListDisplay.start.bind(RivalListDisplay));

