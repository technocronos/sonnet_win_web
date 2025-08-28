/**
 * ユーザサイト全体に適用されるJSファイル。
 */

function body_onLoadGlobal() {

    // "pc-style" という属性を持っている要素をすべて取得。
    var list = document.querySelectorAll("[pc-style],[pc_style]");

    // 一つずつ見ていき、"pc-style" を "style" に乗せ替える。
    for(var i = 0 ; i < list.length ; i++) {
        var node = list[i];
        var style1 = node.getAttribute("style");
        var style2 = node.getAttribute("pc-style");
        var style3 = node.getAttribute("pc_style");
        node.setAttribute("style", (style1 ? style1 : "") + "; " + (style2 ? style2 : "") + "; " + (style3 ? style3 : ""));
    }

    // body_onLoad という関数がある場合はコールする。
    if(window.body_onLoad)
        body_onLoad();
}

document.addEventListener( "DOMContentLoaded", body_onLoadGlobal, false );
