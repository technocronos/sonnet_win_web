<?php

/**
 * フィールドマスタはDBテーブルではなくファイルに置かれているので、getRecord, needRecord くらいしか
 * 利用できない…というか、必要ない。
 */
class Field_MasterService extends Service {


    // privateメンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのファイルを読み込んで、解析して返す。
     *
     * @param string    読み込むフィールドファイルのID。
     * @return array    定義の内容。ファイルがない場合は null。
     */
    private function readDataFile($id) {

        // データが置かれているファイルのパスを取得。
        $path = MO_BASE_DIR."/resources/field/{$id}.js";

        // ファイルの中身を取得。ファイルがない場合はレコードなしとしてnullを返す。
        $data = @file_get_contents($path);
        if($data === false)
            return null;

        // JSONデコードを行う。できない場合はエラー。
        $data = json_decode($data, true);
        if(json_last_error() != JSON_ERROR_NONE)
            throw new MojaviException("'{$id}' のフィールド定義をJSONデコードできない。(文字コードも要チェック)");

        // ここまでくればOK。リターン。
        return $data;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'quest_id';

    protected $isMaster = true;


    //-----------------------------------------------------------------------------------------------------
    /**
     * queryRecord() をオーバーライド
     */
    protected function queryRecord($pk) {

        return $this->readDataFile($pk[0]);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * createDaoをオーバーライド。
     * エラーにする。
     */
    protected function createDao($readonly = false) {

        throw new MojaviException('field_master はDBにないため、このインターフェイスは利用できません。');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * setPkCacheをオーバーライド。
     * データサイズが大きいのでキャッシュしないようにする。
     */
    protected function setPkCache($pk, $record) {
    }
}
