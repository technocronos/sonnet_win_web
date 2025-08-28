<?php
/**
 * ������ӥ����å�
 * ¾���Ϲ��ܥǡ��������ϥǡ��������פ��뤫���ǧ���뤿��ΥХ�ǡ�����󥿥���
 * 
 * (validate����ե�����ѥ�᡼��)
 * comparison_field������¾���Ϲ��ܥǡ�����HTML�ե������̾�����ꤹ��
 * match_error������¾���Ϲ��ܥǡ����Ȱ��פ��ʤ�����ɽ�����륨�顼��å�����
 * sensitive�������羮ʸ������̤��뤫(�ǥե���ȡ�true)
 * is_equal������¾���Ϲ��ܥǡ��������ϥǡ��������פ��뤫(�ǥե���ȡ�true)
 * 	(true������¾���Ϲ��ܤȰ��פ��롢false������¾���Ϲ��ܤȰ��פ��ʤ�)
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
     * �Х�ǡ�������¹Ԥ���
     * @access public
     * @param string &$value �Х�ǡ�����󤹤�ǡ���
     * @param string &$error ���顼����(���顼��å���������Ǽ�����)
     * @return boolean �Х�ǡ��������
     */
	public function execute (&$value, &$error)
	{
		/* REQUEST�ǡ�������¾���Ϲ��ܥǡ������������ */
		$context = &$this->getContext();
		$request = &$context->getRequest();
		$comparisonField = 
			$request->getParameter($this->getParameter('comparison_field'));
	
		/* ���ϥǡ�����¾���Ϲ��ܥǡ�������Ӥ��� */
		if ($this->getParameter('sensitive')) {
			$result = strcmp($value, $comparisonField);
		} else {
			$result = strcasecmp($value, $comparisonField);
		}

		/* ���פ��뤳�Ȥ�����å����� */	
		if ($this->getParameter('is_equal') && $result == 0) {
			return true;
		}

		/* ���פ��ʤ����Ȥ�����å����� */	
		if (!$this->getParameter('is_equal') && $result != 0) {
			return true;
		}

		/* ¾���ܤȤ���Ӥ˼��Ԥ����饨�顼��å������򥻥åȤ��� */
		$error = $this->getParameter('match_error');
		
		return false;
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
		$this->setParameter('comparison_field', '');
		$this->setParameter('sensitive', true);
		$this->setParameter('is_equal', true);
		$this->setParameter('match_error', 'The fields do not match');
		
		/* �Х�ǡ����ѥ�᡼���򥻥åȤ��� */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>