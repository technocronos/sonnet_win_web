/**
 * メッセージリストを制御するシングルトンオブジェクト。
 *
 */
function SphereItemListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(SphereItemListDisplay.prototype, 'constructor', {
        value : SphereItemListDisplay,
        enumerable : false
    });

    this.defaultListId = "item-list";
    this.defaultEntryId = "item-list-template";

    this.me = Page.getParams("me");
    this.get_param = Page.getParams("get_param");

    this.list = {};
    this.scroll = undefined;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
SphereItemListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
SphereItemListDisplay.prototype.start = function() {
console.log("SphereItemListDisplay.start rannning...");
    var self = SphereItemListDisplay;

    self.super.start.call(self);

    //ローディングは出さない
    $("#mini_loading").hide();

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
SphereItemListDisplay.prototype.reload = function (){
    var self = SphereItemListDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //okボタンクリック時イベントハンドラ
    $("#item-list-close").off('click').on('click',function() {
        sound("se_btn");

        pex.getAPI().gotoFrame("/user", "itemcancel");

        self.destroy();
        self.me.close();
    });

    //リストを作成する。
    SphereApi.itemlist(self.get_param, function(response){
        console.log(response);

        self.list = response;

        // 一つもなかったら...
        if(self.list.count().length == 0) {
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
        self.refreshList(self.list);

        self.super.reload.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
SphereItemListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = SphereItemListDisplay;

    $("[key='name']", board).text( entry["item_name"] );
    $("[key='image']", board).attr("src", AppUtil.getItemIconURL(entry["item_id"]) );;

    if(entry["useable"] == true){
        $("[key='button']", board).show();

        if(entry["category"] != "ITM")
            $("[key='button_caption']", board).html("装備");

        $("[key='button']", board).off('click').on('click',function() {
            sound("se_btn");

            pex.getAPI().setVariable("/user", "page", 1);
            pex.getAPI().setVariable("/user", "slot", entry["slot"]);

            pex.getAPI().setVariable("/user", "itemNo", entry["item_no"]);
            pex.getAPI().gotoFrame("/user", "itemselect");

            setTimeout(function(){
                self.destroy();
                self.me.close();
            },200);
        });
    }else{
        $("[key='button_caption']", board).html("装備中");

        AppUtil.disableButton($("[key='button']", board),"174_74");
    }

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
SphereItemListDisplay.prototype.onLoaded = function() {
    var self = SphereItemListDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#SphereItemListContents .scrollWrapper', {
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
SphereItemListDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    SphereItemListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var SphereItemListDisplay = new SphereItemListDisplay();

$(document).ready(SphereItemListDisplay.start.bind(SphereItemListDisplay));

