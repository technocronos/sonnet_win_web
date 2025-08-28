
/**
 * クエスト結果を制御するシングルトンオブジェクト。
 *
 */
function FieldEndDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(FieldEndDisplay.prototype, 'constructor', {
        value : FieldEndDisplay,
        enumerable : false
    });

    this.defaultListId = "fieldend-list";
    this.defaultEntryId = "fieldend-template";

    this._parent = Page.getParams("_parent");
    this.sphereId = Page.getParams("sphereId");
    this.me = Page.getParams("fieldend_me");

    this.list = {};
    this.scroll = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
FieldEndDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
FieldEndDisplay.prototype.start = function() {
console.log("FieldEndDisplay.start rannning...");
    var self = FieldEndDisplay;

    var content_height = 1000;

    if(is_tablet == "tablet"){
        $("#FieldEndDiv").css("transform", "scale(0.8)");
        $("#FieldEndDiv").css("transform-origin", "50% 50%");
    }

    $("#bg_image").html(Page.preload_image.circle_bg);

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
FieldEndDisplay.prototype.reload = function (){
    var self = FieldEndDisplay;

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //タブを作成する。
    QuestApi.field_end(self.sphereId, function(response){
        console.log(response);

        switch(parseInt(response.sphere_result)){
            case response.SPHERE_SUCCESS:
                text = response["quest"]["quest_name"] + "クリアなのだ｡オツカレサンなのだ";
                break;
            case response.SPHERE_ESCAPE:
                text = response["quest"]["quest_name"] + "を脱出したのだ｡準備して再挑戦なのだ";
                break;
            case response.SPHERE_FAILURE:
                text = response["quest"]["quest_name"] + "失敗なのだ…くじけずに準備して再挑戦なのだ"
                break;
            case response.SPHERE_GIVEUP:
                text = response["quest"]["quest_name"] + "をギブアップしたのだ"
                break;
        }

        $("#navi_serif").html(text);

        $("#turn").html(response.summary.turn);
        $("#terminate").html(response.summary.terminate);

        //ペナルティがある場合
        if((response.sphere_result == response.SPHERE_FAILURE || response.sphere_result == response.SPHERE_GIVEUP) && response.quest.penalty_pt > 0){
            //廃止
        }

        //ミッション達成の場合
        if(response.summary.mission.achieve){
            self.showMission(response.summary.mission);
            chara_gold = response.gold;
            MainContentsDisplay.HeaderCanvas.update("gold", chara_gold);
        }

        //次のクエストがある場合
        if(response.next != null){
            $("#next_quest_area").show();
        		$("#next_quest_name").html(response.next.quest_name);

            $("#fieldend-close").show();

            //次へボタンクリック時イベントハンドラ
            $("#next-quest").show();
            $("#next-quest").off('click').on('click',function() {
                sound("se_btn");
                self.destroy();

                //すぐ遷移するとサウンドが鳴らないので・・
                setTimeout(function(){
                    window.location.href = response.urlOnNext;
                }, 500);
            });

        }else{
            $("#next_quest_area").hide();
            //ボタンの位置を変更
            $("#fieldend-close").css("left", "260px");
            $("#fieldend-close").show();
        }

        //戻るボタンクリック時イベントハンドラ
        $("#fieldend-close").off('click').on('click',function() {
            sound("se_btn");
            self.destroy();
            if(self._parent)
                self._parent.start();
        });

        self.list = response["treasures"];

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
FieldEndDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = FieldEndDisplay;

    $("[key='item_name']", board).text( entry["item_name"] );
    $("[key='flavor_text']", board).text( entry["flavor_text"] );
    $("[key='icon']", board).attr("src", AppUtil.getItemIconURL(entry["item_id"]) );

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
FieldEndDisplay.prototype.onLoaded = function() {
    var self = FieldEndDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#FieldEndContents .scrollWrapper', {
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
 * ミッション達成ポップアップを呼び出す
 */
FieldEndDisplay.prototype.showMission = function(mission){
    var d = new Dialogue();

    Page.setParams("mission", mission);
    Page.setParams("fieldend_mission_d", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(FieldEndMissionHtml);
    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
FieldEndDisplay.prototype.destroy = function (){
    var self = FieldEndDisplay;

    Page.setParams("sphereId", null);
    Page.setParams("fieldend_me", null);

    self.me.close();

    self.super.destroy.call(self);
    FieldEndDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var FieldEndDisplay = new FieldEndDisplay();

$(document).ready(FieldEndDisplay.start.bind(FieldEndDisplay));

