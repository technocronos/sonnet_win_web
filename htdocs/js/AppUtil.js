
/**
 * 汎用関数を集めたシングル㌧オブジェクト。
 */
var AppUtil = {};


//-----------------------------------------------------------------------------------------------------
/**
 * 秒数をhh:mm:ssの形式で返す。
 *
 * @param   秒数
 */
AppUtil.toHms = function(time) {

    var t = Math.floor(time);
    var hms = "";
    var h = t / 3600 | 0;
    var m = t % 3600 / 60 | 0;
    var s = t % 60;

    if (h != 0) {
        hms = AppUtil.padZero(h,2) + ":" + AppUtil.padZero(m,2) + ":" + AppUtil.padZero(s,2) ;
    } else if (m != 0) {
        hms = "00:" + AppUtil.padZero(m,2) + ":" + AppUtil.padZero(s,2);
    } else {
        hms = "00:00:" + AppUtil.padZero(s,2);
    }

    return hms;


}

//-----------------------------------------------------------------------------------------------------
/**
 * ゼロ埋めをする
 *
 */
AppUtil.padZero = function(num,length) {
    return ('0000000000' + num).slice(-length);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ボタンをグレイアウトする
 */
AppUtil.disableButton = function(board, size) {
    board = $(board);

    board.find("div").removeClass("colorNormal");
    board.find("div").removeClass("colorDark");
    board.find("div").addClass("colorDisable");

    board.find("img").attr("src", AppUrl.asset("img/parts/sp/btn_flame_"+size+"_disable.png"));
}

//---------------------------------------------------------------------------------------------------------
/**
 * ボタンをダークにする
 */
AppUtil.darkButton = function(board, size) {
    board = $(board);

    board.find("div").removeClass("colorNormal");
    board.find("div").removeClass("colorDisable");
    board.find("div").addClass("colorDark");

    board.find("img").attr("src", AppUrl.asset("img/parts/sp/btn_flame_"+size+"_dark.png"));
}

//---------------------------------------------------------------------------------------------------------
/**
 * ボタンを戻す
 */
AppUtil.ableButton = function(board, size) {
    board = $(board);

    board.find("div").removeClass("colorDisable");
    board.find("div").removeClass("colorDark");
    board.find("div").addClass("colorNormal");

    board.find("img").attr("src", AppUrl.asset("img/parts/sp/btn_flame_"+size+".png"));
}

//-----------------------------------------------------------------------------------------------------
/**
 * 現在のタイムスタンプを取得する
 *
 */
AppUtil.getTime = function() {
    // Dateオブジェクトを作成
    var date = new Date() ;

    // UNIXタイムスタンプを取得する (ミリ秒単位)
    return date.getTime() ;
}

AppUtil.fromtimestamp = function(timestamp) {
    var ts = timestamp;

    var d = new Date( ts * 1000 );
    var year  = d.getFullYear();
    var month = d.getMonth() + 1;
    var day  = d.getDate();
    var hour = ( d.getHours()   < 10 ) ? '0' + d.getHours()   : d.getHours();
    var min  = ( d.getMinutes() < 10 ) ? '0' + d.getMinutes() : d.getMinutes();
    var sec   = ( d.getSeconds() < 10 ) ? '0' + d.getSeconds() : d.getSeconds();

    return year +"/" + month +"/" +day +" " +hour+":" +min+":" +sec;
}

//-----------------------------------------------------------------------------------------------------
/**
 * アイテムのアイコンURLを返す
 *
 */
AppUtil.getItemIconURL = function(item_id) {
    var icon_url = "img/item_sm/" + AppUtil.padZero(item_id, 5) + ".png";
    //汎用攻撃アイコンを使用する場合は "att"。
    if(3000 <= item_id  &&  item_id <= 3999){
        icon_url = "img/item/att.gif";
    }
//console.log(AppUrl.asset(icon_url));
    return AppUrl.asset(icon_url);
}

//---------------------------------------------------------------------------------------------------------
/*
 * 数字をフォント画像で返す。
 *  
 * @param   追加するdomオブジェクト
 * @param   使用したいフォント。/img/font/に入っているフォルダを指定。
 * @param   フォントにする数字
 *  
*/
AppUtil.createTagForNumbers = function(div, font, number){
    var str = String(number);

    if(str == "")
      return false;

    var array = str.split("");

    $.each(array,function(){
        var dom = $("<img>");
        dom.css("margin-left", "2px");
        dom.attr("src", AppUrl.asset("/img/font/" + font + "/" + this + ".png"));
        div.append(dom);
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * 日付をあとどれくらいか出す。
 */
AppUtil.compareDate = function(expire_at){

    var expire = "";

    //通信で帰って来たものが"2017-11-06 09%3A54%3A15"みたくエンコードされてる場合があるため緊急処置としてデコードする。
    expire_at = decodeURIComponent(expire_at);

    //今日の日時を表示
    var date1 = Date.generate(expire_at);
    var date2 = new Date();
    var diff = date1 - date2;
    var diffResult = diff / 86400000;//1日は86400000ミリ秒
    if(diffResult < 1){
        //1日以下の場合は時間で返す
        diffResult = diff / 3600000;
        if(diffResult < 1){
            //あと1時間以下の場合は分で返す
            diffResult = diff / 60000;
            expire = "" + Math.floor(diffResult + 1) + "分";
        }else{
            expire = "" + Math.floor(diffResult + 1) + "時間";
        }
    }else{
        expire = "" + Math.floor(diffResult + 1) + "日";
    }

    return expire;
}

/**
 * 多重ソート関数
 * Aの位置×Bの数×Cの数+Bの位置×Cの数+Cの位置
 * @param datas {array} 並び替えする対象の二次元配列
 * @param rules {array} 並び替えルール
 */
AppUtil.msort = function(datas,rules) {
    //if(!(datas instanceof Array))throw new Error('datas is not array.');
    if(!(rules instanceof Array))throw new Error('rules is not array.');

    //順序番号のパラメータを生成
    var p='_index_'+Math.floor(Math.random()*1000)+((new Date()).getTime()).toString();

    //並び替えする対象の二次元配列をループ
    for(var i1=0,n1=datas.length;i1<n1;i1++){
        var n2=rules.length;

        //並び替えの順番が指定されていないときに、datasから値を取り出して、通常のsortをした順序指定をrules変数内に上書きする
        for(var i2=0;i2<n2;i2++){
            var key=rules[i2][0];
            var rule=rules[i2][1];
            if(!(rule instanceof Array)){
                var tmp=new Array(n1);
                for(var i3=0;i3<n1;i3++){
                    tmp[i3]=datas[i3][key];
                }
                tmp.sort(function(o1, o2){
                    return ( o1 > o2 ) ? 1 : -1;
                });
                if(rule=='desc')tmp.reverse();
                rules[i2][1]=new Array(n1);
                for(var i3=0;i3<n1;i3++){
                    rules[i2][1][i3]=tmp[i3];
                }
            }
        }
        
        //順序を計算する公式によって、順序番号の値を代入する
        var indexs=new Array(n2);
        for(var i2=0;i2<n2;i2++){
            var key=rules[i2][0];
            var rule=rules[i2][1];
            var index=rule.indexOf(datas[i1][key]);
            indexs[i2]=(index>-1)?index:rule.length;
            for(var i3=i2+1;i3<n2;i3++){
                var rule=rules[i3][1];
                if(rule instanceof Array){
                    indexs[i2]*=rule.length+1;
                }
            }
        }
        datas[i1][p]=0;
        for(var i2=0;i2<n2;i2++){
            datas[i1][p]+=indexs[i2];
        }
    }
    
    //順序番号の値でソートする
    datas.sort(function(a,b){
        if(a[p]<b[p])return -1;
        if(a[p]>b[p])return 1;
        return 0;
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * オブジェクトを回転させる。
 *
 */
AppUtil.rotate = function($obj, duration, dest) {
    var deg_start = 0
    var deg_end = 360;

    if(dest == "left"){
        deg_start = 360;
        deg_end = 0;
    }

		// degという変数を0から360まで3秒かけて変化させる。
		$({deg:deg_start}).animate({deg:deg_end}, {
  			duration:(duration * 1000),
  			// 途中経過
  			progress:function() {
    				$obj.css({
    					transform:'rotate(' + this.deg + 'deg)'
    				});
  			},
        easing:"linear",
  			// アニメーション完了
  			complete:function() {
            AppUtil.rotate($obj, duration, dest);
  			}
		});
}

//---------------------------------------------------------------------------------------------------------
/**
 * 広がる波紋アニメ
 *
 */
AppUtil.circle = function($obj, x, y) {
    var settings = {
      size : 500,
      spd : 200,
      color : "#ccc"
    }

    var circle_style = {
      "position":"absolute",
      "height":10,
      "width":10,
      "border":"solid 4px "+settings.color,
      "border-radius":settings.size
    }

    var pos = {
        top :(-settings.size/2)+y,
        left :(-settings.size/2)+x
    }

    $obj.css(circle_style).css({
        "top":y,
        "left":x
    }).animate({"height":settings.size,"width":settings.size,"left":pos.left,"top":pos.top},{duration:settings.spd,queue:false})
    .fadeOut(settings.spd * 1.8,function(){
        //$(this).remove();
    });

}

//---------------------------------------------------------------------------------------------------------
/**
 * シェイクアニメーション
 */
AppUtil.shakeAnim = function(entry){

    $(function(){
        entry.children('img').jrumble({ x: 2,y: 0,rotation: 0 });
        var move = false;
        setInterval(function(){
            if(move == false){
                entry.children('img').trigger('startRumble');
                move = true;
            }else{
                entry.children('img').trigger('stopRumble');
                move = false;
            }
        },1500);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 点滅アニメーション
 */
AppUtil.FlashAnim = function(entry){
    $(function(){
      	setInterval(function(){
        		entry.fadeOut(1200, function(){$(this).fadeIn(200)});
      	}, 1400);
    });
}

//---------------------------------------------------------------------------------------------------------
/**
 * 画像を一定間隔で入れ替えるアニメーション
 */
AppUtil.changeImageAnim = function(entry, image){
      var i = 0;
      var timer = setInterval(function changeImg(){
          entry.attr("src",AppUrl.asset(image[i]));
          if(i < image.count().length -1){//画像を順に切り替え
              i++;
          }
          else{
              i = 0;
          }
      }, 1500);//2秒間隔
      return timer;
}

//---------------------------------------------------------------------------------------------------------
/**
 * オブジェクト判定
 *
 */
AppUtil.isObject = function(o) {
  return (o instanceof Object && !(o instanceof Array)) ? true : false;
};
//---------------------------------------------------------------------------------------------------------
/**
 * json判定
 *
 */
AppUtil.isJSON = function(arg) {
    arg = (typeof arg === "function") ? arg() : arg;
    if (typeof arg  !== "string") {
        return false;
    }
    try {
    arg = (!JSON) ? eval("(" + arg + ")") : JSON.parse(arg);
        return true;
    } catch (e) {
        return false;
    }
};

//---------------------------------------------------------------------------------------------------------
/*
 * PHPのnl2brと同じ処理をする方法（改行コード→brに変換）
 * 
 **/
AppUtil.nl2br = function(str) {
  var res = str.replace(/\r\n/g, "<br>");
  res = res.replace(/(\n|\r)/g, "<br>");
  return res;
}

//---------------------------------------------------------------------------------------------------------
/*
 * プロパティ数を調べて返す
 * 
 **/
AppUtil.getPropertyNum = function(obj) {
    var len = 0;
    
    for (var key in obj) { ++len; }
    
    return len;
}

/**
* 受け取ったオブジェクトをクエリ文字列にシリアライズします。
*
* サンプル オブジェクト
*   var query = {
*     action: 'view',
*     id: '123',
*     debug: undefined,
*     arraylist: [
*       'val1',
*       'val2'
*     ],
*     hashlist: {
*       foo: 'foo',
*       bar: 'bar'
*     }
*   }
* サンプル シリアライズ結果
*   action=view&id=123&debug&arraylist[]=val1&arraylist[]=val2&hashlist[foo]=foo&hashlist[bar]=bar
*/
AppUtil.serialize = function (data) {
    var key, value, type, i, max;
    var encode = window.encodeURIComponent;
    var query = '';
 
    for (key in data) {
        value = data[key];
        type = typeof(value) === 'object' && value instanceof Array ? 'array' : typeof(value);
        switch (type) {
            case 'undefined':
                // キーのみ
                query += key;
                break;
            case 'array':
                // 配列
                for (i = 0, max = value.length; i < max; i++) {
                    query += key + '[]';
                    query += '=';
                    query += encode(value[i]);
                    query += '&';
                }
                query = query.substr(0, query.length - 1);
                break;
            case 'object':
                // ハッシュ
                for (i in value) {
                    query += key + '[' + i + ']';
                    query += '=';
                    query += encode(value[i]);
                    query += '&';
                }
                query = query.substr(0, query.length - 1);
                break;
            default:
                query += key;
                query += '=';
                query += encode(value);
                break;
        }
        if(query != "")
            query += '&';
    }
    query = query.substr(0, query.length - 1);
    return query;
};

AppUtil.toObject = function(str) {
  var result = {}, hash;
  var param = str.substring(str.indexOf('?')+1).split('&');
  for (var i=0; i<param.length; i++) {
    hash = param[i].split('=');
    result[hash[0]] = hash[1];
  }
  return result;
};
/**
* PHPのmt_randと同じ働きをする。
*
*/
AppUtil.mt_rand = function(min, max) {
  var argc = arguments.length
  if (argc === 0) {
    min = 0
    max = 2147483647
  } else if (argc === 1) {
    throw new Error('Warning: mt_rand() expects exactly 2 parameters, 1 given')
  } else {
    min = parseInt(min, 10)
    max = parseInt(max, 10)
  }
  return Math.floor(Math.random() * (max - min + 1)) + min
}


/**
* 引数の数字がプラスなら+、マイナスなら-とつけて返す。
*
*/
AppUtil.plus_minus = function(num){
    if(parseInt(num) >=0)
      return "+" + num;
    else
      return "-" + num;
}


/**
* 引数のオブジェクトを再帰的に処理し、キーをくっつけて一次元にしてグローバル変数、parse_arrに格納する。
* 再帰処理をしてるため使う前に必ずparse_arrを初期化すること。
*/
var parse_arr = {};
AppUtil.array_parse = function(array, prefix){
    $.each(array,function(key, value){
        if(typeof value == 'object'){
            AppUtil.array_parse(value, prefix + key + "_");
        }else{
            parse_arr[prefix + key] = value;
        }
    });
}



//---------------------------------------------------------------------------------------------------------
/*
 *  指定されたhistory_logレコードの内容を説明するHTMLを出力するテンプレート
 * 
 *     パラメータ)
 *         history   表示したいhistory_logレコード。
 *
*/
AppUtil.getHistoryText = function(history) {
    var text = "";

    if(history.deleted_at){
        text = '(削除)';
    }else if(history.type == History_LogService_TYPE_BATTLE_CHALLENGE || history.type == History_LogService_TYPE_BATTLE_DEFENCE){
        if(history.battle){
            /* 誰と戦ったのか */
            text = history.battle.rival_character_name + 'に対戦を';
            if(history.type == 1)
              text += "挑みました";
            else
              text += "挑まれました";

            /* バトルサマリ */
            text += "結果：";
            switch(history.battle.bias_status){
              case "win":
                text += '勝ち';
                break;
              case "lose":
                text += '負け';
                break;
              case "draw":
                text += '相討ち';
                break;
              case "timeup":
                text += '時間切れ';
                break;
            }
            if(history.battle.tournament_id == Tournament_MasterService_TOUR_MAIN){
                text += "階級pt：" + history.battle.bias_result.gain.grade;
            }
            /* コメント */
            if(history.battle.comment){
                text += AppUtil.nl2br(history.battle.comment);
            }
        }else{
            text = "ﾊﾞﾄﾙ情報は削除されました";
        }
    }else if(history.type == History_LogService_TYPE_CHANGE_GRADE){
        text = history.player_name + "が" + history.grade.grade_name + "に";
        if(history.ref2_value > 0)
            text += "昇格";
        else
            text += "降格";
        text += "しました";

    }else if(history.type == History_LogService_TYPE_LEVEL_UP){
        text = history.player_name + "のﾚﾍﾞﾙが" + history.ref2_value + "になりました";

    }else if(history.type == History_LogService_TYPE_EFFECT_TIMEUP){
        text = history.player_name + "の" + history.effect_name + "の効果がきれました";

    }else if(history.type == History_LogService_TYPE_INVITE_SUCCESS){
        if(history.invited){
            text =  history.invited.short_name + "さんがｹﾞｰﾑ招待に応じてくれました。特典ｹﾞｯﾄ!";
        }else{
            text =  "友だち招待に応じたため特典ｹﾞｯﾄ!";
        }
    }else if(history.type == History_LogService_TYPE_PRESENTED){
        text = history.giver.short_name + "さんから" + history.item.item_name + "をﾌﾟﾚｾﾞﾝﾄしてもらいました";

    }else if(history.type == History_LogService_TYPE_QUEST_FIN){
        //廃止
    }else if(history.type == History_LogService_TYPE_ITEM_BREAK){
        text = history.item.item_name + "が壊れました";

    }else if(history.type == History_LogService_TYPE_ITEM_LVUP){
        text = history.item.item_name + "がLv" + history.ref2_value + "になりました";

    }else if(history.type == History_LogService_TYPE_WEEKLY_HIGHER){
        text = "週間ﾗﾝｷﾝｸﾞ" + history.ref1_value + "位!" + history.item.item_name + "をｹﾞｯﾄしました";

    }else if(history.type == History_LogService_TYPE_CAPTURE){
        text = history.rare_name + "ﾓﾝｽﾀｰ" + history.monster.monster_name + "をｹﾞｯﾄしました";

    }else if(history.type == History_LogService_TYPE_ADMIRED){
        //廃止
    }else if(history.type == History_LogService_TYPE_REPLIED){
        //廃止
    }else if(history.type == History_LogService_TYPE_COMMENT){
        //廃止
    }else if(history.type == History_LogService_TYPE_QUEST_FIN2){
        text = "クエスト『" + history.summary.quest_name + "』を";
        switch(history.summary.result){
            case "1":
                text += '成功';
                break;
            case "2":
                text += '失敗';
                break;
            case "3":
                text += 'ｷﾞﾌﾞｱｯﾌﾟ';
                break;
            case "4":
                text += '脱出';
                break;
        }
        text += "しました";

        if(history.summary.attain_stair)
            text += history.summary.attain_stair + "階まで到達";

    }else if(history.type == History_LogService_TYPE_TEAM_BATTLE){
        //廃止
    }

    return text;
}

//---------------------------------------------------------------------------------------------------------
/**
 * チュートリアル用矢印を生成する
 * idはtutorial_arrowとしておく
 *
 * dest : up/down/left/right
 *
 */
AppUtil.showArrow = function(content, dest,top,left) {
    var dom = $("<img>");

    //ID設定
    dom.attr("id","tutorial_arrow");

    //矢印画像を読み込み
    dom.attr("src",AppUrl.asset("img/parts/sp/arrow_"+dest+".png"));

    //位置を指定
    dom.css("top",top + "px");
    dom.css("left",left + "px");

    dom.css("z-index",100);
    dom.css("position","absolute");

    //70pxで使用
    dom.css("width","70px");

    //domを追加する。
    content.append(dom);

    //点滅させる
    setInterval(function(){
        $('#tutorial_arrow').fadeOut(500,function(){$(this).fadeIn(500)});
    },1000);
}

//---------------------------------------------------------------------------------------------------------
/**
 * チュートリアル用矢印を消す
 */
AppUtil.removeArrow = function() {
    $("#tutorial_arrow").remove();
}


//---------------------------------------------------------------------------------------------------------
/**
 * phpのhtmlspecialchars_decode
 */
AppUtil.htmlspecialchars_decode  = function(string, quote_style) {
  //       discuss at: http://phpjs.org/functions/htmlspecialchars_decode/
  //      original by: Mirek Slugen
  //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      bugfixed by: Mateusz "loonquawl" Zalega
  //      bugfixed by: Onno Marsman
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //         input by: ReverseSyntax
  //         input by: Slawomir Kaniecki
  //         input by: Scott Cariss
  //         input by: Francois
  //         input by: Ratheous
  //         input by: Mailfaker (http://www.weedem.fr/)
  //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
  //        returns 1: '<p>this -> &quot;</p>'
  //        example 2: htmlspecialchars_decode("&amp;quot;");
  //        returns 2: '&quot;'

  var optTemp = 0,
    i = 0,
    noquotes = false;
  if (typeof quote_style === 'undefined') {
    quote_style = 2;
  }
  string = string.toString()
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>');
  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  };
  if (quote_style === 0) {
    noquotes = true;
  }
  if (typeof quote_style !== 'number') {
    // Allow for a single string or an array of string flags
    quote_style = [].concat(quote_style);
    for (i = 0; i < quote_style.length; i++) {
      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
      if (OPTS[quote_style[i]] === 0) {
        noquotes = true;
      } else if (OPTS[quote_style[i]]) {
        optTemp = optTemp | OPTS[quote_style[i]];
      }
    }
    quote_style = optTemp;
  }
  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
    string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
    // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
  }
  if (!noquotes) {
    string = string.replace(/&quot;/g, '"');
  }
  // Put this in last place to avoid escape being double-decoded
  string = string.replace(/&amp;/g, '&');

  return string;
}