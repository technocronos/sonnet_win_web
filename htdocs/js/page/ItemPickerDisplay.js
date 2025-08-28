/**
 * ショップリストを制御するシングルトンオブジェクト。
 * 最初の数字は　マグナ = 1 コイン = 2
 * 次の数字は　アイテム = 1 装備   = 2
 *
 */
function ItemPickerDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ItemPickerDisplay.prototype, 'constructor', {
        value : ItemPickerDisplay,
        enumerable : false
    });

    this.defaultListId = "item-picker-list";
    this.defaultEntryId = "item-picker-template";

    this.entry = Page.getParams("item_picker_entry");
    this.me = Page.getParams("item_picker_d");

    this.equip_list = {};

    this.scroll = undefined;

    this.mount = 1;
    this.category = 'WPN';

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ItemPickerDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ItemPickerDisplay.prototype.start = function() {
console.log("ItemPickerDisplay.start rannning...");
    var self = ItemPickerDisplay;

    //タブの選択
    $("#tabp-" + self.category).css("display", "block");

    $("#window_bg").html(Page.preload_image.itempicker_window);

    //装備一覧を取得。
    EquipApi.list(function(list){
        console.log(list);
        self.equip_list = list;
        self.super.start.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ItemPickerDisplay.prototype.reload = function (){
    var self = ItemPickerDisplay;

    $("#navi_serif_picker").html(self.entry.item_name + "をどの装備に使うのだ？");

    $("#use_item_image").attr("src", AppUrl.asset("img/item/" + AppUtil.padZero(self.entry["item_id"], 5) + ".gif") );

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //現在の装備を初期化
    self.player_equip = {};

    switch(self.category){
        case "HED":
          self.mount = 3;
          mount_str = "頭";
          break;
        case "BOD":
          self.mount = 2;
          mount_str = "服";
          break;
        case "WPN":
          self.mount = 1;
          mount_str = "武器";
          break;
        case "ACS":
          self.mount = 4;
          mount_str = "アクセサリ";
          break;
        case "ITM":
          self.mount = 5;
          mount_str = "アイテム";
          break;
    }

    $.each(self.equip_list['equip'], function(key, value){
        if(key == self.mount)
            self.list = value;
    });

    // 一つもなかったら...
    if(self.list.length == 0) {
        // その旨のパネルを表示。
        var no = Juggler.generate("no-entry");
        $("#" + self.defaultListId).append(no);
        //スクロールは消す
        self.scroll.refresh();

        // 処理はここまで。
        return;
    }

    //装備中の装備をとっておく
    $.each(self.equip_list["PLAEQP"], function(key, value){
        if(key == self.mount)
            self.player_equip = value;
    });

    // パケットをリストに表示。
    self.refreshList(self.list);
    self.super.reload.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
ItemPickerDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = ItemPickerDisplay;

    //---------------------------------------------------------------------------------------------------------
    /*
     * 各種情報を画面に表示
     *
    */
    if(self.category == "ITM"){
        $("#item_template").show();
        $("#equip_template").hide();
    }else{
        $("#item_template").hide();
        $("#equip_template").show();
    }

    $("[key='item_name']", board).text( entry["item_name"] );
    $("[key='set_name']", board).text( entry["set_name"] );

    //装備中の場合
    if(self.player_equip["user_item_id"] == entry["user_item_id"]){
        $("[key='item_name']", board).text( entry["item_name"] + "(装備中)");
    }


    $("[key='durable_count']", board).text( entry["durable_count"] );

    $("[key='hold']", board).text( entry["num"] );

    if(entry["level"] >= entry["max_level"])
        $("[key='level']", board).text( entry["level"] + "[MAX]" );
    else
        $("[key='level']", board).text( entry["level"] );

    $("[key='flavor_text']", board).text( entry["flavor_text"] );
    $("[key='effect']", board).text( entry["effect"] );

    $("[key='att1']", board).text( entry["attack1"] );
    $("[key='att2']", board).text( entry["attack2"] );
    $("[key='att3']", board).text( entry["attack3"] );
    $("[key='spd']", board).text( entry["speed"] );

    $("[key='def1']", board).text( entry["defence1"] );
    $("[key='def2']", board).text( entry["defence2"] );
    $("[key='def3']", board).text( entry["defence3"] );
    $("[key='defX']", board).text( entry["defenceX"] );

    $("[key='image']", board).attr("src", AppUtil.getItemIconURL(entry["item_id"]) );

    $("[key='rare']", board).attr("src", AppUrl.asset("img/parts/sp/rare_icon_"+entry.rear_level+".png"));

    //---------------------------------------------------------------------------------------------------------
    /*
     * 各種ボタンイベントハンドラ設定
     *
    */

    //---------------------------------------------------------------------------------------------------------
    //使用ボタン押下時イベントハンドラ
    $("[key='use_button']", board).off('click').on('click',function() {
        sound("se_btn");

        //アイテムを使う
        EquipApi.use(self.entry["user_item_id"], entry["user_item_id"], function(response){
            console.log(response);
            var text = "";
            if(response["result"] == "ok"){
                text = self.entry["item_name"] +  "を使ったのだ"
            }else{
                text = response["err_msg"];
            }

            var d = new Dialogue();

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(PopupHtml);

            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();

            $("#popup_body").html(text);

            $("#popup-close").off('click').on('click',function() {
                sound("se_btn");
                d.close();
                EquipListDisplay.restart();
            });
            self.destroy();
        });
    });

    //閉じるボタンクリック時イベントハンドラ
    $("#item-picker-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
ItemPickerDisplay.prototype.onLoaded = function() {
    var self = ItemPickerDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#ItemPickerContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * タブ切り替え時に呼び出される。
 */
ItemPickerDisplay.prototype.onChangeCategory = function(category) {
    var self = ItemPickerDisplay;

    if(self.category == category)
        return false;

    sound("se_btn");

    //前のタブの選択を削除
    $("#tabp-" + self.category).css("display", "none");
    self.category = category;
    //新しいタブの選択
    $("#tabp-" + self.category).css("display", "block");

    self.reload();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ItemPickerDisplay.prototype.destroy = function (){
    var self = ItemPickerDisplay;

    self.me.close();
    self.super.destroy.call(self);
    ItemPickerDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ItemPickerDisplay = new ItemPickerDisplay();

$(document).ready(ItemPickerDisplay.start.bind(ItemPickerDisplay));

