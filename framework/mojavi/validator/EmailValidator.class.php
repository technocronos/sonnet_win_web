<?php
/**
 * E�᡼�륢�ɥ쥹�����å�
 * E�᡼�륢�ɥ쥹�ȷ��(�Ǿ�/����)������å����뤿��ΥХ�ǡ�����󥿥���
 * 
 * (validate����ե�����ѥ�᡼��)
 * email_error������E�᡼�륢�ɥ쥹�ν񼰤Ǥʤ�����ɽ�������å�����
 * max������E�᡼�륢�ɥ쥹�κ�����(ͭ���͡�0��)(�ǥե���ȡ�-1)
 * max_error�������������򥪡��Ф�������ɽ�����륨�顼��å�����
 * min������E�᡼�륢�ɥ쥹�κǾ����(ͭ���͡�0��)(�ǥե���ȡ�-1)
 * min_error�������Ǿ�����򥪡��Ф�������ɽ�����륨�顼��å�����
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
     * �Х�ǡ�������¹Ԥ���
     * @access public
     * @param string &$value �Х�ǡ�����󤹤�ǡ���
     * @param string &$error ���顼����(���顼��å���������Ǽ�����)
     * @return boolean �Х�ǡ��������
     */
	public function execute (&$value, &$error)
	{
		/* E�᡼�륢�ɥ쥹�ν񼰤�����å����� */
		if (!preg_match('/^[0-9a-z_.\-\+]*@[0-9a-z\-]+(\.[0-9a-z\-]+)*'.
			'\.(com|net|org|edu|gov|mil|int|info|biz|name|pro|museum|aero|coop|[a-z][a-z])$/i', $value)) {
			$error = $this->getParameter('email_error');
			return false;
		}

		/* E�᡼�륢�ɥ쥹��ʸ�����򥻥åȤ��� */
		$length = strlen($value);

		/* E�᡼�륢�ɥ쥹�κǾ�����򥪡��Ф��Ƥ��ʤ��������å����� */
		if ($this->getParameter('min') > -1 && $length < $this->getParameter('min')) {
			$error = $this->getParameter('min_error');
			return false;
		}

		/* E�᡼�륢�ɥ쥹�κ������򥪡��Ф��Ƥ��ʤ��������å����� */
		if ($this->getParameter('max') > -1 && $length > $this->getParameter('max')) {
			$error = $this->getParameter('max_error');
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
		$this->setParameter('email_error', 'Invalid email address');
		$this->setParameter('max', -1);
		$this->setParameter('max_error', 'Email address is too long');
		$this->setParameter('min', -1);
		$this->setParameter('min_error', 'Email address is too short');

		/* �Х�ǡ����ѥ�᡼���򥻥åȤ��� */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>