
/**
 * ガチャラインナップを制御するシングルトンオブジェクト。
 *
 */
function GachaLineupDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(GachaLineupDisplay.prototype, 'constructor', {
        value : GachaLineupDisplay,
        enumerable : false
    });

    this.defaultListId = "gacha-detail-list";
    this.defaultEntryId = "gacha-detail-template";

    this.gachaId = Page.getParams("gachaId");
    this.me = Page.getParams("me");

    this.list = {};
    this.scroll = undefined;

    this.response = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
GachaLineupDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
GachaLineupDisplay.prototype.start = function() {
console.log("GachaLineupDisplay.start rannning...");
    var self = GachaLineupDisplay;
    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
GachaLineupDisplay.prototype.reload = function (){
    var self = GachaLineupDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    GachaApi.list(self.gachaId, function(response){
        console.log(response);

        //タイトル設定
        $("#gacha_title").html(response["gacha"]["gacha_name"]);

        var list = response["list"];

        //okボタンクリック時イベントハンドラ
        $("#dialogue-close").off('click').on('click',function() {
            sound("se_btn");
            self.destroy()
            self.me.close();
        });

        // パケットをリストに表示。
        self.refreshList(list);

        self.super.reload.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
GachaLineupDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = GachaLineupDisplay;

    // エントリデータに合わせてセットアップ。
    if(entry["item"]["set"] != null){
        $("[key='setname']", board).text( entry["item"]["set"]["set_name"] );
        $("[key='reality']", board).attr("src",  AppUrl.asset("img/parts/sp/rare_icon_"+entry["item"]["rear_level"]+".png"));
    }else{

    }
    $("[key='name']", board).text( entry["item"]["item_name"] );
    $("[key='image']", board).attr( "src", WEB_ROOT + "/img/item/" + AppUtil.padZero(entry["item"]["item_id"], 5) + ".gif");
    $("[key='text']", board).text( entry["item"]["flavor_text"]);
    $("[key='rate']", board).text( "提供割合" + entry["rate"] + "%");
    $("[key='durability']", board).text( entry["item"]["durability"]);

    $("[key='att1']", board).text( entry["item"]["attack1"] );
    $("[key='att2']", board).text( entry["item"]["attack2"] );
    $("[key='att3']", board).text( entry["item"]["attack3"] );
    $("[key='spd']", board).text( entry["item"]["speed"] );

    $("[key='def1']", board).text( entry["item"]["defence1"] );
    $("[key='def2']", board).text( entry["item"]["defence2"] );
    $("[key='def3']", board).text( entry["item"]["defence3"] );
    $("[key='defX']", board).text( entry["item"]["defenceX"] );

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
GachaLineupDisplay.prototype.onLoaded = function() {
    var self = GachaLineupDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#GachaLineupContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: true
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
GachaLineupDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    GachaLineupDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var GachaLineupDisplay = new GachaLineupDisplay();

$(document).ready(GachaLineupDisplay.start.bind(GachaLineupDisplay));

