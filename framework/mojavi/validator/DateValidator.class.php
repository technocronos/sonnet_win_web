<?php
/**
 * 日付チェック
 * 日付データの書式と有効な日付データをチェックするためのバリデーションタイプ
 * (書式)
 * ・書式1：YY(YY)/MM/DD
 * ・書式2：YY(YY)-MM-DD
 * ・書式3：DD.MM.YY(YY)
 * ・書式4：DD/MM/YY(YY)
 * 
 * ・YY(YY)・・・西暦年4桁もしくは、西暦年2桁
 *	(70以上は1900年台、69以下は2000年台とする)
 * ・MM・・・月2桁
 * ・DD・・・日2桁
 * 
 * (validate設定ファイルパラメータ)
 * format_error・・・日付データの書式でない時に表示するエラーメッセージ
 * date_error・・・日付データの値が有効でない時に表示するエラーメッセージ
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 */
class DateValidator extends Validator
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
		/* 日付の書式をチェックし、日付データを取得する */
		$matches = array();
		
		// check YY(YY)/MM/DD
		if (preg_match('/^(?:((?:\d{2})?\d{2})\/)?(\d{2})\/(\d{2})$/', $value, $matches)) {
			if (count($matches) == 4) {
				$year = $matches[1];
				$month = $matches[2];
				$day = $matches[3];
			} else {
				$year = date('Y');
				$month = $matches[1];
				$day = $matches[2];
			}
		// check YY(YY)-MM-DD
		} elseif (preg_match('/^(?:((?:\d{2})?\d{2})-)?(\d{2})-(\d{2})$/', $value, $matches)) {
			if (count($matches) == 4) {
				$year = $matches[1];
				$month = $matches[2];
				$day = $matches[3];
			} else {
				$year = date('Y');
				$month = $matches[1];
				$day = $matches[2];
			}
		// check DD.MM.YY(YY)
		} elseif (preg_match('/^(\d{1,2})(?:[. ](\d{1,2})(?:[. ]((?:\d{2})?\d{2}))?)?[. ]?$/', $value, $matches)) {
			$day = $matches[1];
			if (isset($matches[2])) {
				$month = $matches[2];
			} else {
				$month = date('m');
			}
			if (isset($matches[3])) {
				$year = $matches[3];
			} else {
				$year = date('Y');
			}
		// check MM/DD/YY(YY)
		} elseif (preg_match('/^(\d{1,2})\/(\d{1,2})(?:\/((?:\d{2})?\d{2}))?$/', $value, $matches)) {
			$month = $matches[1];
			$day = $matches[2];
			if (sizeof($matches) > 3) {
				$year = $matches[3];
			} else {
				$year = date('Y');
			}
		// check YYYYMMDD
		} elseif (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $value, $matches)) {
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
		} else {
			$error = $this->getParameter('format_error');
			return false;
		}

		/* 西暦年2桁対応 */
		if ($year < 70) {
			$year += 2000;
		} elseif ($year < 100) {
			$year += 1900;
		}
		
		/* 日付データであるかチェックする */
		if (!checkdate($month, $day, $year)) {
			$error = $this->getParameter('date_error');
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
		$this->setParameter('format_error', 'Invalid format');
		$this->setParameter('date_error', 'Invalid date');

		/* バリデータパラメータをセットする */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>