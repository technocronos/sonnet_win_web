
/**
 * モンスター図鑑を制御するシングルトンオブジェクト。
 *
 */
function MonsterListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MonsterListDisplay.prototype, 'constructor', {
        value : MonsterListDisplay,
        enumerable : false
    });

    this.defaultListId = "monster-list";
    this.defaultEntryId = "monster-template";

    this.category = Page.getParams("category");

    this.list = {};
    this.scroll = undefined;

    this.response = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MonsterListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MonsterListDisplay.prototype.start = function() {
console.log("MonsterListDisplay.start rannning...");
    var self = MonsterListDisplay;

    tab_list = $(".tabs");
    tab_list.empty();

    //タブを作成する。
    MonsterApi.list(self.category, function(response){
        console.log(response);

        self.response = response;

        var i = 1;
        //タブ作成
        $.each(self.response["tab_list"], function(key, val){
            var dom = $("<div>");
            dom.addClass("tab-element");
            if(i == 1){
                dom.addClass("selected");
                //デフォルト値
                self.category = key;
            }
            dom.attr("id","tab-" + key);
            dom.html(val);
            //タブに追加
            tab_list.append(dom);

            //タブクリック時イベントハンドラ
            $("#" + "tab-" + key).off('click').on('click',function() {
                sound("se_btn");
                self.onChangeTab(key);
            });

            i++;
        });

        //絞り込みボタンクリック時イベントハンドラ
        $("#btn_filter").off('click').on('click',function() {
            sound("se_btn");

            var d = new Dialogue();

            Page.setParams("category", self.category);
            Page.setParams("filter_list", self.response);
            Page.setParams("filter_d", d);

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(MonsterFilterHtml);
            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();
        });

        //okボタンクリック時イベントハンドラ
        $("#dialogue-close").off('click').on('click',function() {
            sound("se_btn");
            MainContentsDisplay.FooterCanvas.out("zukan","zukan_list");
        });


        self.super.start.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MonsterListDisplay.prototype.reload = function (){
    self = MonsterListDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //タイトル設定
    MainContentsDisplay.HeaderCanvas.caption(self.response["title"]);
    MainContentsDisplay.HeaderCanvas.init();
    MainContentsDisplay.HeaderCanvas.in();

    $("#navigator").html(self.response["title"] + " ＞ " + self.response["tab_list"][self.category]);

    self.column = self.response["field"];
    if(self.response["field"] == "appearance")
        self.column = "appearance_area";

    //list作成
    var monster = $();
    $.each(self.response["list"]["resultset"], function(key, val){
        //カテゴリで絞り込む
        if(self.response["field"] != "terminate"){
            if(val[self.column] == self.category)
                monster = monster.add(val);
        }else{
            monster = monster.add(val);
        }
    });

    if(self.response["field"] == "terminate")
        $("#monster_text").html(self.response["flavor"]);
    else
        $("#monster_text").html(self.response["flavor"][self.category]);

    // パケットをリストに表示。
    self.refreshList(monster);

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
MonsterListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = MonsterListDisplay;

    if(entry["terminate_at"]){
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/monster_panel"+entry.rare_level+".png") );
        $("[key='image']", board).attr("src", AppUrl.asset("img/chara/" + entry.image_url) );

        $("[key='btn']", board).off('click').on('click',function() {
            sound("se_btn");

            var d = new Dialogue();

            Page.setParams("monster", entry);
            Page.setParams("field", self.response["category_text"][entry.category]);
            Page.setParams("monster_detail_d", d);

            d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
            d.content(MonsterDetailHtml);
            d.autoClose = false;
            d.veilClose = false;
            d.opacity = 0.5;

            d.show();
        });
    }else{
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/monster_panel0.png") );
    }

    $("[key='monster_no']", board).text( entry["monster_no"] );

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MonsterListDisplay.prototype.onLoaded = function() {
    var self = MonsterListDisplay;

    $("#main_contents").fadeIn("fast", function(){
        //記事のスクロール表示
        if(self.scroll){
            self.scroll.refresh();
        }else{
            self.scroll = new IScroll('#MonsterListContents .scrollWrapper', {
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
MonsterListDisplay.prototype.onChangeTab = function(category) {
    sound("se_btn");

    //前のタブの選択を削除
    $("#tab-" + this.category).removeClass("selected");
    this.category = category;
    //新しいタブの選択
    $("#tab-" + this.category).addClass("selected");

    this.reload();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
MonsterListDisplay.prototype.destroy = function (){
    var self = MonsterListDisplay;

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();
        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var MonsterListDisplay = new MonsterListDisplay();

$(document).ready(MonsterListDisplay.start.bind(MonsterListDisplay));

