/**
 * webaudioクラスのインスタンスを保持し、audioタグとあわせて扱うためのクラス
 * 
 * BGM、SE、ボイス合わせて鳴らしたり、止めたり、ミュートしたりする。
 */
function audio() {
    this.currBgm = undefined;
    this.currVoice = undefined;
    this.currSE = undefined;
    this.last_se_time = 0;


    //BGMのボリューム
    this.default_vol = 1;
   //SE,ジングルのボリューム
    this.default_se_vol = 0.6;
   //voiceのボリューム
    this.default_voice_vol = 1;

}

//---------------------------------------------------------------------------------------------------------
/**
 * webaudioAPI、audioタグともに初期化する。
 * 
 * @param bgm_continue_flg 初期化からBGMを外すかどうか。
 *                         大体はBGMもストップだがBGMのみ継続したい場合はtrueを指定されたい。
 */
audio.prototype.init = function(bgm_continue_flg){

    //BGMストップ
    if(bgm_continue_flg != true){
        this.sound_stop();
        this.currBgm = undefined;
    }

}

//---------------------------------------------------------------------------------------------------------
/**
 * サウンドを鳴らす。
 * 
 * fscommand2("JavaScript", "sound", "BGM_b01_menu");
 * のようにSWF側から呼び出す。一回きりで連続で鳴らせる。
 * 
 * SP版ではiphoneで鳴らないのでかならずtouchendのイベントから呼びだすこと。
 */
audio.prototype.sound = function(soundname)
{
    //ミュートしていない場合のみ
    if(audio.isMute() == false){
        try {
            if (typeof(Unity) === 'undefined') {
                Unity = {
                    call: function(msg) {
                        var iframe = document.createElement('IFRAME');
                        iframe.setAttribute('src', 'unity:' + msg);
                        document.documentElement.appendChild(iframe);
                        document.documentElement.removeChild(iframe);
                    }
                };
            }

            //noneが送られてきた場合はstopとみなす。
            if(soundname == "none"){
                audio.sound_stop();
                return;
            }

            if(soundname.startsWith("bgm_")){
                //現在の再生ＢＧＭを書き換え
                this.currBgm = soundname;

                Unity.call('bgm_start-' + soundname  + "-" + audio.default_vol);
            }else{

                if(soundname.startsWith("v_")){
                      Unity.call('sound-' + soundname + "-" + audio.default_voice_vol);
                      audio.sndfx.play_count[soundname]++;
                }else{
                      if(this.currSE == soundname){
console.log("soundname=" + soundname);
console.log("se time=" + (Date.now() - this.last_se_time)); 
                          if((Date.now() - this.last_se_time) < 100){
console.log("se skip! :" + soundname);
                              return;
                          }
                      }

                      this.currSE = soundname;
                      this.last_se_time = Date.now();

                      Unity.call('sound-' + soundname + "-" + audio.default_se_vol);
                      audio.sndfx.play_count[soundname]++;
                }
            }

        }
        catch (e) {
            alert(e);
        }

    }
}

//---------------------------------------------------------------------------------------------------------
/*
 * サウンドを止める。(BGMのみ)
 * 
 */
audio.prototype.sound_stop = function()
{
console.log("sound_stop call..")

    try {
        if (typeof(Unity) === 'undefined') {
            Unity = {
                call: function(msg) {
                    var iframe = document.createElement('IFRAME');
                    iframe.setAttribute('src', 'unity:' + msg);
                    document.documentElement.appendChild(iframe);
                    document.documentElement.removeChild(iframe);
                }
            };
        }

        Unity.call('bgm_stop');
    }
    catch (e) {
        alert(e);
    }
}

//---------------------------------------------------------------------------------------------------------
/*
 * サウンドを止める(SE、ボイス用)
 * 
 */
audio.prototype.sound_stop_fx = function(soundname)
{
    //if(soundname != undefined)
    //    this.sndfx.stop(soundname);

}

//---------------------------------------------------------------------------------------------------------
/*
 * ブラウザ表示がホームボタンなどで隠れたりきり変わったりした時のイベントハンドラ
 * ＢＧＭがでっぱなしにならないようにネイティブアプリのように音を止めてしまう。
 * ＳＥとかの単発音をストップしてもしょうがないのでＡｕｄｉｏタグのみ
 */
audio.prototype.visibilitychange = function() {

}

//---------------------------------------------------------------------------------------------------------
/*
 * ミュート状態であるかどうかを判定して返す。
 * 
 */
audio.prototype.isMute = function (){
    if(Page.getStorage('mute_condition') == '1')
      return true;
    else
      return false;
}

//---------------------------------------------------------------------------------------------------------
/*
 * ミュート状態にしたり、ミュートを解除したりする。
 * 
 */
audio.prototype.mute = function() {
    if(audio.isMute() == false){
        Page.setStorage('mute_condition', '1');

        this.sound_stop();
        
    }else{
        Page.setStorage('mute_condition', '0');

        //webオーディオは再生
        if(document.getElementById(audio.currBgm) != undefined)
            this.sound(audio.currBgm);

    }
}

//---------------------------------------------------------------------------------------------------------
/*
 * ボイスミュート状態であるかどうかを判定して返す。
 * 
 */
audio.prototype.isVMute = function (){
    if(Page.getStorage('voice_mute_condition') == '1')
      return true;
    else
      return false;
}

//---------------------------------------------------------------------------------------------------------
/*
 * ボイスミュート状態にしたり、ミュートを解除したりする。
 * 
 */
audio.prototype.voice_mute = function() {
    if(audio.isVMute() == false){
        Page.setStorage('voice_mute_condition', '1');
    }else{
        Page.setStorage('voice_mute_condition', '0');
    }
}
