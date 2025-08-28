<?php

/**
 * チーム対戦についてのユーティリティ
 */
Class TeamBattleUtil {

    // チーム対戦チケットのアイテムID
    const TICKET_ID = 99002;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定したキャラ同士がチームバトルできるかどうかを返す。
     * システム的におかしい引数の場合は例外を投げる。
     *
     * @param int       挑戦側キャラクタID
     * @param int       防衛側キャラクタID
     * @return string   調査結果を表すコード。以下のいずれか。
     *                      ok              対戦可能
     *                      sphere          フィールドクエスト進行中である
     */
    public static function canBattle($challengerId, $defenderId) {

        $memberSvc = new User_MemberService();

        // 自分と対決しようとしているならエラー。
        if($challengerId == $defenderId)
            throw new MojaviException('自分と対戦しようとした。');

        // 双方の情報を取得。
        $svc = new Character_InfoService();
        $challenger = $svc->needRecord($challengerId);
        $defender =   $svc->needRecord($defenderId);

        // 双方の所属ユーザが同じであればエラー。
        if($challenger['user_id'] == $defender['user_id'])
            throw new MojaviException('同じ所属のキャラ同士で対戦しようとした。');

        // システムユーザとは対戦できない。
        if($challenger['user_id'] < 0  ||  $defender['user_id'] < 0)
            throw new MojaviException('システムユーザのキャラで対戦しようとした。');

        // 挑戦側のスフィア出撃チェック。
        if($challenger['sally_sphere'])
            return 'sphere';

        // 防衛側のユーザに仲間がいない。
        if($memberSvc->getMemberCount($defender['user_id']) == 0)
            return 'member-rival';

        // 挑戦側のユーザに仲間が二人以上いない。
        if($memberSvc->getMemberCount($challenger['user_id']) < 2)
            return 'member';

        // 挑戦側のユーザが対戦チケットを持っていない
        if(Service::create('User_Item')->getHoldCount($challenger['user_id'], self::TICKET_ID) == 0)
            return 'ticket';

        // ここまで来ればOK
        return 'ok';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 対戦相手キャラクターの抽出を行い、その一覧を返す。
     *
     * @param int       何件抽出するか。
     * @return array    対戦相手キャラクターのレコードを列挙した配列。
     */
    public function getRivalList($findCount) {

        $charaSvc = new Character_InfoService();
        $cacheSvc = new Cache_InfoService();

        // 各ユーザの仲間人数のキャッシュ日時を取得。
        $cacheTime = $cacheSvc->getValue(Cache_InfoService::SOCIALITY, -1);

        // 3時間以上前のキャッシュなら更新する。キャッシュがなかった場合もこの条件式が成り立つことに留意。
        if( (int)$cacheTime <= time() - 3*60*60 ) {

            // いったんすべてクリア
            $cacheSvc->clearGroup(Cache_InfoService::SOCIALITY);

            // 仲間がいるすべてのユーザの仲間数をカウント
            $memberCounts = Service::create('User_Member')->sumupMembers();

            // 仲間数が規定数を超えているものに絞る。
            foreach($memberCounts as $index => $count) {
                if($count < 2)
                    unset($memberCounts[$index]);
            }

            // インデックス-1に現在日時をいれて、キャッシュに挿入。
            $memberCounts[-1] = time();
            $cacheSvc->insertGroup(Cache_InfoService::SOCIALITY, $memberCounts);
        }

        // キャッシュにあるレコード数を取得。ただし、そのうちの一つはキャッシュ日時なので、カウントしない。
        $count = $cacheSvc->countEntries(Cache_InfoService::SOCIALITY) - 1;

        // キャッシュから取得するときのオフセットをランダムに決定。
        $offset = (0 < $count - $findCount) ? mt_rand(0, $count - $findCount) : 0;

        // 決定したオフセットからレコード取得。
        $userIds = $cacheSvc->getGroup(Cache_InfoService::SOCIALITY, array(
            'LIMIT' => $findCount,
            'OFFSET' => $offset + 1,
            'ORDER BY' => 'data_id'
        ));

        // そのユーザのキャラクターを取得。
        $result = array();
        foreach(array_keys($userIds) as $userId)
            $result[] = $charaSvc->needAvatar($userId, true);

        // リターン。
        return $result;
    }
}
