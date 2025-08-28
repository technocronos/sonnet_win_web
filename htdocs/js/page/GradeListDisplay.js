
/**
 * 階級表を画面制御するシングルトンオブジェクト。
 *
 */
function GradeListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(GradeListDisplay.prototype, 'constructor', {
        value : GradeListDisplay,
        enumerable : false
    });

    this.defaultListId = "gradelist-list";
    this.defaultEntryId = "gradelist-template";

    this.response = $();
    this.list = $();

    this.me = Page.getParams("gradelist_d");
    this.screen = Page.getParams("screen");

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
GradeListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
GradeListDisplay.prototype.start = function() {
console.log("GradeListDisplay.start rannning...");
    var self = GradeListDisplay;

    if(self.screen ==""){
        self.screen = "status";
    }

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
GradeListDisplay.prototype.reload = function (){
    var self = GradeListDisplay;

    $("#navi_serif").html("階級表なのだ");

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //キャンセルボタンクリック時イベントハンドラ
    $("#grade_list_close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    GradeApi.list(function(response){
        console.log(response);
        self.response = response;
        self.list = response.list;

        // 一つもなかったら...
        if(self.list.length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);
            //スクロールは消す
            if(self.scroll)
                self.scroll.refresh();

            // 処理はここまで。
            return;
        }

        // パケットをリストに表示。
        self.refreshList(self.list);

        self.super.reload.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
GradeListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = GradeListDisplay;

    $("[key='grade_name']", board).text( entry["grade_name"] );    
    $("[key='distribute']", board).text( self.response["distribute"][entry["grade_id"]] );

    $("[key='btn']", board).off('click').on('click',function() {
        sound("se_btn");

        var d = new Dialogue();

        Page.setParams("gradeId", entry["grade_id"]);
        Page.setParams("gradeuser_d", d);
        Page.setParams("screen", self.screen);

        d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
        d.content(GradeUserHtml);

        d.autoClose = false;
        d.veilClose = false;
        d.opacity = 0.5;

        d.show();

    });

    self.super.setupEntryBoard.call(self);
}
//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
GradeListDisplay.prototype.onLoaded = function() {
    var self = GradeListDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#GradeListContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
GradeListDisplay.prototype.destroy = function (){
    var self = GradeListDisplay;

    self.me.close();
    self.super.destroy.call(self);
    GradeListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var GradeListDisplay = new GradeListDisplay();

$(document).ready(GradeListDisplay.start.bind(GradeListDisplay));

