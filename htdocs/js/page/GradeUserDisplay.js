
/**
 * 階級別ユーザー一覧画面を制御するシングルトンオブジェクト。
 *
 */
function GradeUserDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(GradeUserDisplay.prototype, 'constructor', {
        value : GradeUserDisplay,
        enumerable : false
    });

    this.defaultListId = "gradeuser-list";
    this.defaultEntryId = "gradeuser-template";

    this.scroll_loading = true;

    this.list = $();
    this.grade = $();

    this.gradeId = Page.getParams("gradeId");
    this.me = Page.getParams("gradeuser_d");
    this.screen = Page.getParams("screen");

    this.scroll = undefined;
  
    this.nolist = false
    this.page = 0;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
GradeUserDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
GradeUserDisplay.prototype.start = function() {
console.log("GradeUserDisplay.start rannning...");
    var self = GradeUserDisplay;

    if(self.screen ==""){
        self.screen = "status";
    }

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
GradeUserDisplay.prototype.reload = function (){
    var self = GradeUserDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //キャンセルボタンクリック時イベントハンドラ
    $("#grade_user_close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    GradeApi.user(self.gradeId, self.page, function(response){
        console.log(response);

        $("#navi_serif_grade_user").html(response.grade.grade_name + "な奴らなのだ");

        self.grade = response.grade;
        self.list = response.list.resultset;

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
GradeUserDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = GradeUserDisplay;

    //ユーザー名
    //$("[key='user_name']", board).text( entry["user"]["name"] );
    //ユーザーアイコン
    $("[key='icon']", board).attr("src", AppUrl.asset("img/chara/" + entry.imageUrl) );

    $("[key='member']", board).text( entry["member"] );
    $("[key='player_name']", board).text( entry["player_name"] );
    $("[key='level']", board).text( entry["level"] );
    $("[key='grade']", board).text( entry["grade_name"] );

    //HPゲージ作成
    var gauge_width = ((entry.hp / entry.hp_max) * 277);
    $("[key='hp_gauge_bar']", board).css("width", gauge_width + "px");
    $("[key='hp_text']", board).html(parseInt(entry.hp) + "/" + parseInt(entry.hp_max));

    $("[key='att1']", board).text( entry["total_attack1"] );
    $("[key='att2']", board).text( entry["total_attack2"] );
    $("[key='att3']", board).text( entry["total_attack3"] );
    $("[key='spd']", board).text( entry["total_speed"] );

    $("[key='def1']", board).text( entry["total_defence1"] );
    $("[key='def2']", board).text( entry["total_defence2"] );
    $("[key='def3']", board).text( entry["total_defence3"] );
    $("[key='defX']", board).text( entry["total_defenceX"] );

    if(chara_user_id != entry.user_id){
        //対戦ボタンクリック時イベントハンドラ
        $("[key='battle_button']", board).off("click").on("click",function(){
            sound('se_btn');

            Page.setParams("his_user_id", entry.user_id);

            $("#btn_hed").off('click');
            $("#btn_wpn").off('click');
            $("#btn_acs").off('click');
            $("#btn_acs").off('click');
            $("#btn_change").off('click');
            $("#btn_cloth_out").off('click');

            GradeListDisplay.destroy();
            self.destroy();

            MainContentsDisplay.FooterCanvas.out("his_page", self.screen);
        });
    }else{
        $("[key='battle_button']", board).off("click");
        $("[key='battle_button']", board).hide();
    }

    self.super.setupEntryBoard.call(self);
}
//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
GradeUserDisplay.prototype.onLoaded = function() {
    var self = GradeUserDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#GradeUserContents .scrollWrapper', {
            click: true,
            scrollbars: 'custom', /* スクロールバーを表示 */
            //fadeScrollbars: true, /* スクロールバーをスクロール時にフェードイン・フェードアウト */
            //interactiveScrollbars: true, /* スクロールバーをドラッグできるようにする */
            //shrinkScrollbars: 'scale', /* スクロールバーを伸縮 */
            mouseWheel: false
        });

        //---------------------------------------------------------------------------------------------------------
        /*
         * スクロールバーが終端に来たら残りのデータを読み込む
        */
        self.scroll.on('scrollEnd', function () {
            console.log("onScrollEnd.." + this.y);

            if(this.maxScrollY == this.y && self.nolist == false){
                self.showScrollLoading(self.scroll);

                self.page++;
                GradeApi.user(self.gradeId, self.page, function(response){
                    console.log(response);

                    // 一つもなかったら...
                    if(response["list"]["resultset"].length == 0) {
                        self.nolist = true;
                        self.hideScrollLoading(self.scroll);
                        return;
                    }

                    //キーを既存のものとマージする
                    var newKey = self.list.length;
                    $.each(response["list"]["resultset"], function(key, value){
                        self.list[newKey] = value;
                        newKey++;
                    });

                    console.log(self.list);

                    // レコードをリストに追加表示。
                    self.refreshList(response["list"]["resultset"],false);
                    self.hideScrollLoading(self.scroll);
                });
            }else{
                self.showScrollLoading(self.scroll);
                self.hideScrollLoading(self.scroll);
            }
        });
    }

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
GradeUserDisplay.prototype.destroy = function (){
    var self = GradeUserDisplay;

    self.me.close();
    self.super.destroy.call(self);
    GradeUserDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var GradeUserDisplay = new GradeUserDisplay();

$(document).ready(GradeUserDisplay.start.bind(GradeUserDisplay));

