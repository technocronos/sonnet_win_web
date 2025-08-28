/**
 * 送金依頼リストを制御するシングルトンオブジェクト。
 *
 */
function BitcoinListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(BitcoinListDisplay.prototype, 'constructor', {
        value : BitcoinListDisplay,
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
BitcoinListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BitcoinListDisplay.prototype.start = function() {
console.log("BitcoinListDisplay.start rannning...");
    var self = BitcoinListDisplay;

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
BitcoinListDisplay.prototype.reload = function (){
    var self = BitcoinListDisplay;

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
    VcoinApi.list(function(list){
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


BitcoinListDisplay.prototype.getStatus = function(entry) {
    switch(entry["status"]){
      case VCOIN_STATUS_INITIAL:
        return "未処理"
        break;
      case VCOIN_STATUS_RECEIVE:
        return "処理中(" + entry["status_update_at"] + ")"
        break;
      case VCOIN_STATUS_CANCEL:
        return "キャンセル(" + entry["status_update_at"] + ")"
        break;
      case VCOIN_STATUS_COMPLETE:
        return "完了(" + entry["status_update_at"] + ")"
        break;
    }
}
//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
BitcoinListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = BitcoinListDisplay;

    $("[key='amount']", board).text( parseFloat(entry["amount"]) );
    $("[key='fee']", board).text( parseFloat(entry["fee"]) );
    $("[key='address']", board).text( entry["address"] );

    if(entry["transaction"] == "" || entry["transaction"] == null){
        $("[key='transaction']", board).hide();
    }else{
        $("[key='transaction']", board).val( entry["transaction"] );
    }

    $("[key='blockchain_link']", board).off("click").on("click", function(){
        window.open("https://live.blockcypher.com/btc/tx/" + entry["transaction"], '_blank');
    });

    $("[key='status']", board).text( self.getStatus(entry) );
    $("[key='create_at']", board).text( entry["create_at"] );

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BitcoinListDisplay.prototype.onLoaded = function() {
    var self = BitcoinListDisplay;

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
BitcoinListDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    BitcoinListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BitcoinListDisplay = new BitcoinListDisplay();

$(document).ready(BitcoinListDisplay.start.bind(BitcoinListDisplay));

