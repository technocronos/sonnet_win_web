<?php

class Text_MasterService extends Service {

    public static $CATEGORY = array(
        'word' => array("title"=>'重要単語', 'explain'=>'世界観を決める単語。') ,
        'system' => array("title"=>'システム', 'explain'=>'システム文言。') ,
        'tutorial' => array("title"=>'チュートリアル', 'explain'=>'概要：チュートリアル用文言。もじょが主にしゃべる形で展開される。タップで文言が次に切り替わる仕様。1段目→タップ→2段目のようにセリフの切り替わりは改行で表現されている。文字数不足等あればセリフを分けて対応されたい。<br>セリフ内の改行は「\n」で可能だが自動で改行される。') ,
        'home' => array("title"=>'ホーム') ,
        'drama' => array("title"=>'ドラマ', 'explain'=>'ドラマ文言。シンボルを「DRAMA_CODE」で絞り込むと人名、「drama_master_flow_」で絞り込むとシナリオテキストになる。<br>「!XSPEAKER」や「!PAGE」のようにシナリオコマンドと一体になっている。コマンドは絶対に崩さないこと。<br>確認方法：<a href="https://test.native.sonnet.crns-game.net/?module=Admin&action=ShowDrama" target=_blank>寸劇表示</a>ツールから確認可能。ドラマIDはdrama_master_flow_の後の数字。') ,
        'item' => array("title"=>'アイテム', 'explain'=>'アイテム文言。シンボルを「item_name」で絞り込むとアイテム名、「flavor_text」で絞り込むとテキストになる。<br>文字制限:アイテム名は15文字、フレーバーテキストは62文字') ,
        'set' => array("title"=>'装備セット','explain'=>'装備セット文言。シンボルを「set_name」で絞り込むとセット名、「set_text」で絞り込むとテキストになる。<br>文字制限:アイテム名は15文字、フレーバーテキストは62文字') ,
        'gacha' => array("title"=>'ガチャ', 'explain'=>'ガチャ。「gacha_name」ガチャ名。「flavor_text」ガチャの説明。') ,
        'battle' => array("title"=>'バトル', 'explain'=>'バトル文言。<br>文字数制限：44文字') ,
        'grade' => array("title"=>'階級', 'explain'=>'階級名。極端に長くならなければ文字制限特になし。') ,
        'dtech' => array("title"=>'必殺技', 'explain'=>'必殺技文言。「dtech_name」必殺技名。「dtech_desc」必殺技フレーバーテキスト。<br>文字制限:必殺技名は20文字、フレーバーテキストは44文字') ,
        'help' => array("title"=>'ヘルプ', 'explain'=>'ヘルプ文言。「HELP_GROUP」ヘルプのグループ「help_title」ヘルプタイトル。「help_body」ヘルプ本文。<br>文字制限は特になし。タグも含まれるがそのままで構造を崩さないように注意。') ,
        'monster' => array("title"=>'モンスター', 'explain'=>'モンスター文言。「text_log_body_」モンスター名。「monster_master_flavor_text_」フレーバーテキスト。<br>正直文字数がかなり厳しいと思うのでUI変更含めて要相談。') ,
        'place' => array("title"=>'場所', 'explain'=>'地名。<br>文字制限：12文字。<br>正直文字数がかなり厳しいと思うのでUI変更含めて要相談。') ,
        'quest' => array("title"=>'クエスト', 'explain'=>'クエスト文言。') ,
        'sphere' => array("title"=>'スフィア', 'explain'=>'クエストのシナリオコマンド。sphere_クエストIDとなっているのでクエストIDでシンボルを絞り込んで作業されたい。<br>「LINES %avatar%」のようにシナリオコマンドと一体になっている。コマンドは絶対に崩さないこと。<br>SPEAKコマンドは「SPEAK %avatar% 師匠 では始めるとよい」のようになっている。師匠の所がスピーカー、「では始めるとよい」の所がシナリオ内容となる。<br>LINESやSPEAKは104文字だがNOTIFは例外的に75文字まで。') ,
        'raid' => array("title"=>'レイドダンジョン', 'explain'=>'レイドダンジョン関連文言。') ,
        'vcoin' => array("title"=>'仮装通貨', 'explain'=>'仮装通貨関連文言。文字数特に制限なし。') ,
        'error' => array("title"=>'エラー', 'explain'=>'APIエラー表示。メッセージボックスで表示される。<br>改行は「\n」で可能だが自動で改行される。') ,
        'promo' => array("title"=>'プロモーション', 'explain'=>'プロモーション関連文言') ,
        'notice' => array("title"=>'お知らせ', 'explain'=>'お知らせ関連文言') ,
    );

    //-----------------------------------------------------------------------------------------------------
    /**
     * 全レコードを返す
     *
     */
    public function getAll() {

        $sql = '
            SELECT *
            FROM text_master
        ';

        return $this->createDao(true)->getAll($sql);
    }

    public function getSymbol($symbol) {

        $sql = '
            SELECT *
            FROM text_master
            WHERE symbol = "'.$symbol.'"
        ';

        return $this->createDao(true)->getRow($sql);
    }

    public function findRecords($condition, $numOnPage, $page) {

        $dao = $this->createDao(true);

        // 固定の条件を作成。システムユーザは除外する。
        $where = array();
        $where['ORDER BY'] = 'text_master.sort';

        $category = null;

        foreach(Text_MasterService::$CATEGORY as $key=>$value){
            if($_GET[$key] != ""){
                $category[] = $key;
            }
        }


        // 指定された条件を組み込む。
        if(strlen($condition['symbol']))  $where['text_master.symbol'] = array('sql'=>'LIKE ?', 'value'=>'%'.DataAccessObject::escapeLikeLiteral($condition['symbol']).'%');
        if(strlen($condition['ja']))  $where['text_master.ja'] = array('sql'=>'LIKE ?', 'value'=>'%'.DataAccessObject::escapeLikeLiteral($condition['ja']).'%');
        if(strlen($condition['en']))  $where['text_master.en'] = array('sql'=>'LIKE ?', 'value'=>'%'.DataAccessObject::escapeLikeLiteral($condition['en']).'%');
        if($category != null) {
            foreach($category as $c){
                $holder[] = "?";
            }
            $where['text_master.category'] = array('sql'=>'IN (' . implode(',', $holder) . ')', 'value'=>$category);
        }

        if(strlen($condition['progress']) || $condition['progress'] != 0){
            if($condition['progress'] == 1){
                $where['NULLIF(text_master.en,\'\')'] = array('sql'=>'IS NULL');
            }else if($condition['progress'] == 2){
                $where['text_master.en'] = array('sql'=>'!= ""');
            }
        }  

        return $this->selectPage($where, $numOnPage, $page);
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'text_id';

    protected $isMaster = true;
}
