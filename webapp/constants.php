<?php
/**
 * 配置環境に左右されない設定
 **/

//暫定対応。SHOP、管理Pなどページが大きいところでMobilePictogramConverterがメモリエラーになる。
ini_set('memory_limit', '512M');

// ゲームに関する設定
//=========================================================================================================

// 管理ページで使用
define("ADMIN_USER_LIST_PAGES", 100);

// 行動ptの最大値
define('ACTION_PT_MAX', 100.0);

// 平時の行動ptが1秒でいくつ回復するか。3->2.5時間で全快するペース
define('ACTION_PT_RECOVERY', ACTION_PT_MAX/(2.5*60*60));

// 対戦ptの最大値
define('MATCH_PT_MAX', 50.0);

// 平時の対戦ptが1秒でいくつ回復するか。60->90分で全快するペース
define('MATCH_PT_RECOVERY', MATCH_PT_MAX/(1.5*60*60));

// 平時のHPが1秒で何%回復するかを小数で。8時間で全快するペース
define('HP_RECOVERY', 1.0/(8*60*60));

// ユーザ対戦で消費するpt数
define('USER_BATTLE_CONSUME', 10);

// プラットフォームのユーザ投稿で得られる行動pt
define('ARTICLE_AP', 20);

// ゲーム内通貨の名称・単位
define('GOLD_NAME', 'マグナ');

// 同じ相手との一日バトル制限数
if(ENVIRONMENT_TYPE == 'prod')
    define('DUEL_LIMIT_ON_DAY_RIVAL', 3);
else
    define('DUEL_LIMIT_ON_DAY_RIVAL', 3);

// フィールドクエスト進行中、ユーザ対戦から保護される時間(時間)
define('DUEL_SPHERE_PROTECT_HOURS', 24);

// メッセージの文字数制限(全角換算)
define('MESSAGE_LENGTH_LIMIT', 100);

// メッセージ機能有効、無効
define('MESSAGE_ENABLE', true);

// ユーザ名の最大表示幅。
define('USERNAME_DISPLAY_WIDTH', 12);

// キャラクタ名の最大長(半角換算)。
define('CHARACTER_NAME_LENGTH', 12);

// どのゲームでも送信しそうなアクティビティ
define('ACTIVITY_GAME_START', 'ｹﾞｰﾑ｢'.SITE_NAME.'｣を開始!');            // ゲーム開始時
define('ACTIVITY_MEMORIAL_WIN', 'ｷｬﾗ"[:name:]"が[:num:]勝目!');         // 記念すべき勝利数
define('ACTIVITY_MEMORIAL_LOSE', 'ｷｬﾗ"[:name:]"が[:num:]敗目…');       // 記念すべき敗北数
define('ACTIVITY_MEMORIAL_LEVEL', 'ｷｬﾗ"[:name:]"がﾚﾍﾞﾙ[:level:]に!');   // 記念すべきレベル
define('ACTIVITY_GRADE_UP', '"[:grade:]"に昇格!');                      // 昇格
define('ACTIVITY_GRADE_DOWN', '"[:grade:]"に降格…');                   // 降格

// キャラクター画像のサイズ
define('CHARA_WIDTH', 80);
define('CHARA_HEIGHT', 100);

define('TWITTER_URI', 'https://twitter.com/sow_games');


// 画面幅。
define('SCREEN_WIDTH_MOBILE', 240);     // 携帯

//汚いけどandroid browserだけ360にしたかった(android browser対応は廃止予定)
if(preg_match("/Android|^dream|CUPCAKE|blackberry9500|blackberry9530|blackberry9520|blackberry9550|blackberry9800|webOS|incognito|webmate/", $_SERVER['HTTP_USER_AGENT'])){
    if(preg_match("/Chrome|Firefox/", $_SERVER['HTTP_USER_AGENT'])){
        define('SCREEN_WIDTH_PC',     320);     // PC
    }else{
        define('SCREEN_WIDTH_PC',     360);     // PC
    }
}else{
    define('SCREEN_WIDTH_PC',     320);     // PC
}

// ディレクトリやMojaviの設定、その他自動的に定まる定数
//=========================================================================================================

// PEAR Installation Directory
define('MO_PEAR_DIR', '/usr/local/lib/php');

/**
 * An absolute file-system path to the all-in-one class file Mojavi
 * uses.
 */
define('MOJAVI_FILE', MO_BASE_DIR.'/framework/mojavi/mojavi.php');

/**
 * ログのフォーマットパラメータ
 * c .... クラス名
 * F .... ファンクション名
 * l .... 行番号
 * m .... メッセージ
 * N .... メッセージ名
 * p .... メッセージパラメータ番号
 * n .... 改行コード
 * r .... 復帰コード
 * t .... タブコード
 * T .... 時刻
 * C .... 定数名（%C{定数名}とすると、定数の値が出力される）
 * d .... 日付（%d{書式}とすると、日付のフォーマットが指定できる）
 * f .... ファイル名（%f{'file'}とすると、basenameが出力され、%f{'dir'}とすると、dirnameが出力される）
 * x .... 任意の値（Loggerに格納されたパラメータ名を指定すると、その値が出力される）
 */
define('MO_LOG_DIR', MO_BASE_DIR . '/var/logs');
define('MO_LOG_FILENAME', 'mojavi_%d{Ymd}.log');
define('MO_LOG_PATTERN_LAYOUT', '[%N] %d{Y/m/d H:i:s} [%x{ip_address} - %x{login_id}] %m %c::%F() %f:%l %x{data}%n');

// +---------------------------------------------------------------------------+
// | Should we run the system in debug mode? When this is on, there may be     |
// | various side-effects. But for the time being it only deletes the cache    |
// | upon start-up.                                                            |
// |                                                                           |
// | This should stay on while you're developing your application, because     |
// | many errors can stem from the fact that you're using an old cache file.   |
// +---------------------------------------------------------------------------+
define('MO_DEBUG', false);

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the mojavi package. This directory         |
// | contains all the Mojavi packages.                                         |
// +---------------------------------------------------------------------------+
define('MO_APP_DIR', MO_BASE_DIR . '/framework/mojavi');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to your web application directory. This       |
// | directory is the root of your web application, which includes the core    |
// | configuration files and related web application data.                     |
// +---------------------------------------------------------------------------+
define('MO_WEBAPP_DIR', MO_BASE_DIR . '/webapp');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the directory where cache files will be    |
// | stored.                                                                   |
// |                                                                           |
// | NOTE: If you're going to use a public temp directory, make sure this is a |
// |       sub-directory of the temp directory. The cache system will attempt  |
// |       to clean up *ALL* data in this directory.                           |
// +---------------------------------------------------------------------------+
define('MO_CACHE_DIR', MO_BASE_DIR.'/var/cache/mojavi');

// +---------------------------------------------------------------------------+
// | The PHP error reporting level.                                            |
// |                                                                           |
// | See: http://www.php.net/error_reporting                                   |
// +---------------------------------------------------------------------------+
define('MO_ERROR_REPORTING', E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);

// display_errorsの設定。
ini_set('display_errors', ENVIRONMENT_TYPE != 'prod');

// Smarty について
define('MO_SMARTY_DIR', MO_BASE_DIR.'/framework/smarty');       // Smartyが入っているディレクトリ
define('MO_SMARTY_CACHE', MO_BASE_DIR.'/var/cache/smarty');     // Smartyのコンパイルキャッシュディレクトリ

// MobilePictogramConverterについて
define('MO_MPC_DIR', MO_BASE_DIR.'/framework/MobilePictogramConverter');    // MobilePictogramConverterが入っているディレクトリ

// 内部参照ファイルを配置するディレクトリ
define('RESOURCES_DIR', MO_BASE_DIR.'/resources');

// SWFについて
define('SWF_PATH', RESOURCES_DIR.'/swf');                   // SWF元ファイルの格納ディレクトリ
define('SWF_PATH_N', RESOURCES_DIR.'/swf_n');                   // SWF元ファイルの格納ディレクトリ
define('SWF_WORKING_DIR', '/dev/shm');                      // SWF作成時のワーキングディレクトリ
define('FLASM_COMMAND', '/usr/local/bin/flasm');            // flasmコマンドへのパス
define('SWF_MIXI_TMPDIR', MO_BASE_DIR.'/var/tmp/swf');      // mixiで使用する、SWFの一時保管場所
define('SWF_SM_TMPDIR', MO_BASE_DIR.'/htdocs/swf');      // スマホ版で使用する、SWFの一時保管場所

// ドキュメントルートの物理パス
define('MO_HTDOCS', MO_BASE_DIR.'/htdocs');

// WEBで公開しない、内部リソースとして使用する画像データの置き場
define('IMG_RESOURCE_DIR', RESOURCES_DIR.'/image');

// コントローラーのURL
// mixiで、SWFからHTMLへの遷移時に問題が出るので、"index.php" は省略できない。
// どんな問題かというと、mixiには「アプリトップ以外は、ガジェットURLがあるディレクトリと同じか、
// その配下のディレクトリじゃないと、直リンクできないよ」という制限にひっかかる問題。
// mixiが干渉できないSWF内のURLは直リンクとして認識される。そこに「ガジェットURLが "/gadget.php"
// の場合に、URLパス "/" が同じディレクトリとして認識されない」という糞仕様が加わってこのような問題になる。
define('APP_CONTROLLER', APP_WEB_ROOT.'index.php');


// キャラクター画像の合成処理用作業ディレクトリと、合成後のファイル置き場。
define('CHARA_TMP_DIR', MO_BASE_DIR.'/var/tmp/character');
define('CHARA_CACHE_DIR', MO_BASE_DIR.'/var/cache/character');

define('WIDE_STAMP', 1000065);

//コインのアイテムID
define('COIN_ITEM_ID', 99003);

//仮想通貨フィー
define('VCOIN_FEE', 0.0005);
//仮想通貨最低出金額
define('VCOIN_MINIMAM', 0.002);
//仮想通貨出金可能課金額
define('VCOIN_MINIMAM_PAYMENT', 5000);

//付与するビットコイン
define('BTC_AMOUNT_RARE1', 0.000003); //ザコ
define('BTC_AMOUNT_RARE2', 0.000023); //ボス
define('BTC_AMOUNT_RARE3', 0.000083); //大ボス

//付与するレイドポイント
define('RAID_AMOUNT_RARE1', 10); //ザコ
define('RAID_AMOUNT_RARE2', 30); //ボス
define('RAID_AMOUNT_RARE3', 100); //大ボス



// PLATFORM_API_CLASS       プラットフォームAPIにアクセスするためのクラス名
// PLATFORM_NAME            プラットフォームの名前
// PLATFORM_CURRENCY_NAME   プラットフォームの課金用通貨の名前
// PLATFORM_COMMUNITY_NAME  プラットフォームのコミュニティの名前
// PLATFORM_ARTICLE_NAME    プラットフォームのユーザ投稿機能の名前
// PLATFORM_PROFIT_RATIO    プラットフォームの売上に対する還元率
// PLATFORM_GADGET_URL      プラットフォームのユーザアクセス用URL
// PLATFORM_API_URL         プラットフォームのAPIのURL
// PLATFORM_OPERATOR_URL    プラットフォームの運営者のURL。ない場合はカラ文字
// SMARTPHONE_JS_URL        プラットフォームから提供されるセッション管理支援JSファイルのURL
// SHORTCUT_KEY_MENU, SHORTCUT_IND_MENU     アプリメニューページへのショートカットキーと表示
// SHORTCUT_KEY_SUB1, SHORTCUT_IND_SUB1     同じく、サブ機能1。ソネットではステータスへの遷移がこれにあたる。
// SHORTCUT_KEY_SUB2, SHORTCUT_IND_SUB2     同じく、サブ機能2。ページによって様々。
// COMMENT_NAME, ADMIRATION_NAME            コメントの機能名、称賛の機能名
switch(PLATFORM_TYPE) {
    case 'gree':
        define('PLATFORM_API_CLASS', 'GreeApi');
        define('PLATFORM_NAME', 'GREE');
        define('PLATFORM_CURRENCY_NAME', 'ｺｲﾝ');
        define('PLATFORM_COMMUNITY_NAME', 'ｺﾐｭﾆﾃｨ');
        define('PLATFORM_ARTICLE_NAME', 'GREEひとこと');
        define('PLATFORM_PROFIT_RATIO', 0.609);

        define('URL_TYPE', "direct");

        if(ENVIRONMENT_TYPE == 'test') {
            define('CONTAINER_URL_MOBILE', 'http://mgadget-sb.gree.jp/'.APP_ID);
            define('CONTAINER_URL_PC', 'http://pf-sb.gree.jp/'.APP_ID);
            define('PLATFORM_GADGET_URL', 'http://mgadget-sb.gree.jp/'.APP_ID);
            define('PLATFORM_API_URL', 'http://os-sb.gree.jp/api/rest');
            define('SMARTPHONE_JS_URL','pf-sb.gree.net');
            define('PLATFORM_JS', 'http://aimg-pf.gree.net/js/app/gree.js');
        }else {
            define('CONTAINER_URL_MOBILE', 'http://mgadget.gree.jp/'.APP_ID);
            define('CONTAINER_URL_PC', 'http://pf.gree.net/'.APP_ID);
            define('PLATFORM_GADGET_URL', 'http://mgadget.gree.jp/'.APP_ID);
            define('PLATFORM_API_URL', 'http://os.gree.jp/api/rest');
            define('SMARTPHONE_JS_URL','aimg-pf.gree.net');
            define('PLATFORM_JS', 'http://aimg-pf.gree.net/js/app/gree.js');
        }
        define('PLATFORM_OPERATOR_URL', '');
        define('SHORTCUT_KEY_MENU', '2');   define('SHORTCUT_IND_MENU', '');
        define('SHORTCUT_KEY_SUB1', '3');   define('SHORTCUT_IND_SUB1', '');
        define('SHORTCUT_KEY_SUB2', '4');   define('SHORTCUT_IND_SUB2', '');
        define('COMMENT_NAME', 'つぶやき');   define('ADMIRATION_NAME', 'ｲｲﾈ');
        define('SONNET_NOW_OPEN', true);
        define('TEAM_BATTLE_OPEN', true);
        break;
    case 'mbga':
        require_once(MO_WEBAPP_DIR . "/lib/Common.class.php");

        define('PLATFORM_API_CLASS', 'MobageApi');
        define('PLATFORM_NAME', 'MOBAGE');
        define('PLATFORM_CURRENCY_NAME', 'ﾓﾊﾞｺｲﾝ');
        define('PLATFORM_COMMUNITY_NAME', 'ｻｰｸﾙ');
        define('PLATFORM_ARTICLE_NAME', 'Mobage日記');
        define('PLATFORM_PROFIT_RATIO', 0.609);
        define('PLATFORM_JS', '');

        define('URL_TYPE', "container");

        if(ENVIRONMENT_TYPE == 'test') {
            define('CONTAINER_URL_MOBILE', 'http://sb.pf.mbga.jp/' . APP_ID);
            define('CONTAINER_URL_PC', 'http://g' . APP_ID . '.sb.sp.pf.mbga.jp');
            define('PLATFORM_API_URL', 'http://sb.sp.app.mbga.jp/api/restful/v1');
            define('PLATFORM_OPERATOR_URL', 'http://sb.mbga.jp/_pf_developer?game_id='.APP_ID);
        }else {
            define('CONTAINER_URL_MOBILE', 'http://pf.mbga.jp/' . APP_ID);
            define('CONTAINER_URL_PC', 'http://g' . APP_ID .'.sp.pf.mbga.jp');
            define('PLATFORM_API_URL', 'http://sp.app.mbga.jp/api/restful/v1');
            define('PLATFORM_OPERATOR_URL', 'http://mbga.jp/_pf_developer?game_id='.APP_ID);
        }

        define('SHORTCUT_KEY_MENU', '2');   define('SHORTCUT_IND_MENU', '');
        define('SHORTCUT_KEY_SUB1', '');    define('SHORTCUT_IND_SUB1', '');
        define('SHORTCUT_KEY_SUB2', '');    define('SHORTCUT_IND_SUB2', '');
        define('COMMENT_NAME', 'つぶやき');   define('ADMIRATION_NAME', 'ｲｲﾈ');
        define('SONNET_NOW_OPEN', false);
        define('TEAM_BATTLE_OPEN', false);

        define('VCOIN_RELEASE_FLG', false);

        //ビットコインキャンペーン
        define('BTC_CAMPAIGN_NAME', '春のビットコインキャンペーン');
        define('BTC_CAMPAIGN_START_DATE', '2017/07/10 00:00:00');
        define('BTC_CAMPAIGN_END_DATE', '2017/08/10 23:59:59');

        break;
    case 'mixi':
        define('PLATFORM_API_CLASS', 'MixiApi');
        define('PLATFORM_NAME', 'mixi');
        define('PLATFORM_CURRENCY_NAME', 'mixiﾎﾟｲﾝﾄ');
        define('PLATFORM_COMMUNITY_NAME', 'ｺﾐｭﾆﾃｨ');
        define('PLATFORM_ARTICLE_NAME', 'mixiﾎﾞｲｽ');
        define('PLATFORM_PROFIT_RATIO', 0.750);
        define('PLATFORM_JS', '');

        define('URL_TYPE', "container");

        if(ENVIRONMENT_TYPE == 'test')
#             define('PLATFORM_GADGET_URL', 'http://ma.test.mixi.net/'.APP_ID.'/');
            define('PLATFORM_GADGET_URL', 'http://ma.mixi.net/'.APP_ID.'/');
        else
            define('PLATFORM_GADGET_URL', 'http://ma.mixi.net/'.APP_ID.'/');
        define('PLATFORM_API_URL', 'http://api.mixi-platform.com/os/0.8');
        define('PLATFORM_OPERATOR_URL', '?guid=ON&url='.urlencode(APP_WEB_ROOT.'index.php?module=User&action=Static&id=operator'));
        define('SHORTCUT_KEY_MENU', '4');   define('SHORTCUT_IND_MENU', '');
        define('SHORTCUT_KEY_SUB1', '3');   define('SHORTCUT_IND_SUB1', '');
        define('SHORTCUT_KEY_SUB2', '6');   define('SHORTCUT_IND_SUB2', '');
        define('COMMENT_NAME', 'コメント');   define('ADMIRATION_NAME', '(*^ｰﾟ)b');
        define('SONNET_NOW_OPEN', true);
        define('TEAM_BATTLE_OPEN', true);
        break;
    case 'waku':
        define('PLATFORM_API_CLASS', 'WakuApi');
        define('PLATFORM_NAME', 'WAKU+');
        define('PLATFORM_CURRENCY_NAME', 'ｺｲﾝ');
        define('PLATFORM_COMMUNITY_NAME', 'ｻｰｸﾙ');
        define('PLATFORM_ARTICLE_NAME', 'WAKU+日記');
        define('PLATFORM_PROFIT_RATIO', 0.696);
        define('PLATFORM_JS', '');

        define('URL_TYPE', "container");

        if(ENVIRONMENT_TYPE == 'test') {
            define('CONTAINER_URL_MOBILE', 'http://pf.sb.wakupl.com/'.APP_ID.'/');
            define('CONTAINER_URL_PC', 'http://pf.sb.wakupl.com/'.APP_ID.'/');
            define('PLATFORM_API_URL', 'http://os.sb.wakupl.com/api/rest/v1');
        }else {
            define('CONTAINER_URL_MOBILE', 'http://pf.wakupl.com/'.APP_ID.'/');
            define('CONTAINER_URL_PC', 'http://pf.wakupl.com/'.APP_ID.'/');
            define('PLATFORM_API_URL', 'http://os.wakupl.com/api/rest/v1');
        }
        define('SHORTCUT_KEY_MENU', '2');   define('SHORTCUT_IND_MENU', '');
        define('SHORTCUT_KEY_SUB1', '');    define('SHORTCUT_IND_SUB1', '');
        define('SHORTCUT_KEY_SUB2', '');    define('SHORTCUT_IND_SUB2', '');
        define('COMMENT_NAME', 'つぶやき');   define('ADMIRATION_NAME', 'ｲｲﾈ');
        define('SONNET_NOW_OPEN', false);
        define('TEAM_BATTLE_OPEN', false);

        define('STARTDUSH_CAMPAIGN_START_DATE', '2015/10/22 12:00:00');
        define('STARTDUSH_CAMPAIGN_END_DATE', '2015/11/05 12:00:00');

        define('VCOIN_RELEASE_FLG', true);

        //ビットコインキャンペーン
        define('BTC_CAMPAIGN_NAME', '春のビットコインキャンペーン');
        define('BTC_CAMPAIGN_START_DATE', '2017/07/10 00:00:00');
        define('BTC_CAMPAIGN_END_DATE', '2017/08/10 23:59:59');

        break;
    case 'niji':
        define('PLATFORM_API_CLASS', 'NijiApi');
        define('PLATFORM_NAME', 'にじよめ');
        define('PLATFORM_CURRENCY_NAME', 'にじｺｲﾝ');
        define('PLATFORM_CURRENCY_UNIT', 'ｺｲﾝ');
        define('PLATFORM_COMMUNITY_NAME', 'ｺﾐｭﾆﾃｨ');
        define('PLATFORM_ARTICLE_NAME', '');
        define('PLATFORM_PROFIT_RATIO', 0.7);

        define('URL_TYPE', "direct");

        //define('GET_ON_POST', true);
        if(ENVIRONMENT_TYPE == 'test') {
            define('CONTAINER_URL_MOBILE', '');
            define('CONTAINER_URL_PC', 'https://sb.nijiyome.jp/apps/start/'.APP_ID);
            define('PLATFORM_API_URL', 'http://spapi.nijiyome.jp/rest');
            define('PLATFORM_JS', '//spgm.nijiyome.jp/js/touch.js');
        }else {
            define('CONTAINER_URL_MOBILE', '');
            define('CONTAINER_URL_PC', 'https://nijiyome.jp/app/start/'.APP_ID);
            define('PLATFORM_API_URL', 'http://api.nijiyome.jp/rest');
            define('PLATFORM_JS', '//gm.nijiyome.jp/js/touch.general.js');
        }
        define('PLATFORM_OPERATOR_URL', '');
        define('SHORTCUT_KEY_MENU', '2');   define('SHORTCUT_IND_MENU', '');
        define('SHORTCUT_KEY_SUB1', '3');   define('SHORTCUT_IND_SUB1', '');
        define('SHORTCUT_KEY_SUB2', '4');   define('SHORTCUT_IND_SUB2', '');

        define('STARTDUSH_CAMPAIGN_START_DATE', '2018/04/10 12:00:00');
        define('STARTDUSH_CAMPAIGN_END_DATE', '2018/04/17 23:59:59');

        define('VCOIN_RELEASE_FLG', true);

        //ビットコインキャンペーン
        define('BTC_CAMPAIGN_NAME', '春のビットコインキャンペーン');
        define('BTC_CAMPAIGN_START_DATE', '2021/01/15 00:00:00');
        define('BTC_CAMPAIGN_END_DATE', '2021/04/15 23:59:59');

        //第何週に開始するか。0の場合は毎週。
        define('BATTLE_RANK_WEEK', 3);

        break;
    case 'geso':
        define('PLATFORM_API_CLASS', 'GesoApi');
        define('PLATFORM_NAME', 'ゲソ天');
        define('PLATFORM_CURRENCY_NAME', 'ゲソコイン');
        define('PLATFORM_CURRENCY_UNIT', 'ゲソコイン');
        define('PLATFORM_COMMUNITY_NAME', 'コミュニティ');
        define('PLATFORM_ARTICLE_NAME', '');
        define('PLATFORM_PROFIT_RATIO', 0.7);

        define('URL_TYPE', "direct");

        if(ENVIRONMENT_TYPE == 'test') {
            define('CONTAINER_URL_MOBILE', '');
            define('CONTAINER_URL_PC', 'http://s.gesoten.com/games/game_preview/'.APP_ID);
            define('PLATFORM_API_URL', 'http://app.s.gesoten.com/social/rest');
            define('PLATFORM_JS', '');
            define('PLATFORM_FOOTER_JS', 'http://s.gesoten.com/games/game_footer_js/'.APP_ID);
        }else {
            define('CONTAINER_URL_MOBILE', '');
            define('CONTAINER_URL_PC', 'http://gesoten.com/games/game_preview/'.APP_ID);
            define('PLATFORM_API_URL', 'http://app.gesoten.com/social/rest');
            define('PLATFORM_JS', '');
            define('PLATFORM_FOOTER_JS', 'http://gesoten.com/games/game_footer_js/'.APP_ID);
        }
        define('PLATFORM_OPERATOR_URL', '');
        define('SHORTCUT_KEY_MENU', '2');   define('SHORTCUT_IND_MENU', '');
        define('SHORTCUT_KEY_SUB1', '3');   define('SHORTCUT_IND_SUB1', '');
        define('SHORTCUT_KEY_SUB2', '4');   define('SHORTCUT_IND_SUB2', '');

        define('STARTDUSH_CAMPAIGN_START_DATE', '2018/06/5 17:00:00');
        define('STARTDUSH_CAMPAIGN_END_DATE', '2018/06/12 23:59:59');

        define('VCOIN_RELEASE_FLG', true);

        //ビットコインキャンペーン
        define('BTC_CAMPAIGN_NAME', '春のビットコインキャンペーン');
        define('BTC_CAMPAIGN_START_DATE', '2018/07/11 00:00:00');
        define('BTC_CAMPAIGN_END_DATE', '2018/08/11 23:59:59');

        break;
    case 'nati':
        define('PLATFORM_API_CLASS', 'NativeApi');
        define('PLATFORM_NAME', 'ネイティブ');
        define('PLATFORM_CURRENCY_NAME', 'ｺｲﾝ');
        define('PLATFORM_CURRENCY_UNIT', 'ｺｲﾝ');
        define('PLATFORM_COMMUNITY_NAME', 'ｺﾐｭﾆﾃｨ');
        define('PLATFORM_ARTICLE_NAME', '');
        define('PLATFORM_PROFIT_RATIO', 0.7);

        define('URL_TYPE', "direct");

        define('IOS_VER', 60);        //iosのビルド番号
        define('ANDROID_VER', 1); //androidのversionCode

        define('APP_STORE_URL', 'https://apps.apple.com/app/id1372485938');        //iosのストアURL
        define('GOOGLE_PLAY_URL', 'https://play.google.com/store/apps/details?id=jp.technocronos.sonnet'); //androidのストアURL

        //define('GET_ON_POST', true);
        if(ENVIRONMENT_TYPE == 'test') {
            define('CONTAINER_URL_MOBILE', '');
            define('CONTAINER_URL_PC', '');
            define('PLATFORM_API_URL', '');
            define('PLATFORM_JS', '');

            define('IOS_VERIFY_URL', 'https://sandbox.itunes.apple.com/verifyReceipt');
        }else {
            define('CONTAINER_URL_MOBILE', '');
            define('CONTAINER_URL_PC', '');
            define('PLATFORM_API_URL', '');
            define('PLATFORM_JS', '');

            if(EXAMINATION_STEP == 1)
                define('IOS_VERIFY_URL', 'https://buy.itunes.apple.com/verifyReceipt');
            else
                define('IOS_VERIFY_URL', 'https://sandbox.itunes.apple.com/verifyReceipt');
        }
        define('PLATFORM_OPERATOR_URL', '');

        //スタートダッシュキャンペーン
        define('STARTDUSH_CAMPAIGN_START_DATE', '2021/03/17 00:00:00');
        define('STARTDUSH_CAMPAIGN_END_DATE', '2031/04/14 23:59:59');

        define('STARTDUSH_CAMPAIGN_GET_ITEM', COIN_ITEM_ID);
        define('STARTDUSH_CAMPAIGN_GET_AMOUNT', 500);

        //ビットコインキャンペーン
        //これをfalseにするとビットコインにまつわる表示が消える。
        //BTCを手に入れることができなくなるわけではないので注意。
        define('VCOIN_RELEASE_FLG', true);

        //ビットコインキャンペーンタイトル
        if(isset($_GET["lang"]) && $_GET["lang"] == 1)
            define('BTC_CAMPAIGN_NAME', 'Winter Bitcoin Campaign');
        else
            define('BTC_CAMPAIGN_NAME', '総額100万円！秋のビットコインキャンペーン');

        //ビットコインの出金停止フラグ
        //trueにすると出金停止となる。総額を超えた時などに使用。併せて期間も短くしてBTC獲得も終了とされたい。
        define('BTC_CAMPAIGN_PAYMENT_STOP', false);

        //BTC出金申請リセット制限開始日時
        define('BTC_APPLY_RESTRICT_DATE', '2023-02-28 00:00:00');

        //ビットコインキャンペーン期間
        //これが切れるとモンスターやバトルランキング集計でBTCが手に入らなくなる。
        //表示がなくなるわけではないし、出金も可能。
        define('BTC_CAMPAIGN_START_DATE', '2023/01/01 00:00:00');
        define('BTC_CAMPAIGN_END_DATE', '2024/12/31 23:59:59');

        //第何週に開始するか。0の場合は毎週。
        define('BATTLE_RANK_WEEK', 3);

        define('TEAM_BATTLE_OPEN', true);

        define('ETH_ADDR_OPEN', false);

        //友達招待
        define('FRIEND_INVITE_START_DATE', '2022/10/01 00:00:00');
        define('FRIEND_INVITE_END_DATE', '2022/12/31 23:59:59');

        $dt = new DateTime();
        $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        $currenttime = $dt->format('Y-m-d H:i:s');

        if(strtotime(FRIEND_INVITE_START_DATE) <= strtotime($currenttime) && strtotime(FRIEND_INVITE_END_DATE) > strtotime($currenttime)){
            define('FRIEND_INVITE_OPEN', true);
        }else{
            define('FRIEND_INVITE_OPEN', false);
        }


        break;
}

// プラットフォームAPIの戻り値をキャッシュするかどうか。
define('USE_PLATFORM_CACHE', (EXAMINATION_STEP != 0));


// PHPの設定
//=========================================================================================================

// 通常のエラーを例外に変換する。
function exception_error_handler($errno, $errstr, $errfile, $errline ) {

    // 報告すべきエラーでないなら無視する。
    if( !(error_reporting() & $errno) )
        return false;

    // MojaviExceptionが使用できる状態にあるなら MojaviException に変換してスロー。
    if(class_exists('MojaviException', false))
        throw new MojaviException($errstr, 0, $errno, $errfile, $errline);

    // MojaviExceptionが使用できないなら標準処理に任せる。
    return false;
}
set_error_handler("exception_error_handler");


// PHPエラーログファイルの場所を変更する。
ini_set('error_log', MO_LOG_DIR.'/phperror_'.date('Ymd').'.log');


// PHP標準のapache_request_headersがない場合に、代替関数を定義する。
if (!function_exists('apache_request_headers')) { function apache_request_headers() {
    foreach($_SERVER as $key=>$value) {
        if (substr($key,0,5)=="HTTP_") {
            $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
            $out[$key]=$value;
        }
    }
    return $out;
}}


// PHP標準のarray_fill_keysがない場合に、代替関数を定義する。
if (!function_exists('array_fill_keys')) { function array_fill_keys($keys, $value) {
    $result = array();
    foreach($keys as $key) {
        $result[$key] = $value;
    }
    return $result;
}}
