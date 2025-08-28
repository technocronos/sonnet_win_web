
/**
 * 画面制御を行うスクリプトに共通のタスクを実装しているクラス。
 * 各画面制御オブジェクトへnewして使う。
 * このクラスは毎回必ずdestroyされることを想定しているので画面をまたがった情報は管理できない。
 * 
 * 画面呼び出しフローは主に下記の通り。子クラスでオーバーライドして実装されたい。
 * ---------------------------------------------------------------------------------------------
 *   開始処理
 * ---------------------------------------------------------------------------------------------
 *   start()
 *     この関数は子クラスから呼ばれる。
 *     サウンド読み込み済みの場合は直接reload()が呼ばれ、まだの場合はsoundLoad()が呼ばれる。
 *     登録済みの場合はhome.statusが確実に読み込まれていることを保証する。
 * ---------------------------------------------------------------------------------------------
 *   全画面共通処理
 * ---------------------------------------------------------------------------------------------
 *   reload()
 *     メイン処理。loading処理が無く、データバインド関連処理が無い場合は直接onLoadedをコールする。
 *     子クラスで処理が終わったと思われるタイミングでコールされたい。
 *     ただし、子クラスで途中でreturnする場合等、呼ばれるかどうかは結局、実装次第なので注意。
 * ---------------------------------------------------------------------------------------------
 *   データバインド関連処理
 * ---------------------------------------------------------------------------------------------
 *   refreshList()
 *     APIからデータセットを取得した場合、この関数を使ってバインドする。
 *     1レコードごとにsetupEntryBoard()が呼ばれる。
 *   setupEntryBoard()
 *     1レコードごとにデータをバインドする。
 *     親をコールすることによってすべてバインドされたタイミングでonLoaded()をコールすることができる。
 * ---------------------------------------------------------------------------------------------
 *   終了関連処理
 * ---------------------------------------------------------------------------------------------
 *   onLoaded()
 *     サウンドローディング、およびデータバインド処理が完全に終了したタイミングで呼ばれる。
 *     サウンドローディングが終わっていない場合もコールを待ち続けるのでこれがコールされる場合は
 *     確実にデータバインド、サウンドロードが終わっていることを保証する。
 *   destroy()
 *     画面がもう必要ない時に終了処理をする。手動で適切なタイミングでコールされたい。
 * ---------------------------------------------------------------------------------------------
 */
function DisplayCommon() {
    // リスト要素のid。
    this.defaultListId = undefined;

    // 各エントリとなる要素のid。
    this.defaultEntryId = undefined;

    //EntryBoard読み込み完了カウンター
    this.counter = 0;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "DisplayCommon";

    this.scroll_loading = false;
    this.timeout_id = null;

    this.devicewidth = devicewidth;
    this.deviceheight = deviceheight;

    this.canvas_margin = 0;

    this.shapeDetail = {
      "all": { "method":"cache", cacheScale: 3},
    };

    this.transparent = false;

}

//---------------------------------------------------------------------------------------------------------
/**
 * 画面がスタートされた際に実行される。
 * 子クラスのstartから　this.super.start.call(this);　のようにコールすること。
 * これにより page.getContext() によりこの画面のインスタンスがどこからでも得られる。
 *
 */
DisplayCommon.prototype.start = function (){
    console.log("DisplayCommon.start rannning...");

    var self = this;

    if(is_tablet == "tablet")
        $("#loading-img").css("zoom", "200%");

    $("#mini_loading").show();

    //fontがロードされるまで待つ。初回限定。
    if(!Page.font_load){
        WebFont.load({
            custom: {
                families: [font_name],
            },
            loading: function() {
                console.log('font loading');
            },
            active: function() {
                console.log('font active');

                Page.font_load = true;
            },
            inactive: function() {
                console.log('font inactive');
                //エラー処理
                self.onerror("ネット―ワークの接続に問題があり、読み込めませんでした。");
                clearInterval(timer);
            },
            fontloading: function(familyName, fvd) {
                console.log('fontloading', familyName, fvd);
            },
            fontactive: function(familyName, fvd) {
                console.log('fontactive', familyName, fvd);
            },
            fontinactive: function(familyName, fvd) {
                console.log('fontinactive', familyName, fvd);
                //エラー処理
                self.onerror("ネット―ワークの接続に問題があり、読み込めませんでした。");
                clearInterval(timer);
            }
        });
    }

    Page.preload_count = 0;

    //プリロードフォルダにある画像は全部ここで読み込んでおく。初回限定。
    if(Page.preload_count < preload_list.length){
        $.each(preload_list, function(key, value){
            if(Page.preload_image[value["name"]] === undefined){
                //まだ画像が読み込まれてないなら・・
                Page.preload_image[value["name"]] = new Image();

                $(Page.preload_image[value["name"]]).attr("src", AppUrl.asset(value["url"]));

                Page.preload_image[value["name"]].onload = function() { 
                    Page.preload_count++;
                }

                Page.preload_image[value["name"]].onerror = function() { 
                    self.onerror("ネット―ワークの接続に問題があり、画像が読み込めませんでした。");
                }
            }else{
                //すでに読み込まれてるならカウントだけ上げる
                Page.preload_count++;
            }
        });
    }


    var timer = null;
    var timer_time = 0;

    $(function(){
        timer = setInterval(function(){
            if(Page.preload_count == preload_list.length && Page.font_load){
                //loading..表示タイマーストップ
                clearInterval(timer);

                if(selist.length == 0){
                    //サウンドがいらない画面
                    self.reload();
                }else if(audio.sndfx.sound_loaded == 0){
                    //サウンドが読み込まれてない場合、ローディング表示をしてサウンドを読み込む。
                    self.soundLoad(self.reload);
                }else{
                    //サウンド読み込みが無い場合は直接reloadが呼ばれる。
                    self.reload();
                }
            }else{
                timer_time = timer_time + 100;
                console.log("image_timer=" + timer_time);
                if(timer_time > 10000){
                    //10秒でタイムアウトエラー処理
                    if(Page.font_load == false)
                        self.onerror("ネット―ワークの接続に問題があり、フォントが読み込めませんでした。");
                    else
                        self.onerror("ネット―ワークの接続に問題があり、リソースが読み込めませんでした。");

                    clearInterval(timer);
                }
            }
        },100);
    }); 
}

//---------------------------------------------------------------------------------------------------------
/**
 * TOPに戻るエラーポップアップを表示する。
 * 
 */
DisplayCommon.prototype.onerror = function(msg){
    var content = "コンテンツ読み込み時にエラーが発生しました。<br/>お手数ですが、トップからやり直してください。<br />" + msg + "<br /><br /><div style='text-align:center'><a href='" + URL_TOP + "'>TOPへ戻る</a></div>";
    // 表示。
    var d = new Dialogue();
    d.autoClose = false;
    d.veilClose = false;
    d.content(content);
    d.show();

    //エラーはどんなコンテンツより全面に出す。
    $("#dialogue-appearance").css("z-index", 1000);


}

//---------------------------------------------------------------------------------------------------------
/**
 * loadingつきでサウンドを読み込む
 * 各画面で個別のサウンド処理がある場合にはオーバーライドして実装すること。
 */
DisplayCommon.prototype.soundLoad = function(callback){
    var self = this;

    var canvas = document.getElementById("container");

    // 実際のサイズを格納（この値に合わせる）
    var scale = window.devicePixelRatio; // この値を1にすると、Retinaディスプレイではぼやけるようになります

    // キャンバスのサイズ調整。普通にcanvasのstyleで調整したいとこだが、ボタン領域が追随してくれない。
    canvas.style.zoom = ((self.devicewidth / (self.devicewidth * scale)) * 100) + "%";

    if(self.canvas_margin > 0)
        canvas.style.top = (self.canvas_margin / ((self.devicewidth / (self.devicewidth * scale)) * 100)) + "px";

    pex = new Pex(swf, canvas, {
        "width": self.devicewidth * scale,
        "height": self.deviceheight * scale,
        "debug": false,
        "disableFrameSkip": false,
        "delayEval" : true,
        "enableButton": true,
        "enableTouch": true,
        "transparent": this.transparent,
        "partialDraw": PartialDraw, //true推奨だがアニメーションが動かない場合はfalseを設定する
        "shapeDetail": this.shapeDetail,
        "cacheColoredImage" : true ,
        "onerror" : function(msg) { 
            self.onerror(msg);
        },
        "stopOnStart": true
    });

    self.beforeStart();

    // SWF の再生準備が整ったタイミングで再生を開始する。でないとクエストがうまく動かなかった。
    pex.getAPI().ready(function() { 
        $("#mini_loading").hide();
        pex.getAPI().engineStart(); 

        //clientWidthをここで渡しておく。全SWF共通処理。
        pex.getAPI().setVariable("/", "clientWidth", self.devicewidth);

        //web audio APIを使う場合、サウンドを読み込む
        audio.sndfx = new webaudio;

        //読みこむファイルの個数
        audio.sndfx.all_file_count = selist.length;

        self.swfstart(pex, callback);

        //タッチ用イベントハンドラ
        canvas.addEventListener("touchstart", eval(self.ClassName+".touchHandler"), false);
        canvas.addEventListener("touchmove", eval(self.ClassName+".touchHandler"), false);
        canvas.addEventListener("touchend", eval(self.ClassName+".touchHandler"), false);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * swfスタート処理があるならオーバーライドして記述する
 * 
 */
DisplayCommon.prototype.swfstart = function(pex, callback) {
    audio.sndfx.sound_loaded = 1;
    callback();
}

//---------------------------------------------------------------------------------------------------------
/**
 * swfスタート前に処理があるならオーバーライドして記述する
 * 
 */
DisplayCommon.prototype.beforeStart = function(e) {

}
//---------------------------------------------------------------------------------------------------------
/**
 * タッチハンドラがあるならオーバーライドして記述する
 * 
 */
DisplayCommon.prototype.touchHandler = function(e) {
    
}

//---------------------------------------------------------------------------------------------------------
/**
 * startメソッドから自動的に呼ばれる画面HTML作成用メソッド。
 * 基底では何もしないのでオーバーライドして使う。
 *
 * startから直接呼ばれるかsoundLoadのコールバックとして呼ばれる。
 * 後者の場合、thisが仕えないのでこの関数内ではthisは使わない方が無難。
 * 
 */
DisplayCommon.prototype.reload = function() {

    //loading表示ストップ
    $("#mini_loading").hide();

    //データバインド関連処理が無い場合は直接onLoadedをコール
    if(this.defaultListId == undefined && this.defaultEntryId == undefined) 
        this.onLoaded();

}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定されたデータをリスト表示する。
 *
 * @param   リスト表示したいデータの配列。
 * @param   エントリ要素を空にするかどうか。指定しない場合は空にする。
 */
DisplayCommon.prototype.refreshList = function(list, isEmpty) {

    // リスト要素とエントリ要素はメンバ変数を使う。
    listId = this.defaultListId;
    entryId = this.defaultEntryId;
    if(isEmpty == undefined)  isEmpty = true;

    // リスト要素をjQueryで取得。
    var list$ = $("#" + listId);

    // リストを一旦空に。
    if(isEmpty)
        list$.empty();

    // エントリ要素を一つずつ作成・表示していく。
    for(var i = 0, entry ; entry = list[i] ; i++) {

        // エントリを表示するボードを作成。
        var board = this.generateEntryBoard(entry, entryId);

        // クローンした要素をリストに追加。
        list$.append(board);
    }
    
    //---------------------------------------------------------------------------------------------------------
    /*
     * すべての読み込みが完了したかどうかを監視して、onLoadedイベントを発火する
     * これによりonLoadedに来た時は確実に読み込みが終ったことを保証する。
     * そのためにはsetupEntryBoardをオーバーライドして完了時に親をコールすること。
     */
    var self = this;
    var tRL = setInterval(function(){
        if(self.counter >= i){
            clearInterval(tRL);

            if(self.scroll_loading == true){
                var dom = $("<div>");
                dom.css("width","100%");
                dom.css("height","35px");
                dom.css("line-height","35");
                dom.css("text-align","center");
                dom.css("float","left");
                dom.attr("id","scroll_loading");
                var domimg = $("<img>");
                domimg.attr("src",AppUrl.asset("/img/parts/yajirushi.png"));
                domimg.css("width", "35px");
                dom.append(domimg);
                list$.append(dom);
            }

            //onLoadedイベントを発火
            self.onLoaded();

            return;
        }
    },200);
}

//---------------------------------------------------------------------------------------------------------
/**
 * エントリを表示するボードを作成する。
 *
 * @param   エントリのデータ。
 * @param   各エントリとなる要素のid。
 * @return  作成したエントリ要素(jQuery)。
 */
DisplayCommon.prototype.generateEntryBoard = function(entry, entryId) {

    // テンプレートをクローン。
    var board = Juggler.generate(entryId);

    // エントリデータに合わせてセットアップ。
    this.setupEntryBoard(entry, board);

    return board;
}

//---------------------------------------------------------------------------------------------------------
/*
 * 子で読み込みが終わったタイミングで親をコールすれば読み込み完了カウンターをインクリメントする。
 * 
 *
 */
DisplayCommon.prototype.setupEntryBoard = function(entry, board) {
    this.counter++;

    //console.log("setupEntryBoard end.." + this.counter);
}

//---------------------------------------------------------------------------------------------------------
/*
 * EntryBoardがすべて表示し終わった時のイベントハンドラ
 * 
 *
*/
DisplayCommon.prototype.onLoaded = function() {

    //にじよめの場合は専用APIでヘッダをどける
    if(window["nijiyome"]){
        nijiyome.ui({"method":"scroll", "x":0, "y":0});
    }

    $("#click_gurde").hide();

    console.log("onLoaded start..");
}

//---------------------------------------------------------------------------------------------------------
/*
 * 追加読み込み時にローディング表示をする
 * 
 *
*/
DisplayCommon.prototype.showScrollLoading = function(scroll) {
    var self = this;
console.log("showScrollLoading");
    $("#scroll_loading").show();
    $("#scroll_loading").html("");

    var dom = $("<img>");
    dom.attr("src",AppUrl.asset("/img/parts/loading_icon.gif"));
    //dom.css("height","15px");

    $("#scroll_loading").append(dom);

    scroll.refresh();

}
//---------------------------------------------------------------------------------------------------------
/*
 * 追加読み込み時にローディング表示を消す
 * 
 *
*/
DisplayCommon.prototype.hideScrollLoading = function(scroll) {
    var self = this;
console.log(self.timeout_id);
    // ------------------------------------------------------------
    // すでに動いているタイマーを停止する
    // ------------------------------------------------------------
    if(self.timeout_id !== null){
      	// setTimeout() メソッドの動作をキャンセルする
      	clearTimeout(self.timeout_id);

      	self.timeout_id = null;
    }
    //ちょっと遅延させてから消す
    $(function(){
        self.timeout_id =setTimeout(function(){
            $("#scroll_loading").remove();
            scroll.refresh();
        },2000);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * BGイメージを設定する
 */
DisplayCommon.prototype.setBgImage = function(image_name, top) {
    var self = this;

    //プリロード利用時にcss指定できないので・・
    $("#canvas_bg").css("transform", "scale(" + self.devicewidth / 750 + ")");
    $("#canvas_bg").css("transform-origin", "0px 0px");

    top = 0;

    $("#canvas_bg").css("top", top + "px");

    //バトル背景(flashから呼んでるため・・)
    if(image_name == "battle_bg"){
        if(PLATFORM_TYPE == "nati"){
            //画像をちょっと拡大する
            $("#canvas_bg").css("transform", "scale(" + self.devicewidth / 680 + ")");
            $("#canvas_bg").css("transform-origin", "-20px 0px");
        }
        $("#canvas_bg").css("top", "0px");
    }
    //背景初期化
    $("#canvas_bg").empty();
    $("#bg_front").empty();

    $("#canvas_bg").html(Page.preload_image[image_name]);

}

//---------------------------------------------------------------------------------------------------------
/**
 * 汎用ポップアップを表示する。
 */
DisplayCommon.prototype.showMessage = function(text, callback) {
    var d = new Dialogue();

    Page.setParams("pop_d", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(PopupHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();

    $("#popup_body").html(text);

    //コールバックが無い場合はただ閉じる
    if(callback == null){
        callback = function(){
            sound("se_btn");
            PopupDisplay.destroy();
        }
    }

    $("#popup-close").off('click').on('click',callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 汎用確認ポップアップを表示する。
 */
DisplayCommon.prototype.showConfirm = function(text, callback) {
    var d = new Dialogue();

    Page.setParams("popup_d", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(PopupConfirmHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.opacity = 0.5;

    d.show();

    $("#confirm_body").html(text);

    $("#popupconf-ok").off('click').on('click',callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ヘルプを呼び出す
 */
DisplayCommon.prototype.onHelpShow = function(help_id) {
    sound("se_btn");
    var detail_d = new Dialogue();

    Page.setParams("help_id", help_id);
    Page.setParams("detail_me", detail_d);

    detail_d.appearance('<div ><div key="dialogue-content"></div></div>');
    detail_d.content(HelpDetailHtml);

    detail_d.autoClose = false;
    detail_d.veilClose = false;
    detail_d.top = 0;

    detail_d.show();
}

//---------------------------------------------------------------------------------------------------------
/*
  destroyメソッドを定義する
*/
DisplayCommon.prototype.destroy = function (){
}

