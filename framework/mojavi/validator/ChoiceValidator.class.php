<?php
/**
 * ��������å�
 * ����ǡ���(������)�������ͤ���Ӥ��뤿��ΥХ�ǡ�����󥿥���
 * 
 * (validate����ե�����ѥ�᡼��)
 * choices����������ǡ���(array)
 * choices_error�������Х�ǡ�����󥨥顼��ȯ����������ɽ�������å�����
 * sensitive�������羮ʸ������̤��뤫(�ǥե���ȡ�false)
 * valid����������ǡ����Τ����줫�Υǡ����������å�����(�ǥե���ȡ�true)
 * 	(true����������ǡ����Ȱ��פ��롢false����������ǡ����Ȱ��פ��ʤ�)
 * 
 * ��validate�ե�������������
 * [methods]
 *	get  = "colName"
 *	post = "colName"
 * 
 * [names]
 * 	colName.required		=	"yes"
 *	colName.required_msg	=	"colName�����Ϥ��Ƥ���������"
 *	colName.validators		=	"colNameValidator"
 * 
 * [colNameValidator]
 *	class = "ChoiceValidator"
 *	param.choices.0			=	"0"
 *	param.choices.1			=	"1"
 *	param.choices_error		=	"colName�����Ϥ��Ƥ���������"
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
     * �Х�ǡ�������¹Ԥ���
     * @access public
     * @param string &$value �Х�ǡ�����󤹤�ǡ���
     * @param string &$error ���顼����(���顼��å���������Ǽ�����)
     * @return boolean �Х�ǡ��������
     */
	public function execute (&$value, &$error)
	{
		$found = false;

		/* �羮ʸ������̤��ʤ���硢�����ͤ�ʸ�����Ѵ����� */
		if (!$this->getParameter('sensitive')) {
			$newValue = strtolower($value);
		} else {
			$newValue = &$value;
		}

		/* ����ǡ���(������)�������ͤ�¸�ߤ��뤫������å����� */
		if (in_array($newValue, $this->getParameter('choices'))) {
			$found = true;
		}

		/* ��������å����顼�Ǥ���С����顼��å������򥻥åȤ��� */
		if (($this->getParameter('valid') && !$found) ||
			(!$this->getParameter('valid') && $found))
		{
			$error = $this->getParameter('choices_error');
			return false;
		}

		return true;
	}

	/**
     * �������
     * @access public
     * @param Object $contextt context
     * @param array $parameters �Х�ǡ����ѥ�᡼��
     * @return boolean �������
     */
	public function initialize ($context, $parameters = null)
	{
		/* �ǥե�����ͥ��å� */
		$this->setParameter('choices', array());
		$this->setParameter('choices_error', 'Invalid value');
		$this->setParameter('sensitive', false);
		$this->setParameter('valid', true);

		/* �Х�ǡ����ѥ�᡼���򥻥åȤ��� */
		parent::initialize($context, $parameters);

		/* �羮ʸ������̤��ʤ���硢����ǡ���(������)��ʸ�����Ѵ����� */
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