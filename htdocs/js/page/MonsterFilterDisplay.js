
/**
 * 汎用ポップアップを制御するシングルトンオブジェクト。
 *
 */
function MonsterFilterDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MonsterFilterDisplay.prototype, 'constructor', {
        value : MonsterFilterDisplay,
        enumerable : false
    });

    this.defaultListId = "filter-list";
    this.defaultEntryId = "filter-template";

    this.me = Page.getParams("filter_d");

    this.list = $();
    this.monster_list = Page.getParams("filter_list");
    this.category = Page.getParams("category");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MonsterFilterDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MonsterFilterDisplay.prototype.start = function() {
console.log("MonsterFilterDisplay.start rannning...");
    var self = MonsterFilterDisplay;

    $("#filter_navigator").html(self.monster_list["title"] + " ＞ " + self.monster_list["tab_list"][self.category]);

    //キャンセルボタンクリック時イベントハンドラ
    $("#btn_calcel").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });
    $.each(self.monster_list.tab_list, function(key,value){
        self.list = self.list.add({"kind": key, "text":value});
    });
    // パケットをリストに表示。
    self.refreshList(self.list);

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
MonsterFilterDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = MonsterFilterDisplay;
console.log(entry);

    if(self.category == entry.kind)
        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/monster_filter_selected.png") );

    $("[key='btn']", board).off('click').on('click',function() {
        sound("se_btn");

        $("[key='panel']", board).attr("src", AppUrl.asset("img/parts/sp/monster_filter_selected.png") );
        Page.setParams("category", entry.kind);
        MonsterListDisplay.category = entry.kind;
        MonsterListDisplay.reload();
        self.destroy();
    });

    $("[key='text']", board).text( entry.text );

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MonsterFilterDisplay.prototype.reload = function (){
    self = MonsterFilterDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MonsterFilterDisplay.prototype.onLoaded = function() {
    var self = MonsterFilterDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
MonsterFilterDisplay.prototype.destroy = function (){
    var self = MonsterFilterDisplay;

    self.me.close();
    self.super.destroy.call(self);
    MonsterFilterDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var MonsterFilterDisplay = new MonsterFilterDisplay();

$(document).ready(MonsterFilterDisplay.start.bind(MonsterFilterDisplay));

