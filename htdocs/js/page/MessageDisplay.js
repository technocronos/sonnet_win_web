
/**
 * お知らせ詳細を制御するシングルトンオブジェクト。
 *
 */
function MessageDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MessageDisplay.prototype, 'constructor', {
        value : MessageDisplay,
        enumerable : false
    });
    this.companionId = Page.getParams("companionId");
    this.companionName = Page.getParams("companionName");
    this.me = Page.getParams("message_d");

    this.scroll = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MessageDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MessageDisplay.prototype.start = function() {
console.log("MessageDisplay.start rannning...");
    var self = MessageDisplay;
    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MessageDisplay.prototype.reload = function (){
    var self = MessageDisplay;

    $("#message_explain").html(self.companionName + "にメッセージを送るのだ。100字までなのだ。");

    //OKボタンクリック時イベントハンドラ
    $("#btn_send").off('click').on('click',function() {
        sound("se_btn");

        var body = $("#message").val();

        MessageApi.send(self.companionId, body, function(response){
            console.log(response);

            //結果を返す
            var text = "";
            if(response["result"] == "ok"){
                //okならフォームはクローズ
                self.me.close();
                text = "メッセージを送信したのだ";
            }else{
                text = response["result"];
            }

            var d = new Dialogue();
            Page.setParams("pop_d", d);

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(PopupHtml);

            d.autoClose = false;
            d.veilClose = false;
            d.top = 178;
            d.opacity = 0.5;

            d.show();

            $("#popup_body").html(text);
            $("#popup-close").off('click').on('click',function() {
                sound("se_btn");
                PopupDisplay.destroy();
            });            
        });

    });

    //キャンセルボタンクリック時イベントハンドラ
    $("#btn_close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    $("#message").blur(function() {
        //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・
        //にじよめの場合は専用APIで
        if(window["nijiyome"]){
            nijiyome.ui({"method":"scroll", "x":0, "y":0});
        }else{
          $(window).scrollTop(0);
        }
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MessageDisplay.prototype.onLoaded = function() {
    var self = MessageDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
MessageDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);

    MessageDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var MessageDisplay = new MessageDisplay();

$(document).ready(MessageDisplay.start.bind(MessageDisplay));

