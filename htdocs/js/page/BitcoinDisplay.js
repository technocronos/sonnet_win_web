
/**
 * ビットコイン画面を制御するシングルトンオブジェクト。
 *
 */
function BitcoinDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "BitcoinDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    this.me = Page.getParams("me");

    $(window).scrollTop(0);
}

// 親クラスを継承
BitcoinDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BitcoinDisplay.prototype.start = function() {
console.log("BitcoinDisplay.start rannning...");
    var self = BitcoinDisplay;

    var content_height = 1100;

    if(is_tablet == "tablet"){
        $("#bitcoindiv").css("transform", "scale(0.9)");
        $("#bitcoindiv").css("transform-origin", "50% 50%");
    }else{
        $("#bitcoindiv").css("top" , screen.height + "px");
        $("#bitcoindiv").css("margin-top", "-" + (content_height * ($(window).width() / 750)) + "px");
    }

    $("#bg_image").html(Page.preload_image.circle_bg);

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BitcoinDisplay.prototype.reload = function (){
    var self = BitcoinDisplay;

    //ホームサマリ情報を取得する。
    HomeApi.summary(null, null, null, null, function(response){
        MainContentsDisplay.summary = response;
        
        $("#btc_amount").html(MainContentsDisplay.summary.vcoin);
        $("#vcoin_fee").html(VCOIN_FEE);
        $("#vcoin_minimam").html(VCOIN_MINIMAM);

        if((MainContentsDisplay.summary.vcoin - VCOIN_MINIMAM) >= 0)
            $("#address").prop("disabled", false);
        else
            $("#address").prop("disabled", true);

        //okボタンクリック時イベントハンドラ
        $("#bitcoin-send").off('click').on('click',function() {
            sound("se_btn");
            var address = $("#address").val();
            var txt = "出金依頼をするのだ？";
            var amount = MainContentsDisplay.summary.vcoin;

            if((MainContentsDisplay.summary.vcoin - VCOIN_MINIMAM) < 0){
                txt = VCOIN_MINIMAM + "BTC以上無いと出金できないのだ";
                self.showMessage(txt);
                return;
            }else if(address == ""){
                txt = "アドレスを入力するのだ";
                self.showMessage(txt);
                return;
            }

            //確認ポップアップを立ち上げる
            self.showConfirm(txt, function(){
                sound("se_btn");

                //出金依頼をする
                VcoinApi.send(amount, address, function(response){
                    console.log(response);

                    if(response["result"] == "no_address"){
                        txt = "アドレスを入力するのだ";
                    }else if(response["result"] == "invalid_address"){
                        txt = "そのアドレスは間違ってるのだ";
                    }else if(response["result"] == "no_user"){
                        txt = "ユーザーがいないのだ・・";
                    }else if(response["result"] == "short_amount"){
                        txt = VCOIN_MINIMAM + "BTC以上無いと出金できないのだ";
                    }else if(response["result"] == "short_payment"){
                        txt = "これまでの課金額が" + VCOIN_MINIMAM_PAYMENT + "円以上無いと出金できないのだ。詳しくはヘルプを見るのだ。";
                    }else if(response["result"] == "invalid_amount"){
                        txt = "出金額が一致しないのだ";
                    }else if(response["result"] == "payment_stop"){
                        txt = "現在、出金停止中なのだ。お知らせを見るのだ。";
                    }else if(response["result"] == "canpain_stop"){
                        txt = "現在、キャンペーンをしていないのだ";
                    }else{
                        txt = "出金依頼が完了したのだ。<br>入金までしばらく待つのだ。";
                    }

                    self.showMessage(txt, function(){
                        sound("se_btn");
                        PopupDisplay.destroy();
                        PopupConfirmDisplay.destroy();
                        self.reload();
                    });
                });
            });
        });

        //履歴ボタンクリック時イベントハンドラ
        $("#btn_getlog").off('click').on('click',function() {
            sound("se_btn");
            self.showGetLog();
        });

        //履歴ボタンクリック時イベントハンドラ
        $("#btn_list").off('click').on('click',function() {
            sound("se_btn");
            self.showList();
        });

        //閉じるボタンクリック時イベントハンドラ
        $("#bitcoin-close").off('click').on('click',function() {
            sound("se_btn");
            if(window["nijiyome"]){
                nijiyome.ui({"method":"scroll", "x":0, "y":0});
            }
            self.destroy();
            self.me.close();
        });

        $("#btn_help1").off("click").on("click",function(){
            sound("se_btn");
            self.onHelpShow("vcoin-about");
        });
        $("#btn_help2").off("click").on("click",function(){
            sound("se_btn");
            self.onHelpShow("vcoin-receive");
        });
        $("#btn_help3").off("click").on("click",function(){
            sound("se_btn");
            self.onHelpShow("vcoin-receive-order");
        });
        $("#btn_help4").off("click").on("click",function(){
            sound("se_btn");
            self.onHelpShow("vcoin-notice");
        });

        $("#address").blur(function() {
            //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・
            //にじよめの場合は専用APIで
            if(window["nijiyome"]){
                nijiyome.ui({"method":"scroll", "x":0, "y":0});
            }else{
              $(window).scrollTop(0);
            }
        });
        self.super.reload.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BitcoinDisplay.prototype.onLoaded = function() {
    var self = BitcoinDisplay;

    self.super.onLoaded.call(self);
}


//---------------------------------------------------------------------------------------------------------
/**
 * 取得ログクリック時イベントハンドラ。
 */
BitcoinDisplay.prototype.showGetLog = function() {
    sound("se_btn");
    var d = new Dialogue();

    Page.setParams("me", d);

    d.appearance('<div ><div key="dialogue-content"></div></div>');
    d.content(BitcoinGetLogHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 出金履歴クリック時イベントハンドラ。
 */
BitcoinDisplay.prototype.showList = function() {
    sound("se_btn");
    var d = new Dialogue();

    Page.setParams("me", d);

    d.appearance('<div ><div key="dialogue-content"></div></div>');
    d.content(BitcoinListHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BitcoinDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    BitcoinDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BitcoinDisplay = new BitcoinDisplay();

$(document).ready(BitcoinDisplay.start.bind(BitcoinDisplay));

