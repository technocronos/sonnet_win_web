<?php

class User_PropertyService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * ログインカウンタの更新を管理を行う。カウントアップするべきタイミングで呼ぶ。
     *
     * @param int       ユーザID
     * @return array    次のキーを持つ配列。
     *                      tick    ログインカウントを更新したかどうか。
     *                      count   更新後のuser_info.login_day_count
     */
    public function tickLoginCount($userId) {

        // 指定されたユーザの "login_bonus_date", "login_day_count" を取得。
        $props = $this->getProperty($userId, array("login_bonus_date", "login_day_count"));

        // 本日分のカウントを既にしているなら、リターン。
        if(!is_null($props['login_bonus_date'])  &&  $props['login_bonus_date'] >= (int)date('Ymd'))
            return array('tick'=>false, 'count'=>(int)$props['login_day_count']);

        // ログインカウンタを+1。
        $this->updateProperty($userId, 'login_bonus_date', (int)date('Ymd'));
        $this->updateProperty($userId, 'login_day_count', 1, 'plus');

        // +1した後の値をとってリターン。
        return array('tick'=>true, 'count'=>$this->getProperty($userId, 'login_day_count'));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの、指定されたプロパティを取得する。
     *
     * @param int       ユーザID
     * @param mixed     プロパティ名。複数のプロパティを取得する場合はそれらの名前を格納した配列。
     *                  nullを渡した場合はすべて取得する
     * @return mixed    単一のプロパティの場合はその値。複数のプロパティを指定している(第2引数が
     *                  配列かnull)場合は、プロパティ名をキー、値を値とする配列。
     */
    public function getProperty($userId, $name = null) {

        // 検索条件を作成。
        $condition = array();
        $condition['user_id'] = $userId;
        if($name)  $condition['prop_name'] = $name;

        // 問い合わせ
        $records = $this->selectResultset($condition);

        // プロパティ名が文字列で指定されている場合は値を返す。
        if( is_string($name) ) {
            return $records ? $records[0]['prop_value'] : null;

        // 複数のプロパティを取得しようとしている場合は、プロパティ名をキー、値を値とする配列に
        // 直してリターン。
        }else{
            return ResultsetUtil::colValues($records, 'prop_value', 'prop_name');
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの、指定されたプロパティの値を更新する。
     *
     * @param int       ユーザID
     * @param string    プロパティ名。
     * @param int       更新後の値、あるいは増減値。
     * @param string    増減値として指定しているなら "plus"。
     */
    public function updateProperty($userId, $name, $update, $alt = '') {

        $record = array('user_id'=>$userId, 'prop_name'=>$name, 'prop_value'=>$update);

        $onUpdate = array();
        if($alt == 'plus')
            $onUpdate['prop_value'] = array('sql'=>'prop_value + ?', 'value'=>$update);

        $this->saveRecord($record, $onUpdate);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザのプロパティをすべて削除する。
     *
     * @param int       ユーザID
     */
    public function clearProperty($userId) {

        $this->createDao()->delete( array('user_id'=>$userId) );
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('user_id', 'prop_name');
}
