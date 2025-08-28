
/**
 * メインFlashを制御するオブジェクト。
 *
 */
function MainContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //コンストラクタ名を書き換える
    Object.defineProperty(MainContentsDisplay.prototype, 'constructor', {
        value : MainContentsDisplay,
        enumerable : false
    });

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    $(window).scrollTop(0);

    //スクロールをストップする
    $(window).on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //背景スクロールをストップする
    $("#canvas_bg").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //ローディング画面スクロールをストップする
    $("#loading").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });


    this.HeaderCanvas = null;
    this.FooterCanvas = null;

    this.screen = "menu";

    this.summary = null;

    this.transparent = true;

    this.shop_currency = "gold";
    this.shop_category = "ITM";

    this.bgm = "bgm_menu";

}

// 親クラスを継承
MainContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
MainContentsDisplay.prototype.start = function() {
console.log("MainContentsDisplay.start rannning...");
    var self = MainContentsDisplay;

    $("#loading").show();

    //プリロード利用時にcss指定できないので・・
    $("#click_gurde").css("transform", "scale(" + self.devicewidth / 750 + ")");
    $("#click_gurde").css("transform-origin", "0px 0px");
    $("#click_gurde").show();

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * loadingつきでサウンドを読み込む
 * 各画面で個別のサウンド処理がある場合にはオーバーライドして実装すること。
 */
MainContentsDisplay.prototype.soundLoad = function(callback){
    var self = MainContentsDisplay;
    //ホームサマリ情報を取得する。
    HomeApi.summary(dataId, firstscene, his_user_id, sphereId, function(response){
        console.log(response);
        self.summary = response;

        //サマリを格納しておく
        Page.setSummary(self.summary);

        if(self.summary.firstscene != "" && self.summary.firstscene != null){
            self.summary.selectmenu = self.summary.firstscene;

            //ショップの決済から戻った場合
            if(self.summary.selectmenu == "shop" && self.summary.label == "result"){
                //もう使ってしまってる場合はnullもあり得る
                if(self.summary.buy != null){
                    Page.setParams("buy_user_item_id", self.summary.buy.user_item_id)
                    self.shop_currency = "coin";
                    self.shop_category = "ITM";
                }
            }

            if(self.summary.selectmenu == "quest" && self.summary.sphereId != null){
                Page.setParams("sphereId", self.summary.sphereId);
            }

            if(self.summary.selectmenu == "his_page" && self.summary.his_user_id != null){
                Page.setParams("his_user_id", self.summary.his_user_id);
            }
        }

        //ヘッダー
        self.HeaderCanvas = new HeaderCanvas(self,null,self.summary);

        //フッター
        self.FooterCanvas = new FooterCanvas(self,null,self.summary);

        var timer = null;

        //全canvasが読み込まれていることを保証する。
        $(function(){
            timer = setInterval(function(){
                if(self.HeaderCanvas.loaded && 
                    self.FooterCanvas.loaded){
                    //loading..表示タイマーストップ
                    clearInterval(timer);

                    $("#footer_sm").css("width", (self.FooterCanvas.width * window.devicePixelRatio) + "px");

                    //web audio APIを使う場合、サウンドを読み込む
                    audio.sndfx = new webaudio;

                    //読みこむファイルの個数
                    audio.sndfx.all_file_count = selist.length;
                    //ファイル読み込み済み
                    audio.sndfx.sound_loaded = 1;
                    self.reload();
                }
            },100);
        });
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
MainContentsDisplay.prototype.reload = function (){
console.log("MainContentsDisplay reload run..");
    var self = MainContentsDisplay;

    $("#mini_loading").fadeOut("normal", function(){
        //loading表示ストップ
        $("#mini_loading").hide();

        sound(self.bgm);

        //ローディング非表示
        $("#loading").hide();
        $("#click_gurde").hide();

        self.startScreen(self.summary.selectmenu);

    });

    self.super.reload.call(self);

}

/*
  画面遷移時にフッターより呼び出される
*/
MainContentsDisplay.prototype.onScreenChange = function(start_screen, end_screen){
    var self = MainContentsDisplay;

    $("#click_gurde").show();

    self.endScreen(end_screen);

    var timeout_id = setTimeout(function(){
        clearTimeout(timeout_id);
        self.startScreen(start_screen);
    },800);
}

/*
  画面開始時処理
*/
MainContentsDisplay.prototype.startScreen = function(screen){
    var self = MainContentsDisplay;

    if(screen == null || screen == "")
        return;

    self.screen = screen;

    //フッター登場
    self.FooterCanvas.in();

    switch(screen){
      case "menu":
        self.setBgImage("bg1", 0);

        //ホームを出す
        self.ContentsShow(HomeHtml);

        //ヘッダー登場
        self.HeaderCanvas.caption("ホーム");
        self.HeaderCanvas.in();

        break;
      case "status":
        self.setBgImage("bg2", 0);

        //マイページを出す
        self.ContentsShow(MypageHtml);

        //ヘッダー登場
        self.HeaderCanvas.caption("マイページ");
        self.HeaderCanvas.in();

        break;
      case "his_page":
        self.setBgImage("bg2", 0);

        //他人のページを出す
        self.ContentsShow(HispageHtml);

        //ヘッダー登場
        self.HeaderCanvas.caption("他人のページ");
        self.HeaderCanvas.in();

        break;
      case "quest":
        self.setBgImage("bg2", 0);

        //クエストを出す
        self.ContentsShow(QuestHtml);
        
        //ヘッダー登場
        self.HeaderCanvas.caption("");
        self.HeaderCanvas.in();
        break;
      case "weapon":
        //装備リストを出す
        self.EquipListShow("WPN");
        self.setBgImage("bg2", 0);

        //ヘッダー登場
        self.HeaderCanvas.caption("装備・合成");
        self.HeaderCanvas.in();

        break;
      case "gacha":
        self.setBgImage("circle_bg", 0);

        //ガチャ一覧を出す
        self.ContentsShow(GachaHtml);

        //ヘッダー登場
        self.HeaderCanvas.caption("ガチャ");
        self.HeaderCanvas.in();

        break;
      case "gacha_detail":
        self.setBgImage("circle_bg", 0);

        //ガチャ一覧を出す
        self.ContentsShow(GachaDetailHtml);

        //ヘッダー登場
        self.HeaderCanvas.caption("ガチャ");
        self.HeaderCanvas.in();

        break;
      case "shop":
        //ショップリストを出す
        self.ShopListShow(self.shop_currency, self.shop_category);
        self.setBgImage("bg2", 0);

        //ヘッダー登場
        self.HeaderCanvas.caption("ショップ");
        self.HeaderCanvas.in();

        break;
      case "rival":
        self.setBgImage("bg2", 0);

        //対戦ユーザー一覧を出す
        self.ContentsShow(RivalListHtml);

        //ヘッダー登場
        self.HeaderCanvas.caption("ユーザー対戦");
        self.HeaderCanvas.in();

        break;
      case "zukan":
        self.setBgImage("bg_book", 0);

        //図鑑一覧を出す
        self.ContentsShow(MonsterHtml);

        //ヘッダー登場
        self.HeaderCanvas.caption("モンスター図鑑");
        self.HeaderCanvas.in();

        break;
      case "zukan_list":
        self.setBgImage("bg_book", 0);

        //図鑑詳細を出す
        self.ContentsShow(MonsterListHtml);

        //ヘッダー登場
        //self.HeaderCanvas.caption("");
        //self.HeaderCanvas.in();
        break;
    }
}

/*
  画面終了時処理
*/
MainContentsDisplay.prototype.endScreen = function(screen){
    var self = MainContentsDisplay;

    if(screen == null || screen == "")
        return;

    //ヘッダー終了
    self.HeaderCanvas.out();

    switch(screen){
      case "menu":
        //画面を終了させる
        HomeDisplay.destroy();
        break;
      case "status":
        //画面を終了させる
        MypageDisplay.destroy();
        break;
      case "his_page":
        //画面を終了させる
        HispageDisplay.destroy();
        break;
      case "quest":
        //画面を終了させる
        QuestDisplay.destroy();
        break;
      case "weapon":
        //画面を終了させる
        EquipListDisplay.destroy();
        break;
      case "gacha":
        //画面を終了させる
        GachaDisplay.destroy();
        break;
      case "gacha_detail":
        //画面を終了させる
        GachaDetailDisplay.destroy();
        break;
      case "shop":
        //画面を終了させる
        ShopListDisplay.destroy();
        break;
      case "rival":
        //画面を終了させる
        RivalListDisplay.destroy();
        break;
      case "zukan":
        //画面を終了させる
        MonsterDisplay.destroy();
        break;
      case "zukan_list":
        MonsterListDisplay.destroy();
        break;
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * 時間をフォーマットして戻す。（マイページ画面専用）
 */
MainContentsDisplay.prototype.compareDate = function(expire, path, expire_return){
    var expire_at = AppUtil.compareDate(expire);
    pex.getAPI().setVariable(path, expire_return, expire_at);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ショップ一覧を出す
 */
MainContentsDisplay.prototype.ShopListShow = function(currency, category){
    var self = MainContentsDisplay;

    Page.setParams("shop_currency", currency);
    Page.setParams("shop_category", category);

    self.ContentsShow(ShopListHtml);

}

//---------------------------------------------------------------------------------------------------------
/**
 * 武器、アイテム一覧を出す
 */
MainContentsDisplay.prototype.EquipListShow = function(category){
    var self = MainContentsDisplay;

    Page.setParams("equip_category", category);

    self.ContentsShow(EquipListHtml);

}

//---------------------------------------------------------------------------------------------------------
/**
 * HTMLコンテンツを表示する。ポップアップではなくヘッダー、フッターの下レイヤに表示される。
 */
MainContentsDisplay.prototype.ContentsShow = function(content){
    var self = MainContentsDisplay;

    var safeareatop = getComputedStyle(document.documentElement).getPropertyValue("--sat");
    safeareatop = safeareatop.replace("px", "");

    if(isNaN(safeareatop)){
        safeareatop = 0;
    }

    // コンテンツの要素を書き換える
    var contents_top = 0;
    var ratio = self.devicewidth / 750;

    var contents_height = parseInt($("#mainflex").css("height"));

    var target = $(content).filter("#content").clone();
    var script = $(content).filter("script").clone();

    target.css("position","absolute");
    target.css("transform","scale(" + ratio + ") translateX(-50%)");
    target.css("transform-origin","0px");
    target.css("left", (self.devicewidth / 2) + "px");

    var header_height = (parseInt($("#header_sm").css("height")) * $("#header_sm").css("zoom")) - 20;

//console.log(header_height)
    var outer = target.outerHeight() * ratio;

    if(target.outerHeight() > 0){
        //短い端末はヘッダーに隠れてしまわないようにする
        if( (contents_height - outer) / 2 <  header_height)
            contents_top = header_height;

        //高さが設定してある場合、縦中央ぞろえ
        target.css("top",((contents_height + (contents_top - safeareatop)) / 2) + "px");
        target.css("margin-top", "-" + (target.outerHeight() / 2) + "px");

        //高さがそもそも足りない端末は縮小して表示する
        if(is_tablet == "tablet"){
            //全体を縮小
            var ratio2 = (contents_height / 1150);
            target.css("transform","scale(" + ratio2 + ") translateX(-50%)");
        }
    }else{
        target.css("top",contents_top + "px");
    }

    //親をクローンして入れ替える
    var content_clone = $();
    content_clone = content_clone.add(script);
    content_clone = content_clone.add(target);

    //戻す
    content = content_clone;

    content_clone = null;

    $("#main_contents").html(content);

}

//---------------------------------------------------------------------------------------------------------
/**
 * ヘルプ一覧用ポップアップを立ち上げる。ヘッダーから呼び出される
 */
MainContentsDisplay.prototype.help_list = function(){
    var d = new Dialogue();

    Page.setParams("me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(HelpListHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
MainContentsDisplay.prototype.destroy = function (){

    this.super.destroy.call(this);
    MainContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var MainContentsDisplay = new MainContentsDisplay();

$(document).ready(MainContentsDisplay.start.bind(MainContentsDisplay));

