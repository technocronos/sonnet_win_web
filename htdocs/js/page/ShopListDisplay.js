/**
 * ショップリストを制御するシングルトンオブジェクト。
 * 最初の数字は　マグナ = 1 コイン = 2
 * 次の数字は　アイテム = 1 装備   = 2
 *
 */
function ShopListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ShopListDisplay.prototype, 'constructor', {
        value : ShopListDisplay,
        enumerable : false
    });

    //スクロール無効
    $("#ShopListContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.defaultListId = "shop-list";
    this.defaultEntryId = "shop-list-template";

    this.list = {};
    this.scroll = undefined;

    this.ClassName = "ShopListDisplay";

    this.currency = Page.getParams("shop_currency");
    this.category = Page.getParams("shop_category");

    //gold マグナ coin コイン
    if(this.currency == undefined)
        this.currency = 'gold';
    if(this.category == undefined)
        this.category = 'ITM';

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ShopListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ShopListDisplay.prototype.start = function() {
console.log("ShopListDisplay.start rannning...");
    var self = ShopListDisplay;

    //タブの選択
    $("#tab1-" + self.currency).attr("src", AppUrl.asset("img/parts/sp/btn_buy_"+self.currency+"_selected.png"));
    $("#tab2-" + self.category).css("display", "block");

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ShopListDisplay.prototype.reload = function (){
    var self = ShopListDisplay;

    //結果表示がある場合
    buy_user_item_id = Page.getParams("buy_user_item_id");
    if(buy_user_item_id != null){
        var d = new Dialogue();

        Page.setParams("shopresult_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(ShopResultHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();

        return;
    }

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //コイン購入ボタンクリック時イベントハンドラ
    $("#btn_coin_list").off("click").on("click", function(){
        sound("se_btn");
        try {
            if (typeof(Unity) === 'undefined') {
                Unity = {
                    call: function(msg) {
                        var iframe = document.createElement('IFRAME');
                        iframe.setAttribute('src', 'unity:' + msg);
                        document.documentElement.appendChild(iframe);
                        document.documentElement.removeChild(iframe);
                    }
                };
            }

            Unity.call('shop');
        }
        catch (e) {
            alert(e);
        }
    });

    //タブを作成する。
    ShopApi.list(self.category, self.currency, function(list){
        console.log(list);

        self.list = list;

        // 一つもなかったら...
        if(self.list.Num == 0) {
            $("#next_lv").html( "");

            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);
            //スクロールは消す
            if(self.scroll)
                self.scroll.refresh();

            self.onLoaded();

            // 処理はここまで。
            return;
        }

        //チュートリアル中でない場合
        if(parseInt(Page.getSummary().tutorial_step) >= parseInt(TUTORIAL_END)){
            if(self.list.next !== null) 
                $("#next_lv").html( "次のアイテム解放LV：lv" +  self.list.next.unlock_level);
        }else{
                $("#next_lv").hide();
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
ShopListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = ShopListDisplay;

    if(self.category == "ITM"){
        $("[key='item_template']", board).show();
        $("[key='equip_template']", board).hide();
        $("[key='item_name']", board).text( entry["item_name"] );
    }else{
        $("[key='item_template']", board).hide();
        $("[key='equip_template']", board).show();

        switch(entry["category"]){
            case "HED":
              mount = "頭";
              break;
            case "BOD":
              mount = "体";
              break;
            case "WPN":
              mount = "武器";
              break;
            case "ACS":
              mount = "アクセサリ";
              break;
        }

        $("[key='item_name']", board).text( mount + "：" + entry["item_name"] + "("+entry["set_name"]+")");
         
    }

    $("[key='hold']", board).text( entry["hold"] );
    $("[key='price']", board).text( entry["price"] );

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

    if(parseInt(entry["sale"]) > 0)
        $("[key='sale']", board).attr("src", AppUrl.asset("img/parts/sp/saleicon_"+entry["sale"]+".png") );


    if(self.currency == "gold"){
        $("#navi").attr("src", AppUrl.asset("img/parts/sp/navi.png"))
        $("#navi_serif").html( "ここはショップなのだ。買い物をするのだ。" );
        $("[key='currency']", board).text( "マグナ" );
    }else{
        $("#navi").attr("src", AppUrl.asset("img/parts/sp/navi2.png"))
        $("#navi_serif").html( "コインのウルトラパワーで物が買えるのじゃ。安いぞ!買ってけ" );
        $("[key='currency']", board).text( "コイン" );
    }

    $("[key='currency_icon']", board).attr("src", WEB_ROOT + "img/parts/sp/"+self.currency+"_icon.png");

    $("[key='button']", board).off('click').on('click',function() {
        sound("se_btn");

        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_SHOPPING))
            AppUtil.removeArrow();

        var d = new Dialogue();

        Page.setParams("currency", self.currency);
        Page.setParams("shop_entry", entry);
        Page.setParams("shopconfirm_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(ShopConfirmHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();
    });

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
ShopListDisplay.prototype.onLoaded = function() {
    var self = ShopListDisplay;

    //チュートリアル中の場合
    if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_SHOPPING)){
        $("#tab-category-selected").hide();
        $("#tab-category-bg").hide();
        $("#tab-currency").hide();
        $("#navi_serif").html( Page.getSummary().opening.join('<br>') );
        //ナビカーソルを表示する
        AppUtil.showArrow($("#ShopListContents"), "down", 420, 70);
    }

    $("#main_contents").fadeIn("fast", function(){
        //記事のスクロール表示
        if(self.scroll){
            self.scroll.refresh();
        }else{
            self.scroll = new IScroll('#ShopListContents .scrollWrapper', {
                click: true,
                scrollbars: 'custom', /* スクロールバーを表示 */
                //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
                //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
                //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
                mouseWheel: false
            });
        }
        self.super.onLoaded.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 通貨タブ切り替え時に呼び出される。
 */
ShopListDisplay.prototype.onChangeCurrency = function(currency) {
    var self = ShopListDisplay;

    if(self.currency == currency)
        return false;

    //チュートリアル中の場合は無視
    if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END))
        return false;

    sound("se_btn");

    //前のタブの選択を削除
    $("#tab1-" + self.currency).attr("src", WEB_ROOT + "img/parts/sp/btn_buy_"+self.currency+".png");
    self.currency = currency;
    //新しいタブの選択
    $("#tab1-" + self.currency).attr("src", WEB_ROOT + "img/parts/sp/btn_buy_"+self.currency+"_selected.png");

    self.reload();
}

//---------------------------------------------------------------------------------------------------------
/**
 * カテゴリタブ切り替え時に呼び出される。
 */
ShopListDisplay.prototype.onChangeCategory = function(category) {
    var self = ShopListDisplay;

    if(self.category == category)
        return false;

    //チュートリアル中の場合は無視
    if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END))
        return false;

    sound("se_btn");

    //前のタブの選択を削除
    $("#tab2-" + self.category).css("display", "none");
    self.category = category;
    //新しいタブの選択
    $("#tab2-" + self.category).css("display", "block");

    self.reload();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ShopListDisplay.prototype.destroy = function (){
    var self = ShopListDisplay;

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();
        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var ShopListDisplay = new ShopListDisplay();

$(document).ready(ShopListDisplay.start.bind(ShopListDisplay));

