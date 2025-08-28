<?php
/**
 * ���ե����å�
 * ���եǡ����ν񼰤�ͭ�������եǡ���������å����뤿��ΥХ�ǡ�����󥿥���
 * (��)
 * ����1��YY(YY)/MM/DD
 * ����2��YY(YY)-MM-DD
 * ����3��DD.MM.YY(YY)
 * ����4��DD/MM/YY(YY)
 * 
 * ��YY(YY)����������ǯ4��⤷���ϡ�����ǯ2��
 *	(70�ʾ��1900ǯ�桢69�ʲ���2000ǯ��Ȥ���)
 * ��MM��������2��
 * ��DD��������2��
 * 
 * (validate����ե�����ѥ�᡼��)
 * format_error���������եǡ����ν񼰤Ǥʤ�����ɽ�����륨�顼��å�����
 * date_error���������եǡ������ͤ�ͭ���Ǥʤ�����ɽ�����륨�顼��å�����
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
     * �Х�ǡ�������¹Ԥ���
     * @access public
     * @param string &$value �Х�ǡ�����󤹤�ǡ���
     * @param string &$error ���顼����(���顼��å���������Ǽ�����)
     * @return boolean �Х�ǡ��������
     */
	public function execute (&$value, &$error)
	{
		/* ���դν񼰤�����å��������եǡ������������ */
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

		/* ����ǯ2���б� */
		if ($year < 70) {
			$year += 2000;
		} elseif ($year < 100) {
			$year += 1900;
		}
		
		/* ���եǡ����Ǥ��뤫�����å����� */
		if (!checkdate($month, $day, $year)) {
			$error = $this->getParameter('date_error');
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
		$this->setParameter('format_error', 'Invalid format');
		$this->setParameter('date_error', 'Invalid date');

		/* �Х�ǡ����ѥ�᡼���򥻥åȤ��� */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>