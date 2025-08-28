
/**
 * TOP画面を制御するシングルトンオブジェクト。
 *
 */
function IndexSuccessDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(IndexSuccessDisplay.prototype, 'constructor', {
        value : IndexSuccessDisplay,
        enumerable : false
    });

    //スクロールをストップする
    $(window).on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //背景スクロールをストップする
    $("#IndexContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
IndexSuccessDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
IndexSuccessDisplay.prototype.start = function() {
console.log("IndexSuccessDisplay.start rannning...");
    var self = IndexSuccessDisplay;

    sound_stop();

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

        Unity.call('top');
    }
    catch (e) {
        alert(e);
    }

    self.FlashAnim($("#btn_start"));
    $('body').css("background", "black");

    //self.reload();
    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
IndexSuccessDisplay.prototype.reload = function (){
    var self = IndexSuccessDisplay;

    $img = $(Page.preload_image["titlelogo"]).css("width", "100%");
    $("#titlelogo").html($img);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
IndexSuccessDisplay.prototype.onLoaded = function() {
    var self = IndexSuccessDisplay;

    //sound("bgm_theme");

    //loading表示ストップ
    $("#loading").hide();

    $("#btn_start").off('click').on('click',function() {
        sound("se_btn");
        sound_stop();
        //退場アニメーションが終わったら
        $("#IndexContents").fadeOut("normal", function(){
            location.href = AppUrl.htmlspecialchars_decode( startAction , 'ENT_NOQUOTES' );
        });
    });

    $("#btn_sound").off('click').on('click',function() {
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

                Unity.call('sound-se_btn');
            }
            catch (e) {
                alert(e);
            }
    });

    $("#btn_bgmstart").off('click').on('click',function() {
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

                Unity.call('bgm_start-bgm_battle');
            }
            catch (e) {
                alert(e);
            }
    });

    $("#btn_bgmstop").off('click').on('click',function() {
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

                Unity.call('bgm_stop');
            }
            catch (e) {
                alert(e);
            }
    });

    $("#btn_ios_store").off('click').on('click',function() {
        //sound("se_btn");
        location.href = AppUrl.htmlspecialchars_decode( APP_STORE_URL , 'ENT_NOQUOTES' );
    });

    $("#btn_android_store").off('click').on('click',function() {
        //sound("se_btn");  

        //直接ストア遷移は52以降実装
        if(ANDROID_VER >= 52){
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

                Unity.call('version-' + ANDROID_VER);
            }
            catch (e) {
                alert(e);
            }
        }else{
            location.href = AppUrl.htmlspecialchars_decode( GOOGLE_PLAY_URL , 'ENT_NOQUOTES' );
        }
    });

    //nativeの場合は全体クリックしても遷移
    if(PLATFORM_TYPE == "nati"){
        $("#btn_splash").off('click').on('click',function() {
            sound("se_btn");
            sound_stop();
            $("#IndexContents").fadeOut("normal", function(){
                location.href = AppUrl.htmlspecialchars_decode( startAction , 'ENT_NOQUOTES' );
            });
        });
    }

    //self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 点滅アニメーション
 */
IndexSuccessDisplay.prototype.FlashAnim = function(entry){
    entry.fadeTo(2400, 0.2 , function(){$(this).fadeTo(2400, 1)});

    setInterval(function(){
        entry.fadeTo(2400, 0.2 , function(){$(this).fadeTo(2400, 1)});
    }, 4800);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
IndexSuccessDisplay.prototype.destroy = function (){
    var self = IndexSuccessDisplay;

    self.super.destroy.call(self);
    IndexSuccessDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var IndexSuccessDisplay = new IndexSuccessDisplay();

$(document).ready(IndexSuccessDisplay.start.bind(IndexSuccessDisplay));

