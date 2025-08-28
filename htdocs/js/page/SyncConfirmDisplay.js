
/**
 * ショップ確認を制御するシングルトンオブジェクト。
 *
 */
function SyncConfirmDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(SyncConfirmDisplay.prototype, 'constructor', {
        value : SyncConfirmDisplay,
        enumerable : false
    });

    this.PLAEQP = Page.getParams("PLAEQP");
    this.entry = Page.getParams("sync_entry");
    this.me = Page.getParams("syncconfirm_d");

    this.mount = 1;
    this.base_equip = {};

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
SyncConfirmDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
SyncConfirmDisplay.prototype.start = function() {
console.log("SyncConfirmDisplay.start rannning...");
    var self = SyncConfirmDisplay;

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
SyncConfirmDisplay.prototype.reload = function (){
    var self = SyncConfirmDisplay;

console.log(self.entry);

    //装備合成に必要な値段を取得。
    EquipApi.getPrice(self.base_equip["user_item_id"], self.entry["user_item_id"], function(response){
        console.log(response);
        //必要マグナ
        $("#need_gold").html(response["price"]);

        //アイテム名
        $("#item_name").html(self.entry.item_name + " " + "Lv" + self.entry.level);

        $("#item_image").attr('src', AppUtil.getItemIconURL(self.entry["item_id"]) );

        //所持マグナ
        $("#gold").html(chara_gold);

        //ナビセリフ
        $("#navi_serif_sync").html("今の装備に" + self.entry.item_name + "を合成するのだ？");

        //OKボタンクリック時イベントハンドラ
        $("#sync-confirm-ok").off('click').on('click',function() {
            sound("se_btn");

            EquipApi.change(null, null, self.entry["user_item_id"], self.mount, function(response){
                console.log(response);
                var text = "";
                if(response["result"] == "ok"){
                    var d = new Dialogue();

                    Page.setParams("sync_response", response);

                    Page.setParams("sync_base_entry", self.base_equip);
                    Page.setParams("sync_source_entry", self.entry);

                    Page.setParams("syncresult_d", d);

                    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
                    d.content(SyncResultHtml);

                    d.autoClose = false;
                    d.veilClose = false;
                    d.opacity = 0.5;

                    d.show();
                }else{

                    switch(response["result"]){
                        case "noitem":
                            text = "アイテムがないのだ";
                            break;
                        case "equipping":
                            text = "装備中なのだ";
                            break;
                        case "maxlevel":
                            text = "レベルがこれ以上上がらないのだ";
                            break;
                        case "nomoney":
                            text = "マグナがないのだ。地に足をつけるのだ・・。";
                            break;
                        case "in_quest":
                            text = "クエスト中は装備の変更はできないのだ。";
                            break;
                        case "not_me":
                            text = "誰の装備を変更しようとしてるのだ・・？";
                            break;
                    }

                    var d = new Dialogue();

                    Page.setParams("pop_d", d);

                    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
                    d.content(PopupHtml);

                    d.autoClose = false;
                    d.veilClose = false;
                    d.opacity = 0.5;

                    d.show();

                    $("#popup_body").html(text);

                    $("#popup-close").off('click').on('click',function() {
                        sound("se_btn");
                        PopupDisplay.destroy();
                    });
                }

                self.destroy();
            });
        });
        //キャンセルボタンクリック時イベントハンドラ
        $("#sync-confirm-close").off('click').on('click',function() {
            sound("se_btn");
            self.destroy();
        });

    });


    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
SyncConfirmDisplay.prototype.onLoaded = function() {
    var self = SyncConfirmDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * ショップAPIを呼び出す。課金の場合は制御は帰ってこない。
 *
*/
SyncConfirmDisplay.prototype.sync = function(item, currency, count) {
    var self = SyncConfirmDisplay;

    ShopApi.buy(item.item_id, item.category, currency, count, function(response){
        console.log(response);
        Page.setParams("buy_user_item_id", response.buy_user_item_id);
        Page.setParams("buy_gold", response.gold);
        Page.setParams("buy_currency", currency);
        self.destroy();
        ShopListDisplay.reload();
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
SyncConfirmDisplay.prototype.destroy = function (){
    var self = SyncConfirmDisplay;

    self.me.close();
    self.super.destroy.call(self);
    SyncConfirmDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var SyncConfirmDisplay = new SyncConfirmDisplay();

$(document).ready(SyncConfirmDisplay.start.bind(SyncConfirmDisplay));

