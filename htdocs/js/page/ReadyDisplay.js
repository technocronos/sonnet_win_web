
/**
 * クエスト準備を制御するシングルトンオブジェクト。
 *
 */
function ReadyDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(ReadyDisplay.prototype, 'constructor', {
        value : ReadyDisplay,
        enumerable : false
    });

    this.defaultListId = "ready-list";
    this.defaultEntryId = "ready-template";

    this.entry = Page.getParams("ready_entry");
    this.me = Page.getParams("ready_d");

    this.response = {};
    this.list = {};
    this.scroll = undefined;

    this.category = Page.getParams("ready_category");

    if(this.category == undefined)
        this.category = 'RCV';

    this.slot = {};

    this.category_num = {};

    this.category_num["RCV"] = 1;
    this.category_num["ATT"] = 2;
    this.category_num["EQP"] = 3;

    this.slot[this.category_num["RCV"]] = $();
    this.slot[this.category_num["ATT"]] = $();
    this.slot[this.category_num["EQP"]] = $();

    this.RCV_LIMIT = 6;
    this.ATT_LIMIT = 6;
    this.EQP_LIMIT = 2;

    this.slot_count = [];

    this.slot_count["RCV"] = 0;
    this.slot_count["ATT"] = 0;
    this.slot_count["EQP"] = 0;

    this.NaviCanvas = null;
    this.navi_speak = false;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
ReadyDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
ReadyDisplay.prototype.start = function() {
console.log("ReadyDisplay.start rannning...");
    var self = ReadyDisplay;

    var content_height = parseInt($("#ReadyContents").css("height"));
    var ratio = $(window).width() / 750;

    if(is_tablet != "tablet")
        $("#ready_head").css("height", ((content_height * 0.1) / ratio) + "px");
    else
        $("#ready_head").css("height", ((content_height * 0.05) / ratio) + "px");

    $("#ready_foot").css("height", ((content_height * 0.1) / ratio) + "px");

    var ready_middle_h = ((content_height * 0.7) / ratio);

    if(ready_middle_h <= 900){
        //全体を縮小
        if(is_tablet != "tablet"){
            $("#ready_middle").css("height", "900px");
            $("#ready_middle").css("transform", "scale(" + ready_middle_h / 900 + ")")
        }else{
            $("#ready_middle").css("height", "780px");
            $("#ready_middle").css("transform", "scale(" + 700 / 900 + ")")
        }
    }else{
        $("#ready_middle").css("height", ready_middle_h + "px");
    }


    sound_stop();

    //タブの選択
    $("#tab-" + self.category).css("display", "block");

    //タブを作成する。
    QuestApi.ready(self.entry.quest_id, self.entry.place_id, self.entry.consume_pt, null, function(response){
        console.log(response);

        sound("bgm_dungeon");

        self.response = response;

        $("#bg_image").html(Page.preload_image.circle_bg);

        $("#RCV_TAKE").html(self.RCV_LIMIT);
        $("#ATT_TAKE").html(self.ATT_LIMIT);
        $("#EQP_TAKE").html(self.EQP_LIMIT);

        self.super.start.call(self);

    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
ReadyDisplay.prototype.reload = function (){
    var self = ReadyDisplay;


    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    self.list = self.response["item"][self.category];
console.log(self.list);

    //戻るボタンクリック時イベントハンドラ
    $("#ready-cancel").off('click').on('click',function() {
        sound("se_btn");
        sound("bgm_menu");
        self.destroy();
    });

    //出発ボタンイベントハンドラ
    $("#ready-go").off('click').on('click',function() {
        sound("se_btn");

        sound_stop();

        var slot = {};
        var counter = 0;
        $.each(self.slot, function(key, value){
            $.each(value, function(key2, value2){
                slot["slot" + counter] = value2.user_item_id;
                counter++;
            });
        });

        console.log(slot);

        if(counter == 0)
            slot["slot0"] = "";

        //クエストへ行く。制御は戻ってこない
        QuestApi.ready(self.entry.quest_id, self.entry.place_id, self.entry.consume_pt, slot, function(response){
            console.log(response);
        });
    });

    // 一つもなかったら...
    if(self.list.length == 0) {
        // その旨のパネルを表示。
        var no = Juggler.generate("no-entry");
        $("#" + self.defaultListId).append(no);

        self.onLoaded();

        if(self.scroll)
            self.scroll.refresh();

        // 処理はここまで。
        return;
    }

    // パケットをリストに表示。
    self.refreshList(self.list);

    self.super.reload.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
ReadyDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = ReadyDisplay;

    //カテゴリをRCV,ATT,EQPに翻訳する
    if(self.category == "RCV" || self.category == "ATT")
        var category = self.category;
    else
        var category = "EQP";

    if(entry.category == "ITM"){
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/ready_item_panel.png") );
        $("[key='item_panel']", board).show();
        if(category == "RCV"){
            $("[key='item_value_text']", board).html("回復:");
        }else{
            $("[key='item_value_text']", board).html("攻撃:");
        }
    }else{
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/ready_weapon_panel.png") );
        $("[key='weapon_panel']", board).show();
    }

    $("[key='icon']", board).attr("src", AppUtil.getItemIconURL(entry["item_id"]) );

    $("[key='item_name']", board).text( entry["item_name"] );
    $("[key='has_count']", board).text( entry["num"] );

    $("[key='item_value']", board).text( entry["item_value"] );
    $("[key='item_spread']", board).text( (parseInt(entry["item_spread"]) + 1) );
    $("[key='item_limitation']", board).text( entry["item_limitation"] );

    $("[key='level']", board).text( entry["level"] );
    $("[key='durability']", board).text( entry["durability"] );

    $("[key='att1']", board).text( entry["attack1"] );
    $("[key='att2']", board).text( entry["attack2"] );
    $("[key='att3']", board).text( entry["attack3"] );
    $("[key='spd']", board).text( entry["speed"] );

    $("[key='def1']", board).text( entry["defence1"] );
    $("[key='def2']", board).text( entry["defence2"] );
    $("[key='def3']", board).text( entry["defence3"] );
    $("[key='defX']", board).text( entry["defenceX"] );

    //持ち出し選択個数を調べる
    var item_count = 0;
    $.each(self.slot[self.category_num[category]], function(key, value){
        if(value.item_id == entry.item_id){
            item_count++;
        }
    });

    //スロットにあるものがある場合、状態を反映する
    if(item_count > 0){
        //キャンセルボタン表示
        $("[key='cancel']", board).show();

        //個別持ち出し個数パネル表示
        $("[key='take_count_panel']", board).show();

        //持っている個数を反映
        $("[key='has_count']", board).text( entry.num -  item_count);

        //持ち出し個数を反映
        $("[key='take_count']", board).html(item_count);

        //個別リミットが来た場合は非活性
        if(item_count >= entry.num){
            //非活性にする
            $("[key='gray_panel']", board).show();
        }

        if(entry.category == "ITM"){
            $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/ready_item_panel_on.png") );
        }else{
            $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/ready_weapon_panel_on.png") );
        }
    }

    //セルクリックイベントハンドラ
    $("[key='cell']", board).off('click').on('click',function() {
        sound("se_btn");

        //アイテム追加
        self.onItemPick(category, entry, board);

    });

    //キャンセルボタンクリックイベントハンドラ
    $("[key='cancel']", board).off('click').on('click',function() {
        sound("se_btn");

        var clone = self.slot.clone();
        self.slot[self.category_num[category]] = $();

        $.each(clone[self.category_num[category]], function(key, value){
            if(value.item_id != entry.item_id){
                self.slot[self.category_num[category]] = self.slot[self.category_num[category]].add(value);
            }else{
                //スロットの数を戻す
                self.slot_count[category]--;
            }
        });

        //持ち出し個数反映
        $("#" + category+"_TAKE").html(eval("self."+category+"_LIMIT") - self.slot_count[category]);

        self.reload();
    });

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * セルクリック時イベントハンドラ
 */
ReadyDisplay.prototype.onItemPick = function(category, _entry, board) {
    var self = ReadyDisplay;

    var entry = _entry.clone();

    //全体リミットでないのであれば追加
    if(eval("self."+category+"_LIMIT") > self.slot_count[category]){
        //そのアイテムを持ち出せるかどうか
        var item_count = 0;
        $.each(self.slot[self.category_num[category]], function(key, value){
              if(value.item_id == entry.item_id)
                  item_count++;
        });

        //持ち出し個数にまだ余裕がある
        if(item_count < entry.num){

            //レコード追加
            self.slot[self.category_num[category]] = self.slot[self.category_num[category]].add(entry);

            //スロットの数を増やす
            self.slot_count[category]++;
            item_count++;

            //持ち出し個数反映
            $("#" + category+"_TAKE").html(eval("self."+category+"_LIMIT") - self.slot_count[category]);

            //キャンセルボタン表示
            $("[key='cancel']", board).show();

            //個別持ち出し個数パネル表示
            $("[key='take_count_panel']", board).show();

            //持っている個数を反映
            $("[key='has_count']", board).text( entry.num -  item_count);

            //持ち出し個数を反映
            $("[key='take_count']", board).html(item_count);

            //個別リミットが来た場合は非活性
            if(item_count >= entry.num){
                //非活性にする
                $("[key='gray_panel']", board).show();
            }

            if(entry.category == "ITM"){
                $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/ready_item_panel_on.png") );
            }else{
                $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/ready_weapon_panel_on.png") );
            }

        }
    }

console.log(self.slot[self.category_num[category]]);

}

//---------------------------------------------------------------------------------------------------------
/**
 * タブ切り替え時に呼び出される。
 */
ReadyDisplay.prototype.onChangeCategory = function(category) {
    var self = ReadyDisplay;

    if(self.category == category)
        return false;

    sound("se_btn");

    //前のタブの選択を削除
    $("#tab-" + self.category).css("display", "none");
    self.category = category;
    //新しいタブの選択
    $("#tab-" + self.category).css("display", "block");

    self.reload();
}


//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
ReadyDisplay.prototype.onLoaded = function() {
    var self = ReadyDisplay;

    $("#ReadyContents").show();

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#ReadyContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });
    }

    if(self.response.comment.length > 0 && self.navi_speak == false){
        var summary = Page.getSummary();
        summary.opening = self.response.comment;
        summary.openingNum = self.response.comment.length;
        summary.end_function = "ReadyDisplay.tutorial_ready_navi_speak_end";

        //navi作成
        self.NaviCanvas= new NaviCanvas(self,"_ready",summary);

        var timer = null;
        $(function(){
            timer = setInterval(function(){
                if(self.NaviCanvas.loaded){
                    //loading..表示タイマーストップ
                    clearInterval(timer);
                    //ナビ開始
                    $("#navi_panel_ready").show();
                    self.NaviCanvas.appear();
                    self.navi_speak = true;
                }
            },500);
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ナビしゃべった後Flashよりコールされる
 */
ReadyDisplay.prototype.tutorial_ready_navi_speak_end = function (){
    var self = ReadyDisplay;

    //ナビ退場
    setTimeout(function(){
        $("#navi_panel_ready").hide();
        self.NaviCanvas.destroy();
    }, 1000);
}
//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
ReadyDisplay.prototype.destroy = function (){
    var self = ReadyDisplay;

    Page.setParams("ready_entry", null);
    Page.setParams("ready_me", null);

    self.me.close();

    self.super.destroy.call(self);
    ReadyDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var ReadyDisplay = new ReadyDisplay();

$(document).ready(ReadyDisplay.start.bind(ReadyDisplay));

