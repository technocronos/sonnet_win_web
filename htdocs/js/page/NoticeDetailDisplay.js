
/**
 * お知らせ詳細を制御するシングルトンオブジェクト。
 *
 */
function NoticeDetailDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(NoticeDetailDisplay.prototype, 'constructor', {
        value : NoticeDetailDisplay,
        enumerable : false
    });

    this.notify_at = Page.getParams("notify_at");
    this.title = Page.getParams("title");
    this.body = Page.getParams("body");
    this.me = Page.getParams("noticedetail_d");

    this.scroll = undefined;
  
    this.super = DisplayCommon.prototype;
}

// 親クラスを継承
NoticeDetailDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
NoticeDetailDisplay.prototype.start = function() {
console.log("NoticeDetailDisplay.start rannning...");
    var self = NoticeDetailDisplay;
    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
NoticeDetailDisplay.prototype.reload = function (){
    var self = NoticeDetailDisplay;

    $("#notice_notify_at").html(self.notify_at);
    $("#notice_title").html(self.title);
    $("#notice_body").html(AppUtil.nl2br(self.body));

    //okボタンクリック時イベントハンドラ
    $("#detail-close").off('click').on('click',function() {
        sound("se_btn");
        self.me.close();
        self.destroy();
    });

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
NoticeDetailDisplay.prototype.onLoaded = function() {
    var self = NoticeDetailDisplay;

    //記事のスクロール表示
    if(self.scroll){
        self.scroll.refresh();
    }else{
        self.scroll = new IScroll('#NoticeDetailContents .scrollWrapper', {
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
 * ページのオブジェクトを破棄する。
 */
NoticeDetailDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    Page.setParams("notify_at", null);
    Page.setParams("title", null);
    Page.setParams("body", null);
    Page.setParams("noticedetail_d", null);
    NoticeDetailDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var NoticeDetailDisplay = new NoticeDetailDisplay();

$(document).ready(NoticeDetailDisplay.start.bind(NoticeDetailDisplay));

