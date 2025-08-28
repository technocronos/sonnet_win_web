
/**
 * アイテム使用を制御するシングルトンオブジェクト。
 *
 */
function ItemUseDisplay(){

    this.entry = Page.getParams("item_use_entry");
    this.me = Page.getParams("item_use_d");

    this.mount = 1;
    this.base_equip = {};

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ItemUseDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ItemUseDisplay.prototype.start = function() {
console.log("ItemUseDisplay.start rannning...");
    var self = ItemUseDisplay;

    switch(self.entry.category){
        case "HED":
          self.mount = 3;
          break;
        case "BOD":
          self.mount = 2;
          break;
        case "WPN":
          self.mount = 1;
          break;
        case "ACS":
          self.mount = 4;
          break;
    }

    $.each(self.PLAEQP, function(key, value){
        if(key == self.mount)
            self.base_equip = value;
    });

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ItemUseDisplay.prototype.reload = function (){
    var self = ItemUseDisplay;

console.log(self.entry);

    //アイテム名
    $("#item_name").html(self.entry.item_name);

    $("#item_image").attr('src', AppUtil.getItemIconURL(self.entry["item_id"]));

    //フレーバーテキスト
    $("#flavor_text").html(self.entry["flavor_text"]);

    //ナビセリフ
    $("#navi_serif_sync").html(self.entry.item_name + "を使うのだ？");

    //okボタンクリック時イベントハンドラ
    $("#item-use-ok").off('click').on('click',function() {
        sound("se_btn");

        //アイテムを使う
        EquipApi.use(self.entry["user_item_id"], null, function(response){
            console.log(response);
            var text = "";
            if(response["result"] == "ok"){
                text = self.entry["item_name"] +  "を使ったのだ"

                switch(self.entry.item_type){
                    case ITEM_RECV_HP:
                    case ITEM_RECV_AP:
                    case ITEM_RECV_MP:
                        HomeApi.summary(null, null, null, null, function(summary){
                            console.log(summary);
                            //サマリを格納しておく
                            Page.setSummary(summary);

                            //ヘッダー更新
                            MainContentsDisplay.HeaderCanvas.init(summary);
                        });
                        break;
                }

                //MainContentsDisplay.HeaderCanvas.update("ap", response.gold);
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
    //キャンセルボタンクリック時イベントハンドラ
    $("#item-use-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
ItemUseDisplay.prototype.onLoaded = function() {
    var self = ItemUseDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ItemUseDisplay.prototype.destroy = function (){
    var self = ItemUseDisplay;

    self.me.close();
    self.super.destroy.call(self);
    ItemUseDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ItemUseDisplay = new ItemUseDisplay();

$(document).ready(ItemUseDisplay.start.bind(ItemUseDisplay));

