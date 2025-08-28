
/**
 * アプリケーションのURLを取り扱う関数を集めたシングルトンオブジェクト。
 */
var AppUrl = {};

//-----------------------------------------------------------------------------------------------------
/**
 * 指定されたサイト・アクションとクエリパラメータでURLを生成する。
 *
 * @param   サイト・アクション名。"site/action" の形式。"site/" は省略できる。この場合は "user/" が
 *          使われる。
 * @param   GET変数を表す連想配列
 * @return  生成したURL
 *
 * サイト名とアクション名を連想配列の中に入れて一つの引数で示すこともできる。この場合は "@" キーで
 * 指定する。
 */
AppUrl.build = function(name, params) {

    var args = this.normalize(name, params);

    // 共通変数を補う。
    args["oauth"] = Environment["oauth_id"];
    args["ver"] = Environment["version_code"];

    return Environment["app_base"] + this.figurate(args);
}

//-----------------------------------------------------------------------------------------------------
/**
 * 引数で指定されたアプリケーションアクションを単一の文字列にシリアル化する。
 *
 * @param   サイト・アクション名。"site/action" の形式。"site/" は省略できる。
 * @param   GET変数を表す連想配列
 * @return  シリアル化した文字列
 *
 * サイト名とアクション名を連想配列の中に入れて一つの引数で示すこともできる。この場合は "@" キーで
 * 指定する。
 */
AppUrl.serialize = function(name, params) {

    var args = this.normalize(name, params);

    return this.figurate(args);
}

//-----------------------------------------------------------------------------------------------------
/**
 * serialize() の逆を行う。
 *
 * @param   シリアル化した文字列。
 * @return  アプリケーションアクションに表すパラメータ。
 */
AppUrl.unserialize = function(serial) {

    var matches = serial.match(/^([^?]+)(?:\?(.*))?$/);

    var result = {};
    result["@"] = matches[1];

    if(matches[2])
        result.merge( location.parse(matches[2]) );

    return result;
}

//-----------------------------------------------------------------------------------------------------
/**
 * build や serialize の引数を、一つの連想配列に統一する。
 */
AppUrl.normalize = function(name, params) {

    var result = {};

    // 一つの引数で示されている場合は簡単。
    if(typeof(name) == "object") {
        result.merge(name);

    // 二つの引数で示されている場合は...
    }else {

        if(params)
            result.merge(params);

        result["@"] = name;
    }

    // アクション名にサイト名がない場合は補う。
    if(result["@"].indexOf("/") === -1)
        result["@"] = "user/" + result["@"];

    // undefined なキーは取り除く。
    for(var i in result) {
        if(result[i] == undefined)
            delete result[i];
    }

    return result;
}

//-----------------------------------------------------------------------------------------------------
/**
 * 引数で指定されたパラメータからアクションのURLを生成する。
 *
 * @param   AppUrl.normalize() の戻り値
 * @return  生成したURL
 */
AppUrl.figurate = function(args) {

    var name = args["@"];
    delete args["@"];

    return name + "?" + $.param(args);
}

//-----------------------------------------------------------------------------------------------------
/**
 * 指定されたパスの静的ファイルを取得するためのURLを返す。
 *
 * @param   htdocsからの相対パス。
 */
AppUrl.asset = function(path) {

    // ドメインが入っているなら触らないほうが良いだろう...
    if( path.includes("//") )
        return path;

    // キャッシュスタンプの前に付加するセパレータを決定。
    var separator = path.includes("?") ? "&" : "?";

    // 汎用更新スタンプがある場合のみキャッシュスタンプを付ける。
    var updateStamp = wide_stamp ? separator + "cache=" + wide_stamp : "";

    // アセットサーバーのドメインに、パス、キャッシュスタンプと言う形で生成する。
    return CDN_WEB_ROOT + path + updateStamp;
}


//-----------------------------------------------------------------------------------------------------
/**
 *  GETパラメータを配列にして返す
 *
 *  @return     パラメータのObject
 *
 */
AppUrl.getUrlVars = function(){
    var vars = {};
    var param = location.search.substring(1).split('&');
    for(var i = 0; i < param.length; i++) {
        var keySearch = param[i].search(/=/);
        var key = '';
        if(keySearch != -1) key = param[i].slice(0, keySearch);
        var val = param[i].slice(param[i].indexOf('=', 0) + 1);
        if(key != '') vars[key] = decodeURI(val);
    }
    return vars;
}


//-----------------------------------------------------------------------------------------------------
/**
 *  クエリ文字列の?以降を{"aaa":111,"bbb",222}のようなオブジェクトで返す
 *
 * @param   urlのクエリストリング。?より右全部
 *  @return     object
 *
 */
AppUrl.getQueryParam = function(url){
    var arg = new Object;
    var pair = url.substring(0).split('&');

    for(var i=0;pair[i];i++) {
        var kv = pair[i].split('=');
        arg[kv[0]]=kv[1];
    }

    return arg;
}


AppUrl.htmlspecialchars_decode  = function(string, quoteStyle){
  // eslint-disable-line camelcase
  //       discuss at: http://locutus.io/php/htmlspecialchars_decode/
  //      original by: Mirek Slugen
  //      improved by: Kevin van Zonneveld (http://kvz.io)
  //      bugfixed by: Mateusz "loonquawl" Zalega
  //      bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //         input by: ReverseSyntax
  //         input by: Slawomir Kaniecki
  //         input by: Scott Cariss
  //         input by: Francois
  //         input by: Ratheous
  //         input by: Mailfaker (http://www.weedem.fr/)
  //       revised by: Kevin van Zonneveld (http://kvz.io)
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES')
  //        returns 1: '<p>this -> &quot;</p>'
  //        example 2: htmlspecialchars_decode("&amp;quot;")
  //        returns 2: '&quot;'

  var optTemp = 0
  var i = 0
  var noquotes = false

  if (typeof quoteStyle === 'undefined') {
    quoteStyle = 2
  }
  string = string.toString()
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  }
  if (quoteStyle === 0) {
    noquotes = true
  }
  if (typeof quoteStyle !== 'number') {
    // Allow for a single string or an array of string flags
    quoteStyle = [].concat(quoteStyle)
    for (i = 0; i < quoteStyle.length; i++) {
      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
      if (OPTS[quoteStyle[i]] === 0) {
        noquotes = true
      } else if (OPTS[quoteStyle[i]]) {
        optTemp = optTemp | OPTS[quoteStyle[i]]
      }
    }
    quoteStyle = optTemp
  }
  if (quoteStyle & OPTS.ENT_HTML_QUOTE_SINGLE) {
    // PHP doesn't currently escape if more than one 0, but it should:
    string = string.replace(/&#0*39;/g, "'")
    // This would also be useful here, but not a part of PHP:
    // string = string.replace(/&apos;|&#x0*27;/g, "'");
  }
  if (!noquotes) {
    string = string.replace(/&quot;/g, '"')
  }
  // Put this in last place to avoid escape being double-decoded
  string = string.replace(/&amp;/g, '&')

  return string
}