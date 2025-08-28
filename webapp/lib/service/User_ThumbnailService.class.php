<?php

class User_ThumbnailService extends Service {

    // プラットフォームからの返答のキャッシュ有効期間(時間)
    CONST CACHE_EXPIRE_HOURS = 72;

    // アバター画像のURLを取得できないときに代替として使う "NoImage" 画像のURL。
    private static $NO_IMAGE_FILENAME = array(
        'M' => 'no_image.jpg',
        'L' => 'no_image_l.jpg',
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユーザのサムネイルURLを返す。
     *
     * @param int       ユーザID
     * @param string    サムネイルのサイズ。user_thumbnail.size の値。
     * @return string   サムネイルURL
     */
    public function getThumbnail($userId, $size = 'M') {

        // getThumbnailsIn に転送して処理する。
        $ret = $this->getThumbnailsIn(array($userId), $size);
        return $ret[$userId];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された複数のユーザのサムネイルURLを返す。
     *
     * @param array     ユーザIDを列挙した配列。
     * @param string    サムネイルのサイズ。user_thumbnail.size の値。
     * @return array    ユーザIDをキーに、サムネイルURLを値とする配列。
     */
    public function getThumbnailsIn($userIds, $size = 'M') {

        // 戻り値初期化。
        $result = array();

        // システムユーザのアバターはNoImageにして問い合わせ対象から除外。
        foreach($userIds as $key => $id) {
            if($id < 0) {
                $result[$id] = '';
                unset($userIds[$key]);
            }
        }

        // 問い合わせ対象がなくなったのならここでリターン。
        if(!$userIds)
            return self::emptyToNoImage($result, $size);

        // 対象のレコードをデータベースから取得。
        $sqlParams = array($size);
        $sql = '
            SELECT *
            FROM user_thumbnail
            WHERE size = ?
              AND user_id ' . DataAccessObject::buildRightSide($userIds, $sqlParams) . '
        ';
        $records = $this->createDao(true)->getAll($sql, $sqlParams);

        // キャッシュ有効期間内のもののみを戻り値へ。
        foreach($records as $cache) {
            if(strtotime($cache['update_at']) + self::CACHE_EXPIRE_HOURS * 60*60 < time())
                $result[ $cache['user_id'] ] = $cache['url'];
        }

        // キャッシュから取得できたものを除外。
        $userIds = array_diff($userIds, array_keys($result));

        // 問い合わせ対象がなくなったのならここでリターン。
        if(!$userIds)
            return self::emptyToNoImage($result, $size);

        // 残りはプラットフォームに問い合わせ
        $platformRes = PlatformApi::queryThumbnail($userIds, ($size=='L' ? 'large' : 'medium'));

        // 問い合わせたものを一つずつ見ていく。
        foreach($userIds as $id) {

            // URLを取得。問い合わせが失敗しているならカラ文字とする。
            $url = $platformRes[$id] ?: '';

            // 恒久キャッシュとしてストア。
            $this->saveRecord(array(
                'size' => $size,
                'user_id' => $id,
                'url' => $url,
            ));

            // 戻り値に追加。
            $result[$id] = $url;
        }

        // リターン。
        return self::emptyToNoImage($result, $size);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('size', 'user_id');


    //-----------------------------------------------------------------------------------------------------
    /**
     * updateRecordをオーバーライド。update_atも更新するようにする。
     */
    public function updateRecord($pk, $update) {
        $update['update_at'] = array('sql'=>'NOW()');
        return parent::updateRecord($pk, $update);
    }


    // privateメンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された配列要素のうち、カラの値を持つものを「NO IMAGE」の画像URLに差し替えて返す。
     */
    private static function emptyToNoImage($result, $size) {

        foreach($result as &$url) {
            if(!$url)
                $url = APP_WEB_ROOT . 'img/parts/' . self::$NO_IMAGE_FILENAME[$size];
        }

        return $result;
    }
}
