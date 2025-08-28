<?php
/**
 * �ե���������å�
 * �ե����륵�����������������ʤɤ�����å����뤿��ΥХ�ǡ�����󥿥���
 * 
 * (validate����ե�����ѥ�᡼��)
 *
 * types����������������������(array)
 *				  1 = GIF
 *				  2 = JPG
 *				  3 = PNG
 *				  4 = SWF
 *				  5 = PSD
 *				  6 = BMP
 *				  7 = TIFF(intel byte order)
 *				  8 = TIFF(motorola byte order)
 *				  9 = JPC
 *				  10 = JP2
 *				  11 = JPX
 *				  12 = JB2
 *				  13 = SWC
 *				  14 = IFF
 *				  15 = WBMP
 *				  16 = XBM
 * types_error���������������פˤʤ�����ɽ�����륨�顼��å�����
 *
 * max������������������Х��ȥ�����(ͭ���͡�1��)(�ǥե���ȡ�null)
 * max_error������������Х��ȥ���������ä�����ɽ�����륨�顼��å�����
 * min���������������Ǿ��Х��ȥ�����(ͭ���͡�1��)(�ǥե���ȡ�null)
 * min_error���������Ǿ��Х��ȥ������򲼲�ä�����ɽ�����륨�顼��å�����
 *
 * max_height ��������������ĥ�����(ͭ���͡�0��)(�ǥե���ȡ�null)
 * max_height_err ����������ĥ���������ä�����ɽ�����륨�顼��å�����
 * min_height �����������Ǿ��ĥ�����(ͭ���͡�0��)(�ǥե���ȡ�null)
 * min_height_err �������Ǿ��ĥ������򲼲�ä�����ɽ�����륨�顼��å�����
 *
 * max_width���������������粣������(ͭ���͡�0��)(�ǥե���ȡ�null)
 * max_width_err�����������粣����������ä�����ɽ�����륨�顼��å�����
 * min_width�������������Ǿ���������(ͭ���͡�0��)(�ǥե���ȡ�null)
 * min_width_err���������Ǿ����������򲼲�ä�����ɽ�����륨�顼��å�����
 * 
 * quarantine ���������åץ��ɥե����뤬�����륹�˴������Ƥ��ʤ����ʥǥե���ȡ�false��
 * quarantine_err �����åץ��ɥե����뤬�����륹�˴������Ƥ������Υ��顼��å�����
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 */
class FileValidator extends Validator
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
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
		// MAX_FILE_SIZE���������
		$context = &$this->getContext();
		$request = &$context->getRequest();
		$maxFileSize = $request->getParameter('MAX_FILE_SIZE', ini_get('upload_max_filesize'));
		if (is_numeric($maxFileSize)) {
			$maxFileSize = sprintf('%d�ͥХ���', $maxFileSize / 1024 / 1024);
		}

		// �ե����뤬���åץ��ɤ���ʤ��ä��Τǥ����å����ʤ�
		if (empty($value['name'])) {
			return true;
		}
		
		// ���åץ��ɤΥ��顼�����å�
		switch ($value['error']) {
			case UPLOAD_ERR_OK : // �ե����뤬���åץ��ɤ��줿
				// ���⤷�ʤ�
				break;
			case UPLOAD_ERR_INI_SIZE : //�ե����륵���������С�
			case UPLOAD_ERR_FORM_SIZE : //�ե����륵���������С��ʥե������
				$error = sprintf("�ե����륵�������礭�᤮�ޤ�������%s�Ǥ��ꤤ���ޤ���", $maxFileSize);
				return false;
			case UPLOAD_ERR_PARTIAL : //�Դ����ե�����
			case UPLOAD_ERR_NO_FILE : //�ե����뤬���åץ��ɤ���ʤ��ä�
				$error = '�ե����륢�åץ��ɤ˼��Ԥ��ޤ��������åץ��ɤ�ľ���Ʋ�������';
				return false;
			case UPLOAD_ERR_CANT_WRITE : // �ե��������ߥ��顼��ȯ�����ޤ���
				throw new FileException('�ե��������ߥ��顼��ȯ�����ޤ������ե�����ν���߸��¤�����å����Ƥ���������');
			case UPLOAD_ERR_NO_TMP_DIR : //�ƥ�ݥ��ե������¸�ߤ��ʤ�
				throw new FileException('�ƥ�ݥ��ե������¸�ߤ��ޤ��󡣥ƥ�ݥ��ե��������������Ƥ��뤫�����å����Ƥ���������');
		}

		// ���������ե���������å���Ԥ���������
		
		// �����륹�����å���Ԥ�
		if ($this->getParameter('quarantine') && extension_loaded('clamav')) {
			cl_setlimits(5, 1000, 200, 0, $maxFileSize);    	
			$message = cl_scanfile($value['tmp_name']);
			if ($message) {
				$error = $this->getParameter('quarantine_err');
				return false;
			}
		}
	
		// �ե����������������
		$imagesize = getimagesize($value['tmp_name']);
		$width = $imagesize[0];
		$height = $imagesize[1];
		$type = $imagesize[2];
		$size = $value['size'];
		
		$types = $this->getParameter('types');
		$max = $this->getParameter('max');
		$min = $this->getParameter('min');
		$max_height = $this->getParameter('max_height');
		$min_height = $this->getParameter('min_height');
		$max_width = $this->getParameter('max_width');
		$min_width = $this->getParameter('min_width');
		
		// ���������ץ����å�
		if ($types != null) {
			if (!in_array($type, $types)) {
				$error = $this->getParameter('types_error');
				return false;
			}
		}

		// �ե����륵���������å�
		if ($max != null && $size > $max) {
			// too large
			$error = $this->getParameter('max_error');
			return false;
		}
		if ($min != null && $size < $min) {
			// too small
			$error = $this->getParameter('min_error');
			return false;
		}
		
		// �����ĥ����������å�
		if ($max_height != null && $height > $max_height) {
			// too long
			$error = $this->getParameter('max_height_err');
			return false;
		}
		if ($min_height != null && $height < $min_height) {
			// too short
			$error = $this->getParameter('min_height_err');
			return false;
		}
		
		// �����������������å�
		if ($max_width != null && $width > $max_width) {
			// too long
			$error = $this->getParameter('max_width_err');
			return false;
		}
		if ($min_width != null && $width < $min_width) {
			// too short
			$error = $this->getParameter('min_width_err');
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
		// �ǥե�����ͥ��å�
		$this->setParameter('types', null);
		$this->setParameter('types_error', 'Invalid image type');
		$this->setParameter('max', null);
		$this->setParameter('max_error', 'Size is too large');
		$this->setParameter('min',null);
		$this->setParameter('min_error', 'Size is too small');
		$this->setParameter('max_height', null);
		$this->setParameter('max_height_err', 'Height is too long');
		$this->setParameter('max_width',null);
		$this->setParameter('max_width_err', 'Width is too long');
		$this->setParameter('min_height', null);
		$this->setParameter('min_height_err', 'Height is too short');
		$this->setParameter('min_width',null);
		$this->setParameter('min_width_err', 'Width is too short');
		$this->setParameter('quarantine', false);
		$this->setParameter('quarantine_err', 'Infected file');

		// �Х�ǡ����ѥ�᡼���򥻥åȤ���
		parent::initialize($context, $parameters);

		return true;

	}

}

?>