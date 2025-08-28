
/**
 * ガチャ詳細を制御するシングルトンオブジェクト。
 *
 */
function GachaDetailDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(GachaDetailDisplay.prototype, 'constructor', {
        value : GachaDetailDisplay,
        enumerable : false
    });
    //スクロール無効
    $("#GachaDetailContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.entry = Page.getParams("gacha_entry");
    this.freeGacha = Page.getParams("freeGacha");
    this.ticketCount = Page.getParams("ticketCount");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
GachaDetailDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
GachaDetailDisplay.prototype.start = function() {
console.log("GachaDetailDisplay.start rannning...");
    var self = GachaDetailDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
GachaDetailDisplay.prototype.reload = function (){
    var self = GachaDetailDisplay;

    $("#navi_serif").html(self.entry.flavor_text);
    $("#btn_free").hide();

    if(self.entry.sp_flg == 1){
        $("#summary").html(self.entry.caption);
    }

    $("#ticketCount").html(self.ticketCount);
    $("#gacha_image").attr("src", AppUrl.asset("img/gacha/" + AppUtil.padZero(self.entry.gacha_id, 5) + "_b.png"));


    $("#btn_back").off("click").on("click", function(){
        sound("se_btn");
        MainContentsDisplay.FooterCanvas.out("gacha","gacha_detail");
    });

    $("#btn_lineup").off("click").on("click", function(){
        sound("se_btn");
        self.showGachaLineUp(self.entry.gacha_id);
    });

    var kind = "";

    //硬貨の種別を設定する
    if(self.entry.gacha_kind == 1){
        kind = "gold";

        //マグナガチャは特定商取引法に基づく表記無し
        $("#btn_syoutori").hide();

        //マグナガチャは詳細無し
        $("#btn_lineup").hide();
        //1連アイコン設定
        $("#currency_1").attr("src", AppUrl.asset("img/parts/sp/gold_icon.png"));
        $("#price_1").html(self.entry.price);
        $("#currencytxt_1").html("マグナ");

        //11連アイコン設定
        $("#currency_11").attr("src", AppUrl.asset("img/parts/sp/gold_icon.png"));
        $("#price_11").html(self.entry.price_bulk);
        $("#currencytxt_11").html("マグナ");

        //ガチャチケット非表示
        $("#gacha_ticket").hide();
        $("#ticket_panel").hide();
        //ボタン位置調整
        $("#gacha_1").css("left", "100px");
        $("#gacha_11").css("left",  "377px");

    }else if(self.entry.gacha_kind == 2){
        kind = "charge";

        //チュートリアルガチャは詳細無し
        if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_GACHA))
            $("#btn_lineup").hide();


        //マグナガチャは特定商取引法に基づく表記無し
        $("#btn_syoutori").show();

        //1連アイコン設定
        $("#currency_1").attr("src", AppUrl.asset("img/parts/sp/coin_icon.png"));        
        $("#price_1").html(self.entry.price);
        $("#currencytxt_1").html("コイン");

        //11連アイコン設定
        $("#currency_11").attr("src", AppUrl.asset("img/parts/sp/coin_icon.png"));        
        $("#price_11").html(self.entry.price_bulk);
        $("#currencytxt_11").html("コイン");

        //雑貨ガチャは11連無し
        if(self.entry.gacha_id == 9998){
            //ボタン位置調整
            $("#gacha_1").css("left", "100px");
            $("#gacha_11").hide();
            $("#gacha_ticket").css("left",  "377px");
        }

        $("#gacha_ticket").off("click").on("click", function(){
            sound("se_btn");
            self.draw("ticket", 1);
        });
        if(PLATFORM_TYPE == "nati"){
            $("#btn_coin").show();
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

            $("#coin_panel").show();
            $("#CoinCount").html(Page.getSummary().coin);
        }

        if(parseInt(self.ticketCount) < parseInt(self.entry.freeticket_count)){
            $("#ticket_btn_image").attr("src", AppUrl.asset("img/parts/sp/gacha_ticket_disable.png"));
            $("#gacha_ticket").off("click");
        }

        //チケット個数設定
        $("#price_ticket").html(self.entry.freeticket_count);

        //ガチャチケット表示
        $("#gacha_ticket").show();
        $("#ticket_panel").show();

    }else{
        var kind = "free";
        var count = 1;

        $("#navi_serif").html("このガチャは一日一回タダで回せるのじゃ");

        $("#btn_free").show();
        $("#btn_no_free").hide();

        $("#ticket_panel").hide();

        $("#btn_free").off("click").on("click", function(){
            sound("se_btn");
            sound_stop();
            GachaApi.play(self.entry.gacha_id, kind, count,  function(response){
                console.log(response);
            });
        });
    }

    $("#gacha_1").off("click").on("click", function(){
        sound("se_btn");
        self.draw(kind, 1);
    });

    $("#gacha_11").off("click").on("click", function(){
        sound("se_btn");
        self.draw(kind, 11);
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * ガチャを回す。
 * @param kind マグナ:gold  課金:charge チケット:ticket
 * @param count 回す回数。
 *
*/
GachaDetailDisplay.prototype.draw = function(kind, count) {
    var self = GachaDetailDisplay;

    //マグナガチャ
    if(kind == "gold"){
        if(count == 11)
            price = self.entry.price_bulk;
        else
            price = self.entry.price;

        if(price > Page.getSummary().gold){
            self.showMessage("マグナが足りないのだ・・");
            return;
        }
    }else if(kind == "ticket"){
        if (parseInt(self.entry.freeticket_count) > parseInt(self.ticketCount)){
            self.showMessage("チケットが足りないのだ・・");
            return;
        }
    }else if(kind == "charge"){
        if(PLATFORM_TYPE == "nati"){
            if(count == 11)
                price = self.entry.price_bulk;
            else
                price = self.entry.price;

            if(price > Page.getSummary().coin){
                self.showMessage("コインが足りないのだ・・");
                return;
            }
        }
    }

    var text = self.entry.gacha_name + "を引くのだ？";

    self.showConfirm(text, function(){
        sound("se_btn");
        sound_stop();
        GachaApi.play(self.entry.gacha_id, kind, count,  function(response){
            console.log(response);
        });
    });
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
GachaDetailDisplay.prototype.onLoaded = function() {
    var self = GachaDetailDisplay;

    if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_GACHA)){
        $("#btn_no_free").hide();

        $("#gacha_1").off("click");
        $("#gacha_11").off("click");
        $("#gacha_ticket").off("click");

        $("#navi_serif").html("ここがガチャの部屋じゃ<br>今回は特別にタダじゃぞい。りせまら、とかいうことをしてる暇があるなら修行するがよい！");

        $("#btn_free").show();
        $("#btn_free").off("click").on("click", function(){
            sound("se_btn");
            var kind = "free";
            sound_stop();
            GachaApi.play(self.entry.gacha_id, kind, 1, function(response){
                console.log(response);
            });
        });
    }

    $("#main_contents").fadeIn("fast", function(){
        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_GACHA))
            //ナビカーソルを表示する
            AppUtil.showArrow($("#GachaDetailContents"), "down", 670, 340);

        self.super.onLoaded.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * ガチャリストポップアップを呼び出す
 */
GachaDetailDisplay.prototype.showGachaLineUp = function(gachaId) {
    var d = new Dialogue();

    Page.setParams("gachaId", gachaId);
    Page.setParams("me", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(GachaLineupHtml);

    d.autoClose = false;
    d.veilClose = false;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
GachaDetailDisplay.prototype.destroy = function (){
    var self = GachaDetailDisplay;

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();
        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var GachaDetailDisplay = new GachaDetailDisplay();

$(document).ready(GachaDetailDisplay.start.bind(GachaDetailDisplay));

