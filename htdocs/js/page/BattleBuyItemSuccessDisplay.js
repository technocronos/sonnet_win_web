
/**
 * アイテム使用（個別）を制御するシングルトンオブジェクト。
 *
 */
function BattleBuyItemSuccessDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(BattleBuyItemSuccessDisplay.prototype, 'constructor', {
        value : BattleBuyItemSuccessDisplay,
        enumerable : false
    });

    //スクロールをストップする
    $(window).on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //スクロールをストップする
    $("#out").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //スクロールをストップする
    $("#BattleBuyItemSuccess").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.buy_count = 1;
    this.price = 0;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
BattleBuyItemSuccessDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BattleBuyItemSuccessDisplay.prototype.start = function() {
console.log("BattleBuyItemSuccessDisplay.start rannning...");
    var self = BattleBuyItemSuccessDisplay;

    var ratio = $(window).width() / 750;
    $("#BattleBuyItemSuccess").css("transform","scale(" + ratio + ")");
    $("#BattleBuyItemSuccess").css("transform-origin","0px 0px");

    self.price = price;

    //ホームサマリ情報を取得する。
    HomeApi.summary(null, null, null, null, function(response){
        //サマリを格納しておく
        Page.setSummary(response);

        self.super.start.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BattleBuyItemSuccessDisplay.prototype.reload = function (){
    var self = BattleBuyItemSuccessDisplay;

    //アイテムアイコン
    $("#item_icon").attr("src", AppUtil.getItemIconURL(suggest_item_id));

    //値段
    $("#price").html(self.price);

    //合計値段
    $("#total_price").html(self.price);

    if(PLATFORM_TYPE == "nati"){
        //所持コイン
        $("#CoinCount").html(Page.getSummary().coin);

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

        //買うボタンクリック時イベントハンドラ
        $("#buy_button").off('click').on('click',function() {
            sound("se_btn");
            ShopApi.buy(suggest_item_id, "ITM", "coin", self.buy_count, function(response){
                console.log(response);

                if(response.result == "ok"){

                    setTimeout(function(){
                        location.href = bitem_backtoUrl.replace(new RegExp("&amp;","g"), "&");
                    }, 500);

                }else if(response.result == "no_coin"){
                    self.showMessage("コインが足りないのだ・・", function(){
                        sound("se_btn");
                        PopupDisplay.destroy();
                    });
                }
            });
        });

        //戻るボタンクリック時イベントハンドラ
        $("#btn_close").off('click').on('click',function() {
            sound("se_btn");
            setTimeout(function(){
                location.href = bitem_backtoUrl.replace(new RegExp("&amp;","g"), "&");
            }, 500);
        });

    }
    //↑ボタンクリック時イベントハンドラ
    $("#btn_up").off('click').on('click',function() {
        if(self.buy_count < 10){
            sound("se_btn");
            self.buy_count++;
            $('input[name="num"]').val(self.buy_count);

            $("#buy_count").html(self.buy_count);
            $("#total_price").html(self.buy_count * self.price);

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
            $('input[name="num"]').val(self.buy_count);

            $("#buy_count").html(self.buy_count);
            $("#total_price").html(self.buy_count * self.price);

            if(self.buy_count == 1)
                $("#btn_down").attr("src", AppUrl.asset("img/parts/sp/more_btn_down_disable.png"));
            if(self.buy_count < 10)
                $("#btn_up").attr("src", AppUrl.asset("img/parts/sp/more_btn_up.png"));
        }
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BattleBuyItemSuccessDisplay.prototype.onLoaded = function() {
    var self = BattleBuyItemSuccessDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BattleBuyItemSuccessDisplay.prototype.destroy = function (){
    var self = BattleBuyItemSuccessDisplay;

    self.super.destroy.call(self);
    BattleBuyItemSuccessDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BattleBuyItemSuccessDisplay = new BattleBuyItemSuccessDisplay();

$(document).ready(BattleBuyItemSuccessDisplay.start.bind(BattleBuyItemSuccessDisplay));

