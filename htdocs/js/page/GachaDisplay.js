
/**
 *ガチャ画面を制御するシングルトンオブジェクト。
 *
 */
function GachaDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(GachaDisplay.prototype, 'constructor', {
        value : GachaDisplay,
        enumerable : false
    });
    //スクロール無効
    $("#GachaContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.list = $();

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
GachaDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
GachaDisplay.prototype.start = function() {
console.log("GachaDisplay.start rannning...");
    var self = GachaDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
GachaDisplay.prototype.reload = function (){
    self = GachaDisplay;

    GachaApi.gacha(function(response){
        console.log(response);
        self.list = response;

        var caption = "";

        $.each(self.list.gacha, function(key, value){
            //マグナガチャ
            if(value.gacha_kind == 1){
                $("#gacha_gold").show();

                $("#gacha_gold").off("click").on("click", function(){
                    self.showGachaDetail('1', false);
                });
            //雑貨ガチャ
            }else if(value.gacha_id == "9998"){
                $("#gacha_zakka").show();
                $("#gacha_zakka").off("click").on("click", function(){
                    var gacha_id = 9998;
                    //フリーのガチャが引ける場合
                    if(Page.getSummary().freeGacha){
                        gacha_id = 9997;
                    }
                    self.showGachaDetail(gacha_id, false);
                });
            //ローテーションガチャ
            }else if(value.wk_flg == 1){
                $("#gacha_lotation").show();
                $("#gacha_lotation_bannar").attr("src", AppUrl.asset("img/gacha/" + AppUtil.padZero(value.gacha_id, 5) + ".png"));

                $("#gacha_lotation").off("click").on("click", function(){
                    self.showGachaDetail(value.gacha_id, true);
                });
            //SPガチャ
            }else if(value.sp_flg == 1){
                $("#gacha_coin").show();
                $("#gacha_coin_bannar").attr("src", AppUrl.asset("img/gacha/" + AppUtil.padZero(value.gacha_id, 5) + ".png"));

                $("#gacha_coin").off("click").on("click", function(){
                    self.showGachaDetail(value.gacha_id, true);
                });
            //イベントガチャ
            }else if(value.clear_event_id != undefined){
                $("#gacha_event").show();
                caption = value.caption;
                $("#gacha_event_bannar").attr("src", AppUrl.asset("img/gacha/" + AppUtil.padZero(value.gacha_id, 5) + ".png"));
                //告知タイムの時
                if(value.notice_time == true){
                    //TODO::告知用に切り替える
                    $("#gacha_event_bannar").attr("src", AppUrl.asset("img/gacha/" + AppUtil.padZero(value.gacha_id, 5) + ".png"));
                    $("#gacha_event_bannar").css("-webkit-filter", "grayscale(1)");
                    $("#gacha_event_bannar").css("filter", "gray");
                    $("#gacha_event_bannar").css("filter", "grayscale(1)");
                }else{
                    $("#gacha_event").off("click").on("click", function(){
                        self.showGachaDetail(value.gacha_id, true);
                    });
                }
            //チュートリアルガチャ
            }else if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_GACHA)){
                $("#gacha_coin").show();
                $("#gacha_coin_bannar").attr("src", AppUrl.asset("img/gacha/" + AppUtil.padZero(value.gacha_id, 5) + ".png"));

                $("#gacha_coin").off("click").on("click", function(){
                    self.showGachaDetail(value.gacha_id, true);
                });
            }
        });

        //キャプションを更新
        $("#summary").html(caption);

        //チュートリアル中の場合
        if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_GACHA)){
            $("#navi_serif").html("ここはワシが案内してやろう<br>ガチャには すぺっしゃる なアイテムがたくさんあるぞい｡<span style='color:red'>師匠ガチャ</span>をクリックじゃ!")

            //ナビカーソルを表示する
            AppUtil.showArrow($("#GachaContents"), "down", 150, 350);

        }else{
            $("#navi_serif").html("コインやマグナでガチャを回してアイテムゲットするのじゃ<br>回せ回せ!人生とはギャンブルじゃぞ! ");
        }

        self.super.reload.call(self);
    });

}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
GachaDisplay.prototype.onLoaded = function() {
    var self = GachaDisplay;

    $("#main_contents").show();

    $("#btn_list").animate({
        "left": "74px"
    },200);

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ガチャリストポップアップを呼び出す
 */
GachaDisplay.prototype.showGachaDetail = function(gacha_id, sp_flg) {
    //チュートリアル中でスペシャルガチャ以外の場合
    if(parseInt(Page.getSummary().tutorial_step) == parseInt(TUTORIAL_GACHA) && sp_flg == false)
        return;

    sound("se_btn");

    var entry = $();
    $.each(self.list.gacha, function(key, value){
        if(value.gacha_id == gacha_id)
            entry = value;
    });

    if(gacha_id == 9997)
        entry = {"gacha_id":9997};

    Page.setParams("freeGacha", self.list.freeGacha);
    Page.setParams("ticketCount", self.list.ticketCount);
    Page.setParams("gacha_entry", entry);

    MainContentsDisplay.FooterCanvas.out("gacha_detail","gacha");
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
GachaDisplay.prototype.destroy = function (){
    var self = GachaDisplay;

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();
        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var GachaDisplay = new GachaDisplay();

$(document).ready(GachaDisplay.start.bind(GachaDisplay));

