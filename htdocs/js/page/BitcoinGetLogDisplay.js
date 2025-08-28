/**
 * 取得ログを制御するシングルトンオブジェクト。
 *
 */
function BitcoinGetLogDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(BitcoinGetLogDisplay.prototype, 'constructor', {
        value : BitcoinGetLogDisplay,
        enumerable : false
    });

    this.defaultListId = "bitcoin-list";
    this.defaultEntryId = "bitcoin-list-template";

    this.me = Page.getParams("me");

    this.list = {};
    this.scroll = undefined;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
BitcoinGetLogDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BitcoinGetLogDisplay.prototype.start = function() {
console.log("BitcoinGetLogDisplay.start rannning...");
    var self = BitcoinGetLogDisplay;

    var content_height = 1100;

    if(is_tablet == "tablet"){
        $("#bitcoinlistdiv").css("transform", "scale(0.9)");
        $("#bitcoinlistdiv").css("transform-origin", "50% 50%");
    }else{
        $("#bitcoinlistdiv").css("top" , screen.height + "px");
        $("#bitcoinlistdiv").css("margin-top", "-" + (content_height * ($(window).width() / 750)) + "px");
    }

    $("#bg_image_list").html(Page.preload_image.help_detail_bg);

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BitcoinGetLogDisplay.prototype.reload = function (){
    var self = BitcoinGetLogDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //閉じるボタンクリック時イベントハンドラ
    $("#list-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    //タブを作成する。
    VcoinApi.log(function(list){
        console.log(list);

        self.list = list;

        // 一つもなかったら...
        if(self.list.length == 0) {
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
BitcoinGetLogDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = BitcoinGetLogDisplay;

    $("[key='amount']", board).text( parseFloat(entry["amount"]) );
    $("[key='name']", board).text( entry["name"] );
    $("[key='reason']", board).text( entry["reason"] );
    $("[key='update_at']", board).text( entry["update_at"] );

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BitcoinGetLogDisplay.prototype.onLoaded = function() {
    var self = BitcoinGetLogDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#BitcoinListContents .scrollWrapper', {
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
BitcoinGetLogDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    BitcoinGetLogDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BitcoinGetLogDisplay = new BitcoinGetLogDisplay();

$(document).ready(BitcoinGetLogDisplay.start.bind(BitcoinGetLogDisplay));

