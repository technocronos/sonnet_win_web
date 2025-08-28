
/**
 * ショップ確認を制御するシングルトンオブジェクト。
 *
 */
function ShopConfirmDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ShopConfirmDisplay.prototype, 'constructor', {
        value : ShopConfirmDisplay,
        enumerable : false
    });

    this.currency = Page.getParams("currency");
    this.entry = Page.getParams("shop_entry");
    this.me = Page.getParams("shopconfirm_d");

    this.buy_count = 1;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ShopConfirmDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ShopConfirmDisplay.prototype.start = function() {
console.log("ShopConfirmDisplay.start rannning...");
    var self = ShopConfirmDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ShopConfirmDisplay.prototype.reload = function (){
    var self = ShopConfirmDisplay;

console.log(self.entry);

    switch(self.entry.category){
        case "ITM":
            mount = "消費アイテム";
            break;
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

    $("#item_category").html(mount);
    //アイテム名
    $("#item_name").html(self.entry.item_name);

    $("#item_image").attr('src', AppUtil.getItemIconURL(self.entry["item_id"]));

    if(parseInt(self.entry["sale"]) > 0)
        $("#sale_image").attr('src', AppUrl.asset("img/parts/sp/saleicon_"+self.entry["sale"]+".png"));

    //フレーバーテキスト
    $("#flavor_text").html(self.entry.flavor_text);

    if(self.entry.category != "ITM"){
        $("#weapon_status").show();
        $("#item_status").hide();

        $("#att1").text( self.entry["attack1"] );
        $("#att2").text( self.entry["attack2"] );
        $("#att3").text( self.entry["attack3"] );
        $("#spd").text( self.entry["speed"] );

        $("#def1").text( self.entry["defence1"] );
        $("#def2").text( self.entry["defence2"] );
        $("#def3").text( self.entry["defence3"] );
        $("#defX").text( self.entry["defenceX"] );
    }else{
        $("#item_status").show();
        $("#weapon_status").hide();
        $("#effect").text( self.entry["effect"] );
    }

    //通貨
    if(self.currency == "gold"){
        $("#navi_conf").attr("src", AppUrl.asset("img/parts/sp/navi.png"));
        $("#currency_icon").attr("src", AppUrl.asset("img/parts/sp/gold_icon.png"));
        $("#currency_icon2").attr("src", AppUrl.asset("img/parts/sp/gold_icon.png"));
        $("#currency_icon3").attr("src", AppUrl.asset("img/parts/sp/gold_icon.png"));
        $("#has_currency_text").html( "所持<br>マグナ" );

        $("#navi_serif_conf").html( "個数を決めて購入を押すのだ" );
        $("#currency").html( "マグナ" );
        $("#currency2").html( "マグナ" );

        //所持マグナ
        $("#gold").html(chara_gold);

        $("#shop-confirm-buy").css("left", "197px");
        $("#shop-confirm-close").css("left", "378px");
        $("#btn_coin_area").hide();

    }else{
        $("#navi_conf").attr("src", AppUrl.asset("img/parts/sp/navi2.png"))
        $("#currency_icon").attr("src", AppUrl.asset("img/parts/sp/coin_icon.png"));
        $("#currency_icon2").attr("src", AppUrl.asset("img/parts/sp/coin_icon.png"));
        $("#currency_icon3").attr("src", AppUrl.asset("img/parts/sp/coin_icon.png"));
        $("#has_currency_text").html( "所持<br>コイン" );

        $("#navi_serif_conf").html( "個数を決めて購入を押すのじゃ。ガッといけガッと" );
        $("#currency").html( "コイン" );
        $("#currency2").html( "コイン" );

        //所持コイン
        $("#gold").html(Page.getSummary().coin);

        //ボタン位置を調整
        if(PLATFORM_TYPE == "nati"){
            $("#shop-confirm-buy").css("left", "75px");
            $("#shop-confirm-close").css("left", "275px");
        }

        $("#btn_coin_area").show();
    }

    //値段
    $("#price").html(self.entry.price);

    //合計値段
    $("#total_price").html(self.entry.price);

    //↑ボタンクリック時イベントハンドラ
    $("#btn_up").off('click').on('click',function() {
        if(self.buy_count < 10){
            sound("se_btn");
            self.buy_count++;
            $("#buy_count").html(self.buy_count);
            $("#total_price").html(self.buy_count * self.entry.price);

            if(self.buy_count == 10)
                $("#btn_up").attr("src", AppUrl.asset("img/parts/sp/more_btn_down_disable.png"));
            if(self.buy_count > 1)
                $("#btn_down").attr("src", AppUrl.asset("img/parts/sp/more_btn_down.png"));
        }
    });
    //↓ボタンクリック時イベントハンドラ
    $("#btn_down").off('click').on('click',function() {
        if(self.buy_count > 1){
            sound("se_btn");
            self.buy_count--;
            $("#buy_count").html(self.buy_count);
            $("#total_price").html(self.buy_count * self.entry.price);

            if(self.buy_count == 1)
                $("#btn_down").attr("src", AppUrl.asset("img/parts/sp/more_btn_down_disable.png"));
            if(self.buy_count < 10)
                $("#btn_up").attr("src", AppUrl.asset("img/parts/sp/more_btn_up.png"));
        }
    });

    //購入ボタンクリック時イベントハンドラ
    $("#shop-confirm-buy").off('click').on('click',function() {
        sound("se_btn");
        var text = self.entry.item_name + "を"+self.buy_count+"個買うのだ？";

        self.showConfirm(text, function(){
            sound("se_btn");
            self.buy_item(self.entry, self.currency, self.buy_count);
            PopupConfirmDisplay.destroy();
        });
    });
    //キャンセルボタンクリック時イベントハンドラ
    $("#shop-confirm-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });
    //コイン購入ボタンクリック時イベントハンドラ
    $("#btn_coin").off("click").on("click", function(){
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

    //チュートリアル中の場合
    if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_SHOPPING)){
        //個数選択ボタン非活性
        $("#btn_up").attr("src", AppUrl.asset("img/parts/sp/more_btn_up_disable.png"));
        $("#btn_up").off('click');

        //購入キャンセルボタン非活性
        AppUtil.disableButton($("#shop-confirm-close"), "174_74");
        $("#shop-confirm-close").off('click');

        //セリフチュートリアル用に書き換え
        $("#navi_serif_conf").html( "個数を決めて購入を押すのだ<br>マグナ無いからとりあえず1個買うのだ" );

        //ナビカーソルを表示する
        AppUtil.showArrow($("#ShopConfirmContents"), "down", 600, 250);
    }

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
ShopConfirmDisplay.prototype.onLoaded = function() {
    var self = ShopConfirmDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * ショップAPIを呼び出す。課金の場合は制御は帰ってこない。
 *
*/
ShopConfirmDisplay.prototype.buy_item = function(item, currency, count) {
    var self = ShopConfirmDisplay;

    ShopApi.buy(item.item_id, item.category, currency, count, function(response){
        console.log(response);

        if(response.result == "ok"){
            Page.setParams("buy_user_item_id", response.buy_user_item_id);
            Page.setParams("buy_currency", currency);

            if(currency == "gold")
                Page.setParams("buy_gold", response.gold);
            else
                Page.setParams("buy_gold", response.coin);

        }else if(response.result == "no_gold"){
            self.showMessage("マグナが足りないのだ・・", null);
        }else if(response.result == "no_coin"){
            self.showMessage("コインが足りないのだ・・", null);
        }

        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END))
            AppUtil.removeArrow();

        self.destroy();
        ShopListDisplay.reload();
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ShopConfirmDisplay.prototype.destroy = function (){
    var self = ShopConfirmDisplay;

    self.me.close();
    self.super.destroy.call(self);
    ShopConfirmDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ShopConfirmDisplay = new ShopConfirmDisplay();

$(document).ready(ShopConfirmDisplay.start.bind(ShopConfirmDisplay));

