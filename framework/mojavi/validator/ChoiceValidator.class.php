<?php
/**
 * 選択チェック
 * 選択データ(設定値)と選択値を比較するためのバリデーションタイプ
 * 
 * (validate設定ファイルパラメータ)
 * choices・・・選択データ(array)
 * choices_error・・・バリデーションエラーが発生した時に表示するメッセージ
 * sensitive・・・大小文字を区別するか(デフォルト：false)
 * valid・・・選択データのいずれかのデータかチェックする(デフォルト：true)
 * 	(true・・・選択データと一致する、false・・・選択データと一致しない)
 * 
 * ＜validateファイルの設定例＞
 * [methods]
 *	get  = "colName"
 *	post = "colName"
 * 
 * [names]
 * 	colName.required		=	"yes"
 *	colName.required_msg	=	"colNameを入力してください。"
 *	colName.validators		=	"colNameValidator"
 * 
 * [colNameValidator]
 *	class = "ChoiceValidator"
 *	param.choices.0			=	"0"
 *	param.choices.1			=	"1"
 *	param.choices_error		=	"colNameを入力してください。"
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 */
class ChoiceValidator extends Validator
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
		$found = false;

		/* 大小文字を区別しない場合、選択値を小文字に変換する */
		if (!$this->getParameter('sensitive')) {
			$newValue = strtolower($value);
		} else {
			$newValue = &$value;
		}

		/* 選択データ(設定値)に選択値が存在するかをチェックする */
		if (in_array($newValue, $this->getParameter('choices'))) {
			$found = true;
		}

		/* 選択チェックエラーであれば、エラーメッセージをセットする */
		if (($this->getParameter('valid') && !$found) ||
			(!$this->getParameter('valid') && $found))
		{
			$error = $this->getParameter('choices_error');
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
		$this->setParameter('choices', array());
		$this->setParameter('choices_error', 'Invalid value');
		$this->setParameter('sensitive', false);
		$this->setParameter('valid', true);

		/* バリデータパラメータをセットする */
		parent::initialize($context, $parameters);

		/* 大小文字を区別しない場合、選択データ(設定値)を小文字に変換する */
		if ($this->getParameter('sensitive') == false) {
			$choice = $this->getParameter('choices');
			$count = sizeof($choice);

			for ($i = 0; $i < $count; $i++)
			{
				if (is_string($choices[$i])) {
					$choice[$i] = strtolower($choices[$i]);
				}
			}
			$this->setParameter('choices', $choice);
		}

		return true;
	}
}

?>