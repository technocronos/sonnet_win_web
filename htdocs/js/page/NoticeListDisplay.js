/**
 * メッセージリストを制御するシングルトンオブジェクト。
 *
 */
function NoticeListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(NoticeListDisplay.prototype, 'constructor', {
        value : NoticeListDisplay,
        enumerable : false
    });

    this.defaultListId = "notice-list";
    this.defaultEntryId = "notice-list-template";

    this.me = Page.getParams("me");

    this.noticelist = {};
    this.scroll = undefined;

    this.scroll_loading = true;

    this.nolist = false
    this.page = 0;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
NoticeListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
NoticeListDisplay.prototype.start = function() {
console.log("NoticeListDisplay.start rannning...");
    var self = NoticeListDisplay;

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
NoticeListDisplay.prototype.reload = function (){
    var self = NoticeListDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //okボタンクリック時イベントハンドラ
    $("#notice-list-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    //タブを作成する。
    NoticeApi.list(self.page, function(noticelist){
        console.log(noticelist);

        self.noticelist = noticelist.resultset;

        // 一つもなかったら...
        if(self.noticelist.length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);
            //スクロールは消す
            if(self.scroll)
                self.scroll.refresh();

            // 処理はここまで。
            return;
        }

        // パケットをリストに表示。
        self.refreshList(self.noticelist);

        self.super.reload.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
NoticeListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = NoticeListDisplay;

    $("[key='title']", board).text( entry["title"] );
    $("[key='notify_at']", board).text( entry["notify_at"] );

    $("[key='button']", board).off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("notify_at", entry.notify_at);
        Page.setParams("title", entry.title);
        Page.setParams("body", entry.body);
        Page.setParams("noticedetail_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(NoticeDetailHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.top = null;

        d.show();
    });

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
NoticeListDisplay.prototype.onLoaded = function() {
    var self = NoticeListDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#NoticeListContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });

        //---------------------------------------------------------------------------------------------------------
        /*
         * スクロールバーが終端に来たらloadingを表示しつつ、残りのデータを読み込む
        */
        self.scroll.on('scrollEnd', function () {
            console.log("onScrollEnd.." + this.y);

            if(this.maxScrollY == this.y && self.nolist == false){
                self.showScrollLoading(self.scroll);

                self.page++;
                NoticeApi.list(self.page, function(noticelist){
                    console.log(noticelist);

                    self.noticelist = noticelist.resultset;

                    // 一つもなかったら...
                    if(self.noticelist.length == 0) {
                        self.nolist = true;
                        self.hideScrollLoading(self.scroll);
                        return;
                    }

                    //キーを既存のものとマージする
                    var newKey = self.noticelist.length;
                    $.each(noticelist.resultset, function(key, value){
                        self.noticelist[newKey] = value;
                        newKey++;
                    });

                    // パケットをリストに表示。
                    self.refreshList(self.noticelist,false);
                    self.hideScrollLoading(self.scroll);
                });
            }else{
                self.showScrollLoading(self.scroll);
                self.hideScrollLoading(self.scroll);
            }
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
NoticeListDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    NoticeListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var NoticeListDisplay = new NoticeListDisplay();

$(document).ready(NoticeListDisplay.start.bind(NoticeListDisplay));

