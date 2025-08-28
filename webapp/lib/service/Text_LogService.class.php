<?php

class Text_LogService extends Service {

    // inspection_status 列の値の意味。
    const STATUS_OK = 0;
    const STATUS_NG = 1;

    // 監査NGの場合の代替文字列。
    const NG_TEXT = '(削除)';

    // プラットフォームからの返答をキャッシュする時間(分)
    const TEXT_CACHE_MINUTES = 60;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたIDの監査済みテキストを取得する。
     * 監査NGの場合は代替文字列を返す。
     *
     * @param int       text_log.text_id の値。
     * @return string   監査済みのテキスト。
     */
    public function getText($textId) {

        // nullが指定されているならカラ文字列を返す。
        if(is_null($textId))
            return '';

        // あとは getTextsIn で処理する。
        $result = $this->getTextsIn(array($textId));
        return $result[$textId];
    }

    /**
     * getText()を静的にコールできるようにしたショートカットメソッド
     */
    public static function get($textId) {
        return self::create('Text_Log')->getText($textId);
    }

    /**
     * text_masterがあるのであればそちらで取得する
     */
    public function getBody($text_log, $name_id) {

        $name = AppUtil::getText("text_log_body_" . $name_id);

        if($name == "")
            $name = $text_log["body"];

        return $name;
    }

    /**
     * getRecordをオーバーライドして多言語対応
     */
    public function getRecord(/* 可変引数 */) {
        $args = func_get_args();
        $record = parent::getRecord($args[0]);

        $name = AppUtil::getText("text_log_body_" . $record["text_id"]);

        if($name == "")
            $name = $record["body"];

        $record["body"] = $name;

        return $record;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された複数のIDの監査済みテキストを取得する。
     * 監査NGの場合は代替文字列を返す。
     *
     * @param array     text_log.text_id の値を列挙した配列。
     * @return array    text_log.text_id の値をキーに、監査済みのテキストを値とする配列。
     */
    public function getTextsIn($textIds) {

        // 指定されたレコードを取得。
        $records = $this->inspectRecordsIn($textIds);

        // 戻り値初期化。
        $result = array();

        // 指定されたIDとそのレコードを一つずつ見て、返すべきテキストを戻り値に格納していく。
        foreach($records as $id => $record) {

            // レコードがないならカラ文字列とする。
            if(!$record){
                $body = '';

            // プラットフォームから監査NGとされているならNG用テキストにする。
            }else if($record['inspection_status'] == self::STATUS_NG){
                $body = self::NG_TEXT;

            // OKならbody列を取得する。
            }else{
                $body = AppUtil::getText("text_log_body_" . $record["text_id"]);

                if($body == "")
                    $body = $record["body"];
            }
            // 戻り値に格納。
            $result[$id] = $body;
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された条件でテキストをページ分けして取得する。
     *
     * @param array     次のキーを持つ配列
     *                      type    必須。type列の値。
     * @param int       1ページの件数
     * @param int       取得するページ番号
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getList($condition, $numOnPage, $page = 0) {

        $condition['ORDER BY'] = 'create_at DESC';
        return $this->selectPage($condition, $numOnPage, $page);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザーIDで検索する
     *
     * @param writer_id     ユーザーID。
     */
    public function getWriter($writer_id) {

        // SQL作成＆実行
        $sql = "
            SELECT *
            FROM text_log
            WHERE writer_id = ? and type = 'CHR'
            ORDER BY create_at DESC LIMIT 1
        ";

        return $this->createDao(true)->getAll($sql, array($writer_id));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザーIDで検索する
     *
     * @param writer_id     ユーザーID。
     */
    public function getWriters($writer_id) {

        // SQL作成＆実行
        $sql = "
            SELECT *
            FROM text_log
            WHERE writer_id = ? and type = 'CHR'
            ORDER BY create_at DESC
        ";

        return $this->createDao(true)->getAll($sql, array($writer_id));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された種別で、テキストを保存する。
     *
     * @param string    テキスト種別。text_log.type の値。
     * @param string    テキスト本体。
     * @param int       テキストを書いたユーザのID。
     * @param int       テキストの宛て先になっているユーザID。宛て先がない場合はnull。
     * @return int      INSERTしたレコードのtext_id
     */
    public function postText($type, $body, $writerId, $toId = null) {

        // プラットフォームにテキスト送信。監査IDを得る。
        $inspectId = $this->sendText($type, $body, $writerId, $toId);

        // INSERT。
        $record = array(
            'type' => $type,
            'body' => $body,
            'writer_id' => $writerId,
            'inspection_id' => $inspectId,
            'check_date' => array('sql'=>'NOW()'),
        );

        return $this->insertRecord($record, true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたIDのテキストを更新する。
     *
     * @param int       テキストID
     * @param string    テキスト本体。
     * @param int       テキストの宛て先になっているユーザID。宛て先がない場合はnull。
     */
    public function updateText($textId, $body, $toId = null) {

        // 現在の状態を取得。
        $current = $this->needRecord($textId);

        // 監査IDを得ているなら、プラットフォームに削除要請を出しておく。
        if($current['inspection_id'])
            PlatformApi::deleteText($current['inspection_id']);

        // 更新。
        $update = array();
        $update['body'] = $body;
        $update['inspection_id'] = $this->sendText($current['type'], $body, $current['writer_id'], $toId);
        $update['inspection_status'] = 0;
        $update['check_date'] = array('sql'=>'NOW()');
        $update['create_at'] = array('sql'=>'NOW()');

        $this->updateRecord($textId, $update);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された複数のレコードを削除する。
     *
     * @param array     text_idの配列。
     */
    public function deleteRecordsIn($textIds) {

        // NULL値を除外する。
        foreach($textIds as $index => $value) {
            if(is_null($value))
                unset($textIds[$index]);
        }

        // 全部NULLだったらここでリターン。
        if(!$textIds)
            return;

        // 削除対象のレコードから、監査IDをすべて取得。
        $sql = '
            SELECT inspection_id
            FROM text_log
            WHERE text_id ' . DataAccessObject::buildRightSide($textIds, $sqlParams) . '
              AND LENGTH(inspection_id) > 0
        ';

        $inspectionIds = $this->createDao(true)->getCol($sql, $sqlParams);

        // プラットフォームに削除要請を出す。
        if($inspectionIds)
            PlatformApi::deleteText($inspectionIds);

        // レコードを削除。
        $this->createDao()->delete(array(
            'text_id' => $textIds,
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'text_id';


    //-----------------------------------------------------------------------------------------------------
    /**
     * deleteRecord()をオーバーライド。deleteRecordsIn() に転送する。
     */
    public function deleteRecord(/* 可変引数 */) {

        $args = func_get_args();
        $this->deleteRecordsIn( $args );
    }


    // privateメンバ。
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたテキストをプラットフォームに提出して、監査IDを得る。
     * 引数リストは postText と同一。
     */
    private function sendText($type, $body, $writerId, $toId) {

        // 内容がカラ文字、あるいは監査の必要がない場合は監査テキストとして提出しない。
        if($body == ''  ||  (PLATFORM_TYPE == 'mbga'  &&  $type == 'CHR'))
            return '';

        // プラットフォームに送信。
        return PlatformApi::postText($body, $writerId, $toId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのレコードを getRecordsIn と同じ要領で取得。
     * ただし、監査状態のチェックを行い、更新されている場合はレコードに反映する。
     */
    private function inspectRecordsIn($textIds) {

        // とりあえずレコードを取得。
        $resultset = $this->getRecordsIn($textIds);

        // 監査状態をチェックする必要があるレコードの監査IDを配列 $inspectIds に列挙する。
        $inspectIds = array();
        foreach($resultset as $record) {

            if(!$record)
                continue;

            // 監査の必要がない、あるいは、プラットフォームから一度でも監査NGとされているなら
            // チェックしない。
            if(!$record['inspection_id']  ||  $record['inspection_status'] == self::STATUS_NG)
                continue;

            // 入力日時と監査状態チェック日時をタイムスタンプに直す。
            $record['check_date'] = strtotime($record['check_date']);
            $record['create_at'] = strtotime($record['create_at']);

            // 本番の場合に、入力日時から十分に期間を置いて監査チェックされているならもうチェックしない。
            if(USE_PLATFORM_CACHE  &&  $record['create_at'] + 7*24*60*60 <= $record['check_date'])
                continue;

            // 本番の場合に、前回の監査チェック日時からまだほとんど経過していないならパスする。
            $cacheSeconds = ((PLATFORM_TYPE == 'mbga') ? 0 : TEXT_CACHE_MINUTES) * 60;  // モバゲが「反映されてない」とかクレーム入れてきたので、モバゲだけキャッシュ０にする。生意気言うならAPIサーバのレス速度改善してほしい。
            if(USE_PLATFORM_CACHE  &&  time() < $record['check_date'] + $cacheSeconds)
                continue;

            // ここまで来たら、しょうがないからチェックする。
            $inspectIds[] = $record['inspection_id'];
        }

        // チェックする必要があるものが一つもないなら、ここでリターン。
        if(!$inspectIds)
            return $resultset;

        // プラットフォームから監査済みテキストを取得。
        $censoredTexts = PlatformApi::getText($inspectIds);

        // 一つずつ見ていく。
        foreach($inspectIds as $inspectId) {

            // 該当するレコードを参照として変数 $record に取得。
            foreach($resultset as &$record) {
                if($record['inspection_id'] == $inspectId)
                    break;
            }

            // 監査結果を取得。エラーで結果を取得できていない場合は監査OKと同じ扱いにする。
            $text = is_null($censoredTexts) ? $record['body'] : $censoredTexts[$inspectId];

            // レコード更新準備。
            $update = array();

            // check_dateを書き換え。
            $update['check_date'] = date('Y/m/d H:i:s');

            // 監査NGならinspection_statusも書き換え。
            if(strlen($text) == 0)
                $update['inspection_status'] = self::STATUS_NG;

            // 監査OKだが、テキストがプラットフォームによって修正されている場合は body 列も更新する。
            else if($record['body'] != $text)
                $update['body'] = $text;

            // レコードUPDATE。
            $this->updateRecord($record['text_id'], $update);

            // 取得したレコードにも反映する。
            $record = array_merge($record, $update);
        }

        return $resultset;
    }
}
