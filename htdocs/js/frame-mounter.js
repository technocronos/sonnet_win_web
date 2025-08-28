/**
 * PC版において、gadgetに直接ホストされるwindowにおけるjavascriptコードを収める。
 */

//----------------------------------------------------------------------------------------------------------
/**
 * サーバーAPIを呼び出す関数。
 *
 * @param   API名
 * @param   GET変数。不要な場合は null か空のオブジェクトを指定する。
 * @param   POST変数。GETメソッドの場合はnullを指定する。
 * @return  完了時にリゾルブされるPromise。
 */
function callServerApi(api, get, post) {

    if(!get)
        get = {};

    // クエリストリングを作成。
    get["action"] = api;
    var qstring = gadgets.io.encodeValues(get);

    // URLを作成。
    var url = appBase + "?module=Api&" + qstring;

    // リクエストパラメータを作成。
    var params = {};
    params[gadgets.io.RequestParameters.METHOD] = (post == null) ? gadgets.io.MethodType.GET : gadgets.io.MethodType.POST;
    params[gadgets.io.RequestParameters.CONTENT_TYPE] = gadgets.io.ContentType.JSON;
    params[gadgets.io.RequestParameters.AUTHORIZATION] = gadgets.io.AuthorizationType.SIGNED;
    params[gadgets.io.RequestParameters.POST_DATA] = gadgets.io.encodeValues(post);
    params[gadgets.io.RequestParameters.REFRESH_INTERVAL] = 0;

    // リクエストが完了したらリゾルブされるPromiseを作ってリターン。リジェクトはされない。
    return new Promise(function(resolve, reject){

        gadgets.io.makeRequest(url, function(obj){

            if(!obj.data) {
                console.error("gadgets.io.makeRequestでエラー");
                console.error(obj);
                return;
            }

            if(obj.data.error) {
                console.error(obj.data);
                return;
            }

            resolve(obj.data);
        }, params);
    });
}

//----------------------------------------------------------------------------------------------------------
/**
 * postMessage されたら呼ばれる。
 */
function receiveMessage(event) {

    // ゲームサーバ以外からのものを無視。
    if( !/^http:\/\/[\w\.]+\.crns-game\.net$/.test(event.origin) )
        return;

    // メッセージの種別ごとに処理する。
    var message = JSON.parse(event.data);
    switch(message.type) {

        // 最初に尋ねられるこのマウンターの存在確認。
        case "my-attachment-ping":
            event.source.postMessage('{"type":"my-attachment-pong"}', "*");
            break;

//         // 決済要求
//         case "my-attachment-payment":
//
//             var vendor = window["dmm"] || window["mbga"];
//
//             var itemParams = {};
//             itemParams[opensocial.BillingItem.Field.SKU_ID] = message.sku;
//             itemParams[opensocial.BillingItem.Field.PRICE]  = message.price;
//             itemParams[opensocial.BillingItem.Field.COUNT]  = 1;
//             itemParams[opensocial.BillingItem.Field.DESCRIPTION] = message.description;
//             itemParams[vendor.BillingItem.Field.NAME]         = message.name;
//             itemParams[vendor.BillingItem.Field.IMAGE_URL]    = message.image_url;
//             var item = opensocial.newBillingItem(itemParams);
//
//             var params = {};
//             params[opensocial.Payment.Field.PAYMENT_TYPE]  = opensocial.Payment.PaymentType.PAYMENT;
//             params[opensocial.Payment.Field.ITEMS]  = [item];
//             params[opensocial.Payment.Field.AMOUNT] = message.price;
//             var payment = opensocial.newPayment(params);
//
//             // 決済リクエスト実行。結果が確定したら...
//             opensocial.requestPayment(payment, function(response) {
//
//                 var answer = response.getData();
//
//                 // エラーが起きている場合。
//                 if( response.hadError() ) {
//                     if(answer.getField(opensocial.Payment.Field.RESPONSE_CODE) != "userCancelled") {
//                         console.error("opensocial.requestPayment でエラーが返されました。");
//                         console.error(response);
//                     }
//                     return;
//                 }
//
//                 // 完了している場合。アプリ発行の決済IDを取得(モバゲ)。
//                 var orderId = answer.getField(opensocial.Payment.Field.ORDER_ID);
//
//                 // 返信メッセージを送る。
//                 var pong = {type:"my-attachment-payment-fix", paymentId:orderId};
//                 event.source.postMessage(JSON.stringify(pong), "*");
//             });
//
//             break;

        // 高さ合わせ要求
        case "my-attachment-adjust-height":
            document.getElementById("mounted-frame").style.height = "100%";
            gadgets.window.adjustHeight();

            break;

    }
}

// postMessage の受け口を登録する。
window.addEventListener("message", receiveMessage, false);
