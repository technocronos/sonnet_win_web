<?php
/**
 * 項目比較チェック
 * 他入力項目データと入力データが一致するかを確認するためのバリデーションタイプ
 * 
 * (validate設定ファイルパラメータ)
 * comparison_field・・・他入力項目データのHTMLフィールド名を設定する
 * match_error・・・他入力項目データと一致しない時に表示するエラーメッセージ
 * sensitive・・・大小文字を区別するか(デフォルト：true)
 * is_equal・・・他入力項目データと入力データが一致するか(デフォルト：true)
 * 	(true・・・他入力項目と一致する、false・・・他入力項目と一致しない)
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 */
class FieldMatchValidator extends Validator
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
		/* REQUESTデータから他入力項目データを取得する */
		$context = &$this->getContext();
		$request = &$context->getRequest();
		$comparisonField = 
			$request->getParameter($this->getParameter('comparison_field'));
	
		/* 入力データと他入力項目データを比較する */
		if ($this->getParameter('sensitive')) {
			$result = strcmp($value, $comparisonField);
		} else {
			$result = strcasecmp($value, $comparisonField);
		}

		/* 一致することをチェックする */	
		if ($this->getParameter('is_equal') && $result == 0) {
			return true;
		}

		/* 一致しないことをチェックする */	
		if (!$this->getParameter('is_equal') && $result != 0) {
			return true;
		}

		/* 他項目との比較に失敗したらエラーメッセージをセットする */
		$error = $this->getParameter('match_error');
		
		return false;
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
		$this->setParameter('comparison_field', '');
		$this->setParameter('sensitive', true);
		$this->setParameter('is_equal', true);
		$this->setParameter('match_error', 'The fields do not match');
		
		/* バリデータパラメータをセットする */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>