<?php

// チュートリアル中はフッタリンクやトップページからこのアクションに遷移する。
//
// チュートリアルは結構ややこしいのでココで解説しておく。
// プロローグからチュートリアルは以下のステップで構成されている。
//
//     ユーザ情報なし
//         プロローグ＆名前入力
//     step=0
//         師匠の家・寸劇(オープニング)
//         (終了したらstep10にアップ)
//     step=10
//         メインメニュー＆クエストのチュートリアル
//         (クエスト開始したらstep20にアップ)
//     step=20
//         ファーストクエスト(精霊の洞窟)中
//         (クエスト終了したらstep30にアップ)
//     step=30
//         師匠の家・寸劇(精霊の洞窟を終えて)
//         ⇒続けて、チュートバトル
//         (チュートバトルが終わったらstep60にアップ)
//     step=40(廃止)
//         師匠の家・寸劇(チュートバトルを終えて)
//         (終了したらstep50にアップ)
//     step=50(廃止)
//         ステータス画面へのチュートリアル
//         (ステータス画面で「ｺｺ」リンクをクリックしたら60にアップ)
//     step=60
//         師匠の家・寸劇(ショップへの前振り)
//         (終了したらstep70にアップ)
//     step=70
//         ショップチュートリアル
//         (何かを買ったらstep75にアップ)
//     step=75
//         ガチャチュートリアル
//         (ガチャ回したらstep80にアップ)
//     step=80(廃止)
//         対戦チュートリアル
//         (対戦一覧で誰もいない or バトル確認画面到達で85にアップ)
//     step=85
//         装備チュートリアル
//         (装備完了で90にアップ、スマホ版のみ)
//     step=90
//         師匠の家・寸劇(チュートリアルを終えて＆クエストの依頼)
//         (終了したらstep100にアップ)
//     step=100
//         チュートリアル終了
//
// ユーザはいかなる場合でもこのアクションに来る場合があるので、
// すべてのステップに備えておく必要がある。

class TutorialAction extends DramaBaseAction {

    protected function onExecute() {

        // ドラマ完了通知の場合の処理。
        if(isset($_GET['end']))
            $this->processEnd();

        // チュートリアルのステップ完了の場合
        if(isset($_GET['done'])) {

            $userSvc = new User_InfoService();

            // チュートリアルを次段階へ。
            switch($_GET['done']) {
                case 'Status':      $current = User_InfoService::TUTORIAL_STATUS;   break;
                case 'Rival':       $current = User_InfoService::TUTORIAL_RIVAL;    break;
                default:            throw new MojaviException('不正な遷移です');
            }
            $userSvc->tutorialStepUp($this->user_id, $current);

            // ユーザレコードを取り直す
            $this->userInfo = $userSvc->needRecord($this->user_id);
        }

        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
            // 以下のチュートリアルステップは別アクションに飛ばす。
            switch($this->userInfo['tutorial_step']) {

                // Mainアクションへ
                case User_InfoService::TUTORIAL_MAINMENU:       // メインメニュー案内
                case User_InfoService::TUTORIAL_FIELD:          // ファーストクエスト中
                case User_InfoService::TUTORIAL_STATUS:         // ステータス案内
                case User_InfoService::TUTORIAL_SHOPPING:       // ショップ案内
                case User_InfoService::TUTORIAL_RIVAL:          // 対戦案内
                    Common::redirect('Swf', 'Main');

                // ガチャへ
                case User_InfoService::TUTORIAL_GACHA:          // ガチャ案内
                    Common::redirect('User', 'GachaList');
            }
        }else{
            // 以下のチュートリアルステップは別アクションに飛ばす。
            switch($this->userInfo['tutorial_step']) {
                // Mainアクションへ
                case User_InfoService::TUTORIAL_MAINMENU:       // メインメニュー案内
                case User_InfoService::TUTORIAL_FIELD:          // ファーストクエスト中
                case User_InfoService::TUTORIAL_STATUS:         // ステータス案内
                case User_InfoService::TUTORIAL_SHOPPING:       // ショップ案内
                case User_InfoService::TUTORIAL_RIVAL:          // 対戦案内
                case User_InfoService::TUTORIAL_GACHA:          // ガチャ案内
                case User_InfoService::TUTORIAL_EQUIP:          // 装備案内
                    Common::redirect('Swf', 'Main');
            }
        }
        // すでにチュートリアルが終わっているならメインへ。
        if($this->userInfo['tutorial_step'] >= User_InfoService::TUTORIAL_END)
            Common::redirect('Swf', 'Main');

        // ドラマIDの設定。
        $this->dramaId = constant('Drama_MasterService::TUTORIAL' . $this->userInfo['tutorial_step']);

        // 戻り先の設定。
        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_BATTLE){
            $this->endTo = Common::genContainerUrl('Swf', 'TutorialBattle', null, true);
        }else{
            $this->endTo = Common::genContainerUrl('Swf', 'Tutorial', array('end'=>$this->userInfo['tutorial_step']), true);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 完了通知を処理する。
     */
    private function processEnd() {

        $userSvc = new User_InfoService();

        // ショップ前の寸劇が完了したのなら、プレイヤーの所持金を増やす
        if(
               $this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_PRESHOP
            && $_GET['end'] == User_InfoService::TUTORIAL_PRESHOP
        ) {
            $userSvc->plusValue($this->user_id, array('gold'=>30));
        }

        // チュートリアルを次段階へ。
        $userSvc->tutorialStepUp($this->user_id, $_GET['end']);

        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_LAST){
            //ワクプラの場合でスタートダッシュキャンペーン中なら
            $dt = new DateTime();
            $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
            $currenttime = $dt->format('Y-m-d H:i:s');

            if(PLATFORM_TYPE == 'waku' && strtotime(STARTDUSH_CAMPAIGN_START_DATE) <= strtotime($currenttime) && strtotime(STARTDUSH_CAMPAIGN_END_DATE) > strtotime($currenttime)){
                Common::redirect('Swf', 'WakuStartDushCampain');
            }

$jizen_list =[
    101,
    3080,
    3365,
    3594,
    8782,
    10214,
    10598,
    20081,
    34189,
    34812,
    43997,
    45605,
    52287,
    53086,
    53308,
    54970,
    59962,
    67278,
    76518,
    79731,
    90030,
    94282,
    95196,
    121625,
    125314,
    128078,
    152758,
    153861,
    163784,
    167207,
    167290,
    193909,
    198369,
    210289,
    211740,
    218911,
    220104,
    248496,
    253399,
    258015,
    258324,
    276769,
    280027,
    280128,
    286098,
    298421,
    301561,
    306628,
    324623,
    330150,
    331667,
    351183,
    357781,
    367411,
    367992,
    373514,
    380510,
    385087,
    390046,
    392924,
    409500,
    412895,
    421274,
    423380,
    428510,
    429366,
    478639,
    486099,
    492832,
    493065,
    496649,
    509960,
    521187,
    545971,
    546734,
    551236,
    563665,
    563710,
    590604,
    597832,
    603376,
    610554,
    614214,
    619787,
    633608,
    640845,
    648229,
    670619,
    673303,
    680987,
    689147,
    697016,
    699203,
    699789,
    705418,
    706619,
    712337,
    720257,
    729496,
    730862,
    731977,
    735330,
    738081,
    739014,
    754254,
    757777,
    785023,
    786590,
    791846,
    794864,
    797604,
    806547,
    809146,
    818197,
    819031,
    823254,
    832176,
    839742,
    842839,
    859419,
    876103,
    898912,
    909509,
    910201,
    913850,
    928354,
    930789,
    952462,
    960212,
    971047,
    982008,
    985028,
    992497,
    992862,
    993562,
    996080,
    1007310,
    1012638,
    1015675,
    1021856,
    1029903,
    1030680,
    1048464,
    1058170,
    1058681,
    1088167,
    1088468,
    1101618,
    1113845,
    1115640,
    1123801,
    1124903,
    1133739,
    1133746,
    1139092,
    1142403,
    1146337,
    1158112,
    1159419,
    1166244,
    1176882,
    1182399,
    1188140,
    1202845,
    1205172,
    1205820,
    1214127,
    1222152,
    1231151,
    1236973,
    1238680,
    1241861,
    1248545,
    1252190,
    1271634,
    1281201,
    1298847,
    1304656,
    1307785,
    1311439,
    1315822,
    1320757,
    1320770,
    1330495,
    1333748,
    1341177,
    1347236,
    1352883,
    1364221,
    1368087,
    1372378,
    1374364,
    1385330,
    1396919,
    1414643,
    1424332,
    1427193,
    1435406,
    1437560,
    1441545,
    1442350,
    1446974,
    1455237,
    1465233,
    1499718,
    1506192,
    1520292,
    1528690,
    1536936,
    1547468,
    1554065,
    1561745,
    1564828,
    1567858,
    1575179,
    1577279,
    1592699,
    1603471,
    1610540,
    1616863,
    1619128,
    1636497,
    1637642,
    1638700,
    1645654,
    1659510,
    1661352,
    1669358,
    1670525,
    1677045,
    1678695,
    1685378,
    1688214,
    1690174,
    1697935,
    1699036,
    1699104,
    1703372,
    1703929,
    1704973,
    1706901,
    1707261,
    1710004,
    1711918,
    1712726,
    1717266,
    1719910,
    1732346,
    1732592,
    1735267,
    1738111,
    1748240,
    1750070,
    1750874,
    1751205,
    1759141,
    1763220,
    1763849,
    1767330,
    1771281,
    1773538,
    1774309,
    1775607,
    1777563,
    1777738,
    1778948,
    1781919,
    1788051,
    1789118,
    1790130,
    1795968,
    1802111,
    1808649,
    1809174,
    1815528,
    1825672,
    1826895,
    1834002,
    1835497,
    1839173,
    1840009,
    1841360,
    1846489,
    1850076,
    1853254,
    1859204,
    1866580,
    1867116,
    1867280,
    1872514,
    1874354,
    1874807,
    1879136,
    1879320,
    1881523,
    1885124,
    1885285,
    1886644,
    1886769,
    1886858,
    1886867,
    1888039,
    1890293,
    1892911,
    1895302,
    1895633,
    1896100,
    1897586,
    1897669,
    1898916,
    1899417,
    1901062,
    1901489,
    1901988,
    1902951,
    1903231,
    1903422,
    1903558,
    1903759,
    1904844,
    1905073,
    1905610,
    1906113,
    1906897,
    1907443,
    1907509,
    1907884,
    1908518,
    1909126,
    1909260,
    1909790,
    1909801
    ];

            //事前登録
            if(PLATFORM_TYPE == 'niji' && in_array($this->user_id, $jizen_list)){
                // アイテム付与。
                $svc = new User_ItemService();
                $uitemId = $svc->gainItem($this->user_id, 1902, 1);//ニワトリ
                $uitemId = $svc->gainItem($this->user_id, 1905, 1);//マルティーニの槌
                $uitemId = $svc->gainItem($this->user_id, 11004, 1);//グラディウス
                Common::varLog("にじよめ事前登録 付与完了 user_id=" . $this->user_id);
            }

            if(strtotime(STARTDUSH_CAMPAIGN_START_DATE) <= strtotime($currenttime) && strtotime(STARTDUSH_CAMPAIGN_END_DATE) > strtotime($currenttime)){
                Common::redirect('Swf', 'WakuStartDushCampain');
            }
        }

        // ココに来る場合、次は必ずメインメニューに行くことになっているので、そちらへ飛ばす。
        Common::redirect('Swf', 'Main');
    }
}
