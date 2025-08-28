
/**
 * 履歴リストを制御するシングルトンオブジェクト。
 *
 */
function HistoryListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(HistoryListDisplay.prototype, 'constructor', {
        value : HistoryListDisplay,
        enumerable : false
    });

    this.defaultListId = "history-list";
    this.defaultEntryId = "history-template";

    this.me = Page.getParams("me");

    this.userId = Page.getParams("userId");
    this.player_name = Page.getParams("player_name");

    this.type = "history";
    this.category = Page.getParams("category");

    if(this.category == undefined)
       this.category = "me";

    this.list = {};
    this.scroll = undefined;

    this.response = undefined;

    this.scroll_loading = true;
  
    this.nolist = false
    this.page = 0;

    this.ClassName = "HistoryListDisplay";

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
HistoryListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
HistoryListDisplay.prototype.start = function() {
console.log("HistoryListDisplay.start rannning...");
    var self = HistoryListDisplay;

    //タブの選択
    $("#tab-" + self.category).css("display", "block");

    //他人のページの場合はタブ自体非表示
    if(self.userId != chara_user_id){
        $("#tab-member_touch").hide();
        $("#tab-bg").hide();
    }

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
HistoryListDisplay.prototype.reload = function (){
    var self = HistoryListDisplay;

    $("#bg_image").html(Page.preload_image.msg_window);
    $("#tab-username").html(self.player_name);

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //okボタンクリック時イベントハンドラ
    $("#dialogue-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    //タブを作成する。
    HistoryApi.list(self.userId,self.type,self.category,self.page, function(response){
        console.log(response);

        self.list = response["list"]["resultset"];

        // 一つもなかったら...
        if(self.list.length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);

            if(self.scroll)
                self.scroll.refresh();

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
HistoryListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = HistoryListDisplay;

    $("[key='create_at']", board).text( entry["create_at"] );
    $("[key='history_id']", board).text( "NO" + entry["history_id"] );
    $("[key='player_name']", board).text( entry["player_name"] );

    //$("[key='image']", board).attr("src", entry["thumbnailUrl"] == null ? AppUrl.asset("img/parts/sp/avatar_thumb_88_100.png"):entry["thumbnailUrl"] );
    $("[key='image']", board).attr("src", AppUrl.asset("img/chara/" + entry.imageUrl) );

    $("[key='text']", board).text( AppUtil.getHistoryText(entry) );

    if(entry["user_id"] != chara_user_id){
    }

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
HistoryListDisplay.prototype.onLoaded = function() {
    var self = HistoryListDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#HistoryListContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });

        //---------------------------------------------------------------------------------------------------------
        /*
         * スクロールバーが終端に来たら残りのデータを読み込む
        */
        self.scroll.on('scrollEnd', function () {
            console.log("onScrollEnd.." + this.y);

            if(this.maxScrollY == this.y && self.nolist == false){
                self.showScrollLoading(self.scroll);

                self.page++;
                HistoryApi.list(self.userId,self.type,self.category,self.page, function(response){
                    console.log(response);

                    // 一つもなかったら...
                    if(response["list"]["resultset"].length == 0) {
                        self.nolist = true;
                        self.hideScrollLoading(self.scroll);
                        return;
                    }

                    //キーを既存のものとマージする
                    var newKey = self.list.length;
                    $.each(response["list"]["resultset"], function(key, value){
                        self.list[newKey] = value;
                        newKey++;
                    });

                    console.log(self.list);

                    // レコードをリストに追加表示。
                    self.refreshList(response["list"]["resultset"],false);
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
 * タブ切り替え時に呼び出される。
 */
HistoryListDisplay.prototype.onChangeTab = function(category) {
    if(this.category == category)
        return;

    sound("se_btn");

    //前のタブの選択を削除
    $("#tab-" + this.category).css("display", "none");
    this.category = category;
    //新しいタブの選択
    $("#tab-" + this.category).css("display", "block");

    this.page = 0;
    this.nolist = false;

    this.reload();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
HistoryListDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    HistoryListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var HistoryListDisplay = new HistoryListDisplay();

$(document).ready(HistoryListDisplay.start.bind(HistoryListDisplay));

