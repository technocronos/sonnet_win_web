
/**
 * メッセージリストを制御するシングルトンオブジェクト。
 *
 */
function HelpListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(HelpListDisplay.prototype, 'constructor', {
        value : HelpListDisplay,
        enumerable : false
    });

    this.defaultListId = "help-list";
    this.defaultEntryId = "help-template";

    this.me = Page.getParams("me");

    this.list = {};
    this.scroll = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
HelpListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
HelpListDisplay.prototype.start = function() {
console.log("HelpListDisplay.start rannning...");
    var self = HelpListDisplay;

    var content_height = 1100;

    if(is_tablet != "tablet"){
        $("#helpdiv").css("top" , screen.height + "px");
        $("#helpdiv").css("margin-top", "-" + (content_height * ($(window).width() / 750)) + "px");
    }

    $("#bg_image").html(Page.preload_image.bg_none);
    $("#help-list-close").html(Page.preload_image.help_close_btn);

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
HelpListDisplay.prototype.reload = function (){
    var self = HelpListDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //okボタンクリック時イベントハンドラ
    $("#help-list-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    //タブを作成する。
    HelpApi.list(function(list){
        console.log(list);

        self.list = list;

        // 一つもなかったら...
        if(self.list.length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);

            self.scroll.refresh();

            // 処理はここまで。
            return;
        }

        var helplist = $();

        $.each(self.list.groups , function(key,value){
            helplist = helplist.add({"title":value});
            $.each(self["list"]["helpTree"][key], function(){
                helplist = helplist.add(this);
            });
        });

console.log(helplist);

        // パケットをリストに表示。
        self.refreshList(helplist);

        self.super.reload.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
HelpListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = HelpListDisplay;

    if(entry["title"]){
        $("[key='title_panel']", board).show();
        $("[key='koumoku_panel']", board).hide();

        $("[key='title']", board).text( entry["title"] );
    }else{
        $("[key='title_panel']", board).hide();
        $("[key='koumoku_panel']", board).show();

        $(board).css("height", "93px");

        $("[key='koumoku_panel']", board).attr("help_id",  entry["help_id"] );

        $("[key='help_title']", board).text( entry["help_title"] );
    }

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 項目パネルクリック時イベントハンドラ。
 */
HelpListDisplay.prototype.onDetailClick = function(button) {
    console.log("onDetailClick");

    var help_id = $(button).attr("help_id");

    this.openbyhelpid(help_id);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 該当ヘルプIDの詳細を開く
 */
HelpListDisplay.prototype.openbyhelpid = function(help_id) {

    sound("se_btn");
    var detail_d = new Dialogue();

    Page.setParams("help_id", help_id);
    Page.setParams("detail_me", detail_d);

    detail_d.appearance('<div ><div key="dialogue-content"></div></div>');
    detail_d.content(HelpDetailHtml);

    detail_d.autoClose = false;
    detail_d.veilClose = false;
    detail_d.top = 0;

    detail_d.show();
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
HelpListDisplay.prototype.onLoaded = function() {
    var self = HelpListDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#HelpListContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
HelpListDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    HelpListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var HelpListDisplay = new HelpListDisplay();

$(document).ready(HelpListDisplay.start.bind(HelpListDisplay));

