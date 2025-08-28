/*
 * 主にSWFから呼び出されることを想定した関数群
*/

//メッセージ表示用。
//fscommand2("JavaScript", "showmsg", "TEST OK");
//のようにSWF側から呼び出す
function showmsg(msg)
{
    console.log(msg);
}

//---------------------------------------------------------------------------------------------------------
/**
 * サマリー用API
 */
function home_summary(){
    //ホームサマリ情報を取得する。
    HomeApi.summary(null, null, null, function(response){
        console.log(response);
        self.summary = response;

        //再帰処理で全データを一次元にする
        parse_arr = {};
        AppUtil.array_parse(response,"");
        console.log(parse_arr);

        //再帰処理したものを一気に渡す
        $.each(parse_arr, function(key, value){
            pex.getAPI().setVariable("/", key, value);
        });
    });
}


//---------------------------------------------------------------------------------------------------------
/**
 * サマリー更新する。nativeから呼ばれる想定。
 */
function update_home_summary(){
console.log("update_home_summary run..");
    HomeApi.summary(null, null, null, null, function(summary){
        console.log(summary);
        //サマリを格納しておく
        Page.setSummary(summary);

        //各コンテンツ更新
        $("#CoinCount").html(summary["coin"]);
        $("#gold").html(summary["coin"]);
    });
}

/*
  画像を動的に差し替える
*/
function changeImage(imageUrl,movie_name, sizeW, sizeH, phase_movie){
    //マップを読んでおく
    var image = new Image();
    image.src = AppUrl.asset(imageUrl);

    image.onload = function() { 
        pex.getAPI().replaceMovieClip(movie_name, image, sizeW, sizeH, true, 0, 0);
        if(phase_movie != "" && phase_movie != undefined){
            //phase_movieに終了を伝える
            pex.getAPI().setVariable(phase_movie, "change", "ok");
        } 
    }
    
}

//---------------------------------------------------------------------------------------------------------
/**
 * キャラのグラフィックを差し替える(重いため未使用)
 */
function getAvator(phase_movie, prefix, user_id){
    CharaApi.avator(prefix, user_id, null, null, function(response){
        console.log(response);
        console.log("phase_movie =" + phase_movie);

        var counter = 0;

        $.each(response, function(key, url){
            if(url != "none"){
                var image = new Image();
                image.src = url;
                image.onload = function() { 
                    pex.getAPI().replaceMovieClip(key, image, 80, 100, true, 0, 0);
                    counter++;
                }
               	// 表示。
                pex.getAPI().setVisible(phase_movie + key, true);
            }else{
               	// 表示。
                pex.getAPI().setVisible(phase_movie + key, false);
                counter++;
            }
        });

        var tRL = setInterval(function(){
            if(counter >= 7){
                clearInterval(tRL);

                //phase_movieに終了を伝える
                pex.getAPI().setVariable(phase_movie, "change", "ok");

                return;
            }
        },200);

    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * デバイスの情報を返す
 *
 */
function getDeviceInfo(phase_movie){
    var devicewidth = $(window).width();
    var deviceheight = $(window).height();

    //swfの240に換算した場合の高さを得る
    stage_height = (deviceheight * 240) / devicewidth;

    //iosの354を上限とする。
    if(stage_height > 354){
        stage_height = 354;
    }
console.log(stage_height);
    pex.getAPI().setVariable(phase_movie, "device_width", devicewidth);
    pex.getAPI().setVariable(phase_movie, "device_height", deviceheight);
    pex.getAPI().setVariable(phase_movie, "stage_height", stage_height);

}

function unescape_str(phase_movie, variable, value){
console.log("decodeUrl run..");

    decode_value = decodeURIComponent(value);

console.log(decode_value);

    pex.getAPI().setVariable(phase_movie, variable, decode_value);
}

//---------------------------------------------------------------------------------------------------------
/**
 * サウンド再生用。
 * fscommand2("JavaScript", "sound", "BGM_b01_menu"); 
 * のようにSWF側から呼び出す。
 *
 */
function sound(soundname){
    console.log("sound " + soundname);
    audio.sound(soundname);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ボイス再生用。
 * fscommand2("JavaScript", "voice", "Voice_b01_menu"); 
 * のようにSWF側から呼び出す。
 *
 */
function voice(soundname){
    //console.log("voice " + soundname);
    //audio.sound(soundname, true);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 今、再生されているサウンドを停止する。
 * fscommand2("JavaScript", "sound_stop");
 * のようにSWF側から呼び出す。
 */
function sound_stop(){
    audio.sound_stop();
}


//---------------------------------------------------------------------------------------------------------
/**
 * グローバルのBGM変数を書き換える
 * 
 * SWF内部でsound_stopし、別のBGMを鳴らしたい場合はこの関数で指示すると
 * 次のタップアクションでそのBGMが鳴るようになる。今の所バトル専用。
 * 
 */
function change_bgm(change_bgm_name){
    //現在のBGMは初期化しておいてまた次のタップで鳴らせるようにする。
    audio.currBgm = undefined;
    bgm = change_bgm_name;
}
