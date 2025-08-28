<?php
/**
 * Eメールアドレスチェック
 * Eメールアドレスと桁数(最小/最大)をチェックするためのバリデーションタイプ
 * 
 * (validate設定ファイルパラメータ)
 * email_error・・・Eメールアドレスの書式でない時に表示するメッセージ
 * max・・・Eメールアドレスの最大桁数(有効値：0〜)(デフォルト：-1)
 * max_error・・・最大桁数をオーバした時に表示するエラーメッセージ
 * min・・・Eメールアドレスの最小桁数(有効値：0〜)(デフォルト：-1)
 * min_error・・・最小桁数をオーバした時に表示するエラーメッセージ
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 */
class EmailValidator extends Validator
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
		/* Eメールアドレスの書式をチェックする */
		if (!preg_match('/^[0-9a-z_.\-\+]*@[0-9a-z\-]+(\.[0-9a-z\-]+)*'.
			'\.(com|net|org|edu|gov|mil|int|info|biz|name|pro|museum|aero|coop|[a-z][a-z])$/i', $value)) {
			$error = $this->getParameter('email_error');
			return false;
		}

		/* Eメールアドレスの文字数をセットする */
		$length = strlen($value);

		/* Eメールアドレスの最小桁数をオーバしていないかチェックする */
		if ($this->getParameter('min') > -1 && $length < $this->getParameter('min')) {
			$error = $this->getParameter('min_error');
			return false;
		}

		/* Eメールアドレスの最大桁数をオーバしていないかチェックする */
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
		$this->setParameter('email_error', 'Invalid email address');
		$this->setParameter('max', -1);
		$this->setParameter('max_error', 'Email address is too long');
		$this->setParameter('min', -1);
		$this->setParameter('min_error', 'Email address is too short');

		/* バリデータパラメータをセットする */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>