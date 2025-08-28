
/**
 * 階級別ユーザー一覧画面を制御するシングルトンオブジェクト。
 *
 */
function BattleEventDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(BattleEventDisplay.prototype, 'constructor', {
        value : BattleEventDisplay,
        enumerable : false
    });

    this.defaultListId = "gradeuser-list";
    this.defaultEntryId = "gradeuser-template";

    this.scroll_loading = true;

    this.list = $();

    this.me = Page.getParams("battleevent_d");
    this.category = Page.getParams("category");

    //12 weekly 11 daily
    if(this.category == undefined)
       this.category = "12";

    this.scroll = undefined;
  
    this.nolist = false
    this.count = 10;
    this.page = 0;

    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
BattleEventDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
BattleEventDisplay.prototype.start = function() {
console.log("BattleEventDisplay.start rannning...");

    var self = BattleEventDisplay;

    //タブの選択
    $("#tab-" + self.category).css("display", "block");

    self.super.start.call(self);

}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
BattleEventDisplay.prototype.reload = function (){
    var self = BattleEventDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    $("#battle_event_help").off("click").on("click",function(){
        sound("se_btn");
        self.onHelpShow("other-ranking");
    });

    //キャンセルボタンクリック時イベントハンドラ
    $("#battle_event_close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    BattleApi.ranking(self.category, self.count, self.page, function(response){
        console.log(response);

        var begin = AppUtil.fromtimestamp(response.term.begin);
        var end = AppUtil.fromtimestamp(response.term.end);

        var start_date = AppUtil.fromtimestamp(response.rankinfo.start_date);
        var result_date = AppUtil.fromtimestamp(response.rankinfo.result_date - 1);

        if(self.category == 12){
            //曜日限定開催の場合
            if(response.rankinfo.start_date != null){

                //開催中
                if(response.rankinfo.status == 1){
                    if(response.rankinfo.in_aggregate == true){
                        $("#tab-div").hide();
                        response.list.resultset = [];
                        $("#navi_serif_event").html("バトルイベント開催中なのだ！現在集計中なのだ。今月は" + Date.generate(start_date).format("MM月DD日") +"～"+ Date.generate(result_date).format("MM月DD日") + "で開催中なのだ。");
                    }else{
                        $("#navi_serif_event").html("バトルイベントの順位表なのだ！今月は" + Date.generate(start_date).format("MM月DD日") +"～"+ Date.generate(result_date).format("MM月DD日") + "で開催中なのだ。期間中は毎日AM4時集計なのだ！");
                    }

                //結果発表
                }else if(response.rankinfo.status == 2){
                    if(response.rankinfo.in_aggregate == true){
                        $("#tab-div").hide();
                        response.list.resultset = [];
                        $("#navi_serif_event").html("只今集計中なのだ。5時まで待つのだ。今月は" + Date.generate(start_date).format("MM月DD日") +"～"+ Date.generate(result_date).format("MM月DD日") + "で開催中したのだ。");
                    }else{
                        $("#navi_serif_event").html("ケッカハッピョーなのだ！おつかれさんなのだ。今月は" + Date.generate(start_date).format("MM月DD日") +"～"+ Date.generate(result_date).format("MM月DD日") + "で開催中したのだ。結果は1週間くらいで閉じられるのだ。");
                    }
                //非開催 or 2日前
                }else if(response.rankinfo.status == 3 || response.rankinfo.status == 4){
                    $("#tab-div").hide();
                    response.list.resultset = [];
                    $("#navi_serif_event").html("まだ開催してないのだ。今月は" + Date.generate(start_date).format("MM月DD日") +"～"+ Date.generate(result_date).format("MM月DD日") + "で開催予定なのだ。");
                }
            //常時開催の場合
            }else{
                $("#navi_serif_event").html("バトルイベントの順位表なのだ。今は" + Date.generate(begin).format("MM月DD日") +"～"+ Date.generate(end).format("MM月DD日") + "までで開催中なのだ。");
            }
        }else{
            //曜日限定開催の場合
            if(response.rankinfo.start_date != null){
                if(response.rankinfo.in_aggregate == true){
                    $("#tab-div").hide();
                    response.list.resultset = [];
                }
            }

            $("#navi_serif_event").html("バトルイベントの" + Date.generate(begin).format("MM月DD日") + "の順位表なのだ。参加賞は100位以内に軍鶏の時計をあげるのだ。");
        }

        //タブに文言追加
        $("#weekly_txt").html(Date.generate(start_date).format("DD日") +"～"+ Date.generate(result_date).format("DD日"));
        $("#daily_txt").html(response.period.daily);

        self.list = response.list.resultset;

        // 一つもなかったら...
        if(self.list.length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry-be");
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
BattleEventDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = BattleEventDisplay;

    //ランク順位
    $("[key='rank']", board).text( entry["rank"] + "位");

    //階級ポイント
    $("[key='point']", board).text( entry["point"] );

    //ユーザー名
    $("[key='user_name']", board).text( entry.avatar.player_name );
    //ユーザーアイコン
    //$("[key='icon']", board).attr("src", entry["thumbnailUrl"] == null ? AppUrl.asset("img/parts/sp/avatar_thumb_88_100.png"):entry["thumbnailUrl"] );
    $("[key='icon']", board).attr("src", AppUrl.asset("img/chara/" + entry.avatar.imageUrl) );

    $("[key='level']", board).text( entry["avatar"]["level"] );
    $("[key='grade']", board).text( entry["avatar"]["grade"]["grade_name"] );

    //最高順位
    if(self.category == 12){
        if(entry["highest"]["weekly"] != undefined)
            $("[key='highest']", board).text( entry["highest"]["weekly"] + "位" );
        else
            $("[key='highest']", board).text( "--位" );
    }else{
        if(entry["highest"]["daily"] != undefined)
            $("[key='highest']", board).text( entry["highest"]["daily"] + "位" );
        else
            $("[key='highest']", board).text( "--位" );
    }

    if(chara_user_id != entry.user_id){
        //プロフボタンクリック時イベントハンドラ
        $("[key='prof_button']", board).off("click").on("click",function(){
            sound('se_btn');

            Page.setParams("his_user_id", entry.user_id);

            self.destroy();

            MainContentsDisplay.FooterCanvas.out("his_page", "rival");
        });
        //対戦ボタンクリック時イベントハンドラ
        $("[key='battle_button']", board).off("click").on("click",function(){
            sound('se_btn');
            self.showBattleConfirm(entry["avatar"]["character_id"]);

        });
    }else{
        $("[key='prof_button']", board).off("click");
        $("[key='prof_button']", board).hide();

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
BattleEventDisplay.prototype.onLoaded = function() {
    var self = BattleEventDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#BattleEventContents .scrollWrapper', {
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
                BattleApi.ranking(self.category, self.count, self.page, function(response){
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
BattleEventDisplay.prototype.onChangeCategory = function(category) {
    var self = BattleEventDisplay;

    if(self.category == category)
        return false;

    sound("se_btn");

    //前のタブの選択を削除
    $("#tab-" + self.category).css("display", "none");
    self.category = category;
    //新しいタブの選択
    $("#tab-" + self.category).css("display", "block");

    self.page = 0;
    self.nolist = false;

    self.reload();
}

//---------------------------------------------------------------------------------------------------------
/**
 * 対戦確認ポップアップを呼び出す
 */
BattleEventDisplay.prototype.showBattleConfirm = function(rivalId){
    var self = BattleEventDisplay;

    var d = new Dialogue();

    Page.setParams("rivalId", rivalId);
    Page.setParams("battle_confirm_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(BattleConfirmHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = -50;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
BattleEventDisplay.prototype.destroy = function (){
    var self = BattleEventDisplay;


    Page.setParams("category", null);
    Page.setParams("battleevent_d", null);

    self.me.close();
    self.super.destroy.call(self);
    BattleEventDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var BattleEventDisplay = new BattleEventDisplay();

$(document).ready(BattleEventDisplay.start.bind(BattleEventDisplay));

