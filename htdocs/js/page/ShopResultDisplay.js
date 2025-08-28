
/**
 * ショップ確認を制御するシングルトンオブジェクト。
 *
 */
function ShopResultDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ShopResultDisplay.prototype, 'constructor', {
        value : ShopResultDisplay,
        enumerable : false
    });

    this.currency = Page.getParams("buy_currency");
    this.user_item_id = Page.getParams("buy_user_item_id");
    this.gold = Page.getParams("buy_gold");

    //使用したらすぐ初期化してしまう
    Page.setParams("buy_currency", null);
    Page.setParams("buy_user_item_id", null);
    Page.setParams("buy_gold", null);

    this.me = Page.getParams("shopresult_d");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ShopResultDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ShopResultDisplay.prototype.start = function() {
console.log("ShopResultDisplay.start rannning...");
    var self = ShopResultDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ShopResultDisplay.prototype.reload = function (){
    var self = ShopResultDisplay;

    UserItemApi.get(self.user_item_id, function(response){
        console.log(response);

        var user_item = response["user_item"];

        $("#item_image").attr('src', AppUtil.getItemIconURL(user_item["item_id"]));

        //現在のサマリーを得る
        var summary = Page.getSummary();

        //通貨
        if(self.currency == "gold"){
            $("#navi_result").attr("src", AppUrl.asset("img/parts/sp/navi.png"))
            $("#navi_serif_result").html( user_item.item_name + "をゲットしたのだ！" );

            //flashに値段を反映(ヘッダは常にrefが定期的に走るので値だけ書き換え)
            chara_gold= self.gold;
            MainContentsDisplay.HeaderCanvas.update("gold", self.gold);

            summary.gold = self.gold;
        }else{
            $("#navi_result").attr("src", AppUrl.asset("img/parts/sp/navi2.png"))
            $("#navi_serif_result").html( user_item.item_name + "ゲットじゃ。フォッフォッフォ、やはりコインじゃのう" );

            summary.coin = self.gold;
        }

        //サマリーを反映
        Page.setSummary(summary);

        //OKボタンクリック時イベントハンドラ
        $("#shop-result-close").off('click').on('click',function() {
            sound("se_btn");
            self.destroy();

            //チュートリアル中の場合、サマリーを再取得し、次の画面に移動する
            if(parseInt(Page.getSummary().tutorial_step) < parseInt(TUTORIAL_END)){
                HomeApi.summary(null, null, null, null, function(response){
                    console.log(response);
                    Page.setSummary(self.summary);

                    //ホームに戻る
                    MainContentsDisplay.onScreenChange("menu", "shop");
                });
            }else{
                ShopListDisplay.reload();
            }
        });
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
ShopResultDisplay.prototype.onLoaded = function() {
    var self = ShopResultDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ShopResultDisplay.prototype.destroy = function (){
    var self = ShopResultDisplay;

    self.me.close();
    self.super.destroy.call(self);
    ShopResultDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ShopResultDisplay = new ShopResultDisplay();

$(document).ready(ShopResultDisplay.start.bind(ShopResultDisplay));

