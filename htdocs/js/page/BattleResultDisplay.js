
/**
 * バトル結果を制御するシングルトンオブジェクト。
 *
 */
function BattleResultDisplay(){

    //コンストラクタ名を書き換える
    Object.defineProperty(BattleResultDisplay.prototype, 'constructor', {
        value : BattleResultDisplay,
        enumerable : false
    });

    this.defaultListId = "battle-result-list";
    this.defaultEntryId = "battle-result-template";

    this.battleId = Page.getParams("battleId");
    this.side = Page.getParams("side");
    this.repaireId = Page.getParams("repaireId");

    this.me = Page.getParams("battle_result_me");

    this.CharaCanvasBtl = null;
    this.ExpGaugeCanvas = null;

    this.category = "EQP";
    this.reslt = null;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
BattleResultDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BattleResultDisplay.prototype.start = function() {
console.log("BattleResultDisplay.start rannning...");
    var self = BattleResultDisplay;

    if(is_tablet == "tablet"){
        $("#battle_middle").css("transform", "scale(0.7)");
        $("#battle_middle").css("transform-origin", "50% 50%");
    }

    $("#bg_image").html(Page.preload_image.bg_none);

    //タブの選択
    $("#tab-" + self.category).css("display", "block");
    $("#SUM_panel").hide();

    self.CharaCanvasBtl = new CharaCanvas(self,"Btl",chara_user_id, chara_image_url);
    self.ExpGaugeCanvas = new ExpGaugeCanvas(self);

    //全canvasが読み込まれていることを保証する。
    var timer = null;
    $(function(){
        timer = setInterval(function(){
            if(self.CharaCanvasBtl.loaded){
                //loading..表示タイマーストップ
                clearInterval(timer);

                self.super.start.call(self);
            }
        },500);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BattleResultDisplay.prototype.reload = function (){
console.log("BattleResultDisplay.reload rannning...");

    var self = BattleResultDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    BattleApi.result(self.battleId, self.side, self.repaireId, null, function(response){

/*
response.gradeup = true;
response["grade"] = {};
response["grade"]["dtech"] = {};
response["grade"]["grade_name"] = "テストグレード";
response["grade"]["dtech"]["dtech_name"] = "テスト必殺技";

response["capture"] = {};
response["capture"]["character_id"] = "01101";

response["result"]["gain"]["uitem"] = [
                                        {"item_id":1001, "item_name": "くすりびん", "category":"ITM", "flavor_text":"昔から伝わっている回復薬だけど､とにかくﾏｽﾞい", "effect":"HPを1000回復 射程2 範囲1"},
                                        {"item_id":1002, "item_name": "キュアハーブス", "category":"ITM", "flavor_text":"どこかのﾅﾝﾄｶさんが作ってるらしい｡ほんのり甘くておいしい", "effect":"HPを1000回復 射程2 範囲1"},
                                      ];

*/
        if(response.capture != null){
            response["capture_flg"] = true;
        }

        if(response.result.gain.uitem != undefined){
            if(response.result.gain.uitem.length > 0){
                response["item_flg"] = true;
            }
        }

        self.list = response;

console.log(self.list);

        $("#battle_result").attr("src", AppUrl.asset("img/parts/sp/battle_result_title_" + self.list.battle.bias_status + ".png" ))

        if(self.list.battle.bias_status == "win")
            $("#chara_spot").show();

        //ユーザー対戦とクエストで出口を分ける
        if(self.list.battle.tournament_id == 1){
            //バトル
            $("#btn_left_text").html("対戦相手一覧へ");
            $("#btn_right_text").html("対戦相手のページへ");
        }else{
            //クエスト
            $("#btn_left_text").html("メインページへ");
            $("#btn_right_text").html("フィールドへ");
        }

        if(self.list.battle.bias_user_id != -1){
        
            //追加マグナ
            $("#add_gold").html("+" + self.list.battle.bias_result.gain.gold);
            //現在のマグナ
            $("#gold").html(parseInt(self.list.battle.bias_result.gain.gold) + parseInt(self.list.current.gold));

            //追加階級ポイント
            $("#grade_nominal").html("+" + self.list.battle.bias_result.gain.grade_nominal);
            //現在のポイント
            if(self.list.battle.bias_result.gain.grade < self.list.battle.bias_result.gain.grade_nominal){
                $("#grade_pt").html("[MAX]");
            }else{
                $("#grade_pt").html(self.list.current.grade_pt);
            }
            //ゲージ更新
            self.ExpGaugeCanvas.init(self.list.battle.bias_result.gain.exp, self.list.current.exp.relative_exp, self.list.current.exp.relative_next);

        }else{
            //追加マグナ
            $("#add_gold").html("---");
            //現在のマグナ
            $("#gold").html("---");

            //追加階級ポイント
            $("#grade_nominal").html("---");
            //現在のポイント
            $("#grade_pt").html("---");
            //ゲージ更新
            self.ExpGaugeCanvas.init(0, 0, 0);
        }

        //サマリー
        $("#match_length").html(self.list.battle.result_detail.match_length + "回");

        self.setSummary("total_hurt");
        self.setSummary("normal_hurt");
        self.setSummary("normal_hits");
        self.setSummary("tact0");
        self.setSummary("revenge_hurt");
        self.setSummary("revenge_count");

        if(self.list.battle.bias_result.summary.revenge_attacks > 0){
            $("#revenge_hitsP").html(Math.floor((self.list.battle.bias_result.summary.revenge_hits / self.list.battle.bias_result.summary.revenge_attacks * 100)) + "%");
        }else{
            $("#revenge_hitsP").html("---%");
        }
        if(self.list.battle.rival_result.summary.revenge_attacks > 0){
            $("#revenge_hitsE").html(Math.floor((self.list.battle.rival_result.summary.revenge_hits / self.list.battle.rival_result.summary.revenge_attacks * 100)) + "%");
        }else{
            $("#revenge_hitsE").html("---%");
        }

        //ボタンクリック時イベントハンドラ
        $("#btn_left").off('click').on('click',function() {
            sound("se_btn");

            var url = response["urlOnMain"];
            if(self.list.battle.tournament_id == 1)
                url = response["urlOnList"];

            self.destroy();

            //すぐ遷移するとサウンドが鳴らないので・・
            setTimeout(function(){
                window.location.href = url;
            }, 500);
        });

        //ボタンクリック時イベントハンドラ
        $("#btn_right").off('click').on('click',function() {
            sound("se_btn");

            var url = response["urlOnQuest"];
            if(self.list.battle.tournament_id == 1)
                url = response["urlOnMypage"];

            self.destroy();

            //すぐ遷移するとサウンドが鳴らないので・・
            setTimeout(function(){
                window.location.href = url;
            }, 500);
        });

        if(self.list.battle.bias_user_id != -1){
            for(i = 1; i <= 4; i++){
                if(self.list.result.equip.before[i] == undefined){
                    self.list.result.equip.before[i] = {"user_item_id": "", "item_id": ""};
                }

                if(self.list.result.equip.after[i] == undefined){
                    self.list.result.equip.after[i] = {"user_item_id": "", "item_id": ""};
                }
            }
            // パケットをリストに表示。
            self.refreshList(self.list.result.equip.after,self.list.result.equip.before);

        }else{
            self.onLoaded();
        }


        self.super.reload.call(self);
    });
}



//---------------------------------------------------------------------------------------------------------
/**
 * 仮想通貨ゲットを表示する
 */
BattleResultDisplay.prototype.showVcoin = function(amount) {
    var self = BattleResultDisplay;

    var d = new Dialogue();

    Page.setParams("amount", amount);
    Page.setParams("vcoin_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(VcoinGetHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();

}

//---------------------------------------------------------------------------------------------------------
/**
 * レベルアップを表示する
 */
BattleResultDisplay.prototype.showLevelUp = function() {
    var self = BattleResultDisplay;

    var d = new Dialogue();

    Page.setParams("list", self.list);
    Page.setParams("levelup_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(LevelUpHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 階級アップを表示する
 */
BattleResultDisplay.prototype.showGradeUp = function() {
    var self = BattleResultDisplay;

    var d = new Dialogue();

    Page.setParams("list", self.list);
    Page.setParams("gradeup_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(GradeUpHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * モンスターGETを表示する
 */
BattleResultDisplay.prototype.showZukanGet = function() {
    var self = BattleResultDisplay;

    var d = new Dialogue();

    Page.setParams("list", self.list);
    Page.setParams("zukanget_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(ZukanGetHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * アイテムゲットを表示する
 */
BattleResultDisplay.prototype.showItemGet = function() {
    var self = BattleResultDisplay;

    var d = new Dialogue();

    Page.setParams("list", self.list);
    Page.setParams("battleitemget_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(BattleItemGetHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * リストを更新する
 */
BattleResultDisplay.prototype.refreshList = function(list,list2) {
    var self = BattleResultDisplay;

    // リスト要素とエントリ要素はメンバ変数を使う。
    listId = this.defaultListId;
    entryId = this.defaultEntryId;

    // リスト要素をjQueryで取得。
    var list$ = $("#" + listId);

    // エントリ要素を一つずつ作成・表示していく。
    for(var i = 1, entry ; entry = list[i] ; i++) {
        entry2 = list2[i];

        // エントリを表示するボードを作成。
        var board = this.generateEntryBoard(entry, entry2, entryId);

        // クローンした要素をリストに追加。
        list$.append(board);
    }

    self.onLoaded();
}
//---------------------------------------------------------------------------------------------------------
/**
 * エントリを表示するボードを作成する。
 *
 * @param   エントリのデータ。
 * @param   各エントリとなる要素のid。
 * @return  作成したエントリ要素(jQuery)。
 */
BattleResultDisplay.prototype.generateEntryBoard = function(entry, entry2, entryId) {

    // テンプレートをクローン。
    var board = Juggler.generate(entryId);

    // エントリデータに合わせてセットアップ。
    this.setupEntryBoard(entry, entry2, board);

    return board;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
BattleResultDisplay.prototype.setupEntryBoard = function(entry, entry2, board) {
    var self = BattleResultDisplay;

    var after = entry;
    var before = entry2;

console.log(after)
console.log(before)

    //装備名
    $("[key='item_name']", board).text( after["item_name"] );

    //装備アイコン
    if(before["item_id"] != "")
        $("[key='icon']", board).attr("src", AppUtil.getItemIconURL(before["item_id"]) );
    else
        $("[key='icon']", board).hide();

    if(before.level != after.level){
        //Lvアップ
        $("[key='level_txt']", board).text( "UP" );
        $("[key='level_txt']", board).show();
        $("[key='level']", board).css("color","#ffa4aa");
        $("[key='lv_icon']", board).show();
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/battle_result_panel_lvup.png"));
    }else{
        $("[key='level_txt']", board).text( "" );
        $("[key='level_txt']", board).hide();
        $("[key='lv_icon']", board).hide();
    }

    if(parseInt(after.max_level) <= parseInt(after.level)){
     		//MAXレベル
        $("[key='level_txt']", board).text( "MAX" );
        $("[key='level_txt']", board).show();
        $("[key='level']", board).css("color","#ffa4aa");
        $("[key='lv_icon']", board).show();
    }

 		//レベル
    $("[key='level']", board).text( after.level );

    //耐久値の変化
    var durable_count = before.durable_count;
    if(before.durable_count != after.durable_count){
      durable_count = durable_count + "⇒" + after.durable_count;
    }

    //修理して戻ってきている場合は、その旨表示する
    if(after.repaire != undefined){
    	  durable_count = durable_count + "⇒" +after.repaire;
        $("[key='repaire']", board).html("修理しました");
    }

    $("[key='durability']", board).html(durable_count);

    //修理ボタン
    if(after.repaire_useto != undefined){
        $("[key='btn_repaire']", board).show();
        $("[key='btn_repaire']", board).off("click").on("click", function(){
            sound("se_btn");

            self.destroy();

            //すぐ遷移するとサウンドが鳴らないので・・
            setTimeout(function(){
                window.location.href = after.urlOnRepaire;
            }, 500);
        });

        //ボタンを押してくださいの文言をブリンク
        $(function(){
            timer = setInterval(function(){
                $(".blink").fadeOut(500,function(){$(this).fadeIn(500)});
            }, 1400);
        });

    }else{
        $("[key='btn_repaire']", board).hide();
        $("[key='btn_repaire']", board).off("click");
    }

    if(before.user_item_id == ""){
        $("[key='equip_panel']", board).hide();
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/battle_result_panel_none.png"));
    }else{
        $("[key='equip_panel']", board).show();

        $("[key='att1']", board).text( after["attack1"] );
        $("[key='att2']", board).text( after["attack2"] );
        $("[key='att3']", board).text( after["attack3"] );
        $("[key='spd']", board).text( after["speed"] );

        $("[key='def1']", board).text( after["defence1"] );
        $("[key='def2']", board).text( after["defence2"] );
        $("[key='def3']", board).text( after["defence3"] );
        $("[key='defX']", board).text( after["defenceX"] );

        if(after["attack1"] != before["attack1"])
            $("#att1-icon").show();

        if(after["attack2"] != before["attack2"])
            $("#att2-icon").show();

        if(after["attack3"] != before["attack3"])
            $("#att3-icon").show();

        if(after["speed"] != before["speed"])
            $("#spd-icon").show();

        if(after["defence1"] != before["defence1"])
            $("#def1-icon").show();

        if(after["defence2"] != before["defence2"])
            $("#def2-icon").show();

        if(after["defence3"] != before["defence3"])
            $("#def3-icon").show();

        if(after["defenceX"] != before["defenceX"])
            $("#defX-icon").show();
    }

    //この戦闘で壊れた場合
    if(after.user_item_id == undefined && before.user_item_id != ""){
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/battle_result_panel_dark.png"));
        $("[key='equip_panel']", board).hide();
        $("[key='durability']", board).hide();
        $("[key='lv_icon']", board).hide();
        $("[key='batsu']", board).show();

        $("[key='level_txt']", board).text( "" );
        $("[key='level_txt']", board).hide();
        $("[key='lv_icon']", board).hide();
    }

    //壊れている場合
    if(before.level == undefined){
        $("[key='level_txt']", board).text( "" );
        $("[key='level_txt']", board).hide();
        $("[key='lv_icon']", board).hide();
        $("[key='level']", board).hide();
    }

    self.super.setupEntryBoard.call(self);
}
//---------------------------------------------------------------------------------------------------------
/**
 * サマリーを作成する
 */
BattleResultDisplay.prototype.setSummary = function(summary) {
    var self = BattleResultDisplay;

    $("#"+summary+"P").html(self.list.battle.bias_result.summary[summary]);
    $("#"+summary+"E").html(self.list.battle.rival_result.summary[summary]);

    if(parseInt(self.list.battle.bias_result.summary[summary]) > parseInt(self.list.battle.rival_result.summary[summary])){
        $("#"+summary+"P").css("color", "red");
        $("#"+summary+"E").css("color", "red");
    }else if(parseInt(self.list.battle.bias_result.summary[summary]) < parseInt(self.list.battle.rival_result.summary[summary])){
        $("#"+summary+"P").css("color", "#4090c8");
        $("#"+summary+"E").css("color", "#4090c8");
    }else{
        $("#"+summary+"P").css("color", "#4d994d");
        $("#"+summary+"E").css("color", "#4d994d");
    }
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BattleResultDisplay.prototype.onLoaded = function() {
    var self = BattleResultDisplay;

    //キャラ登場
    self.CharaCanvasBtl.pos(0, 0);
    self.CharaCanvasBtl.in();

    if(self.list.battle.result_detail.get_vcoin){
        self.showVcoin(self.list.battle.result_detail.get_vcoin);
        self.list.battle.result_detail.get_vcoin = false;
    }else if(self.list.levelup){
        self.showLevelUp();
        self.list.levelup = false;
    }else if(self.list.gradeup){
        self.showGradeUp();
        self.list.gradeup = false;
    }else if(self.list.capture_flg == true){
        self.showZukanGet();
        self.list.capture_flg = false;
    }else if(self.list.item_flg == true){
        self.showItemGet();
        self.list.item_flg = false;
    }

    if(self.list.battle.bias_user_id == -1){
        self.changeTab("SUM");
    }

    sound("bgm_mute");

    self.super.onLoaded.call(self);
}


//---------------------------------------------------------------------------------------------------------
/**
 * タブクリックイベントハンドラ
 */
BattleResultDisplay.prototype.onChangeCategory = function (category){
    var self = BattleResultDisplay;

    sound("se_btn");

    self.changeTab(category);
}

//---------------------------------------------------------------------------------------------------------
/**
 * タブを変更する
 */
BattleResultDisplay.prototype.changeTab = function (category){
    var self = BattleResultDisplay;

    if(self.category == category)
        return false;

    //前のタブの選択を削除
    $("#tab-" + self.category).css("display", "none");
    $("#"+self.category+"_panel").hide();

    self.category = category;
    //新しいタブの選択
    $("#tab-" + self.category).css("display", "block");
    $("#"+self.category+"_panel").show();

}


//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BattleResultDisplay.prototype.destroy = function (){
    var self = BattleResultDisplay;

    self.CharaCanvasBtl.out();

    //退場アニメーションが終わったらdestroy
    $("#BattleResultContents").fadeOut("fast", function(){
        self.CharaCanvasBtl.destroy();
        self.ExpGaugeCanvas.destroy();

        self.CharaCanvasBtl = null;
        self.ExpGaugeCanvas = null;

        //self.me.close();

        self.super.destroy.call(self);
        BattleResultDisplay = null;
    });
}

//---------------------------------------------------------------------------------------------------------
var BattleResultDisplay = new BattleResultDisplay();

$(document).ready(BattleResultDisplay.start.bind(BattleResultDisplay));

