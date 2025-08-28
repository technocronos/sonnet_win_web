<?php
/**
 * プログラムを配置する環境に左右される設定を行うファイル。
 **/

// データベース定義

$DATABASE_SPEC = array(
    'default' => array(
        'hostspec' => 'mariadb',
        'database' => 'sonnet_1',
        'username' => 'sonnet_mas',
        'password' => 'HHil0OjGZTXv0JEh6IN5',
    ),
    'sonnet_m' => array(
        'hostspec' => '127.0.0.1',
        'database' => 'sonnet_m',
        'username' => 'sonnet_mas',
        'password' => 'HHil0OjGZTXv0JEh6IN5',
    ),
);

// 各テーブルのデータベース定義
$TABLE_DATABASE = array(
    // テーブル名をキー、配置されているデータベース設定名を値とする。
    // 例) 'sample' => array('master'=>'masDB', 'slave'=>'slaDB', 'kanri'=>'kanDB');
    // "slave", "kanri" でも "master" と同じものを使うなら、単なる文字列としてもOK。
    // ここで設定されていない場合は "default" のデータベースを使用する。

#     'character_effect' =>     'default',
);


// 運営会社名、ゲーム名、ドメイン、ルートURL
define("COMPANY_NAME", "株式会社ﾃｸﾉｸﾛﾉｽ");
define("SITE_NAME", "ｿﾈｯﾄ･ｵﾌﾞ･ｳｨｻﾞｰﾄﾞ(ﾃｽﾄ)");
define("SITE_SHORT_NAME", "ｿﾈｯﾄ");
define("SITE_DOMAIN", $_SERVER["HTTP_HOST"]);
define("APP_WEB_ROOT", 'https://' . SITE_DOMAIN . "/");

//リリース日時
define('RELEASE_DATE', '2021-03-17 16:00:00');      // リリース日時
define("TEST_USER", "635707243"); //テストユーザー
define("SOUND_TEST", false); //テストユーザー

// プラットフォーム、環境の情報
define('PLATFORM_TYPE', 'nati');
define('ENVIRONMENT_TYPE', 'test');     // "test":テスト環境  か  "prod":本番  か。
define('EXAMINATION_STEP', 0);          // 審査通過前なら0、通過後なら1を指定。テスト環境では無視される。
define('MAINTENANCE_MODE', false);      // Adminモジュール以外のアクセスを遮断したい場合はtrueにする。

define('ETH_ADDRESS', "0xa313231E03768d3e563Df11D27f9295CDbe8072B");
define('ETH_ADDRESS_PASS', "ca890b3c0af2feff89e6e68d0e914b00d0fbccd7e349f1eea8189dffbc4f6a6e");
define('CONTRACT_ADDRESS', "0xaD9770848A2Eb75f67506B448F032FDC697d7F9b");

 // resourceのハッシュ値
//define('RESOURCE_HASH_ANDROID', "ef64fd8a3352abc57bea5d1a9aeb24eb");  
//define('RESOURCE_HASH_IOS', "fc4ef31eaa1fcadbef09d68d36354a44");

// プラットフォームから発行される情報
switch($_SERVER["HTTP_HOST"]) {
    case 'test.gree.sonnet.crns-game.net':
        define("APP_ID", '4162');
        define('CONSUMER_KEY', '0a32c8652d13');
        define('CONSUMER_SECRET', '6c832305d8f2aa8a4ed94084908cc045');
        define("OFFICIAL_COMMUNITY_URL", 'http://mgadget-sb.gree.jp/'.APP_ID.'?guid=ON&url=http%3A%2F%2F'.SITE_DOMAIN.'%2F%3Faction%3DStatic%26module%3DUser%26id%3Dcommunity');        // 公式コミュニティのURL
        break;
    case 'test.mbga.sonnet.crns-game.net':
        define("APP_ID", '12007857');
        define('CONSUMER_KEY', '1aca5af342752fe2785a');
        define('CONSUMER_SECRET', '29b2882af79ef92f53de29799c2d5d748976e247');
        define("OFFICIAL_COMMUNITY_URL", 'http://sb.pf.mbga.jp/'.APP_ID.'?guid=ON&url=http%3A%2F%2F'.SITE_DOMAIN.'%2F%3Faction%3DStatic%26module%3DUser%26id%3Dcommunity');        // 公式コミュニティのURL
        break;
    case 'test.mixi.sonnet.crns-game.net':
        define("APP_ID", '35322');
        define('CONSUMER_KEY', 'af205282fe6718ba099d');
        define('CONSUMER_SECRET', '197367315a7c1125a4cb1042642b434f0945db97');
        define("OFFICIAL_COMMUNITY_URL", 'http://mixi.net/');        // 公式コミュニティのURL
        break;
    case 'test.waku.sonnet.crns-game.net':
        define("APP_ID", '100447');
        define('CONSUMER_KEY', '3c13605dc5d2');
        define('CONSUMER_SECRET', 'c72a6f1266c097af74651efdcb805822');
        define("OFFICIAL_COMMUNITY_URL", 'http://wakupl.com/');   // 公式コミュニティのURL
        break;
    case 'test.native.sonnet.crns-game.net':
        define("APP_ID", '');
        define('CONSUMER_KEY', '');
        define('CONSUMER_SECRET', '');
        define("OFFICIAL_COMMUNITY_URL", '');   // 公式コミュニティのURL
        break;
}

// System Installation Directory
define('MO_BASE_DIR', '/var/www');


// 定数ファイルのインクルード
require(dirname(__FILE__).'/constants.php');
