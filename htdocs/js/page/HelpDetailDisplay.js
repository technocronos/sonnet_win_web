
/**
 * お知らせ詳細を制御するシングルトンオブジェクト。
 *
 */
function HelpDetailDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(HelpDetailDisplay.prototype, 'constructor', {
        value : HelpDetailDisplay,
        enumerable : false
    });
    this.help_id = Page.getParams("help_id");
    this.me = Page.getParams("detail_me");
    this.summary = Page.getSummary();

    this.scroll = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
HelpDetailDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
HelpDetailDisplay.prototype.start = function() {
console.log("HelpDetailDisplay.start rannning...");
    var self = HelpDetailDisplay;

    var content_height = 1000;

    $("#helpdetaildiv").css("top" , screen.height + "px");
    $("#helpdetaildiv").css("margin-top", "-" + (content_height * ($(window).width() / 750)) + "px");

    $("#help_detail_bg_image").html(Page.preload_image.help_detail_bg);

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
HelpDetailDisplay.prototype.reload = function (){
    var self = HelpDetailDisplay;

    HelpApi.detail(self.help_id, function(list){
console.log(list);
        $("#help_title").html(list.help.help_title);

        var HtmlFileName = ("Help-" + list.help.help_id).replace(
            /-./g,
            function(str) {
                return str.charAt(1).toUpperCase();
            });

        HtmlFileName = HtmlFileName + "Html";

        try{
            $("#help_body").html(eval(HtmlFileName));

            if(self.help_id == "other-shoutai"){
                for(var i = 1; i <= 3; i++){
                    if(eval("ibonus_" + i) != null){
                        $("#ibonus_table_"+ i).show();
                        $("#ibonus_image_"+ i).attr("src", AppUtil.getItemIconURL(eval("ibonus_" + i).item_id));
                        $("#ibonus_name_"+ i).html(eval("ibonus_" + i).item_name);
                    }
                }

                for(var i = 1; i <= 2; i++){
                    if(eval("abonus_" + i) != null){
                        $("#abonus_table_"+ i).show();
                        $("#abonus_image_"+ i).attr("src", AppUtil.getItemIconURL(eval("ibonus_" + i).item_id));
                        $("#abonus_name_"+ i).html(eval("abonus_" + i).item_name);
                    }
                }
            }else if(self.help_id == "other-contact"){
//console.log(self.summary.chara.user_id)
                $("#supportmail").blur(function() {
                    //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・
                    $(window).scrollTop(0);
                    $("#iframe-container").contents().scrollTop(0);
                });
                $("#customer_uid").val(self.summary.chara.user_id);
                $("#customer_uid").blur(function() {
                    //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・
                    $(window).scrollTop(0);
                    $("#iframe-container").contents().scrollTop(0);
                });
                $("#customer_name").val(self.summary.player_name);
                $("#customer_name").blur(function() {
                    //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・
                    $(window).scrollTop(0);
                    $("#iframe-container").contents().scrollTop(0);
                });
            }

        }catch(ex){ 
            HtmlFileName = "HelpEmptyHtml";

            $("#help_body").html(eval(HtmlFileName));
        }

        $('input[name="inhearid_code"]').blur(function() {
            //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・

            //にじよめの場合は専用APIで
            if(window["nijiyome"]){
                nijiyome.ui({"method":"scroll", "x":0, "y":0});
            }else{
                $(window).scrollTop(0);
                $("#iframe-container").contents().scrollTop(0);
            }
        });

        $('input[name="tokuten_code"]').blur(function() {
            //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・

            //にじよめの場合は専用APIで
            if(window["nijiyome"]){
                nijiyome.ui({"method":"scroll", "x":0, "y":0});
            }else{
                $(window).scrollTop(0);
                $("#iframe-container").contents().scrollTop(0);
            }
        });

        //事前登録コード決定ボタンクリック時イベントハンドラ
        $("#tokuten-send").off('click').on('click',function() {
            sound("se_btn");
            var tourokucode = $('input[name="tokuten_code"]').val();

            if(tourokucode == ""){
                $("#tokuten_error").html("特典コードを入力してください");
            }else{
                PreRegLogApi.set(tourokucode, function(response){
                    console.log(response);
                    if(response == 1){
                        $("#tokuten_error").html("特典をGETしました！");
                    }else if(response == 2){
                        $("#tokuten_error").html("すでに特典をGET済みです。");
                    }else if(response == 3){
                        $("#tokuten_error").html("そのコードは無効です。");
                    }else if(response == 4){
                        $("#tokuten_error").html("特典コードを入力してください");
                    }else if(response == 5){
                        $("#tokuten_error").html("その特典コードはすでに他のユーザーが使用済みです");
                    }
                });
            }

        });

        //okボタンクリック時イベントハンドラ
        $("#help-detail-close").off('click').on('click',function() {
            sound("se_btn");
            if(window["nijiyome"]){
                nijiyome.ui({"method":"scroll", "x":0, "y":0});
            }
            self.destroy();
            self.me.close();
        });

        //引き継ぎ
        $("#btn_inherit").off("click").on("click",function(){
            sound("se_btn");
            var inherit_code  = $('input[name="inherit_code"]').val();

            if(inherit_code == ""){
                $("#inherit_err").html("引き継ぎコードを入力してください");
                $("#inherit_err").show();
            }else{
                self.showConfirm("データの引き継ぎをするのだ？", function(){
                    self.destroy();
                    self.me.close();
                    PopupConfirmDisplay.destroy();
                    HomeApi.inherit(inherit_code, function(response){
                        if(response.result == -1){
                            self.showMessage("引き継ぎできなかったのだ。コード入力するのだ",function(){
                                sound("se_btn");
                                HelpListDisplay.openbyhelpid("other-inheritdo")
                                PopupDisplay.destroy();
                            });
                        }else if(response.result == -2){
                            self.showMessage("引き継ぎできなかったのだ。どこから来たのだ？",function(){
                                sound("se_btn");
                                HelpListDisplay.openbyhelpid("other-inheritdo")
                                PopupDisplay.destroy();
                            });
                        }else if(response.result == -3){
                            self.showMessage("引き継ぎできなかったのだ。コード間違ってないのだ？",function(){
                                sound("se_btn");
                                HelpListDisplay.openbyhelpid("other-inheritdo")
                                PopupDisplay.destroy();
                            });
                        }else{
                            self.showMessage("引き継ぎしたのだ。TOPページに行くのだ。",function(){
                                sound("se_btn");
                                setTimeout(function(){
                                    location.href = AppUrl.htmlspecialchars_decode( URL_TOP , 'ENT_NOQUOTES' );
                                }, 500);
                            });
                        }
                    });
                });
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
HelpDetailDisplay.prototype.onLoaded = function() {
    var self = HelpDetailDisplay;

    setTimeout(function(){
        //記事のスクロール表示
        if(self.scroll){
            self.scroll.refresh();
        }else{
            self.scroll = new IScroll('#HelpDetailContents .scrollWrapper', {
                click: true,
                scrollbars: 'custom', /* スクロールバーを表示 */
                //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
                //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
                //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
                mouseWheel: false
            });
        }
    },500);
    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
HelpDetailDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    HelpDetailDisplay = null;

    Page.setParams("help_id", null);
    Page.setParams("detail_me", null);

}

//---------------------------------------------------------------------------------------------------------
var HelpDetailDisplay = new HelpDetailDisplay();

$(document).ready(HelpDetailDisplay.start.bind(HelpDetailDisplay));

