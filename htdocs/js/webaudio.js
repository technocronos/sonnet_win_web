/*
 *  web audio apiを使うためのライブラリ
 *　BGMはAudioタグを使うのでこちらではコントロールしない。
 *
 *　BGM、SE、ボイス含めたサウンド全体の制御はaudio.jsで行う。
 */

//---------------------------------------------------------------------------------------------------------
/**
 * コンストラクタ
 * AudioContextは1回newしたらdestroyはできない。よって必ず全部通して1回のみnewして使いまわすこと。
 * （1回で落ちることはないが6回くらいnewしたら落ちます）
 */
var webaudio = function(){
    this.ctx = (function(){
                    window.AudioContext = window.AudioContext || window.webkitAudioContext;
                    return new AudioContext();
                })();

    this.vol = 1;
    this.init();

};

//---------------------------------------------------------------------------------------------------------
/**
 * 変数を初期化することによって疑似的に初期化する。
 */
webaudio.prototype.init = function(){
    this.all_file_count = 0;
    this.curr_file_count = 0;
    this.file_buf_count = 0;

    this.sound_loaded = 0;
    this.play_count = {};

    this.buffers = {};
    this.src = {};
}

//---------------------------------------------------------------------------------------------------------
/**
 * サウンドをロードする
 */
webaudio.prototype.load = function(arg, pex, callback){

    for(i=0,il=arg.length;i<il;i++){

        (function(target){
            var req = new XMLHttpRequest();
            req.open("GET", arg[i].src, true);
            req.responseType = "arraybuffer";
            req.alias = arg[i].alias;

            //ロード時イベント
            req.onload = function() {
                if(req.response) {
                    target.ctx.decodeAudioData(req.response,function(b){
                        target.buffers[req.alias] = b;
                        target.play_count[req.alias] = 0;
                        console.log(req.alias + " is OK!");
                        target.file_buf_count++;

                        //全部の受信ファイルのバッファが完了したら教える
                        if(target.file_buf_count == target.all_file_count){
                            var txt = "準備完了。タップしてください。";
                            if(pex != null){
                                pex.getAPI().setVariable("/", "progress_txt", txt);
                                pex.getAPI().setVariable("/main", "progress_txt", txt);
                                pex.getAPI().setVariable("/", "sound_loaded", 1);
                            }
                            target.sound_loaded = 1;
                            if(callback != null){
                                setTimeout(function(){
                                    callback();
                                }, 0);
                            }
                        }

                    },function(e){
                        console.log("Error with decoding audio data " + e + req.alias);
                    });
                }
            }
            //進捗時イベント
            req.onprogress = function(e){
                console.log("ファイル受信中: " + e.loaded + " / " + e.total);
                var txt = "ファイル受信中: " + e.loaded + " / " + e.total;
                if(pex != null){
                    pex.getAPI().setVariable("/", "progress_txt", txt);
                    pex.getAPI().setVariable("/main", "progress_txt", txt);
                }
            }
            req.onloadend = function(e){
                target.curr_file_count++;
                console.log("受信が完了" + target.curr_file_count + "/" + target.all_file_count );
                var txt = "サウンドファイル展開中... ";
                if(pex != null){
                    pex.getAPI().setVariable("/", "progress_txt", txt);
                    pex.getAPI().setVariable("/main", "progress_txt", txt);
                }
            };
            req.send();
        })(this);

    };
};


/*
  オーディオを再生する。
*/
webaudio.prototype.play = function(alias,loop){
    
    if (this.buffers[alias] == undefined)
        return false;

    //まだロードが完了してない
    if(this.sound_loaded != 1)
        return false;

    this.src[alias] = this.ctx.createBufferSource();
    this.src[alias].buffer = this.buffers[alias];
    this.src[alias].loop = loop;

    this.src[alias].gainNode = this.ctx.createGain();

    //非推奨のため
    //this.src[alias].gainNode.gain.value = this.vol;
    this.src[alias].gainNode.gain.setTargetAtTime(this.vol, this.ctx.currentTime, 0.015);

    this.src[alias].connect(this.src[alias].gainNode);
    this.src[alias].gainNode.connect(this.ctx.destination);

    this.src[alias].start(0);

    this.play_count[alias]++;
}

//---------------------------------------------------------------------------------------------------------
/**
 *  ボリュームを変更する。
 *
 *  BGMの音量制御はこれではできないので注意
 */
webaudio.prototype.volume = function(vol){

    this.vol = vol;

}

//---------------------------------------------------------------------------------------------------------
/*
 * プレイを停止する。
 */
webaudio.prototype.stop = function(alias){

    if(this.src[alias] != undefined)
        this.src[alias].stop(0);

}


//---------------------------------------------------------------------------------------------------------
/*
 * プレイ回数を返す。
 */
webaudio.prototype.play_cnt = function(alias){

    if(this.play_count[alias] == 0 || this.play_count[alias] == undefined)
        return 0;
    else
        return this.play_count[alias];
}

//---------------------------------------------------------------------------------------------------------
/*
 * プレイ回数をリセットする。
 */
webaudio.prototype.reset_play_cnt = function(alias){
    this.play_count[alias] = 0;
}

