
/**
 * クエストを制御するシングルトンオブジェクト。
 *
 */
function QuestDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(QuestDisplay.prototype, 'constructor', {
        value : QuestDisplay,
        enumerable : false
    });

    this.scroll = undefined;

    //スクロール無効
    $("#QuestContents").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    //カーソル
    this.CursorCanvas = null;
    //マップポイント
    this.PointCanvas = new Object();

    this.super = DisplayCommon.prototype;

    //コンストラクタ名を書き換える
    Object.defineProperty(QuestDisplay.prototype, 'constructor', {
        value : QuestDisplay,
        enumerable : false
    });

    this.questlist = {};
    this.currRegion = 0;
    this.currPlace = 0;

    this.map_scale = 1.7;
    this.x_margin = 40;

    this.cursorX_margin = 20;
    this.cursorY_margin = 20;
    this.qNameX_margin = 60;
    this.qNameY_margin = 40;

    this.sphereId = Page.getParams("sphereId");
}

// 親クラスを継承
QuestDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
QuestDisplay.prototype.start = function() {
    var self = QuestDisplay;

    if(self.sphereId != null){
        self.showFieldEnd(self.sphereId);
        self.sphereId = null;
        return;
    }

    //クエストリスト情報を取得する。
    QuestApi.list(function(response){
        console.log(response);
        self.questlist = response;

        self.currRegion = self.questlist.currRegion;
        self.currPlace = self.questlist.currPlace;

        //androidはパフォーマンスが悪くなるため雲アニメーション無し
        if(carrier !== 'android'){
            //雲読み込み
            $("#crowd_back").empty();
            $("#crowd_back").css("left", "-100px");
            $("#crowd_back").html(Page.preload_image["crowd_back"]);

            //雲読み込み
            $("#crowd_front").empty();
            $("#crowd_front").css("left", "-100px");
            $("#crowd_front").css("top", "-100px");
            $("#crowd_front").html(Page.preload_image["crowd_front"]);
            $("#crowd_front").css("opacity", "0.5");

            $("#crowd_front").animate({
                "marginLeft": "500px"
            }, 50000).animate({
                "opacity": "0"
            });

            $("#crowd_back").animate({
                "marginLeft": "500px"
            }, 100000).animate({
                "opacity": "0"
            });
        }

        //okボタンクリック時イベントハンドラ
        $("#btn_global").off('click').on('click',function() {
            sound('se_btn');

            QuestDisplay.onChangeRegion(0, QuestDisplay.currRegion);
        });

        self.super.start.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
QuestDisplay.prototype.reload = function (){
    var self = QuestDisplay;

    //マップ読み込み
    $("#map_bg").empty();
    $("#map_bg").css("left", "-" + (self.x_margin + 30) + "px");
    $("#map_bg").html(Page.preload_image[AppUtil.padZero(self.currRegion, 2)]);

    //ポイントとタッチ領域を初期化
    $('#point_area').empty();
    $('#point_touch_area').empty();
    $('#questname_area').empty();
    $('#cursor_area').empty();

    $.each(self.questlist.place[self.currRegion],function(key, value){
        var X = parseInt(value.X) - self.x_margin;
        var Y = parseInt(value.Y);

        var num = 1;

        //ローカルマップにいる場合
        if(self.currRegion != 0 && key == 0){
            //最初の地点は青
            num = 2;
        }

        //現在いる地点
        if(key == self.currPlace){
            //カーソル作成
            $('#cursor_area').append('<canvas id="cursor_sm" style="position:absolute;"></canvas>');
            self.CursorCanvas = new CursorCanvas(self, null, (X + self.cursorX_margin) * self.map_scale, (Y - self.cursorY_margin)* self.map_scale);

            //地域名表示キャプション作成
            $('#questname_area').append('<canvas id="questname_sm" style="position:absolute;"></canvas>');
            self.QuestNameCanvas = new QuestNameCanvas(self, null, (X - self.qNameX_margin)* self.map_scale, (Y + self.qNameY_margin)* self.map_scale, value.Name);

            num = 3;
        }

        //canvasを作成する
        $('#point_area').append('<canvas id="point_sm'+key+'" style="position:absolute;"></canvas>');
        self.PointCanvas[key] = new PointCanvas(self, key, X * self.map_scale, Y * self.map_scale, num);

        //タッチ領域更新
        $('#point_touch_area').append('<div id="point_sm'+key+'_touch" style="position:absolute;width:85px;height:85px;"></div>');
        $("#point_sm" + key + "_touch").css("left", X * self.map_scale);
        $("#point_sm" + key + "_touch").css("top", Y * self.map_scale);
        //地点クリックイベントハンドラ
        $("#point_sm" + key + "_touch").off("click").on("click",function(){
            sound('se_btn');

            if(self.currRegion != 0){
                if(self.currPlace != key){
                    //ローカルマップの場合は地域名を切り替える
                    self.onChangePlace(self.currRegion, key);
                }else{
                    //クエストリストを出す。
                    self.showQuestList(self.currRegion, self.currPlace);
                }
            }else{
                if(self.currPlace != key){
                    //グローバルマップの場合は選択された地域名を再度クリックしたらローカルマップに切り替え
                    self.onChangePlace(self.currRegion, key);
                }else{
                    //移動しようとしているマップがもともといる地域の場合はその地点を選択。その他は最初の地点を選択。
                    if(self.currPlace == self.questlist.currRegion)
                        QuestDisplay.onChangeRegion(self.questlist.currRegion, self.questlist.currPlace);
                    else
                        QuestDisplay.onChangeRegion(self.currPlace, 0);
                }
            }
        });
    });

    //全canvasが読み込まれていることを保証する。
    var timer = null;
    $(function(){
        var point_count = 0;
        var point_ok = {};
        timer = setInterval(function(){
            $.each(self.questlist.place[self.currRegion],function(key, value){
                if(self.PointCanvas[key].loaded && point_ok[key] != "check"){
                    point_ok[key] = "check";
                    point_count++;
                }
            });

            if(point_count ==  self.questlist.place[self.currRegion].count().length && self.CursorCanvas.loaded){
                //loading..表示タイマーストップ
                clearInterval(timer);

                //ヘッダーキャプション更新
                if(self.currRegion != 0)
                    MainContentsDisplay.HeaderCanvas.caption(self.questlist.place[0][self.currRegion].Name);
                else
                    MainContentsDisplay.HeaderCanvas.caption("グローバルマップ");

                MainContentsDisplay.HeaderCanvas.init();

                self.super.reload.call(self);
            }
        },500);
    });
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
QuestDisplay.prototype.onLoaded = function() {
    var self = QuestDisplay;

    $("#main_contents").fadeIn("fast", function(){
        //クエストリストを出す。
        if(self.currRegion != 0)
          self.showQuestList(self.currRegion, self.currPlace);
    });

    //クエストリストのonloadedを優先したいのでこちらでは親をコールしない。
    //self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエストリストを表示する
 */
QuestDisplay.prototype.showQuestList = function (region, place){
    var self = QuestDisplay;

    //オーバーレイボタン領域表示
    var d = new Dialogue();

    Page.setParams("questlist", self.questlist);
    Page.setParams("region", region);
    Page.setParams("place", place);
    Page.setParams("questlist_d", d);

    d.appearance('<div style="padding:2em"><div key="dialogue-content"></div></div>');
    d.content(QuestListHtml);
    d.autoClose = false;
    d.veilClose = false;
    d.veilShow = true;
    d.top = 0;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * マップを切り替える
 */
QuestDisplay.prototype.onChangeRegion = function (changeRegion, currPlace){
    var self = QuestDisplay;

    console.log("changeRegion = " + changeRegion)
    console.log("currPlace = " + currPlace)

    //地域名を書き換え
    self.currRegion = changeRegion;
    self.currPlace = currPlace;

    if(self.currRegion == 0)
        $("#btn_global").hide();
    else
        $("#btn_global").show();

    //ポイントpexを解放
    $.each(self.PointCanvas, function(){
        this.destroy();
    });
    //ポイントpex格納オブジェクトを初期化
    self.PointCanvas = new Object();
    //カーソルpexを解放
    self.CursorCanvas.destroy();
    //地域名キャプションpexを解放
    self.QuestNameCanvas.destroy();

    //クエストリスト情報を取得する。
    QuestApi.list(function(response){
        console.log(response);
        self.questlist = response;
        self.reload();
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * 地域をタップしたときのイベントハンドラ
 */
QuestDisplay.prototype.onChangePlace = function (currRegion, currPlace){
    console.log("currRegion = " + currRegion)
    console.log("currPlace = " + currPlace)

    var self = QuestDisplay;
    console.log(self.questlist.place[currRegion][currPlace]);

    //地域名を書き換え
    self.currRegion = currRegion;
    self.currPlace = currPlace;

    var entry = self.questlist.place[currRegion][currPlace];

    var X = parseInt(entry.X) - self.x_margin;
    var Y = parseInt(entry.Y);

    //カーソル位置を変更
    self.CursorCanvas.pos((X + self.cursorX_margin) * self.map_scale, (Y - self.cursorY_margin)* self.map_scale);
    //名前位置を変更
    self.QuestNameCanvas.pos((X - self.qNameX_margin)* self.map_scale, (Y + self.qNameY_margin)* self.map_scale);

    //名前を更新する
    self.QuestNameCanvas.change(entry.Name);

}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト終了ポップアップを表示する
 */
QuestDisplay.prototype.showFieldEnd = function (sphereId){
    var self = QuestDisplay;
    var d = new Dialogue();

    Page.setParams("_parent", self);
    Page.setParams("sphereId", sphereId);
    Page.setParams("fieldend_me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(FieldEndHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 0;

    d.show();
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
QuestDisplay.prototype.destroy = function (){
    var self = QuestDisplay;

    //クエストリストが開きっぱなしならdestroy
    if(QuestListDisplay != null)
        QuestListDisplay.destroy();

    $("#main_contents").fadeOut("slow", function(){
        $("#main_contents").empty();

        $.each(self.PointCanvas, function(k,v){
            self.PointCanvas[k].destroy();
            self.PointCanvas[k] = null;
        });
        self.CursorCanvas.destroy();
        self.CursorCanvas = null;
        self.QuestNameCanvas.destroy();
        self.QuestNameCanvas = null;

        self.super.destroy.call(self);
    });
}

//---------------------------------------------------------------------------------------------------------
var QuestDisplay = new QuestDisplay();

$(document).ready(QuestDisplay.start.bind(QuestDisplay));

