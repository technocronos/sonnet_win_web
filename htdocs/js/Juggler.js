
/**
 * HTML要素をハンドリングする関数を集めたシングルトンオブジェクト。
 */
var Juggler = {};

//---------------------------------------------------------------------------------------------------------
/**
 * 引数で指定された id 値を持つ要素をクローンして返す。
 * このとき、クローン後の要素で style の display をリセットし、id属性を削除する。
 *
 * @param   id 属性の値。またはjQueryオブジェクト。
 * @return  クローンした要素を内包するオブジェクト(jQuery)。
 */
Juggler.generate = function(target) {

    if(!target.jquery)
        var target = $("#" + target).clone();

    var result = target.clone();

    result.css("display", "");
    result.attr("id", null);
    return result;
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定された <img> 要素に、指定された品目のアイコン画像をセットする。
 *
 * @param   <img> 要素(jQuery)
 * @param   API仕様書の "構造体／アーティクル"。
 */
Juggler.setArticleImage = function(img, article) {

    ArticleServer.queryArticle(article, function(article){

        switch(article["class"]) {
            case "unit":
                img.attr( "src", article["icon_urls"][article["color"]] ); 
                break;
            case "item":
                img.attr( "src", article["icon_url"] );
                break;
            case "stock":
                img.attr( "src", article["icon_url"] );
                break;
            default:
                img.attr( "src", article["image_url"] );
        }
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定された <img> 要素に、指定された品目のアイコン画像をセットする。
 *
 * @param   <img> 要素(jQuery)
 * @param   API仕様書の "構造体／ゼンマロイド"。
 */
Juggler.setFrameImage = function(img, frame) {
    img.attr( "src", frame["graph_url"] ); 
}


//---------------------------------------------------------------------------------------------------------
/**
 * 引数に指定された <img> 要素に、指定された品目のアイコン画像をセットする。
 *
 * @param   <img> 要素(jQuery)
 * @param   API仕様書の "構造体／ゼンマロイド"。
 */
Juggler.setItemImage = function(img, item) {
    img.attr( "src", item["icon_url"] ); 
}

