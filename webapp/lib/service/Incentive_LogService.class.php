<?php

class Incentive_LogService extends Service {

    const ARTICLE = 1;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが、指定された種別のインセンティブを規定時間内に何回受けているかを返す。
     *
     * @param int   ユーザID
     * @param int   インセンティブの種別
     * @return int  基底時間内に受けている回数
     */
    public function getHotCount($userId, $type) {

        // 規定時間。今のところ当日の0時固定。
        $threshold = date('Y/m/d 00:00:00');

        return $this->countRecord(array(
            'type' => $type,
            'user_id' => $userId,
            'create_at' => array('sql'=>'>= ?', 'value'=>$threshold),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが、指定された種別のインセンティブを付与されたときのログを作成する。
     *
     * @param int   ユーザID
     * @param int   インセンティブの種別
     */
    public function logIncentive($userId, $type) {

        $this->insertRecord(array(
            'type' => $type,
            'user_id' => $userId,
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'incentive_id';

    protected $deleteOlds = 30;
}
