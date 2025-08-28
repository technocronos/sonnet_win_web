
/**
 * 履歴リストを制御するシングルトンオブジェクト。
 *
 */
function BattleLogDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(BattleLogDisplay.prototype, 'constructor', {
        value : BattleLogDisplay,
        enumerable : false
    });

    this.defaultListId = "battlelog-list";
    this.defaultEntryId = "battlelog-template";

    this.me = Page.getParams("me");

    this.charaId = Page.getParams("charaId");
    this.tourId = Tournament_MasterService_TOUR_MAIN;
    this.category = Page.getParams("category");

    if(this.category == undefined)
       this.category = "challenge";

    this.scroll_loading = true;

    this.list = {};
    this.scroll = undefined;
  
    this.nolist = false
    this.page = 0;

    this.ClassName = "BattleLogDisplay";

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
BattleLogDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BattleLogDisplay.prototype.start = function() {
console.log("BattleLogDisplay.start rannning...");
    var self = BattleLogDisplay;

    //タブの選択
    $("#tab-" + self.category).css("display", "block");

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BattleLogDisplay.prototype.reload = function (){
    var self = BattleLogDisplay;

    $("#bg_image").html(Page.preload_image.msg_window);


    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //okボタンクリック時イベントハンドラ
    $("#dialogue-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    //タブを作成する。
    BattleLogApi.list(self.charaId,self.tourId,self.category,self.page, function(response){
        console.log(response);

        self.list = response["list"]["resultset"];

        // 一つもなかったら...
        if(self.list.length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);

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
BattleLogDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = BattleLogDisplay;

    $("[key='create_at']", board).text( entry["create_at"] );
    $("[key='rival_user_name']", board).text( entry["rival_character_name"] );
    //$("[key='rival_character_name']", board).text( entry["rival_character_name"] );
    $("[key='level']", board).text( entry.rival_result.character.level );
    $("[key='grade_name']", board).text( entry["rival_ready"]["grade_name"] );
    //$("[key='image']", board).attr("src", entry["thumbnailUrl"] == null ? AppUrl.asset("img/parts/sp/avatar_thumb_88_100.png"):entry["thumbnailUrl"] );
    $("[key='image']", board).attr("src", AppUrl.asset("img/chara/" + entry.imageUrl) );

    $("[key='result_icom']", board).attr("src", AppUrl.asset("img/parts/sp/battlelog_" + entry["bias_status"] + ".png") );

    $("[key='match_length']", board).text( entry["result_detail"]["match_length"] + "回" );

    $("[key='total_hurtP']", board).text( entry["bias_result"]["summary"]["total_hurt"] );
    $("[key='total_hurtE']", board).text( entry["rival_result"]["summary"]["total_hurt"] );

    $("[key='normal_hurtP']", board).text( entry["bias_result"]["summary"]["normal_hurt"] );
    $("[key='normal_hurtE']", board).text( entry["rival_result"]["summary"]["normal_hurt"] );


    $("[key='normal_hitsP']", board).text( entry["bias_result"]["summary"]["normal_hits"] );
    $("[key='normal_hitsE']", board).text( entry["rival_result"]["summary"]["normal_hits"] );


    $("[key='tact0P']", board).text( entry["bias_result"]["summary"]["tact0"] );
    $("[key='tact0E']", board).text( entry["rival_result"]["summary"]["tact0"] );


    $("[key='revenge_hurtP']", board).text( entry["bias_result"]["summary"]["revenge_hurt"] );
    $("[key='revenge_hurtE']", board).text( entry["rival_result"]["summary"]["revenge_hurt"] );


    $("[key='revenge_countP']", board).text( entry["bias_result"]["summary"]["revenge_count"] );
    $("[key='revenge_countE']", board).text( entry["rival_result"]["summary"]["revenge_count"] );

    if(entry["bias_result"]["summary"]["revenge_attacks"] > 0)
        $("[key='revenge_hitsP']", board).text( parseInt(entry["bias_result"]["summary"]["revenge_hits"] / entry["bias_result"]["summary"]["revenge_attacks"] * 100) );
    else
        $("[key='revenge_hitsP']", board).text( "----" );

    if(entry["rival_result"]["summary"]["revenge_attacks"] > 0)
        $("[key='revenge_hitsE']", board).text( parseInt(entry["rival_result"]["summary"]["revenge_hits"] / entry["rival_result"]["summary"]["revenge_attacks"] * 100) );
    else
        $("[key='revenge_hitsE']", board).text( "----" );

    $("[key='exp']", board).text( "+" + entry["bias_result"]["gain"]["exp"] );
    $("[key='gold']", board).text( "+" + entry["bias_result"]["gain"]["gold"] );
    $("[key='grade_nominal']", board).text( "+" + entry["bias_result"]["gain"]["grade_nominal"] );


    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
BattleLogDisplay.prototype.onLoaded = function() {
    var self = BattleLogDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#BattleLogContents .scrollWrapper', {
            //click: true,
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
                BattleLogApi.list(self.charaId,self.tourId,self.category,self.page, function(response){
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
 * タブ切り替え時に呼び出される。
 */
BattleLogDisplay.prototype.onChangeTab = function(category) {
    sound("se_btn");

    //前のタブの選択を削除
    $("#tab-" + this.category).css("display", "none");
    this.category = category;
    //新しいタブの選択
    $("#tab-" + this.category).css("display", "block");

    this.page = 0;
    this.nolist = false;

    this.reload();
}


//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BattleLogDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    BattleLogDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BattleLogDisplay = new BattleLogDisplay();

$(document).ready(BattleLogDisplay.start.bind(BattleLogDisplay));

