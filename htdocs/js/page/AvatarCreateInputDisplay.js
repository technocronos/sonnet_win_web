
/**
 * ユーザー登録を制御するシングルトンオブジェクト。
 *
 */
function AvatarCreateInputDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(AvatarCreateInputDisplay.prototype, 'constructor', {
        value : AvatarCreateInputDisplay,
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
    $("#AvatarCreateInput").on('touchmove.noScroll', function(e) {
            e.preventDefault();
    });

    this.NaviCanvas = null;

    this.super = DisplayCommon.prototype;

    this.btn_ok_push = false;

}

// 親クラスを継承
AvatarCreateInputDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
AvatarCreateInputDisplay.prototype.start = function() {
console.log("AvatarCreateInputDisplay.start rannning...");
    var self = AvatarCreateInputDisplay;

    var summary = {};
    summary.opening = ["よく来たのだ", 
                                "もじょはこのこの世界でヒマツブシしてる精霊なのだ",
                                "もじょって名前なのだ",
                                "これからもちょくちょく顔出して案内してやるから喜べなのだ",
                                "それじゃ、まずは主人公の名前を\n決めるのだ",
                                "おっと!もし機種変更で再登録\nのユーザーなら引き継ぎコードを入れるのだ",
                                "",
                               ];
    summary.openingNum = summary.opening.count().length;
    summary.end_function = "PrologueContentsDisplay.navi_speak_end"

    //navi作成
    self.NaviCanvas= new NaviCanvas(self,null,summary);

    //全canvasが読み込まれていることを保証する。
    var timer = null;
    $(function(){
        timer = setInterval(function(){
            if(self.NaviCanvas.loaded){
                //loading..表示タイマーストップ
                clearInterval(timer);

                self.super.start.call(self);

                //ローディングは出さない
                $("#mini_loading").hide();
            }
        },500);
    });

}

/*
  ナビがしゃべり終わった
*/
AvatarCreateInputDisplay.prototype.navi_speak_end = function(e) {
    var self = AvatarCreateInputDisplay;

    $("#maincontent").show();

    //ナビ退場
    self.NaviCanvas.out();
    self.NaviCanvas.destroy();

}

/*
  ナビがしゃべり終わった
*/
AvatarCreateInputDisplay.prototype.navi_speak_end2 = function(e) {
    var self = AvatarCreateInputDisplay;
    self.NaviCanvas.out();
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
AvatarCreateInputDisplay.prototype.reload = function (){
    var self = AvatarCreateInputDisplay;

    //navi登場
    self.NaviCanvas.appear();

    $('input[name="name"]').blur(function() {
        //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・
        $(window).scrollTop(0);
        $("#iframe-container").contents().scrollTop(0);
    });

    $('input[name="inherit_code"]').blur(function() {
        //iOSの場合だと入力ダイアログがコンテンツを上に押し上げるので・・
        $(window).scrollTop(0);
        $("#iframe-container").contents().scrollTop(0);
    });


    $("#btn_ok").off("click").on("click",function(){
        sound("se_btn");
        var user_name = $('input[name="name"]').val();
        if(user_name == ""){
            self.showMessage("名前を入力するのだ", function(){
                        sound("se_btn");
                        PopupDisplay.destroy();
                    });
        }else if(user_name.length > 6){
            self.showMessage("名前は6文字までなのだ", function(){
                        sound("se_btn");
                        PopupDisplay.destroy();
                    });
        }else{
            self.showConfirm(user_name + "って名前でいいのだ？", function(){
                sound("se_btn");

                //二度押し対応
                if(self.btn_ok_push == true){
                    return;
                }else{
                    self.btn_ok_push = true;
                }

                HomeApi.regist(user_name, function(response){
                    console.log(response);

                    if(response.result == 1){
                        $("#maincontent").hide();

                        //navi登場
                        var summary = {};
                        summary.opening = ["OKなのだ", 
                                            user_name + "、準備はいいのだ？",
                                            "…じゃ､そろそろ物語を始めるのだ…",
                                            "",
                                          ];
                        summary.openingNum = summary.opening.count().length;
                        summary.end_function = "PrologueContentsDisplay.navi_speak_end2"

                        //navi作成
                        self.NaviCanvas= new NaviCanvas(self,null,summary);

                        //全canvasが読み込まれていることを保証する。
                        var timer = null;
                        $(function(){
                            timer = setInterval(function(){
                                if(self.NaviCanvas.loaded){
                                    //loading..表示タイマーストップ
                                    clearInterval(timer);
                                    //確認を閉じる
                                    PopupConfirmDisplay.destroy();
                                    //navi再登場
                                    self.NaviCanvas.appear();
                                }
                            },100);
                        });

                    }else if(response.result == 2){
                        self.showMessage("名前は6文字までなのだ",function(){
                            sound("se_btn");
                            PopupDisplay.destroy();
                        });
                    }
                });

            });
        }
    });

    $("#btn_inherit").off("click").on("click",function(){
        sound("se_btn");
        var inherit_code  = $('input[name="inherit_code"]').val();

        if(inherit_code == ""){
            self.showMessage("引き継ぎコードを入力するのだ",function(){
                        sound("se_btn");
                        PopupDisplay.destroy();
                    });
        }else{
            self.showConfirm("データの引き継ぎをするのだ？", function(){
                PopupConfirmDisplay.destroy();
                HomeApi.inherit(inherit_code, function(response){
                    if(response.result == -1){
                        self.showMessage("引き継ぎできなかったのだ。コード入力するのだ");
                    }else if(response.result == -2){
                        self.showMessage("引き継ぎできなかったのだ。どこから来たのだ？");
                    }else if(response.result == -3){
                        self.showMessage("引き継ぎできなかったのだ。コード間違ってないのだ？");
                    }else{
                        self.showMessage("引き継ぎしたのだ。メインページに行くのだ。",function(){
                            sound("se_btn");
                            setTimeout(function(){
                                location.href = AppUrl.htmlspecialchars_decode( urlOnMain , 'ENT_NOQUOTES' );
                            }, 500);
                        });
                    }
                });
            });
        }
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
AvatarCreateInputDisplay.prototype.onLoaded = function() {
    var self = AvatarCreateInputDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
AvatarCreateInputDisplay.prototype.destroy = function (){
    var self = AvatarCreateInputDisplay;

    self.super.destroy.call(self);
    AvatarCreateInputDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var AvatarCreateInputDisplay = new AvatarCreateInputDisplay();

$(document).ready(AvatarCreateInputDisplay.start.bind(AvatarCreateInputDisplay));

