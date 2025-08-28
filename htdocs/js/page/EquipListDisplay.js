/**
 * ショップリストを制御するシングルトンオブジェクト。
 * 最初の数字は　マグナ = 1 コイン = 2
 * 次の数字は　アイテム = 1 装備   = 2
 *
 */
function EquipListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(EquipListDisplay.prototype, 'constructor', {
        value : EquipListDisplay,
        enumerable : false
    });

    //スクロール無効
    $("#EquipListContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    this.defaultListId = "equip-list";
    this.defaultEntryId = "equip-list-template";

    this.list = {};
    this.equip_list = {};

    this.player_equip = {};

    this.scroll = undefined;

    this.ClassName = "EquipListDisplay";

    this.category = Page.getParams("equip_category");
    this.mount = 1;

    if(this.category == undefined)
        this.category = 'WPN';

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
EquipListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
EquipListDisplay.prototype.start = function() {
console.log("EquipListDisplay.start rannning...");
    var self = EquipListDisplay;

    //タブの選択
    $("#tab-" + self.category).css("display", "block");

    //装備一覧を取得。
    EquipApi.list(function(list){
        console.log(list);
        self.equip_list = list;
        self.super.start.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * APIを再読み込みする。情報が更新されたらコールすること。
 */
EquipListDisplay.prototype.restart = function() {
console.log("EquipListDisplay.restart rannning...");
    var self = EquipListDisplay;

    self.equip_list = {};

    //装備一覧を取得。
    EquipApi.list(function(list){
        console.log(list);
        self.equip_list = list;
        self.reload();
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
EquipListDisplay.prototype.reload = function (){
    var self = EquipListDisplay;

    $("#navi_serif").html("もっている武器とアイテムなのだ。合成についてはこっちを見るのだ。");

    $("#sync_help").off("click").on("click",function(){
        sound("se_btn");
        self.onHelpShow("item-sync");
    });


    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //現在の装備を初期化
    self.player_equip = {};

    switch(self.category){
        case "HED":
          self.mount = 3;
          mount_str = "頭";
          break;
        case "BOD":
          self.mount = 2;
          mount_str = "服";
          break;
        case "WPN":
          self.mount = 1;
          mount_str = "武器";
          break;
        case "ACS":
          self.mount = 4;
          mount_str = "アクセサリ";
          break;
        case "ITM":
          self.mount = 5;
          mount_str = "アイテム";
          break;
    }

    $.each(self.equip_list['equip'], function(key, value){
        if(key == self.mount)
            self.list = value;
    });

    // 一つもなかったら...
    if(self.list.Num == 0) {
        // その旨のパネルを表示。
        var no = Juggler.generate("no-entry");
        $("#" + self.defaultListId).append(no);

        //スクロールは消す
        if(self.scroll)
            self.scroll.refresh();

        self.onLoaded();

        // 処理はここまで。
        return;
    }

    //装備中の装備をとっておく
    $.each(self.equip_list["PLAEQP"], function(key, value){
        if(key == self.mount)
            self.player_equip = value;
    });

    if(self.player_equip["user_item_id"] == null && self.category != "ITM")
        $("#navi_serif").html("もっている武器とアイテムなのだ。" + mount_str + "になんも装備してないから合成できないのだ。<br>何か装備するのだ");

    // パケットをリストに表示。
    self.refreshList(self.list);
    self.super.reload.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
EquipListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = EquipListDisplay;

    //---------------------------------------------------------------------------------------------------------
    /*
     * 各種情報を画面に表示
     *
    */
    if(self.category == "ITM"){
        $("[key='item_template']", board).show();
        $("[key='equip_template']", board).hide();
    }else{
        $("[key='item_template']", board).hide();
        $("[key='equip_template']", board).show();
        $("[key='rare']", board).attr("src", AppUrl.asset("img/parts/sp/rare_icon_"+entry.rear_level+".png"));
    }

    $("[key='item_name']", board).text( entry["item_name"] );
    $("[key='set_name']", board).text( entry["set_name"] );

    $("[key='durable_count']", board).text( entry["durable_count"] );

    $("[key='hold']", board).text( entry["num"] );

    if(parseInt(entry["level"]) >= parseInt(entry["max_level"]))
        $("[key='level']", board).text( entry["level"] + "[MAX]" );
    else
        $("[key='level']", board).text( entry["level"] );

    $("[key='flavor_text']", board).text( entry["flavor_text"] );
    $("[key='effect']", board).text( entry["effect"] );

    $("[key='att1']", board).text( entry["attack1"] );
    $("[key='att2']", board).text( entry["attack2"] );
    $("[key='att3']", board).text( entry["attack3"] );
    $("[key='spd']", board).text( entry["speed"] );

    $("[key='def1']", board).text( entry["defence1"] );
    $("[key='def2']", board).text( entry["defence2"] );
    $("[key='def3']", board).text( entry["defence3"] );
    $("[key='defX']", board).text( entry["defenceX"] );

    $("[key='image']", board).attr("src", AppUtil.getItemIconURL(entry["item_id"]) );

    $("[key='currency_icon']", board).attr("src", AppUrl.asset("img/parts/sp/"+self.currency+"_icon.png"));

    //---------------------------------------------------------------------------------------------------------
    /*
     * 各種ボタンイベントハンドラ設定
     *
    */

    //---------------------------------------------------------------------------------------------------------
    //捨てるボタン押下時イベントハンドラ
    $("[key='dust_button']", board).off('click').on('click',function() {
        sound("se_btn");

        if(entry["present_flg"] == 0){
            var text = entry["item_name"] + "は捨てられないのだ";
            var d = new Dialogue();

            Page.setParams("pop_d", d);

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(PopupHtml);

            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();

            $("#popup_body").html(text);

            setTimeout(function(){
                $("#popup-close").off('click').on('click',function() {
                    sound("se_btn");
                    PopupDisplay.destroy();
                });
            }, 200);
        }else{
            var d = new Dialogue();

            Page.setParams("popup_d", d);

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(PopupConfirmHtml);

            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();

            var text = entry["item_name"] + "を捨てるのだ？";

            if(entry["num"] > 1){
                text += "<br>一つだけじゃなくて" + entry["num"] + "個全部捨てるのだ。"
            }

            $("#confirm_body").html(text);
            setTimeout(function(){
                $("#popupconf-ok").off('click').on('click',function() {
                    sound("se_btn");

                    EquipApi.discard(entry["user_item_id"],function(response){
                        console.log(response);
                        var text = "";
                        if(response["result"] == "ok"){
                            text = entry["item_name"] +  "を捨てたのだ"
                        }else{
                            switch(response["err_code"]){
                                case "equipping":
                                    text = "装備中のものは捨てられないのだ";
                                    break;
                                case "forbidden":
                                    text = "それ捨てちゃﾀﾞﾒなのだ｡<br>なんでもﾎﾟｲﾎﾟｲやっちゃﾀﾞﾒなのだ";
                                    break;
                            }
                        }
                        var d = new Dialogue();

                        Page.setParams("pop_d", d);

                        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
                        d.content(PopupHtml);

                        d.autoClose = false;
                        d.veilClose = false;
                        d.opacity = 0.5;

                        d.show();

                        $("#popup_body").html(text);

                        $("#popup-close").off('click').on('click',function() {
                            sound("se_btn");
                            PopupDisplay.destroy();

                            if(response["result"] == "ok")
                                self.restart();
                        });

                        PopupConfirmDisplay.destroy();
                    });

                });
            }, 200);

        }

    });

    //---------------------------------------------------------------------------------------------------------
    //装備ボタン押下時イベントハンドラ
    $("[key='equip_button']", board).off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("popup_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(PopupConfirmHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();

        var text = entry["item_name"] + "を装備するのだ？";

        $("#confirm_body").html(text);

        $("#popupconf-ok").off('click').on('click',function() {
            sound("se_btn");

            EquipApi.change(null, entry["user_item_id"],null,self.mount, function(response){
                console.log(response);
                var text = "";
                if(response["result"] == "ok"){
                    text = entry["item_name"] +  "を装備したのだ"
                }else{
                    switch(response["result"]){
                        case "noitem":
                            text = "アイテムがないのだ";
                            break;
                        case "equipping":
                            text = "装備中なのだ";
                            break;
                        case "maxlevel":
                            text = "レベルがこれ以上上がらないのだ";
                            break;
                        case "nomoney":
                            text = "マグナがないのだ。地に足をつけるのだ・・。";
                            break;
                        case "in_quest":
                            text = "クエスト中は装備の変更はできないのだ。";
                            break;
                        case "not_me":
                            text = "誰の装備を変更しようとしてるのだ・・？";
                            break;
                    }
                }
                var d = new Dialogue();

                Page.setParams("pop_d", d);

                d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
                d.content(PopupHtml);

                d.autoClose = false;
                d.veilClose = false;
                d.opacity = 0.5;

                d.show();

                $("#popup_body").html(text);

                $("#popup-close").off('click').on('click',function() {
                    sound("se_btn");
                    PopupDisplay.destroy();

                    if(response["result"] == "ok")
                        self.restart();
                });

                PopupConfirmDisplay.destroy();
            });
        });
    });

    //---------------------------------------------------------------------------------------------------------
    //合成ボタン押下時イベントハンドラ
    $("[key='sync_button']", board).off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("PLAEQP", self.equip_list["PLAEQP"]);
        Page.setParams("sync_entry", entry);
        Page.setParams("syncconfirm_d", d);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(SyncConfirmHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();
    });

    //---------------------------------------------------------------------------------------------------------
    //使用ボタン押下時イベントハンドラ
    $("[key='use_button']", board).off('click').on('click',function() {
        sound("se_btn");

        if(entry["item_type"] == ITEM_REPAIRE){
            //修理アイテムは対象装備を選択する。
            var d = new Dialogue();

            Page.setParams("item_picker_entry", entry);
            Page.setParams("item_picker_d", d);

            d.appearance('<div><div key="dialogue-content"></div></div>');
            d.content(ItemPickerHtml);

            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();
        }else{
            var d = new Dialogue();

            Page.setParams("item_use_entry", entry);
            Page.setParams("item_use_d", d);

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(ItemUseHtml);

            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();
        }
    });

    //---------------------------------------------------------------------------------------------------------
    /*
     * ボタン表示/非表示切り替え
     *
    */
    //装備中の場合
    if(self.player_equip["user_item_id"] == entry["user_item_id"]){
        //捨てるボタン非活性＋イベント削除
        if(self.category == "ITM")
            AppUtil.disableButton($("[key='dust_button']", board), "150_66");
        else
            AppUtil.disableButton($("[key='dust_button']", board), "174_66");

        $("[key='dust_button']", board).off('click');

        //装備ボタンダーク化＋イベント削除
        AppUtil.darkButton($("[key='equip_button']", board), "174_66");
        $("[key='equip_button']", board).find("div").html("装備中");
        $("[key='equip_button']", board).off('click');

        //装備合成ボタン非活性＋イベント削除
        AppUtil.disableButton($("[key='sync_button']", board), "174_66");
        $("[key='sync_button']", board).off('click');
    }

    //現在の装備が無い場合
    if(self.player_equip["user_item_id"] == null){
        //装備合成ボタン非活性＋イベント削除
        AppUtil.disableButton($("[key='sync_button']", board), "174_66");
        $("[key='sync_button']", board).off('click');
    }

    //使用できないアイテムの場合
    if(entry["useable"] == 0){
        //使用ボタン非活性＋イベント削除
        AppUtil.disableButton($("[key='use_button']", board), "150_66");
        $("[key='use_button']", board).off('click');
    }

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
EquipListDisplay.prototype.onLoaded = function() {
    var self = EquipListDisplay;

    $("#main_contents").fadeIn("fast", function(){
        //記事のスクロール表示
        if(self.scroll){
            self.scroll.refresh();
        }else{
            self.scroll = new IScroll('#EquipListContents .scrollWrapper', {
                click: true,
                scrollbars: 'custom', /* スクロールバーを表示 */
                //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
                //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
                //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
                mouseWheel: false
            });
        }
        self.super.onLoaded.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * タブ切り替え時に呼び出される。
 */
EquipListDisplay.prototype.onChangeCategory = function(category) {
    var self = EquipListDisplay;

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
/**
 * ページのオブジェクトを破棄する。
 */
EquipListDisplay.prototype.destroy = function (){
    var self = EquipListDisplay;

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();
        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var EquipListDisplay = new EquipListDisplay();

$(document).ready(EquipListDisplay.start.bind(EquipListDisplay));

