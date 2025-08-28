
/**
 * サーバと通信するための汎用ユーティリティ。new でインスタンスを作成して requestText(), requestJson() を
 * 呼んで利用する。
 *
 * @param   URL
 * @param   POSTパラメータ。空の場合は GET メソッドになる。
 * @param   PC版の場合に署名を要求する場合は true を指定する。
 */
function Server(url, post, signed) {

    this.url = url;
    this.post = post;
    this.signed = signed;
}

//---------------------------------------------------------------------------------------------------------
/**
 * リクエストを行う。
 *
 * @param   リクエスト完了時のコールバック。このコールバックは引数を一つ取ることができ、サーバからの
 *          レスポンスが渡される。
 * @param   レスポンスの種別。"text", "json" のいずれか。省略時は "text"。
 */
Server.prototype.request = function(callback, type) {

    // 種別省略時は "text"。
    if(!type)
        type = "text";

    var url = this.url;

    // 提供形態によってリクエスト手段は異なる。
    switch(support_type) {

        // フレーム型スマホ版
        case "frame":
            jQuery.ajax({
                url: url,
                method: this.post ? "post" : "get",
                data: this.post,
                dataType : type,
                timeout: 10000,
                success: function(data){
                    //redirectURLがある場合はそちらへ移動
                    if(data["redirectURL"] != null){
                        window.location.href = data["redirectURL"];
                        return;
                    }
                    callback(data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus);
                    var res = [];
                    res["error"] = XMLHttpRequest.responseText;
                    Api.processError(res);
                }
            });
            break;

        // プロキシ型スマホ版
        case "proxy":
            // 未実装

        // PC版
        case "gadget":

            // makeRequest() のパラメータ作成。
            var params = {};
            params[gadgets.io.RequestParameters.METHOD] = gadgets.io.MethodType[this.post ? "POST" : "GET"];
            params[gadgets.io.RequestParameters.POST_DATA] = gadgets.io.encodeValues(this.post);
            params[gadgets.io.RequestParameters.CONTENT_TYPE] = gadgets.io.ContentType[(type == "json") ? "JSON" : "TEXT"];
            params[gadgets.io.RequestParameters.AUTHORIZATION] = gadgets.io.AuthorizationType[this.signed ? "SIGNED" : "NONE"];

            // リクエスト実行。
            gadgets.io.makeRequest(url, function(obj) {

                // エラーが発生している場合は出力。
                if(obj.errors.length > 0) {
                    console.log("url: " + url);
                    Application.showError("makeRequest", obj.errors[0], obj.text);
                    return;
                }

                // 指定されたコールバックをコール。
                callback(obj.data)

            }, params);

            break;
    }
}

//---------------------------------------------------------------------------------------------------------
/**
 * request() の第二引数を "text" で固定したもの。
 */
Server.prototype.requestText = function(callback) {
    this.request(callback, "text");
}

//---------------------------------------------------------------------------------------------------------
/**
 * request() の第二引数を "json" で固定したもの。
 */
Server.prototype.requestJson = function(callback) {
    this.request(callback, "json");
}

