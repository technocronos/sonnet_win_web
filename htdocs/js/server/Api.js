
/**
 * 指定されたサーバAPIを呼び出すためのユーティリティ。Server から派生している。
 * new でインスタンスを作成して requestJson() を呼んで利用する。
 *
 * @param   API名
 * @param   GET変数
 * @param   POST変数。これを指定すると自動的にPOSTメソッドになる。
 *          GETメソッドにしたい場合は null を指定されたい。
 */
function Api(url, get, post) {

    if(url != undefined)
        url = AppUtil.htmlspecialchars_decode(url);

    if(get != "" && get != null  && get != undefined){
console.log(get);
        if(typeof(get) !== 'object'){
            //jsonオブジェクトに変換
            get = $.parseJSON(get);
        }
        //クエリ文字にする
        var query = "&" + AppUtil.serialize(get)

        //コンテナ経由型URLでない場合はエスケープしない
        if(URL_TYPE == "container")
            url = url + encodeURIComponent(query);
        else
            url = url + query;
    }

    if(post != "" && post != null && post != undefined){
console.log(post);
        if(typeof(post) !== 'object'){
          //jsonオブジェクトに変換
          post = $.parseJSON(post);
        }
    }

    this.url = url;

    Server.call(this, this.url, post, true);
}

Api.prototype = Object.create(Server.prototype);

//---------------------------------------------------------------------------------------------------------

// 通信中の数。
Api.communications = 0;

// リクエストが行われると起動するイベント。
Api.onRequest = new Delegate();

// 全通信が終了したら起動するイベント。
Api.onComplete = new Delegate();

//---------------------------------------------------------------------------------------------------------
/**
 * 静的メソッド。通信をしていない場合にリクエスト完了イベントを起動する。
 * データを求められたがすべてキャッシュで返答できた場合などに呼び出す。
 */
Api.kickFinishEvent = function() {

    // すでにキックしようとしている場合は無視する。
    if(this.finishKicker)
        return;

    // 普通、通信は複数同時に求められる。後続の処理がただちに通信を開始する場合もあるので、遅延してから
    // 検査する。
    this.finishKicker = setTimeout(function(){

        // キックフラグを下ろす。
        Api.finishKicker = undefined;

        if(Api.communications == 0)
            Api.onComplete.trigger();

    }, 0);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 静的メソッド。APIコール時のエラーレスポンスを処理する。
 *
 * @param   APIのレスポンス。
 */
Api.processError = function(res) {

    // とりあえずコンソールに出力。
    console.log("api error: " + res["error"]);
    console.log(res["error_trace"]);

    // エラーコードに従ってエラーメッセージを作成。トップからやり直すための案内を付ける場合は
    // 変数 linkRetry を true にする。
    var linkRetry = false;
    switch(res["error"]) {

        case "maintenance":
            var content = "ただいまサーバーメンテナンス中です。<br />申し訳ありませんが、もうしばらくお待ちください。";
            break;

        case "banned":
            var content = "お客様は措置規定に該当した可能性があるため、当アプリへのアクセスが制限されています。<br />ご理解、ご了承の程、宜しくお願いいたします。";
            break;

        case "multi":
            var content = "複数のタブ・ウィンドウでプレイされている可能性があります。<br />同時プレイには対応しておりませんので、一つのタブでのご利用をお願いします。";
            break;

        case "noid":
            var content = "当アプリへのログイン有効時間が切れています。<br />";
            linkRetry = true;
            break;

        case "timeout":
            var content = "通信がタイムアウトになりました。<br />";
            linkRetry = true;
            break;

        case "version":
            var content = "アプリが更新されました。<br />";
            linkRetry = true;
            break;

        case "noregister":
        case "preregister":
            var content = "このエラーになるのはバグっぽいです。";
            break;

        default:
            //var content = '申し訳ありません。サーバ側で何かがありました。<br />エラーコード: <span style="color:darkred">%s</span><br /><br />'.format(res["error"]);
            var content = '申し訳ありません。サーバ側で何かがありました。<br />';
            linkRetry = true;
    }

    // 必要ならトップからやり直すための案内を付ける。
    if(linkRetry)
        content += "お手数ですが、トップからやり直してください。<br /><br /><div style='text-align:center'><a href='" + URL_TOP + "'>TOPへ戻る</a></div>";

    console.log(content);

    // 表示。
    var d = new Dialogue();
    d.autoClose = false;
    d.veilClose = false;
    d.top = 200;
    d.content(content);
    d.show();

    //エラーはどんなコンテンツより全面に出す。
    $("#dialogue-appearance").css("z-index", 1000);
}

//---------------------------------------------------------------------------------------------------------
/**
 * requestJson() の別名。APIの場合、リクエストはJsonしかあり得ないので…
 */
Api.prototype.transmit = function(callback) {

    this.requestJson(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * request() をオーバーライド。
 *
 * @param   リクエスト完了時のコールバック。このコールバックは引数を一つ取ることができ、サーバからの
 *          レスポンスが渡される。
 */
Api.prototype.request = function(callback, type) {

    // リクエストイベントを発火。
    Api.onRequest.trigger(this.url, this.get, this.post);

    // 通信カウント+1。
    Api.communications++;

    // リクエスト実行。
    Server.prototype.request.call(this, function(res) {

        // エラーが起きているならそれを処理する。
        if(res  &&  res["error"]) {
            Api.processError(res);
            return;
        }

        // 通信カウント-1。
        Api.communications--;

        // 指定されたコールバックをコール。
        if(callback)
            callback(res);

        // すべての通信が終わったら処理を行う。
        if(Api.communications == 0)
            Api.onComplete.trigger();

    }, type);
}
