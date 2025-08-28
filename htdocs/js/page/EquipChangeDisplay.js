/**
 * ショップリストを制御するシングルトンオブジェクト。
 * 最初の数字は　マグナ = 1 コイン = 2
 * 次の数字は　アイテム = 1 装備   = 2
 *
 */
function EquipChangeDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(EquipChangeDisplay.prototype, 'constructor', {
        value : EquipChangeDisplay,
        enumerable : false
    });

    this.defaultListId = "equip-change-list";
    this.defaultEntryId = "equip-change-template";

    this.me = Page.getParams("me");

    this.list = $();
    this.equip_list = $();

    this.player_equip = $();

    this.scroll = undefined;

    this.ClassName = "EquipChangeDisplay";

    this.category = Page.getParams("equip_category");
    this.mount = 1;

    if(this.category == undefined)
        this.category = 'WPN';

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
EquipChangeDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
EquipChangeDisplay.prototype.start = function() {
console.log("EquipChangeDisplay.start rannning...");
    var self = EquipChangeDisplay;

    $("#window_bg").html(Page.preload_image.itempicker_window);

    //装備一覧を取得。
    EquipApi.list(function(list){
        console.log(list);
        self.equip_list = list;
        self.super.start.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
EquipChangeDisplay.prototype.reload = function (){
    var self = EquipChangeDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //現在の装備を初期化
    self.player_equip = {};

    switch(self.category){
        case "HED":
          self.mount = 3;
          mount_str = "頭";
          $("#tab").attr("src", AppUrl.asset("img/parts/sp/equip_tab_hed_selected.png"));
          break;
        case "BOD":
          self.mount = 2;
          mount_str = "服";
          $("#tab").attr("src", AppUrl.asset("img/parts/sp/equip_tab_bod_selected.png"));
          break;
        case "WPN":
          self.mount = 1;
          mount_str = "武器";
          $("#tab").attr("src", AppUrl.asset("img/parts/sp/equip_tab_wpn_selected.png"));
          break;
        case "ACS":
          self.mount = 4;
          mount_str = "アクセサリ";
          $("#tab").attr("src", AppUrl.asset("img/parts/sp/equip_tab_acs_selected.png"));
          break;
    }

    //装備中の装備をとっておく
    $.each(self.equip_list["PLAEQP"], function(key, value){
        if(key == self.mount)
            self.player_equip = value;
    });

    $.each(self.equip_list['equip'], function(key, value){
        if(key == self.mount){
            if(self.player_equip["user_item_id"] != null){
                self.list = self.list.add(self.player_equip);
                $.each(value, function(k,v){
                    if(typeof(v) === 'object' || v instanceof Array){
                        if(self.player_equip["user_item_id"] != v["user_item_id"])
                            self.list = self.list.add(v);
                    }
                });
            }else{
                self.list = value;
            }
        }
    });

console.log(self.list)

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

    // パケットをリストに表示。
    self.refreshList(self.list);
    self.super.reload.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
EquipChangeDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = EquipChangeDisplay;

    //---------------------------------------------------------------------------------------------------------
    /*
     * 各種情報を画面に表示
     *
    */
    if(self.category == "ITM"){
        $("#item_template").show();
        $("#equip_template").hide();
    }else{
        $("#item_template").hide();
        $("#equip_template").show();
    }

    $("[key='item_name']", board).text( entry["item_name"] );
    $("[key='set_name']", board).text( entry["set_name"] );

    $("[key='durable_count']", board).text( entry["durable_count"] );

    $("[key='hold']", board).text( entry["num"] );
    if(parseInt(entry["level"]) >= parseInt(entry["max_level"]))
        $("[key='level']", board).text( entry["level"] + "[MAX]");
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

    $("[key='rare']", board).attr("src", AppUrl.asset("img/parts/sp/rare_icon_"+entry.rear_level+".png"));

    //---------------------------------------------------------------------------------------------------------
    /*
     * 各種ボタンイベントハンドラ設定
     *
    */

    //---------------------------------------------------------------------------------------------------------
    //捨てるボタン押下時イベントハンドラ
    $("[key='dust_button']", board).off('click').on('click',function() {
        sound("se_btn");

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
                    //flashに通知
                    MypageDisplay.changeReload();
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
                    });
                }
                PopupConfirmDisplay.destroy();
                self.destroy();
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

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
EquipChangeDisplay.prototype.onLoaded = function() {
    var self = EquipChangeDisplay;

    //キャンセルボタンクリック時イベントハンドラ
    $("#equip-change-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#EquipChangeContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * タブ切り替え時に呼び出される。
 */
EquipChangeDisplay.prototype.onChangeCategory = function(category) {
    var self = EquipChangeDisplay;

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
EquipChangeDisplay.prototype.destroy = function (){
    var self = EquipChangeDisplay;

    self.me.close();
    self.super.destroy.call(self);
    EquipChangeDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var EquipChangeDisplay = new EquipChangeDisplay();

$(document).ready(EquipChangeDisplay.start.bind(EquipChangeDisplay));

