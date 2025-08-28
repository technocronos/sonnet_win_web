
/**
 * 階級アップ結果を制御するシングルトンオブジェクト。
 *
 */
function BattleItemGetDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(BattleItemGetDisplay.prototype, 'constructor', {
        value : BattleItemGetDisplay,
        enumerable : false
    });

    this.defaultListId = "battle-itemget-list";
    this.defaultEntryId = "battle-itemget-template";

    this.list = Page.getParams("list");
    this.me = Page.getParams("battleitemget_me");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
BattleItemGetDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BattleItemGetDisplay.prototype.start = function() {
console.log("BattleItemGetDisplay.start rannning...");
    var self = BattleItemGetDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BattleItemGetDisplay.prototype.reload = function (){
    var self = BattleItemGetDisplay;

    $("#rorate_image").show();
    //回転
    AppUtil.rotate($("#rorate_image"), 2.5);

console.log(self.list["result"]["gain"]["uitem"]);

    // パケットをリストに表示。
    self.refreshList(self.list["result"]["gain"]["uitem"]);

    //OKボタンクリック時イベントハンドラ
    $("#popup-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        BattleResultDisplay.onLoaded();
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
BattleItemGetDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = BattleItemGetDisplay;

    //装備名
    $("[key='item_name']", board).text( entry["item_name"] );
    //装備アイコン
    $("[key='icon']", board).attr("src", AppUtil.getItemIconURL(entry["item_id"]) );

    $("[key='flavor_text']", board).text( entry["flavor_text"] );

    if(entry["category"] == "ITM"){
        $("[key='item_panel']", board).show();
        $("[key='equip_panel']", board).hide();

        $("[key='effect']", board).text( entry["effect"] );
    }else{
        $("[key='item_panel']", board).hide();
        $("[key='equip_panel']", board).show();

        $("[key='att1']", board).text( entry["attack1"] );
        $("[key='att2']", board).text( entry["attack2"] );
        $("[key='att3']", board).text( entry["attack3"] );
        $("[key='spd']", board).text( entry["speed"] );

        $("[key='def1']", board).text( entry["defence1"] );
        $("[key='def2']", board).text( entry["defence2"] );
        $("[key='def3']", board).text( entry["defence3"] );
        $("[key='defX']", board).text( entry["defenceX"] );
    }


    self.super.setupEntryBoard.call(self);
}
//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BattleItemGetDisplay.prototype.onLoaded = function() {
    var self = BattleItemGetDisplay;

    //ちょっと遅延させてから消す
    $(function(){
        $("#weapon_area").sparkleh();

        setTimeout(function(){
            sound("se_congrats");
            AppUtil.circle($("#circle_div"),750 / 2, 160);

            setTimeout(function(){
                AppUtil.circle($("#circle_div2"),750 / 2, 160);
            },40);
        },200);
    });

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BattleItemGetDisplay.prototype.destroy = function (){
    var self = BattleItemGetDisplay;

    self.me.close();
    self.super.destroy.call(self);
    BattleItemGetDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BattleItemGetDisplay = new BattleItemGetDisplay();

$(document).ready(BattleItemGetDisplay.start.bind(BattleItemGetDisplay));

