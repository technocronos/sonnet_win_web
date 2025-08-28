
/**
 * 合成結果を制御するシングルトンオブジェクト。
 *
 */
function SyncResultDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(SyncResultDisplay.prototype, 'constructor', {
        value : SyncResultDisplay,
        enumerable : false
    });

    this.response = Page.getParams("sync_response");

    this.base_entry = Page.getParams("sync_base_entry");
    this.source_entry = Page.getParams("sync_source_entry");

    this.me = Page.getParams("syncresult_d");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
SyncResultDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
SyncResultDisplay.prototype.start = function() {
console.log("SyncResultDisplay.start rannning...");
    var self = SyncResultDisplay;

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
SyncResultDisplay.prototype.reload = function (){
    var self = SyncResultDisplay;

    $("#item_image").attr('src', AppUtil.getItemIconURL(self.base_entry["item_id"]));

    var text = "";

    //フレーバーテキスト
    if(self.response["alv"] > self.response["blv"]){
        $("#revelup_panel").show();
        $("#rorate_image").show();
        //回転
        AppUtil.rotate($("#rorate_image"), 2.5);

        text = self.base_entry["item_name"] + "がレベル" + self.response["alv"] + "になりました！" + "<br>";
    }else{
        $("#revelup_panel").hide();
        $("#rorate_image").hide();
    }

    text += self.base_entry["item_name"] + "に" + self.source_entry["item_name"] + "を合成しました" + "<br>";
    text += "経験値：" + "<span class='colorIntens'>" + self.response["bex"] + "→" + self.response["aex"] + "</span><br>";

    if(self.response["alv"] > self.response["blv"]){
        text += "レベル：" + "<span class='colorIntens'>" + self.response["blv"] + "→" + self.response["alv"] + "</span><br>";
    }

    text += "<br>";
    text += self.source_entry["item_name"] + "は消滅した.." + "<br>";

    $("#result_text").html(text);

    //jsに値段を反映
    chara_gold = parseInt(self.response["agld"]);

    //flashに値段を反映(ヘッダは常にrefが定期的に走るので値だけ書き換え)
    MainContentsDisplay.HeaderCanvas.update("gold", chara_gold);

    //OKボタンクリック時イベントハンドラ
    $("#sync-result-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        EquipListDisplay.restart();
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
SyncResultDisplay.prototype.onLoaded = function() {
    var self = SyncResultDisplay;

    //ちょっと遅延させてから消す
    $(function(){
        $("#weapon_area").sparkleh();

        setTimeout(function(){
            sound("se_congrats");
            AppUtil.circle($("#circle_div"),750 / 2, 160);

            setTimeout(function(){
                AppUtil.circle($("#circle_div2"),750 / 2, 160);
            },40);
        },200);
    });

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
SyncResultDisplay.prototype.destroy = function (){
    var self = SyncResultDisplay;

    self.me.close();
    self.super.destroy.call(self);
    SyncResultDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var SyncResultDisplay = new SyncResultDisplay();

$(document).ready(SyncResultDisplay.start.bind(SyncResultDisplay));

