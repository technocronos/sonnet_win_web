
/**
 * スフィアFlashを制御するシングルトンオブジェクト。
 *
 */
function SphereContentsDisplay(){
    //コンストラクタ名を書き換える
    Object.defineProperty(SphereContentsDisplay.prototype, 'constructor', {
        value : SphereContentsDisplay,
        enumerable : false
    });

    this.first_flg = true;

    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "SphereContentsDisplay";

    //this.shapeDetail = { all: { method: "func" } }

    this.flick_flg = true;

    this.top_over = false;
    this.left_over = false;
    this.right_over = false;
    this.bottom_over = false;

    this._touchX = null;
    this._touchY = null;

    this.contentheight = 0;

    //スクロール無効
    $("#bottom-div").on('touchmove.noScroll', function(e) {
        e.preventDefault();
    });

    $(window).scrollTop(0);
}

// 親クラスを継承
SphereContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
SphereContentsDisplay.prototype.start = function() {
console.log("SphereContentsDisplay.start rannning...");
    var self = SphereContentsDisplay;

    self.contentheight = parseInt($("#mainflex").css("height"));
console.log(self.contentheight)

    self.super.start.call(self);
}

SphereContentsDisplay.prototype.beforeStart = function() {
/*
    //マップチップ読み込み
    //パフォーマンスが悪化したためやっぱりswfeditorで行う
    for(i = 0; i < parseInt(tips_num); i++){
        var movie_name = "bg" + (i +1) ;
        var imageUrl = eval("tips_" + i);

        changeImage(imageUrl,movie_name, 25, 25)
    }


    //ユニット読み込み
    for(i = 0; i < parseInt(units_num); i++){
        var movie_name = "unit" + (i +1) ;

        var imageUrl1 = WEB_ROOT + "img/unitTip/" + eval("units_" + i) + "_1.png";
        var imageUrl2 = WEB_ROOT + "img/unitTip/" + eval("units_" + i) + "_2.png";

        changeImage(imageUrl1,movie_name + "_1", 24, 24)
        changeImage(imageUrl2,movie_name + "_2", 24, 24)
    }
*/
}

SphereContentsDisplay.prototype.swfstart = function(pex, callback) {
    var timer = null;
    $(function(){
        timer = setInterval(function(){
        var CurrentFrame = pex.getAPI().getCurrentFrame("/");
            if(CurrentFrame == 4){
                //loading..表示タイマーストップ
                clearInterval(timer);

                pex.getAPI().setVariable("/", "sound_loaded", 1);
                audio.sndfx.sound_loaded = 1;

                callback();

                return;

            }
        },100);
    }); 
}

//---------------------------------------------------------------------------------------------------------
/**
 * html5へのタッチハンドラ。
 */
SphereContentsDisplay.prototype.touchHandler = function(e) {
    var self = SphereContentsDisplay;

    e.preventDefault();

    var touch = e.touches[0];
    var changedTouches = e.changedTouches[0];

    var structWid = pex.getAPI().getVariable("/","structWid");
    var structHei = pex.getAPI().getVariable("/","structHei");
    var TIP_SIZE = pex.getAPI().getVariable("/","TIP_SIZE");
    var STAGE_WID = pex.getAPI().getVariable("/","STAGE_WID");
    var STAGE_HEI = pex.getAPI().getVariable("/","STAGE_HEI");

    if(e.type == "touchstart"){

        //タッチ開始座標をとっておく
        touchstartX = touch.pageX;
        touchstartY = touch.pageY;

        //開始時点の_offset値をとっておく
        _x = pex.getAPI().getVariable("/stage","_offsetX");
        _y = pex.getAPI().getVariable("/stage","_offsetY");

        //座標を渡しておく
        pex.getAPI().setVariable("/user/pointR", "touchstartX", touch.pageX);
        pex.getAPI().setVariable("/user/pointR", "touchstartY", touch.pageY);
        if(carrier == 'android'){
            pex.getAPI().gotoFrame("/user/pointR", "onTouchStart");
        }
    }
    if(e.type == "touchmove"){
          var touchX = parseInt(_x + ((touch.pageX - touchstartX) * (STAGE_WID / document.body.clientWidth)));
          var touchY = parseInt(_y + ((touch.pageY - touchstartY) * (STAGE_WID / document.body.clientWidth)));

          if(self._touchX != null){
              //右に進んでいる場合
              if(self._touchX > touchX){
                  if(self.right_over == true)
                      touchX = self._touchX;
                  //右に進んでいるのに左ロックがかかっている場合、左ロックは解除
                  if(self.left_over == true)
                      self.left_over = false;
              //左に進んでいる場合
              }else{
                  //これ以上進めない場合
                  if(self.left_over == true)
                      touchX = self._touchX;
                  //左に進んでいるのに右ロックがかかっている場合、右ロックは解除
                  if(self.right_over == true)
                      self.right_over = false;
              }
          }

          if(self._touchY != null){
              //下に進んでいる場合
              if(self._touchY > touchY){
                  //これ以上進めない場合
                  if(self.bottom_over == true)
                      touchY = self._touchY;
                  //下に進んでいるのに上ロックがかかっている場合、上ロックは解除
                  if(self.top_over == true)
                      self.top_over = false;
              //上に進んでいる場合
              }else{
                  //これ以上進めない場合
                  if(self.top_over == true)
                      touchY = self._touchY;
                  //上に進んでいるのに下ロックがかかっている場合、下ロックは解除
                  if(self.bottom_over == true)
                      self.bottom_over = false;
              }
          }

          self._touchX = touchX;
          self._touchY = touchY;

          pex.getAPI().setVariable("/user/pointR", "gainX", touchX);
          pex.getAPI().setVariable("/user/pointR", "gainY", touchY);

          //フリックスタート
          pex.getAPI().setVariable("/user/pointR","flick_flg",1);

          pex.getAPI().gotoFrame("/user/pointR", "onFlick");

          //_offset値を変更する
          pex.getAPI().setVariable("/stage","_offsetX",touchX);
          pex.getAPI().setVariable("/stage","_offsetY",touchY);

    }
    if(e.type == "touchend"){
        pex.getAPI().setVariable("/user/pointR", "touchendX", changedTouches.pageX);
        pex.getAPI().setVariable("/user/pointR", "touchendY", changedTouches.pageY);
        if(carrier == 'iphone'){
            pex.getAPI().gotoFrame("/user/pointR", "onTouchEnd");
        }
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * スフィアから上下左右のフリック限界に達したかどうかを受け付ける
 */
SphereContentsDisplay.prototype.flick_controller = function (str){
    var self = SphereContentsDisplay;

    if(str == "top_over"){
        self.top_over = true;
    }else if(str == "left_over"){
        self.left_over = true;
    }else if(str == "right_over"){
        self.right_over = true;
    }else if(str == "bottom_over"){
        self.bottom_over = true;
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * スフィアからコマンドを受け付けて処理する
 */
SphereContentsDisplay.prototype.command = function (mv, get){
console.log("SphereContentsDisplay.command run..");

console.log(get);

    var self = SphereContentsDisplay;

    //コマンド情報を取得する。
    SphereApi.command(get, function(response){
        console.log(response);

        //レスポンスを一気に渡す
        $.each(response, function(key, value){
            //mitterにレスポンスを返す
            pex.getAPI().setVariable(mv, key, value);
        });
    });
}
//---------------------------------------------------------------------------------------------------------
/**
 * アイテムリストポップアップを立ち上げる
 */
SphereContentsDisplay.prototype.itemlist = function (get_param){
console.log("SphereContentsDisplay.itemlist run..");
console.log(get_param);

    var d = new Dialogue();

    Page.setParams("get_param", get_param);
    Page.setParams("me", d);

    d.appearance('<div><div key="dialogue-content"></div></div>');
    d.content(SphereItemListHtml);

    d.autoClose = false;
    d.veilClose = false;
    d.top = 80;

    d.show();
}
//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
SphereContentsDisplay.prototype.reload = function (){
    var self = SphereContentsDisplay;

    //セーフエリアを取得
    var safeareatop = getComputedStyle(document.documentElement).getPropertyValue("--sat");
    safeareatop = parseInt(safeareatop.replace("px", ""));

    if(isNaN(safeareatop)){
        safeareatop = 0;
    }

    pex.getAPI().setVariables("/", {"SAFE_MODE_MARGIN":safeareatop});

    //メインウィンドウ位置調整
    Page.setSphereWin(pex.getAPI(), self.contentheight);

    $("#flex").hide();

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
SphereContentsDisplay.prototype.onLoaded = function() {
    var self = SphereContentsDisplay;


    pex.getAPI().gotoFrame("/", "onFirstTap");
    sound(bgm);

    $(window).scrollTop(0);

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
SphereContentsDisplay.prototype.destroy = function (){
console.log("SphereContentsDisplay.destroy run..");
    var self = SphereContentsDisplay;

    pex.getAPI().destroy();

    self.super.destroy.call(self);
    SphereContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var SphereContentsDisplay = new SphereContentsDisplay();

$(document).ready(SphereContentsDisplay.start.bind(SphereContentsDisplay));

