<?php

/**
 * キャラクター画像に関するユーティリティクラス。
 */
class CharaImageUtil {

    const PATH_SECRET = '#$TGQ$G%G 5q4gq34r5grefg4w3$';


    //-----------------------------------------------------------------------------------------------------
    /**
     * キャラ情報を受け取って、キャラクターイメージファイルの物理パスを返す。
     *
     * @param array     Character_InfoService::getExRecord で取得したキャラクター情報。
     * @param string    どのタイプのイメージがほしいのかを以下のいずれかで指定する。
     *                      web     gifフルサイズ
     *                      nail    gifサムネイル用
     *                      swf     pngフルサイズ
     * @return string   指定された画像の物理パス。
     */
    public static function getImageFromChara($chara, $type, $imgtype = 'normal', $bg = '') {

        // ImageMagickが利用できない場合はデフォルト画像を返す
        if (!self::isImageMagickAvailable()) {
            return self::getDefaultImagePath($type);
        }

        // 指定されたキャラの画像構成を取得。
        $formation = self::getFormation($chara);

        //スマホ用対策
        if($imgtype == 'large')
            $formation[0] = $formation[0] . "_lg";
        else
            $formation[0] = $formation[0] . "_sm";

        $formation[] = $bg;

        // 画像構成からファイルを作成して、パスを返す。
        return self::needImage($formation, $imgtype) . self::$EXT[$type];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getSpec() の戻り値を受け取って、キャラクターイメージファイルの物理パスを返す。
     *
     * @param array     getSpec() の戻り値。
     * @param string    どのタイプのイメージがほしいのか指定する。getImageFromChara() と同じ。
     * @return string   指定された画像の物理パス。
     */
    public static function getImageFromSpec($spec, $type, $imgtype = 'normal', $bg = '') {

        // ImageMagickが利用できない場合はデフォルト画像を返す
        if (!self::isImageMagickAvailable()) {
            return self::getDefaultImagePath($type);
        }

        // getSpec() の戻り値から [画像構成 ＋ 認証キー] を取得。
        $formation = explode('.', $spec);

        // 画像構成と認証キーを分離
        $key = array_pop($formation);

        // 認証キーが合っているかチェック。
        if( $key != self::getWebKey($formation) )
            throw new MojaviException('リクエストされているパスとファイルキーが一致しません。');

        if($imgtype == 'large')
            $formation[0] = $formation[0] . "_lg";
        else
            $formation[0] = $formation[0] . "_sm";

        $formation[] = $bg;

        // 画像構成からファイルを作成して、パスを返す。
        return self::needImage($formation, $imgtype) . self::$EXT[$type];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたキャラ情報から画像生成に必要な情報を抜き出して、
     * HTML上での伝送に使える文字列にして返す。
     *
     * @param array     Character_InfoService::getExRecord で取得したキャラクター情報。
     * @return string   HTML上での伝送に使用する文字列
     */
    public static function getSpec($chara) {

        // 指定されたキャラの画像構成を取得。
        $formation = self::getFormation($chara);

        // 画像構成をURLで伝えるに当たっての認証キーを生成。
        $formation[] = self::getWebKey($formation);

        // 構成とキーを「.」で区切ってリターン。
        return implode('.', $formation);
    }


    // private メンバ
    //=====================================================================================================

    // タイプに応じた拡張子。
    private static $EXT = array(
        'web' => '.gif',
        'swf' => '.png',
        'nail' => '_nail.gif',
    );

    // private メンバ
    //=====================================================================================================
    // タイプに応じたフォルダ。

    private static $IMAGE_SCALE = array(
        'normal' => 2,
        'large' => 5,
    );

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの画像構成(画像を作成するのに必要な最低限の情報)を返す。
     *
     * @param array     Character_InfoService::getExRecord で取得したキャラクター情報。
     * @return array    画像構成を格納した序数配列。
     *                  第0要素にraceが、第1以降の要素には画像を構成するアイテムIDが入る。
     *                  第1以降の各要素の意味は race によって異なる。
     */
    private static function getFormation($chara) {

        // 装備箇所と対応する装備アイテムのIDを取得。
        // 管理画面でセットされている擬似列があるならそちらから取得。
        if( isset($chara['admin_check']) ) {
            $equipGraphs = $chara['admin_check'];

        // ないなら...
        }else {

            // 装備なしの状態での装備アイテムIDを取得。
            $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
            $equipGraphs = ResultsetUtil::colValues($mounts, 'default_id', 'mount_id');

            // 装備しているものがある場合はそのアイテムIDで上書き。
            foreach($chara['equip'] as $mountId => $uitem)
                $equipGraphs[$mountId] = $uitem['item_id'];
        }

        // 種族によって切り替える。
        switch($chara['race']) {

            case 'PLA':
                $headId = $equipGraphs[Mount_MasterService::PLAYER_HEAD];
                $bodyId = $equipGraphs[Mount_MasterService::PLAYER_BODY];
                $weaponId = $equipGraphs[Mount_MasterService::PLAYER_WEAPON];
                $shieldId = $equipGraphs[Mount_MasterService::PLAYER_SHIELD];
                return array('PLA', $weaponId, $bodyId, $headId, $shieldId);

            case 'MOB':
                return array('MOB', $chara['graphic_id']);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された画像構成をシステム外部で受け渡しするときの認証キーを返す。
     *
     * @param array     getFormation() の戻り値。
     * @return string   認証キー
     */
    private static function getWebKey($formation) {

        $base = implode('#', $formation) . '#' . self::PATH_SECRET;
        return sha1($base);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された画像構成のファイルが必要になったタイミングで呼ばれる。
     * まだ作成されていないなら作成し、その格納パスを返す。
     *
     * @param array     getFormation() の戻り値。
     * @return string   格納パスだが、拡張子は除かれている。
     */
    private static function needImage($formation, $imgtype = 'normal') {

        // ImageMagickが利用できない場合は、デフォルト画像のパスを返す
        if (!self::isImageMagickAvailable()) {
            return self::getDefaultImagePath('web'); // デフォルトではwebタイプを返す
        }

        // 指定された画像構成での格納パスを取得。
        $path = self::getPath($formation);

        // 格納パスのディレクトリを取得。
        $dir = dirname($path);

        // 指定された画像構成でのレイヤ配列を取得。
        $layers = self::getLayers($formation);

        // ディレクトリの有無チェック＆作成。
        if( !file_exists($dir) )
            mkdir($dir, 0777, true);

        // 管理ファイルのパスを決定。
        $lockFilePath = $dir . '/lock';

        // 管理ファイルの作成とロックの取得。
        $lockFile = fopen($lockFilePath, 'c');
        flock($lockFile, LOCK_EX);

        // 合成後のファイルがある場合は管理ファイルの更新日時が最終合成日時になるので、これを取得。
        // 合成後のファイルがない場合は最も古いタイムスタンプを最終合成日時とする。
        $lastCreateTime = file_exists($path.'.gif') ? filemtime($dir.'/lock') : 0;

        // レイヤとなる画像のうち、最終合成日時より後に更新されているものが
        // あるがとうかチェック。ある場合は...
        if( self::isUpdated($layers, $lastCreateTime) ) {

            // 合成をいつ行ったかを記録するため、管理ファイルの更新日時を更新する。
            touch($lockFilePath);

            // 合成処理。
            self::createImage($layers, $path, $imgtype);
        }

        // ロック解放。
        flock($lockFile, LOCK_UN);
        fclose($lockFile);

        // 格納パスをリターン。
        return $path;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された画像構成の場合の格納パスを返す。
     *
     * @param array     getFormation() の戻り値。
     * @return string   格納パスだが、拡張子は除かれている。
     */
    private static function getPath($formation) {

        // 画像構成をそのままディレクトリ階層にする。
        $path = implode('/', $formation);

        // ファイル名はなんでもいいけど、とりあえず各パーツの下3桁を連結したものにする。
        $file = '';
        foreach($formation as $part)
            $file .= substr($part, -3);

        // 合成済み画像の置き場の下のパスに変換してリターン。
        return CHARA_CACHE_DIR . '/' . $path . '/' . $file;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された画像構成でのレイヤ構造を下から順に配列で返す。
     *
     * @param array     getFormation() の戻り値。
     * @return array    レイヤ画像のパスを格納している序数配列。
     *                  下レイヤから順番に格納されている。
     */
    private static function getLayers($formation) {

        // 構成の最初の要素は race の値なので取り出しておく。
        $race = $formation[0];

        // レイヤ構造を下から順にファイル名のみで作成。
        $layers = array();
        switch($race) {

            // race:PLA の場合。この場合、配列 $formation の要素には次の部位のアイテムIDが格納されている。
            //     1:武器、2:体、3:頭、4:盾
            case 'PLA':
            case 'PLA_sm':
            case 'PLA_lg':
                $layers[] = $formation[5] . '.png';
                $layers[] = sprintf('%05d', $formation[3]) . '_1.png';
                $layers[] = sprintf('%05d', $formation[2]) . '_1.png';
                $layers[] = sprintf('%05d', $formation[1]) . '.png';
                $layers[] = sprintf('%05d', $formation[2]) . '.png';
                $layers[] = sprintf('%05d', $formation[4]) . '_1.png';
                $layers[] = sprintf('%05d', $formation[3]) . '_2.png';
                $layers[] = sprintf('%05d', $formation[4]) . '_2.png';
                break;

            // race:MOB の場合はレイヤは一つしかない。
            case 'MOB':
            case 'MOB_sm':
            case 'MOB_lg':
                $layers[] = $formation[2] . '.png';
                $layers[] = sprintf('%05d', $formation[1]) . '.png';
                break;
        }

        // race の値に応じて、パーツ画像置き場を取得。
        $partsDir = IMG_RESOURCE_DIR . '/' . $race;
        $bgDir    = IMG_RESOURCE_DIR . '/bg';

        // 構成された各レイヤを見て...
        foreach($layers as $index => $layer) {

            // 絶対パスに変換。
            if($index != 0)
                $layers[$index] = "{$partsDir}/{$layer}";
            else
                $layers[$index] = "{$bgDir}/{$layer}";

            // ファイルが存在しないものを削除する。
            if(!file_exists($layers[$index]))
                unset($layers[$index]);
        }

        // リターン。
        return $layers;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたレイヤ画像のうち、指定された最終合成日時より後に更新されているものが
     * あるがとうかを返す。
     *
     * @param array     getLayers() の戻り値。
     * @param int       最終合成日時。
     * @return bool     更新されたレイヤ画像があるならtrue、ないならfalse。
     */
    private static function isUpdated($layers, $lastCreate) {

        // レイヤ画像を一つずつ見ていく。
        // レイヤ画像の最終更新日時が指定された日時より後だったらtrue。
        foreach($layers as $layer) {
            if( $lastCreate < filemtime($layer) )
                return true;
        }

        // ここまで来れば更新なし。
        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された画像構成で合成処理を行い、指定された格納パスに保存する。
     *
     * @param array     getLayers() の戻り値。
     * @param string    getPath() の戻り値。ディレクトリは作成済みであること。
     */
    private static function createImage($layers, $path, $imgtype = 'normal') {

        $scale = self::$IMAGE_SCALE[$imgtype];

        // 作業用ファイルのパスを取得。
        $workPath = CHARA_TMP_DIR . '/' . uniqid() . '.png';

        $chara_width = CHARA_WIDTH * $scale;
        $chara_height = CHARA_HEIGHT * $scale;

        // とりあえず、元になるファイルを作成。
        $command = sprintf('convert -size %dx%d xc:none "%s"', $chara_width, $chara_height, $workPath);
//Common::varLog($command);
        self::execute($command);

        // レイヤ画像を一つずつ合成していく。
        foreach($layers as $layer) {
            $command = sprintf('composite "%s" "%s" "%s"', $layer, $workPath, $workPath);
            self::execute($command);
        }

        // 合成し終わった画像を出力。WEB用。
        $command = sprintf('convert "%s" "%s"', $workPath, $path.self::$EXT['web']);
        self::execute($command);

        // 同、SWF用。
        $command = sprintf('convert "%s" "%s"', $path.self::$EXT['web'], $path.self::$EXT['swf']);
        self::execute($command);

        $a = 15 * $scale;
        $b = 10 * $scale;
        $c = 60 * $scale;
        $d = 48 * $scale;

        $command = sprintf('convert -chop ' . $a . 'x' . $b .' -crop ' . $c .'x' . $d .'+0+0 +repage "%s" "%s"', $workPath, $path.self::$EXT['nail']);

        self::execute($command);

        // 作業用ファイルを削除。
        unlink($workPath);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ImageMagickが利用可能かどうかをチェックする。
     *
     * @return bool     ImageMagickが利用可能ならtrue、利用できないならfalse。
     */
    private static function isImageMagickAvailable() {
        static $available = null;
        
        if ($available === null) {
            exec('which convert 2>/dev/null', $output, $returnCode);
            $available = ($returnCode === 0);
        }
        
        return $available;
    }

    /**
     * デフォルト画像のパスを返す。
     *
     * @param string    画像タイプ（web, nail, swf）
     * @return string   デフォルト画像のパス
     */
    private static function getDefaultImagePath($type) {
        // デフォルト画像のディレクトリパス（必要に応じて調整）
        $defaultDir = CHARA_TMP_DIR . '/default';
        
        // ディレクトリが存在しない場合は作成
        if (!is_dir($defaultDir)) {
            mkdir($defaultDir, 0755, true);
        }
        
        $defaultImagePath = $defaultDir . '/default' . self::$EXT[$type];
        
        // デフォルト画像ファイルが存在しない場合は、空の画像を作成
        if (!file_exists($defaultImagePath)) {
            self::createEmptyDefaultImage($defaultImagePath, $type);
        }
        
        return $defaultImagePath;
    }

    /**
     * 空のデフォルト画像を作成する。
     *
     * @param string    作成する画像のパス
     * @param string    画像タイプ（web, nail, swf）
     */
    private static function createEmptyDefaultImage($imagePath, $type) {
        // 画像サイズを決定
        switch ($type) {
            case 'nail':
                $width = 60;
                $height = 48;
                break;
            case 'web':
            case 'swf':
            default:
                $width = 120;
                $height = 96;
                break;
        }
        
        // GDライブラリが利用可能な場合は、空の画像を作成
        if (extension_loaded('gd')) {
            $image = imagecreatetruecolor($width, $height);
            
            // 透明色を設定
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $transparent);
            
            // 画像を保存
            switch ($type) {
                case 'web':
                    imagegif($image, $imagePath);
                    break;
                case 'swf':
                case 'nail':
                    imagepng($image, $imagePath);
                    break;
            }
            
            imagedestroy($image);
        } else {
            // GDライブラリが利用できない場合は、既存の画像をコピーするか、エラーを回避
            // 最小限のPNGファイルを作成（1x1ピクセルの透明画像）
            $minimalPng = "\x89PNG\r\n\x1a\n\x00\x00\x00\rIHDR\x00\x00\x00\x01\x00\x00\x00\x01\x08\x06\x00\x00\x00\x1f\x15\xc4\x89\x00\x00\x00\nIDATx\x9cc\x00\x00\x00\x02\x00\x01\xe5\x27\xfe\x0f\x00\x00\x00\x00IEND\xaeB`\x82";
            file_put_contents($imagePath, $minimalPng);
        }
    }

    /**
     * 引数で指定された ImageMagick コマンドを実行する。
     * 実行に失敗したら例外を投げる。
     *
     * @param string    実行したいコマンド。
     */
    private static function execute($command) {

        // ImageMagickが利用できない場合は例外を投げる
        if (!self::isImageMagickAvailable()) {
            throw new MojaviException('ImageMagickがインストールされていません。画像処理ができません。');
        }

        // 標準エラー出力を標準出力にリダイレクトする。
        $command .= ' 2>&1';

        // 実行。エラーが発生しているなら例外を投げる。
        exec($command, $output, $commVal);
        if($commVal)  throw new MojaviException(implode("\n", $output));
    }
}
