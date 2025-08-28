
/**
 * バトルFlashを制御するシングルトンオブジェクト。
 *
 */
function FieldEndContentsDisplay(){
    this.super = DisplayCommon.prototype;

    //クラス名（子クラスで書き換えること）
    this.ClassName = "FieldEndContentsDisplay";

    this.touchend_disable = false;
    this.flick_flg = true;
    this.currRegion = "";

    $(window).scrollTop(0);
}

// 親クラスを継承
FieldEndContentsDisplay.prototype = new DisplayCommon();

//---------------------------------------------------------------------------------------------------------
/**
 * ページが読み込まれたら呼ばれる。
 */
FieldEndContentsDisplay.prototype.start = function() {
console.log("FieldEndContentsDisplay.start rannning...");
    var self = FieldEndContentsDisplay;

    self.super.start.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * html5へのタッチハンドラ。
 */
FieldEndContentsDisplay.prototype.touchHandler = function(e) {
    e.preventDefault();

    var touch = e.touches[0];
    var changedTouches = e.changedTouches[0];

    var STAGE_WID = pex.getAPI().getVariable("/","STAGE_WID");
    var path = "/fieldendmenu/itemdrop/list";

    var top = 0;
    var bot = 0;

    if(e.type == "touchstart"){

          touchstartX = touch.pageX;
          touchstartY = touch.pageY;

          entryNum = parseInt(pex.getAPI().getVariable(path,"entryNum"));
          INTERVAL = parseInt(pex.getAPI().getVariable(path,"INTERVAL"));
          _LINELIST_X = parseInt(pex.getAPI().getVariable(path,"_LINELIST_X"));

          LINES = parseInt(pex.getAPI().getVariable(path,"LINES"));
          flick_flg = pex.getAPI().getVariable(path,"flick_flg");
          LENGTH = parseInt(pex.getAPI().getVariable(path +"/scroll","LENGTH"));
          OFFSET = parseInt(pex.getAPI().getVariable(path +"/scroll","OFFSET"));

          _y = parseInt(pex.getAPI().getVariable(path,"_LINELIST_Y"));
          num = parseInt(pex.getAPI().getVariable(path +"/scroll", "num"));
          page = parseInt(pex.getAPI().getVariable(path +"/scroll", "page"));

          //座標を渡しておく
          pex.getAPI().setVariable("/", "touchstartX", touch.pageX);
          pex.getAPI().setVariable("/", "touchstartY", touch.pageY);
          if(carrier == 'android'){
             if(audio.sndfx.play_cnt('se_btn') == 0 && audio.sndfx.sound_loaded == 1)
                pex.getAPI().gotoFrame("/", "onFirstTap");
          }
      }
    if(e.type == "touchmove"){
          //フリックが有効なページの場合
          if(flick_flg == 1){
              var touchY = parseInt(_y + ((touch.pageY - touchstartY)* (STAGE_WID / document.body.clientWidth)));

              //Y座標可動範囲
              if(touchY > top){
                  touchY = top;
              }else if(touchY < (((entryNum * INTERVAL) + bot) - (INTERVAL * LINES)) * -1){
                  touchY = (((entryNum * INTERVAL) + bot) - (INTERVAL * LINES)) * -1;
              }
  console.log("touchY=" + touchY);
              pex.getAPI().setPosition(path + "/linelist", _LINELIST_X, touchY);
              pex.getAPI().setVariable(path, "_LINELIST_Y", touchY);

              pos = (touchY / INTERVAL) * -1;

            	// スクロールガイドの初期化。
              pex.getAPI().setVariable(path +"/scroll", "pos", pos);

              // 1ページで全体を表示できるならガイドは必要ない。
              if(num == 0  ||  num <= page) {
                  pex.getAPI().setVisible(path +"/scroll", false);
              }else {
                  // バーの位置を設定。
                  if(pos > num - page)  pos = num - page;
                  bar_y = OFFSET + LENGTH * pos / num;
              	  pex.getAPI().setPosition(path +"/scroll/bar",1,bar_y);
                	// 表示。
                  pex.getAPI().setVisible(path +"/scroll", true);
              }
          }
      }
    if(e.type == "touchend"){
          pex.getAPI().setVariable("/", "touchendX", changedTouches.pageX);
          pex.getAPI().setVariable("/", "touchendY", changedTouches.pageY);
          if(carrier == 'iphone'){
             if(audio.sndfx.play_cnt('se_btn') == 0 && audio.sndfx.sound_loaded == 1)
                pex.getAPI().gotoFrame("/", "onFirstTap");
          }

          //BGMを鳴らす
          if(audio.currBgm == undefined)
              sound('bgm_menu');
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * メイン処理
 */
FieldEndContentsDisplay.prototype.reload = function (){
    var self = FieldEndContentsDisplay;

    self.super.reload.call(self);
}

//---------------------------------------------------------------------------------------------------------
/*
 * すべて表示し終わった時のイベントハンドラ
 *
*/
FieldEndContentsDisplay.prototype.onLoaded = function() {
    var self = FieldEndContentsDisplay;

    self.super.onLoaded.call(self);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ページのオブジェクトを破棄する。
 */
FieldEndContentsDisplay.prototype.destroy = function (){
    this.super.destroy.call(this);
    FieldEndContentsDisplay = null;
}

//---------------------------------------------------------------------------------------------------------
var FieldEndContentsDisplay = new FieldEndContentsDisplay();

$(document).ready(FieldEndContentsDisplay.start.bind(FieldEndContentsDisplay));

