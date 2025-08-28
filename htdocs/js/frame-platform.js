
/**
 * プラットフォームごと・形態(PC版・スマホ版)ごとの違いを吸収して、プラットフォーム機能を利用するためのライブラリ。
 * 今のとこ、PC版のインラインガジェット型の中に、オリジナル<iframe>を置く形態のことしか考えてないけど。
 */
var Platform = {

    //------------------------------------------------------------------------------------------------------
    /**
     * 疑似コンストラクタ。
     */
    construct: function() {

        var resolver;

        // ready() で登録されるコールバックを管理するためのクラス。
        this.readyPromise = new Promise( function(resolve, reject){resolver=resolve} );
        this.readyPromise.resolve = resolver;

        // 決済APIのコールバック関数。
        this.paymentCallback = null;
    },

    //------------------------------------------------------------------------------------------------------
    /**
     * PC版によってマウントされているかどうかを確認する。最初に呼ばれる。
     */
    ping: function() {

        if(window.parent)
            window.parent.postMessage('{"type":"my-attachment-ping"}', "*");

        // PC版によるマウントでない場合は返答ないので、タイムアウトを登録しておく。
        setTimeout( function(){Platform.pong(false)}, 200 );
    },

    /**
     * ping() の返答としての postMessage で呼ばれる。(返答の有無に関わらず)タイムアウト時にも呼ばれる。
     *
     * @param   返答有りとしてのコールなら true、タイムアウトとしてのコールなら false。
     */
    pong: function(reply) {

        // PC版によってマウントされているならこれらの値を調整する。
        if(reply) {
            this["support_type"] = "frame";
            this["is_pc_gedget"] = true;
        }

        // ready() によって登録されているコールバックをキック。
        // 返答有りの場合はタイムアウトの場合と合わせて二回コールされることになるが、そこはPromiseがうまく一回のみ処理してくれる。
        this.readyPromise.resolve();
    },

    /**
     * PC版マウンターの存在確認が終了したタイミングで実行したいコールバックを登録する。
     *
     * @param   登録したいコールバック関数。
     */
    ready: function(callback) {

        this.readyPromise.then(callback);
    },

//     //------------------------------------------------------------------------------------------------------
//     /**
//      * プラットフォーム通貨による課金購入を呼び出す。
//      *
//      * @param   以下のキーを含むオブジェクト
//      *              selling_id      商材ID
//      *              sell_name       販売名(「アイテム名 x 数量」や「ガチャ名 x チャレンジ数」)
//      *              sell_price      販売価格
//      *              image_url       アイコンURL
//      *              color_code      ボックスガチャの場合に色コード
//      *              auto_discard    ガチャの場合に自動売却するかどうか
//      * @param   決済各定時に呼び出されたいコールバック。引数には決済コードが渡される(モバゲではアプリ発行の決済ID)。
//      */
//     arrangePurchase: function(params, callback) {
//
//         // 指定されたコールバックを取っておく。
//         this.paymentCallback = callback;
//
//         // SKUの決定。本来は商材IDをそのまま使えば良いのだが、今回は色コードや自動売却の指定をSKUを通じて渡さなければならない都合上、次のようなルールで構築する。
//         //      [商材ID桁数][商材ID][色コード1-5][自動売却0-1]
//         // 数値強制でなければもう少しましなルールにも出来たのだが…数値原理主義の重力に魂を縛られた人々はどうぞ粛正されて下さい。
//         var sku = "" + params["selling_id"].toString().length + params["selling_id"];
//         if(params["auto_discard"] != undefined) {
//             var variation = {"red":1, "green":2, "blue":3, "yellow":4, "black":5}[ params["color_code"] ] || 0;
//             var option = params["auto_discard"] ? 1 : 0;
//             sku += "" + variation + option;
//         }
//
//         // ブリッジフレームに連絡。
//         var message = {
//             type: "my-attachment-payment",
//             sku: sku,
//             name: params["sell_name"],
//             description: "　",
//             price: params["sell_price"],
//             image_url: params["image_url"],
//         };
//         window.parent.postMessage(JSON.stringify(message), "*");
//     },
//
//     /**
//      * 課金決済が確定したら呼ばれる。
//      */
//     purchaseFixed: function(message) {
//
//         // コールバックがあるならコール。
//         if(this.paymentCallback)
//             this.paymentCallback(message.paymentId);
//
//         // コールバックを解放する。
//         this.paymentCallback = null;
//     },

    //------------------------------------------------------------------------------------------------------
    /**
     * ゲームフレームの高さを現在の状態に合わせる。
     */
    adjustHeight: function(gedget_height) {

        // PCガジェットの場合。
        if( this["is_pc_gedget"] ) {

            var message = {
                type: "my-attachment-adjust-height",
                height: $(document).height(),
            };
            window.parent.postMessage(JSON.stringify(message), "*");

        // PCガジェットではない場合。
        }else {
            if( window["nijiyome"] )
                gadgets.window.adjustHeight(gedget_height);
        }
    },

//     //------------------------------------------------------------------------------------------------------
//     /**
//      * 指定された位置にスクロールする。モバゲPC版では該当機能が無いため何も起きない。
//      */
//     scrollTo: function(to) {
//
//         // PCガジェットの場合。
//         if( this["is_pc_gedget"] ) {
//
//         // PCガジェットではない場合。
//         }else {
//
//             if( window["nijiyome"] )
//                 nijiyome.ui({'method':'scroll', 'x':0, 'y':to});
//         }
//     },

    //------------------------------------------------------------------------------------------------------
    /**
     * postMessage() されたら呼ばれる。
     */
    receiveMessage: function(event) {

        // 本当は event.origin をチェックしないといけないのだが…プラットフォームのドメイン確定できないしなあ…まあ大丈夫かな？

        // メッセージの種別ごとに処理する。
        try{
            var message = JSON.parse(event.data);
            switch(message.type) {
                case "my-attachment-pong":          this.pong(true);                break;
                case "my-attachment-payment-fix":   this.purchaseFixed(message);    break;
            }
        }catch (e) {
            //console.log(event.data);
        }
    },
}

// 初期化。
Platform.construct();

// postMessage の受け口を登録する。
window.addEventListener("message", Platform.receiveMessage.bind(Platform), false);
