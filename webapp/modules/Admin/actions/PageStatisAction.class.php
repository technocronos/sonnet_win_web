<?php


class PageStatisAction extends AdminBaseAction {

    // 棒グラフのスケール
    const GRAPH_SCALE = 5000;

    // ページ名
    private static $TITLES = array(
        'action=Approach&module=User' => '仲間申請',
        'action=ApproachList&module=User' => '仲間申請一覧',
        'action=AvatarCreate&module=User' => '初期名前登録',
        'action=Battle&module=Swf' => 'バトルFLASH',
        'action=BattleConfirm&module=User' => '対戦確認',
        'action=BattleHistory&module=User' => '対戦履歴',
        'action=BattleResult&module=User' => 'バトル結果',
        'action=Comment&module=User' => 'つぶやき入力',
        'action=CommentTree&module=User' => '履歴ツリー',
        'action=Detain&module=Swf' => '退会FLASH',
        'action=DiaryList&module=User' => '開発日誌一覧',
        'action=Discard&module=User' => 'アイテム破棄',
        'action=Distribution&module=User' => 'アイテム配布',
        'action=Equip&module=User' => '装備(箇所選択)',
        'action=EquipChange&module=User' => '装備変更',
        'action=FieldDrama&module=Swf' => 'ドラマFLASH(フィールド)',
        'action=FieldEnd&module=User' => 'クエスト終了',
        'action=FieldReady&module=User' => 'クエスト準備',
        'action=FieldReopen&module=User' => 'クエスト再開／ギブアップ',
        'action=GachaDetail&module=User' => 'ガチャ詳細',
        'action=GachaFree&module=User' => '無料ガチャ',
        'action=GachaList&module=User' => 'ガチャ一覧',
        'action=GradeDetail&module=User' => '階級詳細',
        'action=GradeList&module=User' => '階級一覧',
        'action=Help&module=User' => 'ヘルプ',
        'action=HisPage&module=User' => '他者のページ',
        'action=HistoryList&module=User' => '履歴',
        'action=HistoryTouch&module=User' => '履歴削除／称賛',
        'action=Index&module=User' => 'トップページ',
        'action=Information&module=User' => 'インフォメーション',
        'action=ItemGet&module=User' => 'アイテムゲット',
        'action=ItemList&module=User' => 'アイテム確認',
        'action=ItemUseFire&module=User' => 'アイテム使用',
        'action=ItemUseSelect&module=User' => 'アイテム使用選択',
        'action=Main&module=Swf' => 'メニューFLASH',
        'action=MemberList&module=User' => '仲間一覧',
        'action=MemberSearch&module=User' => '仲間を探す',
        'action=Message&module=User' => 'メッセージ送信',
        'action=MessageList&module=User' => 'メッセージ一覧',
        'action=MonsterDetail&module=User' => 'モンスター詳細',
        'action=MonsterList&module=User' => 'モンスター一覧',
        'action=MonsterTop&module=User' => 'モンスター図鑑トップ',
        'action=Move&module=Swf' => '移動FLASH',
        'action=NameChange&module=User' => '名前変更',
        'action=OshiraseDetail&module=User' => 'お知らせ・日誌詳細',
        'action=OshiraseList&module=User' => 'お知らせ一覧',
        'action=ParamUp&module=User' => '振り分け',
        'action=PlatformArticle&module=User' => '日記完了',
        'action=Present&module=User' => 'プレゼント',
        'action=Prologue&module=Swf' => 'プロローグFLASH',
        'action=QuestDrama&module=Swf' => 'ドラマFLASH(クエスト)',
        'action=QuestList&module=User' => 'クエスト一覧',
        'action=Ranking&module=User' => 'バトルランキング',
        'action=Ready&module=Swf' => 'クエスト準備',
        'action=RivalList&module=User' => '対戦相手一覧',
        'action=Shop&module=User' => 'ショップ',
        'action=Sphere&module=Swf' => 'スフィアロード',
        'action=SphereCommand&module=Swf' => 'スフィアコマンド送信',
        'action=Static&module=User' => 'その他静的ページ',
        'action=Status&module=User' => 'ステータス',
        'action=Suggest&module=User' => '課金誘導',
        'action=TimeLine&module=User' => '履歴タイムライン',
        'action=Tutorial&module=Swf' => 'チュートリアルFLASH',
        'action=TutorialBattle&module=Swf' => 'チュートリアルバトル',
    );


    public function execute() {

        // デフォルト値の設定。
        if(strlen($_GET['from']) == 0  &&  strlen($_GET['to']) == 0) {
            $_GET['from'] = date('Y/m/d', strtotime('-7day'));
            $_GET['to'] = '';
        }

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'from' => 'datetime',
            'to' => array('ifempty'=>date('Y/m/d'), 'dateend'),
            '_form' => array(
                array('lowerupper' => array('from', 'to')),
                'interval' => array('dateinterval' => array('from', 'to', '30day')),
            ),
        ));
        $this->setAttribute('validator', $validator);

        // 入力値の検査。
        $validator->validate($_GET);

        // エラーがあるならココまで。
        if($validator->isError())
            return View::SUCCESS;

        // fromが省略されている場合はtoの2週間前とする。
        if(!$validator->values['from']) $validator->values['from'] = DateTimeUtil::add('-2week', $validator->values['to'], 'Y/m/d H:i:s');

        // アクセス数を集計してビュー用に割り当てる。
        $this->sumup($validator->values['from'], $validator->values['to']);

        // その他ビュー用割り当て。
        $this->setAttribute('pageTitles', self::$TITLES);
        $this->setAttribute('scale', self::GRAPH_SCALE);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された期間の売り上げを集計して、ビューに割り当てる。
     *
     * @param string    期間開始の日時
     * @param string    期間終了の日時
     */
    private function sumup($from, $to) {

        $data = Service::create('Page_Statistics')->sumupByDate($from, $to);
        $this->setAttribute('data', $data);

        // 範囲内の日付をすべて抽出。
        $fromTime = strtotime($from);
        $toTime = strtotime($to);
        $cols = array();
        for($time = $fromTime ; $time < $toTime ; $time = strtotime('+1day', $time))
            $cols[] = date('Y-m-d', $time);

        $cols = array_combine($cols, $cols);
        $this->setAttribute('cols', $cols);
    }
}
