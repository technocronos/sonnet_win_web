/**
 * /page以下の各ページJSとは別に.userviewの下の土台用のHTML用の共通処理を記述する。
 * 動的に呼ばれるDisplayCommonや各ページJSと違って土台のlayout.htmlでnewされ、ページをまたいで保持される。
 * 
 * 複数ページをまたいで保持したい情報がある場合はsetParamsやgetParamsを使用し、
 * アプリを落としても保持したいような情報がある場合はsetStorageやgetStorageを使用されたい
 * 
 * 単純にポップアップに情報を渡したい、等の軽いページ間やりとり用途であれば
 *      var server = new Server("html/●●/●●.html?unit_id=12345");
 *        server.requestText(function(response) { ...以下処理
 * のようにクエリ文字を付与し、page.getQuery("unit_id")のように取得されたい
 * 
 */
function Page() {
    $("#container").hide();

    //ページ間でやりとりする場合のパラメータ格納
    this.params = {};

    //ショップ、ガチャ等で決済画面から戻って来た際に使用するフラグ
    //全画面共通で使用し、一回限りのみ戻った画面を復元する際に使用するグローバル変数。
    this.params["PaymentReturnFlg"] = true;

    //他のコントロールと名前が被らないようにてきとうなプレフィックス
    this.prefix = "asdfktenasdfkjaasfdal-";

    this.preload_image = new Object();
    this.preload_count = 0;
    this.font_load = false;

    this.summary = null;
}

//-------------------------------------------------------------------------------------------------------------
/**
 * ゲッター、セッター
 */
//-------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------
/**
 * 該当キーの値を返す
 *
 */
Page.prototype.getParams = function(key){
    return this.params[key];
}

//---------------------------------------------------------------------------------------------------------
/**
 * 該当キーの値を設定する
 *
 */
Page.prototype.setParams = function(key, value){
    this.params[key] = value;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 該当キーのストレージの値を返す
 * jsonの場合はオブジェクトで返す
 */
Page.prototype.getStorage = function(key){
    var storage = localStorage;
    if(storage.getItem(key) == "null")
        return null;
    else if(storage.getItem(key) == "undefined")
        return undefined;
    else if(AppUtil.isJSON(storage.getItem(key)))
        return JSON.parse(storage.getItem(key));
    else
        return storage.getItem(key);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 該当キーの値をストレージに設定する
 * オブジェクトの場合はjsonで保存する
 */
Page.prototype.setStorage = function(key, value){
    var storage = localStorage;

    if(!AppUtil.isObject(value))
        storage.setItem(key, value);
    else
        storage.setItem(key, JSON.stringify(value));

}

//---------------------------------------------------------------------------------------------------------
/**
 * 該当キーのストレージを削除する
 *
 */
Page.prototype.removeStorage = function(key){
    var storage = localStorage;
    storage.removeItem('mute_condition');
}

//---------------------------------------------------------------------------------------------------------
/**
 * 現在のサマリーを返す。
 *
 */
Page.prototype.getSummary = function(){
    return Page.summary;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 現在のサマリーをセットする。
 *
 */
Page.prototype.setSummary = function(summary){
    Page.summary = summary;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 現在の画面オブジェクトを返す。
 *
 */
Page.prototype.getContext = function(){
    return Page.context;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 現在の画面オブジェクトをセットする。基本的には基底クラスで勝手に設定されるがまれに手動で設定することもある。
 *
 */
Page.prototype.setContext = function(context){
    Page.context = context;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 画面の高さに応じてコンテンツを調整する
 *
 */
Page.prototype.getContentsHeight = function(){
    var height = deviceheight;
console.log("deviceheight=" + deviceheight)
    if(carrier == "android"){
        if(deviceheight >= 569 && deviceheight < 640)
            //Xperia XZ1 compact SO-02K 720x1280
            //TOUGHBOOK P-01K 720x1280
            //arrows BE F-04K 720x1280
            height = 590;
        else if(deviceheight >= 640 && deviceheight < 670)
            //htc garaxy 720x1280
            //AQUOS R compact 701SH 1080x2032
            //AQUOS R SH-03J 1440x2560
            height = 656;
        else if(deviceheight >= 670 && deviceheight < 678)
            //????
            height = 686;
        else if(deviceheight >= 678 && deviceheight < 731)
            //AQUOS R compact SHV41 1080x2032
            height = 660;
        else if(deviceheight >= 731 && deviceheight < 736)
            //nexus5X 1080x1920 -> 732
            //nexus6  1440x2560 -> 732
            //Pixel 2 1080x1920
            height = 740;
        else if(deviceheight >= 736 && deviceheight < 739)
            //Galaxy Z Fold2 5G 1768x2208
            height = 710;
        else if(deviceheight >= 739 && deviceheight < 748)
            //Xiaomi Redmi Note 5,6 1080x2160
            //Samsung Galaxy S8+ 1440x2960 -> 740
            height = 740;
        else if(deviceheight >= 748 && deviceheight < 749)
            //Xperia 5 ⅡSO-51A 1080x2520 
            //Galaxy Feel2 SC-02L 720x1480 ->740
            height = 760;
        else if(deviceheight >= 749 && deviceheight < 760)
            //ZenFone 5Z ZS62KL 1080x2246
            height = 730;
        else if(deviceheight >= 760 && deviceheight < 775)
            //Sharp AQUOS R2 SHV42 1440x3040
            //moto g7 power XT1955-7 720x1520
            height = 735;
        else if(deviceheight >= 775 && deviceheight < 780)
            //Xiaomi Redmi Note 8T 1080x2340
            height = 770;
        else if(deviceheight >= 780 && deviceheight < 792)
            //AQUOS zero5G basic A002SH 1080x2340 
            //Galaxy A21 SC-42A   720x1560 
            height = 765;
        else if(deviceheight >= 792 && deviceheight < 800)
            //Sharp AQUOS R5G SH-51A 1440x3168
            height = 780;
        else if(deviceheight >= 800 && deviceheight < 808)
            //Galaxy S20 5G SCG01 1440x3200
            //Galaxy A41 SC-41a   1080x2400 
            height = 780;
        else if(deviceheight >= 808 && deviceheight < 823)
            //Pixel 3a 1080x2220
            height = 810;
        else if(deviceheight >= 823 && deviceheight < 851)
            //pixel 2XL 1440x2880
            //DINGO BX 901KC 720x1440
            height = 830;
        else if(deviceheight >= 851 && deviceheight < 854)
            //Pixel 5   1080x2340
            //Pixel 3 XL 1440x2960
            //AQUOS sence5G SH-53A 1080x2280
            //Xiaomi Redmi Note 8Pro 1080x2340
            height = 808;
        else if(deviceheight >= 851 && deviceheight < 854)
            //Pixel 5   1080x2340
            //Pixel 3 XL 1440x2960
            //AQUOS sence5G SH-53A 1080x2280
            height = 808;
        else if(deviceheight >= 854 && deviceheight < 869)
            //Galaxy S20+ SC-52A 1440x3200 
            //Galaxy S20+ SC-51A 1440x3200 
            //Galaxy A32 5G SCG08 720x1600 
            //Essential Phone PH-1 - English 1312x2560
            height = 826;
        else if(deviceheight >= 869 && deviceheight < 873)
            //arrows NX9 F-52A 1080x2280
            //ZenFone MAX Pro(M2) ZB631KL 
            height = 840;
        else if(deviceheight >= 873 && deviceheight < 879)
            //Xiaomi Redmi Note 9Pro 1080x2400
            height = 866;
        else if(deviceheight >= 879 && deviceheight < 892)
            //Pixel 4 XL 1440x3040
            //Pixel 3a XL 1080x2160
            //Galaxy Z flip 5G 1080x2636
            height = 866;
        else if(deviceheight >= 892 && deviceheight < 938)
            //LG style3 L-41A 1440x3120
            //arrow 5G F-51A 1440x3120
            //Galaxy note20 Ultra SC-53A  1440x3088
            height = 850;
        else if(deviceheight >= 938 && deviceheight < 960)
            //VELVET L-52A 1080x2460
            height = 900;
        else if(deviceheight >= 960 && deviceheight < 976)
            //Xperia 5 ⅡSO-52A 1080x2520
            height = 960;
        else if(deviceheight >= 976)
            //Sony Xperia 1 Ⅱ SO-51A 1096x2560
            height = 980;

    }else{
        if(deviceheight >= 480 && deviceheight < 568)
            //iphone 4s 640x960
            height = 680;
        else if(deviceheight >= 568 && deviceheight < 667)
            //iphone 5s 640x1136 
            height = 595;
        else if(deviceheight >= 667 && deviceheight < 736)
            //iphone 6,8
            //iphone SE2 750x1334
            height = 680;
        else if(deviceheight >= 736 && deviceheight < 812)
            //iphone6PLUS,8PLUS
            height = 740;
        else if(deviceheight >= 812 && deviceheight < 844)
            //iphone X 828x1792
            //iphone XS 1125x2436
            //iphone 11 Pro 1125x2436
            height = 815;
        else if(deviceheight >= 844 && deviceheight < 896)
            //iphone 12 1284x2778
            //iphone 12 Pro 1170x2532
            height = 845;
        else if(deviceheight >= 896 && deviceheight < 926)
            //iphone XR 828x1792
            //iphone XS MAX 1242x2688
            //iphone 11 828x1792
            //iphone 11 Pro MAX 1242x2688
            height = 890;
        else if(deviceheight >= 926)
            //iphone12 Pro MAX 1284x2778
            height = 920;

    }
//alert("deviceheightres=" + height)
    return height;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 画面の高さに応じてドラマのウィンドウ位置を調整する
 *
 */
Page.prototype.setDramaWin = function(api, contentheight){
    //メインウィンドウ位置調整
    var height = ((contentheight * 240) / devicewidth);
console.log(height);
    api.setPosition("/main/window", 0, height);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 画面の高さに応じてスフィアのウィンドウ位置を調整する
 *
 */
Page.prototype.setSphereWin = function(api, contentheight){
    //メインウィンドウ位置調整
    var height = ((contentheight * 240) / devicewidth) - 350;

    stage_height =24 * 15;

console.log("setSphereWin = " + stage_height + ":" + height);

    api.setVariables("/", {"STAGE_HEI":stage_height , "movie_height":height});
}

