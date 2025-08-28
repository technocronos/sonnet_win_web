
/**
 * メッセージリストを制御するシングルトンオブジェクト。
 *
 */
function MemberListDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(MemberListDisplay.prototype, 'constructor', {
        value : MemberListDisplay,
        enumerable : false
    });

    this.defaultListId = "member-list";
    this.defaultEntryId = "member-template";

    this.userId = Page.getParams("userId");
    this.player_name = Page.getParams("player_name");

    this.me = Page.getParams("me");

    this.category = Page.getParams("category");

    if(this.category == undefined)
       this.category = "member";

    this.list = {};
    this.scroll = undefined;

    this.response = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
MemberListDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MemberListDisplay.prototype.start = function() {
console.log("MemberListDisplay.start rannning...");
    var self = MemberListDisplay;

    //タブの選択
    $("#tab-" + self.category).css("display", "block");

    $("#tab-me-area").show();
    $("#tab-member-area").hide();

    //他人のページの場合は非表示
    if(self.userId != chara_user_id){
        $("#tab-bg").hide();
        $("#tab-me-area").hide();
        $("#tab-member-area").show();
    }

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MemberListDisplay.prototype.reload = function (){
    var self = MemberListDisplay;

    $("#bg_image").html(Page.preload_image.msg_window);
    $("#tab-username").html(self.player_name);

    list = $("#" + self.defaultListId);
    // まずは現在のリストを空に。
    list.empty();

    //okボタンクリック時イベントハンドラ
    $("#dialogue-close").off('click').on('click',function() {
        sound("se_btn");
        self.destroy();
        self.me.close();
    });

    //自分の場合、バッチがあれば表示する
    if(self.userId == chara_user_id){
        if(MainContentsDisplay.summary.unconfirmCount > 0){
            $("#batch_send").show();
        }else{
            $("#batch_send").hide();
        }
        if(MainContentsDisplay.summary.unanswerCount > 0 ){
            $("#batch_recv").show();
        }else{
            $("#batch_recv").hide();
        }
    }

    if(self.category == "member"){
        //タブを作成する。
        MemberApi.list(self.userId,function(response){
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
    }else if(self.category == "search" ){
        //タブを作成する。
        MemberApi.search(function(response){
            console.log(response);

            self.response = response;

            // 一つもなかったら...
            if(self.response["list"].length == 0) {
                // その旨のパネルを表示。
                var no = Juggler.generate("no-entry");
                $("#" + self.defaultListId).append(no);

                if(self.scroll)
                    self.scroll.refresh();

                // 処理はここまで。
                return;
            }

            // パケットをリストに表示。
            self.refreshList(self.response["list"]);

            self.super.reload.call(self);
        });

    }else{
        //タブを作成する。
        ApproachApi.list(self.category, function(response){
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

            if(self.category == "send")
                MainContentsDisplay.summary.unconfirmCount = 0;

            // パケットをリストに表示。
            self.refreshList(self.response["list"]["resultset"]);

            self.super.reload.call(self);
        });
    }

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたエントリを、指定されたボードに表示するときに呼ばれる。
 */
MemberListDisplay.prototype.setupEntryBoard = function(entry, board) {
    var self = MemberListDisplay;

    if(self.category == "member" || self.category == "search"){
        //$("[key='image']", board).attr("src", entry["thumbnailUrl"] == null ? AppUrl.asset("img/parts/sp/avatar_thumb_88_100.png"):entry["thumbnailUrl"] );
        $("[key='image']", board).attr("src", AppUrl.asset("img/chara/" + entry.imageUrl) );
        $("[key='name']", board).text( entry["player_name"] );
        $("[key='level']", board).text( entry["chara"]["level"] );
        $("[key='grade_name']", board).text( entry["grade"]["grade_name"] );

        if(entry["user_id"] != chara_user_id){
            $("[key='detail_btn']", board).show();
            //詳細ボタンクリック時イベントハンドラ
            $("[key='detail_btn']", board).off('click').on('click',function() {
                sound("se_btn");

                $("#btn_hed").off('click');
                $("#btn_wpn").off('click');
                $("#btn_acs").off('click');
                $("#btn_acs").off('click');
                $("#btn_change").off('click');
                $("#btn_cloth_out").off('click');

                self.destroy();

                Page.setParams("his_user_id", entry["user_id"]);
                //他人のページの場合
                if(self.userId != chara_user_id)
                    MainContentsDisplay.FooterCanvas.out("his_page","his_page");
                else
                    MainContentsDisplay.FooterCanvas.out("his_page","status");
            });
        }else{
            $("[key='detail_btn']", board).hide();
            $("[key='detail_btn']", board).off('click');
        }
        $("[key='cancel_btn']", board).hide();
        $("[key='ok_btn']", board).hide();
        $("[key='ng_btn']", board).hide();
    }else if(self.category == "send" ){
        //$("[key='image']", board).attr("src", entry["companion"]["thumbnailUrl"]== null ? AppUrl.asset("img/parts/sp/avatar_thumb_88_100.png"):entry["companion"]["thumbnailUrl"] );
        $("[key='image']", board).attr("src", AppUrl.asset("img/chara/" + entry.companion.imageUrl) );
        $("[key='name']", board).text( entry["companion"]["player_name"] );
        $("[key='level']", board).text( entry["companion"]["chara"]["level"] );
        $("[key='grade_name']", board).text( entry["companion"]["grade"]["grade_name"] );

        $("[key='detail_btn']", board).show();
        $("[key='detail_btn']", board).css("top", "37px");

        //詳細ボタンクリック時イベントハンドラ
        $("[key='detail_btn']", board).off('click').on('click',function() {
            sound("se_btn");

            $("#btn_hed").off('click');
            $("#btn_wpn").off('click');
            $("#btn_acs").off('click');
            $("#btn_acs").off('click');
            $("#btn_change").off('click');
            $("#btn_cloth_out").off('click');

            self.destroy();

            Page.setParams("his_user_id", entry["companion"]["chara"]["user_id"]);
            //他人のページの場合
            if(self.userId != chara_user_id)
                MainContentsDisplay.FooterCanvas.out("his_page","his_page");
            else
                MainContentsDisplay.FooterCanvas.out("his_page","status");
        });

        if(entry.status == 0){
            //キャンセルボタンクリック時イベントハンドラ
            $("[key='cancel_btn']", board).show();
            $("[key='cancel_btn']", board).css("top", "112px");

            $("[key='cancel_btn']", board).off('click').on('click',function() {
                sound("se_btn");

                var txt = "申請をキャンセルするのだ？";

                //確認ポップアップを立ち上げる
                self.showConfirm(txt, function(){
                    sound("se_btn");
                    //申請キャンセルをする
                    ApproachApi.cancel(entry["approach_id"], function(response){
                        console.log(response);
                        PopupConfirmDisplay.destroy();
                        self.reload();
                    });
                });
            });
        }else{
            $("[key='cancel_btn']", board).hide();
            if(entry.status == 1){
                $("[key='status_text']", board).show();
                $("[key='status_text']", board).html("承認されました");
            }
        }

        $("[key='ok_btn']", board).hide();
        $("[key='ng_btn']", board).hide();

    }else if(self.category == "receive" ){
        //$("[key='image']", board).attr("src", entry["companion"]["thumbnailUrl"]== null ? AppUrl.asset("img/parts/sp/avatar_thumb_88_100.png"):entry["companion"]["thumbnailUrl"] );
        $("[key='image']", board).attr("src", AppUrl.asset("img/chara/" + entry.companion.imageUrl) );
        $("[key='name']", board).text( entry["companion"]["player_name"] );
        $("[key='level']", board).text( entry["companion"]["chara"]["level"] );
        $("[key='grade_name']", board).text( entry["companion"]["grade"]["grade_name"] );

        $("[key='detail_btn']", board).show();
        $("[key='detail_btn']", board).css("top", "37px");

        //詳細ボタンクリック時イベントハンドラ
        $("[key='detail_btn']", board).off('click').on('click',function() {
            sound("se_btn");

            $("#btn_hed").off('click');
            $("#btn_wpn").off('click');
            $("#btn_acs").off('click');
            $("#btn_acs").off('click');
            $("#btn_change").off('click');
            $("#btn_cloth_out").off('click');

            self.destroy();

            Page.setParams("his_user_id", entry["companion"]["chara"]["user_id"]);
            //他人のページの場合
            if(self.userId != chara_user_id)
                MainContentsDisplay.FooterCanvas.out("his_page","his_page");
            else
                MainContentsDisplay.FooterCanvas.out("his_page","status");
        });

        $("[key='cancel_btn']", board).hide();
        $("[key='ok_btn']", board).off('click').on('click',function() {
            sound("se_btn");
            var txt = "仲間申請を承認するのだ？";
            //確認ポップアップを立ち上げる
            self.showConfirm(txt, function(){
                sound("se_btn");
                //承認をする
                ApproachApi.accept(entry["approach_id"], function(response){
                    console.log(response);
                    MainContentsDisplay.summary.unanswerCount--;
                    //ヘッダーを更新する
                    var member_current = Page.getSummary().member.current + 1;
                    MainContentsDisplay.HeaderCanvas.update("member_current", member_current);
                    PopupConfirmDisplay.destroy();
                    self.reload();
                });                
            });
        });
        $("[key='ng_btn']", board).off('click').on('click',function() {
            sound("se_btn");

            var txt = "仲間申請を却下するのだ？";
            //確認ポップアップを立ち上げる
            self.showConfirm(txt, function(){
                sound("se_btn");
                //承認却下をする
                ApproachApi.reject(entry["approach_id"], function(response){
                    console.log(response);
                    MainContentsDisplay.summary.unanswerCount--;
                    PopupConfirmDisplay.destroy();
                    self.reload();
                });
            });
        });
        $("[key='ok_btn']", board).show();
        $("[key='ng_btn']", board).show();
    }else{
        
    }

    self.super.setupEntryBoard.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
MemberListDisplay.prototype.onLoaded = function() {
    var self = MemberListDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#content .scrollWrapper', {
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
 * タブ切り替え時に呼び出される。
 */
MemberListDisplay.prototype.onChangeTab = function(category) {
    var self = MemberListDisplay;

    if(self.category == category)
        return;

    sound("se_btn");

    //前のタブの選択を削除
    $("#tab-" + this.category).css("display", "none");
    self.category = category;
    //新しいタブの選択
    $("#tab-" + this.category).css("display", "block");

    self.reload();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
MemberListDisplay.prototype.destroy = function (){
    var self = MemberListDisplay;

    self.me.close();
    self.super.destroy.call(self);
    MemberListDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var MemberListDisplay = new MemberListDisplay();

$(document).ready(MemberListDisplay.start.bind(MemberListDisplay));

