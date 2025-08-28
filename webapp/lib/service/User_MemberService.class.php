<?php

class User_MemberService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが仲間かどうかを返す。
     */
    public function isMember($ownerId, $targetId) {

        return $this->createDao(true)->exists( $this->pkStruct($ownerId, $targetId) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザから指定のユーザへプレゼントが可能かどうかを調べる。
     *
     * @param int       プレゼントする側のユーザID
     * @param int       プレゼントされる側のユーザID
     * @return string   以下のコードのいずれか
     *                      self_present    自分にプレゼントしようとしている
     *                  プレゼント可能な場合カラ文字列
     */
    public function canPresent($userId, $companionId) {

        // 自分にプレゼントしようとしていないかチェック。
        if($userId == $companionId)
            return 'self_present';

        // ここまでくればOK。
        return '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの仲間の人数を返す。
     *
     * @param int       ユーザID。配列での複数指定も可能。
     * @return int      既に仲間になっている人数。
     *                  ユーザIDを配列で指定している場合は、ユーザIDをキー、仲間数を値とする配列。
     */
    public function getMemberCount($userId) {

        if( is_array($userId) ) {

            $sql = '
                SELECT user_id
                     , COUNT(*) AS count
                FROM user_member
                WHERE user_id ' . DataAccessObject::buildRightSide($userId, $sqlParams) . '
                GROUP BY user_id
            ';

            $resultset = $this->createDao(true)->getAll($sql, $sqlParams);

            return ResultsetUtil::colValues($resultset, 'count', 'user_id');

        }else {
            return $this->countRecord(array('user_id'=>$userId));
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの仲間人数に関する様々な情報を返す。
     *
     * @param int       ユーザID
     * @return array    以下のキーを持つ配列。
     *                      current     既に仲間になっている人数
     *                      request     申請を出している人数
     *                      receive     申請を受けている人数
     *                      total       current + request + receive の数
     *                      limit       ユーザ数上限
     */
    public function getMemberInfo($userId) {

        static $cache = array();

        // キャッシュにあるならキャッシュから返す。
        if(array_key_exists($userId, $cache))
            return $cache[$userId];

        // 戻り値初期化。
        $result = array();

        // 既に仲間になっている人数。
        $result['current'] = $this->getMemberCount($userId);

        // 申請がらみの人数
        $approach = Service::create('Approach_Log')->getUnansweredCount($userId);
        $result['request'] = $approach['request'];
        $result['receive'] = $approach['receive'];

        // "total" の値。
        $result['total'] = $result['current'] + $result['request'] + $result['receive'];

        // 上限人数。
        $result['limit'] = Service::create('Level_Master')->getMemberLimit($userId);

        // キャッシュに保存してリターン。
        $cache[$userId] = $result;
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの仲間をページ分けして取得する。
     *
     * @param int       ユーザID
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    DataAccessObject::getPage と同様。
     *                  ただし、結果セットは user_info に、"last_cooperate_at" を追加したもの。
     */
    public function getMemberList($userId, $showOnPage, $page) {

        // とりあえず、user_member をページ分けして取得。
        $condition = array(
            'user_id' => $userId,
            'ORDER BY' => 'create_at DESC',
        );
        $list = $this->selectPage($condition, $showOnPage, $page);

        // 該当ページ部分のユーザレコードを取得。
        $userSvc = new User_InfoService();
        $users = $userSvc->getRecordsIn(ResultsetUtil::colValues($list['resultset'], 'member_id'));

        // ユーザレコードの結果セットに、"last_cooperate_at" を追加したものを変数 $resultset に得る。
        $resultset = array();
        foreach($list['resultset'] as $member) {
            $record = $users[ $member['member_id'] ];
            $record['last_cooperate_at'] = $member['last_cooperate_at'];
            $resultset[] = $record;
        }

        // リストの結果セットを差し替えてリターン。
        $list['resultset'] = $resultset;
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの仲間のIDのみを配列で返す。
     *
     * @param int       ユーザID
     * @return array    仲間のIDの配列。
     */
    public function getMemberIds($userId) {

        $sql = '
            SELECT member_id
            FROM user_member
            WHERE user_id = ?
        ';

        return $this->createDao(true)->getCol($sql, $userId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの仲間候補を取得する。
     *
     * @param int       ユーザID
     * @return array    仲間候補
     */
    public function getCandidateList($userId) {

        // 仲間候補のID一覧を取得するSQL作成。
        $sql = '
            SELECT user_info.user_id
            FROM user_info
            WHERE user_info.user_id > 0
            ORDER BY user_info.last_access_date DESC
            LIMIT 200
        ';

        // SQL実行して候補者取得。
        $candidates = $this->createDao(true)->getAll($sql);

        // 現在の仲間のID一覧を取得。
        $memberIds = $this->getMemberIds($userId);

        // 現在申請を出しているID一覧を取得。
        $recipientIds = Service::create('Approach_Log')->getApproacherIds($userId);

        // 候補者一覧の中からランダムに10件取得。
        $resultIds = array();
        shuffle($candidates);
        foreach($candidates as $candidate) {

            // 自分は除く。
            if($candidate['user_id'] == $userId)
                continue;

            // すでに仲間なのを除く。
            if(in_array($candidate['user_id'], $memberIds))
                continue;

            // すでに申請を出しているものを除く。
            if(in_array($candidate['user_id'], $recipientIds))
                continue;

            // 候補者の仲間情報を取得。
            $memberInfo = $this->getMemberInfo($candidate['user_id']);

            // すでに上限なら除く
            if($memberInfo['total'] >= $memberInfo['limit'])
                continue;

            // 未承認申請を10以上溜めているものは除く。
            if($memberInfo['receive'] >= 10)
                continue;

            // ここまで来ればOK。候補者のIDを追加。規定件数になったらループを抜ける。
            $resultIds[] = $candidate['user_id'];
            if(count($resultIds) > 10)
                break;
        }

        // IDからレコードを取得してリターン。
        $userSvc = new User_InfoService();
        return $userSvc->getRecordsIn($resultIds, false);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザーが仲間を増やせる状態にあるかどうかを調べる。
     *
     * @param int       ユーザID
     * @return bool     まだ仲間を増やせるならtrue、増やせないならfalse。
     */
    public function canIncreaseMember($userId) {

        $memberInfo = $this->getMemberInfo($userId);

        return ($memberInfo['total'] < $memberInfo['limit']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 仲間がいるすべてのユーザの仲間数を返す。
     *
     * @return array    ユーザIDをキー、仲間数を値とする配列。仲間がいないユーザは返されないことに注意。
     */
    public function sumupMembers() {

        $sql = '
            SELECT user_id
                 , COUNT(*) AS count
            FROM user_member
            GROUP BY user_id
        ';

        $resultset = $this->createDao(true)->getAll($sql);

        return ResultsetUtil::colValues($resultset, 'count', 'user_id');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * user1とuser2をお互いに仲間にする
     */
    public function makeFriend($user1Id, $user2Id) {

        // 自分を仲間にしようとしてないかエラーチェック。
        if($user1Id == $user2Id)
            throw new MojaviException('自分を仲間にしようとした');

        $dao = $this->createDao();

        $record = array(
            'user_id' => $user1Id,
            'member_id' => $user2Id,
        );
        $dao->insert($record, false, true);

        $record = array(
            'user_id' => $user2Id,
            'member_id' => $user1Id,
        );
        $dao->insert($record, false, true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * user1とuser2の仲間関係を解除する
     */
    public function dissolveFriend($user1Id, $user2Id) {

        $dao = $this->createDao();

        $record = array(
            'user_id' => $user1Id,
            'member_id' => $user2Id,
        );
        $dao->delete($record);

        $record = array(
            'user_id' => $user2Id,
            'member_id' => $user1Id,
        );
        $dao->delete($record);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたレコードの last_cooperate_at を更新する。
     */
    public function markCooperation($userId, $memberId) {

        $this->updateRecord(array($userId, $memberId), array(
            'last_cooperate_at' => array('sql'=>'NOW()'),
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('user_id', 'member_id');
}
