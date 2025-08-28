
/**
 * メッセージリストを制御するシングルトンオブジェクト。
 *
 */
function MessageListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MessageListDisplay.prototype, 'constructor', {
        value : MessageListDisplay,
        enumerable : false
    });

    this.defaultListId = "message-list";
    this.defaultEntryId = "message-template";

    this.userId = Page.getParams("userId");
    this.category = Page.getParams("type");

    this.me = Page.getParams("me");

    this.list = {};
    this.scroll = undefined;

    this.response = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MessageListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MessageListDisplay.prototype.start = function() {
console.log("MessageListDisplay.start rannning...");
    var self = MessageListDisplay;

    //タブの選択
    $("#tab-" + self.category).css("display", "block");

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MessageListDisplay.prototype.reload = function (){
    self = MessageListDisplay;

    $("#bg_image").html(Page.preload_image.msg_window);

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //okボタンクリック時イベントハンドラ
    $("#dialogue-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
    });

    //タブを作成する。
    MessageApi.list(self.userId, self.category, function(response){
        console.log(response);

        self.response = response;

        // 一つもなかったら...
        if(self.response["list"]["resultset"].length == 0) {
            // その旨のパネルを表示。
            var no = Juggler.generate("no-entry");
            $("#" + self.defaultListId).append(no);

            if(self.scroll)
                self.scroll.refresh();

            // 処理はここまで。
            return;
        }

        // パケットをリストに表示。
        self.refreshList(self.response["list"]["resultset"]);

        self.super.reload.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
MessageListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = MessageListDisplay;

    //$("[key='image']", board).attr("src", entry["thumbnailUrl"] == null ? AppUrl.asset("img/parts/sp/avatar_thumb_88_100.png"):entry["thumbnailUrl"] );
    $("[key='image']", board).attr("src", AppUrl.asset("img/chara/" + entry.imageUrl) );
    $("[key='create_at']", board).text( entry["create_at"] );
    $("[key='comanion_name']", board).text( entry["comanion_name"] );
    $("[key='body']", board).text( entry["body"] );

    //ユーザーボタンクリック時イベントハンドラ
    $("[key='user_btn']", board).off('click').on('click',function() {
        sound("se_btn");

        $("#btn_hed").off('click');
        $("#btn_wpn").off('click');
        $("#btn_acs").off('click');
        $("#btn_acs").off('click');
        $("#btn_change").off('click');
        $("#btn_cloth_out").off('click');

        self.destroy();

        Page.setParams("his_user_id", entry[self.response["companionCol"]]);
        MainContentsDisplay.FooterCanvas.out("his_page","status");
    });

    if(entry["send_user_id"] == chara_user_id){
        $("[key='del_btn']", board).show();
        //削除ボタンクリック時イベントハンドラ
        $("[key='del_btn']", board).off('click').on('click',function() {
            sound("se_btn");
        });
    }else{
        $("[key='del_btn']", board).hide();
    }

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MessageListDisplay.prototype.onLoaded = function() {
    var self = MessageListDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#MessageListContents .scrollWrapper', {
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
MessageListDisplay.prototype.onChangeTab = function(category) {
    var self = MessageListDisplay;

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
MessageListDisplay.prototype.destroy = function (){
    var self = MessageListDisplay;

    self.me.close();
    self.super.destroy.call(self);
    MessageListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var MessageListDisplay = new MessageListDisplay();

$(document).ready(MessageListDisplay.start.bind(MessageListDisplay));

