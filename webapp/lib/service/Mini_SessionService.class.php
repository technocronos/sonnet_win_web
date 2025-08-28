<?php

class Mini_SessionService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたデータの値を返す。
     * なかった場合はnullを返す。
     */
    public function getData($id) {

        $record = $this->getRecord($id);

        return $record ? json_decode($record['data'], true) : null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getDataと同じだが、データがなかった場合は所定のページにリダイレクトする。
     */
    public function needData($id) {

        $data = $this->getData($id);

        if(is_null($data))
            Common::redirect($_GET['module'], 'Static', array('id'=>'Timeout'));

        return $data;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値をDBに保存する。
     *
     * @param mixed     保存したいデータ。
     * @param string    更新の場合は更新対象のID。新規保存の場合は不要
     * @return string   新規保存の場合は自動的に決定したID。更新の場合は第2引数と同じ
     */
    public function setData($data = array(), $id = '') {

        // レコードを作成。
        $record = array('data' => json_encode($data));

        // 更新の場合。
        if($id) {
            $this->updateRecord($id, $record);
            return $id;

        // 新規の場合。
        }else {
            $record['mini_session_id'] = Common::createRandomString(32);
            $this->insertRecord($record);
            return $record['mini_session_id'];
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値を既存の値にマージする。マージのルールはarray_mergeと同じ。
     * 既にセットされているデータがない場合は新規作成を行う(このとき指定のIDは使用されない)。
     * 既にセットされているデータが配列でない場合、そのデータは削除される。
     *
     * @param array     マージしたいデータ。
     * @param string    データID
     * @return string   保存に使用されたID。基本的に第二引数と同じ値だが、新規作成が行われた場合は
     *                  値が変わっているので注意。
     */
    public function mergeData($data, $id) {

        // 第二引数が指定されているなら既存データを取得。
        if($id)
            $oldData = $this->getData($id);

        // 既存データがなかったら新規作成モードに。
        if(!isset($oldData)) {
            $id = '';

        // 既存データがあって、それが配列なら、マージ。
        }else if(is_array($oldData)) {
            $data = array_merge($oldData, $data);
        }

        // 保存。
        return $this->setData($data, $id);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'mini_session_id';

    // 作成から24時間以上経過しているレコードを削除するようにする。
    protected $deleteOlds = 1;
}
