<?php
/**
 * ファイルチェック
 * ファイルサイズ、画像サイズなどをチェックするためのバリデーションタイプ
 * 
 * (validate設定ファイルパラメータ)
 *
 * types・・・・・・画像タイプ(array)
 *				  1 = GIF
 *				  2 = JPG
 *				  3 = PNG
 *				  4 = SWF
 *				  5 = PSD
 *				  6 = BMP
 *				  7 = TIFF(intel byte order)
 *				  8 = TIFF(motorola byte order)
 *				  9 = JPC
 *				  10 = JP2
 *				  11 = JPX
 *				  12 = JB2
 *				  13 = SWC
 *				  14 = IFF
 *				  15 = WBMP
 *				  16 = XBM
 * types_error・・・画像タイプにない場合に表示するエラーメッセージ
 *
 * max・・・・・・・最大バイトサイズ(有効値：1〜)(デフォルト：null)
 * max_error・・・・最大バイトサイズを上回った時に表示するエラーメッセージ
 * min・・・・・・・最小バイトサイズ(有効値：1〜)(デフォルト：null)
 * min_error・・・・最小バイトサイズを下回った時に表示するエラーメッセージ
 *
 * max_height ・・・画像最大縦サイズ(有効値：0〜)(デフォルト：null)
 * max_height_err ・画像最大縦サイズを上回った時に表示するエラーメッセージ
 * min_height ・・・画像最小縦サイズ(有効値：0〜)(デフォルト：null)
 * min_height_err ・画像最小縦サイズを下回った時に表示するエラーメッセージ
 *
 * max_width・・・・画像最大横サイズ(有効値：0〜)(デフォルト：null)
 * max_width_err・・画像最大横サイズを上回った時に表示するエラーメッセージ
 * min_width・・・・画像最小横サイズ(有効値：0〜)(デフォルト：null)
 * min_width_err・・画像最小横サイズを下回った時に表示するエラーメッセージ
 * 
 * quarantine ・・・アップロードファイルがウィルスに感染していないか（デフォルト：false）
 * quarantine_err ・アップロードファイルがウィルスに感染していた時のエラーメッセージ
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 */
class FileValidator extends Validator
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
     * バリデーションを実行する
     * @access public
     * @param string &$value バリデーションするデータ
     * @param string &$error エラー内容(エラーメッセージが格納される)
     * @return boolean バリデーション結果
     */
	public function execute (&$value, &$error)
	{
		// MAX_FILE_SIZEを取得する
		$context = &$this->getContext();
		$request = &$context->getRequest();
		$maxFileSize = $request->getParameter('MAX_FILE_SIZE', ini_get('upload_max_filesize'));
		if (is_numeric($maxFileSize)) {
			$maxFileSize = sprintf('%dＭバイト', $maxFileSize / 1024 / 1024);
		}

		// ファイルがアップロードされなかったのでチェックしない
		if (empty($value['name'])) {
			return true;
		}
		
		// アップロードのエラーチェック
		switch ($value['error']) {
			case UPLOAD_ERR_OK : // ファイルがアップロードされた
				// 何もしない
				break;
			case UPLOAD_ERR_INI_SIZE : //ファイルサイズオーバー
			case UPLOAD_ERR_FORM_SIZE : //ファイルサイズオーバー（フォーム）
				$error = sprintf("ファイルサイズが大き過ぎます。最大%sでお願いします。", $maxFileSize);
				return false;
			case UPLOAD_ERR_PARTIAL : //不完全ファイル
			case UPLOAD_ERR_NO_FILE : //ファイルがアップロードされなかった
				$error = 'ファイルアップロードに失敗しました。アップロードし直して下さい。';
				return false;
			case UPLOAD_ERR_CANT_WRITE : // ファイル書込みエラーが発生しました
				throw new FileException('ファイル書込みエラーが発生しました。ファイルの書込み権限をチェックしてください。');
			case UPLOAD_ERR_NO_TMP_DIR : //テンポラリフォルダが存在しない
				throw new FileException('テンポラリフォルダが存在しません。テンポラリフォルダが作成されているかチェックしてください。');
		}

		// ↓↓↓　ファイルチェックを行う　↓↓↓
		
		// ウィルスチェックを行う
		if ($this->getParameter('quarantine') && extension_loaded('clamav')) {
			cl_setlimits(5, 1000, 200, 0, $maxFileSize);    	
			$message = cl_scanfile($value['tmp_name']);
			if ($message) {
				$error = $this->getParameter('quarantine_err');
				return false;
			}
		}
	
		// ファイル情報を取得する
		$imagesize = getimagesize($value['tmp_name']);
		$width = $imagesize[0];
		$height = $imagesize[1];
		$type = $imagesize[2];
		$size = $value['size'];
		
		$types = $this->getParameter('types');
		$max = $this->getParameter('max');
		$min = $this->getParameter('min');
		$max_height = $this->getParameter('max_height');
		$min_height = $this->getParameter('min_height');
		$max_width = $this->getParameter('max_width');
		$min_width = $this->getParameter('min_width');
		
		// 画像タイプチェック
		if ($types != null) {
			if (!in_array($type, $types)) {
				$error = $this->getParameter('types_error');
				return false;
			}
		}

		// ファイルサイズチェック
		if ($max != null && $size > $max) {
			// too large
			$error = $this->getParameter('max_error');
			return false;
		}
		if ($min != null && $size < $min) {
			// too small
			$error = $this->getParameter('min_error');
			return false;
		}
		
		// 画像縦サイズチェック
		if ($max_height != null && $height > $max_height) {
			// too long
			$error = $this->getParameter('max_height_err');
			return false;
		}
		if ($min_height != null && $height < $min_height) {
			// too short
			$error = $this->getParameter('min_height_err');
			return false;
		}
		
		// 画像横サイズチェック
		if ($max_width != null && $width > $max_width) {
			// too long
			$error = $this->getParameter('max_width_err');
			return false;
		}
		if ($min_width != null && $width < $min_width) {
			// too short
			$error = $this->getParameter('min_width_err');
			return false;
		}

		return true;
	}

	/**
	 * 初期処理
	 * @access public
	 * @param Object $contextt context
	 * @param array $parameters バリデータパラメータ
	 * @return boolean 処理結果
	 */
	public function initialize ($context, $parameters = null)
	{
		// デフォルト値セット
		$this->setParameter('types', null);
		$this->setParameter('types_error', 'Invalid image type');
		$this->setParameter('max', null);
		$this->setParameter('max_error', 'Size is too large');
		$this->setParameter('min',null);
		$this->setParameter('min_error', 'Size is too small');
		$this->setParameter('max_height', null);
		$this->setParameter('max_height_err', 'Height is too long');
		$this->setParameter('max_width',null);
		$this->setParameter('max_width_err', 'Width is too long');
		$this->setParameter('min_height', null);
		$this->setParameter('min_height_err', 'Height is too short');
		$this->setParameter('min_width',null);
		$this->setParameter('min_width_err', 'Width is too short');
		$this->setParameter('quarantine', false);
		$this->setParameter('quarantine_err', 'Infected file');

		// バリデータパラメータをセットする
		parent::initialize($context, $parameters);

		return true;

	}

}

?>