
/**
 * アイテム使用（個別）を制御するシングルトンオブジェクト。
 *
 */
function SuggestSuccessDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(SuggestSuccessDisplay.prototype, 'constructor', {
        value : SuggestSuccessDisplay,
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
    $("#SuggestSuccess").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.buy_count = 1;
    this.price = 0;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
SuggestSuccessDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
SuggestSuccessDisplay.prototype.start = function() {
console.log("SuggestSuccessDisplay.start rannning...");
    var self = SuggestSuccessDisplay;

    var ratio = $(window).width() / 750;
    $("#SuggestSuccess").css("transform","scale(" + ratio + ")");
    $("#SuggestSuccess").css("transform-origin","0px 0px");

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
SuggestSuccessDisplay.prototype.reload = function (){
console.log("SuggestSuccessDisplay.reload rannning...");
    var self = SuggestSuccessDisplay;

    //アイテムアイコン
    $("#item_icon").attr("src", AppUtil.getItemIconURL(item_id));

    //値段
    $("#price").html(self.price);

    //合計値段
    $("#total_price").html(self.price);

    if(PLATFORM_TYPE == "nati"){
        //所持コイン
        $("#gold").html(Page.getSummary().coin);

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

        //買うボタンクリック時イベントハンドラ
        $("#buy_button").off('click').on('click',function() {
            sound("se_btn");
            ShopApi.buy(suggest_item_id, "ITM", "coin", self.buy_count, function(response){
                console.log(response);

                if(response.result == "ok"){

                    setTimeout(function(){
                        location.href = suggest_nexturl.replace(new RegExp("&amp;","g"), "&");
                    }, 500);

                }else if(response.result == "no_coin"){
                    self.showMessage("コインが足りないのだ・・", function(){
                        sound("se_btn");
                        PopupDisplay.destroy();
                    });
                }
            });
        });
    }

    //使うボタンクリック時イベントハンドラ
    $("#submit_button").off('click').on('click',function() {
        sound("se_btn");
        setTimeout(function(){
            document.form.submit();
        }, 500);
    });

    //戻るボタンクリック時イベントハンドラ
    $("#btn_close").off('click').on('click',function() {
        sound("se_btn");
        setTimeout(function(){
            location.href = backto_url.replace(new RegExp("&amp;","g"), "&");
        }, 500);
    });
    //メニューへ戻るボタンクリック時イベントハンドラ
    $("#btn_backto_main").off('click').on('click',function() {
        sound("se_btn");
        setTimeout(function(){
            location.href = main_url.replace(new RegExp("&amp;","g"), "&");
        }, 500);
    });

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
SuggestSuccessDisplay.prototype.onLoaded = function() {
    var self = SuggestSuccessDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
SuggestSuccessDisplay.prototype.destroy = function (){
    var self = SuggestSuccessDisplay;

    self.super.destroy.call(self);
    SuggestSuccessDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var SuggestSuccessDisplay = new SuggestSuccessDisplay();

$(document).ready(SuggestSuccessDisplay.start.bind(SuggestSuccessDisplay));

