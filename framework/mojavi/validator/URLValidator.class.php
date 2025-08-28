<?php
/**
 * URL�����å�
 * URL�ȷ��(�Ǿ�/����)������å����뤿��ΥХ�ǡ�����󥿥���
 * 
 * (validate����ե�����ѥ�᡼��)
 * url_error������URL�ν񼰤Ǥʤ�����ɽ�������å�����
 * max������URL�κ�����(ͭ���͡�0��)(�ǥե���ȡ�-1)
 * max_error�������������򥪡��Ф�������ɽ�����륨�顼��å�����
 * min������URL�κǾ����(ͭ���͡�0��)(�ǥե���ȡ�-1)
 * min_error�������Ǿ�����򥪡��Ф�������ɽ�����륨�顼��å�����
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
     * �Х�ǡ�������¹Ԥ���
     * @access public
     * @param string &$value �Х�ǡ�����󤹤�ǡ���
     * @param string &$error ���顼����(���顼��å���������Ǽ�����)
     * @return boolean �Х�ǡ��������
     */
	public function execute (&$value, &$error)
	{
		/* URL�ν񼰤�����å����� */
		if (!preg_match("/^(http|https):\/\/[A-Za-z0-9;\?:@&=+\$,\-_\.!~\*'\(\)%]+(:\d+)?" . 
			"(\/?[A-Za-z0-9;\/\?:@&=+\$,\-_\.!~\*'\(\)%])*$/",  $value)) {
			$error = $this->getParameter('url_error');
			return false;
		}

		/* URL��ʸ�����򥻥åȤ��� */
		$length = strlen($value);

		/* URL�κǾ�����򥪡��Ф��Ƥ��ʤ��������å����� */
		if ($this->getParameter('min') > -1 && $length < $this->getParameter('min')) {
			$error = $this->getParameter('min_error');
			return false;
		}

		/* URL�κ������򥪡��Ф��Ƥ��ʤ��������å����� */
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
		$this->setParameter('url_error', 'Invalid URL');
		$this->setParameter('max', -1);
		$this->setParameter('max_error', 'URL is too long');
		$this->setParameter('min', -1);
		$this->setParameter('min_error', 'URL is too short');

		/* �Х�ǡ����ѥ�᡼���򥻥åȤ��� */
		parent::initialize($context, $parameters);

		return true;
	}
}
?>