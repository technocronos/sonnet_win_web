<?php
/**
 * URLチェック
 * URLと桁数(最小/最大)をチェックするためのバリデーションタイプ
 * 
 * (validate設定ファイルパラメータ)
 * url_error・・・URLの書式でない時に表示するメッセージ
 * max・・・URLの最大桁数(有効値：0〜)(デフォルト：-1)
 * max_error・・・最大桁数をオーバした時に表示するエラーメッセージ
 * min・・・URLの最小桁数(有効値：0〜)(デフォルト：-1)
 * min_error・・・最小桁数をオーバした時に表示するエラーメッセージ
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 */
class URLValidator extends Validator
{

	// +-----------------------------------------------------------------------+
	// | METHODS                                                               |
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
		/* URLの書式をチェックする */
		if (!preg_match("/^(http|https):\/\/[A-Za-z0-9;\?:@&=+\$,\-_\.!~\*'\(\)%]+(:\d+)?" . 
			"(\/?[A-Za-z0-9;\/\?:@&=+\$,\-_\.!~\*'\(\)%])*$/",  $value)) {
			$error = $this->getParameter('url_error');
			return false;
		}

		/* URLの文字数をセットする */
		$length = strlen($value);

		/* URLの最小桁数をオーバしていないかチェックする */
		if ($this->getParameter('min') > -1 && $length < $this->getParameter('min')) {
			$error = $this->getParameter('min_error');
			return false;
		}

		/* URLの最大桁数をオーバしていないかチェックする */
		if ($this->getParameter('max') > -1 && $length > $this->getParameter('max')) {
			$error = $this->getParameter('max_error');
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
		/* デフォルト値セット */
		$this->setParameter('url_error', 'Invalid URL');
		$this->setParameter('max', -1);
		$this->setParameter('max_error', 'URL is too long');
		$this->setParameter('min', -1);
		$this->setParameter('min_error', 'URL is too short');

		/* バリデータパラメータをセットする */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>